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

$arpm=array("B"=>"무통장","V"=>"계좌이체","O"=>"가상계좌","Q"=>"가상계좌(매매보호)","C"=>"신용카드","P"=>"신용카드(매매보호)","M"=>"핸드폰");

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
</style>

<?
		$s_curtime=strtotime("$s_year-$s_month-$s_day");
		$s_curdate=date("Ymd",$s_curtime)."000000";
		$e_curtime=strtotime("$e_year-$e_month-$e_day");
		$e_curdate=date("Ymd",$e_curtime)."999999";
		
		# 취소/교환/반품/환불 대기		
		$sql = "SELECT 
					oc.oc_no,
					a.ordercode,
					min(a.id) as id,
					min(a.paymethod) as paymethod,
					min(a.oi_step1) as oi_step1,
					min(a.oi_step2) as oi_step2,
					min(b.redelivery_type) as redelivery_type,
					min(b.op_step) as op_step,
					min(oc.regdt) as regdt,
					min(oc.rfee) as rfee,
					min(oc.rprice) as rprice,
					SUM(b.price) as price,
					SUM(b.option_price) as option_price, 
					SUM(b.option_quantity) as option_quantity,   
					SUM(b.deli_price) as deli_price, 
					SUM(((b.price+b.option_price) * b.option_quantity)+b.deli_price) as tot_price,
					(Select tinyimage from tblproduct where productcode = min(b.productcode)) as tinyimage, 
					(Select brandname from tblproduct p left join tblproductbrand pb on p.brand=pb.bridx where p.productcode = min(b.productcode)) as brandname, 
					SUM(b.option_quantity) as quantity,
					count(b.productcode) prod_cnt
					FROM
					tblorderinfo a join tblorderproduct b on a.ordercode = b.ordercode left join tblorder_cancel oc ON b.oc_no=oc.oc_no
					join tblvenderinfo v on b.vender = v.vender
					WHERE 1=1
					AND a.id = '".$_ShopInfo->getMemid()."'
					AND ( 
						(b.redelivery_type = 'G' And b.op_step = 41)
						OR (a.oi_step1 in (3,4) And (coalesce(b.opt1_change, '') = '' And coalesce(b.opt2_change, '') = '') And b.op_step = 41)
						OR (a.bank_date is not null And ((a.oi_step1 in (1,2) and b.op_step = 41) OR b.op_step = 42) And ((coalesce(b.opt1_change, '') = '' And coalesce(b.opt2_change, '') = ''))) )
					AND oc.regdt >= '".$s_curdate."' AND oc.regdt <= '".$e_curdate."' 
					GROUP BY oc.oc_no, a.ordercode ORDER BY oc.oc_no DESC";

		//echo $sql;
		
		$paging = new New_Templet_paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		//exdebug($sql);
 ?>
<div id="contents">
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<!-- LNB -->
			<? include  "mypage_TEM01_left.php";  ?>
			<!-- //LNB -->

			<article class="mypage_content">
				<section class="mypage_main">
					<ul class="my-tab-menu clear">
						<li class="<?=$viewtab['request']?>"><a>주문취소/반품/교환 신청</a></li>
						<li class="<?=$viewtab['result']?>"><a>주문취소/반품/교환 완료</a></li>
					</ul>
					<!-- 주문취소/반품/교환 신청 -->
					<div class="mt-50 tab-menu-content <?=$viewtab['request']?>">
						<div class="order_right">
							<div class="total">총 <?=number_format($t_count)?>건</div>
							<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
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
							</form>
						</div>
						<table class="th_top">
							<caption></caption>
							<colgroup>
								<col style="width:20%">
								<col style="width:10%">
								<col style="width:auto">
								<col style="width:8%">
								<col style="width:10%">
								<col style="width:10%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">주문번호</th>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">수량</th>
									<th scope="col">결제금액</th>
									<th scope="col">상태</th>
								</tr>
							</thead>
							<tbody>
<?		
		$cnt=0;
		if($t_count){
			while($row=pmysql_fetch_object($result)) {
				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

				$ord_date	= substr($row->ordercode,0,4)."-".substr($row->ordercode,4,2)."-".substr($row->ordercode,6,2);

				$ord_title	= $row->productname;
				$ord_brand	= $row->brandname;
				if ($row->prod_cnt > 1) {
					$ord_title	.= " 외 ".($row->prod_cnt - 1)."건";
				}

				$file = getProductImage($Dir.DataDir.'shopimages/product/', $row->tinyimage);
?>
								<tr class="bold">
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
									<td class="payment"><?=number_format($row->tot_price)?>원</td>
									<td><span><?=GetStatusOrder("p", $row->oi_step1, $row->oi_step2, $row->op_step, $row->redelivery_type)?></span></td>
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
							</tbody>
						</table>
						<div class="list-paginate mt-30"><?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?></div>
					</div>
					<!-- // 주문취소/반품/교환 신청 -->
<?
		$r_s_curtime=strtotime("$r_s_year-$r_s_month-$r_s_day");
		$r_s_curdate=date("Ymd",$r_s_curtime)."000000";
		$r_e_curtime=strtotime("$r_e_year-$r_e_month-$r_e_day");
		$r_e_curdate=date("Ymd",$r_e_curtime)."999999";
		
		# 취소/교환/반품/환불 대기
		$sql = "SELECT 
					oc.oc_no,
					a.ordercode,
					min(a.id) as id,
					min(a.paymethod) as paymethod,
					min(a.oi_step1) as oi_step1,
					min(a.oi_step2) as oi_step2,
					min(b.redelivery_type) as redelivery_type,
					min(b.op_step) as op_step,
					min(oc.regdt) as regdt,
					min(oc.rfee) as rfee,
					min(oc.rprice) as rprice,
					SUM(b.price) as price,
					SUM(b.option_price) as option_price, 
					SUM(b.option_quantity) as option_quantity,   
					SUM(b.deli_price) as deli_price, 
					SUM(((b.price+b.option_price) * b.option_quantity)+b.deli_price) as tot_price,
					(Select tinyimage from tblproduct where productcode = min(b.productcode)) as tinyimage, 
					(Select brandname from tblproduct p left join tblproductbrand pb on p.brand=pb.bridx where p.productcode = min(b.productcode)) as brandname, 
					SUM(b.option_quantity) as quantity,
					count(b.productcode) prod_cnt
					FROM
					tblorderinfo a join tblorderproduct b on a.ordercode = b.ordercode left join tblorder_cancel oc ON b.oc_no=oc.oc_no
					join tblvenderinfo v on b.vender = v.vender
					WHERE 1=1
					AND a.id = '".$_ShopInfo->getMemid()."'
					AND ( 
						(a.oi_step1 = 0 And a.oi_step2 = 44)
						OR (b.redelivery_type = 'G' And b.op_step = 44)
						OR (a.oi_step1 in (3,4) And a.oi_step2 = 42)
						OR (a.oi_step1 > 0 And b.op_step = 44 And ((coalesce(b.opt1_change, '') = '' And coalesce(b.opt2_change, '') = ''))) )
					AND oc.regdt >= '".$s_curdate."' AND oc.regdt <= '".$e_curdate."' 
					GROUP BY oc.oc_no, a.ordercode ORDER BY oc.oc_no DESC";

		//echo $sql;
		
		$r_paging = new New_Templet_paging($sql,10,10,'GoPage2',true);
		$r_t_count = $r_paging->t_count;
		$gotopage2 = $r_paging->gotopage;

		$sql = $r_paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		//exdebug($sql);
 ?>
					<!-- 주문취소/반품/교환 완료 -->
					<div class="mt-50 tab-menu-content <?=$viewtab['result']?>">
						<div class="order_right">
							<div class="total">총 <?=number_format($r_t_count)?>건</div>
							<form name="form3" action="<?=$_SERVER['PHP_SELF']?>">
							<div class="date-sort clear">
								<div class="type month">
									<p class="title">기간별 조회</p>
								<?
									if(!$r_day_division) $r_day_division = '1MONTH';

								?>
								<?foreach($arrSearchDate as $kk => $vv){?>
									<?
										$dayClassName = "";
										if($r_day_division != $kk){
											$dayClassName = '';
										}else{
											$dayClassName = 'on';
										}
									?>
									<button type="button" class="<?=$dayClassName?>" onClick = "GoSearch3('<?=$kk?>', this)"><span><?=$vv?></span></button>
								<?}?>
								</div>
								<div class="type calendar">
									<p class="title">일자별 조회</p>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="r_date1" id="" value="<?=$r_strDate1?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
									<span>-</span>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="r_date2" id="" value="<?=$r_strDate2?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
								</div>
								<button type="button" class="btn-go" onClick="javascript:CheckForm3();"><span>검색</span></button>
							</div>
							</form>
						</div>
						<table class="th_top">
							<caption></caption>
							<colgroup>
								<col style="width:20%">
								<col style="width:10%">
								<col style="width:auto">
								<col style="width:8%">
								<col style="width:10%">
								<col style="width:10%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">주문번호</th>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">수량</th>
									<th scope="col">결제금액</th>
									<th scope="col">상태</th>
								</tr>
							</thead>
							<tbody>
<?		
		$cnt=0;
		if($r_t_count){
			while($row=pmysql_fetch_object($result)) {
				$number = ($r_t_count-($setup[list_num] * ($gotopage2-1))-$cnt);

				$ord_date	= substr($row->ordercode,0,4)."-".substr($row->ordercode,4,2)."-".substr($row->ordercode,6,2);

				$ord_title	= $row->productname;
				$ord_brand	= $row->brandname;
				if ($row->prod_cnt > 1) {
					$ord_title	.= " 외 ".($row->prod_cnt - 1)."건";
				}

				$file = getProductImage($Dir.DataDir.'shopimages/product/', $row->tinyimage);
?>
								<tr class="bold">
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
									<td class="payment"><?=number_format($row->tot_price)?>원</td>
									<td><span><?=GetStatusOrder("p", $row->oi_step1, $row->oi_step2, $row->op_step, $row->redelivery_type)?></span></td>
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
							</tbody>
						</table>
						<div class="list-paginate mt-30"><?=$r_paging->a_prev_page.$r_paging->print_page.$r_paging->a_next_page?></div>
					</div>
					<!-- // 주문취소/반품/교환 완료 -->
				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->

<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>
</div>
</BODY>
</HTML>
















