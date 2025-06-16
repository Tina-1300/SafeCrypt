@echo off
IF EXIST vendor (
    echo Suppression du dossier vendor...
    rmdir /s /q vendor
    exit
)

composer dump-autoload -o