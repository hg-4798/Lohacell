<?

// 리뷰 작성 가능 리스트 조회
$sql  = "SELECT tblResult.ordercode, tblResult.idx ";
$sql .= "FROM ";
$sql .= "   ( ";
$sql .= "       SELECT a.*, b.regdt  ";
$sql .= "       FROM tblorderproduct a LEFT JOIN tblorderinfo b ON a.ordercode = b.ordercode ";
$sql .= "       WHERE a.productcode = '" . $productcode . "' AND b.id = '" . $_ShopInfo->getMemid()  . "' and ( (b.oi_step1 = 3 AND b.oi_step2 = 0) OR (b.oi_step1 = 4 AND b.oi_step2 = 0) ) ";
$sql .= "       ORDER BY a.idx DESC ";
$sql .= "   ) AS tblResult LEFT ";
$sql .= "   OUTER JOIN tblproductreview tpr ON tblResult.productcode = tpr.productcode and tblResult.ordercode = tpr.ordercode and tblResult.idx = tpr.productorder_idx ";
$sql .= "WHERE tpr.productcode is null ";
$sql .= "ORDER BY tblResult.idx asc ";
$sql .= "LIMIT 1 ";

$result = pmysql_query($sql);
list($review_ordercode, $review_order_idx) = pmysql_fetch($sql);
pmysql_free_result($result);
?>

<div class="layer-dimm-wrap pop_stock_detail"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner">
		<h3 class="layer-title">매장재고조회</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<h4><span>[<?=$_pdata->brand?>]</span> <?=$_pdata->productname?></h4>
			<form name="frm_storesearch" id="frm_storesearch" onSubmit="return false;">
				<input type="hidden" name="stock_prodcd" value="<?=$_pdata->prodcode?>">
				<input type="hidden" name="stock_colorcd" value="<?=$_pdata->colorcode?>">
				<input type = 'hidden' class = 'CLS_set_quantity' value = "">
				<div class="sorting-size">
					<ul class="clear">
						<li><label><input type="radio" class="CLS_sizechk" id="size_220" name="sizechk" ids="220" value="220" onclick="storeSearchChkForm('size');"><span>220</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_225" name="sizechk" ids="225" value="225" onclick="storeSearchChkForm('size');"><span>225</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_230" name="sizechk" ids="230" value="230" onclick="storeSearchChkForm('size');"><span>230</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_235" name="sizechk" ids="235" value="235" onclick="storeSearchChkForm('size');"><span>235</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_240" name="sizechk" ids="240" value="240" onclick="storeSearchChkForm('size');"><span>240</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_245" name="sizechk" ids="245" value="245" onclick="storeSearchChkForm('size');"><span>245</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_250" name="sizechk" ids="250" value="250" onclick="storeSearchChkForm('size');"><span>250</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_255" name="sizechk" ids="255" value="255" onclick="storeSearchChkForm('size');"><span>255</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_260" name="sizechk" ids="260" value="260" onclick="storeSearchChkForm('size');"><span>260</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_265" name="sizechk" ids="265" value="265" onclick="storeSearchChkForm('size');"><span>265</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_270" name="sizechk" ids="270" value="270" onclick="storeSearchChkForm('size');"><span>270</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_275" name="sizechk" ids="275" value="275" onclick="storeSearchChkForm('size');"><span>275</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_280" name="sizechk" ids="280" value="280" onclick="storeSearchChkForm('size');"><span>280</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_285" name="sizechk" ids="285" value="285" onclick="storeSearchChkForm('size');"><span>285</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_290" name="sizechk" ids="290" value="290" onclick="storeSearchChkForm('size');"><span>290</span></label></li>
						<li><label><input type="radio" class="CLS_sizechk" id="size_300" name="sizechk" ids="300" value="300" onclick="storeSearchChkForm('size');"><span>300</span></label></li>
						<li class='hide'><label><input type="radio" class="CLS_sizechk" id="size_0" name="sizechk" ids="0" value=""><span>0</span></label></li>
					</ul>
				</div>

				<p class="txt-size CLS_stock_detail_result_text">사이즈를 선택해 주세요.<!--선택하신 사이즈 <em>220</em> mm의 재고를 보유한 매장이 <em>20개</em> 검색되었습니다.--></p>

				<div class="form-box">
					<div class="my-comp-select" style="width:150px;">
						<!--class="required_value" 로인해 qna 등록시 지역설정 알럿창 떠서 클레스 삭제 2016-09-25-->
						<!--<select name="area_code" class="required_value" label="지역" onChange="storeSearchChkForm('area');">-->
						<select name="area_code" onChange="storeSearchChkForm('area');">
							<option value="">전체</option>
							<?
							foreach ( $store_area as $key => $val ) {
								?>
								<option value="<?=$key?>"><?=$val?></option>
								<?
							}
							?>
						</select>
					</div>
					<fieldset class="search_form">
						<input type="text" name="searchVal" id="searchVal" title="검색어 입력자리">
						<button type="submit" onclick="storeSearchChkForm('all');" >검색</button>
					</fieldset>
				</div>
			</form>

			<div class="map-box CLS_stock_store_list hide">
				<div class="input-area">
					<ol id="stock_store_result">
						<!--li>
							<a href="#">
								<span class="on">1</span> <!-- // [D]지점 선택시 class="on" 추가 --
								<p><strong class="type_txt3">핫티 명동 1호점</strong></p>
								<p>서울 특별시 중구 명동 111-1111 ege 빌딩 4층 가-201호</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>2</span>
								<p><strong class="type_txt3">핫티 명동 1호점</strong></p>
								<p>서울 특별시 중구 명동 111-1111 ege 빌딩 4층</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>3</span>
								<p><strong class="type_txt3">핫티 명동 1호점</strong></p>
								<p>서울 특별시 중구 명동 111-1111 ege 빌딩 4층</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>4</span>
								<p><strong class="type_txt3">핫티 명동 1호점</strong></p>
								<p>서울 특별시 중구 명동 111-1111 ege 빌딩 4층</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>5</span>
								<p><strong class="type_txt3">핫티 명동 1호점</strong></p>
								<p>서울 특별시 중구 명동 111-1111 ege 빌딩 4층</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li -->
					</ol>
				</div>
				<div class="map-area">
					<!-- <img src="../static/img/test/@test_map_img01.jpg" alt="지점지도"> -->

					<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true&libraries=places"></script>

					<script>
						var map, places, iw;
						var beaches = [];
						var markers = [];
						var markersArray = [];

						var size_x = 30;
						var size_y = 30;
						var icon_x = 20;
						var icon_y = 30;

						// 연한 아이콘 (이었다가 다시 진한 아이콘으로 변경)
						var icon_store = new google.maps.MarkerImage('./images/maps_icon_pin.png', new google.maps.Size(size_x, size_y), new google.maps.Point(0,0), new google.maps.Point(icon_x,icon_y), new google.maps.Size(size_x, size_y));

						// 진한 아이콘
						var icon_store_bold = new google.maps.MarkerImage('./images/maps_icon_pin.png', new google.maps.Size(size_x, size_y), new google.maps.Point(0,0), new google.maps.Point(icon_x,icon_y), new google.maps.Size(size_x, size_y));

						function myLocation() {
							if(navigator.geolocation){
								navigator.geolocation.getCurrentPosition(successHandler, errorHandler);
							}
						}

						function successHandler(position) {
							var geo_x = position.coords.latitude;
							var geo_y = position.coords.longitude;

							initialize(geo_x, geo_y);
						}

						function errorHandler(error) {
							var errorCode = error.code;
							var errorMessage = error.message;

							initialize('','');
						}

						function initialize(geo_x, geo_y) {
							if(geo_x == ""){
								geo_x = 37.53881;
							}

							if(geo_y == ""){
								geo_y = 127.124369;
							}

							var mapOptions = {
								center: new google.maps.LatLng(geo_x,geo_y),
								zoom: 10,
								mapTypeId: google.maps.MapTypeId.ROADMAP
							};

							map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

							var initMapLocations = [];
							<?php
							foreach($arrNearBySeoulCoord as $key => $val) {
								echo "initMapLocations.push({$val}); \n";
							}
							?>

							setMarkers(map, beaches, initMapLocations);
						}

						function setMarkers(map, locations, initMapLocations) {
							var mapIdx = 0;
							for (i = 0; i < locations.length; i++) {
								var beach = locations[i];

								addrMarkerListener(mapIdx, beach);

								if (beach[0] != 'my') {
									addResult(i, beach);
								}

								mapIdx++;
							}

							if ( locations.length == 0 ) {
								for (i = 0; i < initMapLocations.length; i++) {
									addrMarkerListener(mapIdx, initMapLocations[i]);
									mapIdx++;
								}
							}
						}

						function addrMarkerListener(i, beach){
							var displayicon = "";

							var myLatLng = new google.maps.LatLng(beach[1], beach[2]);
							if (beach[0] != 'my') {
								displayicon = icon_store;
							}

							markers[i] = new google.maps.Marker({
								position: myLatLng,
								animation: google.maps.Animation.DROP,
								map: map,
								icon: displayicon,
								title: beach[0],
								phone: beach[4],
								addr: beach[5],
								zIndex: beach[3]
							});

							if (beach[0] != 'my') {
								markerListener(map, markers[i]);
								markersArray.push(markers[i]);
							}

						}

						function deleteOverlays() {
							if (markersArray) {
								for (i in markersArray) {
									//markersArray[i].setMap(null);
									google.maps.event.addListener(markersArray[i], 'click', function() {
										this.setMap(null);
									});
								}
								markersArray.length = 0;
							}
						}

						function markerListener(map, localmarker){
							google.maps.event.addListener(localmarker, 'click', function(){
								iwOpen(map, localmarker);
							});
						}

						function iwOpen(map, localmarker){
							var contentString =	'<dl style="padding:0 8px 10px 9px;line-height:1.1;"><div style="height:6px;"></div>'+
								'<dt style="padding-bottom:10px;color:#0f8bff;font-weight:bold">'+localmarker.title+'</dt>'+
								'<dd style="padding-bottom:5px;">매장주소 : '+localmarker.addr+'</dd>'+
								'<dd>전화번호 : <a href="tel:'+localmarker.phone+'" target="_self" style="font-weight:bold">'+localmarker.phone+'</a></dd>'+
								'</dl>';

							if (iw) {
								iw.close();
								iw = null;
							}

							iw = new google.maps.InfoWindow({
								content: contentString
							});

							iw.open(map, localmarker);
						}

						function addResult(i, beach) {
							var rdate_div= $('.CLS_HiddenRdate').html();
							var results = document.getElementById('stock_store_result');

							var addr_arr	= beach[5].split(" / ");

							var li  = document.createElement('li');
							var contentString = '<li>';
							contentString += '	<a href="javascript:mapSelectStore(\''+i+'\', \''+beach[1]+'\', \''+beach[2]+'\', \''+beach[12]+'\', \''+beach[0]+'\', \''+beach[5]+'\',this);" onClick="javascript:$(\'.CLS_stock_store_num\').removeClass(\'on\');$(this).find(\'.CLS_stock_store_num\').addClass(\'on\');">';
							contentString += '		<span class="CLS_stock_store_num';
							if (beach[11] == '1') contentString += ' on';
							contentString += '">' + beach[11] + '</span>';
							contentString += '		<p><strong class="type_txt3">' + beach[0] + '</strong> [재고 : '+beach[13]+']</p>';
							contentString += '		<p>' + beach[5] + '</p>';
							contentString += '		<p class="type_txt1">' + beach[4] + '</p>';
							contentString += '	</a>';
							contentString += "<div class='CLS_HiddenRdate' id='ID_HiddenRdate_"+i+"'>"+rdate_div+"</div>";
							contentString += '</li>';

							li.innerHTML = contentString;
							results.appendChild(li);
							$('#ID_HiddenRdate_'+i).hide();
							if (beach[11] == '1'){
								$('#ID_HiddenRdate_'+i).show();
							}
							if (beach[11] == '1') mapSelectStore(i, beach[1], beach[2], beach[12], beach[0], beach[5]);
						}

						function mapSelectStore(i, x, y, storecode, storename, storeaddr,obj) {
							markers[i].setIcon(icon_store_bold);

							google.maps.event.trigger(markers[i], 'click');
							//$('body, html').animate({scrollTop:0}, 100);
							myLatLng2 = new google.maps.LatLng(x, y);
							map.setCenter(myLatLng2);
							map.setZoom(16);

							$('.CLS_HiddenRdate').hide();
							$('#ID_HiddenRdate_'+i).show();
							$('.CLS_select_reservation_date').val('');


							$(".CLS_select_storecode").val(storecode);
							$(".CLS_select_storename").val(storename);
							$(".CLS_select_storeaddr").val(storeaddr);
						}

						//매장재고 조회
						function storeSearchChkForm(type) {
							if (type == 'size') $("#frm_storesearch").find("select[name=area_code]").val('');
							if (type == 'size' || type == 'area') $("#frm_storesearch").find("input[name=searchVal]").val('');
							var prodcd			= $("#frm_storesearch").find("input[name=stock_prodcd]").val();
							var colorcd			= $("#frm_storesearch").find("input[name=stock_colorcd]").val();
							var size				= $("#frm_storesearch").find("input:radio[name=sizechk]:checked").val();
							var area_code	= $("#frm_storesearch").find("select[name=area_code]").val();
							var search			= $("#frm_storesearch").find("input[name=searchVal]").val();

							// 사이즈 선택시 날짜 셋팅
							if (type == 'size'){
								if(global_delivery_type == '1'){
									$(".required_reservation_date").load("../front/ajax_get.reserve.date.php?mode=date&delivery_type=1");
								}else if(global_delivery_type == '2'){
									//$(".required_reservation_date").load("../front/ajax_get.reserve.date.php?mode=date&delivery_type=2");
								}
								$(".CLS_stock_detail_result_text").html("선택하신 사이즈 - mm의 재고를 보유한 매장이 <em>-개</em> 검색되었습니다.");
							}

							if (size =='') {
								alert("사이즈를 선택해 주세요.");
								if (type == 'area') document.frm_storesearch.area_code.value = '';
							} else {
								if (type == 'all' && search =='') {
									alert("검색어를 입력해 주세요.");
									document.frm_storesearch.searchVal.focus();
								} else {
									var params = {
										prodcd : prodcd,
										colorcd : colorcd,
										size : size,
										area_code : area_code,
										search : search,
										delivery_type : global_delivery_type,
										option_quantity:$(".CLS_set_quantity").val()
									};


									$.post('../front/ajax_stock_store_list.php', params,function(data){
										if(data == 'noRecord'){
											$(".CLS_stock_detail_result_text").html("검색된 매장이 없습니다.");
											$(".CLS_stock_store_list").hide();
											initialize('','');
										} else {
											var data_num	=0;
											$("#stock_store_result").html("");
											beaches = [];
											markers = [];
											markersArray = [];
											$.each(data,function(entryIndex,entry)
											{
												_number						= entry.number;
												_storeName					= entry.storeName;
												_storeAddress   			= entry.storeAddress;
												_storeCode					= entry.storeCode;
												_storeTel					= entry.storeTel;
												_storeXY					= entry.storeXY;
												_storeXY_arr				= _storeXY.split("|");
												_filename					= entry.filename;
												_storeOfficeHour			= entry.storeOfficeHour;
												_storeCategory				= entry.storeCategory;
												_storeVendorName			= entry.storeVendorName;
												_storeAreaCode				= entry.storeAreaCode;
												_remainQty					= entry.remainQty;

												beaches.push([_storeName, _storeXY_arr[0], _storeXY_arr[1], data_num, _storeTel, _storeAddress,_filename,_storeOfficeHour,_storeCategory,_storeVendorName,_storeAreaCode, _number, _storeCode, _remainQty]);
												data_num++;
											});

											myLocation();

											$(".CLS_stock_detail_result_text").html("선택하신 사이즈 <em>"+size+"</em> mm의 재고를 보유한 매장이 <em>"+data_num+"개</em> 검색되었습니다.");
											$(".CLS_stock_store_list").show();
										}
									});
								}
							}
						}

						function resetStockDetail() {
							$(".CLS_stock_detail_result_text").html("사이즈를 선택해 주세요.");
							document.frm_storesearch.sizechk.value		= "";
							document.frm_storesearch.area_code.value	= "";
							document.frm_storesearch.searchVal.value	= "";
							$("#stock_store_result").html("");
							$(".CLS_stock_store_list").hide();
							$('.CLS_sizechk#size_0').trigger("click");
							initialize('','');
						}
					</script>
					<div id="map-canvas" style="min-height:388px;width: 100%"></div>
				</div>
			</div>




			<input type = 'hidden' class = 'CLS_select_storecode'>
			<input type = 'hidden' class = 'CLS_select_reservation_date'>
			<input type = 'hidden' class = 'CLS_select_storename'>
			<input type = 'hidden' class = 'CLS_select_storeaddr'>
			<div class='CLS_HiddenRdate' id='ID_HiddenRdate' style = 'display:none;'>
			<div class="form-box CLS_reservation_date" style = 'display:none;'>
				<div class="form-box">
					<div class="my-comp-select" style="width:150px;">
						<select class="required_reservation_date" label="날짜" onchange="$('.CLS_select_reservation_date').val($(this).val())">
							<option value="">- 사이즈 선택 -</option>
						</select>
					</div>
					<fieldset class="search_form">
						<button type="button" onclick="storeSelectData();" style = 'width:100px;'>매장픽업 선택</button>
					</fieldset>

				</div>
			</div>
			</div>

			<style>
				.pop_address_input div { text-align:left; }
				.pop_address_input div .short{ width:60px; height:24px; }
				.pop_address_input div .long{ width:250px; height:24px; padding:0 5px; border:1px solid #ddd; font-size:1.1rem;  }
			</style>
			<div class="form-box CLS_write_address" style = 'display:none;'>
				<div class="form-box">
					<div class = 'pop_address_input' style="width:540px;">
						<div>

							<input type='hidden' id='post5' name='post5' value='' >
							<input type="hidden" id="rpost1" name = 'rpost1'>
							<input type="hidden" id='rpost2' name = 'rpost2'>
							<input type="text" name = 'post' id = 'post' class="short" title="우편번호 첫번째 입력자리" readonly>
							<a href="javascript:openDaumPostcode();" class="btn-type1 ml-5">주소찾기</a>

						</div>
						<div class="mt-5">
							<input type="text" name = 'raddr1' id = 'raddr1' class = 'long' title="우편번호 선택에 의한 주소 자동입력 자리" readonly>
							<input type="text" name = 'raddr2' id = 'raddr2' class = 'long' title="자동입력주소 외 상세 주소 입력자리">
						</div>
					</div>
					<fieldset class="search_form">
						<button type="button" onclick="storeSelectData();" style = 'width:100px;height:50px;'>당일수령 선택</button>
					</fieldset>

				</div>
			</div>



			<div class="list_text">
				<h3>유의사항</h3>
				<ul>
					<li>ㆍ핫티 매장재고조회는 상품의 매장별 재고정보를 제공하여 고객님의 상품 구매를 지원합니다.</li>
					<li>ㆍ상품 특성상 매장 재고가 실시간으로 변동되어 재고로 표시되어도 품절되는 경우가 있으니 방문 전, 꼭 매장에 직접 문의를 부탁 드립니다.</li>
					<li>ㆍ행사진행에 따른 매장별 가격이 상이할 수 있습니다.</li>
				</ul>
			</div>

		</div> <!-- //.layer-content-->
	</div>
</div>

<div class="layer-dimm-wrap pop-review-detail"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner w800">
		<h3 class="layer-title">HOT<span class="type_txt1">-T</span> 상품리뷰 <span id="submit_type">작성</span></h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">

			<form name='reviewForm' id='reviewForm' method='POST' action='' >
				<table class="th_left">
					<caption>상품리뷰 작성/상세보기</caption>
					<colgroup>
						<col style="width:100px">
						<col style="width:auto">
					</colgroup>
					<tbody>
					<tr>
						<th scope="row">상품</th>
						<td colspan="3" class="goods_info modify_info">
							<a href="javascript:void(0)">
								<img src="../data/shopimages/product/<?=$_pdata->minimage?>" alt="마이페이지 상품 썸네일 이미지">
								<ul class="bold">
									<li id="qna-brandname"><?=$_pdata->brand?></li>
									<li id="qna-productname"><?=strip_tags($_pdata->productname)?></li>
								</ul>
							</a>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_writer">만족도</label></th>
						<td>
							<div class="my-comp-select">
								<select title="" class="selectbox" name="review_vote" id="review_vote">
									<option value="5">★ ★ ★ ★ ★</option>
									<option value="4">★ ★ ★ ★ </option>
									<option value="3">★ ★ ★ </option>
									<option value="2">★ ★ </option>
									<option value="1">★</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_writer">상품 평가</label></th>
						<td>
							<section class="wrap_select_rating">
								<div class="select_rating">
									<span>사이즈</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_size" id="review_size-2">
												<label for="review_size-2">작다</span>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_size" id="review_size-1">
												<label for="review_size-1" class="none">조금 작다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_size" id="review_size0" checked>
												<label for="review_size0">적당함</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_size" id="review_size1">
												<label for="review_size1" class="none">조금 크다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_size" id="review_size2">
												<label for="review_size2">크다</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>발볼 넓이</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_foot_width" id="foot_width-2">
												<label for="foot_width-2">작다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_foot_width" id="foot_width-1">
												<label for="foot_width-1" class="none">조금 작다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_foot_width" id="foot_width0" checked>
												<label for="foot_width0">적당함</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_foot_width" id="foot_width1">
												<label for="foot_width1" class="none">조금 크다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_foot_width" id="foot_width2">
												<label for="foot_width2">크다</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>색상</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_color" id="color-2">
												<label for="color-2">어둡다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_color" id="color-1">
												<label for="color-1" class="none">조금 어둡다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_color" id="color0" checked>
												<label for="color0">화면과 같다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_color" id="color1">
												<label for="color1" class="none">조금 밝다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_color" id="color2">
												<label for="color2">밝다</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>품질/만족도</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_quality" id="quality-2">
												<label for="quality-2">불만</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_quality" id="quality-1">
												<label for="quality-1" class="none">조금 불만</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_quality" id="quality0" checked>
												<label for="quality0">보통</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_quality" id="quality1">
												<label for="quality1" class="none">조금 만족</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_quality" id="quality2">
												<label for="quality2">만족</label>
											</div>
										</li>
									</ul>
								</div>
							</section>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_writer">제목 <span class="required">*</span></label></th>
						<td>
							<input type="text" name="inp_writer" id="review_title" title="제목 입력자리" style="width:100%;">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_content">내용 <span class="required">*</span></label></th>
						<td>
							<textarea name="inp_content" id="review_content" cols="30" rows="10" style="width:100%"></textarea>
							<p class="s_txt">ㆍ배송, 상품문의, 취소, 교환 등의 문의사항은 1:1문의 또는 상담전화를 이용해 주시기 바랍니다</p>
						</td>
					</tr>
					<tr>
						<th scope="row">사진 <span class="required">*</span></th>
						<td>
							<!-- <form> -->
							<fieldset>
								<legend>상품 리뷰작성을 합니다.</legend>
								<ul class="reg-review">
									<li>
										<div class="add-photo-wrap">
											<div class="add-photo" id="add-photo1">
												<!--<button type="button">삭제</button>
												<p style="background:url(../static/img/test/@test_review_dum1.jpg) center no-repeat; background-size:contain"></p>-->
												<input type="file" name="up_filename[]" accept="image/*">
												<input type="hidden" id="file_exist" name="file_exist" value="N" />
												<input type="hidden" name="v_up_filename[]" id="upfile">
											</div>
											<div class="add-photo" id="add-photo2">
												<!--<button type="button">삭제</button>
												<p style="background:url(../static/img/test/@test_review_dum1.jpg) center no-repeat; background-size:contain"></p>-->
												<input type="file" name="up_filename[]" accept="image/*">
												<input type="hidden" id="file_exist" name="file_exist" value="N" />
												<input type="hidden" name="v_up_filename[]" id="upfile2">
											</div>
											<div class="add-photo" id="add-photo3">
												<input type="file" name="up_filename[]" accept="image/*">
												<input type="hidden" id="file_exist" name="file_exist" value="N" />
												<input type="hidden" name="v_up_filename[]" id="upfile3">
											</div>
											<div class="add-photo" id="add-photo4">
												<input type="file" name="up_filename[]" accept="image/*">
												<input type="hidden" id="file_exist" name="file_exist" value="N" />
												<input type="hidden" name="v_up_filename[]" id="upfile4">
											</div>
											<div class="add-photo" id="add-photo5">
												<input type="file" name="up_filename[]" accept="image/*">
												<input type="hidden" id="file_exist" name="file_exist" value="N" />
												<input type="hidden" name="v_up_filename[]" id="upfile5">
											</div>
										</div>
									</li>
								</ul>
							</fieldset>
							<!-- </form> -->
							<p class="s_txt">ㆍ파일명 : 한글, 영문, 숫자 / 파일 용량 : 3M 이하 / 파일 형식 : GIF, JPG(JPEG)</p>
						</td>
					</tr>
					</tbody>
				</table>

				<input type="hidden" name="productcode" id="productcode" value="<?=$productcode?>" />
				<input type="hidden" name="productname" id="productname" value="" />
				<input type="hidden" name="ordercode" id="ordercode" value="<?=$review_ordercode?>" />
				<input type="hidden" name="productorder_idx" id="productorder_idx" value="<?=$review_order_idx?>" />
				<input type="hidden" name="review_num" id="review_num" value="0" />
				<input type="hidden" name="mode" id="mode" value="" />
				<input type="hidden" name="color" id="color" value="" />
				<input type="hidden" name="size" id="size" value="" />
				<input type="hidden" name="foot_width" id="foot_width" value="" />
				<input type="hidden" name="quality" id="quality" value="" />
			</form>
			<p class="ment mt-5"><span class="required">*</span> 필수입력</p>
			<div class="btn_wrap"><a href="javascript:;" class="btn-type1" id="review_submit" onclick='javascript:ajax_review_insert();'>저장</a></div>
		</div>
	</div>
</div>
<!-- // 상품리뷰 상세팝업 -->

<!-- 상품 질문 dimm layer -->
<div class="layer-dimm-wrap pop-qna-detail">
	<div class="dimm-bg"></div>
	<div class="layer-inner w800">
		<h3 class="layer-title">HOT<span class="type_txt1">-T</span> 상품문의</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<h4 class="title"></h4>
			<table class="th_left util">
				<caption>상품에 대한 궁금증 질문</caption>
				<colgroup><col style="width:100px"><col style="auto"></colgroup>
				<tbody>
				<tr>
					<th scope="row"><label for="qna-subject">제목</label> <span class="required">*</span></th>
					<td colspan="3"><input type="text" id="qna-subject" class="input-def" title="질문 제목 입력자리" style="width:100%;"></td>
				</tr>
				<tr>
					<th scope="row"><label for="qna-content">내용</label></th>
					<td colspan="3">
						<textarea id="qna-content" cols="30" rows="10" class="textarea-def" style="width:100%;"></textarea>
						<!--<p class="ment mt-5">
						※ 배송,상품문의,취소,교환등의 문의사항은 고객센터를 이용해 주시기 바랍니다.  상품평에 작성하시면 답변을 받지 못합니다.</p>-->
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="phone_chk">휴대폰 답변</label><input id="phone_chk" type="checkbox" class="chk_agree checkbox-def ml-5"></th>
					<td>
						<input type="text" class="chk_only_number" id="hp" name="hp" placeholder="하이픈(-) 없이 입력" title="휴대폰 번호" label="휴대폰 답변" style="width:240px" maxlength="11">
					</td>
					<th><label for="email_chk">이메일 답변</label><input id="email_chk" type="checkbox" class="chk_agree checkbox-def ml-5"></th>
					<td>
						<input type="text" id="email" name="email" title="이메일 아이디 입력자리" label="이메일 답변" style="width:240px">
					</td>
				</tr>
				<tr>
					<th scope="row">공개여부</th>
					<td colspan="3">
						<input type="radio" name="view-type" id="view" value='0' class="radio-def" checked>
						<label for="view">공개</label>
						<input type="radio" name="view-type" id="no-view" value='1' class="radio-def">
						<label for="no-view">비공개</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="qna-pwd">비밀번호</label></th>
					<td colspan="3"><input type="password" id="qna-pwd" class="input-def"><input type=hidden name=oldpass id=oldpass></td>
				</tr>
				</tbody>
			</table>
			<p class="ment mt-5"><span class="required">*</span> 필수입력</p>
			<div class="btn_wrap">
				<input type='hidden' id='qna-num' value='' >
				<a href="#" class="btn-type1" onclick='javascript:QnAController();'>문의하기</a>
			</div>
		</div>
	</div>
</div>
<style>

</style>

<!-- 무이자 카드 dimm layer -->
<div class="layer-dimm-wrap layer-detail-card" >
	<div class="dimm-bg"></div>
	<div class="layer-inner ">
		<h3 class="layer-title"></h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<div class="card-month-scroll js-scroll">
				<?php
				foreach( $card_banner[111] as $cardKey=>$cardVal ){
					echo "<img src='".$cardVal['banner_img']."' alt='무이자할부 안내' >";
				}
				?>
				<!-- <img src="../static/img/common/card_monthly_benefit.jpg" alt="무이자할부 안내"> -->
			</div>
		</div>
	</div>
</div>

<!-- 관련포스팅_상세보기 팝업 -->
<div class="layer-dimm-wrap pop-view-detail CLS_instagram"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner">
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<div class="img-area">
				<img src="" alt="" id="instagram_img">
			</div>
			<div class="cont-area">
				<div class="title">
					<h3><span class="pl-10"><!-- <img src="" alt="instagram"> --></span></h3>
					<!--  <button class="comp-like btn-like" title="선택 안됨"><span id="like_count"></button> <!-- // [D] 좋아요를 선택하면 버튼에 class="on" title="선택됨"을 추가 -->
				</div>
				<div class="cont-view">
					<div class="inner">
						<p id="content"></p>
						<p class="tag" id="instagram_tag">
							<!-- 							#hott #hottest #nike #airjordan #Jordan #shoes #fashion #item #ootd #dailylook #핫티 #나이키 #에어조던 #조던 #신발 #패션 #아이템 #데일리 #데일리룩 #데일리슈즈 #신스타그램 #슈스타그램 #daily #dailyshoes #shoestagram -->
						</p>
					</div>
				</div>
				<div class="goods-detail-related">
					<h3>관련 상품</h3>
					<ul class="related-list">
						<!--
                                                <li>
                                                    <a href="javascript:;">
                                                        <figure>
                                                            <img src="../static/img/test/@test_instagram_wish01.jpg" alt="관심상품">
                                                            <figcaption>
                                                                # CONVERSE<br>
                                                                CTAS 70 HI
                                                            </figcaption>
                                                        </figure>
                                                    </a>
                                                </li> -->

						</li>
					</ul>
				</div> <!-- // .goods-detail-related -->
			</div> <!-- // .cont-area -->
			<!--
            <div class="btn-wrap">
                <a href="javascript:pagePrev();" class="view-prev">이전</a>
                <a href="javascript:pageNext();" class="view-next">다음</a>
            </div>-->
		</div>
	</div>
</div>
<!-- // 관련포스팅_상세보기 팝업 -->
<!-- 네비게이션 -->
<div class="top-page-local">
	<ul>
		<li><a href="/">HOME</a></li>
		<li <?=$thisCate2[1] ? "" : "class='on'" ?>><a href="<?=$Dir?>front/productlist.php?code=<?=substr($thisCate2[0]->category, 0, 3)?>"><?=$thisCate2[0]->code_name?></a></li>
		<?php
		$txt_tot_cate	= $thisCate2[0]->code_name;
		if( count( $thisCate2 ) > 1 ){
			$loop_cnt = count($thisCate2);
			for ( $i = 1; $i < $loop_cnt; $i++ ) {
				$classOn = "";
				if ( $i == $loop_cnt - 1 ) {
					$classOn = "on";    // 마지막 카테고리에 on 처리 ?>
					<li class="<?=$classOn?>"><?=$thisCate2[$i]->code_name?></li>
				<?   }else{ ?>
					<li><a href="<?=$Dir?>front/productlist.php?code=<?=$thisCate2[$i]->category?>"><?=$thisCate2[$i]->code_name?></a></li>
				<?	}
				$txt_tot_cate	.= "/".$thisCate2[$i]->code_name;
				?>
				<?
			} // end of for
		}
		?>

	</ul>
</div>
<!-- //네비게이션-->


<div id="contents">
	<!-- goods #container -->
	<main id="contents">
		<!-- 상품상세 - 상단 -->
		<div class="goods-detail-hero">
			<form name='prForm' id='prForm' method='POST' action="<?=$Dir.FrontDir?>basket.php" >
				<input type='hidden' name='prcode' id='prcode' value='<?=$_pdata->productcode?>' >
				<input type='hidden' name='pridx' id='pridx' value='<?=$_pdata->pridx?>' >
				<input type="hidden" name="constant_quantity" id="constant_quantity" value="<?=$_pdata->quantity?>" >
				<input type='hidden' name="up_name" id="up_name" value="<?=$_ShopInfo->getmemname()?>" >
				<input type="hidden" id="brand_name" value="<?=$_pdata->brand?>">
				<input type="hidden" id="link-label" value="HOTT 온라인 매장">
				<input type="hidden" id="link-title" value="<?=$_pdata->brand?> <?=$_pdata->productname?>">
				<input type="hidden" id="link-image" value="<?=$_pdata->maximage?>" data-width='200' data-height='300'>
				<input type="hidden" id="link-url" value="<?=$link_url ?>">
				<input type="hidden" id="link-img-path"value="<?=$imgPath ?>">
				<input type="hidden" id="link-code"value="<?=$productcode ?>">
				<input type="hidden" id="link-menu"value="product">
				<input type="hidden" id="link-memid" value="<?=$_ShopInfo->getMemid()?>">

				<!-- 상품상세 - 상단 - 이미지 -->
				<div class="hero-image">
					<!-- (D) 이미지는 background-image:url()로 연결합니다. -->
					<ul class="image-list">

						<?
						// 상품 썸네일 옆에 작은 이미지들을 배열에 저장해서 한번에 그려준다.
						$arrMiniThumbList = array();

						# 상품 큰 이미지
						if( is_file( $imagepath_product.$_pdata->maximage ) || strpos($_pdata->maximage, "http://") !== false ) {
							$tmp_imgCont = "<li style=\"background-image:url('" . getProductImage($imagepath_product, $_pdata->maximage) . "');\"></li>\n";
							array_push($arrMiniThumbList, $tmp_imgCont);
						}

						if ( $multi_img=="Y" && $yesimage[0] ) {
							$arrMultiImg = array(); // 상품 상세 설명이 없는 경우 노출하기 위해 배열에 저장
							foreach( $yesimage as $mImgKey=>$mImgVal ){
								$multiImg = getProductImage($imagepath_multi, $mImgVal);
								array_push($arrMultiImg, $multiImg);

								$tmp_imgCont = "<li style=\"background-image:url('" . $multiImg . "');\"></li>\n";
								array_push($arrMiniThumbList, $tmp_imgCont);
							}
						}

						if ( count($arrMiniThumbList) >= 1 ) {
							foreach ( $arrMiniThumbList as $key => $val ) {
								echo $val;
							}
						}
						?>
					</ul>
				</div>
				<!-- // 상품상세 - 상단 - 이미지 -->
				<?
				if ($_pdata->sex =='M') $_pdata_sex	= "MEN";
				else if ($_pdata->sex =='F') $_pdata_sex	= "WOMEN";
				else if ($_pdata->sex =='U') $_pdata_sex	= "UNISEX";
				?>
				<!-- 상품상세 - 상단 - 정보 -->
				<div class="hero-info">
					<h2>
						<div class="brand">[ <?=$_pdata->brand?> ] <span>[ <?=$_pdata_sex?> ]</span> <!-- // [D] 20160913 성별추가 --></div>
						<?=$_pdata->productname?>
						<p class="ko-name"><?=$_pdata->productname_kor?></p> <!-- // [D] 20160913 한글 상품명추가 -->
					</h2>
					<div class="price-wrap">
						<?if($_pdata->consumerprice != $_pdata->sellprice){ ?>
							<dl class="price">
								<dt>판매가</dt>
								<dd><del><?=number_format( $_pdata->consumerprice )?></del></dd>
							</dl>
							<dl class="sale-price">
								<dt>혜택가</dt>
								<dd>
									<strong><?=number_format( $_pdata->sellprice )?></strong>
									<span class="sale"><?=get_price_percent( $_pdata->consumerprice, $_pdata->sellprice )?>%</span>
								</dd>
							</dl>
						<?}else{ ?>
							<dl class="sale-price">
								<dt>혜택가</dt>
								<dd>
									<!-- <strong><?=number_format($val['consumerprice']) ?></strong> -->
									<strong><?=number_format($_pdata->sellprice) ?></strong>
								</dd>
							</dl>
						<?} ?>
						<?if ($coupon_use['code'] !='' && $coupon_use['btn_yn']=='Y') {?>
							<dl class="coupon">
								<dt>쿠폰가</dt>
								<dd>
									<strong><?=number_format($coupon_use['price']) ?></strong>
									<a href="javascript:;" class="CLS_coupon_download ml-5" data-coupon='<?=encrypt_md5("COUPON|".$coupon_use['type']."|".$coupon_use['code'],"*ghkddnjsrl*")?>'><img src="../static/img/btn/btn_coupon_download.gif" alt="쿠폰다운로드"></a>
									<div class="speech-bubble">
										<?=$coupon_use['name']?>
									</div>
								</dd>
							</dl>
						<?}?>
						<?if($_ShopInfo->getStaffYn() == 'Y' && $_pdata->hotdealyn=='N'){?>
							<dl class="staff">
								<dt>임직원가</dt>
								<dd>
									<strong><?=number_format($staff_use['price']) ?></strong>
								</dd>
							</dl>
						<?}?>
						<!-- <?if($_pdata->consumerprice != $_pdata->sellprice){ ?>
						<del><?=number_format( $_pdata->consumerprice )?></del>
						<strong><?=number_format( $_pdata->sellprice )?></strong>
						<span class="sale"><?=get_price_percent( $_pdata->consumerprice, $_pdata->sellprice )?>%</span>
					<?}else{ ?>
						<strong><?=number_format($val['consumerprice']) ?></strong>
					<?} ?>
					<?if ($coupon_use['code'] !='' && $coupon_use['btn_yn']=='Y') {?>
					<span class="coupon">쿠폰가 <em><?=number_format($coupon_use['price']) ?></em></span>
					<span class="sale"><?=$coupon_use['per']?>%</span>
					<span class="coupon"><?=$coupon_use['name']?>&nbsp;<a href="javascript:;" class="CLS_coupon_download" data-coupon='<?=encrypt_md5("COUPON|".$coupon_use['type']."|".$coupon_use['code'],"*ghkddnjsrl*")?>'>[쿠폰 다운로드]</a></span>
					<?}?>
					<?if($_ShopInfo->getStaffYn() == 'Y'){?>
					<div class="comp-member">임직원가 <em><?=number_format($staff_use['price']) ?></em></div>
					<span class="sale"><?=$staff_use['per']?>%</span>
					<?}?> // 기존 개발 소스-->
					</div>
					<?$colorProdHtml = getColorProduct($_pdata->productcode, $_pdata->prodcode, "detail" );
					$colorProdText = getColorProductText($_pdata->productcode, $_pdata->prodcode );
					?>
					<div class="hero-info-color with-btn-rolling<?=!$colorProdHtml?' hide':''?>">
						<p><?=$colorProdText ?></p>
						<!-- (D) 선택된 li에 class="on" title="선택됨"을 추가합니다. -->
						<ul class="CLS_colorProduct">
							<?=$colorProdHtml?>
							<!-- 					<li class="on" title="선택됨"><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color1.jpg" alt="Wolf Grey"></a></li> -->
							<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color2.jpg" alt="Bright Crimson"></a></li> -->
							<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color3.jpg" alt="Gym Red"></a></li> -->
							<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color4.jpg" alt="Sail"></a></li> -->
							<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color5.jpg" alt="Dark Obsidian"></a></li> -->
							<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color6.jpg" alt="Black"></a></li> -->
						</ul>
					</div>






					<!-- 배송 방법 선택 -->
					<style>
						.CLS_store_selection_done_layer{
							display:none; position: absolute; background: #FFFFFF; padding: 8px; margin-left: -200px;margin-top: -10px; border: 1px solid #999;
						}
						.hero-info-delivery-type .sorting-size {margin:10px 0; overflow:hidden;}
						.hero-info-delivery-type .sorting-size li {float:left;width:114px; margin-right:5px; height:28px; line-height:28px; text-align:center; color:#000; border:1px solid #696969; }
						.hero-info-delivery-type .sorting-size li:last-child {margin-right:0;}
						.hero-info-delivery-type .sorting-size label{display:block; overflow:hidden; position:relative; cursor:pointer;}
						.hero-info-delivery-type .sorting-size label input[type="radio"]{position:absolute; z-index:-1; left:-9999px; margin:0;}
						.hero-info-delivery-type .sorting-size label span{display:block; height:28px; color:000; font-size:1.2rem; line-height:28px; text-align:center;}
						.hero-info-delivery-type .sorting-size label :checked + span{background:#000; color:#fff; border:1px solid #000;}
					</style>
					<div class="hero-info-delivery-type">
						<div class="sorting-size">
							<ul class="clear">
								<?foreach($arrDeliveryType as $k => $v){?>
									<li><label><input type="radio" class="CLS_delivery_type" name="delivery_type" value="<?=$k?>" <?if($k=='0'){?>checked<?}?>><span><?=$v?></span></label></li>
								<?}?>
							</ul>
						</div>
					</div>

					<div class="hero-info-form">
						<?php
						if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity > 0  && $_pdata->option_type == '0' ){ // 조합형 옵션
							foreach( $optionNames as $nameKey=>$nameVal ) {
								?>
								<div class="comp-select size">
									<select name='op_opt[]' class="my_value CLS_option_value" title="<?=$nameVal?>" data-option-code='' onchange="javascript:option_select( this.value, '<?=$nameKey?>' );">
										<option value="" data-qty='' data-code=''><?=$nameVal?></option>
										<?php
										if( $nameKey == 0 ) {
											foreach( $options as $oKey=>$oVal ) {
												$option_qty = $oVal['qty'];
												$option_disable = '';
												$option_text = '';
												$priceText = '';
												if(  $option_depth == 1 && $oVal['price'] > 0 ){
													$priceText = ' ( + '.number_format($oVal['price']).' 원 )';
												} else if( $option_depth == 1 && $oVal['price'] < 0 ) {
													$priceText = ' ( - '.number_format($oVal['price']).' 원 )';
												}

												if(
													( $option_qty !== null && $option_qty <= 0 ) &&
													( ( $option_depth > 0 && $nameKey != 0 ) || ( $option_depth == 1 && $nameKey == 0 ) ) &&
													$_pdata->quantity < 999999999
												){
													$option_disable = ' disable';
													$option_text = '[품절]';
												}
												?>
												<option value="<?=$oVal["code"]?>" <?=$option_disable?> data-qty='<?=$option_qty?>' data-code='<?=$oVal["code"]?>'><?=$option_text.$oVal["code"].$priceText?></option>
												<?php
											} // foreach $options
										} // nameKey if
										?>
									</select>
								</div>
								<?php
							} // optionNames foreach
						} else if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity <= 0  && $_pdata->option_type == '0' ) { // if $_pdata->option1 품절된 옵션
							foreach( $optionNames as $nameKey=>$nameVal ) {
								?>
								<div class="comp-select size">
									<select name='op_opt[]' class="my_value CLS_option_value" title="<?=$nameVal?>" data-option-code='' >
										<option value="" data-qty='' data-code='' selected>품절</option>
									</select>
								</div>
								<?php
							} // optionNames foreach
						}
						$tf_arr = explode( '@#', $_pdata->option1_tf );
						if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity > 0  && $_pdata->option_type == '1' ){ // 독립형 옵션
							foreach( $optionNames as $nameKey=>$nameVal ) {
								?>
								<div class="comp-select size">
									<select name='alone_option[]' class="my_value" title="<?=$nameVal?><?=($tf_arr[$nameKey] == 'T')?' (필수)':' (선택)'?>" data-option-code='' data-option-qty='' onchange="javascript:option_select( this.value, '<?=$nameKey?>' );">
										<option value="" data-qty='' data-code='' data-tf=''><?=$nameVal?><?=($tf_arr[$nameKey] == 'T')?' (필수)':' (선택)'?></option>
										<?php
										foreach( $options[$nameVal] as $oKey=>$oVal ) {
											if( $oVal->option_tf == 'T' ) $tf_text = '*필수';
											//exdebug( $oVal );
											$option_disable = '';
											$option_text = '';
											$priceText = '';
											if( $oVal->option_price > 0 ){
												$priceText = ' ( + '.number_format($oVal->option_price).' 원 )';
											}else if( $oVal->option_price < 0 ) {
												$priceText = ' ( - '.number_format($oVal->option_price).' 원 )';
											}
											if(
												( $oVal->option_quantity !== null && $oVal->option_quantity < 0 ) &&
												$_pdata->quantity < 999999999
											){
												$option_disable = ' disable';
												$option_text = '[품절]';
											}
											?>
											<option value="<?=$oVal["code"]?>" <?=$option_disable?> data-qty='<?=$oVal->option_quantity?>' data-code='<?=$oVal->option_code?>' data-tf='<?=$oVal->option_tf?>'><?=$option_text.$oKey.$priceText?></option>
											<?php
										}
										?>
									</select>
									<input type='hidden' name='alone_option_tf[]' value='<?=$oVal->option_tf?>' >
								</div>
								<?php
							} // $optionName foreach
						} else if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity <= 0  && $_pdata->option_type == '1' ) { // option_type if
							foreach( $optionNames as $nameKey=>$nameVal ) {
								?>
								<div class="comp-select size">
									<select name='op_opt[]' class="my_value CLS_option_value" title="<?=$nameVal?>" data-option-code='' >
										<option value="" data-qty='' data-code='' selected>품절</option>
									</select>
								</div>
								<?php
							}
						}
						if( strlen( $_pdata->option2 ) > 0 && $_pdata->quantity > 0 ){
							foreach( $addOptionNames as $addKey=>$addVal ){
								?>
								<div class="qty comp-input">
									<input type="text" class="input-def" name ='addoption[]' data-option-code='<?=$addVal?>' data-option-tf='<?=$addOption_tf[$addKey]?>' maxlength='<?=$addOption_maxlen[$addKey]?>'>
									<input type='hidden' name='addoption_tf[]' value='<?=$addOption_tf[$addKey]?>' >
									<span class="byte hide">(<strong>0</strong>/<?=$addOption_maxlen[$addKey]?>)</span>
								</div>
								<?php
							} // addoption foreach
						} else if( strlen( $_pdata->option2 ) > 0 && $_pdata->quantity <= 0 ) {// addoption if
							foreach( $addOptionNames as $addKey=>$addVal ){
								?>
								<div class="qty comp-input">
									<input type="text" name ='addoption[]' data-option-code='<?=$addVal?>' data-option-tf='<?=$addOption_tf[$addKey]?>' maxlength='<?=$addOption_maxlen[$addKey]?>' disabled value='품절' >
									<input type='hidden' name='addoption_tf[]' value='<?=$addOption_tf[$addKey]?>' >
									<span class="byte hide">(<strong>0</strong>/<?=$addOption_maxlen[$addKey]?>)</span>
								</div>
								<?php
							} // addoption foreach
						}
						?>



						<!--
				<div class="qty">
					<input type="text" name='quantity' id='quantity' title="수량" value="<?=($_pdata->quantity <= 0 || $_pdata->soldout == 'Y')?'0':'1'?>" style="width:234px;">
					<button class="btn_add btn-plus" type="button"><span>수량 1 더하기</span></button>
					<button class="btn_subtract btn-minus" type="button"><span>수량 1 빼기</span></button>
				</div>
				<div class="btn_stock btn_stock_detail">
					<a class="btn-type1" href="javascript:;" onClick="javascript:resetStockDetail();">매장재고조회</a>
				</div>
				-->



						<!--div class="comp-select shipping">
                            <select title="배송/수령방법">
                                <option value="0">배송/수령방법</option>
                            </select>
                        </div-->
					</div>

					<!-- 옵션 추가 레이어 테이블 -->
					<style>
						.hero-info-option {padding-top:5px;}
						.hero-info-option div.qty{position:relative; margin-left:10px}
						.hero-info-option div.qty input[type="text"]{width:122px !important;box-sizing:border-box; margin-bottom:3px; height:35px; border:1px solid #808080; background:transparent; color:#000; font-size:1.4rem;}
						.hero-info-option div.qty button {position:absolute; left:83px; width:39px; height:17px; border:1px solid #808080;}
						.hero-info-option div.qty button span{display:block; overflow:hidden; font-size:0; text-indent:-9999px;}
						.hero-info-option div.qty button.btn_add{top:0; background:url("../static/img/btn/btn_arrow_up.png") no-repeat 50% 50%;}
						.hero-info-option div.qty button.btn_subtract{bottom:3px; background:url("../static/img/btn/btn_arrow_down.png") no-repeat 50% 50%;}


						.hero-info-option .btn_option_delete {float:left;bottom:3px; right:0; margin:0px auto; text-align:center; width:100%; margin-left:5px;}
						/*.hero-info-option .btn_option_delete a {width:114px; height:35px; line-height:35px; background:#a6a6a6; font-size:1.3rem;}*/
						.hero-info-option .btn_option_delete a {display:block; margin-left:3px; width:18px; height:18px; background:url("../static/img/btn/btn_delivery_del.png") no-repeat; text-indent:-9999px;}

						.hero-info-option .btn_option_delete_type {float:left;bottom:3px; right:0; margin:0px auto; text-align:center; width:100%;}
						.hero-info-option .btn_option_delete_type a.addOptionDelete {display:block; width:18px; height:18px; background:url("../static/img/btn/btn_delivery_del.png") no-repeat; text-indent:-9999px;}
						.hero-info-option .btn_option_delete_type a.addOptionStore {margin:0 3px; width:90px; height:35px; line-height:35px; background:#a6a6a6;}
						.hero-info-option .store_selection_area {width:63px; text-align:center;}
						.hero-info-option .store_selection_area .CLS_store_selection_done {color:#ef4036;}
					</style>
					<div class="hero-info-option">
						<table width = '100%' cellpadding="0" cellspacing="0" border="0" align="center">
							<col style="width:12%"><col style="width:15%"><col style="width:12%"><col style="width:12%"><col style="width:auto">
							<tbody class = 'hero-info-option-table'>
							</tbody>
						</table>
					</div>


					<div class="hero-info-buttonset">
						<?if( $_pdata->quantity <= 0 || $_pdata->soldout == 'Y' ) {?>
							<a class="btn_buy now" href="javascript:alert('품절된 상품입니다.');">구매하기</a>
							<?if ($_pdata->hotdealyn=='N') {?><a class="cart" href="javascript:alert('품절된 상품입니다.');">장바구니</a><?}?>
							<?
						} else {
							$mem_auth_type	= getAuthType($_ShopInfo->getMemid());
							if($mem_auth_type!='sns') {
								?>
								<a class="btn_buy now" href="javascript:order_check('<?=strlen( $_ShopInfo->getMemid() )?>','N');">구매하기</a>
								<?if($_ShopInfo->getStaffYn() == 'Y' && $_pdata->hotdealyn=='N'){?><a class="comp-buy" href="javascript:order_check('<?=strlen( $_ShopInfo->getMemid() )?>','Y');">임직원구매</a><?}?>
								<?if ($_pdata->hotdealyn=='N') {?><a class="cart" href="javascript:basket_check();">장바구니</a><?}?>
								<?
							} else {
								?>
								<a class="btn_buy now" href="javascript:chkAuthMemLoc('','pc');">구매하기</a>
								<?if ($_pdata->hotdealyn=='N') {?><a class="cart" href="javascript:chkAuthMemLoc('','pc');">장바구니</a><?}?>
								<?
							}
						}
						?>
					</div>
					<div class="hero-info-community">
						<a class="btn-star" href="javascript:void(0);" onclick="$(window).scrollTop($('.goods-detail-review').offset().top-104);"><span class="comp-star star-score"><strong style="width:<?=$review_info['marks_ever_width']?>%;">5점만점에 <?=$review_info['marks_ever_cnt']?>점</strong></span>(<?=number_format($review_info['marks_total_cnt'])?>)</a>
						<a class="btn-posting" href="javascript:void(0);" onclick="$(window).scrollTop($('.goods-detail-posting').offset().top-104)"><strong>관련 포스팅</strong></a>
						<!-- (D) 좋아요를 선택하면 버튼에 class="on" title="선택됨"을 추가합니다. -->
						<?if($like_info->section){ ?>
							<button type="button" class="comp-like btn-like like_p<?=$like_info->productcode?> on" onclick="detailSaveLike('<?=$like_info->productcode?>','on','product','<?=$_ShopInfo->getMemid()?>','<?=$_pdata->brand?>')" id="like_<?=$like_info->productcode ?>" title="선택됨"><span class="like_pcount_<?=$like_info->productcode ?>"><strong>좋아요</strong><?=$like_info->hott_cnt ?></span></button>
						<?}else{ ?>
							<button type="button" class="comp-like btn-like like_p<?=$like_info->productcode?>" onclick="detailSaveLike('<?=$like_info->productcode?>','off','product','<?=$_ShopInfo->getMemid()?>','<?=$_pdata->brand?>')" id="like_<?=$like_info->productcode ?>" title="선택 안됨"><span class="like_pcount_<?=$like_info->productcode ?>"><strong>좋아요</strong><?=$like_info->hott_cnt ?></span></button>
						<?} ?>
						<!-- <button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button> -->
					</div>
					<div class="hero-info-tag">
						<h6>TAG</h6>
						<!-- (D) 선택된 li에 class="on" title="선택됨"을 추가합니다. -->
						<ul>
							<?foreach($arrTag as $key=>$val ){?>
								<li><a href="javascript:void(0);"><?=$val ?></a></li>
							<?} ?>
						</ul>
					</div>
					<div class="hero-info-share">
						<ul>
							<li><a href="javascript:;" id="facebook-link"><img src="../static/img/btn/btn_share_facebook.png" alt="페이스북으로 공유"></a></li>
							<li><a href="javascript:;" id="twitter-link"><img src="../static/img/btn/btn_share_twitter.png" alt="트위터로 공유"></a></li>
							<li><a href="javascript:;" id="band-link"><img src="../static/img/btn/btn_share_blogger.png" alt="밴드로 공유"></a></li>
							<!-- 					<li><a href="javascript:;"><img src="../static/img/btn/btn_share_instagram.png" alt="인스타그램으로 공유"></a></li> -->
							<li><a href="javascript:kakaoStory();" id="kakaostory-link"><img src="../static/img/btn/btn_share_kakaostory.png" alt="카카오스토리로 공유"></a></li>
							<li><a href="javascript:ClipCopy('<?=$link_url ?>');">URL</a></li>
						</ul>
					</div>

				</div>
			</form>
			<!-- // 상품상세 - 상단 - 정보 -->
		</div>
		<!-- 상품상세 - 상단 -->
		<?php
		if( $related_html ) {
			?>
			<!-- 상품상세 - 관련상품 -->
			<section class="goods-detail-related">
				<h3>관련 상품<span class="plus"></span></h3>
				<?php
				foreach( $related_html as $key=>$related ){
					echo $related;
				} // related foreach
				?>
			</section>
			<!-- // 상품상세 - 관련상품 -->
			<?
		}
		?>
		<!-- 상품상세 - 상품소개 -->
		<article class="goods-detail-intro">
			<?php
			// ================================================================================
			// PRODUCT INFO
			// ================================================================================

			$_pdata_content = stripslashes($_pdata->content);
			if( strlen($detail_filter) > 0 ) {
				$_pdata_content = preg_replace($filterpattern,$filterreplace,$_pdata_content);
			}

			// <br>태그 제거
			$arrList = array("/<br\/>/", "/<br>/");
			$_pdata_content_tmp = trim(preg_replace($arrList, "", $_pdata_content));

			if ( empty($_pdata_content_tmp) ) {
				echo "<ul class=\"detail-thumb\">";
				foreach ( $arrMultiImg as $key => $val ) {
					echo "<li><img src=\"{$val}\" alt=\"\"></li>";
				}
				echo "</ul>";
			} else {
				if ( strpos($_pdata_content,"table>")!=false || strpos($_pdata_content,"TABLE>")!=false)
					echo "<pre>".$_pdata_content."</pre>";
				else if(strpos($_pdata_content,"</")!=false)
					echo nl2br($_pdata_content);
				else if(strpos($_pdata_content,"img")!=false || strpos($_pdata_content,"IMG")!=false)
					echo nl2br($_pdata_content);
				else
					echo str_replace(" ","&nbsp;",nl2br($_pdata_content));
			}
			?>
		</article>
		<!-- // 상품상세 - 상품소개 -->

		<?php
		// ================================================================================
		// SIZE INFO
		// ================================================================================
		if( strlen( trim( preg_replace( array("/<br\/>/", "/<br>/"), "", $_pdata->pr_sizecon ) ) ) > 0 ) {
			$_pdata_sizecon = stripslashes($_pdata->pr_sizecon);
		} else {
			$_pdata_sizecon = "";
		}
		?>
		<!-- 상품상세 - 사이즈정보 -->
		<section class="goods-detail-size<?=$_pdata_sizecon==''?' hide':''?>">
			<h3>사이즈 정보</h3>
			<?
			if( strlen($detail_filter) > 0 ) {
				$_pdata_sizecon = preg_replace($filterpattern,$filterreplace,$_pdata_sizecon);
			}

			// <br>태그 제거
			$arrList_sizecon = array("/<br\/>/", "/<br>/");
			$_pdata_sizecon_tmp = trim(preg_replace($arrList_sizecon, "", $_pdata_sizecon));

			if ( empty($_pdata_sizecon_tmp) ) {
				echo "<ul class=\"detail-thumb\">";
				foreach ( $arrMultiImg as $key => $val ) {
					echo "<li><img src=\"{$val}\" alt=\"\"></li>";
				}
				echo "</ul>";
			} else {
				if ( strpos($_pdata_sizecon,"table>")!=false || strpos($_pdata_sizecon,"TABLE>")!=false)
					echo "<pre>".$_pdata_sizecon."</pre>";
				else if(strpos($_pdata_sizecon,"</")!=false)
					echo nl2br($_pdata_sizecon);
				else if(strpos($_pdata_sizecon,"img")!=false || strpos($_pdata_sizecon,"IMG")!=false)
					echo nl2br($_pdata_sizecon);
				else
					echo str_replace(" ","&nbsp;",nl2br($_pdata_sizecon));
			}
			?>
		</section>
		<!-- // 상품상세 - 사이즈정보 -->

		<!-- 상품상세 - 관련포스팅 -->
		<section class="goods-detail-posting">
			<h3>관련 포스팅</h3>
			<div class="posting-list">
				<!-- (D) 좋아요를 선택하면 버튼에 class="on" title="선택됨"을 추가합니다. -->

			</div>
		</section>
		<!-- // 상품상세 - 관련포스팅 -->

		<!-- 상품상세 - 리뷰 -->
		<section class="goods-detail-review">
			<? include($Dir.FrontDir."prreview_tem001.php"); ?>
		</section>
		<!-- // 상품상세 - 리뷰 -->

		<!-- 상품상세 - Q&A -->
		<section class="goods-detail-qa">
			<? include($Dir.FrontDir."prqna_tem001.php"); ?>
		</section>
		<!-- // 상품상세 - Q&A -->

		<!-- 상품상세 - 배송/반품 -->
		<section class="goods-detail-return">
			<?=$deli_info?>
		</section>
		<!-- // 상품상세 - 배송/반품 -->
	</main>
	<!-- goods #container -->
</div><!-- //#contents -->
