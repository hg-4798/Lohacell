<!-- 공통 적용 스크립트 , 모든 페이지에 노출되도록 설치. 단 전환페이지 설정값보다 항상 하단에 위치해야함 -->  
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script> 
<script type="text/javascript"> 
if (!wcs_add) var wcs_add={};
wcs_add["wa"] = "s_681a3fb8926";
if (!_nasa) var _nasa={};
wcs.inflow("iknowione.co.kr");
wcs_do(_nasa);
</script>
<?php
if(!isset($Basket)) {
	$Basket = new BASKET;
}

$basket_cnt = $Basket->getCount(); //장바구니개수
list( $csnotice_title ) = pmysql_fetch( " SELECT title FROM tblcustomer_notice WHERE notice_type ='Y' ORDER BY regdt DESC LIMIT 1 " );

$assign = array(
	'cnt'=>array(
		'basket'=>$basket_cnt
	),
	'csnotice_title'=> $csnotice_title //공지사항
);

_render('_include/bottom.html', $assign, MDir.'template');

//사이트 분석통계용
$Analytics = new analytics;
$Analytics->insert();


return false;
?>