<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");
include("calendar.php");
########################### TEST 쇼핑몰 확인 ##########################
DemoShopCheck("데모버전에서는 접근이 불가능 합니다.", "history.go(-1)");
#######################################################################

####################### 페이지 접근권한 check ###############
$PageCode = "or-3";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################
$linkhub_ID			= $_shopdata->linkhub_id;
$linkhub_PWD			= $_shopdata->linkhub_pwd;
$linkhub_CorpNum1	= substr($_shopdata->linkhub_corpnum,0,3); 
$linkhub_CorpNum2	= substr($_shopdata->linkhub_corpnum,3,2);
$linkhub_CorpNum3	= substr($_shopdata->linkhub_corpnum,5,5);
$linkhub_CorpNum		= $linkhub_CorpNum1.$linkhub_CorpNum2.$linkhub_CorpNum3;
$linkhub_CEOName		= $_shopdata->linkhub_ceoname;
$linkhub_CorpName	= $_shopdata->linkhub_corpname;
$linkhub_Addr		= $_shopdata->linkhub_addr;
$linkhub_ZipCode_ex	= explode("-",$_shopdata->linkhub_zipcode);
$linkhub_ZipCode1	= $linkhub_ZipCode_ex[0];
$linkhub_ZipCode2	= $linkhub_ZipCode_ex[1];
$linkhub_ZipCode		= $linkhub_ZipCode1."-".$linkhub_ZipCode2;
$linkhub_BizType		= $_shopdata->linkhub_biztype;
$linkhub_BizClass	= $_shopdata->linkhub_bizclass;
$linkhub_ContactName = $_shopdata->linkhub_contactname;
$linkhub_ContactEmail_ex= explode("@",$_shopdata->linkhub_contactemail);
$linkhub_ContactEmail1= $linkhub_ContactEmail_ex[0];
$linkhub_ContactEmail2= $linkhub_ContactEmail_ex[1];
$linkhub_ContactEmail = $linkhub_ContactEmail1."@".$linkhub_ContactEmail2;
$linkhub_ContactTEL_ex	= explode("-",$_shopdata->linkhub_contacttel);
$linkhub_ContactTEL1	= $linkhub_ContactTEL_ex[0];
$linkhub_ContactTEL2	= $linkhub_ContactTEL_ex[1];
$linkhub_ContactTEL3	= $linkhub_ContactTEL_ex[2];
$linkhub_ContactTEL	= $linkhub_ContactTEL1."-".$linkhub_ContactTEL2."-".$linkhub_ContactTEL3;
$linkhub_ContactHP_ex	= explode("-",$_shopdata->linkhub_contacthp);
$linkhub_ContactHP1	= $linkhub_ContactHP_ex[0];
$linkhub_ContactHP2	= $linkhub_ContactHP_ex[1];
$linkhub_ContactHP3	= $linkhub_ContactHP_ex[2];
$linkhub_ContactHP	= $linkhub_ContactHP1."-".$linkhub_ContactHP2."-".$linkhub_ContactHP3;
$linkhub_ContactFAX_ex	= explode("-",$_shopdata->linkhub_contactfax);
$linkhub_ContactFAX1	= $linkhub_ContactFAX_ex[0];
$linkhub_ContactFAX2	= $linkhub_ContactFAX_ex[1];
$linkhub_ContactFAX3	= $linkhub_ContactFAX_ex[2];
$linkhub_ContactFAX	= $linkhub_ContactFAX1."-".$linkhub_ContactFAX2."-".$linkhub_ContactFAX3;


include "../linkhub/TaxinvoiceExample/common.php";
try	{
	$result = $TaxinvoiceService->CheckIsMember($linkhub_CorpNum,$LinkID); 
	$code = mb_convert_encoding($result->code,"euc-kr","utf-8");
	$message = mb_convert_encoding($result->message,"euc-kr","utf-8");
} 
catch(PopbillException $pe) {
	$code = mb_convert_encoding($pe->getCode(),"euc-kr","utf-8");
	$message = mb_convert_encoding($pe->getMessage(),"euc-kr","utf-8");
}
$popbill_login_url = "";
$popbill_point_url = "";
$popbill_publiclicense_url = "";
$popbill_tempdocu_url = "";
$popbill_salesdocu_url = "";
$popbill_makesales_url = "";

if ($code != "1") {
	alert_go('세금계산서 환경설정 후 이용하시기 바랍니다.','order_taxsaveconfig_linkhub.php');
}

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*3));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*7));

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (strtotime($search_end)-strtotime($search_start))/86400;
if ($termday>367) {
	alert_go('검색기간은 1년을 초과할 수 없습니다.');
}

$mode=$_POST["mode"];
$flag=$_POST["flag"];
$ordercode=$_POST["ordercode"];
$calcno=$_POST["calcno"];

/*
if($mode=="OK" && strstr("Y",$flag)) {
	
	$up_query="update tbltaxcalclist set type='1' where no='{$calcno}'";
	pmysql_query($up_query);
	
	$onload="<script>window.onload=function(){ alert('발급되었습니다.'); document.location.href='order_taxcalclist_linkhub.php'}</script>";
}
*/
$type=$_POST["type"];

include("header.php");
?>
<style>
.new_action_btn {
	display: inline-block;border-radius: 3px;border: 1px solid #0A65B2;	padding: 2px 2px;
	color: #fff; font: 11px ngeb; cursor:pointer;
	text-align: center;
	border: 1px solid #52A3E7;
	background: #BBE3FF;
	background: -moz-linear-gradient(top, #BBE3FF 0%, #0A65B2 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#BBE3FF), color-stop(100%,#0A65B2));
	background: -webkit-linear-gradient(top, #BBE3FF 0%,#0A65B2 100%);
	background: -o-linear-gradient(top, #BBE3FF 0%,#0A65B2 100%);
	background: -ms-linear-gradient(top, #BBE3FF 0%,#0A65B2 100%);
	background: linear-gradient(to bottom, #BBE3FF 0%,#0A65B2 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#BBE3FF', endColorstr='#0A65B2',GradientType=0 );
}

.new_action_btn_red {
	display: inline-block;border-radius: 3px;border: 1px solid #B20A3D;	padding: 2px 2px;
	color: #fff; font: 11px ngeb; cursor:pointer;
	text-align: center;
	border: 1px solid #E7525D;
	background: #ED6B1B;
	background: -moz-linear-gradient(top, #ED6B1B 0%, #B20A3D 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ED6B1B), color-stop(100%,#B20A3D));
	background: -webkit-linear-gradient(top, #ED6B1B 0%,#B20A3D 100%);
	background: -o-linear-gradient(top, #ED6B1B 0%,#B20A3D 100%);
	background: -ms-linear-gradient(top, #ED6B1B 0%,#B20A3D 100%);
	background: linear-gradient(to bottom, #ED6B1B 0%,#B20A3D 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ED6B1B, endColorstr='#B20A3D',GradientType=0 );
}
#dialog-link {
	padding: .4em 1em .4em 20px;
	text-decoration: none;
	position: relative;
}
#dialog-link span.ui-icon {
	margin: 0 5px 0 0;
	position: absolute;
	left: .2em;
	top: 50%;
	margin-top: -8px;
}
#icons {
	margin: 0;
	padding: 0;
}
#icons li {
	margin: 2px;
	position: relative;
	padding: 4px 0;
	cursor: pointer;
	float: left;
	list-style: none;
}
#icons span.ui-icon {
	float: left;
	margin: 0 4px;
}
.fakewindowcontain .ui-widget-overlay {
	position: absolute;
}
#loading {
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  position: fixed;
  display: block;
  opacity: 0.7;
  background-color: #fff;
  z-index: 10000;
  text-align: center;
}

#loading-image {
  position: absolute;
  top: 100px;
  left: 240px;
  z-index: 100;
}
</style>
<div id="loading" style="display:none;">
	<div id='loading-image'><img src="/images/ajax-loader.gif" width="70px"></div>                                                                                                                  
</div>
<script src="/osrc/jquery-ui-1.11.3.custom/external/jquery/jquery.js"></script>
<script src="/osrc/jquery-ui-1.11.3.custom/jquery-ui.js"></script>
<link href="/osrc/jquery-ui-1.11.3.custom/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="lib.js.php"></script>

<script language="JavaScript">
 $(document).ready(function() {
	$(document).ajaxStart(function() {
		$('#loading').show();
		$("#loading-image").show().css({
            top:  ($(window).height()-20 )/2 + 'px',
            left: ($(window).width() )/2.2 + 'px'
        });
		
    }).ajaxStop(function() {
        $('#loading').hide();
		$("#loading-image").hide();
    });
});
function taxRegsterUpdate(){
	$.ajax({
		type: "POST",
		url: "/admin/linkhub_tax_register_proc.php",
		data: $("#tax_reg_frm").serialize(),
		dataType:"json",
		beforeSend: function () {
		}
	}).done(function(data){
		alert(data.message);
		if (data.code == "1"){
			CheckForm();
		}
	});
}
$(function(){
	$( "#tax_register" ).dialog({
		autoOpen: false,
		width: 820,
		height: "auto",
		buttons: [
			{
				text: "등록",
				click: function() {
					taxRegsterUpdate();
					//$( "#tax_register" ).dialog( "close" );
					//$("#tax_reg_frm").submit();
				}
			},
			{
				text: "취소",
				click: function() {
					$( "#tax_register" ).dialog( "close" );
				}
			}
		]
	});
	/*
	$("#s_mode > option[value=<%=campaignForm.getS_mode()%>]").attr("selected", "true");
	$("input:radio[name=s_period]:input[value='<%=campaignForm.getPeriod()%>']").attr("checked", "true");
	$('#memo_layer').html(data).dialog('open');
	*/
});

function CheckForm() {
	document.form1.mode.value="";
	document.form1.flag.value="";
	document.form1.ordercode.value="";
	document.form1.block.value="";
	document.form1.gotopage.value="";
	document.form1.submit();
}

function process(mode,flag,ordercode,no) {
	msg="";
		
	//if(flag=="Y") msg="해당 요청건에 대해서 세금계산서를 등록을 하시겠습니까?";
	if (mode=="register"){
		msg="해당 요청건에 대해서 세금계산서를 등록 하시겠습니까?";

		if(confirm(msg)) {
			$.ajax({
				type: "POST",
				url: "/admin/linkhub_tax_register_form.php",
				data: " ordercode="+ordercode+"&t_idx="+no,
				dataType:"html",
				beforeSend: function () {
				}
			}).done(function(data){
				$("#tax_register").html(data).dialog("open");
			});
		}
		return;
	} else if (mode=="issue"){
		msg="해당 요청건에 대해서 세금계산서를 발행 하시겠습니까?";
		if(confirm(msg)) {
			$.ajax({
				type: "POST",
				url: "/linkhub/TaxinvoiceExample/Issue_ajax.php",
				data: "t_idx="+no,
				dataType:"json",
				beforeSend: function () {
				}
			}).done(function(data){
				alert(data.message);
				if (data.code == "1"){
					CheckForm();
				}
			});
		}
		return;
	}
}

function taxsaveview(ordercode) {
	window.open("order_taxsaveviewpop.php?ordercode="+ordercode,"kcptaxsaveview","scrollbars=no,width=700,height=600");
}

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","orderdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
}

function TaxsaveExcel() {
	document.form1.action="order_taxsave_excel.php";
	document.form1.submit();
	document.form1.action="";
}


function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}

function GoPage(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
}

</script>
<!-- 라인맵 -->
<div class="admin_linemap"><div class="line"><p>현재위치 : 주문/매출 &gt; 현금영수증/세금계산서 &gt; <span>세금계산서 발급/조회</span></p></div></div>

<table cellpadding="0" cellspacing="0" width="98%" style="table-layout:fixed">
<tr>
	<td valign="top">

	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
		<col width=240 id="menu_width"></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top">
			<?php include("menu_order.php"); ?>
			</td>

			<td width="20" valign="top"><img src="images/space01.gif" height="1" border="0" width="20"></td>
			<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<!-- 페이지 타이틀 -->
					<div class="title_depth3">세금계산서 발급/조회</div>
					<!-- 소제목 -->
					<div class="title_depth3_sub"><span>세금계산서 발급신청 조회 및 발급내역 확인이 가능합니다.</span></div>
                </td>
            </tr>
            <tr>
            	<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">세금계산서 발급/조회</div>
				</td>
			</tr>
			<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=flag>
			<input type=hidden name=ordercode>
			<input type=hidden name=calcno>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<div class="table_style01">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<th><span>기간 선택</span></th>
							<TD class="td_con1"><input type=text name=search_start value="<?=$search_start?>" size=15 onfocus="this.blur();" OnClick="Calendar(event)" class="input_selected"> ~ <input type=text name=search_end value="<?=$search_end?>" size=15 onfocus="this.blur();" OnClick="Calendar(event)" class="input_selected"> <input type=radio id=idx_vperiod0 name=vperiod value="0" checked style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px" onclick="OnChangePeriod(this.value)" <?=$check_vperiod0?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod0>당일</label> <input type=radio id=idx_vperiod1 name=vperiod value="1" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px" onclick="OnChangePeriod(this.value)" <?=$check_vperiod1?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod1>3일</label> <input type=radio id=idx_vperiod2 name=vperiod value="2" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px" onclick="OnChangePeriod(this.value)" <?=$check_vperiod2?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod2>1주일</label></TD>
						</TR>
						<TR>
							<th><span>처리단계</span></th>
							<TD class="td_con1"><input type=radio id="idx_type0" name=type value="" <?php if(ord($type)==0)echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type0>전체보기</label> <input type=radio id="idx_type1" name=type value="0" <?php if($type=="0")echo "checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type1>발급요청</label> <input type=radio id="idx_type2" name=type value="1" <?php if($type=="1")echo "checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type2>발급완료</label></TD>
						</TR>
						</TABLE>
						</div>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><p align="center"><a href="javascript:CheckForm();"><img src="images/botteon_search.gif" border="0"></a>&nbsp;<!--<a href="javascript:TaxsaveExcel();"><img src="images/btn_excel1.gif" border="0" hspace="1"></a>--></td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td align="center">
				<table cellpadding="0" cellspacing="0" width="100%">
<?php
				if(substr($search_s,0,8)==substr($search_e,0,8)) {
					$qry.= "WHERE date LIKE '".substr($search_s,0,8)."%' ";
				} else {
					$qry.= "WHERE date>='{$search_s}' AND date <='{$search_e}' ";
				}
				//if(ord($type))	$qry.= "AND type='{$type}' ";
				if(ord($type)){	
					if ($type == "0") {
						$qry.= "AND type in (0,2)";
					} else {
						$qry.= "AND type in (1)";
					}
				}
				$sql = "SELECT COUNT(*) as t_count, SUM(CAST(price AS integer)) as t_price FROM tbltaxcalclist ".$qry." and useat_linkhub = 'y' ";
				
				$result = pmysql_query($sql,get_db_conn());
				$row = pmysql_fetch_object($result);
				$t_count = (int)$row->t_count;
				$t_price = (int)$row->t_price;
				pmysql_free_result($result);
				$paging = new newPaging($t_count,10,10);
				$gotopage = $paging->gotopage;
				$sql = "SELECT * FROM tbltaxcalclist {$qry} and useat_linkhub = 'y' ";
				
				$sql.= "ORDER BY date DESC ";
				
				$sql = $paging->getSql($sql);
				$result = pmysql_query($sql,get_db_conn());
?>
				<tr>
					<td width="100%">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td><p align="left">&nbsp;</td>
						<td><p align="right"><img src="images/icon_8a.gif" border="0">총 건수 : <B><?=number_format($t_count)?></B>건&nbsp;&nbsp;<img src="images/icon_8a.gif" border="0">합계금액 : <B><?=number_format($t_price)?></B>원&nbsp; <img src="images/icon_8a.gif" border="0">현재 <b><?=$gotopage?>/<?=ceil($t_count/$setup['list_num'])?></b> 페이지</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td height=3></td>
				</tr>
				<tr>
					<td width="100%">
					<div class="table_style02">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                    <col width="40" />
                    <col width="130" />
                    <col width="120" />
                    <col width="120" />
                   <!-- <col width="140" />-->
                    <col width="100" />
                    <col width="80" />
                    <col width="80" />
                    <col width="80" />
                    <col width="250" />
                    <col width="80" />
                    <col width="80" />
                    <col width="70" />
                    <col width="50" />
					<TR>
						<th><p align="center">No</th>
						<th><p align="center">주문번호</th>
						<th><p align="center">신청일자</th>
						<th><p align="center">사업자번호</th>
						<!--<th><p align="center">주문일자</th>-->
						<th><p align="center">회사명</th>
						<th><p align="center">대표자명</th>
						<th><p align="center">업태</th>
						<th><p align="center">종목</th>
						<th><p align="center">사업장주소</th>
						<th><p align="center">금액</th>
						<th><p align="center">처리</th>
						<th><p align="center">상태</th>
						<th><p align="center">비고</th>
					</TR>
<?php
					$arrtax=array();
					$arrorder=array();
					$ordercode='';
					$cnt=0;
					while($row=pmysql_fetch_object($result)) {
						$number = ($t_count-($setup['list_num'] * ($gotopage-1))-$cnt);
						$arrtax[$cnt]=$row;
						$arrtax[$cnt]->number=$number;
						$ordercode.=",'{$row->ordercode}'";
						$cnt++;
					}
					pmysql_free_result($result);

					if ($cnt==0) {
						echo "<tr><td class=\"td_con2\" colspan=\"13\" align=\"center\">검색된 내역이 없습니다.</td></tr>";
					} else {
						$ordercode=substr($ordercode,1);
						$sql = "SELECT ordercode, sender_name, bank_date, deli_gbn FROM tblorderinfo ";
						$sql.= "WHERE ordercode IN ({$ordercode}) ";
						$result=pmysql_query($sql,get_db_conn());
						while($row=pmysql_fetch_object($result)) {
							$arrorder[$row->ordercode]=$row;
						}
						pmysql_free_result($result);

						for($i=0;$i<count($arrtax);$i++) {
							$date=$arrtax[$i]->date;
							$tsdtime=substr($date,0,4)."/".substr($date,4,2)."/".substr($date,6,2)." (".substr($date,8,2).":".substr($date,10,2).")";
							$orderdate=$arrtax[$i]->ordercode;
							$orderdate=substr($orderdate,0,4)."/".substr($orderdate,4,2)."/".substr($orderdate,6,2)." (".substr($orderdate,8,2).":".substr($orderdate,10,2).")";

							echo "<tr>\n";
							echo "	<TD><p align=\"center\">";
							if(ord($arrorder[$arrtax[$i]->ordercode]->deli_gbn)==0) echo $arrtax[$i]->number;
							else {
								echo "{$arrtax[$i]->number}";
							}
							echo "	</td>\n";
							echo "	<TD><p align=\"center\"><A HREF=\"javascript:OrderDetailView('{$arrtax[$i]->ordercode}')\"><u>{$arrtax[$i]->ordercode}</u></a></p></TD>";
							
							echo "	<TD><p align=\"center\">";
						//	if($arrtax[$i]->type=="1") {
						//		echo "<A HREF=\"javascript:taxsaveview('{$arrtax[$i]->ordercode}')\"><U>{$tsdtime}</U></A>";
						//	} else {
								echo $tsdtime;
						//	}
							echo "	</td>\n";
//							echo "	<TD><p align=\"center\">{$orderdate}</td>\n";
							echo "	<TD><p align=\"center\">{$arrtax[$i]->busino}</p></td>\n";
							echo "	<TD><p align=\"center\">{$arrtax[$i]->company}</p></td>\n";
							echo "	<TD><p align=\"center\">{$arrtax[$i]->name}</p></td>\n";
							echo "	<TD><p align=\"center\">{$arrtax[$i]->service}</p></td>\n";
							echo "	<TD><p align=\"center\">{$arrtax[$i]->item}</p></td>\n";
							echo "	<TD><p align=\"center\">{$arrtax[$i]->address}</p></td>\n";
							echo "	<TD><p align=\"center\"><span class=\"font_orange\"><b>".number_format($arrtax[$i]->price)."원</b></span></TD>\n";
							echo "	<TD><p align=\"center\">";
							if(ord($arrorder[$arrtax[$i]->ordercode]->deli_gbn)==0) {
								echo "개별발급";
							} else {
								if(strlen($arrorder[$arrtax[$i]->ordercode]->bank_date)==14) echo "<font color=red>입금</font>";
								else if (strlen($arrorder[$arrtax[$i]->ordercode]->bank_date)==9 && $arrorder[$arrtax[$i]->ordercode]->bank_date[8]=="X") echo "환불";
								else echo "미입금";
								echo "/";
								if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="Y") echo "배송";
								else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="S") echo "발송준비";
								else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="C") echo "취소";
								else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="R") echo "반송";
								else echo "미배송";
							}
							echo "	</td>\n";
							echo "	<TD><p align=\"center\">[";
							if(ord($arrtax[$i]->error_msg)) echo "<a href=\"javascript:alert('에러 사유 : {$arrtax[$i]->error_msg}')\"><font color=red>";
							if($arrtax[$i]->type=="1") echo "발급완료";
							else echo "발급요청";
							
							if(ord($arrtax[$i]->error_msg)) echo "</font></a>";
							echo "]	</p></td>";
							echo "	<TD><p align=\"center\">";
							if($arrtax[$i]->type=="0") {echo "<a href=\"javascript:process('register','Y','{$arrtax[$i]->ordercode}','{$arrtax[$i]->no}')\">
							<span class='new_action_btn'>등록</span>
							</a>";	
							} else if(($arrtax[$i]->type=="2")) { // 임시등록 완료
								echo "<a href=\"javascript:process('issue','Y','{$arrtax[$i]->ordercode}','{$arrtax[$i]->no}')\">
								<span class='new_action_btn_red'>발행</span>
								</a>";
							} else { 
								echo "-";
							}
							echo "	</p></td>\n";
							echo "</tr>\n";
						}
					}
?>

					</TABLE>
					</div>
					</td>
				</tr>
				<tr>
					<td height=10></td>
				</tr>
				<tr>
					<td width="100%">
					<table cellpadding="0" cellspacing="0" width="100%">
<?php
					echo "<div id=\"page_navi01\" style=\"height:'40px'\">";
					echo "<div class=\"page_navi\">";
					echo "<ul>";
					if($cnt) echo "	".$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page;
					echo "</ul>";
					echo "</div>";
					echo "</div>";
				
?>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</form>
			<form name=idxform action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=vperiod value="<?=$vperiod?>">
			<input type=hidden name=type value="<?=$type?>">
			</form>

			<form name=detailform method="post" action="order_detail.php" target="orderdetail">
			<input type=hidden name=ordercode>
			</form>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<!-- 메뉴얼 -->
				<div class="sub_manual_wrap">
					<div class="title"><p>매뉴얼</p></div>
					<dl>
						<dt><span></span></dt>
						<dd>
							
						</dd>
					</dl>
				</div>

				</td>
			</tr>
			<tr>
				<td height="50"></td>
			</tr>
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
<div id="tax_register" title="세금계산서 등록">test</div>
<?=$onload?>
<?php
include("copyright.php");
