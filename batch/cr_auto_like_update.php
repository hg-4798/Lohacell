#!/usr/local/php/bin/php
<?php
#######################################################################################
# FileName          : cr_auto_like_update.php
# Desc              : 매시 실행되어 일괄 like  조정
# Last Updated      : 2018-06-18
# By                : Parh Heesob
##!/usr/local/php/bin/php
# [deco@deco1 batch]$ ./run_auto_like_update.sh 
#######################################################################################

$Dir="../";
include ($Dir."lib/init.php");
include ($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


    BeginTrans();
    try {
	$sql = "delete from tblproduct_like";
        $result = pmysql_query($sql, get_db_conn());
	$sql = "insert into tblproduct_like 
	select hott_code productcode,COUNT( tl.hott_code )AS hott_cnt from tblhott_like tl WHERE tl.section = 'product' and hott_code!='' 
	group by hott_code";
        $result = pmysql_query($sql, get_db_conn());
    } catch (Exception $e) {
        $flagResult = false;
        RollbackTrans();
    }
    CommitTrans();
