<?php
/**
 * 결제완료리스트
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$Order = new ORDER;

$where = "op.order_status > 1"; //기본검색(결제완료이상부터)

//검색
parse_str($_POST['search'], $search);
$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;
// pre($search);
//pre($where);


$page = ($_POST['page'])?$_POST['page']:'1';
$limit = ($search['limit'])?$search['limit']:'20';

if($page) {
	$offset = ($page-1)*$limit;
}

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

$count = $Order->adodb->getOne("SELECT COUNT(*) AS total FROM ({$sql}) x");

$num = $count-$offset;
$list = array();

$num = $count-($offset);

$sql .= " OFFSET {$offset} LIMIT {$limit}";
//pre($sql);
$rs = $Order->adodb->Execute($sql);

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

//pre($list);

$total_data = array(
	'tot_sale_pay_total'=>$tot_sale_pay_total,
	'tot_sale_sum_end'=>$tot_sale_sum_end,
	'tot_sale_pay_delivery'=>$tot_sale_pay_delivery,
	'tot_sale_coupon_discount'=>$tot_sale_coupon_discount,
	'tot_sale_use_point'=>$tot_sale_use_point,
	'tot_sale_use_mileage'=>$tot_sale_use_mileage,
	'tot_sale_pay_pg'=>$tot_sale_pay_pg,

	'tot_refund_price_total'=>$tot_refund_price_total,
	'tot_refund_price_product'=>$tot_refund_price_product,
	'tot_refund_price_delivery'=>$tot_refund_price_delivery,
	'tot_refund_cancel_coupon'=>$tot_refund_cancel_coupon,
	'tot_refund_point'=>$tot_refund_point,
	'tot_refund_mileage'=>$tot_refund_mileage,
	'tot_refund_cash'=>$tot_refund_cash,

	'tot_pure_sale_pay_total'=>$tot_pure_sale_pay_total,
	'tot_pure_sale_sum_end'=>$tot_pure_sale_sum_end,
	'tot_pure_sale_pay_delivery'=>$tot_pure_sale_pay_delivery,
	'tot_pure_sale_coupon_discount'=>$tot_pure_sale_coupon_discount,
	'tot_pure_sale_use_point'=>$tot_pure_sale_use_point,
	'tot_pure_sale_use_mileage'=>$tot_pure_sale_use_mileage,
	'tot_pure_sale_pay_pg'=>$tot_pure_sale_pay_pg,
);

//pre($list);

//pre($total_data);

//페이징
$paging_config = array(
	'total'=>$count,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'Sales_Day.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'count'=>$count,
	'list'=>$list,
	'paging'=>$paging,
	'total_data'=>$total_data
);

_render("report/sales_price_day.inner.html", $assign, 'admin/template');
?>
