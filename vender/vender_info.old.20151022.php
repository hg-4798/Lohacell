<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

$mode=$_POST["mode"];
if($mode=="update") {
	$up_com_owner=$_POST["up_com_owner"];
	$up_com_post1=$_POST["up_com_post1"];
	$up_com_post2=$_POST["up_com_post2"];
	$up_com_addr=$_POST["up_com_addr"];
	$up_com_biz=$_POST["up_com_biz"];
	$up_com_item=$_POST["up_com_item"];
	$up_com_tel1=$_POST["up_com_tel1"];
	$up_com_tel2=$_POST["up_com_tel2"];
	$up_com_tel3=$_POST["up_com_tel3"];
	$up_com_fax1=$_POST["up_com_fax1"];
	$up_com_fax2=$_POST["up_com_fax2"];
	$up_com_fax3=$_POST["up_com_fax3"];
	$up_com_homepage=strtolower($_POST["up_com_homepage"]);

	$up_p_name=$_POST["up_p_name"];
	$up_p_mobile1=$_POST["up_p_mobile1"];
	$up_p_mobile2=$_POST["up_p_mobile2"];
	$up_p_mobile3=$_POST["up_p_mobile3"];
	$up_p_email=$_POST["up_p_email"];
	$up_p_buseo=$_POST["up_p_buseo"];
	$up_p_level=$_POST["up_p_level"];

	$up_passwd=$_POST["up_passwd"];

	$up_bank1=$_POST["up_bank1"];
	$up_bank2=$_POST["up_bank2"];
	$up_bank3=$_POST["up_bank3"];

	$up_session=$_POST["up_session"];

	$up_com_post="";
	if(strlen($up_com_post1)==3 && strlen($up_com_post2)==3) {
		$up_com_post=$up_com_post1.$up_com_post2;
	}

	$up_com_tel="";
	$up_com_fax="";
	$up_p_mobile="";
	if(strlen($up_com_tel1)>0 && strlen($up_com_tel2)>0 && strlen($up_com_tel3)>0) {
		if(IsNumeric($up_com_tel1) && IsNumeric($up_com_tel2) && IsNumeric($up_com_tel3)) {
			$up_com_tel=$up_com_tel1."-".$up_com_tel2."-".$up_com_tel3;
		}
	}
	if(strlen($up_com_fax1)>0 && strlen($up_com_fax2)>0 && strlen($up_com_fax3)>0) {
		if(IsNumeric($up_com_fax1) && IsNumeric($up_com_fax2) && IsNumeric($up_com_fax3)) {
			$up_com_fax=$up_com_fax1."-".$up_com_fax2."-".$up_com_fax3;
		}
	}
	if(strlen($up_p_mobile1)>0 && strlen($up_p_mobile2)>0 && strlen($up_p_mobile3)>0) {
		if(IsNumeric($up_p_mobile1) && IsNumeric($up_p_mobile2) && IsNumeric($up_p_mobile3)) {
			$up_p_mobile=$up_p_mobile1."-".$up_p_mobile2."-".$up_p_mobile3;
		}
	}
	if(!ismail($up_p_email)) {
		$up_p_email="";
	}
	$up_com_homepage=str_replace("http://","",$up_com_homepage);

	$bank_account="";
	if(strlen($up_bank1)>0 && strlen($up_bank2)>0 && strlen($up_bank3)>0) {
		$bank_account=$up_bank1."=".$up_bank2."=".$up_bank3;
	}

	if(strlen($up_com_owner)==0) {
		echo "<html></head><body onload=\"alert('대표자 성명을 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_post)==0 || strlen($up_com_addr)==0) {
		echo "<html></head><body onload=\"alert('사업장 주소를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_biz)==0) {
		echo "<html></head><body onload=\"alert('사업자 업태를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_item)==0) {
		echo "<html></head><body onload=\"alert('사업자 종목을 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_tel)==0) {
		echo "<html></head><body onload=\"alert('회사 대표전화를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_p_name)==0) {
		echo "<html></head><body onload=\"alert('담당자명을 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_p_mobile)==0) {
		echo "<html></head><body onload=\"alert('담당자 휴대전화를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_p_email)==0) {
		echo "<html></head><body onload=\"alert('담당자 이메일을 정확히 입력하세요.')\"></body></html>";exit;
	}

	$sql = "UPDATE tblvenderinfo SET ";
	if(strlen($up_passwd)>=4) {
		$sql.= "passwd		= '".md5($up_passwd)."', ";
	}
	$sql.= "com_owner		= '".$up_com_owner."', ";
	$sql.= "com_post		= '".$up_com_post."', ";
	$sql.= "com_addr		= '".$up_com_addr."', ";
	$sql.= "com_biz			= '".$up_com_biz."', ";
	$sql.= "com_item		= '".$up_com_item."', ";
	$sql.= "com_tel			= '".$up_com_tel."', ";
	$sql.= "com_fax			= '".$up_com_fax."', ";
	$sql.= "com_homepage	= '".$up_com_homepage."', ";
	$sql.= "p_name			= '".$up_p_name."', ";
	$sql.= "p_mobile		= '".$up_p_mobile."', ";
	$sql.= "p_email			= '".$up_p_email."', ";
	$sql.= "p_buseo			= '".$up_p_buseo."', ";
	$sql.= "p_level			= '".$up_p_level."', ";
	$sql.= "bank_account	= '".$bank_account."' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	if(pmysql_query($sql,get_db_conn())) {
		if($up_session == "Y") {
			$sql = "DELETE FROM tblvendersession WHERE authkey != '".$_VenderInfo->getAuthkey()."' AND vender = '".$_VenderInfo->getVidx()."' ";
			pmysql_query($sql,get_db_conn());
		}

		echo "<html></head><body onload=\"alert('요청하신 작업이 성공하였습니다.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('요청하신 작업중 오류가 발생하였습니다.')\"></body></html>";exit;
	}
}

$com_tel=explode("-",$_venderdata->com_tel);
$com_fax=explode("-",$_venderdata->com_fax);
$com_p_mobile=explode("-",$_venderdata->p_mobile);
$bank_account=explode("=",$_venderdata->bank_account);
include("header.php"); 
?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function formSubmit() {
	var form = document.form1;
	if (!form.up_com_name.value) {
		form.up_com_name.focus();
		alert("상호(회사명)을 입력하세요.");
		return;
	}
	if(CheckLength(form.up_com_name)>30) {
		form.up_com_name.focus();
		alert("상호(회사명)은 한글15자 영문30자 까지 입력 가능합니다");
		return;
	}
	if (!form.up_com_num.value) {
		form.up_com_num.focus();
		alert("사업자등록번호를 입력하세요.");
		return;
	}

	var bizno;
	var bb;
	bizno = form.up_com_num.value;
	bizno = bizno.replace("-","");
	bb = chkBizNo(bizno);
	if (!bb) {
		alert("인증되지 않은 사업자등록번호 입니다.\n사업자등록번호를 다시 입력하세요.");
		form.up_com_num.value = "";
		form.up_com_num.focus();
		return;
	}
	if (!form.up_com_owner.value) {
		form.up_com_owner.focus();
		alert("대표자 성명을 입력하세요.");
		return;
	}
	if(CheckLength(form.up_com_owner)>12) {
		form.up_com_owner.focus();
		alert("대표자 성명은 한글 6글자까지 가능합니다");
		return;
	}
	if (!form.up_com_post1.value || !form.up_com_post2.value) {
		form.up_com_post1.focus();
		alert("우편번호를 입력하세요.");
		return;
	}
	if (!form.up_com_addr.value) {
		form.up_com_addr.focus();
		alert("사업장 주소를 입력하세요.");
		return;
	}
	if(CheckLength(form.up_com_biz)>30) {
		form.up_com_biz.focus();
		alert("사업자 업태는 한글 15자까지 입력 가능합니다");
		return;
	}
	if(CheckLength(form.up_com_item)>30) {
		form.up_com_item.focus();
		alert("사업자 종목은 한글 15자까지 입력 가능합니다");
		return;
	}
	if(form.up_com_tel1.value.length==0 || form.up_com_tel2.value.length==0 || form.up_com_tel3.value.length==0) {
		form.up_com_tel1.focus();
		alert("회사 대표전화를 입력하세요.");
		return;
	}
	if(!isNumber(form.up_com_tel1.value) || !isNumber(form.up_com_tel2.value) || !isNumber(form.up_com_tel3.value)) {
		form.up_com_tel1.focus();
		alert("전화번호는 숫자만 입력하세요.");
		return;
	}
	if(form.up_com_fax1.value.length>0 && form.up_com_fax2.value.length>0 && form.up_com_fax3.value.length>0) {
		if(!isNumber(form.up_com_fax1.value) || !isNumber(form.up_com_fax2.value) || !isNumber(form.up_com_fax3.value)) {
			form.up_com_fax1.focus();
			alert("팩스번호는 숫자만 입력하세요.");
			return;
		}
	}
	if(form.up_p_name.value.length==0) {
		form.up_p_name.focus();
		alert("담당자 이름을 입력하세요.");
		return;
	}
	if(form.up_p_mobile1.value.length==0 || form.up_p_mobile2.value.length==0 || form.up_p_mobile3.value.length==0) {
		form.up_com_tel1.focus();
		alert("담당자 휴대전화를 입력하세요.");
		return;
	}
	if(!isNumber(form.up_p_mobile1.value) || !isNumber(form.up_p_mobile2.value) || !isNumber(form.up_p_mobile3.value)) {
		form.up_com_tel1.focus();
		alert("담당자 휴대전화 번호는 숫자만 입력하세요.");
		return;
	}
	if(form.up_p_email.value.length==0) {
		form.up_p_email.focus();
		alert("담당자 이메일을 입력하세요.");
		return;
	}
	if(!IsMailCheck(form.up_p_email.value)) {
		form.up_p_email.focus();
		alert("담당자 이메일을 정확히 입력하세요.");
		return;
	}
	if(form.up_bank1.value.length==0 || form.up_bank2.value.length==0 || form.up_bank3.value.length==0) {
		alert("정산받을 계좌정보를 정확히 입력하세요.");
		form.up_bank1.focus();
		return;
	}
	if(form.up_passwd.value.length>0 || form.up_passwd2.value.length>0) {
		if(form.up_passwd.value!=form.up_passwd.value) {
			alert("변경하실 비밀번호가 일치하지 않습니다.");
			form.up_passwd2.focus();
			return;
		} else if(form.up_passwd.value.length<4) {
			alert("비밀번호는 영문, 숫자를 혼합하여 4~12자 이내로 입력하세요.");
			form.up_passwd.focus();
			return;
		}
	}


	if(confirm("변경하신 내용을 저장하시겠습니까?")) {
		form.mode.value="update";
		form.target="processFrame";
		form.submit();
	}
}

function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");		
}

</script>
<script src="http://dmaps.daum.net/map_js_init/postcode.js"></script><!-- 다음 우편번호 api -->
<script>
// 다음 우편번호 팝얻창 불러오기
function openDaumPostcode() {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
			// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
			document.getElementById('up_com_post1').value = data.postcode1;
			document.getElementById('up_com_post2').value = data.postcode2;
			document.getElementById('up_com_addr').value = data.address;
			document.getElementById('up_com_addr').focus();
			//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
			//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
			//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
			//document.getElementById('addr').value = addr;

			
		}
	}).open();
}
</script>

<table border=0 cellpadding=0 cellspacing=0 width=1000 style="table-layout:fixed">
<col width=175></col>
<col width=5></col>
<col width=740></col>
<col width=80></col>
<tr>
	<td width=175 valign=top nowrap><? include ("menu.php"); ?></td>
	<td width=5 nowrap></td>
	<td valign=top>

	<table width="100%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:3px solid #EEEEEE" bgcolor="#ffffff">
		<tr>
			<td style="padding:10">
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=165></col>
				<col width=></col>
				<tr>
					<td height=29 align=center background="images/tab_menubg.gif">
					<FONT COLOR="#ffffff"><B>업체정보 관리</B></FONT>
					</td>
					<td></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height=2 bgcolor=red></td></tr>
			<tr>
				<td bgcolor=#FBF5F7>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=10></col>
				<col width=></col>
				<col width=10></col>
				<tr>
					<td colspan=3 style="padding:15,15,5,15">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td style="padding-bottom:5"><img src="images/icon_boxdot.gif" border=0 align=absmiddle> <B>업체정보 관리</B></td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입점사 관리자 정보 및 기타 설정 값을 입력합니다.</td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입력한 정보는 본사 사이트 입점업체 정보에 입력됩니다.</td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입점사 관리자의 상품 처리권한[등록/수정/삭제/인증]은 본사 관리자가 승인 후 가능 합니다.</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td><img src="images/tab_boxleft.gif" border=0></td>
					<td></td>
					<td><img src="images/tab_boxright.gif" border=0></td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- 처리할 본문 위치 시작 -->
			<tr><td height=0></td></tr>
			<tr>
				<td style="padding:15">
				
				<table border=0 cellpadding=0 cellspacing=0 width=100%>

				<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
				<input type=hidden name=mode>

				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> 입점업체 기본정보 <font style="color:#2A97A7">('*'표시는 필수입력입니다)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 상호 (회사명)</B></td>
					<td style=padding:7,10>
					<input type="text" name=up_com_name value="<?=$_venderdata->com_name?>" size=20 maxlength=30 disabled>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업자등록번호</B></td>
					<td style=padding:7,10>
					<input type="text" name=up_com_num value="<?=$_venderdata->com_num?>" size=20 maxlength=20 onkeyup="strnumkeyup(this)" disabled>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 대표자 성명</B></td>
					<td style=padding:7,10>
					<input name=up_com_owner value="<?=$_venderdata->com_owner?>" size=20 maxlength="12">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td rowspan=2 bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업장 주소</B></td>
					<td style=padding:7,10>
					<input type=text name="up_com_post1" value="<?=substr($_venderdata->com_post,0,3)?>" size="3" maxlength="3" readonly> - <input type=text name="up_com_post2" value="<?=substr($_venderdata->com_post,3,3)?>" size="3" maxlength="3" readonly> <img src="images/btn_findpostno.gif" border=0 align=absmiddle style="cursor:hand" onClick="javascript:openDaumPostcode();">
					</td>
				</tr>
				<tr>
					<td style=padding:7,10>
					<input type=text name="up_com_addr" value="<?=$_venderdata->com_addr?>" size=50 maxlength=150>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업자 업태</B></td>
					<td style=padding:7,10>
					<input type="text" name=up_com_biz value="<?=$_venderdata->com_biz?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업자 종목</B></td>
					<td style=padding:7,10>
					<input type=text name=up_com_item value="<?=$_venderdata->com_item?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 회사 대표전화</B></td>
					<td style=padding:7,10>
					<input type=text name=up_com_tel1 value="<?=$com_tel[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text name=up_com_tel2 value="<?=$com_tel[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text name=up_com_tel3 value="<?=$com_tel[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>회사 팩스번호</B></td>
					<td style=padding:7,10>
					<input type=text name=up_com_fax1 value="<?=$com_fax[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text name=up_com_fax2 value="<?=$com_fax[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text name=up_com_fax3 value="<?=$com_fax[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>회사 홈페이지</B></td>
					<td style=padding:7,10>
					http://<input type=text name=up_com_homepage value="<?=$_venderdata->com_homepage?>" size=30 maxlength=50>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> 업체 담당자 정보 <font style="color:#2A97A7">('*'표시는 필수입력입니다)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 담당자 이름</B></td>
					<td style=padding:7,10>
					<input type=text name=up_p_name value="<?=$_venderdata->p_name?>" size=20 maxlength=20> &nbsp; <font style="color:#2A97A7;font-size:8pt">* 입점 담당자 이름을 정확히 입력하세요.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 담당자 휴대전화</B></td>
					<td style=padding:7,10>
					<input type=text name=up_p_mobile1 value="<?=$com_p_mobile[0]?>" size=4 maxlength=3 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text name=up_p_mobile2 value="<?=$com_p_mobile[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text name=up_p_mobile3 value="<?=$com_p_mobile[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)"></td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 담당자 이메일</B></td>
					<td style=padding:7,10>
					<input type=text name=up_p_email value="<?=$_venderdata->p_email?>" size=30 maxlength=50> &nbsp; <font style="color:#2A97A7;font-size:8pt">* 주문확인시 담당자 이메일로 통보됩니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>담당자 부서명</B></td>
					<td style=padding:7,10>
					<input type=text name=up_p_buseo value="<?=$_venderdata->p_buseo?>" size=20 maxlength=20>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>담당자 직위</B></td>
					<td style=padding:7,10>
					<input type=text name=up_p_level value="<?=$_venderdata->p_level?>" size=20 maxlength=20>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> 업체 관리정보 <font style="color:#2A97A7">('*'표시는 필수입력입니다)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>상품 처리 권한</td>
					<td style=padding:7,10>
					<input type=checkbox name=chk_prdt1 value="Y" <?if($_venderdata->grant_product[0]=="Y")echo"checked";?> disabled>등록
					<img width=20 height=0>
					<input type=checkbox name=chk_prdt2 value="Y" <?if($_venderdata->grant_product[1]=="Y")echo"checked";?> disabled>수정
					<img width=20 height=0>
					<input type=checkbox name=chk_prdt3 value="Y" <?if($_venderdata->grant_product[2]=="Y")echo"checked";?> disabled>삭제
					<img width=50 height=0>
					<input type=checkbox name=chk_prdt4 value="Y" <?if($_venderdata->grant_product[3]=="Y")echo"checked";?> disabled>등록/수정시, 관리자 인증
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>입점 상품수 제한</td>
					<td style=padding:7,10>
					<B><?=($_venderdata->product_max==0?"무제한 등록 가능":$_venderdata->product_max."개 까지 상품등록 가능")?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>판매 수수료</td>
					<td style=padding:7,10>
					<B><?=(int)$_venderdata->rate?> %</B>
					&nbsp;&nbsp;&nbsp;&nbsp; <font style="color:#2A97A7;font-size:8pt">* 쇼핑몰 본사에서 받는 상품판매 수수료입니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 정산 계좌정보</td>
					<td style=padding:7,10>
					은행 <input type=text name=up_bank1 value="<?=$bank_account[0]?>" size=10>
					<img width=20 height=0>
					계좌번호 <input type=text name=up_bank2 value="<?=$bank_account[1]?>" size=20>
					<img width=20 height=0>
					예금주 <input type=text name=up_bank3 value="<?=$bank_account[2]?>" size=15>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>정산일</td>
					<td style=padding:7,10>
					<B>매월 <?=(strlen($_venderdata->account_date)>0?$_venderdata->account_date."일":"")?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> 미니샵 운영ID 관리</td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>운영 ID</td>
					<td style=padding:7,10>
					<B><?=$_venderdata->id?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>비밀번호 변경</B></td>
					<td style=padding:7,10>
					<input type=password name=up_passwd size=15> &nbsp; <font style="color:#2A97A7;font-size:8pt">* 영문, 숫자를 혼용하여 사용(4자 ~ 12자)</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>비밀번호 확인</B></td>
					<td style=padding:7,10>
					<input type=password name=up_passwd2 size=15> &nbsp; <font style="color:#2A97A7;font-size:8pt">* 비밀번호는 정기적으로 변경 하실 것을 권장합니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>운영자 세션 삭제</B></td>
					<td style=padding:7,10>
					<input type=radio name=up_session value="N" id="idx_sessionN"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sessionN">로그인 세션 유지</label><img width=20 height=0><input type=radio name=up_session value="Y" id="idx_sessionY"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sessionY">로그인 세션 삭제</label><br>
					<font style="padding-left:5px;color:#2A97A7;font-size:8pt">* 로그인 세션 삭제시 자신을 제외한 모든 운영자들은 재로그인 후 이용이 가능합니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:formSubmit()"><img src="images/btn_save01.gif" border=0></A>
					</td>
				</tr>

				</form>

				</table>

				<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

				</td>
			</tr>
			<!-- 처리할 본문 위치 끝 -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>
</table>
<?=$onload?>
<?php include("copyright.php"); ?>
