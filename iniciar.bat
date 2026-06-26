@echo off
title Tiranossaurus Rex Server
set PATH=C:\PHP;%PATH%
cd /d "%~dp0"

echo ============================================
echo  Tiranossaurus Rex - Iniciando servidor...
echo ============================================
echo.

where php >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERRO: PHP nao encontrado em C:\PHP
    echo.
    pause
    exit /b 1
)

echo Porta: 54549
echo URL:   http://localhost:54549
echo URL:   http://192.168.1.66:54549
echo.
echo Servidor iniciado! Deixe esta janela aberta.
echo Para parar, feche esta janela ou pressione Ctrl+C
echo ============================================
echo.

php -S 0.0.0.0:54549 -t "%~dp0" "%~dp0router.php"

pause
