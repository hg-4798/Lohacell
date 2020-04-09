<?php
/**
 * 회원정보 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

//실행파일 직접접근 방지
if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { 
	header("HTTP/1.0 404 Not Found");
	exit;
}


$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$mode = $_POST['mode'];
$mem_id = $_POST['mem_id'];

$Member = new MEMBER;

if($mode == 'password') {
    $tbl = $Member->tbls['member'];
    $new_passwd = $mem_id.date('md');
    $shadata	= "*".strtoupper(SHA1(unhex(SHA1($new_passwd))));
    $sql = "UPDATE {$tbl} SET passwd='{$shadata}' WHERE id='{$mem_id}'";
    //print_r($sql);
    $result = $Member->adodb->Execute($sql);
    if($result){
        return_json(true, "임시 비밀번호가 발급되었습니다.");
    }else{
        return_json(false, "오류가 발생되었습니다.");
    }
}else if($mode == 'memo'){
    $tbl = $Member->tbls['member'];
    $memo = $_POST['memo'];
    $memo = htmlspecialchars($memo);
    $sql = "UPDATE {$tbl} SET memo=? WHERE id=?";
    $bind = array($memo,$mem_id);
    //print_r($sql);
    $result = $Member->adodb->Execute($sql,$bind);
    if($result){
        return_json(true, "메모가 수정되었습니다.");
    }else{
        return_json(false, "오류가 발생되었습니다.");
    }
}


?>