<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(ord($_ShopInfo->getId())==0){
	echo "<script>alert('정상적인 경로로 접근하시기 바랍니다.');</script>";
	exit;
}

$to=$_POST["to"];
$from=$_POST["from"];
$rname=$_POST["rname"];
$subject=$_POST["subject"];
$body=stripslashes($_POST["body"]);
$upfile=$_FILES["upfile"];

if (ord($to) && ord($from) && ord($subject) && ord($body)) {
$mail = new MAIL();
	$tolist=explode(",",$to);
	if(count($tolist)>=3){
        return_json(false,"3명이상 연속발송이 안됩니다.");
    }
	for($i=0;$i<count($tolist);$i++) {
		$tomail=trim($tolist[$i]);
		if(ismail($tomail)) {
            $mail->sendMemberMail($subject, $body, $from, $tomail);
		}
	}
	return_json(true,"메일 발송이 완료되었습니다.");
}
