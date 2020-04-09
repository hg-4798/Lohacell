<?php
header("Content-type: text/html; charset=utf-8");
include_once("MotHead.lib.php");
/*
문자 발송 요청하기
*/
            
    $MH_rd=array();
    $MH_rd['U_CODE']="발급받은키를 넣어주세요."; ///발급받은 키 사이트의 기업연동->연동하기를 통해 발급받으세요.
    $MH_rd['U_FROM_NUM']="01012341234"; ///발신자번호
    $MH_rd['U_TO_NUM']="01012341234"; //받는사람번호 여러개인경우 , 로 구분 최대 100개
    $MH_rd['U_SUBJECT']="문자 제목 입니다."; //LMS,MMS 일때 문자 제목
    $MH_rd['U_MSG']="문자 내용 입니다."; //문자내용
    
    $MH_rd['U_SEND_DATE']=""; //발송예약일 현재시각 기준 30분 이후로 설정 가능 (Beta)
    $MH_rd['U_TYPE']=1; //문자 종류 1:단문 2:장문 
    $MH_ed['U_VAL']=""; //사용자 임의 변수 U_VAL 로 받을수 있음
    
    $MotHead=new MotHead_Send();
    
    $S_SR=$MotHead->Send($MH_rd);
    
    $S_status=$S_SR['status'];  ///서버통신 성공여부 true , false
    $S_code=$S_SR['code']; //해당 요청건의 고유코드 (Report 받을시 사용)
    $S_result=$S_SR['result']; ///0:실패 1:성공
    $S_msg=$S_SR['msg']; ///에러메세지
    $S_count=$S_SR['count']; //전송 요청 건수 -중복 자동 제거됨
    $S_u_val=$S_SR['U_VAL']; ///사용자 임의변수
    
    if ($S_status&&$S_result==1){
        echo("발송성공".$S_count." 건 / 레포트 코드 : ".$S_code);        
    }else{
        echo("발송실패:".$S_msg);
    }
    
?>