#!/usr/bin/env bash

set -exu
__DIR__=$(
  cd "$(dirname "$0")"
  pwd
)
__PROJECT__=$(
  cd ${__DIR__}/../../
  pwd
)
cd ${__PROJECT__}

shopt -s expand_aliases
export PATH="${__PROJECT__}/runtime:$PATH"
alias php="php -d curl.cainfo=${__PROJECT__}/runtime/cacert.pem -d openssl.cafile=${__PROJECT__}/runtime/cacert.pem"

php prepare.php --without-docker --skip-download=1 --with-web-ui=1

mkdir -p ${__DIR__}/public/data
cp -f ${__PROJECT__}/var/webui/default_extension_list.json ${__DIR__}/public/data
cp -f ${__PROJECT__}/var/webui/extension_list.json ${__DIR__}/public/data
