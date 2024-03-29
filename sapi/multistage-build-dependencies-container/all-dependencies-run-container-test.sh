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

cd ${__PROJECT__}/
ARCH=$(uname -m)

IMAGE_FILE="${__PROJECT__}/var/all-dependencies-container.txt"
if test -f $IMAGE_FILE; then
  {
    docker stop swoole-cli-alpine-dev
    sleep 5
  } || {
    echo $?
  }
  IMAGE=$(cat ${IMAGE_FILE})
  docker run --rm --name swoole-cli-alpine-dev -d -v ${__PROJECT__}:/work -w /work ${IMAGE} tail -f /dev/null
else
  echo 'no  container image'
fi
