<?

// ���� �ۼ� ���� ����Ʈ ��ȸ
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

<div class="layer-dimm-wrap pop_stock_detail"> <!-- .layer-class �̺κп� Ŭ���� �߰��Ͽ� ����մϴ�. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner">
		<h3 class="layer-title">���������ȸ</h3>
		<button type="button" class="btn-close">â �ݱ� ��ư</button>
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

			<p class="txt-size CLS_stock_detail_result_text">����� ������ �ּ���.<!--�����Ͻ� ������ <em>220</em> mm�� ��� ������ ������ <em>20��</em> �˻��Ǿ����ϴ�.--></p>

			<div class="form-box">
				<div class="my-comp-select" style="width:150px;">
					<!--class="required_value" ������ qna ��Ͻ� �������� �˷�â ���� Ŭ���� ���� 2016-09-25-->
					<!--<select name="area_code" class="required_value" label="����" onChange="storeSearchChkForm('area');">-->
					<select name="area_code" onChange="storeSearchChkForm('area');">
						<option value="">��ü</option>
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
					<input type="text" name="searchVal" id="searchVal" title="�˻��� �Է��ڸ�">
					<button type="submit" onclick="storeSearchChkForm('all');" >�˻�</button>
				</fieldset>
			</div>
			</form>

			<div class="map-box CLS_stock_store_list hide">
				<div class="input-area">
					<ol id="stock_store_result">
						<!--li>
							<a href="#">
								<span class="on">1</span> <!-- // [D]���� ���ý� class="on" �߰� --
								<p><strong class="type_txt3">��Ƽ �� 1ȣ��</strong></p>
								<p>���� Ư���� �߱� �� 111-1111 ege ���� 4�� ��-201ȣ</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>2</span>
								<p><strong class="type_txt3">��Ƽ �� 1ȣ��</strong></p>
								<p>���� Ư���� �߱� �� 111-1111 ege ���� 4��</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>3</span>
								<p><strong class="type_txt3">��Ƽ �� 1ȣ��</strong></p>
								<p>���� Ư���� �߱� �� 111-1111 ege ���� 4��</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>4</span>
								<p><strong class="type_txt3">��Ƽ �� 1ȣ��</strong></p>
								<p>���� Ư���� �߱� �� 111-1111 ege ���� 4��</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li>
						<li>
							<a href="#">
								<span>5</span>
								<p><strong class="type_txt3">��Ƽ �� 1ȣ��</strong></p>
								<p>���� Ư���� �߱� �� 111-1111 ege ���� 4��</p>
								<p class="type_txt1">02-1111-1111</p>
							</a>
						</li -->
					</ol>
				</div>
				<div class="map-area">
					<!-- <img src="../static/img/test/@test_map_img01.jpg" alt="��������"> -->

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

					// ���� ������ (�̾��ٰ� �ٽ� ���� ���������� ����)
					var icon_store = new google.maps.MarkerImage('./images/maps_icon_pin.png', new google.maps.Size(size_x, size_y), new google.maps.Point(0,0), new google.maps.Point(icon_x,icon_y), new google.maps.Size(size_x, size_y));

					// ���� ������
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
												'<dd style="padding-bottom:5px;">�����ּ� : '+localmarker.addr+'</dd>'+
												'<dd>��ȭ��ȣ : <a href="tel:'+localmarker.phone+'" target="_self" style="font-weight:bold">'+localmarker.phone+'</a></dd>'+
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
						var results = document.getElementById('stock_store_result');

						var addr_arr	= beach[5].split(" / ");

						var li  = document.createElement('li');
						var contentString = '<li>';
						contentString += '	<a href="javascript:mapSelectStore(\''+i+'\', \''+beach[1]+'\', \''+beach[2]+'\', \''+beach[12]+'\', \''+beach[0]+'\', \''+beach[5]+'\');" onClick="javascript:$(\'.CLS_stock_store_num\').removeClass(\'on\');$(this).find(\'.CLS_stock_store_num\').addClass(\'on\');">';
						contentString += '		<span class="CLS_stock_store_num';
						if (beach[11] == '1') contentString += ' on';
						contentString += '">' + beach[11] + '</span>';
						contentString += '		<p><strong class="type_txt3">' + beach[0] + '</strong> [��� : '+beach[13]+']</p>';
						contentString += '		<p>' + beach[5] + '</p>';
						contentString += '		<p class="type_txt1">' + beach[4] + '</p>';
						contentString += '	</a>';
						contentString += '</li>';

						li.innerHTML = contentString;
						results.appendChild(li);
						if (beach[11] == '1') mapSelectStore(i, beach[1], beach[2], beach[12], beach[0], beach[5]);
					}

					function mapSelectStore(i, x, y, storecode, storename, storeaddr) {
						markers[i].setIcon(icon_store_bold);

						google.maps.event.trigger(markers[i], 'click');
						//$('body, html').animate({scrollTop:0}, 100);
						myLatLng2 = new google.maps.LatLng(x, y);
						map.setCenter(myLatLng2);
						map.setZoom(16);

						$(".CLS_select_storecode").val(storecode);
						$(".CLS_select_storename").val(storename);
						$(".CLS_select_storeaddr").val(storeaddr);
					}

					//������� ��ȸ
					function storeSearchChkForm(type) {
						if (type == 'size') $("#frm_storesearch").find("select[name=area_code]").val('');
						if (type == 'size' || type == 'area') $("#frm_storesearch").find("input[name=searchVal]").val('');
						var prodcd			= $("#frm_storesearch").find("input[name=stock_prodcd]").val();
						var colorcd			= $("#frm_storesearch").find("input[name=stock_colorcd]").val();
						var size				= $("#frm_storesearch").find("input:radio[name=sizechk]:checked").val();
						var area_code	= $("#frm_storesearch").find("select[name=area_code]").val();
						var search			= $("#frm_storesearch").find("input[name=searchVal]").val();

						// ������ ���ý� ��¥ ����
						if (type == 'size'){
							if(global_delivery_type == '1'){
								$(".required_reservation_date").load("../front/ajax_get.reserve.date.php?mode=date&delivery_type=1");
							}else if(global_delivery_type == '2'){
								//$(".required_reservation_date").load("../front/ajax_get.reserve.date.php?mode=date&delivery_type=2");
							}
							$(".CLS_stock_detail_result_text").html("�����Ͻ� ������ - mm�� ��� ������ ������ <em>-��</em> �˻��Ǿ����ϴ�.");
						}

						if (size =='') {
							alert("����� ������ �ּ���.");
							if (type == 'area') document.frm_storesearch.area_code.value = '';
						} else {
							if (type == 'all' && search =='') {
								alert("�˻�� �Է��� �ּ���.");
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
										$(".CLS_stock_detail_result_text").html("�˻��� ������ �����ϴ�.");
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

										$(".CLS_stock_detail_result_text").html("�����Ͻ� ������ <em>"+size+"</em> mm�� ��� ������ ������ <em>"+data_num+"��</em> �˻��Ǿ����ϴ�.");
										$(".CLS_stock_store_list").show();
									}
								});
							}
						}
					}

					function resetStockDetail() {
						$(".CLS_stock_detail_result_text").html("����� ������ �ּ���.");
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
			<input type = 'hidden' class = 'CLS_select_storename'>
			<input type = 'hidden' class = 'CLS_select_storeaddr'>
			<div class="form-box CLS_reservation_date" style = 'display:none;'>
				<div class="form-box">
					<div class="my-comp-select" style="width:150px;">
						<select class="required_reservation_date" label="��¥">
							<option value="">- ������ ���� -</option>
						</select>
					</div>
					<fieldset class="search_form">
						<button type="button" onclick="storeSelectData();" style = 'width:100px;'>�����Ⱦ� ����</button>
					</fieldset>

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
							<input type="text" name = 'post' id = 'post' class="short" title="�����ȣ ù��° �Է��ڸ�" readonly>
							<a href="javascript:openDaumPostcode();" class="btn-type1 ml-5">�ּ�ã��</a>

						</div>
						<div class="mt-5">
							<input type="text" name = 'raddr1' id = 'raddr1' class = 'long' title="�����ȣ ���ÿ� ���� �ּ� �ڵ��Է� �ڸ�" readonly>
							<input type="text" name = 'raddr2' id = 'raddr2' class = 'long' title="�ڵ��Է��ּ� �� �� �ּ� �Է��ڸ�">
						</div>
					</div>
					<fieldset class="search_form">
						<button type="button" onclick="storeSelectData();" style = 'width:100px;height:50px;'>���ϼ��� ����</button>
					</fieldset>

				</div>
			</div>



			<div class="list_text">
				<h3>���ǻ���</h3>
				<ul>
					<li>����Ƽ ���������ȸ�� ��ǰ�� ���庰 ��������� �����Ͽ� ������ ��ǰ ���Ÿ� �����մϴ�.</li>
					<li>����ǰ Ư���� ���� ��� �ǽð����� �����Ǿ� ���� ǥ�õǾ ǰ���Ǵ� ��찡 ������ �湮 ��, �� ���忡 ���� ���Ǹ� ��Ź �帳�ϴ�.</li>
					<li>��������࿡ ���� ���庰 ������ ������ �� �ֽ��ϴ�.</li>
				</ul>
			</div>

		</div> <!-- //.layer-content-->
	</div>
</div>

<div class="layer-dimm-wrap pop-review-detail"> <!-- .layer-class �̺κп� Ŭ���� �߰��Ͽ� ����մϴ�. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner w800">
		<h3 class="layer-title">HOT<span class="type_txt1">-T</span> ��ǰ���� <span id="submit_type">�ۼ�</span></h3>
		<button type="button" class="btn-close">â �ݱ� ��ư</button>
		<div class="layer-content">

            <form name='reviewForm' id='reviewForm' method='POST' action='' >
			<table class="th_left">
				<caption>��ǰ���� �ۼ�/�󼼺���</caption>
				<colgroup>
					<col style="width:100px">
					<col style="width:auto">
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">��ǰ</th>
						<td colspan="3" class="goods_info modify_info">
							<a href="javascript:void(0)">
								<img src="../data/shopimages/product/<?=$_pdata->minimage?>" alt="���������� ��ǰ ����� �̹���">
								<ul class="bold">
									<li id="qna-brandname"><?=$_pdata->brand?></li>
									<li id="qna-productname"><?=strip_tags($_pdata->productname)?></li>
								</ul>
							</a>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_writer">������</label></th>
						<td>
							<div class="my-comp-select">
								<select title="" class="selectbox" name="review_vote" id="review_vote">
									<option value="5">�� �� �� �� ��</option>
									<option value="4">�� �� �� �� </option>
									<option value="3">�� �� �� </option>
									<option value="2">�� �� </option>
									<option value="1">��</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_writer">��ǰ ��</label></th>
						<td>
							<section class="wrap_select_rating">
								<div class="select_rating">
									<span>������</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_size" id="review_size-2">
												<label for="review_size-2">�۴�</span>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_size" id="review_size-1">
												<label for="review_size-1" class="none">���� �۴�</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_size" id="review_size0" checked>
												<label for="review_size0">������</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_size" id="review_size1">
												<label for="review_size1" class="none">���� ũ��</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_size" id="review_size2">
												<label for="review_size2">ũ��</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>�ߺ� ����</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_foot_width" id="foot_width-2">
												<label for="foot_width-2">�۴�</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_foot_width" id="foot_width-1">
												<label for="foot_width-1" class="none">���� �۴�</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_foot_width" id="foot_width0" checked>
												<label for="foot_width0">������</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_foot_width" id="foot_width1">
												<label for="foot_width1" class="none">���� ũ��</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_foot_width" id="foot_width2">
												<label for="foot_width2">ũ��</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>����</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_color" id="color-2">
												<label for="color-2">��Ӵ�</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_color" id="color-1">
												<label for="color-1" class="none">���� ��Ӵ�</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_color" id="color0" checked>
												<label for="color0">ȭ��� ����</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_color" id="color1">
												<label for="color1" class="none">���� ���</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_color" id="color2">
												<label for="color2">���</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>ǰ��/������</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_quality" id="quality-2">
												<label for="quality-2">�Ҹ�</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_quality" id="quality-1">
												<label for="quality-1" class="none">���� �Ҹ�</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_quality" id="quality0" checked>
												<label for="quality0">����</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_quality" id="quality1">
												<label for="quality1" class="none">���� ����</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_quality" id="quality2">
												<label for="quality2">����</label>
											</div>
										</li>
									</ul>
								</div>
							</section>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_writer">���� <span class="required">*</span></label></th>
						<td>
							<input type="text" name="inp_writer" id="review_title" title="���� �Է��ڸ�" style="width:100%;">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_content">���� <span class="required">*</span></label></th>
						<td>
							<textarea name="inp_content" id="review_content" cols="30" rows="10" style="width:100%"></textarea>
							<p class="s_txt">�����, ��ǰ����, ���, ��ȯ ���� ���ǻ����� 1:1���� �Ǵ� �����ȭ�� �̿��� �ֽñ� �ٶ��ϴ�</p>
						</td>
					</tr>
					<tr>
						<th scope="row">���� <span class="required">*</span></th>
						<td>
							<!-- <form> -->
								<fieldset>
								<legend>��ǰ �����ۼ��� �մϴ�.</legend>
								<ul class="reg-review">
									<li>
										<div class="add-photo-wrap">
											<div class="add-photo" id="add-photo1">
												<!--<button type="button">����</button>
												<p style="background:url(../static/img/test/@test_review_dum1.jpg) center no-repeat; background-size:contain"></p>-->
												<input type="file" name="up_filename[]" accept="image/*">
                                                <input type="hidden" id="file_exist" name="file_exist" value="N" />
                                                <input type="hidden" name="v_up_filename[]" id="upfile">
											</div>
											<div class="add-photo" id="add-photo2">
												<!--<button type="button">����</button>
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
							<p class="s_txt">�����ϸ� : �ѱ�, ����, ���� / ���� �뷮 : 3M ���� / ���� ���� : GIF, JPG(JPEG)</p>
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
			<p class="ment mt-5"><span class="required">*</span> �ʼ��Է�</p>
			<div class="btn_wrap"><a href="javascript:;" class="btn-type1" id="review_submit" onclick='javascript:ajax_review_insert();'>����</a></div>
		</div>
	</div>
</div>
<!-- // ��ǰ���� ���˾� -->

<!-- ��ǰ ���� dimm layer -->
<div class="layer-dimm-wrap pop-qna-detail">
    <div class="dimm-bg"></div>
    <div class="layer-inner w800">
        <h3 class="layer-title">HOT<span class="type_txt1">-T</span> ��ǰ����</h3>
        <button type="button" class="btn-close">â �ݱ� ��ư</button>
        <div class="layer-content">
			<h4 class="title"></h4>
			<table class="th_left util">
				<caption>��ǰ�� ���� �ñ��� ����</caption>
				<colgroup><col style="width:100px"><col style="auto"></colgroup>
				<tbody>
				<tr>
					<th scope="row"><label for="qna-subject">����</label> <span class="required">*</span></th>
					<td colspan="3"><input type="text" id="qna-subject" class="input-def" title="���� ���� �Է��ڸ�" style="width:100%;"></td>
				</tr>
				<tr>
					<th scope="row"><label for="qna-content">����</label></th>
					<td colspan="3">
						<textarea id="qna-content" cols="30" rows="10" class="textarea-def" style="width:100%;"></textarea>
						<!--<p class="ment mt-5">
						�� ���,��ǰ����,���,��ȯ���� ���ǻ����� �����͸� �̿��� �ֽñ� �ٶ��ϴ�.  ��ǰ�� �ۼ��Ͻø� �亯�� ���� ���մϴ�.</p>-->
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="phone_chk">�޴��� �亯</label><input id="phone_chk" type="checkbox" class="chk_agree checkbox-def ml-5"></th>
					<td>
						<input type="text" class="chk_only_number" id="hp" name="hp" placeholder="������(-) ���� �Է�" title="�޴��� ��ȣ" label="�޴��� �亯" style="width:240px" maxlength="11">
					</td>
					<th><label for="email_chk">�̸��� �亯</label><input id="email_chk" type="checkbox" class="chk_agree checkbox-def ml-5"></th>
					<td>
						<input type="text" id="email" name="email" title="�̸��� ���̵� �Է��ڸ�" label="�̸��� �亯" style="width:240px">
					</td>
				</tr>
				<tr>
					<th scope="row">��������</th>
					<td colspan="3">
						<input type="radio" name="view-type" id="view" value='0' class="radio-def" checked>
						<label for="view">����</label>
						<input type="radio" name="view-type" id="no-view" value='1' class="radio-def">
						<label for="no-view">�����</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="qna-pwd">��й�ȣ</label></th>
					<td colspan="3"><input type="password" id="qna-pwd" class="input-def"><input type=hidden name=oldpass id=oldpass></td>
				</tr>
				</tbody>
			</table>
			<p class="ment mt-5"><span class="required">*</span> �ʼ��Է�</p>
			<div class="btn_wrap">
				<input type='hidden' id='qna-num' value='' >
				<a href="#" class="btn-type1" onclick='javascript:QnAController();'>�����ϱ�</a>
			</div>
		</div>
    </div>
</div>
<style>

</style>

<!-- ������ ī�� dimm layer -->
<div class="layer-dimm-wrap layer-detail-card" >
    <div class="dimm-bg"></div>
    <div class="layer-inner ">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">â �ݱ� ��ư</button>
        <div class="layer-content">
			<div class="card-month-scroll js-scroll">
<?php
foreach( $card_banner[111] as $cardKey=>$cardVal ){
	echo "<img src='".$cardVal['banner_img']."' alt='�������Һ� �ȳ�' >";
}
?>
				<!-- <img src="../static/img/common/card_monthly_benefit.jpg" alt="�������Һ� �ȳ�"> -->
			</div>
        </div>
    </div>
</div>

		<!-- ����������_�󼼺��� �˾� -->
		<div class="layer-dimm-wrap pop-view-detail CLS_instagram"> <!-- .layer-class �̺κп� Ŭ���� �߰��Ͽ� ����մϴ�. -->
			<div class="dimm-bg"></div>
			<div class="layer-inner">
				<button type="button" class="btn-close">â �ݱ� ��ư</button>
				<div class="layer-content">
					<div class="img-area">
						<img src="" alt="" id="instagram_img">
					</div>
					<div class="cont-area">
						<div class="title">
							<h3><span class="pl-10"><!-- <img src="" alt="instagram"> --></span></h3>
							<!--  <button class="comp-like btn-like" title="���� �ȵ�"><span id="like_count"></button> <!-- // [D] ���ƿ並 �����ϸ� ��ư�� class="on" title="���õ�"�� �߰� -->
						</div>
						<div class="cont-view">
							<div class="inner">
								<p id="content"></p>
								<p class="tag" id="instagram_tag">
		<!-- 							#hott #hottest #nike #airjordan #Jordan #shoes #fashion #item #ootd #dailylook #��Ƽ #����Ű #�������� #���� #�Ź� #�м� #������ #���ϸ� #���ϸ��� #���ϸ����� #�Ž�Ÿ�׷� #����Ÿ�׷� #daily #dailyshoes #shoestagram -->
								</p>
							</div>
						</div>
						<div class="goods-detail-related">
							<h3>���� ��ǰ</h3>
							<ul class="related-list">
		<!--
								<li>
									<a href="javascript:;">
										<figure>
											<img src="../static/img/test/@test_instagram_wish01.jpg" alt="���ɻ�ǰ">
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
						<a href="javascript:pagePrev();" class="view-prev">����</a>
						<a href="javascript:pageNext();" class="view-next">����</a>
					</div>-->
				</div>
			</div>
		</div>
		<!-- // ����������_�󼼺��� �˾� -->
<!-- �׺���̼� -->
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
            $classOn = "on";    // ������ ī�װ��� on ó�� ?>
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
<!-- //�׺���̼�-->


<div id="contents">
	<!-- goods #container -->
	<main id="contents">
	<!-- ��ǰ�� - ��� -->
	<div class="goods-detail-hero">
		<form name='prForm' id='prForm' method='POST' action="<?=$Dir.FrontDir?>basket.php" >
		<input type='hidden' name='prcode' id='prcode' value='<?=$_pdata->productcode?>' >
		<input type='hidden' name='pridx' id='pridx' value='<?=$_pdata->pridx?>' >
		<input type="hidden" name="constant_quantity" id="constant_quantity" value="<?=$_pdata->quantity?>" >
		<input type='hidden' name="up_name" id="up_name" value="<?=$_ShopInfo->getmemname()?>" >
		<input type="hidden" id="brand_name" value="<?=$_pdata->brand?>">
		<input type="hidden" id="link-label" value="HOTT �¶��� ����">
		<input type="hidden" id="link-title" value="<?=$_pdata->brand?> <?=$_pdata->productname?>">
		<input type="hidden" id="link-image" value="<?=$_pdata->maximage?>" data-width='200' data-height='300'>
		<input type="hidden" id="link-url" value="<?=$link_url ?>">
		<input type="hidden" id="link-img-path"value="<?=$imgPath ?>">
		<input type="hidden" id="link-code"value="<?=$productcode ?>">
		<input type="hidden" id="link-menu"value="product">

		<!-- ��ǰ�� - ��� - �̹��� -->
		<div class="hero-image">
			<!-- (D) �̹����� background-image:url()�� �����մϴ�. -->
			<ul class="image-list">

<?
    // ��ǰ ����� ���� ���� �̹������� �迭�� �����ؼ� �ѹ��� �׷��ش�.
    $arrMiniThumbList = array();

	# ��ǰ ū �̹���
	if( is_file( $imagepath_product.$_pdata->maximage ) || strpos($_pdata->maximage, "http://") !== false ) {
        $tmp_imgCont = "<li style=\"background-image:url('" . getProductImage($imagepath_product, $_pdata->maximage) . "');\"></li>\n";
        array_push($arrMiniThumbList, $tmp_imgCont);
	}

	if ( $multi_img=="Y" && $yesimage[0] ) {
        $arrMultiImg = array(); // ��ǰ �� ������ ���� ��� �����ϱ� ���� �迭�� ����
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
		<!-- // ��ǰ�� - ��� - �̹��� -->
<?
	if ($_pdata->sex =='M') $_pdata_sex	= "MEN";
	else if ($_pdata->sex =='F') $_pdata_sex	= "WOMEN";
	else if ($_pdata->sex =='U') $_pdata_sex	= "UNISEX";
?>
		<!-- ��ǰ�� - ��� - ���� -->
		<div class="hero-info">
			<h2>
				<div class="brand">[ <?=$_pdata->brand?> ] <span>[ <?=$_pdata_sex?> ]</span> <!-- // [D] 20160913 �����߰� --></div>
				<?=$_pdata->productname?>
				<p class="ko-name"><?=$_pdata->productname_kor?></p> <!-- // [D] 20160913 �ѱ� ��ǰ���߰� -->
			</h2>
			<div class="price-wrap">
				<?if($_pdata->consumerprice != $_pdata->sellprice){ ?>
				<dl class="price">
					<dt>�ǸŰ�</dt>
					<dd><del><?=number_format( $_pdata->consumerprice )?></del></dd>
				</dl>
				<dl class="sale-price">
					<dt>���ð�</dt>
					<dd>
						<strong><?=number_format( $_pdata->sellprice )?></strong>
						<span class="sale"><?=get_price_percent( $_pdata->consumerprice, $_pdata->sellprice )?>%</span>
					</dd>
				</dl>
				<?}else{ ?>
				<dl class="sale-price">
					<dt>���ð�</dt>
					<dd>
						<!-- <strong><?=number_format($val['consumerprice']) ?></strong> -->
                        <strong><?=number_format($_pdata->sellprice) ?></strong>
					</dd>
				</dl>
				<?} ?>
				<?if ($coupon_use['code'] !='' && $coupon_use['btn_yn']=='Y') {?>
				<dl class="coupon">
					<dt>������</dt>
					<dd>
						<strong><?=number_format($coupon_use['price']) ?></strong>
						<a href="javascript:;" class="CLS_coupon_download ml-5" data-coupon='<?=encrypt_md5("COUPON|".$coupon_use['type']."|".$coupon_use['code'],"*ghkddnjsrl*")?>'><img src="../static/img/btn/btn_coupon_download.gif" alt="�����ٿ�ε�"></a>
						<div class="speech-bubble">
							<?=$coupon_use['name']?>
						</div>
					</dd>
				</dl>
				<?}?>
				<?if($_ShopInfo->getStaffYn() == 'Y' && $_pdata->hotdealyn=='N'){?>
				<dl class="staff">
					<dt>��������</dt>
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
					<span class="coupon">������ <em><?=number_format($coupon_use['price']) ?></em></span>
					<span class="sale"><?=$coupon_use['per']?>%</span>
					<span class="coupon"><?=$coupon_use['name']?>&nbsp;<a href="javascript:;" class="CLS_coupon_download" data-coupon='<?=encrypt_md5("COUPON|".$coupon_use['type']."|".$coupon_use['code'],"*ghkddnjsrl*")?>'>[���� �ٿ�ε�]</a></span>
					<?}?>
					<?if($_ShopInfo->getStaffYn() == 'Y'){?>
					<div class="comp-member">�������� <em><?=number_format($staff_use['price']) ?></em></div>
					<span class="sale"><?=$staff_use['per']?>%</span>
					<?}?> // ���� ���� �ҽ�-->
			</div>
			<?$colorProdHtml = getColorProduct($_pdata->productcode, $_pdata->prodcode, "detail" );
				$colorProdText = getColorProductText($_pdata->productcode, $_pdata->prodcode );
			?>
			<div class="hero-info-color with-btn-rolling<?=!$colorProdHtml?' hide':''?>">
				<p><?=$colorProdText ?></p>
				<!-- (D) ���õ� li�� class="on" title="���õ�"�� �߰��մϴ�. -->
				<ul class="CLS_colorProduct">
					<?=$colorProdHtml?>
<!-- 					<li class="on" title="���õ�"><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color1.jpg" alt="Wolf Grey"></a></li> -->
<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color2.jpg" alt="Bright Crimson"></a></li> -->
<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color3.jpg" alt="Gym Red"></a></li> -->
<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color4.jpg" alt="Sail"></a></li> -->
<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color5.jpg" alt="Dark Obsidian"></a></li> -->
<!-- 					<li><a href="javascript:void(0);"><img src="../static/img/test/@goods_detail_info_color6.jpg" alt="Black"></a></li> -->
				</ul>
			</div>






			<!-- ��� ��� ���� -->
			<style>
				.CLS_store_selection_done_layer{
					display:none; position: absolute; background: #FFFFFF; padding: 8px; margin-left: -200px;margin-top: -10px; border: 1px solid #999; 
				}
				.hero-info-delivery-type .sorting-size {margin:10px 0;}
				.hero-info-delivery-type .sorting-size li {float:left; width:110px; height:28px; line-height:28px; border:1px solid #696969; margin-right:5px; text-align:center; color:#000;}
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
if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity > 0  && $_pdata->option_type == '0' ){ // ������ �ɼ�
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
					$priceText = ' ( + '.number_format($oVal['price']).' �� )';
				} else if( $option_depth == 1 && $oVal['price'] < 0 ) {
					$priceText = ' ( - '.number_format($oVal['price']).' �� )';
				}

				if(
					( $option_qty !== null && $option_qty <= 0 ) &&
					( ( $option_depth > 0 && $nameKey != 0 ) || ( $option_depth == 1 && $nameKey == 0 ) ) &&
					$_pdata->quantity < 999999999
				){
					$option_disable = ' disable';
					$option_text = '[ǰ��]';
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
} else if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity <= 0  && $_pdata->option_type == '0' ) { // if $_pdata->option1 ǰ���� �ɼ�
    foreach( $optionNames as $nameKey=>$nameVal ) {
?>
				<div class="comp-select size">
					<select name='op_opt[]' class="my_value CLS_option_value" title="<?=$nameVal?>" data-option-code='' >
						<option value="" data-qty='' data-code='' selected>ǰ��</option>
					</select>
				</div>
<?php
    } // optionNames foreach
}
$tf_arr = explode( '@#', $_pdata->option1_tf );
if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity > 0  && $_pdata->option_type == '1' ){ // ������ �ɼ�
	foreach( $optionNames as $nameKey=>$nameVal ) {
?>
				<div class="comp-select size">
					<select name='alone_option[]' class="my_value" title="<?=$nameVal?><?=($tf_arr[$nameKey] == 'T')?' (�ʼ�)':' (����)'?>" data-option-code='' data-option-qty='' onchange="javascript:option_select( this.value, '<?=$nameKey?>' );">
						<option value="" data-qty='' data-code='' data-tf=''><?=$nameVal?><?=($tf_arr[$nameKey] == 'T')?' (�ʼ�)':' (����)'?></option>
<?php
		foreach( $options[$nameVal] as $oKey=>$oVal ) {
			if( $oVal->option_tf == 'T' ) $tf_text = '*�ʼ�';
			//exdebug( $oVal );
			$option_disable = '';
			$option_text = '';
            $priceText = '';
			if( $oVal->option_price > 0 ){
				$priceText = ' ( + '.number_format($oVal->option_price).' �� )';
			}else if( $oVal->option_price < 0 ) {
				$priceText = ' ( - '.number_format($oVal->option_price).' �� )';
			}
			if(
				( $oVal->option_quantity !== null && $oVal->option_quantity < 0 ) &&
				$_pdata->quantity < 999999999
			){
				$option_disable = ' disable';
				$option_text = '[ǰ��]';
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
						<option value="" data-qty='' data-code='' selected>ǰ��</option>
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
						<input type="text" name ='addoption[]' data-option-code='<?=$addVal?>' data-option-tf='<?=$addOption_tf[$addKey]?>' maxlength='<?=$addOption_maxlen[$addKey]?>' disabled value='ǰ��' >
						<input type='hidden' name='addoption_tf[]' value='<?=$addOption_tf[$addKey]?>' >
						<span class="byte hide">(<strong>0</strong>/<?=$addOption_maxlen[$addKey]?>)</span>
					</div>
<?php
    } // addoption foreach
}
?>



				<!-- 
				<div class="qty">
					<input type="text" name='quantity' id='quantity' title="����" value="<?=($_pdata->quantity <= 0 || $_pdata->soldout == 'Y')?'0':'1'?>" style="width:234px;">
					<button class="btn_add btn-plus" type="button"><span>���� 1 ���ϱ�</span></button>
					<button class="btn_subtract btn-minus" type="button"><span>���� 1 ����</span></button>
				</div>
				<div class="btn_stock btn_stock_detail">
					<a class="btn-type1" href="javascript:;" onClick="javascript:resetStockDetail();">���������ȸ</a>
				</div>
				-->



				<!--div class="comp-select shipping">
					<select title="���/���ɹ��">
						<option value="0">���/���ɹ��</option>
					</select>
				</div-->
			</div>
			<div class="hero-info-buttonset">
<?if( $_pdata->quantity <= 0 || $_pdata->soldout == 'Y' ) {?>
				<a class="btn_buy now" href="javascript:alert('ǰ���� ��ǰ�Դϴ�.');">�����ϱ�</a>
				<?if ($_pdata->hotdealyn=='N') {?><a class="cart" href="javascript:alert('ǰ���� ��ǰ�Դϴ�.');">��ٱ���</a><?}?>
<?
	} else {
		$mem_auth_type	= getAuthType($_ShopInfo->getMemid());
		if($mem_auth_type!='sns') {
?>
				<a class="btn_buy now" href="javascript:order_check('<?=strlen( $_ShopInfo->getMemid() )?>','N');">�����ϱ�</a>
				<?if($_ShopInfo->getStaffYn() == 'Y' && $_pdata->hotdealyn=='N'){?><a class="comp-buy" href="javascript:order_check('<?=strlen( $_ShopInfo->getMemid() )?>','Y');">����������</a><?}?>
				<?if ($_pdata->hotdealyn=='N') {?><a class="cart" href="javascript:basket_check();">��ٱ���</a><?}?>
<?
		} else {
?>
				<a class="btn_buy now" href="javascript:chkAuthMemLoc('');">�����ϱ�</a>
				<?if ($_pdata->hotdealyn=='N') {?><a class="cart" href="javascript:chkAuthMemLoc('');">��ٱ���</a><?}?>
<?
		}
	}
?>
			</div>
			<div class="hero-info-community">
				<a class="btn-star" href="javascript:void(0);" onclick="$(window).scrollTop($('.goods-detail-review').offset().top-104);"><span class="comp-star star-score"><strong style="width:<?=$review_info['marks_ever_width']?>%;">5�������� <?=$review_info['marks_ever_cnt']?>��</strong></span>(<?=number_format($review_info['marks_total_cnt'])?>)</a>
				<a class="btn-posting" href="javascript:void(0);" onclick="$(window).scrollTop($('.goods-detail-posting').offset().top-104)"><strong>���� ������</strong></a>
				<!-- (D) ���ƿ並 �����ϸ� ��ư�� class="on" title="���õ�"�� �߰��մϴ�. -->
				<?if($like_info->section){ ?>
				<button type="button" class="comp-like btn-like like_p<?=$like_info->productcode?> on" onclick="detailSaveLike('<?=$like_info->productcode?>','on','product','<?=$_ShopInfo->getMemid()?>','<?=$_pdata->brand?>')" id="like_<?=$like_info->productcode ?>" title="���õ�"><span class="like_pcount_<?=$like_info->productcode ?>"><strong>���ƿ�</strong><?=$like_info->hott_cnt ?></span></button>
				<?}else{ ?>
				<button type="button" class="comp-like btn-like like_p<?=$like_info->productcode?>" onclick="detailSaveLike('<?=$like_info->productcode?>','off','product','<?=$_ShopInfo->getMemid()?>','<?=$_pdata->brand?>')" id="like_<?=$like_info->productcode ?>" title="���� �ȵ�"><span class="like_pcount_<?=$like_info->productcode ?>"><strong>���ƿ�</strong><?=$like_info->hott_cnt ?></span></button>
				<?} ?>
				<!-- <button class="comp-like btn-like on" title="���õ�"><span><strong>���ƿ�</strong>159</span></button> -->
			</div>
			<div class="hero-info-tag">
				<h6>TAG</h6>
				<!-- (D) ���õ� li�� class="on" title="���õ�"�� �߰��մϴ�. -->
				<ul>
					<?foreach($arrTag as $key=>$val ){?>
					<li><a href="javascript:void(0);"><?=$val ?></a></li>
					<?} ?>
				</ul>
			</div>
			<div class="hero-info-share">
				<ul>
					<li><a href="javascript:;" id="facebook-link"><img src="../static/img/btn/btn_share_facebook.png" alt="���̽������� ����"></a></li>
					<li><a href="javascript:;" id="twitter-link"><img src="../static/img/btn/btn_share_twitter.png" alt="Ʈ���ͷ� ����"></a></li>
					<li><a href="javascript:;" id="band-link"><img src="../static/img/btn/btn_share_blogger.png" alt="���� ����"></a></li>
<!-- 					<li><a href="javascript:;"><img src="../static/img/btn/btn_share_instagram.png" alt="�ν�Ÿ�׷����� ����"></a></li> -->
					<li><a href="javascript:kakaoStory();" id="kakaostory-link"><img src="../static/img/btn/btn_share_kakaostory.png" alt="īī�����丮�� ����"></a></li>
					<li><a href="javascript:ClipCopy('<?=$link_url ?>');">URL</a></li>
				</ul>
			</div>









			<!-- �ɼ� �߰� ���̾� ���̺� -->
			<style>
				.hero-info-option{
					padding-top:30px;
				}
				.hero-info-option div.qty{position:relative; }
				.hero-info-option div.qty input[type="text"]{box-sizing:border-box; margin-bottom:3px; height:35px; border:1px solid #808080; background:transparent; color:#000; font-size:1.4rem;}
				.hero-info-option div.qty button {position:absolute; left:35px; width:39px; height:17px; border:1px solid #808080;}
				.hero-info-option div.qty button span{display:block; overflow:hidden; font-size:0; text-indent:-9999px;}
				.hero-info-option div.qty button.btn_add{top:0; background:url("../static/img/btn/btn_arrow_up.png") no-repeat 50% 50%;}
				.hero-info-option div.qty button.btn_subtract{bottom:3px; background:url("../static/img/btn/btn_arrow_down.png") no-repeat 50% 50%;}

				
				.hero-info-option .btn_option_delete {float:left;bottom:3px; right:0; margin:0px auto; text-align:center; width:100%; }
				.hero-info-option .btn_option_delete a {width:114px; height:35px; line-height:35px; background:#a6a6a6; font-size:1.3rem;}

				.hero-info-option .btn_option_delete_type {float:left;bottom:3px; right:0; margin:0px auto; text-align:center; width:100%; }
				.hero-info-option .btn_option_delete_type a {width:56px; height:35px; line-height:35px; background:#a6a6a6; font-size:1.3rem; }
			</style>
			<div class="hero-info-option">
				<table width = '100%' cellpadding="5" cellspacing="0" border="1" align="center" style="border-collapse:collapse; border:1px gray solid;">
					<col width = '20%'><col width = '10%'><col width = '15%'><col width = '15%'><col width = '20%'>
					<tbody class = 'hero-info-option-table'>
					</tbody>
				</table>
			</div>










		</div>
		</form>
		<!-- // ��ǰ�� - ��� - ���� -->
	</div>
	<!-- ��ǰ�� - ��� -->
<?php
if( $related_html ) {
?>
	<!-- ��ǰ�� - ���û�ǰ -->
	<section class="goods-detail-related">
		<h3>���� ��ǰ<span class="plus"></span></h3>
<?php
	foreach( $related_html as $key=>$related ){
		echo $related;
	} // related foreach
?>
	</section>
	<!-- // ��ǰ�� - ���û�ǰ -->
<?
}
?>
	<!-- ��ǰ�� - ��ǰ�Ұ� -->
	<article class="goods-detail-intro">
<?php
    // ================================================================================
    // PRODUCT INFO
    // ================================================================================

	$_pdata_content = stripslashes($_pdata->content);
	if( strlen($detail_filter) > 0 ) {
		$_pdata_content = preg_replace($filterpattern,$filterreplace,$_pdata_content);
	}

    // <br>�±� ����
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
	<!-- // ��ǰ�� - ��ǰ�Ұ� -->

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
	<!-- ��ǰ�� - ���������� -->
	<section class="goods-detail-size<?=$_pdata_sizecon==''?' hide':''?>">
		<h3>������ ����</h3>
<?
		if( strlen($detail_filter) > 0 ) {
			$_pdata_sizecon = preg_replace($filterpattern,$filterreplace,$_pdata_sizecon);
		}

		// <br>�±� ����
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
	<!-- // ��ǰ�� - ���������� -->

	<!-- ��ǰ�� - ���������� -->
	<section class="goods-detail-posting">
		<h3>���� ������</h3>
		<div class="posting-list">
			<!-- (D) ���ƿ並 �����ϸ� ��ư�� class="on" title="���õ�"�� �߰��մϴ�. -->

		</div>
	</section>
	<!-- // ��ǰ�� - ���������� -->

	<!-- ��ǰ�� - ���� -->
	<section class="goods-detail-review">
	<? include($Dir.FrontDir."prreview_tem001.php"); ?>
	</section>
	<!-- // ��ǰ�� - ���� -->

	<!-- ��ǰ�� - Q&A -->
	<section class="goods-detail-qa">
		<? include($Dir.FrontDir."prqna_tem001.php"); ?>
	</section>
	<!-- // ��ǰ�� - Q&A -->

	<!-- ��ǰ�� - ���/��ǰ -->
	<section class="goods-detail-return">
		<?=$deli_info?>
	</section>
	<!-- // ��ǰ�� - ���/��ǰ -->
    </main>
    <!-- goods #container -->
</div><!-- //#contents -->
