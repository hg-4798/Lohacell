<?php
header("Content-type: text/html; charset=utf-8");
include_once("MotHead.lib.php");
/*
문자 발송 결과 리포트 요청
*/
    
    $MotHead=new MotHead_Send();
    
    // $R_SR=$MotHead->Report("요청건의 고유코드");
    $R_SR=$MotHead->Report("000000");
         
         
         $R_status=$R_SR['status']; ///리포트 서버 연결 여부 true , false
         $R_result=$R_SR['result']; ///데이터 가져오기  0:실패 1:성공
         $R_msg=$R_SR['msg']; //실패시 에러 메세지
         $R_process=$R_SR['process']; ///  0=>"발송준비중",1=>"발송중",2=>"발송완료"     
         $R_count=$R_SR['count']; ///발송요청건수
         $R_ok_count=$R_SR['ok_count']; ///현재 발송 성공건수
         $R_fail_count=$R_SR['fail_count']; ///현재 발송 실패건수

         
         if ($R_status&&$R_result==1){
             echo("리포트데이터를 성공적으로 가져 왔습니다.");
             echo var_dump($R_SR);
         }else{
             echo("리포트데이터를 가져오는데 실패하였습니다."); 
         }
         
         

         $R_SR=$MotHead->Status("000000");
         
         
         $R_status=$R_SR['status']; ///리포트 서버 연결 여부 true , false
         $R_result=$R_SR['result']; ///데이터 가져오기  0:실패 1:성공
         $R_msg=$R_SR['msg']; //실패시 에러 메세지
         $R_pointsum=$R_SR['point_sum']; ///  현재보유포인트
         $R_left_count=$R_SR['left_count']; ///발송요청건수


         
         if ($R_status&&$R_result==1){
             echo("리포트데이터를 성공적으로 가져 왔습니다.");
             echo var_dump($R_SR);
         }else{
             echo("리포트데이터를 가져오는데 실패하였습니다."); 
         }         
?>