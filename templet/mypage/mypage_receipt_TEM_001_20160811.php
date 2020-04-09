<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$imagepath=$Dir.DataDir."shopimages/etc/main_logo.gif";
$flashpath=$Dir.DataDir."shopimages/etc/main_logo.swf";

if (file_exists($imagepath)) {
	$mainimg="<img src=\"".$imagepath."\" border=\"0\" align=\"absmiddle\">";
} else {
	$mainimg="";
}
if (file_exists($flashpath)) {
	if (preg_match("/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/",$_data->shop_intro,$match)) {
		$width=$match[1];
		$height=$match[2];
	}
	$mainflash="<script>flash_show('".$flashpath."','".$width."','".$height."');</script>";
} else {
	$mainflash="";
}
$pattern=array("(\[DIR\])","(\[MAINIMG\])","/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/");
$replace=array($Dir,$mainimg,$mainflash);
$shop_intro=preg_replace($pattern,$replace,$_data->shop_intro);


$mb_qry="select * from tblmainbannerimg order by banner_sort";


if (stripos($shop_intro,"<table")!==false || strlen($mainflash)>0)
	$main_banner=$shop_intro;
else
	$main_banner=nl2br($shop_intro);
?>
<style>
/** 달력 팝업 **/
.calendar_pop_wrap {position:relative; background-color:#FFF;}
.calendar_pop_wrap .calendar_con {position:absolute; top:0px; left:0px;width:247px; padding:10px; border:1px solid #b8b8b8; background-color:#FFF;}
.calendar_pop_wrap .calendar_con .month_select { text-align:center; background-color:#FFF; padding-bottom:10px;}
.calendar_pop_wrap .calendar_con .day {clear:both;border-left:1px solid #e4e4e4;}
.calendar_pop_wrap .calendar_con .day th {background:url('../admin/img/common/calendar_top_bg.gif') repeat-x; width:34px; font-size:11px; border-top:1px solid #9d9d9d;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; padding:6px 0px 4px;}
.calendar_pop_wrap .calendar_con .day th.sun {color:#ff0012;}
.calendar_pop_wrap .calendar_con .day td {border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; background-color:#FFF; width:34px;  font-size:11px; text-align:center; font-family:tahoma;}
.calendar_pop_wrap .calendar_con .day td a {color:#35353f; display:block; padding:2px 0px;}
.calendar_pop_wrap .calendar_con .day td a:hover {font-weight:bold; color:#ff6000; text-decoration:none;}
.calendar_pop_wrap .calendar_con .day td.pre_month a {color:#fff; display:block; padding:3px 0px;}
.calendar_pop_wrap .calendar_con .day td.pre_month a:hover {text-decoration:none; color:#fff;}
.calendar_pop_wrap .calendar_con .day td.today {background-color:#52a3e7; }
.calendar_pop_wrap .calendar_con .day td.today a {color:#fff;}
.calendar_pop_wrap .calendar_con .close_btn {text-align:center; padding-top:10px;}

.CLS_oldOrderViewLayer{
		border:2px solid gray;
		width:0px;
		height:0px;
		overflow:hidden;
		position:absolute;
		z-index:999;
		background:#ffffff;
		/*display:none;*/
}
</style>


<body<?=($_data->layoutdata["MOUSEKEY"][0]=="Y"?" oncontextmenu=\"return false;\"":"")?><?=($_data->layoutdata["MOUSEKEY"][1]=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=($_data->layoutdata["MOUSEKEY"][2]=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>


<?
		$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
		$result=pmysql_query($sql,get_db_conn());
		$delicomlist=array();
		while($row=pmysql_fetch_object($result)) {
			$delicomlist[$row->code]=$row;
		}
		pmysql_free_result($result);

		$sql = "SELECT receive_ok,ordercode,regdt as ord_date, to_char( SUBSTR( regdt, 1, 8 )::date, 'YYYY-MM-DD' ) as regdt, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 2 as ordertype ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND CHAR_LENGTH( bank_date ) = 14 AND pay_admin_proc != 'C' ";
		$sql.= "AND pay_flag != '9999' AND oi_step2 = '0' ";
		//$sql.= "AND ( regdt >= '".$s_curdate."' AND regdt <= '".$e_curdate."' ) ";
		$sql.= "ORDER BY ordercode DESC ";
		//exdebug( $sql );

		$paging = new New_Templet_paging($sql, 10, 10, 'GoPage', true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		#$result3=pmysql_query($sql,get_db_conn());
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());

		//exdebug("##");
 ?>
<!--
<div class="pop_receipt_cash " style="display:none">
	<form action = "mypage_receipt.indb.php" method = "POST">
		<input type = 'hidden' name = 'mode' value = 'receipt_cash'>
		<input type = 'hidden' name = 'ordercode'>
		<div class="pop_layer_type01">
			<h4>현금영수증 신청</h4>
			<a href="javascript:;" class="close">닫기</a>
		</div>
		<div class="form">
			<table class="receipt" width="100%">
				<colgroup>
					<col style="width:100px" ><col style="width:auto" >
				</colgroup>
				<tr>
					<th>구분</th>
					<td><input type="radio" name="up_tr_code" value="0" checked>개인 <input type="radio" name="up_tr_code" value="1">사업자</td>
				</tr>
				<tr class = 'CLS_handphone'>
					<th>핸드폰번호</th>
					<td>
						<input type="text" name="up_mobile1" maxlength = '3'> -
						<input type="text" name="up_mobile2" maxlength = '4'> -
						<input type="text" name="up_mobile3" maxlength = '4'>
					</td>
				</tr>
				<tr class = 'CLS_biznumber' style = 'display:none;'>
					<th>사업자번호</th>
					<td><input type="text" name="up_comnum1"> - <input type="text" name="up_comnum2"> - <input type="text" name="up_comnum3"></td>
				</tr>
			</table>
			<div class="ta_c"><a href="javascript:;" class = 'btn_pop_gray CLS_submitCash'>현금영수증 신청</a></div>
		</div>
	</form>
</div>

<div class="pop_receipt_tax" style="display:none">
	<form action = "mypage_receipt.indb.php" method = "POST">
		<input type = 'hidden' name = 'mode' value = 'receipt_tax'>
		<input type = 'hidden' name = 'ordercode'>
		<div class="pop_layer_type01">
			<h4>세금계산서 신청</h4>
			<a href="javascript:;" class="close">닫기</a>
		</div>
		<div class="form">
			<table class="receipt" width="100%">
				<colgroup>
					<col style="width:100px" ><col style="width:auto" >
				</colgroup>
				<tr>
					<th>회사명</th>
					<td><input class="w140" type="text" name="up_company"></td>
				</tr>
				<tr>
					<th>사업자번호</th>
					<td>
						<input class="ta_c" type="text" name="up_comnum1" maxlength = "3"> -
						<input class="ta_c" type="text" name="up_comnum2" maxlength = "2"> -
						<input class="ta_c" type="text" name="up_comnum3" maxlength = "5">
					</td>
				</tr>
				<tr>
					<th>대표자명</th>
					<td><input class="w100" type="text" name="up_name"></td>
				</tr>
				<tr>
					<th>업태</th>
					<td><input class="w100" type="text" name="up_service"></td>
				</tr>
				<tr>
					<th>종목</th>
					<td><input class="w100" type="text" name="up_item"></td>
				</tr>
				<tr>
					<th>사업장 주소</th>
					<td><input class="w300" type="text" name="up_address"></td>
				</tr>
			</table>
			<div class="ta_c"><a href="javascript:;" class = 'btn_pop_gray CLS_submitTax'>세금계산서 신청</a></div>
		</div>
	</form>
</div> -->

<!-- 현금영수증 신청 dimm layer -->
<div class="layer-dimm-wrap layer-receipt01" >
	<form action = "mypage_receipt.indb.php" method = "POST">
	<input type = 'hidden' name = 'mode' value = 'receipt_cash'>
	<input type = 'hidden' name = 'ordercode'>
    <div class="dimm-bg"></div>
    <div class="layer-inner auto-align-inner">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">
			<table class="th-left-util">
				<colgroup>
					<col style="width:100px" ><col style="width:auto" >
				</colgroup>
				<tr>
					<th scope="col">구분</th>
					<td>
						<input type="radio" class="radio-def" name="up_tr_code" value="0" id="type1" checked><label for="type1">개인</label>
						<input type="radio" class="radio-def" name="up_tr_code" value="1" id="type2"><label for="type2">사업자</label>
					</td>
				</tr>
				<tr class = 'CLS_handphone'>
					<th scope="col">핸드폰번호</th>
					<td>
						<input class="input-def w70" type="text" name="up_mobile1" maxlength = '3'> -
						<input class="input-def w70" type="text" name="up_mobile2" maxlength = '4'> -
						<input class="input-def w70" type="text" name="up_mobile3" maxlength = '4'>
					</td>
				</tr>
				<tr class = 'CLS_biznumber' style = 'display:none;'>
					<th scope="col">사업자번호</th>
					<td><input type="text" name="up_comnum1"> - <input type="text" name="up_comnum2"> - <input type="text" name="up_comnum3"></td>
				</tr>
			</table>
			<div class="btn-place"><a href="javascript:;" class = 'btn-dib-function CLS_submitCash'><span>현금영수증 신청</span></a></div>
        </div>
    </div>
	</form>
</div>

<!-- 세금계산서 신청 dimm layer -->
<div class="layer-dimm-wrap layer-receipt02">
	<form action = "mypage_receipt.indb.php" method = "POST">
	<input type = 'hidden' name = 'mode' value = 'receipt_tax'>
	<input type = 'hidden' name = 'ordercode'>
    <div class="dimm-bg"></div>
    <div class="layer-inner auto-align-inner">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">
			<table class="th-left-util">
				<colgroup>
					<col style="width:100px" ><col style="width:auto" >
				</colgroup>
				<tr>
					<th>회사명</th>
					<td><input class="input-def" type="text" name="up_company"></td>
				</tr>
				<tr>
					<th>사업자번호</th>
					<td>
						<input class="input-def w70" type="text" name="up_comnum1" maxlength = "3"> -
						<input class="input-def w70" type="text" name="up_comnum2" maxlength = "2"> -
						<input class="input-def w70" type="text" name="up_comnum3" maxlength = "5">
					</td>
				</tr>
				<tr>
					<th>대표자명</th>
					<td><input class="input-def" type="text" name="up_name"></td>
				</tr>
				<tr>
					<th>업태</th>
					<td><input class="input-def" type="text" name="up_service"></td>
				</tr>
				<tr>
					<th>종목</th>
					<td><input class="input-def" type="text" name="up_item"></td>
				</tr>
				<tr>
					<th>사업장 주소</th>
					<td><input class="input-def w300" type="text" name="up_address"></td>
				</tr>
			</table>
			<div class="btn-place"><a href="javascript:;" class = 'btn-dib-function CLS_submitTax'><span>세금계산서 신청</span></a></div>
        </div>
    </div>
	</form>
</div>


<div class="containerBody sub-page">

	<div class="breadcrumb">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="mypage.php">MY PAGE</a></li>
			<li class="on"><a>증빙서류발급</a></li>
		</ul>
	</div>

	<!-- LNB -->
	<div class="left_lnb">
		<? include ($Dir.FrontDir."mypage_TEM01_left.php");?>
		<!---->
	</div><!-- //LNB -->

	<!-- 내용 -->
	<div class="right_section mypage-content-wrap">

	<div class="receipt-list-wrap">

		<div class="mypage-title">증빙서류 발급</div>
		<!-- 날짜 설정 -->
		<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
		<div class="date_find_wrap">
			<ul class="date_setting">
				<li class="title">기간별 조회</li>
				<li class="date">
					<?
						if(!$day_division) $day_division = '1MONTH';

					?>
					<?foreach($arrSearchDate as $kk => $vv){?>
						<?
							$dayClassName = "";
							if($day_division != $kk){
								$dayClassName = 'btn_white_s';
							}else{
								$dayClassName = 'btn_black_s';
							}
						?>
						<a href="Javascript:;" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this)"><?=$vv?></a>
					<?}?>

				</li>
				<li class="title">일자별 조회</li>
				<li class="date">
					<div class="input_bg"><input type="text" name="date1" id="" value="<?=$strDate1?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> ~
					<div class="input_bg"><input type="text" name="date2" id="" value="<?=$strDate2?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> &nbsp;&nbsp;
					<a href="javascript:CheckForm();" class="btn-dib-function"><span>SEARCH</span></a>
				</li>

			</ul>
		</div>
		</form><!-- //날짜 설정 -->

		<table class="th-top util top-line-none">
			<caption>증빙서류 발급 내역 리스트</caption>
			<colgroup>
				<col style="width:190px" >
				<col style="width:auto" >
				<col style="width:150px" >
				<col style="width:150px" >
				<col style="width:140px" >
			</colgroup>
			<thead>
				<tr>
					<th scope="col">주문정보</th>
					<th scope="col">상품정보</th>
					<th scope="col">결제금액</th>
					<th scope="col">결제방법</th>
					<th scope="col">발급</th>
				</tr>
			</thead>
			<tbody>
<?php
	$cnt=0;
	while($row=pmysql_fetch_object($result)) {
		$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

		$ord_time=substr($row->ord_time,0,2).":".substr($row->ord_time,2,2).":".substr($row->ord_time,4,2);

		$sql2 = "SELECT productcode, productname, MAX(deli_com) deli_com, MAX(deli_num) deli_num FROM tblorderproduct  ";
		$sql2 .= " WHERE ordercode='".$row->ordercode."' ";
		$sql2 .= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') AND option_type = 0 group by productcode, productname";
		$cnt2=0;
		$result2=pmysql_fetch_object(pmysql_query($sql2));
		$resultforcnt=pmysql_query($sql2);
		$deli_company = "";
		$deli_number = "";
		while($row2=pmysql_fetch_object($resultforcnt)){
			if(!$deli_company) $deli_company = $row2->deli_com;
			if(!$deli_number) $deli_number = $row2->deli_num;
			$cnt2++;
		}

		list($cash_receipt_count) = pmysql_fetch("select count(ordercode) from tbltaxsavelist where ordercode='".$row->ordercode."'");
		list($tax_receipt_count) = pmysql_fetch("select count(ordercode) from tbltaxcalclist where ordercode='".$row->ordercode."'");
		list($authno) = pmysql_fetch("select authno from tbltaxsavelist where ordercode='".$row->ordercode."'");

		$imgRes = pmysql_query( "SELECT tinyimage FROM tblproduct WHERE productcode = '{$result2->productcode}' ",get_db_conn() );
		$imgRow = pmysql_fetch_object( $imgRes );

		if(strlen($imgRow->tinyimage)!=0) {
			$imgsrc = getProductImage($Dir.DataDir.'shopimages/product/',$imgRow->tinyimage);
		}else {
			$imgsrc = $Dir."images/no_img.gif";
		}

		pmysql_free_result($imgRes);
?>
				<tr>
					<td><strong><?=$row->ordercode?></strong><br>(<?=$row->regdt?>)</td>
					<td class="subject">
<?php
		switch($cnt2){
			case "1" :
				echo $result2->productname;
			break;
			default : $cnt2--;
				echo $result2->productname." 외 ".$cnt2."건";
		}
?>
					</td>
					<td><?=number_format($row->price-$row->dc_price-$row->reserve+$row->deli_price)?>원</td>
					<td>
<?php
		switch( $row->paymethod[0] ){
			case "C";
				echo '카드결제';
				break;
			case "B";
				echo '무통장';
				break;
			case "O";
				echo '가상계좌';
				break;
			case "Q";
				echo '에스크로';
				break;
			case "P";
				echo '카드결제';
				break;
			case "M";
				echo '휴대폰';
				break;
			case "V";
				echo '계좌이체';
				break;
		}

?>
				</td>
				<td>
					<button href="#" class="btn-dib-line  pop_ViewTaxPrint" type="button" ordercode = '<?=$row->ordercode?>' >
						<span>거래명세표</span>
					</button>
<?php
		if($row->paymethod == 'B' || $row->paymethod[0] == 'V' || $row->paymethod[0] == 'O'){
			if(!$cash_receipt_count){
?>
				<button href="#" class="btn-dib-line  btn-receipt01" type="button" ordercode = '<?=$row->ordercode?>' >
					<span>현금영수증 신청</span>
				</button>
<?php
			}else{
?>
				<button href="#" class="btn-dib-line  pop_receiptView" type="button" ordercode = '<?=$row->ordercode?>||<?=$authno?>' >
					<span>영수증확인</span>
				</button>
<?php
			} // cash_receipt_count if
			if(!$tax_receipt_count){
?>
				<button href="#" class="btn-dib-line  btn-receipt02" type="button" ordercode = '<?=$row->ordercode?>' >
					<span>세금계산서 신청</span>
				</button>
<?php
			} // tax_receipt_count if
		}else if( $row->paymethod[0] == 'C' || $row->paymethod[0] == 'P' ){
?>
				<button href="#" class="btn-dib-line  pop_receiptCardView" type="button" ordercode = '<?=$row->ordercode?>' >
					<span>신용카드 매출전표</span>
				</button>
<?php
			if(!$tax_receipt_count){
?>
				<button href="#" class="btn-dib-line  btn-receipt02" type="button" ordercode = '<?=$row->ordercode?>' >
					<span>세금계산서 신청</span>
				</button>
<?php
			} // tax_receipt_count if
		} else {
?>
				-
<?php
		}
?>
				</td>
				</tr>
<?php
		$cnt++;
	}
	pmysql_free_result($result);
	if ($cnt==0) {
		echo " <tr><td colspan='5'>내역이 없습니다.</td></tr>";
	}
?>
				<!-- <tr>
					<td><strong>2016020114463992315A</strong><br>(2016-02-04)</td>
					<td class="subject">POSTCARD 01 외 1건</td>
					<td>11,400원</td>
					<td>카드결제</td>
					<td><button href="#" class="btn-dib-line  btn-receipt02" type="button"><span>세금계산서 신청</span></button></td>
				</tr>
				<tr>
					<td><strong>2016020114463992315A</strong><br>(2016-02-04)</td>
					<td class="subject">POSTCARD 01 외 1건</td>
					<td>11,400원</td>
					<td>실시간<br>계좌이체</td>
					<td><button href="#" class="btn-dib-line  btn-receipt01" type="button"><span>현금영수증 신청</span></button></td>
				</tr>
				<tr>
					<td><strong>2016020114463992315A</strong><br>(2016-02-04)</td>
					<td class="subject">POSTCARD 01 외 1건</td>
					<td>11,400원</td>
					<td>모바일</td>
					<td></td>
				</tr> -->
			</tbody>
		</table>
		<div class="paging"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div>
		<dl class="attention">
			<dt>유의사항</dt>
			<dd>마일리지는 구매금액 제한 없이 현금처럼 사용하실 수 있습니다.</dd>
			<dd>마일리지는 부여된 해로부터 5년 이내에 사용하셔야 합니다.</dd>
			<dd>특정 이벤트 당첨 마일리지는 이벤트 기한내에서만 사용이 가능하고 미사용 적립금은 소멸됩니다.</dd>
			<dd>마일리지는 부여 된 순서로 사용 되며 해당 기간 내에 사용하지 않으실 경우, 잔여 마일리지는 1년 단위로 매해 12월 31일 자동 소멸 됩니다.</dd>
			<dd>회원탈퇴 시 보유마일리지는 소멸되며 추후 복구는 불가합니다.</dd>
		</dl>

	</div><!-- //.right_section -->
</div><!-- //.containerBody -->

<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>
</div>
</BODY>
</HTML>
