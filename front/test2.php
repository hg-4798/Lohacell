
<!doctype html>
<html lang="ko">

<head>
   
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="format-detection" content="telephone=no, address=no, email=no">
	<meta name="keywords" content="SW eshop">
	<meta name="description" content="SW eshop">
   
    <title>SW eShop</title>
    
    <link rel="stylesheet" href="/jayjun/web/static/css/common.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/component.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/content.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/jquery.bxslider.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/nouislider.css">
	
	<script src="/jayjun/web/static/js/jquery-1.12.0.min.js"></script>
	<script src="/jayjun/web/static/js/ui.js"></script>
	<script src="/jayjun/web/static/js/dev.js"></script>
	<script src="/jayjun/web/static/js/jquery.masonry.min.js"></script>
	<script src="/jayjun/web/static/js/placeholders.min.js"></script>
	<script src="/jayjun/web/static/js/jquery.bxslider.min.js"></script>
	<script src="/jayjun/web/static/js/nouislider.min.js"></script>
	<script src="/jayjun/web/static/js/wNumb.js"></script>
	
</head>
<body>



<a href="#contents" class="skip">Skip to Content</a>
	


<!-- 상세 > 리뷰 리스트 -->
<div id="contents">

	<div class="goodsView-page goodsView">
		<article class="goods-view-wrap">
		
			<h2 class="fz-0">상품 상세정보 제공</h2>
			
			<div class="goods-info-area clear">
				<div class="thumb-box">
					<div class="big-thumb" id="thumb-zoomView"> 
						<ul class="thumbList-big">
							<li><img src="/jayjun/web/static/img/test/@goods_thumb690_01.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="/jayjun/web/static/img/test/@goods_thumb690_02.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="/jayjun/web/static/img/test/@goods_thumb690_03.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="/jayjun/web/static/img/test/@goods_thumb690_04.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="/jayjun/web/static/img/test/@goods_thumb690_05.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="/jayjun/web/static/img/test/@goods_thumb690_06.jpg" alt="상품 대표 썸네일"></li>
						</ul>
					</div>
					<ul class="thumbList-small clear">
						<li><a data-slide-index="0"><img src="/jayjun/web/static/img/test/@goods_thumb690_01.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="1"><img src="/jayjun/web/static/img/test/@goods_thumb690_02.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="2"><img src="/jayjun/web/static/img/test/@goods_thumb690_03.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="3"><img src="/jayjun/web/static/img/test/@goods_thumb690_04.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="4"><img src="/jayjun/web/static/img/test/@goods_thumb690_05.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="5"><img src="/jayjun/web/static/img/test/@goods_thumb690_06.jpg" alt="상품 대표 썸네일"></a></li>
					</ul>
				</div><!-- //.thumb-box -->

					
					
					
				</div><!-- //.goods-info-area -->
		

			
			

		</article><!-- //.goods-view-wrap -->

	
	</div>
</div><!-- //#contents -->


<!-- 상세 > 매장픽업 -->
<div class="layer-dimm-wrap find-shopPickup">

</div>


<div class="layer-dimm-wrap find-shopToday">
	 


<div class="layer-inner">
	<h2 class="layer-title">매장선택</h2>
	<div class="popup-summary"><p>※ 원하는 날짜, 원하는 매장에서 상품을 픽업하는 맞춤형 배송 서비스입니다. <br>수령지를 입력하신 후 발송 가능 매장을 검색하세요(오후 4시전 주문시 당일수령 가능)</p></div>
	<button class="btn-close" type="button" ><span>닫기</span></button>
	<div class="layer-content">

		<div class="shop-search" id="order_addr_zone1" style="display: none;">
			<label>픽업 가능 매장 검색</label>
			<div class="select">
				
				<select id="store_sido"  onchange="searchShopSiDo(this.value);">
						<option value="">시·도</option>
						<option value="1">서울</option>
                        <option value="2">경기</option>
                        <option value="3">인천</option>
                        <option value="4">강원</option>
                        <option value="5">충남</option>
                        <option value="6">대전</option>
                        <option value="7">충북</option>
                        <option value="8">부산</option>
                        <option value="9">울산</option>
                        <option value="10">대구</option>
                        <option value="11">경북</option>
                        <option value="12">경남</option>
                        <option value="13">전남</option>
                        <option value="14">광주</option>
                        <option value="15">전북</option>
                        <option value="16">제주</option>
				</select>
			</div>
			<div class="select">
				<select title="구,군 선택" id="store_gugun"  onchange="">
					<option value="">구·군</option>
				</select>
			</div>
			<div class="select">
				<select title="수령일 선택" name="choiseday" id="choiseday">
					<option value="">수령일 선택</option>
					<option value="2017-03-20">2017-03-20</option>
					<option value="2017-03-21">2017-03-21</option>
					<option value="2017-03-22">2017-03-22</option>
					<option value="2017-03-23">2017-03-23</option>
					<option value="2017-03-24">2017-03-24</option>
				</select>
			</div>
		</div>

		<div class="shop-search" id="order_addr_zone2" style="display: none;">
			<label>수령지 정보 입력</label>
			<fieldset>
				<legend>수령지 검색</legend>
				<input type="hidden" id="post_code" name="post_code">
				<input type="text" id="address1" title="검색할 주소지 입력" placeholder="주소검색" onclick="sample5_execDaumPostcode()">
				<input type="text" id="address2" title="검색할 상세주소지 입력" placeholder="상세주소 입력">
				<button class="btn-point" type="button" onclick="sample5_execDaumPostcode()"><span>발송 가능 매장 찾기</span></button>
			</fieldset>
			<input type="hidden" name="lat" id="lat">
			<input type="hidden" name="lng" id="lng">
		</div>
		
		
		<div class="mt-25 clear">
		
				
			<div class="shopList-wrap with-deliveryPrice">
				
				<div class="inner">
					<section class="shopList">
						<h4 class="title">동일 브랜드 매장정보</h4>
						<ul id="mapStoreList" >
							
						</ul>
					</section>
				</div>
				
				<div class="delivery-price clear" id="order_basongprice_zone" style="display: none;"><label>배송비</label><strong class="point-color" ><span id="basong_price">0</span><span>원</span></strong></div>
			</div><!-- //.shopList-wrap -->
			<div class="shopDetail-wrap">
				<dl>
					<dt>[VIKI]강남직영점</dt>
					<dd><span>주소</span>서울 강남구 언주역</dd>
					<dd><span>TEL</span>(02)1234-1234</dd>
				</dl>
				<div class="map-local" id="map-canvas">
					
					<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true&libraries=places&key=AIzaSyBfqdKUCNcgufydVZoN3KKu6LpRD6dvcfY&region=KR"></script>

  
					
					
				</div>
			</div><!-- //.shopDetail-wrap -->
		</div>
		<div class="btnPlace mt-40">
			<button class="btn-line  h-large" type="button" onclick="location.reload();"><span>취소</span></button>
			<button class="btn-point h-large"  type="button" onclick="mapCalculation();"><span>선택</span></button>
		</div>

	</div><!-- //.layer-content -->
</div>

</div>


<!-- 주문 > 매장안내 -->
<div class="layer-dimm-wrap pop-infoStore">
	<div class="layer-inner">
		<h2 class="layer-title">매장 위치정보</h2>
		<button class="btn-close" type="button"><span>닫기</span></button>
		<div class="layer-content">

			<h3 class="store-title">[VIKI]강남직영점</h3>
			<table class="th-left mt-15">
				<caption>매장 정보</caption>
				<colgroup>
					<col style="width:180px">
					<col style="width:auto">
				</colgroup>
				<tbody>
					<tr>
						<th scope="row"><label>주소</label></th>
						<td>서울 강남구 강남대로 238-11</td>
					</tr>
					<tr>
						<th scope="row"><label>운영시간</label></th>
						<td>평일 09:00 ~ 18:00 (토/일 09:00 ~ 18:00)</td>
					</tr>
					<tr>
						<th scope="row"><label>휴무정보</label></th>
						<td>매주 일요일 / 국경일</td>
					</tr>
					<tr>
						<th scope="row"><label>매장 전화번호</label></th>
						<td>02-5212-2512</td>
					</tr>
				</tbody>
			</table>
			<div class="map-local mt-10">구글지도 위치</div>

		</div><!-- //.layer-content -->
	</div>
</div><!-- //주문 > 매장안내 -->
<form name='orderfrm' id='orderfrm' method='GET' action='../front/order.php' >
<input type='hidden' name='basketidxs' id='basketidxs' value='' >
<input type='hidden' name='staff_order' id='staff_order' value='' >
</form>

