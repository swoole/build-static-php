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
export PATH="${__PROJECT__}/runtime:$PATH"
alias php="php -d curl.cainfo=${__PROJECT__}/runtime/cacert.pem -d openssl.cafile=${__PROJECT__}/runtime/cacert.pem"

php bootstrap.php
