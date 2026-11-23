<?php

require __DIR__ . '/vendor/autoload.php';

use SwooleCli\Exception;
use SwooleCli\Preprocessor;

$php_version_tag = trim(file_get_contents(__DIR__ . '/sapi/PHP-VERSION.conf'));
define('BUILD_PHP_VERSION', $php_version_tag);

$homeDir = getenv('HOME');
$p = Preprocessor::getInstance();
$p->parseArguments($argc, $argv);
$buildInContainer = $p->configureBuildEnvironment(__DIR__);
$buildType = $p->getBuildType();


# clean
# clean old make.sh
$p->cleanFile(__DIR__ . '/make.sh');
$p->cleanFile(__DIR__ . '/make-install-deps.sh');
$p->cleanFile(__DIR__ . '/make-env.sh');
$p->cleanFile(__DIR__ . '/make-export-variables.sh');
$p->cleanFile(__DIR__ . '/make-download-box.sh');
$p->cleanFile(__DIR__ . '/cppflags.log');
$p->cleanFile(__DIR__ . '/ldflags.log');
$p->cleanFile(__DIR__ . '/libs.log');
$p->cleanFile(__DIR__ . '/configure.backup');


// Compile directly on the host machine, not in the docker container
if ($p->getInputOption('without-docker') || ($p->isMacos()) || ($p->isLinux() && (!is_file('/.dockerenv')))) {
    $p->setWorkDir(__DIR__);
    $p->setBuildDir(__DIR__ . '/thirdparty');
}

// 在宿主机上直接创建 thirdparty 目录，并确保当前用户可写。
// 容器内构建始终是 root，可以往里写任何内容不受影响；依赖源码由宿主机下载，
// 若 thirdparty 由容器 root 创建（0755），宿主机普通用户将无法写入。
// 这里不假定执行 prepare.php 的具体用户，统一放宽为 0777 让所有用户可写。
$thirdpartyDir = __DIR__ . '/thirdparty';
if (!is_dir($thirdpartyDir)) {
    mkdir($thirdpartyDir, 0777, true);
    chmod($thirdpartyDir, 0777);
} elseif (!is_writable($thirdpartyDir)) {
    // thirdparty 已存在但当前用户不可写（例如之前由容器 root 创建）。
    // 普通用户无法修改 root 所有的目录，chmod 会失败，此时给出通用提示。
    @chmod($thirdpartyDir, 0777);
    if (!is_writable($thirdpartyDir)) {
        fwrite(STDERR, "thirdparty 目录不可写：{$thirdpartyDir}" . PHP_EOL
            . "请在构建容器内执行：chmod 777 /work/thirdparty" . PHP_EOL);
        exit(1);
    }
}

$varDir = __DIR__ . '/var';
if (!is_dir($varDir)) {
    mkdir($varDir, 0777, true);
    chmod($varDir, 0777);
} elseif (!is_writable($varDir)) {
    @chmod($varDir, 0777);
    if (!is_writable($varDir)) {
        fwrite(STDERR, "var 目录不可写：{$varDir}" . PHP_EOL
            . "请在构建容器内执行：chmod 777 /work/var" . PHP_EOL);
        exit(1);
    }
}

// 下载 php-src 源码（按 PHP-VERSION.conf 的版本，下载/解压到 var/php-<version>；
// 容器内通过挂载即 /work/var/php-<version>）
require __DIR__ . '/sapi/scripts/download-php-src-archive.php';

// Sync code from php-src
$p->setPhpSrcDir($p->getWorkDir() . '/var/php-' . BUILD_PHP_VERSION);


// Download swoole-src
if (!is_dir(__DIR__ . '/ext/swoole')) {
    //shell_exec('bash ' . __DIR__ . '/sapi/scripts/download-swoole-src-archive.sh');
}


if ($p->getInputOption('with-override-default-enabled-ext')) {
    $p->setExtEnabled([]);
}

if ($p->getInputOption('with-global-prefix')) {
    $p->setGlobalPrefix($p->getInputOption('with-global-prefix'));
}

if ($p->getInputOption('with-parallel-jobs')) {
    $p->setMaxJob(intval($p->getInputOption('with-parallel-jobs')));
}

$buildType = $p->getBuildType();
if ($p->getInputOption('with-build-type')) {
    $buildType = $p->getInputOption('with-build-type');
    $p->setBuildType($buildType);
}
define('SWOOLE_CLI_BUILD_TYPE', $buildType);
define('SWOOLE_CLI_GLOBAL_PREFIX', $p->getGlobalPrefix());

if ($p->getInputOption('with-http-proxy')) {
    $http_proxy = $p->getInputOption('with-http-proxy');
    $proxyConfig = <<<EOF
export HTTP_PROXY={$http_proxy}
export HTTPS_PROXY={$http_proxy}
EOF;
    $proxyConfig .= PHP_EOL;
    $proxyConfig .= <<<'EOF'
NO_PROXY="127.0.0.0/8,10.0.0.0/8,100.64.0.0/10,172.16.0.0/12,192.168.0.0/16"
NO_PROXY="${NO_PROXY},::1/128,fe80::/10,fd00::/8,ff00::/8"
NO_PROXY="${NO_PROXY},.aliyuncs.com,.aliyun.com,.tencent.com"
NO_PROXY="${NO_PROXY},.tsinghua.edu.cn,.ustc.edu.cn,.npmmirror.com"
NO_PROXY="${NO_PROXY},ftpmirror.gnu.org"
NO_PROXY="${NO_PROXY},gitee.com,gitcode.com"
NO_PROXY="${NO_PROXY},.myqcloud.com,.swoole.com"
NO_PROXY="${NO_PROXY},dl-cdn.alpinelinux.org"
NO_PROXY="${NO_PROXY},deb.debian.org,security.debian.org"
NO_PROXY="${NO_PROXY},archive.ubuntu.com,security.ubuntu.com"
NO_PROXY="${NO_PROXY},pypi.python.org,bootstrap.pypa.io"
export NO_PROXY="${NO_PROXY},localhost"


EOF;
    $p->setProxyConfig($proxyConfig, $http_proxy);
}


if ($p->isMacos()) {
    //$p->setExtraLdflags('-undefined dynamic_lookup');
    $p->setExtraLdflags('');
    exec("brew --prefix 2>&1", $output, $result_code);
    if ($result_code == 0) {
        $homebrew_prefix = trim(implode(' ', $output));
    } else {
        $homebrew_prefix = "";
    }
    $p->withBinPath($homebrew_prefix . '/opt/flex/bin')
        ->withBinPath($homebrew_prefix . '/opt/bison/bin')
        ->withBinPath($homebrew_prefix . '/opt/libtool/bin')
        ->withBinPath($homebrew_prefix . '/opt/m4/bin')
        ->withBinPath($homebrew_prefix . '/opt/automake/bin/')
        ->withBinPath($homebrew_prefix . '/opt/autoconf/bin/')
        ->withBinPath($homebrew_prefix . '/opt/gettext/bin')
        ->setLinker('ld');
    $p->setLogicalProcessors('$(sysctl -n hw.ncpu)');
} else {
    $p->setLinker('ld.lld');
    $p->setLogicalProcessors('$(nproc 2> /dev/null)');
}

// 下载/更新 swoole-src（脚本内部按 SWOOLE-VERSION.conf 判断是否需要 checkout）
// 用 passthru 让脚本输出与退出码透传，下载失败（如网络超时）时能看到具体错误
if (!$p->isMobileTarget()) {
    $swoole_download_status = 0;
    passthru('bash ' . __DIR__ . '/sapi/scripts/download-swoole-src-archive.sh', $swoole_download_status);
    if ($swoole_download_status !== 0) {
        fwrite(STDERR, "download swoole-src failed with exit code: {$swoole_download_status}" . PHP_EOL);
        exit($swoole_download_status);

    }
}

$p->setExtraCflags(' -Os -fno-openmp');

// Generate make.sh
echo "build in container : " . ($buildInContainer ? 'yes' : 'no') . PHP_EOL;
echo "workDir   : " . $p->getWorkDir() . PHP_EOL;
echo "buildDir  : " . $p->getBuildDir() . PHP_EOL;
echo "phpSrcDir : " . $p->getPhpSrcDir() . PHP_EOL;
echo PHP_EOL;
echo "提示：Linux 下默认按容器内构建生成 make.sh（workDir=/work）；" . PHP_EOL;
echo "      若要在宿主机上直接编译，请显式指定 --without-docker" . PHP_EOL;
echo PHP_EOL;

$p->execute();
