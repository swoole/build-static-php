#!/bin/bash

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


if [ -f ./make-env.sh ] ;then
  source ./make-env.sh
else
  echo 'no found file make-env.sh '
  exit 0
fi


if [[ ${CUSTOM_PHP_VERSION_ID} -lt 8020  &&   ${CUSTOM_PHP_VERSION_ID} -ge 7030 ]] ; then
    echo '发现不匹配的链接库'

    test -d ${GLOBAL_PREFIX}/openssl && rm -rf ${GLOBAL_PREFIX}/openssl
    test -d ${BUILD_DIR}/openssl && rm -rf ${BUILD_DIR}/openssl

    test -d ${GLOBAL_PREFIX}/nghttp2 && rm -rf ${GLOBAL_PREFIX}/nghttp2
    test -d ${BUILD_DIR}/nghttp2 && rm -rf ${BUILD_DIR}/nghttp2

    test -d ${GLOBAL_PREFIX}/nghttp3 && rm -rf ${GLOBAL_PREFIX}/nghttp3
    test -d ${BUILD_DIR}/nghttp3 && rm -rf ${BUILD_DIR}/nghttp3

    test -d ${GLOBAL_PREFIX}/ngtcp2 && rm -rf ${GLOBAL_PREFIX}/ngtcp2
    test -d ${BUILD_DIR}/ngtcp2 && rm -rf ${BUILD_DIR}/ngtcp2

    test -d ${GLOBAL_PREFIX}/pgsql && rm -rf ${GLOBAL_PREFIX}/pgsql
    test -d ${BUILD_DIR}/pgsql && rm -rf ${BUILD_DIR}/pgsql

    test -d ${GLOBAL_PREFIX}/curl && rm -rf ${GLOBAL_PREFIX}/curl
    test -d ${BUILD_DIR}/curl && rm -rf ${BUILD_DIR}/curl

    test -d ${GLOBAL_PREFIX}/libssh2 && rm -rf ${GLOBAL_PREFIX}/libssh2
    test -d ${BUILD_DIR}/libssh2 && rm -rf ${BUILD_DIR}/libssh2

    test -d ${GLOBAL_PREFIX}/imagemagick && rm -rf ${GLOBAL_PREFIX}/imagemagick
    test -d ${BUILD_DIR}/imagemagick && rm -rf ${BUILD_DIR}/imagemagick

    test -d ${GLOBAL_PREFIX}/libzip && rm -rf ${GLOBAL_PREFIX}/libzip
    test -d ${BUILD_DIR}/libzip && rm -rf ${BUILD_DIR}/libzip
else
  echo '未发现，不匹配的链接库 '
fi