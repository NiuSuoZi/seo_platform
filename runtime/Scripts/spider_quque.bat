@echo off
title 啓動隊列數據
set PHP_PATH=C:\phpstudy_pro\Extensions\php\php7.3.4nts_p\
set PATH = %PATH%;%PHP_PATH%
set PHP_HOME = 
set PHP_RUNS = 
echo 當前運行路徑： %cd%
%PHP_PATH%/php E:\Project\v3\index.php api/background
pause