<?php

use SwooleCli\Preprocessor;
use SwooleCli\Library;
use SwooleCli\Extension;

return function (Preprocessor $p) {

    $p->withExportVariable('PHP_MONGODB_SSL', 'yes');
    $p->withExportVariable('PHP_MONGODB_SSL_CFLAGS', '$(pkg-config --cflags --static libcrypto libssl  openssl)');
    $p->withExportVariable('PHP_MONGODB_SSL_LIBS', '$(pkg-config   --libs   --static libcrypto libssl  openssl)');


    $p->withExportVariable('PHP_MONGODB_ICU', 'yes');
    $p->withExportVariable('PHP_MONGODB_ICU_CFLAGS', '$(pkg-config --cflags --static icu-i18n  icu-io  icu-uc)');
    $p->withExportVariable('PHP_MONGODB_ICU_LIBS', '$(pkg-config   --libs   --static icu-i18n  icu-io  icu-uc)');

    $p->withExportVariable('PHP_MONGODB_ZSTD_CFLAGS', '$(pkg-config --cflags --static libzstd)');
    $p->withExportVariable('PHP_MONGODB_ZSTD_LIBS', '$(pkg-config   --libs   --static libzstd)');

    $p->withExportVariable('PHP_MONGODB_ZLIB_CFLAGS', '$(pkg-config --cflags --static zlib)');
    $p->withExportVariable('PHP_MONGODB_ZLIB_LIBS', '$(pkg-config   --libs   --static zlib)');

    # PHP 8.2 以上 使用clang 编译
    # 需要解决这个问题 https://github.com/mongodb/mongo-php-driver/issues/1445
    # fix PR https://github.com/mongodb/mongo-php-driver/releases/tag/1.16.2

    $options = ' --enable-mongodb ';
    $options .= ' --with-mongodb-system-libs=no ';
    $options .= ' --with-mongodb-ssl=openssl ';
    $options .= ' --with-mongodb-sasl=no ';
    $options .= ' --with-mongodb-icu=yes ';

    $options .= ' --with-mongodb-client-side-encryption=no ';
    $options .= ' --with-mongodb-snappy=no ';

    $mongodb_version = '1.17.2';

    $ext = new Extension('mongodb');

    $ext->withHomePage('https://www.php.net/mongodb')
        ->withHomePage('https://www.mongodb.com/docs/drivers/php/')
        ->withOptions($options)
        //->withAutoUpdateFile()
        //->withPeclVersion('1.6.2') //官方包 解压需要解决这个问题 https://github.com/mongodb/mongo-php-driver/issues/1459
        ->withFile("mongodb-{$mongodb_version}.tgz")
        ->withDownloadScript(
            'mongo-php-driver',
            <<<EOF
        git clone -b {$mongodb_version} --depth=1 --recursive https://github.com/mongodb/mongo-php-driver.git
EOF
        )//->withDependentExtensions('date','json','standar','spl')

    ;
    $depends = ['icu', 'openssl', 'zlib', 'libzstd'];

    //$depends[] = 'libsasl';
    //$depends[] = 'snappy';

    call_user_func_array([$ext, 'withDependentLibraries'], $depends);

    $p->addExtension($ext);
};
