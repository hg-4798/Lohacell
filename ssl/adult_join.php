<?php
if($_SERVER['HTTPS']!="on") {
	header("HTTP/1.0 404 Not Found");
	exit;
}

#���μ��θ� ȸ������
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$shopurl=$_POST["shopurl"];

if(!preg_match("/{$shopurl}/i",$_SERVER['HTTP_REFERER'])) {
	exit;
}


$id=trim($_POST["id"]);
$passwd1=$_POST["passwd1"];
$passwd2=$_POST["passwd2"];
$name=trim($_POST["name"]);
$resno1=trim($_POST["resno1"]);
$resno2=trim($_POST["resno2"]);
$email=trim($_POST["email"]);
$news_mail_yn=$_POST["news_mail_yn"];
$news_sms_yn=$_POST["news_sms_yn"];
$home_tel=trim($_POST["home_tel"]);
$home_post1=trim($_POST["home_post1"]);
$home_post2=trim($_POST["home_post2"]);
$home_addr1=trim($_POST["home_addr1"]);
$home_addr2=trim($_POST["home_addr2"]);
$mobile=trim($_POST["mobile"]);
$office_post1=trim($_POST["office_post1"]);
$office_post2=trim($_POST["office_post2"]);
$office_addr1=trim($_POST["office_addr1"]);
$office_addr2=trim($_POST["office_addr2"]);
$rec_id=trim($_POST["rec_id"]);
$etc=$_POST["etc"];

for($i=0;$i<10;$i++) {
	if(strpos($etc[$i],"=")) {
		alert_go('�߰������� �Է��� �� ���� ���ڰ� ���ԵǾ����ϴ�.',-1);
		break;
	}
}

if(strlen(trim($id))==0) {
	alert_go('���̵� �Է��� �߸��Ǿ����ϴ�.',-1);
} else if(!IsAlphaNumeric($id)) {
	alert_go('���̵�� ����,���ڸ� �����Ͽ� 4~12�� �̳��� �Է��ϼž� �մϴ�.',-1);
} else if(!preg_match("/^[0-9a-zA-Z]{4,12}$/",$id)) {
	alert_go('���̵�� ����,���ڸ� �����Ͽ� 4~12�� �̳��� �Է��ϼž� �մϴ�.',-1);
} else if(strlen($passwd1)==0 || strlen($passwd2)==0) {
	alert_go('��й�ȣ�� �Է��ϼ���.',-1);
} else if(strlen(trim($name))==0) {
	alert_go('�̸� �Է��� �߸��Ǿ����ϴ�.',-1);
} else if(strlen(trim($email))==0) {
	alert_go('�̸����� �Է��ϼ���.',-1);
} else if(!ismail($email)) {
	alert_go('�̸��� �Է��� �߸��Ǿ����ϴ�.',-1);
} else if(strlen(trim($home_tel))==0) {
	alert_go('����ȭ�� �Է��ϼ���.',-1);
} else {
	$procdata=array();
	$sessid=md5(uniqid(rand(),1)).md5(uniqid(rand(),1));
	foreach($_POST as $key=>$val) {
		$procdata[$key]=trim($val);
	}
	file_put_contents($Dir.DataDir."ssl/".$sessid.".temp",serialize($procdata));

	echo "<html><head><title></title></head><body>\n";
	echo "<form name=form1 method=post action=\"http://".$shopurl."/".RootPath.AdultDir."adult_join.php\">\n";
	echo "<input type=hidden name=type value=\"insert\">\n";
	echo "<input type=hidden name=ssltype value=\"ssl\">\n";
	echo "<input type=hidden name=sessid value=\"".$sessid."\">\n";
	echo "</form>\n";
	echo "<script>document.form1.submit();</script>\n";
	echo "</body></html>\n";
}