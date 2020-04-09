<?php
/********************************************************************* 
// 파 일 명		: product_review.php 
// 설     명		: 상품 리뷰 관리
// 상세설명	: 쇼핑몰 전체 상품들의 리뷰를 관리할 수 있습니다.
// 작 성 자		: hspark
// 수 정 자		: 2015.11.26 - 김재수
// 
// 
*********************************************************************/ 
?>
<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");
include("calendar.php");

####################### 페이지 접근권한 check ###############
$PageCode = "co-2";
$MenuCode = "community";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode               = $_POST['mode'];
$s_checklist        = $_POST["s_checklist"];
$s_notchecklist     = $_POST["s_notchecklist"];

if ( $s_checklist != "''" && $s_checklist != "" ) {
    if ( $mode == "best_review_set" ) {
        $sql  = "UPDATE tblproductreview SET best_type = 1 WHERE num in ({$s_checklist})  ";
    } else {
        $sql  = "UPDATE tblproductreview SET best_type = 0 WHERE num in ({$s_checklist})  ";
    }

    $result = pmysql_query($sql);
}

/*$regdate = $_shopdata->regdate;*/
$regdate = date('Ymd');
$CurrentTime = time();
$period[0] = substr($regdate,0,4)."-".substr($regdate,4,2)."-".substr($regdate,6,2);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",$CurrentTime-(60*60*24*30));

$search_all     = $_POST["search_all"];
$type           = $_POST["type"];
$reviewtype     = $_POST["reviewtype"];
$search_start   = $_POST["search_start"];
$search_end     = $_POST["search_end"];
$vperiod        = (int)$_POST["vperiod"];
$search         = $_POST["search"];
$date           = $_POST["date"];
$productcode    = $_POST["productcode"];
$review_class   = $_POST["review_class"]; // 빈값 : 전체, 0 : 텍스트리뷰, 1 : 포토리뷰

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";
$s_check=$_POST["s_check"];
$brandname=$_POST["brandname"];
if(!$s_check) $s_check="0";

if($s_check=="3") {
	$search="";
	$search_style="disabled style=\"background:#f4f4f4\"";
}
${"check_s_check".$s_check} = "checked";
${"check_vperiod".$vperiod} = "checked";


$sql = "SELECT review_type FROM tblshopinfo ";
$result = pmysql_query($sql,get_db_conn());
$row = pmysql_fetch_object($result);
$review_type = $row->review_type;
if($row->review_type=="N") {
	echo "<script>alert(\"리뷰 기능이 설정이 안되었습니다.\");parent.topframe.location.href=\"JavaScript:GoMenu(1,'shop_review.php')\";</script>";exit;
}
pmysql_free_result($result);

if ($type=="delete" && ord($date)) {
	$sql = "DELETE FROM tblproductreview WHERE productcode = '{$productcode}' AND date = '{$date}' ";
	pmysql_query($sql,get_db_conn());
	$pr_sql = "UPDATE tblproduct SET review_cnt = review_cnt - 1 WHERE productcode ='".$productcode."'";
	pmysql_query( $pr_sql, get_db_conn() );
	$onload = "<script>window.onload=function(){alert('해당 상품리뷰 삭제가 완료되었습니다.');}</script>\n";
} else if ($type=="auth" && ord($date)) {
	$sql = "UPDATE tblproductreview SET display = 'Y' ";
	$sql.= "WHERE productcode = '{$productcode}' AND date = '{$date}'";
	pmysql_query($sql,get_db_conn());
	$onload = "<script>window.onload=function(){alert('해당 상품리뷰 인증이 완료되었습니다.');}</script>\n";
}

include("header.php"); 
?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">

function SearchAll(){
	document.rForm.search_all.value="all";
	document.rForm.submit();
}

function CheckSearch() {
	s_check="";
	for(i=0;i<document.form1.s_check.length;i++) {
		if (document.form1.s_check[i].checked) {
			s_check=document.form1.s_check[i].value;
			break;
		}
	}
    /*
	if (s_check!="2" && s_check!="3" && s_check!="4") {
		if (document.form1.search.value.length<3) {
			if(document.form1.search.value.length==0) alert("검색어를 입력하세요.");
			else alert("검색어는 2글자 이상 입력하셔야 합니다."); 
			document.form1.search.focus();
			return;
		}
	}
    */
	document.form1.type.value="up";
	document.form1.submit();
}

function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}

function OnChangeSearchType(val) {
	if (val==2) {
		document.form1.search.disabled=true;
		document.form1.search.style.background="#f4f4f4";
		document.form1.brandname.disabled=true;
		document.form1.brandname.style.background="#f4f4f4";
	} else if(val==4){
		document.form1.search.disabled=true;
		document.form1.search.style.background="#f4f4f4";
		document.form1.brandname.disabled=false;
		document.form1.brandname.style.background="";		
	}	
	 else {
		document.form1.search.disabled=false;
		document.form1.search.style.background="";
		document.form1.brandname.disabled=true;
		document.form1.brandname.style.background="#f4f4f4";		
	}
}

function Searchid(id) {
	document.form1.type.value="up";
	document.form1.search.disabled=false;
	document.form1.search.style.background="";
	document.form1.search.value=id;
	document.form1.s_check[1].checked=true;
	document.form1.submit();
}
function SearchProduct(prname) {
	document.form1.type.value="up";
	document.form1.search.disabled=false;
	document.form1.search.style.background="#FFFFFF";
	document.form1.search.value=prname;
	document.form1.s_check[0].checked=true;
	document.form1.submit();
}


function MemberView(id){
	parent.topframe.ChangeMenuImg(4);
	document.form4.search.value=id;
	document.form4.submit();
}

function ProductInfo(productcode,prcode,popup) {
	document.form2.code.value=productcode;
	document.form2.prcode.value=prcode;
	document.form2.popup.value=popup;
	if (popup=="YES") {
		document.form2.action="/admin/product/product_input.php?prtype="+prcode+"&callback=ProductList.load&productcode="+productcode;
		document.form2.target="register";
		window.open("about:blank","register","width=1500,height=700,scrollbars=yes,status=no");
	} else {
		document.form2.action="product_register.set.php";
		document.form2.target="";
	}
	document.form2.submit();
}
function ProductMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.primage"+cnt);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.visibility = "visible";
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	Obj = document.getElementById(Obj);
	Obj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}
function AuthReview(date,prcode) {
	if(confirm('해당 리뷰를 인증하시겠습니까?')){
		document.rForm.type.value="auth";
		document.rForm.date.value=date;
		document.rForm.productcode.value=prcode;
		document.rForm.submit();
	}
}
function DeleteReview(date,prcode) {
	if(confirm('해당 리뷰를 삭제하시겠습니까?')){
		document.rForm.type.value="delete";
		document.rForm.date.value=date;
		document.rForm.productcode.value=prcode;
		document.rForm.submit();
	}
}
function ReserveSet(id,date,prcode) {
	window.open("about:blank","reserve_set","width=250,height=150,scrollbars=no");
	document.form5.type.value="review";
	document.form5.id.value=id;
	document.form5.date.value=date;
	document.form5.productcode.value=prcode;
	document.form5.target="reserve_set";
	document.form5.submit();
}
function OrderInfo(id) {
	window.open("about:blank","orderinfo","width=400,height=320,scrollbars=no");
	document.orderform.target="orderinfo";
	document.orderform.id.value=id;
	document.orderform.submit();
}
function ReviewReply(date,prcode) {
	window.open("about:blank","reply","width=400,height=500,scrollbars=yes");
	document.replyform.target="reply";
	document.replyform.date.value=date;
	document.replyform.productcode.value=prcode;
	document.replyform.submit();
}
function GoPage(block,gotopage) {
	document.rForm.block.value = block;
	document.rForm.gotopage.value = gotopage;
	document.rForm.submit();
}

function allCheck(obj) {
    if ( $(obj).is(":checked") ) {
        $("input:checkbox[name='idx[]']").attr("checked", true);
    } else {
        $("input:checkbox[name='idx[]']").attr("checked", false);
    }
}

function lbEdit() {
    if ( confirm("베스트리뷰에 등록하시겠습니까?") ) {

        var arrChkList = new Array();       // 체크된 것들
        var arrNotChkList = new Array();    // 체크되지 않은 것들
        $("input:checkbox[name='idx[]']").each(function(idx) {
            if ( $(this).is(":checked") ) {
                arrChkList.push($(this).val());
            } else {
                arrNotChkList.push($(this).val());
            }
        });

        document.form1.s_checklist.value = "'" + arrChkList.join("','") + "'";
        document.form1.s_notchecklist.value = "'" + arrNotChkList.join("','") + "'";
        document.form1.mode.value = "modify";
        document.form1.submit();
    }
}

function changeBestReview(mode) {

    var arrChkList = new Array();       // 체크된 것들
    $("input:checkbox[name='idx[]']").each(function(idx) {
        if ( $(this).is(":checked") ) {
            arrChkList.push($(this).val());
        }
    });

    if ( arrChkList.length == 0 ) {
        alert("하나 이상을 선택해 주세요.");
        return;
    }

    var msg = "베스트리뷰 해제하시겠습니까?";
    var modeVal = "best_review_unset";
    if ( mode === 1 ) { 
        msg = "베스트리뷰 등록하시겠습니까?";
        modeVal = "best_review_set";
    }

    if ( confirm(msg) ) {
        document.form1.s_checklist.value = "'" + arrChkList.join("','") + "'";
        document.form1.mode.value = modeVal;
        document.form1.submit();
    }
}

function ReviewExcel() {
	document.product_excel.action = 'product_review_excel.php';
	document.product_excel.submit();
}


</script>
<style>
a.search_btn {
  display: inline-block;
  color: #fff;
  height: 26px;
  padding: 0px 5px;
  text-align: center;
  border-radius: 2px;
  font: 12px/24px bold;
  background-color: #44474c;
}
</style>
<div class="content-wrap">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<!-- 페이지 타이틀 -->
					<div class="title_depth3">상품 리뷰 관리</div>
					<!-- 소제목 -->
					<div class="title_depth3_sub"><span>쇼핑몰 전체 상품들의 리뷰를 관리할 수 있습니다.</span></div>
                </td>
            </tr>
            <tr>
            	<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">상품리뷰 검색</div>
				</td>
			</tr>
			<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=date>
			<input type=hidden name=productcode>
			<input type=hidden name=mode value="<?=$mode?>">
            <input type=hidden name='s_checklist' value='<?=$s_checklist?>'>
            <input type=hidden name='s_notchecklist' value='<?=$s_notchecklist?>'>
			<tr>
				<td>
				<div class="table_style01">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <tr>
                    <th><span>리뷰 타입</span>
                    <td>
                        <input type="radio" id="review_class1" name="review_class" value="" <? if ( $review_class == "" ) { echo "checked"; } ?>/> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=review_class1>전체</label>&nbsp;&nbsp;
                        <input type="radio" id="review_class2" name="review_class" value="0" <? if ( $review_class == "0" ) { echo "checked"; } ?> /> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=review_class2>텍스트리뷰</label>&nbsp;&nbsp;
                        <input type="radio" id="review_class3" name="review_class" value="1" <? if ( $review_class == "1" ) { echo "checked"; } ?> /> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=review_class3>포토리뷰</label>&nbsp;&nbsp;
                    </td>
                </tr>
				<TR>
					<th><span>검색기간 선택</span></th>
					<td><input class="input_bd_st01" type="text" name="search_start" OnClick="Calendar(event)" value="<?=$search_start?>"/> ~ <input class="input_bd_st01" type="text" name="search_end" OnClick="Calendar(event)" value="<?=$search_end?>"/>
						<img src=images/btn_today01.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(0)">
						<img src=images/btn_day07.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(1)">
						<img src=images/btn_day14.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(2)">
						<img src=images/btn_day30.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(3)">
					</td>
				</TR>
				<!-- <TR>
					<th><span>브랜드 선택</span></th>
					<TD>
						<select size=1 name="brandname" class="select" <?if($s_check!=4) echo "disabled=\"true\" style=\"background:#f4f4f4\""?> >
							<option value="">전체 브랜드</option>
							<option value="001">FITFLOP</option>
							<option value="002">ROYAL ELASTICS</option>
							<option value="003">FLY FLOT</option>
							<option value="004">ILSE JACOBSEN</option>
							<option value="005">NATIVE SHOES</option>
							<option value="006">AMERI BAG</option>
							<option value="007">BLOWFISH</option>
						</select>
					</TD>
				</TR> -->
				<TR>
					<th><span>검색조건 선택</span></th>
					<TD>
                        <input type=radio name=s_check value="0" onClick="OnChangeSearchType(this.value);" id=idx_s_check0 <?=$check_s_check0?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_s_check0>상품명으로 검색</label>&nbsp;&nbsp;
					    <input type=radio name=s_check value="1" onClick="OnChangeSearchType(this.value);" id=idx_s_check1 <?=$check_s_check1?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_s_check1>작성자로 검색</label>&nbsp;&nbsp;
                        <input type=radio name=s_check value="2" onClick="OnChangeSearchType(this.value);" id=idx_s_check2 <?=$check_s_check2?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_s_check2>관리자작성 검색</label>&nbsp;&nbsp;
					</TD>
				</TR>

				<TR>
					<th><span>검색어 입력</span></th>
					<TD><input name=search size=47 value="<?=$search?>" <?=$search_style?> class="input"> 
						<!-- <select size=1 name=reviewtype class="select">
							<option value="ALL">전체리뷰</option>
							<option value="Y">인증된 리뷰</option>
							<option value="N">인증안된 리뷰</option>
						</select> --> 
						<a href="javascript:CheckSearch();"><img src="images/btn_search2.gif" align=absmiddle  border="0"></a>&nbsp;&nbsp;
						<!-- <?if(isdev()){?>
						<a class="search_btn" href="javascript:SearchAll();" align=absmiddle  border="0" >전체조회</a>
						<?}?> -->
						<!--<a href="javascript:SearchAll();"><img src="images/btn_search2.gif" align=absmiddle  border="0"></a>-->
					</TD>
				</TR>				
				</TABLE>
				<p class="ta_r"><a href="#;"><input type="image" src="img/btn/btn_search01.gif" alt="검색" /></a>&nbsp;<a href="#;" onclick="ReviewExcel();"><img src="images/btn_excel_search.gif" border="0" hspace="1"></a></p>
				</div>
				</td>
			</tr>
			<tr>
				<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">검색 내역</div>
				</td>
			</tr>
			<tr>
				<td>
				<div class="table_style02">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<!--<col width="20"></col>-->
				<col width="100"></col>
				<col width="140"></col>
				<col width=""></col>
				<col width="90"></col>
				<col width="80"></col>
				<col width="80"></col>
				<?php if($review_type=="A"){?>
				<col width="80"></col>
				<?php }?>
				<col width="80"></col>
				<?php if($s_check!="3"){
/*
					if($review_type=="A")
						$colspan=7;
					else
						$colspan=6;
*/
                    $colspan = 9;
				?>
				<TR align=center>
					<!--<th><input type="checkbox" onClick="javascript:allCheck(this);"/></th>-->
					<th>등록일</th>
					<th>작성자 정보</th>
					<th>상품명/리뷰<!-- 및 <FONT color=red>＊</FONT>답글--></th>
					<th>리뷰 타입</th>
					<th>베스트</th>
					<th>포인트</th>
					<th>동일상품</th>
					<?php if($review_type=="A"){?>
					<th>인증</th>
					<?php }?>
					<th width="51">삭제</th>
				</TR>
<?php
				$qry.= "WHERE a.productcode = b.productcode ";
			if($search_all != 'all'){
				if ($reviewtype=="N") {
					$qry.= "AND a.display = 'Y' ";
				} else if ($reviewtype=="Y") {
					$qry.= "AND a.display='N' ";
				}
				$qry.= "AND a.date >= '{$search_s}' AND a.date <= '{$search_e}' ";
				if (strlen(trim($search))>2) {
					if($s_check=="0") {
						$qry.= "AND (b.productname LIKE '%{$search}%' OR a.content LIKE '%{$search}%') ";
					} else if ($s_check=="1") {
						//$qry.= "AND a.id = '{$search}' ";
                        $qry.= "AND a.id = '{$search}' ";
					}

				}
                if ($s_check=="2") {
                    //$qry.= "AND a.id = '{$search}' ";
                    $qry.= "AND a.staff = 'Y' ";
                }
				if($s_check=="3"){
					$qry.= "AND a.best_type = '1' ";
				}
				/*
				if ($s_check=="4") {
					if($brandname!=""){
					$qry.= "AND c.c_category LIKE '004{$brandname}%' ";
					}
				}*/
                if ( $review_class != "" ) {
                    $qry .= "AND a.type = '{$review_class}' ";
                }
			}
				$sql = "SELECT count(*) FROM tblproductreview a, tblproduct b {$qry} ";
				$paging = new Paging($sql,10,20);
				$t_count = $paging->t_count;
				$gotopage = $paging->gotopage;
				
				$sql = "SELECT b.minimage, a.id,a.name,a.reserve,a.display,a.subject,a.content,a.date,a.productcode,b.productname,b.tinyimage,b.selfcode, a.best_type, a.marks, a.type, a.num, a.staff, b.pr_type ";
				$sql.= "FROM tblproductreview a, tblproduct b {$qry} ";
				$sql.= "ORDER BY a.best_type desc, a.date DESC ";
				$excel_sql = $sql;
				$sql = $paging->getSql($sql);
				//exdebug($sql);
				$result = pmysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=pmysql_fetch_object($result)) {
					$number = ($t_count-($setup['list_num'] * ($gotopage-1))-$cnt);
					$contents=explode("=",$row->content);
					//별점
					$marks="";
					for($i=0;$i<$row->marks;$i++){
						$marks.="<FONT color=#000000>★</FONT>";
					}
					for($i=$row->marks;$i<5;$i++){
						$marks.="<FONT color=#DEDEDE>★</FONT>";
					}
					
					
					echo "<tr>\n";
                   // echo "  <td><input type=\"checkbox\" name=\"idx[]\" value=\"{$row->num}\" ></td>\n";
					echo "	<TD align=center class=\"td_con2\">".substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)."</td>\n";
					echo "	<TD>";
					echo "	<NOBR><TABLE cellSpacing=0 cellPadding=0 border=0 width=\"100%\">";
					if (ord($row->id)) {
						echo "	<TR><TD style=\"text-align:left;border-bottom:none;word-break:break-all;\"><img src=\"images/icon_name.gif\" border=\"0\" align=absMiddle> <A HREF=\"javascript:MemberView('{$row->id}');\">[<U>{$row->name}</U>]</A></td></tr>\n";
						echo "	<TR><TD style=\"text-align:left;border-bottom:none;\"><img src=\"images/icon_id.gif\" border=\"0\" align=absMiddle> <A HREF=\"javascript:Searchid('{$row->id}');\">[<U>{$row->id}</U>]</A></td></tr>\n";
						echo "	<TR><TD style=\"text-align:left;border-bottom:none;\"><img src=\"images/icon_order.gif\" border=\"0\" align=absMiddle> <A HREF=\"javascript:OrderInfo('{$row->id}');\">[<U>내역확인</U>]</A></td></tr>\n";
					} else {
						echo "	<TR><TD style=\"text-align:left;border-bottom:none;\"><img src=\"images/icon_name.gif\" border=\"0\" align=absMiddle> [<U>{$row->name}</U>]</td></tr>\n";
                    }
                    $staff=($row->staff=="Y")?"관리자":"회원";
                        echo "	<TR><TD style=\"text-align:left;border-bottom:none;\"><img src=\"images/icon_writer.gif\" border=\"0\" align=absMiddle> [<U>{$staff}</U>]</td></tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "	<TD>";

					echo "	<div class=\"ta_l\"> \n";
					echo "	<table border=0 cellpadding=0 cellspacing=0>\n";
					echo "	<tr>\n";
					echo "		<td rowspan='2' style='padding:10px; border:0px;'><img src='".getProductImage($Dir.DataDir.'shopimages/product/',$row->minimage)."' width='50'></td>\n";
					echo "		<td style=\"text-align:left;border-bottom:none;word-break:break-all;\">\n";
					echo "		<span onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage{$cnt}');\">";
					echo "		<img src=\"images/producttype".($row->assembleuse=="Y"?"y":"n").".gif\" border=\"0\" align=\"absmiddle\" hspace=\"2\"><a href=\"JavaScript:ProductInfo('{$row->productcode}','{$row->pr_type}','YES')\"><font color=#3D3D3D><u>".$row->productname.($row->selfcode?"-".$row->selfcode:"")."</u></font></a>";
					echo "		</span>\n";
					echo "		<div id=primage{$cnt} style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
					echo "		<table border=0 cellspacing=0 cellpadding=0 width=170>\n";
					echo "		<tr bgcolor=#FFFFFF>\n";
					if (ord($row->tinyimage)) {
						echo "			<td align=center width=100% height=150 style=\"BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid\"><img src='".getProductImage($Dir.DataDir.'shopimages/product/',$row->tinyimage)."' width='100%'></td>\n";
					} else {
						echo "			<td align=center width=100% height=150 style=\"BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid\"><img src={$Dir}images/product_noimg.gif></td>\n";
					}
					echo "		</tr>\n";
					echo "		</table>\n";
					echo "		</div>\n";
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td style=\"text-align:left;padding-top:3;border-bottom:none;\"><table border=0 cellpadding=0 cellspacing=0><tr><td valign='top' style='BORDER-BOTTOM: #ffffff 1px solid'><img src=\"images/icon_review.gif\" border=\"0\" align=absMiddle hspace=\"2\"></td><td valign='top' style='text-align:left;BORDER-BOTTOM: #ffffff 1px solid'><a href=\"JavaScript:ReviewReply('{$row->date}','{$row->productcode}')\" title=\"".htmlspecialchars($row->subject)."\"><b>".titleCut(38,$row->subject)."</b><br><p class='ellipsis' style='width: 500px;'>".htmlspecialchars($contents[0])."</p></a></td></tr></table> ";
					if(ord($contents[1])) echo "<font color=red>＊</font>";
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "	</div> \n";

                    echo "  <TD>\n";
                    if ( $row->type == '1' ) {
                        echo "  포토리뷰";
                    } else {
                        echo "  텍스트리뷰";
                    }
                    echo "  </TD>\n";

					echo "	<TD align=center>";
					if($row->best_type){
						//echo $row->best_type;
						echo "Yes";
					}else{
						echo "No";
					}
					//echo "<br />{$marks}";
					echo "	</td>";
					echo "	<TD align=center>";
					if(ord($row->id)==0) {
						echo "<font color=red><B>X</B></font>";
					} else if ($row->reserve==0) {
						echo "<a href=\"javascript:ReserveSet('{$row->id}','{$row->date}','{$row->productcode}')\"><img src=\"images/icon_point02.gif\"  border=\"0\" valign=absmiddle></a>";
					} else {
						echo number_format($row->reserve);
					}
					echo "	</td>\n";
					echo "	<TD align=center><a href=\"javascript:SearchProduct('{$row->productname}');\"><img src=\"images/icon_review1.gif\"  border=\"0\"></a></td>\n";
					if ($review_type=="A") {
						echo "	<TD align=center width=\"59\">";
						if($row->display=="Y") {
							echo "<B>Y</B>";
						} else {
							echo "	<a href=\"javascript:AuthReview('{$row->date}','{$row->productcode}');\"><img src=\"images/btn_ok2.gif\"  border=\"0\" valign=absmiddle></a>";
						}
						echo "	</td>\n";
					}
					echo "	<TD align=center width=\"59\">";
					echo "	<a href=\"javascript:DeleteReview('{$row->date}','{$row->productcode}');\"><img src=\"images/btn_del.gif\"  border=\"0\"></a>";
					echo "	</td>\n";
					echo "</tr>\n";
					$cnt++;
				}
				pmysql_free_result($result);
				if ($cnt==0) {
					echo "<tr><td class=\"td_con2\" colspan={$colspan} align=center>검색된 리뷰 정보가 존재하지 않습니다.</td></tr>";
				}
?>
<!--
                    <tr>
                        <td colspan="<?=$colspan?>">
                            <div style="text-align:center;padding-bottom:40px;">
                                <img src="../admin/images/admin_button05.gif" onclick="javascript:changeBestReview(1);" alt="베스트리뷰 등록">
                                <img src="../admin/images/admin_button06.gif" onclick="javascript:changeBestReview(0);" alt="베스트리뷰 해제">
                            </div>
                        </td>
                    </tr>
-->
				</TABLE>
				</div>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
<?php
				echo "<tr>\n";
				echo "	<td align=center width=\"100%\" class=\"font_size\">\n";
				echo "		".$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page;
				echo "	</td>\n";
				echo "</tr>\n";
			 }else{?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="6"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">No</TD>
					<TD class="table_cell1">이름</TD>
					<TD class="table_cell1">아이디</TD>
					<TD class="table_cell1">평균총점</TD>
					<TD class="table_cell1">리뷰갯수</TD>
					<TD class="table_cell1">포인트</TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
<?php
				$sql = "SELECT COUNT(*) as totcnt, SUM(marks) as totcnt2, id, name FROM tblproductreview ";
				$sql.= "WHERE id != '' AND date >= '{$search_s}' AND date <= '{$search_e}' ";
				if ($reviewtype=="N") {
					$sql.= "AND display = 'Y' ";
				} else if ($reviewtype=="Y") {
					$sql.= "AND display = 'N' ";
				}
				$sql.= "GROUP BY id ORDER BY totcnt DESC LIMIT 20 ";
				$result = pmysql_query($sql,get_db_conn());
				$cnt = 0;
				while($row=pmysql_fetch_object($result)) {
					$cnt++;
					echo "<tr>\n";
					echo "	<TD align=center class=\"td_con2\">{$cnt}</td>\n";
					echo "	<TD align=center class=\"td_con1\">&nbsp;<A HREF=\"javascript:MemberView('{$row->id}');\">{$row->name}</A></td>\n";
					echo "	<TD align=center class=\"td_con1\">&nbsp;<A HREF=\"javascript:Searchid('{$row->id}');\"><B>{$row->id}</B></A></td>\n";
					echo "	<TD align=center class=\"td_con1\">".(float) round($row->totcnt2/$row->totcnt,1)."</td>\n";
					echo "	<TD align=center class=\"td_con1\">{$row->totcnt}</td>\n";
					echo "	<TD align=center class=\"td_con1\"><A HREF=\"javascript:ReserveSet('{$row->id}','".date("YmdHis")."','');\"><img src=\"images/icon_pointi.gif\" border=\"0\" valign=absmiddle></A></td>\n";
					echo "</tr>\n";
				}
				pmysql_free_result($result);
				if ($cnt==0) {
					echo "<tr><td class=lineleft colspan=6 align=center>검색된 리뷰 정보가 존재하지 않습니다.</td></tr>";
				}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="6"></TD>
				</TR>

				<?php }?>

				</table>
				</td>
			</tr>
			</form>
			

			<form name=rForm action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=date>
			<input type=hidden name=productcode>
			<input type=hidden name=s_check value="<?=$s_check?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=reviewtype value="<?=$reviewtype?>">
            <input type=hidden name=review_class value="<?=$review_class?>">
			<input type=hidden name=vperiod value="<?=$vperiod?>">
			<input type=hidden id="search_all" name="search_all" value="<?=$search_all?>">
			</form>

			<form name=form2 action="product_register.php" method=post>
			<input type=hidden name=code>
			<input type=hidden name=prcode>
			<input type=hidden name=popup>
			</form>

			<form name=form3 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type value="<?=$type?>">
			<input type=hidden name=block>
			<input type=hidden name=gotopage>
			<input type=hidden name=s_check value="<?=$s_check?>">
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=productcode value="<?=$productcode?>">
			</form>

			<form name=form4 action="member_list.php" method=post>
			<input type=hidden name=search>
			</form>

			<form name=orderform action="orderinfopop.php" method=post>
			<input type=hidden name=id>
			</form>

			<form name=replyform action="product_reviewreply.php" method=post>
			<input type=hidden name=date>
			<input type=hidden name=productcode>
			</form>

			<form name=form5 action="reserve_money_new.php" method=post>
			<input type=hidden name=type>
			<input type=hidden name=id>
			<input type=hidden name=date>
			<input type=hidden name=productcode>
			</form>

			<form name="product_excel" id="product_excel" method="post">
				<input type=hidden name="excel_sql" value="<?=$excel_sql?>">
			</form>			
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<div class="sub_manual_wrap">
						<div class="title"><p>매뉴얼</p></div>
						<dl>
							<dt><span>상품 리뷰 관리</span></dt>
							<dd>
							- 회원아이디로 검색시 정확한 아이디 입력을 하셔야만 검색이 됩니다.<br>
							- [회원이름] 클릭시 해당 회원의 정보를 확인하실 수 있습니다.<br>
							- [회원아이디] 클릭시 해당 회원아이디로 리뷰 검색이 이루어집니다.<br>
							- [내역확인] 클릭시 해당 회원의 구매내역을 확인할 수 있습니다.<br>
							- 상품명을 클릭시 해당 상품 카테고리내 상품들의 정보를 확인하실 수 있습니다.<br>
							- [새창] 버튼 클릭시 해당 상품의 정보를 수정할 수 있습니다.<Br>
							- 리뷰 클릭시 해당 리뷰의 전체 내용 및 답변을 등록할 수 있습니다.<br>
							- [포인트 지급] 버튼 클릭시 해당 리뷰 작성자에게 포인트를 지급/차감할 수 있습니다.<br>
							- [다른리뷰 보기] 버튼 클릭시 해당 상품명으로 리뷰 검색이 이루어집니다.<br>
							- [삭제] 버튼 클릭시 해당 리뷰가 삭제됩니다.
							</dd>
	
						</dl>
						<dl>
							<dt><span>상품 리뷰 관리 주의사항</span></dt>
							<dd>
							- 삭제된 리뷰는 복원되지 않으므로 신중히 처리하시기 바랍니다.<br>
							- 포인트 지급으로 인한 포인트/차감된 포인트는 복원되지 않으므로 신중히 처리하시기 바랍니다.
							</dd>

						</dl>

					</div>
				</td>
			</tr>
			</table></div>
<?php 
include("copyright.php");
