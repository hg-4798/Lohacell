#!/opt/remi/php56/root/bin/php
<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

#######################################################################################
# FileName          : cr_auto_day.php
# Desc              : 일 배치 실행 파일
# Last Updated      : 2018-10-23
# By                : Parh Heesob
##!/usr/local/php/bin/php
# [deco@deco1 batch]$ ./run_auto_like_update.sh
#######################################################################################

$Dir="../";
include ($Dir."lib/init.php");
include ($Dir."lib/lib.php");



$batch = new BATCH();



//포인트 만료일체크후 무효쳐리- S
$batch->log('포인트 배치시작');
ob_start(); //버퍼시작
$Point = new POINT;
$Point->expire();
$ob_msg = ob_get_contents();
ob_clean();//버퍼클린
$batch->log("포인트 배치끝', $ob_msg);

//포인트 만료일체크후 무효쳐리- E


//마일리지


//



///




$batch->$argv[1]();
