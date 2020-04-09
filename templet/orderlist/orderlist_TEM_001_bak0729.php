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


//$mb_qry="select * from tblmainbannerimg order by banner_sort";

/*
if (stripos($shop_intro,"<table")!==false || strlen($mainflash)>0)
	$main_banner=$shop_intro;
else
	$main_banner=nl2br($shop_intro);
*/
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
</style>

<?
		$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
		$result=pmysql_query($sql,get_db_conn());
		$delicomlist=array();
		while($row=pmysql_fetch_object($result)) {
			$delicomlist[$row->code]=$row;
		}
		pmysql_free_result($result);

		$s_curtime=strtotime("$s_year-$s_month-$s_day");
		$s_curdate=date("Ymd",$s_curtime);
		$e_curtime=strtotime("$e_year-$e_month-$e_day");
		$e_curdate=date("Ymd",$e_curtime)."999999999999";

		$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, order_conf,2 as ordertype, oi_step1, oi_step2, order_msg2 ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		//if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		//else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		//else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		//$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= "AND oi_step2 < 40";
		$sql.= "ORDER BY ordercode DESC ";


		$paging = new New_Templet_paging($sql, 10,  10, 'GoPage', true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		#$result3=pmysql_query($sql,get_db_conn());
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());

		//exdebug("##");
 ?>



	<div class="containerBody sub-page">

		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li><a href="mypage.php">MY PAGE</a></li>
				<li class="on"><a>주문배송조회</a></li>
			</ul>
		</div>

		<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?>
			<!---->
		</div><!-- //LNB -->

		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">

            <div class="order-list-flow">
            	<dl>
                	<dt><span>01</span>주문접수</dt>
                    <dd>상품 주문접수,<br />입금 확인중입니다.</dd>
                </dl>
                <dl>
                	<dt><span>02</span>결제완료</dt>
                    <dd>결제 및 무통장 입금<br />확인 완료되었습니다.</dd>
                </dl>
                <dl>
                	<dt><span>03</span>배송준비중</dt>
                    <dd>상품을 준비하고<br />있습니다.</dd>
                </dl>
                <dl>
                	<dt><span>04</span>배송중</dt>
                    <dd>택배사 발송을 완료,<br />배송 중입니다.</dd>
                </dl>
                <dl>
                	<dt><span>05</span>배송완료</dt>
                    <dd>고객님이 상품을<br />수취한 상태입니다.</dd>
                </dl>
            </div>
			<h4 class="mypage-title mt-30">주문배송조회</h4>
			<!-- 날짜 설정 -->
			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="date_find_wrap mt-5">
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

			<!-- 주문/배송 -->
			<div class="order-list-wrap">


				<table class="th-top util top-line-none">
					<colgroup>
						<col style="width:200px" ><col style="width:80px" ><col style="width:auto" ><col style="width:145px" ><col style="width:130px" ><!-- <col style="width:120px" > -->
					</colgroup>
					<thead>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>결제금액</th>
						<th>진행상태</th>
						<!-- <th>요청사항</th> -->
					</tr>
					</thead>

<?
		$cnt=0;
		if ($t_count > 0) {
			while($row=pmysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

			$ord_time=substr($row->ord_time,0,2).":".substr($row->ord_time,2,2).":".substr($row->ord_time,4,2);
?>
					<tr>
						<? $sql2 = "SELECT idx, productcode, productname, MAX(deli_com) deli_com, MAX(deli_num) deli_num FROM tblorderproduct  ";
						$sql2 .= " WHERE ordercode='".$row->ordercode."' ";
						$sql2 .= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') /*AND option_type = 0*/ group by idx, productcode, productname";
						//exdebug($sql2);
						$res22 = pmysql_query($sql2);
						$result2=pmysql_fetch_object($res22);
						$resultforcnt=pmysql_num_rows($res22);
						$deli_company = "";
						$deli_number = "";
						/*while($row2=pmysql_fetch_array($resultforcnt)){
							if(!$deli_company) $deli_company = $row2[deli_com];
							if(!$deli_number) $deli_number = $row2[deli_num];
						}*/
						if($deli_company=="") $deli_company = $result2->deli_com;
						if($deli_number=="") $deli_number = $result2->deli_num;
						//$imgsrc = getMaxImageForXn($result2->productcode);
						$imgRes = pmysql_query( "SELECT tinyimage FROM tblproduct WHERE productcode = '{$result2->productcode}' ",get_db_conn() );
						$imgRow = pmysql_fetch_object( $imgRes );

						$imgsrc = getProductImage($Dir.DataDir.'shopimages/product/', $imgRow->tinyimage);

						pmysql_free_result($imgRes);
						?>
						<td><a href="javascript:OrderDetail('<?=$row->ordercode?>')"><?=$row->ordercode?></a></td>
						<td class="ta_l">
							<a href="../front/productdetail.php?productcode=<?=$result2->productcode?>">
								<img src="<?=$imgsrc?>" alt="" class='img-size-mypage' >
							</a>
						</td>
						<td class="ta_l">
							<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
								<?
									switch($resultforcnt){
										case 1 :
											echo $result2->productname;
										break;
										default : $resultforcnt--;
											echo $result2->productname." 외 ".$resultforcnt."건";
									}
								?>
							</a>
							<!--div class="opt"><?=$result2->opt1_name?></div>
							<div class="opt"><?=$result2->opt2_name?></div-->
						</td>
						<td><b><?=number_format($row->price-$row->dc_price-$row->reserve+$row->deli_price)?>원</b></td>
						<td>
							<span class="delivery-step"><?=$o_step[$row->oi_step1][$row->oi_step2]?></span><br><a href="javascript:OrderDetail('<?=$row->ordercode?>')" class="btn-dib-line"><span>상세보기</span></a>
						</td>
						<!-- <td><?=$row->order_msg2?></td> -->
					</tr>
<?
		$cnt++;
		}
	} else {
?>
					<tr>
						<td colspan="6">내역이 없습니다.</td>
					</tr>
<?
	}
?>
				</table>
				<div class="paging mt_30"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div>
				<dl class="attention mt-70">
					<dt>유의사항</dt>
					<dd>[주문번호]를 클릭하시면 [주문상세내역]을 확인할 수 있습니다.</dd>
					<dd>주문완료 후, 재고부족 등으로 인해 품절취소가 될 수 있으니 이점 양해 부탁 드립니다.</dd>
					<dd>"결제완료"상태까지는 옵션 및 배송지 변경이 가능하나, "배송준비중" 이후에는 불가능하오니 고객센터로 문의 바랍니다.</dd>
					<dd>주문상태가 "배송 중"이 되면 상품에 대한 "배송조회"가 가능합니다. (도서/산간 제외, 일반지역 약 1~2일 정도 소요)</dd>
					<dd>여러 브랜드의 상품을 함께 주문하신 경우 브랜드별로 따로 배송될 수 있습니다.</dd>
					<div class="btn-place"><a href="mypage_personal.php" class="btn-dib-function"><span>1:1 문의</span></a></div>
				</dl>
			</div><!-- //최근주문정보 -->

		</div><!-- 내용 -->
	</div>
<style type="text/css">
	.layer {display:none; position:fixed; _position:absolute; top:0; left:0; width:100%; height:100%; z-index:100;}
	.layer .bg {position:absolute; top:0; left:0; width:100%; height:100%; background:#000; opacity:.5; filter:alpha(opacity=50);}
	.layer .pop-layer {display:block;}

	.pop-layer {display:none; position: absolute; top: 50%; left: 50%; width: 410px; height:auto;  background-color:#fff; border: 2px solid #000; z-index: 10;}
	.pop-layer .pop-container {padding: 20px 25px;}
	.pop-layer p.ctxt {color: #666; line-height: 25px;}
</style>
<div class="layer" style="display: none;">
	<div class="bg"></div>
	<div id="layer1" class="pop-layer">
		<div class="pop-container">
			<div class="pop-conts">
				<!--content //-->
				<h3 class="title mb_20">반송 신청</h3>
				<table class="th_left" cellpadding="0" cellspacing="0" border="0" width="100%">
					<colgroup>
						<col style="width:120px"><col style="width:auto">
					</colgroup>
					<tbody>
					<tr>
						<th>반송사유</th>
						<td>
							<textarea name="redelivery_reason_content" id="redelivery_reason_content" rows="10" style="width: 98%;resize: none;"></textarea>
							<input type="hidden" id="redelivery_ordercode" >
							<input type="hidden" id="redelivery_obj" >
						</td>
					</tr>
					</tbody>
				</table>
				<div class="ta_c mt_20">
					<a href="javascript:;" id="redelivery_reson_on" class="btn_D on">반송신청</a>
					<a href="javascript:;" id="redelivery_reson_close" class="btn_D cbtn">취소</a>
				</div>
				<!--// content-->
			</div>
		</div>
	</div>
</div>

<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>
</div>
