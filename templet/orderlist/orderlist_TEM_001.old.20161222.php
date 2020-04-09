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

		/*$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, order_conf,2 as ordertype, oi_step1, oi_step2, order_msg2 ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		$sql.= "AND oi_step2 < 40";
		$sql.= "ORDER BY ordercode DESC ";*/

		$sql = "SELECT
				a.ordercode,
				min(a.id) as id,
				min(a.price) as price,
				min(a.deli_price) as deli_price,
				min(a.dc_price) as dc_price,
				min(a.reserve) as reserve,
				min(a.paymethod) as paymethod,
				min(a.oi_step1) as oi_step1,
				min(a.oi_step2) as oi_step2,
				min(b.productname) as productname,
				min(b.productcode) as productcode,
				min(a.redelivery_type) as redelivery_type,
				min(a.order_conf) as order_conf,
				(Select tinyimage from tblproduct where productcode = min(b.productcode)) as tinyimage,
				(Select brandname from tblproduct p left join tblproductbrand pb on p.brand=pb.bridx where p.productcode = min(b.productcode)) as brandname,
				(select sum(option_quantity) from tblorderproduct op where op.ordercode = a.ordercode) quantity,
				(select count(*) from tblorderproduct op where op.ordercode = a.ordercode) prod_cnt,
                (select count(*) from tblorderproduct op2 where op2.store_stock_yn = 'N' and op2.ordercode = a.ordercode) stock_yn_cnt
				FROM tblorderinfo a join tblorderproduct b on a.ordercode = b.ordercode join tblvenderinfo v on b.vender = v.vender
				WHERE a.id='".$_ShopInfo->getMemid()."'
				AND b.option_type = 0 AND a.oi_step1 in (0,1,2,3,4) And a.oi_step2 in (0,40,41,42,44)
				AND a.ordercode >= '".$s_curdate."' AND a.ordercode <= '".$e_curdate."'
				GROUP BY a.ordercode
				ORDER BY a.ordercode DESC ";


		$paging = new New_Templet_paging($sql, 10,  10, 'GoPage', true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
 ?>
 <div id="contents">
	 <!-- 네비게이션 -->
	<div class="top-page-local">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="<?=$Dir?>front/mypage.php">마이 페이지</a></li>
			<li class="on">주문/배송</li>
		</ul>
	</div>
	<!-- //네비게이션-->
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<!-- LNB -->
			<? include  "mypage_TEM01_left.php";  ?>
			<!-- //LNB -->

			<article class="mypage_content">
				<section class="mypage_main">
					<div class="order_list_flow">
						<div class="my_info">
							<ul>
								<li>
									<div><p>1 입금대기</p><p>입금 확인 중입니다<br>결제를 완료해 주세요</p></div>
								</li>
								<li>
									<div><p>2 결제완료</p><p>결제가 완료되어<br>주문정보를 확인중입니다</p></div>
								</li>
								<li>
									<div><p>3 상품포장</p><p>상품을 포장한 후<br>배송 대기중 입니다.</p></div>
								</li>
								<li>
									<div><p>4 배송중</p><p>상품이 발송되어<br>고객님께 배송중입니다.</p></div>
								</li>
								<li>
									<div><p>5 배송완료</p><p>고객님께서 상품을 수령하였습니다.<br>구매확정을 눌러주세요.</p></div>
								</li>
							</ul>
						</div>
					</div> <!-- // order_list_flow -->

					<!-- 주문내역 리스트 -->
					<div class="order_list_wrap mt-50">
						<div class="order_right">
							<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
							<div class="total">총 <?=number_format($t_count)?>건</div>
							<div class="date-sort clear">
								<div class="type month">
									<p class="title">기간별 조회</p>
								<?
									if(!$day_division) $day_division = '1MONTH';

								?>
								<?foreach($arrSearchDate as $kk => $vv){?>
									<?
										$dayClassName = "";
										if($day_division != $kk){
											$dayClassName = '';
										}else{
											$dayClassName = 'on';
										}
									?>
									<button type="button" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this)"><span><?=$vv?></span></button>
								<?}?>
								</div>
								<div class="type calendar">
									<p class="title">일자별 조회</p>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="date1" id="" value="<?=$strDate1?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
									<span>-</span>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="date2" id="" value="<?=$strDate2?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
								</div>
								<button type="button" class="btn-go" onClick="javascript:CheckForm();"><span>검색</span></button>
							</div>
						</div>
						</form>
						<table class="th_top">
							<caption></caption>
							<colgroup>
								<col style="width:5%">
								<col style="width:20%">
								<col style="width:10%">
								<col style="width:auto">
								<col style="width:8%">
								<col style="width:12%">
								<col style="width:10%">
								<col style="width:8%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">NO.</th>
									<th scope="col">주문번호</th>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">수량</th>
									<th scope="col">결제금액</th>
									<th scope="col">상태</th>
									<th scope="col">보기</th>
								</tr>
							</thead>
							<tbody>
<?
		$cnt=0;
		if ($t_count > 0) {
			while($row=pmysql_fetch_object($result)) {
				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

				$ord_date	= substr($row->ordercode,0,4)."-".substr($row->ordercode,4,2)."-".substr($row->ordercode,6,2);

				$ord_title	= $row->productname;
				$ord_brand	= $row->brandname;
				if ($row->prod_cnt > 1) {
					$ord_title	.= " 외 ".($row->prod_cnt - 1)."건";
				}

				$file = getProductImage($Dir.DataDir.'shopimages/product/', $row->tinyimage);

                $stock_status = "";
                if($row->stock_yn_cnt > 0) $stock_status = "<br>(재고부족)";

?>
								<tr class="bold">
									<td><?=$number?></td>
									<td class="order_num"><a href="javascript:OrderDetail('<?=$row->ordercode?>')"><?=$row->ordercode?></a></td>
									<td class="date"><?=$ord_date?></td>
									<td class="goods_info">
										<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
											<img src="<?=$file?>" alt="<?=$ord_title?>">
											<ul>
												<li>[<?=$ord_brand?>]</li>
												<li><?=$ord_title?></li>
											</ul>
										</a>
									</td>
									<td><?=number_format($row->quantity)?></td>
									<td class="payment"><?=number_format($row->price-$row->dc_price-$row->reserve+$row->deli_price)?>원</td>
									<td><span><?=GetStatusOrder("o", $row->oi_step1, $row->oi_step2, "", $row->redelivery_type, $row->order_conf)?><?=$stock_status?></span></td>
									<td><a href="javascript:OrderDetail('<?=$row->ordercode?>')" class="btn-type1 c2">상세보기</a></td>
								</tr>
<?
		$cnt++;
		}
	} else {
?>
								<tr>
									<td colspan="8">내역이 없습니다.</td>
								</tr>
<?
	}
?>
							</tbody>
						</table>
						<div class="list-paginate mt-30"><?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?></div>
					</div>
					<!-- // 주문내역 리스트 -->

					<!-- 안내 -->
					<div class="list_text">
						<h3>유의사항</h3>
						<ul>
							<li>ㆍ주문번호를 클릭하시면 주문상세내역을 확인할 수 있습니다</li>
							<li>ㆍ“입금대기” 상태에서는 모든 주문 취소가 가능하며, “배송중”인 상품은 반품신청을 이용해 주세요.</li>
							<li>ㆍ상품 일부만 취소/교환/반품을 원하시는 경우 1:1 문의 또는 고객센터로 문의 부탁드립니다</li>
							<li>ㆍ주문상태가 "배송 중"이 되면 상품에 대한 "배송추적”이 가능합니다</li>
							<li>ㆍ배송완료 이후 14일이 경과되면 자동 구매확정 처리 되며 교환/반품이 불가능합니다</li>
							<li>ㆍ무통장입금의 경우, 환불 금액 입금이 3~4일정도 소요됩니다 (영업일 기준)</li>
						</ul>
					</div>
					<!-- // 안내 -->

				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->

<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>