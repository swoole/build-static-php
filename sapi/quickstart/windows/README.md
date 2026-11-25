# windows 快速准备构建环境

    1. 原生构建
    2. cygwin 环境 构建
    3. msys2 环境 构建

## windows 环境下 git 配置

```shell

git config --global core.autocrlf false
git config --global core.eol lf
git config --global core.ignorecase false
git config core.ignorecase false # 设置 Git 在 Windows 上也区分大小写

```

### [windows cygwin 环境 构建步骤](../../../docs/Cygwin.md)

### [windows 原生构建步骤](native-build/README.md)

### 构建window  PHP 工具 和 参考

[download windows PHP ](https://windows.php.net/download#php-8.2)
[windows build php 步骤](https://wiki.php.net/internals/windows/stepbystepbuild)
[Latest VC++](https://learn.microsoft.com/en-AU/cpp/windows/latest-supported-vc-redist)
[7zip](https://7-zip.org/)
[visualstudio](https://visualstudio.microsoft.com/zh-hans/downloads/)
[windows-sdk](https://developer.microsoft.com/en-us/windows/downloads/windows-sdk/)

### windows 软连接例子

```bash

mklink composer composer.phar

```
