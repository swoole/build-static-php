@echo off

setlocal
rem show current file location
echo %~dp0
cd /d %~dp0
cd /d ..\..\..\

set "PATH=%__PROJECT__%\var\native-build\php-sdk-binary-tools\bin\;%__PROJECT__%\var\native-build\php-sdk-binary-tools\msys2\bin;%PATH%;"

set "__PROJECT__=%cd%"
echo %cd%
cd %__PROJECT__%

dumpbin /DEPENDENTS ".\var\native-build\php-src\x64\Release_TS\php.exe"

7z a -tzip php-cli-v8.2.32-windows-x64.zip .\var\native-build\php-src\x64\Release_TS\php.exe

exit /b 0

cd /d %__PROJECT__%
endlocal



