<?php
//목록 글보기 개수
$_CONFIG['limit'] = array(
	'20','30','50'
);

$_CONFIG['pay_method'] = array(
	'card'=>'카드결제',
	'vcnt'=>'가상계좌',
	'acnt'=>'실시간계좌이체'
);

$_CONFIG['price_cut'] = array(
	'1'=>'일원단위, 예)12344 → 12340',
	'2'=>'십원단위, 예)12344 → 12300',
	'3'=>'백원단위, 예)12344 → 12000'
);

$_CONFIG['order_status'] = array(
	'0'=>'주문대기',
	'1'=>'주문완료',
	'2'=>'결제완료',
	'3'=>'배송준비중',
	'4'=>'배송중',
	'5'=>'배송완료',
	'6'=>'구매확정'
);

$_CONFIG['cancel_reason'] = array(
	'고객변심',
	'주문실수(상품/옵션)',
	'상품 품절',
	'상품정보 상이',
	'배송지연'
);

//교환사유
$_CONFIG['exchange_reason'] = array( 
	array(
		'text'=>'고객변심',
		'charger'=>'buyer'
	),
	array(
		'text'=>'옵션변경',
		'charger'=>'buyer'
	),
	array(
		'text'=>'상품정보 상이',
		'charger'=>'seller'
	),
	array(
		'text'=>'배송 지연',
		'charger'=>'seller'
	),
	array(
		'text'=>'파손 및 품질불만',
		'charger'=>'seller'
	),
	array(
		'text'=>'상품 품절',
		'charger'=>'seller'
	),
	array(
		'text'=>'배송 오류',
		'charger'=>'seller'
	)
);


//반품사유
$_CONFIG['return_reason'] = array( 
	array(
		'text'=>'고객변심',
		'charger'=>'buyer'
	),
	array(
		'text'=>'주문실수(상품/옵션)',
		'charger'=>'buyer'
	),
	array(
		'text'=>'배송지연',
		'charger'=>'seller'
	),
	array(
		'text'=>'상품정보 상이',
		'charger'=>'seller'
	),
	array(
		'text'=>'파손 및 품질불만',
		'charger'=>'seller'
	),
	array(
		'text'=>'상품 누락/배송 오류',
		'charger'=>'seller'
	)
);
?>