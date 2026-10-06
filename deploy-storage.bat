@echo off
REM ============================================================
REM Script untuk upload storage ke server aaPanel via SCP
REM Jalankan dari folder root project: deploy-storage.bat
REM
REM EDIT variabel di bawah sesuai server kamu
REM ============================================================

set SERVER_USER=root
set SERVER_IP=47.129.142.21
set SERVER_PORT=22
set SERVER_PATH=/www/wwwroot/senjaweb.gauld.my.id/storage/app/public
set LOCAL_PATH=storage\app\public

echo.
echo ========================================
echo  Upload Storage ke Server aaPanel
echo ========================================
echo.
echo Server : %SERVER_USER%@%SERVER_IP%:%SERVER_PATH%
echo Lokal  : %LOCAL_PATH%
echo.
echo Pastikan SSH key sudah diatur, lalu tekan Enter...
pause

REM Upload dengan SCP recursive
scp -P %SERVER_PORT% -r "%LOCAL_PATH%\*" "%SERVER_USER%@%SERVER_IP%:%SERVER_PATH%/"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo [OK] Upload berhasil!
    echo.
    echo Sekarang jalankan storage:link di server:
    echo   ssh %SERVER_USER%@%SERVER_IP% "cd %~dp0.. && php artisan storage:link"
) else (
    echo.
    echo [ERROR] Upload gagal. Cek koneksi SSH dan kredensial.
)

pause
