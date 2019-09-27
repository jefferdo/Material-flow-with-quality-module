@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../wilgucki/blade-builder/blade
php "%BIN_TARGET%" %*
