@echo off

set VENDOR=apps4print
set VENDOR_SHORT=a4p
set MODULE_DIR=apps4print
set PROG_7ZIP="C:\Program Files\7-Zip\7z.exe"
set PROG_PSCP="C:\Program Files (x86)\PuTTY\pscp.exe"
set REMOTE_TARGET_DIR="/var/www/vhosts/modules.apps4print.com/zip/module-versions/%VENDOR%/"


echo ZIP-Datei von Modul erstellen.


echo -------------------------------------------------------------------------------
echo Module:
for %%* in (.) do set _DIR=%%~n*
if "%_DIR%"=="" set _DIR=%CD:~0,2%
echo %_DIR%
set MODULE=%_DIR%



echo -------------------------------------------------------------------------------
echo Version:
FOR /f %%v IN (version.txt) DO set VERSION=%%v
IF NOT EXIST version.txt set /p VERSION=Version:
echo %VERSION%



echo -------------------------------------------------------------------------------
echo create Zip
cd ..
cd ..
%PROG_7zip% a -r -tzip -x!.git -x!%~nx0 -x!_%VENDOR_SHORT%_createCurrentModuleGit.bat -x!.gitignore -x!version.txt %MODULE%-%VERSION%.zip %MODULE_DIR%\vendormetadata.php %MODULE_DIR%\%MODULE%



echo -------------------------------------------------------------------------------
echo upload zip
%PROG_PSCP% -load "3 - Hetzner - EX40 - dev" "%MODULE%-%VERSION%.zip" root@136.243.90.8:"%REMOTE_TARGET_DIR%"



echo -------------------------------------------------------------------------------
echo delete zip
del "%MODULE%-%VERSION%.zip"



pause