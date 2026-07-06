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
cd ${__DIR__}

shopt -s expand_aliases
export PATH="${__PROJECT__}/runtime/php/:$PATH"
alias php="php -d curl.cainfo=${__PROJECT__}/runtime/php/cacert.pem -d openssl.cafile=${__PROJECT__}/runtime/php/cacert.pem"

php bootstrap.php
