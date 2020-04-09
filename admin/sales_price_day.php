<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");
include("calendar.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################
include("./header.php");

$assign = array(

);

_render("report/sales_price_day.html", $assign, 'admin/template');

include("./copyright.php");

exit;
//exdebug($_POST);
//exdebug($_GET);

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",strtotime('-1 month'));


//$s_check    = $_GET["s_check"];
//$search     = trim($_GET["search"]);
$search_start   = $_GET["search_start"];
$search_end     = $_GET["search_end"];
$s_prod         = $_GET["s_prod"];
$search_prod    = $_GET["search_prod"];

//$selected[s_check][$s_check]    = 'selected';
//$selected[s_date][$s_date]      = 'selected';
$selected[s_prod][$s_prod]      = 'selected';

$search_start = $search_start?$search_start:date("Ym")."01";
$search_end = $search_end?$search_end:date("Ymd");
$search_s = $search_start?str_replace("-","",$search_start." 00:00:00"):"";
$search_e = $search_end?str_replace("-","",$search_end." 23:59:59"):"";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (strtotime($search_end)-strtotime($search_start))/86400;
if ($termday>367) {
	alert_go('검색기간은 1년을 초과할 수 없습니다.');
}

$t_price=0;

function option_slice2( $content, $option_type = '0' ){
    $tmp_content = '';
    if( $option_type == '0' ) {
        $tmp_content = explode( chr(30), $content );
    } else {
        $tmp_content = explode( '@#', $content );
    }
    
    return $tmp_content;

}

include("header.php");
?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
	function searchForm() {
		document.form1.action = "sales_price_day.php";
		document.form1.submit();
	}

	function OnChangePeriod(val) {
		var pForm = document.form1;
		var period = new Array(7);
		period[0] = "<?=$period[0]?>";
		period[1] = "<?=$period[1]?>";
		period[2] = "<?=$period[2]?>";
		period[3] = "<?=$period[3]?>";

		if (val < 4) {
			pForm.search_start.value = period[val];
			pForm.search_end.value = period[0];
		} else {
			pForm.search_start.value = '';
			pForm.search_end.value = '';
		}
	}

	function OrderExcel() {
		//alert("excel");
		document.form1.action = "sales_price_day_excel.php";
		document.form1.method = "POST";
		//document.form1.target="_blank";
		document.form1.submit();
		document.form1.action = "";
	}

	/*
	function OrderDeliPrint() {
		alert("운송장 출력은 준비중에 있습니다.");
	}
	*/
	function OrderCheckPrint() {
		document.printform.ordercodes.value = "";
		for (i = 1; i < document.form2.chkordercode.length; i++) {
			if (document.form2.chkordercode[i].checked) {
				document.printform.ordercodes.value += document.form2.chkordercode[i].value.substring(1) + ",";
			}
		}
		if (document.printform.ordercodes.value.length == 0) {
			alert("선택하신 주문서가 없습니다.");
			return;
		}
		if (confirm("소비자용 주문서로 출력하시겠습니까?")) {
			document.printform.gbn.value = "N";
		} else {
			document.printform.gbn.value = "Y";
		}
		document.printform.target = "hiddenframe";
		document.printform.submit();
	}
	/*
	function OrderCheckExcel() {
		document.checkexcelform.ordercodes.value="";
		for(i=1;i<document.form2.chkordercode.length;i++) {
			if(document.form2.chkordercode[i].checked) {
				document.checkexcelform.ordercodes.value+=document.form2.chkordercode[i].value+",";
			}
		}
		if(document.checkexcelform.ordercodes.value.length==0) {
			alert("선택하신 주문서가 없습니다.");
			return;
		}
	    //document.checkexcelform.target="_blank";
		document.checkexcelform.action="order_excel_all_order.php";
		document.checkexcelform.submit();
	}
	*/
/*
	concat(op.order_status, op.cs_type, op.cs_status) IN('200','2E0','300','3E0','400','4E0','500','5E0','600','6E0')
	AND op.order_status>0 AND op.date_order_2 >= '{$search_s}' AND op.date_order_2 <= '{$search_e}'*/
</script>
<div class="content-wrap">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
				<!-- 페이지 타이틀 -->
				<div class="title_depth3">일자별 조회
					<span>일자별 내역을 확인하실 수 있습니다.</span>
				</div>
			</td>
		</tr>

		<tr>
			<td>
				<!-- 소제목 -->
				<div class="title_depth3_sub">일자별 조회</span>
				</div>
			</td>
		</tr>
		<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=GET>
			<tr>
				<td>

					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
						<tr>
							<td width="100%">
								<div class="table_style01">
									<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
										<TR>
											<th>
												<span>기간선택</span>
											</th>
											<td>
												<input class="input_bd_st01" type="text" name="search_start" OnClick="Calendar(event)" value="<?=$search_start?>" /> ~
												<input class="input_bd_st01" type="text" name="search_end" OnClick="Calendar(event)" value="<?=$search_end?>"
												/>
												<img src=images/btn_today01.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(0)">
												<img src=images/btn_day07.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(1)">
												<img src=images/btn_day14.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(2)">
												<img src=images/btn_day30.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(3)">
												<!-- <img src=images/btn_day_total.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(4)"> -->
											</td>
										</TR>
									</TABLE>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="padding-top:4pt;" align="right">
					<a href="javascript:searchForm();">
						<img src="images/botteon_search.gif" border="0">
					</a>&nbsp;
					<a href="javascript:OrderExcel();">
						<img src="images/btn_excel_search.gif" border="0" hspace="1">
					</a>
				</td>
			</tr>
		</form>
		<tr>
			<td height="20"></td>
		</tr>
		<form name=form2 action="<?=$_SERVER['PHP_SELF']?>" method=GET>
			<tr>
				<td style="padding-bottom:3pt;">
					<?php
		$sql = "
				select cdt, sum(basic_pay_total) as basic_pay_total, sum(basic_sum_end) as basic_sum_end, sum(basic_pay_delivery) as basic_pay_delivery
					, sum(basic_coupon_discount) as basic_coupon_discount
					, sum(basic_use_point) as basic_use_point, sum(basic_use_mileage) as basic_use_mileage, sum(basic_pay_pg) as basic_pay_pg
					
					, sum(refund_price_total::integer) as refund_price_total, sum(refund_price_product) as refund_price_product, sum(refund_price_delivery) as refund_price_delivery
					, sum(refund_cancel_coupon::integer) as refund_cancel_coupon, sum(refund_point) as refund_point, sum(refund_mileage) as refund_mileage
					, sum(refund_cash) as refund_cash
				from (
				select  to_char(op.date_order_2, 'YYYY-MM-DD') as cdt
					, coalesce(ob.pay_total) as basic_pay_total, coalesce(ob.sum_end,0) as basic_sum_end, coalesce(ob.pay_delivery) as basic_pay_delivery	
					, (coalesce(ob.coupon_basket_discount,0)+coalesce(ob.coupon_delivery_discount,0)+coalesce(ob.coupon_product_discount,0)) as basic_coupon_discount
					, coalesce(ob.use_point,0) as basic_use_point, coalesce(ob.use_mileage,0) as basic_use_mileage, coalesce(ob.pay_pg,0) as basic_pay_pg
				
					, coalesce(of.price_total,'0') as refund_price_total, coalesce(of.price_product,0) as refund_price_product, coalesce(of.price_delivery,0) as refund_price_delivery
					, coalesce(of.cancel_coupon,'0') as refund_cancel_coupon, coalesce(of.refund_point,0) as refund_point, coalesce(of.refund_mileage,0) as refund_mileage
					, (coalesce(of.refund_cash,0)+coalesce(of.refund_card,0)+coalesce(of.refund_vcnt,0)+coalesce(of.refund_acnt,0)) as refund_cash
					
				from tblorder_basic ob
				left join tblorder_product op on op.order_num = ob.order_num and op.date_order_2 is not null
				left join tblorder_refund of on of.order_num = ob.order_num and op.cs_flag = 'RC'
				where op.order_status>0 AND op.date_order_2 >= '{$search_s}' AND op.date_order_2 <= '{$search_e}'
				)a
				group by cdt
                ";

					/*SELECT  cdt, a.saletype::text, count(a.order_num) as cnt_ord, sum(a.cnt_prod) as cnt_prod
					, sum(a.sum_end) as ordprice, sum(a.pay_delivery) as pay_delivery, sum(a.pay_total) as pay_total
					, sum(a.coupon) as coupon
					, sum(a.use_point) as use_point, sum(a.use_mileage) as use_mileage
					, sum(a.pay_pg) as pay_pg
				from(
					select to_char(op.date_order_2, 'YYYY-MM-DD') as cdt, 'sale' as saletype, count(op.productcode) as cnt_prod
					, max(ob.order_num) as order_num ,max(op.price_end) as sum_end, max(ob.pay_delivery) as pay_delivery, max(ob.pay_total) as pay_total
					, (ob.coupon_basket_discount+ob.coupon_delivery_discount+ob.coupon_product_discount) as coupon
					, max(ob.use_point) as use_point, max(ob.use_mileage) as use_mileage
					, max(ob.pay_pg) as pay_pg
					from tblorder_basic ob
					left join tblorder_product op on op.order_num = ob.order_num
					where op.order_status>0 AND op.date_order_2 >= '{$search_s}' AND op.date_order_2 <= '{$search_e}'
					GROUP BY cdt, ob.order_num
					UNION ALL
					select to_char(op.date_order_2, 'YYYY-MM-DD') as cdt, 'refund' as saletype, count(op.productcode) as cnt_prod
					, max(of.order_num) as order_num ,max(of.price_product) as sum_end, max(of.price_delivery) as pay_delivery, max(of.price_total::integer) as pay_total
					, max(of.cancel_coupon::integer) as coupon
					, max(of.refund_point) as use_point, max(of.refund_mileage) as use_mileage
					, max(of.refund_cash) as pay_pg
					from tblorder_refund of
					left join tblorder_product op on op.cs_idx = of.idx
					where concat(op.order_status, op.cs_type, op.cs_status) IN('1C4','2C4','4R4','5R4')
					AND op.order_status>0 AND op.date_order_2 >= '{$search_s}' AND op.date_order_2 <= '{$search_e}'
					GROUP BY cdt, of.order_num
					UNION ALL
					select to_char(op.date_order_2, 'YYYY-MM-DD') as cdt, 'dd' as saletype, count(op.productcode) as cnt_prod
					, max(ob.order_num) as order_num ,max(ob.sum_end) as sum_end, max(ob.pay_delivery) as pay_delivery, max(ob.pay_total) as pay_total
					, (ob.coupon_basket_discount+ob.coupon_delivery_discount+ob.coupon_product_discount) as coupon
					, max(ob.use_point) as use_point, max(ob.use_mileage) as use_mileage
					, max(ob.pay_pg) as pay_pg
					from tblorder_basic ob
					left join tblorder_product op on op.order_num = ob.order_num
					where concat(op.order_status, op.cs_type, op.cs_status) IN('200','2E0','300','3E0','400','4E0','500','5E0','600','6E0')
					AND op.order_status>0 AND op.date_order_2 >= '{$search_s}' AND op.date_order_2 <= '{$search_e}'
					GROUP BY cdt, ob.order_num
				)as a
				GROUP BY cdt, a.saletype::text*/

		$result=pmysql_query($sql,get_db_conn());
        //echo "sql = ".$sql."<br>";
        //pre($sql);

?>

						<?php
        $sales = array();           // 전체 배열
        $tot_sale_cnt_ord = 0;      // 전체 결제 주문 수량
        $tot_sale_cnt_prod = 0;     // 전체 결제 상품 수량
        $tot_sale_ordprice = 0;     // 전체 결제 주문금액
        $tot_sale_coupon = 0;       // 전체 결제 쿠폰 사용 금액
        $tot_sale_usepoint = 0;     // 전체 결제 적립금 사용 금액
		$tot_sale_use_mileage = 0;     // 전체 결제 e포인트 사용 금액
        $tot_sale_deliprice = 0;    // 전체 결제 배송비 금액
        $tot_sale_realprice = 0;    // 전체 결제 실결제 금액
        $tot_refund_cnt_ord = 0;    // 전체 환불 주문 수량
        $tot_refund_cnt_prod = 0;   // 전체 환불 상품 수량
        $tot_refund_ordprice = 0;   // 전체 환불 주문금액
        $tot_refund_coupon = 0;     // 전체 환불 쿠폰 사용 금액
        $tot_refund_usepoint = 0;   // 전체 환불 적립금 사용 금액
		$tot_refund_use_mileage = 0;   // 전체 환불 e포인트 사용 금액
        $tot_refund_deliprice = 0;  // 전체 환불 배송비 금액
        $tot_refund_realprice = 0;  // 전체 환불 실결제 금액

		while($row=pmysql_fetch_object($result)) {

            //if($row->saletype == "refund") $minus = -1;
            //else $minus = 1;

            $cdt = $row->cdt;
            $cnt_ord = $row->cnt_ord;
            $cnt_prod = $row->cnt_prod;
            $ordprice = $row->ordprice;
            $coupon = $row->coupon;
            $usepoint = $row->use_point;
			$use_mileage = $row->use_mileage;
            //$o_deliprice = $row->o_deliprice;
            $op_deliprice = $row->pay_delivery;
            $real_price = $ordprice - $coupon - $usepoint - $use_mileage + $op_deliprice;

            if($saletype == "sale") {
                $tot_sale_cnt_ord += $cnt_ord;
                $tot_sale_cnt_prod += $cnt_prod;
                $tot_sale_ordprice += $ordprice;
                $tot_sale_coupon += $coupon;
                $tot_sale_usepoint += $usepoint;
				$tot_sale_use_mileage += $use_mileage;
                $tot_sale_deliprice += $op_deliprice;
                $tot_sale_realprice += $real_price;;

            } else if($saletype == "refund") {
                $tot_refund_cnt_ord += $cnt_ord;
                $tot_refund_cnt_prod += $cnt_prod;
                $tot_refund_ordprice += $ordprice;
                $tot_refund_coupon += $coupon;
                $tot_refund_usepoint += $usepoint;
				$tot_refund_use_mileage += $use_mileage;
                $tot_refund_deliprice += $op_deliprice;
                $tot_refund_realprice += $real_price;;
            }

            $sales[$cdt]['cnt_ord'] = $cnt_ord;
            $sales[$cdt]['cnt_prod'] = $cnt_prod;
            $sales[$cdt]['ordprice'] = $ordprice;
            $sales[$cdt]['coupon'] = $coupon;
            $sales[$cdt]['usepoint'] = $usepoint;
			$sales[$cdt]['use_mileage'] = $use_mileage;
            $sales[$cdt]['op_deliprice'] = $op_deliprice;
            $sales[$cdt]['real_price'] = $real_price;

			$cnt++;
		}

        //exdebug($sales);
        //exdebug(count($sales));
        /*
        foreach($sales as $k => $v) {
            echo $k."=>";
            echo $v['sale']['cnt_ord']." / ";
            echo $v['sale']['cnt_prod'];
            echo "<br>";
        }
        */
		pmysql_free_result($result);

        $t_count = count($sales);
?>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="" align="right">
										<img src="images/icon_8a.gif" border="0">총 :
										<B>
											<?=number_format($t_count)?>
										</B>건
										<!-- , &nbsp;&nbsp;<img src="images/icon_8a.gif" border="0">현재 <b><?=$gotopage?>/<?=ceil($t_count/$setup['list_num'])?></b> 페이지 -->
									</td>
								</tr>
							</table>
				</td>
			</tr>
			<tr>
				<td>
					<div class="table_style02">
						<table border=0 cellpadding=0 cellspacing=1 width=100% style="border:1px solid #d1d1d1">
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<col width=80></col>
							<input type=hidden name=chkordercode>

							<TR bgcolor="#d1d1d1">
								<td rowspan=2>
									<b>구분
										<b>
								</td>
								<td rowspan=2>
									<b>주문건수
										<b>
								</td>
								<td rowspan=2>
									<b>주문품목수
										<b>
								</td>
								<td colspan=6>
									<b>판매
										<b>
								</td>
								<td rowspan=2>
									<b>환불건수
										<b>
								</td>
								<td rowspan=2>
									<b>환불품목수
										<b>
								</td>
								<td colspan=6>
									<b>환불
										<b>
								</td>
								<td colspan=6>
									<b>순매출
										<b>
								</td>
							</TR>
							<TR bgcolor="#d1d1d1">
								<td>
									<b>상품구매금액
										<b>
								</td>
								<td>
									<b>배송비
										<b>
								</td>
								<td>
									<b>쿠폰
										<b>
								</td>
								<td>
									<b>포인트
										<b>
								</td>
								<td>
									<b>마일리지
										<b>
								</td>
								<td>
									<b>실결제금액
										<b>
								</td>

								<td>
									<b>상품환불금액
										<b>
								</td>
								<td>
									<b>배송비
										<b>
								</td>
								<td>
									<b>쿠폰
										<b>
								</td>
								<td>
									<b>포인트
										<b>
								</td>
								<td>
									<b>마일리지
										<b>
								</td>
								<td>
									<b>실결제금액
										<b>
								</td>

								<td>
									<b>상품구매금액
										<b>
								</td>
								<td>
									<b>배송비
										<b>
								</td>
								<td>
									<b>쿠폰
										<b>
								</td>
								<td>
									<b>포인트
										<b>
								</td>
								<td>
									<b>마일리지
										<b>
								</td>
								<td>
									<b>실결제금액
										<b>
								</td>
							</TR>
							<?
		$colspan=20;
        $i = 0;
        foreach($sales as $k => $v) {

            if($i%2) $thiscolor="#ffeeff";
            else $thiscolor="#FFFFFF";
?>

								<tr bgcolor=<?=$thiscolor?> onmouseover="this.style.background='#FEFBD1'" onmouseleave="this.style.background='<?=$thiscolor?>'">
										<td>
											<?=$k?>
										</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['cnt_ord'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['cnt_prod'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['ordprice'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['op_deliprice'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['coupon'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['usepoint'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['use_mileage'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['real_price'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['cnt_ord'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['cnt_prod'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['ordprice'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['op_deliprice'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['coupon'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['usepoint'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['use_mileage'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['refund']['real_price'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['ordprice']-$v['refund']['ordprice'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['op_deliprice']-$v['refund']['op_deliprice'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['coupon']-$v['refund']['coupon'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['usepoint']-$v['refund']['usepoint'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['use_mileage']-$v['refund']['use_mileage'])?>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<?=number_format($v['sale']['real_price']-$v['refund']['real_price'])?>&nbsp;&nbsp;</td>
								</tr>
								<?
            $i++;
        }
?>
									<tr bgcolor="#d1d1d1" onmouseover="this.style.background='#FEFBD1'" onmouseleave="this.style.background='#d1d1d1'">
										<td>
											<b>합계</b>
										</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_cnt_ord)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_cnt_prod)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_ordprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_deliprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_coupon)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_usepoint)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_use_mileage)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_realprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_cnt_ord)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_cnt_prod)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_ordprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_deliprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_coupon)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_usepoint)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_use_mileage)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_refund_realprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_ordprice - $tot_refund_ordprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_deliprice - $tot_refund_deliprice)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_coupon - $tot_refund_coupon)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_usepoint - $tot_refund_usepoint)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_use_mileage - $tot_refund_use_mileage)?>
											</b>&nbsp;&nbsp;</td>
										<td style="text-align:right;">
											<b>
												<?=number_format($tot_sale_realprice - $tot_refund_realprice)?>
											</b>&nbsp;&nbsp;</td>
									</tr>
									<?
		if($t_count==0) {
			echo "<tr height=28 bgcolor=#FFFFFF><td colspan={$colspan} align=center>조회된 내용이 없습니다.</td></tr>\n";
		}
?>
						</TABLE>
					</div>
				</td>
			</tr>
			<!-- <tr>
				<td style="padding-top:4pt;"><a href="javascript:OrderCheckPrint();"><img src="images/btn_juprint.gif" border="0" hspace="0"></a>&nbsp;<a href="javascript:OrderCheckExcel();"><img src="images/btn_excel_select.gif" border="0" hspace="1"></a></td>
			</tr> -->
			<input type=hidden name=tot value="<?=$cnt?>">
		</form>

		<form name=detailform method="post" action="order_detail.php" target="orderdetail">
			<input type=hidden name=ordercode>
		</form>

		<form name=idxform action="<?=$_SERVER['PHP_SELF']?>" method=GET>
			<input type=hidden name=type>
			<input type=hidden name=ordercodes>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
		</form>

		<form name=printform action="order_print_pop.php" method=post target="ordercheckprint">
			<input type=hidden name=ordercodes>
			<input type=hidden name=gbn>
		</form>

		<form name=checkexcelform action="order_excel_new.php" method=post>
			<input type=hidden name=ordercodes>
		</form>

		<IFRAME name="HiddenFrame" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0"
		    marginwidth="0"></IFRAME>

		<tr>
			<td height=20></td>
		</tr>
		<tr>
			<td>
				<!-- 매뉴얼 -->
				<div class="sub_manual_wrap">
					<div class="title">
						<p>매뉴얼</p>
					</div>
					<!-- <dl>
							<dt><span>배송/입금일별 주문조회</span></dt>
							<dd>
								- 입금일별, 배송일자별, 주문일자별 주문현황 및 주문내역을 확인/처리하실 수 있습니다.<br>
								- 주문번호를 클릭하면 <b>주문상세내역</b>이 출력되며, 주문내역 확인 및 주문 처리가 가능합니다.<br>
								- 에스크로(결제대금 예치제) 결제의 경우는 주문후 미입금시 5일뒤에 삭제가 가능합니다.<br>
								- 카드실패 주문건은 2시간후에 삭제가 가능합니다.
							</dd>
						</dl>
						<dl>
							<dt><span>배송/입금일별 주문조회 부가기능</span></dt>
							<dd>
								- 엑셀다운로드 : 체크된 주문건을 엑셀파일 형식으로 다운로드 받습니다.
							</dd>
						</dl>
						<dl>
							<dt><span>배송/입금일별 주문조회 주의사항</span></dt>
							<dd>- 배송/입금별 주문조회 기간은 1달을 초과할 수 없습니다.</dd>
						</dl> -->
				</div>
			</td>
		</tr>
	</table>
</div>
<?php 
include("copyright.php");
?>