<?php
	session_start();
	
	$Dir="../../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata2.php");

    $form_data   = urldecode($_GET['form_data']);
    $arrFormData = explode("||", $form_data);
	if ($arrFormData[0] == 'user_modify') {
		$mod_user_data	= decrypt_md5($arrFormData[1]);
		$mud_arr	= explode("!@", $mod_user_data);
	} else if ($arrFormData[0] == 'user_out') {
		$out_user_data	= decrypt_md5($arrFormData[1]);
		$oud_arr	= explode("!@", $out_user_data);
	} else {
		$auth_type = $arrFormData[0];
		$mem_type = $arrFormData[1];
		$find_type = $arrFormData[2];
		$cert_type = $arrFormData[3];
		$join_type = $arrFormData[4];
	}

	$returnMsg = "휴대폰 본인인증이 정상적으로 완료 되었습니다.";
	$returnCode  = "1";	

	$_SESSION[ipin][name] = iconv("UTF-8", "EUC-KR", "김재수");
	$_SESSION[ipin][dupinfo] = "MC0GCCqGSIb3DQIJAyEAl3N6abnFNYgfZN2k7reLfXY2q83Q4xsGiluFJxZl7G0=";
?>
<?if ($mod_user_data || $out_user_data){?>
<?if($returnCode == '1') {?>
<html><head></head><body></body></html>
<script>
/*
 * path : 전송 URL
 * params : 전송 데이터 {'q':'a','s':'b','c':'d'...}으로 묶어서 배열 입력
 * method : 전송 방식(생략가능)
 */

function post_to_url(path, params, method) {
    method = method || "post";  //method 부분은 입력안하면 자동으로 post가 된다.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);
    form.submit();

}

alert('<?=$returnMsg?>');	
<?if ($mod_user_data){?>
post_to_url(
	'<?=$Dir.MDir?>mypage_usermodify.php',
	{
		'type':'<?=$mud_arr[0]?>',
		'passwd1':'<?=$mud_arr[1]?>',
		'passwd2':'<?=$mud_arr[1]?>',
		'birth1':'<?=$mud_arr[2]?>',
		'birth2':'<?=$mud_arr[3]?>',
		'news_mail_yn':'<?=$mud_arr[4]?>',
		'news_sms_yn':'<?=$mud_arr[5]?>',
		'mobile':'<?=$mud_arr[6]?>',
		'gdn_email':'<?=$mud_arr[7]?>'
	}
);
<?} else if ($out_user_data){?>
post_to_url(
	'<?=$Dir.MDir?>mypage_memberout.php',
	{
		'type':'<?=$oud_arr[0]?>',
		'out_reason':'<?=$oud_arr[1]?>',
		'out_reason_content':'<?=$oud_arr[2]?>',
		'memoutinfo':'<?=encrypt_md5($oud_arr[3])?>'
	}
);
<?}?>
</script>
<?} else {?>
<script>
alert('<?=$returnMsg?>');
document.location.href = "<?=$Dir.MDir?>mypage_usermodify.php";
</script>
<?}?>
<?} else {?>
<script>
alert('<?=$returnMsg?>');
document.location.href = "/m/member_chkid.php?auth_type=<?=$auth_type?>&mem_type=<?=$mem_type?>&find_type=<?=$find_type?>&cert_type=<?=$cert_type?>&join_type=<?=$join_type?>";
</script>
<?}?>