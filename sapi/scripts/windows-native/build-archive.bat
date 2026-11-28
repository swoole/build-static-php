@echo off

setlocal enabledelayedexpansion

rem show current file location
echo %~dp0
cd /d %~dp0
cd /d ..\..\..\

set "PATH=%__PROJECT__%\var\native-build\php-sdk-binary-tools\bin\;%__PROJECT__%\var\native-build\php-sdk-binary-tools\msys2\bin;%PATH%;"

set "__PROJECT__=%cd%"
echo %cd%
cd %__PROJECT__%

dumpbin /DEPENDENTS ".\var\native-build\php-src\x64\Release_TS\php.exe"

set "RELEASE_PHP_VERSION="
for /f "tokens=2" %%V in ('.\var\native-build\php-src\x64\Release_TS\php.exe -v 2^>nul ^| findstr /b "PHP "') do (
    if not defined RELEASE_PHP_VERSION set "RELEASE_PHP_VERSION=%%V"
)

echo PHP_VERSION=!RELEASE_PHP_VERSION!
mkdir bin
copy /Y .\var\native-build\php-src\x64\Release_TS\php.exe bin\php.exe
copy /Y .\var\native-build\php-src\x64\Release_TS\php.exe php.exe

7z a -tzip php-cli-v%RELEASE_PHP_VERSION%-windows-x64.zip php.exe

dir

exit /b 0

cd /d %__PROJECT__%
endlocal



