<div id="contents">

	<div class="search-form">
		<p class="fz-14"><strong class="point-color">'<?=$searchTitle?>'</strong>의 검색결과 <strong class="point-color top_total">총 0개</strong>입니다.</p>
		<form name="formSearch" id="formSearch" method="POST" action="productsearch.php" class="mt-20">
			<input type=hidden 		name=addwhere 							value = "<?=$strAddQuery?>">
			
			<legend>상품검색하기</legend>
			<div class="checkbox va-m">
				<input type="checkbox" id="re-search" name="reSearch" value="1" <?=$checked['reSearch']?> class="checkbox-def">
				<label for="re-search">결과 내 재검색</label>
			</div>
			<input type="text" class="w350 ml-15" id="sm_search" name="sm_search" title="검색어 입력자리" placeholder="" value="<?=$searchTitle?>">
			<button type="button" class="btn-point" onclick="javascript:GoSearch()"><span>검색</span></button>
		</form>
	</div>
	
	<div class="goodsList-page">
		<style>
			/*-----상품 썸네일 관련 시작 (thumb-img-new는 모델 컷)-----*/
			.goods-item .thumb-img{height: 450px;padding-bottom: 0%;}
			.goods-item .thumb-img-model{top:0px;}
			.goods-item .thumb-img img{width:280px;height:280px;top:50%;left:50%;margin-top:-120px;margin-left:-140px;}
			.goods-item .thumb-img-model img{width:400px;height:400px;top:50%;left:50%;margin-top:-190px;margin-left:-200px;}
			.goods-list-wrap{float:left;width:100%;}
			.goods-list.four li{width:25%;padding:0px 1%;}
			/*-----상품 썸네일 관련 끝-----*/

			.goods-list-wrap .goods-sort{margin-top:0px;}/*원본 margin-top:-9*/

			/*-----상단 필터 관련-----*/
			input, textarea, select, button {text-rendering: auto;color: initial;letter-spacing: normal;word-spacing: normal;text-transform: none;text-indent: 0px;text-shadow: none;display: inline-block;text-align: start;margin: 0em;}

			.filter-wrap{position:absolute;float:left;width:auto;z-index:9;}/*필터 상단으로*/
			.type-box{float:left;width:auto;padding:15px 0px!important;margin:0px !important;text-align:left;}
			.type-box > label{cursor:pointer;padding-right:10px;}
			.type-box > label:after{position:absolute;display: inline-block;margin-top:4px;margin-left:10px;width:7px;height:4px;content:"";background:url(../static/img/icon/icon_menu_down.png) no-repeat center center;box-sizing: border-box;}
			.type-box .filter-wapper{overflow-y:hidden;max-height:0;padding:0px;margin-top:13px;-webkit-transition:max-height 0.5s ease;-moz-transition:max-height 0.5s ease;-o-transition:max-height 0.5s ease;transition:max-height 0.5s ease;}
			.filter-slide{float:left;border:1px solid #ebebeb;margin:0px;padding:19px 15px;background-color:#fff;}

			.slide_1 {position:absolute;visibility: hidden;}
			.slide_1:checked ~ .filter-wapper {max-height:999px;}
			.checkbox input[type="checkbox"] + label:before {display: inline-block;content: "";margin: -1px 6px 0 0;width: 13px;height: 13px;background: #fff;border: 1px solid #707070;vertical-align: middle;box-sizing: border-box;}
			.checkbox input[type="checkbox"] + label{font-size:11px;}


			.filter-checkbox{width:133px;}
			.filter-color{width:138px;padding:10px 5px;padding-bottom:20px;}
			.filter-size{width:139px;padding:10px 8px;padding-bottom:15px;}
			.filter-price{width:138px;}
			.filter-sort{width:80px;}


			.filter-color label {position: relative;display: inline-block;margin: 10px 0 0 10px;width: 22px;height: 22px;box-sizing: border-box;cursor: pointer;border: 1px solid #e3e3e3;font-size: 100%;font: inherit;vertical-align: baseline;letter-spacing: 0;}
			.filter-color input[type="checkbox"] {visibility: hidden;padding: 0;margin: 0;position: absolute;top: 0;left: 0;width: 1px;height: 1px;}


			.size-check {display: inline-block;position: relative;margin: 7px 0 0 7px;font-size: 0;}
			.size-check input[type="checkbox"] {visibility: hidden;position: absolute;margin: 0;width: 1px;height: 1px;border: none;}
			.size-check input[type="checkbox"] + label {display: inline-block;padding: 0 10px;width: 37px;font-size:10px;height: 21px;background: #fff;border: 1px solid #ccc;text-align: center;line-height: 19px;box-sizing: border-box;cursor: pointer;}

			.price-check, .sort-check{padding:8px 0px;}

			#selected-sort{color:#a3a3a3;margin-left:13px;}
			.sort-check input[type="checkbox"] {visibility: hidden;position: absolute;margin: 0;width: 1px;height: 1px;border: none;}
			.sort-check input[type="checkbox"] + label {background: #fff;cursor: pointer;}


			.total-ea-new{float:right;padding:15px 0px;}

			#btn-filter-reset{padding:15px 0px 10px 0px !important;margin-left:230px;}
			#btn-filter-reset span{border-bottom: 1px solid #fff;}
			.t-line{position:absolute;width:100%;height:1px;background-color:#e3e3e3;left:0px;}
		</style>
		<div class='t-line'></div>
		<article class="clear">
			<div class="goods-list-wrap">
				<div class="goods-sort clear">
					<!-- LNB -->
					<?php include($Dir.TempletDir."product/product_category_TEM001.php");?>
					<!-- //LNB -->
					<!--<div class="total-ea"><strong><?=number_format( $total_cnt )?></strong> items</div>-->
					
					<!--div class="type">
						<button type="button" id="type-half" onclick="javascript:list_cut('two')"><span>2개씩 보기</span></button>
						<button type="button" id="type-quarter" onclick="javascript:list_cut('four')" class="active"><span>4개씩 보기</span></button>
					</div>
					<div class="view-ea ">
						<label>View</label>
						<?foreach ($prod_view_code as $key => $val){ ?>
						<button class="btn-line <?if($listnum==$key){?>on<?php }?>" type="button" onclick="ChangeProdView('<?=$key ?>');"><span><?=$key ?></span></button>
						<?} ?>
					</div-->
					<div class="total-ea-new"><strong>0</strong> 전체</div>
				</div><!-- //.goods-sort -->
				<div class="goods-list-ajax">
					
				</div>
			</div><!-- //.goods-list-wrap -->
		</article>

	</div>
</div><!-- //#contents -->

<?php
include_once($Dir."front/productdetail_layer.php");
?>