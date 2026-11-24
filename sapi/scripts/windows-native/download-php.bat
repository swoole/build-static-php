@echo off

setlocal
rem show current file location
echo %~dp0
cd /d %~dp0
cd /d ..\..\..\

set "PATH=%__PROJECT__%\var\native-build\php-sdk-binary-tools\bin\;%__PROJECT__%\var\native-build\php-sdk-binary-tools\msys2\bin;%PATH%;"

set "__PROJECT__=%cd%"
echo %cd%
mkdir  %__PROJECT__%\var\native-build
cd %__PROJECT__%\var\native-build

:: $env:PHP_VERSION = "8.4.28"
:: set "PHP_VERSION=8.4.28"

if not defined PHP_VERSION set "PHP_VERSION=8.4.28"

if not exist "php-src" git clone -b php-%PHP_VERSION% --depth=1 https://github.com/php/php-src.git php-src

exit /b 0

cd /d %__PROJECT__%
endlocal
