<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

//exdebug($_POST);
//exdebug($_GET);

header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=sales_price_mon_excel_".date("Ymd",$CurrentTime).".xls");
Header("Pragma: no-cache");
Header("Expires: 0");
Header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
Header("Content-Description: PHP4 Generated Data");


$Order = new ORDER;

$where = "op.order_status > 1"; //기본검색(결제완료이상부터)

//검색
$search = $_POST;
$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;


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

?>

<?php

$sql = "
				select cdt, sum(basic_pay_total) as basic_pay_total, sum(basic_sum_end) as basic_sum_end, sum(basic_pay_delivery) as basic_pay_delivery
					, sum(basic_coupon_discount) as basic_coupon_discount
					, sum(basic_use_point) as basic_use_point, sum(basic_use_mileage) as basic_use_mileage, sum(basic_pay_pg) as basic_pay_pg
					
					, sum(refund_price_total::integer) as refund_price_total, sum(refund_price_product) as refund_price_product, sum(refund_price_delivery) as refund_price_delivery
					, sum(refund_cancel_coupon::integer) as refund_cancel_coupon, sum(refund_point) as refund_point, sum(refund_mileage) as refund_mileage
					, sum(refund_cash) as refund_cash
				from (
				select  to_char(op.date_order_2, 'YYYY-MM') as cdt
					, coalesce(ob.pay_total) as basic_pay_total, coalesce(ob.sum_end,0) as basic_sum_end, coalesce(ob.pay_delivery) as basic_pay_delivery	
					, (coalesce(ob.coupon_basket_discount,0)+coalesce(ob.coupon_delivery_discount,0)+coalesce(ob.coupon_product_discount,0)) as basic_coupon_discount
					, coalesce(ob.use_point,0) as basic_use_point, coalesce(ob.use_mileage,0) as basic_use_mileage, coalesce(ob.pay_pg,0) as basic_pay_pg
				
					, coalesce(of.price_total,'0') as refund_price_total, coalesce(of.price_product,0) as refund_price_product, coalesce(of.price_delivery,0) as refund_price_delivery
					, coalesce(of.cancel_coupon,'0') as refund_cancel_coupon, coalesce(of.refund_point,0) as refund_point, coalesce(of.refund_mileage,0) as refund_mileage
					, (coalesce(of.refund_cash,0)+coalesce(of.refund_card,0)+coalesce(of.refund_vcnt,0)+coalesce(of.refund_acnt,0)) as refund_cash
					
				from tblorder_basic ob
				left join tblorder_product op on op.order_num = ob.order_num and op.date_order_2 is not null
				left join tblorder_refund of on of.order_num = ob.order_num and op.cs_flag = 'RC'
				where {$where}
				group by op.date_order_2, ob.pay_total, ob.sum_end, ob.pay_delivery, ob.coupon_basket_discount, ob.coupon_delivery_discount, ob.coupon_product_discount
					, ob.use_point, ob.use_mileage, ob.pay_pg, of.price_total, of.price_product, of.price_delivery, of.cancel_coupon, of.refund_point, of.refund_mileage
					, of.refund_cash, of.refund_card, of.refund_vcnt, of.refund_acnt
				)a
				group by cdt order by cdt asc
";

//pre($sql);

$count = $Order->adodb->getOne("SELECT COUNT(*) AS total FROM ({$sql}) x");

$rs = $Order->adodb->Execute($sql);
//echo "sql = ".$sql."<br>";
//exdebug($sql);

$tot_sale_cnt_ord = 0;      // 전체 결제 주문 수량
$tot_sale_cnt_prod = 0;     // 전체 결제 상품 수량

$tot_sale_pay_total = 0;     // 전체 결제 주문금액
$tot_sale_sum_end = 0;       // 전체 결제 쿠폰 사용 금액
$tot_sale_pay_delivery = 0;     // 전체 결제 적립금 사용 금액
$tot_sale_coupon_discount = 0;     // 전체 결제 e포인트 사용 금액
$tot_sale_use_point = 0;    // 전체 결제 배송비 금액
$tot_sale_use_mileage = 0;    // 전체 결제 실결제 금액
$tot_sale_pay_pg = 0;    // 전체 환불 주문 수량

$tot_pure_sale_pay_total = 0; //순매출 결제총금액
$tot_pure_sale_sum_end = 0; //순매출 상품금액
$tot_pure_sale_pay_delivery = 0; //순매출 배송비
$tot_pure_sale_coupon_discount = 0; //순매출 쿠폰할인금액
$tot_pure_sale_use_point = 0; //순매출 사용 포인트
$tot_pure_sale_use_mileage = 0; //순매출 사용 마일리지
$tot_pure_sale_pay_pg = 0; //순매출 실결제금액


$tot_refund_cnt_prod = 0;   // 전체 환불 상품 수량
$tot_refund_ordprice = 0;   // 전체 환불 주문금액

$tot_refund_price_total = 0; //환불 총액
$tot_refund_price_product = 0; //환불 상품금액
$tot_refund_price_delivery = 0; //환불 배송비
$tot_refund_cancel_coupon = 0; //환불 쿠폰금액
$tot_refund_point = 0; //환불 포인트
$tot_refund_mileage = 0; // 환불 마일리지
$tot_refund_cash = 0; //환불 현금액

while($row = $rs->FetchRow()) {

	$tot_pure_sale_pay_total += $row['basic_pay_total']; //순매출 결제총금액
	$tot_pure_sale_sum_end += $row['basic_sum_end']; //순매출 상품금액
	$tot_pure_sale_pay_delivery += $row['basic_pay_delivery']; //순매출 배송비
	$tot_pure_sale_coupon_discount += $row['basic_coupon_discount']; //순매출 쿠폰할인금액
	$tot_pure_sale_use_point += $row['basic_use_point']; //순매출 사용 포인트
	$tot_pure_sale_use_mileage += $row['basic_use_mileage']; //순매출 사용 마일리지
	$tot_pure_sale_pay_pg += $row['basic_pay_pg']; //순매출 실결제금액

	$row['sale_pay_total'] = $row['basic_pay_total'] + $row['refund_price_total'];     // 결제총금액
	$row['sale_sum_end'] = $row['basic_sum_end'] + $row['refund_price_product'];       // 상품금액
	$row['pay_delivery'] = $row['basic_pay_delivery'] + $row['refund_price_delivery'];     // 배송비
	$row['coupon_discount'] = $row['basic_coupon_discount'] + $row['refund_cancel_coupon'];     //  쿠폰할인금액
	$row['use_point'] = $row['basic_use_point'] + $row['refund_point'];    //  사용 포인트
	$row['use_mileage'] = $row['basic_use_mileage'] + $row['refund_mileage'];    //  사용 마일리지
	$row['pay_pg'] = $row['basic_pay_pg'] + $row['refund_cash'];    // 실결제금액

	$tot_refund_price_total += $row['refund_price_total']; //환불 총액
	$tot_refund_price_product += $row['refund_price_product']; //환불 상품금액
	$tot_refund_price_delivery += $row['refund_price_delivery']; //환불 배송비
	$tot_refund_cancel_coupon += $row['refund_cancel_coupon']; //환불 쿠폰금액
	$tot_refund_point += $row['refund_point']; //환불 포인트
	$tot_refund_mileage += $row['refund_mileage']; // 환불 마일리지
	$tot_refund_cash += $row['refund_cash']; //환불 현금액
	$list[] = $row;
}

$tot_sale_pay_total = $tot_pure_sale_pay_total + $tot_refund_price_total;
$tot_sale_sum_end = $tot_pure_sale_sum_end + $tot_refund_price_product;
$tot_sale_pay_delivery = $tot_pure_sale_pay_delivery + $tot_refund_price_delivery;
$tot_sale_coupon_discount = $tot_pure_sale_coupon_discount + $tot_refund_cancel_coupon;
$tot_sale_use_point = $tot_pure_sale_use_point + $tot_refund_point;
$tot_sale_use_mileage = $tot_pure_sale_use_mileage + $tot_refund_mileage;
$tot_sale_pay_pg = $tot_pure_sale_pay_pg + $tot_refund_cash;

pmysql_free_result($result);

$t_count = count($list);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>

<table border=1 cellpadding=0 cellspacing=0 width=100%>
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

	<TR align=center>
		<th rowspan=2>구분</th>
		<th colspan=6>판매</th>
		<th colspan=6>환불</th>
		<th colspan=6>순매출</th>
	</TR>
	<TR align=center>
		<th>상품구매금액</th>
		<th>배송비</th>
		<th>쿠폰</th>
		<th>포인트</th>
		<th>마일리지</th>
		<th>실결제금액</th>

		<th>상품환불금액</th>
		<th>배송비</th>
		<th>쿠폰</th>
		<th>포인트</th>
		<th>마일리지</th>
		<th>실결제금액</th>

		<th>상품구매금액</th>
		<th>배송비</th>
		<th>쿠폰</th>
		<th>포인트</th>
		<th>마일리지</th>
		<th>실결제금액</th>
	</TR>
	<?
	$colspan=20;
	$i = 0;
	//pre($list);
	foreach($list as $k) {

		if($i%2) $thiscolor="#ffeeff";
		else $thiscolor="#FFFFFF";
		?>

		<tr bgcolor=<?=$thiscolor?> onmouseover="this.style.background='#FEFBD1'" onmouseout="this.style.background='<?=$thiscolor?>'">
			<td><?=$k['cdt']?></td>
			<td style="text-align:right;"><?=number_format($k['sale_sum_end'])?></td>
			<td style="text-align:right;"><?=number_format($k['pay_delivery'])?></td>
			<td style="text-align:right;"><?=number_format($k['coupon_discount'])?></td>
			<td style="text-align:right;"><?=number_format($k['use_point'])?></td>
			<td style="text-align:right;"><?=number_format($k['use_mileage'])?></td>
			<td style="text-align:right;"><?=number_format($k['pay_pg'])?></td>
			<td style="text-align:right;"><?=number_format($k['refund_price_product'])?></td>
			<td style="text-align:right;"><?=number_format($k['refund_price_delivery'])?></td>
			<td style="text-align:right;"><?=number_format($k['refund_cancel_coupon'])?></td>
			<td style="text-align:right;"><?=number_format($k['refund_point'])?></td>
			<td style="text-align:right;"><?=number_format($k['refund_mileage'])?></td>
			<td style="text-align:right;"><?=number_format($k['refund_cash'])?></td>
			<td style="text-align:right;"><?=number_format($k['basic_sum_end'])?></td>
			<td style="text-align:right;"><?=number_format($k['basic_pay_delivery'])?></td>
			<td style="text-align:right;"><?=number_format($k['basic_coupon_discount'])?></td>
			<td style="text-align:right;"><?=number_format($k['basic_use_point'])?></td>
			<td style="text-align:right;"><?=number_format($k['basic_use_mileage'])?></td>
			<td style="text-align:right;"><?=number_format($k['basic_pay_pg'])?></td>
		</tr>
		<?
		$i++;
	}
	?>
	<tr bgcolor=<?=$thiscolor?> onmouseover="this.style.background='#FEFBD1'" onmouseout="this.style.background='<?=$thiscolor?>'">
		<td style="text-align:center;"><b>합계</b></td>

		<td style="text-align:right;"><b><?=number_format($tot_sale_sum_end)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_sale_pay_delivery)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_sale_coupon_discount)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_sale_use_point)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_sale_use_mileage)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_sale_pay_pg)?></b></td>

		<td style="text-align:right;"><b><?=number_format($tot_refund_price_product)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_refund_price_delivery)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_refund_cancel_coupon)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_refund_point)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_refund_mileage)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_refund_cash)?></b></td>

		<td style="text-align:right;"><b><?=number_format($tot_pure_sale_sum_end)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_pure_sale_pay_delivery)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_pure_sale_coupon_discount)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_pure_sale_use_point)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_pure_sale_use_mileage)?></b></td>
		<td style="text-align:right;"><b><?=number_format($tot_pure_sale_pay_pg)?></b></td>
	</tr>
</TABLE>
</body>
</html>