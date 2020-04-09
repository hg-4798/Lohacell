<?php
exit;
//미개발 및 사용안함
/**
 * 일자별 매출조회 목록 엑셀다운로드 처리
 */
$Order = new ORDER('admin');
$xlsx_filename = "일자별 매출조회목록".date('YmdHis').".xlsx"; //다운로드 파일명



parse_str($g_search, $search);
//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['order_all'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}

$Order = new ORDER;

$where = "op.order_status > 1"; //기본검색(결제완료이상부터)

//검색
parse_str($_POST['search'], $search);
$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;
// pre($search);
//pre($where);

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
				where {$where}
				group by op.date_order_2, ob.pay_total, ob.sum_end, ob.pay_delivery, ob.coupon_basket_discount, ob.coupon_delivery_discount, ob.coupon_product_discount
					, ob.use_point, ob.use_mileage, ob.pay_pg, of.price_total, of.price_product, of.price_delivery, of.cancel_coupon, of.refund_point, of.refund_mileage
					, of.refund_cash, of.refund_card, of.refund_vcnt, of.refund_acnt
				)a
				group by cdt order by cdt asc
";

// echo $sql;

$list = array();

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



$rs = $Order->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$temp = array();

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

	$tot_sale_pay_total = $tot_pure_sale_pay_total + $tot_refund_price_total;
	$tot_sale_sum_end = $tot_pure_sale_sum_end + $tot_refund_price_product;
	$tot_sale_pay_delivery = $tot_pure_sale_pay_delivery + $tot_refund_price_delivery;
	$tot_sale_coupon_discount = $tot_pure_sale_coupon_discount + $tot_refund_cancel_coupon;
	$tot_sale_use_point = $tot_pure_sale_use_point + $tot_refund_point;
	$tot_sale_use_mileage = $tot_pure_sale_use_mileage + $tot_refund_mileage;
	$tot_sale_pay_pg = $tot_pure_sale_pay_pg + $tot_refund_cash;

	//pre($product);
	foreach($column_arr as $c) {
		switch($c) {
			case 'member_id':
				$temp[] = ($row['member_id']=='')?"비회원":$row['member_id'];
				break;
			case 'productname':
				$temp[] = $row['productname']."\n".$row['option_name'];
				break;
			case 'pg_paymethod':
				$temp[] = $row['payment_detail']['name'];
				break;
			case 'order_status':
				$temp[] = $row['info'];
				break;
			case 'delivery_company':
				$temp[$no] = $deli_company[$row['delivery_company']]['company_name']."\n".$row['delivery_no'];
				break;
			default:
				$temp[] = $row[$c];
				break;
		}
	}
	$temp[] =

	$list[] = $temp;
}
//pre($list);exit;
?>