#!/usr/bin/env bash

set -exu
__DIR__=$(
  cd "$(dirname "$0")"
  pwd
)
__PROJECT__=$(
  cd ${__DIR__}/../../../
  pwd
)
cd ${__PROJECT__}
ROOT=${__PROJECT__}

# download cygwin
# wget https://cygwin.com/setup-x86_64.exe

# cygwin 移动到 bin 目录
# mv setup-x86_64.exe C:/setup-x86_64.exe

## 设置 站点镜像 地址 为
##  https://mirrors.ustc.edu.cn/cygwin/
##  或者
##  https://mirrors.tuna.tsinghua.edu.cn/cygwin/

## 多个包之间，用逗号分隔

setup-x86_64.exe --no-desktop --no-shortcuts --no-startmenu --quiet-mode --disable-buggy-antivirus --site https://mirrors.ustc.edu.cn/cygwin/ --packages make,git,curl,wget,tar,libtool,bison,gcc-g++,autoconf,automake,openssl,libpcre2-devel,libssl-devel,libcurl-devel,libxml2-devel,libxslt-devel,libgmp-devel,ImageMagick,libpng-devel,libjpeg-devel,libfreetype-devel,libwebp-devel,libsqlite3-devel,zlib-devel,libbz2-devel,liblz4-devel,liblzma-devel,libzip-devel,libicu-devel,libonig-devel,libcares-devel,libsodium-devel,libyaml-devel,libMagick-devel,libzstd-devel,libbrotli-devel,libreadline-devel,libintl-devel,libpq-devel,libssh2-devel,libidn2-devel,gettext-devel,coreutils,openssl-devel,zip,unzip

# setup-x86_64.exe  --no-desktop --no-shortcuts --no-startmenu --quiet-mode --disable-buggy-antivirus    --site  http://mirrors.ustc.edu.cn/cygwin/ --packages zip unzip