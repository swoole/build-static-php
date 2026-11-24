@echo off

setlocal
rem show current file location
echo %~dp0
cd /d %~dp0
cd /d ..\..\..\

set "__PROJECT__=%cd%"
echo %cd%
:: cd /d %__PROJECT__%\var\native-build\php-src\
:: nmake clean

cd /d %__PROJECT__%\var\native-build
if exist "php-src" rmdir /s /q php-src
dir
endlocal
