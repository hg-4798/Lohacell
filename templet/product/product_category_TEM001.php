<?

if (strpos($_SERVER["REQUEST_URI"],'brand_detail.php') !== false) {					// BRAND 페이지
	$cate_type_code	= "BD";
} else if (strpos($_SERVER["REQUEST_URI"],'productlist.php') !== false) {			// 카테고리 상품 리스트 페이지
	$cate_type_code	= "PL";
} else if (strpos($_SERVER["REQUEST_URI"],'productsearch.php') !== false) {		// 상품 검색 리스트 페이지
	$cate_type_code	= "PS";
} else if (strpos($_SERVER["REQUEST_URI"],'show_window.php') !== false) {		// 상품 검색 리스트 페이지
	$cate_type_code	= "SW";
}

// echo "cate_type_code [".$cate_type_code."]";

$parameter = "&size=".$size."&color=".$color_name."&sort=".$sort."&soldout=".$soldout;

// 검색조건 Type 조회 위민트 170126
$code_sql  = "SELECT * FROM (SELECT code_a, code_b, code_c, code_d, code_a||code_b||code_c||code_d as cate_code, code_name, idx , type from tblproductcode) TA WHERE cate_code = '".$code."' ";
$code_res = pmysql_query($code_sql);
$code_row = pmysql_fetch_array($code_res);

$cate_code = $code_row['cate_code'];
$arr_cate = str_split($cate_code, 3);
// echo "type [".$code_row['type']."]";

$sub_cate_sql = "";
$cate_depth = 0;
if($arr_cate[0] == "000")			$cate_depth = 0;
else if($arr_cate[1] == "000")		$cate_depth = 1;
else if($arr_cate[2] == "000")		$cate_depth = 2;
else if($arr_cate[3] == "000")		$cate_depth = 3;
else 								$cate_depth = 4;

if($cate_depth < 4){
	$sub_cate_sql = "SELECT * FROM ( ";
	$sub_cate_sql.= "SELECT code_a,code_b,code_c,code_d, code_a||code_b||code_c||code_d as cate_code, code_name, type, cate_sort, is_hidden FROM tblproductcode ";
	if($brand_idx){
		$sub_cate_sql.= "where code_a||code_b||code_c||code_d in (select cate_code from tblproductbrand_cate where bridx='".$brand_idx."') ";
	}					
	$sub_cate_sql.= ") ta ";
	$sub_cate_sql.= "WHERE 1=1
					AND type = 'LMX'
					AND (cate_code like concat(left('".$cate_code."', ".$cate_depth."*3), '%'))
					AND is_hidden='N'
					ORDER BY cate_sort";
}

$sub_cate_res = pmysql_query($sub_cate_sql);
?>

<!-- 상품리스트 - 사이드바 -->
<div class="filter-wrap">

	<!-- FILETER :: brand start -->	
	<?if(!$brand_idx){?>
	<section class="type-box">
		<input type="checkbox" class="slide_1" name="slide_1_1" id="slide_brand" >
		<label for="slide_brand">브랜드</label>
		<div class="filter-wapper">
			<ul class="filter-slide filter-checkbox">
				<?
				if ($cate_type_code	== "PL" || $cate_type_code	== "PS" || $cate_type_code	== "SW") { // 상품 리스트, 검색 좌측 메뉴
					$t_getBrandList	= getAllBrandList();
					foreach( $t_getBrandList as $t_brandKey => $t_brandVal){
				?>
						<li>
							<div class="checkbox">
								<input type="checkbox" onclick="filter_search()" class="CLS_brandchk" name="brand" value="<?=$t_brandVal->bridx?>" id="<?=$t_brandVal->bridx?>" ids="<?=$t_brandVal->bridx?>">
								<label for="<?=$t_brandVal->bridx?>"><?=$t_brandVal->brandname?></label>
							</div>
						</li>
				<?
					}
				}
				?>
			</ul>
		</div>
	</section>
	<?}?>
	<!--// FILETER :: brand end -->	

	
	
	<!-- FILETER :: color start -->			
	<section class="type-box price">
		<input type="checkbox" class="slide_1" name="slide_1_2" id="slide_color" >
		<label for="slide_color">색상</label>
		<div class="filter-wapper">
			<div class="filter-slide filter-color">
				<?
				$arrDbColorCode = dataColor();
				foreach($arrDbColorCode as $ck => $cv){
				?>
				<label class="<?php if($cv->color_code == '#FFFFFF' || $cv->color_code == '#TRANSP' || strpos($cv->color_code, "#f")!==false || strpos($cv->color_code, "#F")!==false){ ?>with-border<?php }?>" style="background-color:<?=$cv->color_code?>;border:1px solid #a1a1a1" for="smart_search_color_<?=$cv->color_name?>">
					<input onclick="ChangeSort()" type="checkbox" name="color" id="smart_search_color_<?=$cv->color_name?>" ids="<?=$cv->color_name?>" value="<?=$cv->color_name?>"><?=$cv->color_code?>
					<span></span>
				</label>
				<?php }?>
			</div>
		</div>
	</section>			
	<!--// FILETER :: color end -->

	<!-- FILETER :: size start -->	
	<section class="type-box size">
		<input type="checkbox" class="slide_1" name="slide_1_3" id="slide_size" >
		<label for="slide_size">사이즈</label>
		<div class="filter-wapper">
			<div class="filter-slide filter-size">
				<?php
				$arrSizeCode = array("XS", "S", "M", "L", "XL", "XXL");
				foreach($arrSizeCode as $ck => $cv){
				?>
				<div class="size-check">
					<input type="checkbox" name="size" id="size_<?=$cv?>" ids="<?=$cv?>" onclick="ChangeSort()" value="<?=$cv?>">
					<label for="size_<?=$cv?>"><?=$cv?></label>
				</div>
				<?php }?>
			</div>
		</div>
	</section>
	<!--// FILETER :: size end -->				
			
	<!-- FILETER :: price start -->			
	<section class="type-box">
		<input type="checkbox" class="slide_1" name="slide_1_2" id="slide_price" >
		<label for="slide_price">가격</label>
		<div class="filter-wapper">
			<ul class="filter-slide filter-price">
				<li>
					<div class="checkbox price-check">
						<input type="checkbox" class="CLS_pricechk" name="price" id="price_1" value="" data-min="0" data-max="100000">
						<label for="price_1">&#8361;100,000 이하</label>
					</div>
				</li>
				<li>
					<div class="checkbox price-check">
						<input type="checkbox" class="CLS_pricechk" name="price" id="price_2" value="" data-min="100000" data-max="300000">
						<label for="price_2">&#8361;100,000 ~ &#8361;300,000</label>
					</div>
				</li>
				<li>
					<div class="checkbox price-check">
						<input type="checkbox" class="CLS_pricechk" name="price" id="price_3" value="" data-min="300000" data-max="500000">
						<label for="price_3">&#8361;300,000 ~ &#8361;500,000</label>
					</div>
				</li>
				<li>
					<div class="checkbox price-check">
						<input type="checkbox" class="CLS_pricechk" name="price" id="price_4" value="" data-min="500000" data-max="1000000">
						<label for="price_4">&#8361;500,000 ~ &#8361;1,000,000</label>
					</div>
				</li>
				<li>
					<div class="checkbox price-check">
						<input type="checkbox" class="CLS_pricechk" name="price" id="price_5" value="" data-min="1000000" data-max="2000000">
						<label for="price_5">&#8361;1,000,000 이상</label>
					</div>
				</li>
			</ul>
		</div>
	</section>			
	<!--// FILETER :: price end -->

	<!-- FILETER :: sort start -->			
	<section class="type-box sort">
		<input type="checkbox" class="slide_1" name="slide_1_4" id="slide_sort" >
		<label for="slide_sort">정렬<span id="selected-sort">신상품순</span></label>
		<div class="filter-wapper">
		<div class="filter-slide filter-sort">
			<div class="sort-check">
				<input type="checkbox" name="price1" class="CLS_sortchk" id="sort_recent" ids="recent"  value="recent" checked>
				<label for="sort_recent">신상품순</label>
			</div>
			<div class="sort-check">
				<input type="checkbox" name="price2" class="CLS_sortchk" id="sort_best" ids="best"  value="best">
				<label for="sort_best">인기순</label>
			</div>
			<div class="sort-check">
				<input type="checkbox" name="price3" class="CLS_sortchk" id="sort_marks" ids="marks"  value="marks">
				<label for="sort_marks">상품평순</label>
			</div>
			<div class="sort-check">
				<input type="checkbox" name="price4" class="CLS_sortchk" id="sort_like" ids="like"  value="like">
				<label for="sort_like">좋아요순</label>
			</div>
			<div class="sort-check">
				<input type="checkbox" name="price5" class="CLS_sortchk" id="sort_price" ids="price"  value="price">
				<label for="sort_price">낮은가격순</label>
			</div>
			<div class="sort-check">
				<input type="checkbox" name="price6" class="CLS_sortchk" id="sort_price_desc" ids="price_desc"  value="price_desc">
				<label for="sort_price_desc">높은가격순</label>
			</div>
		</div>
		</div>
	</section>
	<!--// FILETER :: price end -->

	<button type="reset" id="btn-filter-reset" class="reset"><span><i class="icon-reset"></i> 초기화</button>

</div><!-- //.filter-wrap -->
<input type="hidden" class="" id="price-start" name="price_start">
<input type="hidden" class="" id="price-end" name="price_end">

<!-- // 상품리스트 - 사이드바 -->

<script type="text/javascript">
	//가격 설정

	$(function(){
		var inputStart = document.getElementById('price-start');
		var inputEnd = document.getElementById('price-end');
	
		// filter range 설정 위민트 170201
		var price_start = 0;
		var price_end = 2000000;
		if("<?=$price_start?>")	price_start = "<?=$price_start?>";
		if("<?=$price_end?>")	price_end 	= "<?=$price_end?>";
	
	
		$("[name='block']").val("");
		$("[name='gotopage']").val("");
	
		// filter 금액 조건 변경시 위민트 170201
		$(".CLS_pricechk").click(function(){
			
			if($(this).prop("checked")){
				$(".CLS_pricechk").prop("checked", false);
				$(this).prop("checked", true);
				$("#price-start").val($(this).data("min"));
				$("#price-end").val($(this).data("max"));
			} else {
				$(".CLS_pricechk").prop("checked", false);
				$("#price-start").val("0");
				$("#price-end").val("2000000");
			}
			
			fnSubmitProductList();
		});

		$(".slide_1").click(function(){
			if($(this).prop("checked")){
				$(".slide_1").prop("checked", false);
				
				$(this).prop("checked", true);
			}

		});

		$(".CLS_sortchk").click(function(){
			var _form = $(".formProdList");
			if($(this).prop("checked")){
				$(".CLS_sortchk").prop("checked", false);
				$(this).prop("checked", true);
				$("[name='sort']", _form).val($(this).val());
			} else {
				$(".CLS_sortchk").prop("checked", false);
				$("#sort_recent").prop("checked", true);
				$("[name='sort']", _form).val("recent");
			}
			$("[name='block']", _form).val("");
			$("[name='gotopage']", _form).val("");
			$("#selected-sort").text($("label[for=sort_"+$("[name='sort']", _form).val()+"]").text());

			fnSubmitProductList();
		});
	
		
	
	});
	$(document).ready(function(){
		//fnLoadProductList();
	
		// 필터 검색조건 초기화 위민트 170131
		$("#btn-filter-reset").click(function(){
			var arr = $("input[type='checkbox']", ".filter-wrap");
			$.each(arr, function(){
				$(this).prop("checked", false);
			});
			$("#price-start").val("0");
			$("#price-end").val("2000000");
			$("#sort_recent").prop("checked", true);
			var _form = $(".formProdList");
			$("[name='sort']", _form).val("recent");
			$("#selected-sort").text($("label[for=sort_"+$("[name='sort']", _form).val()+"]").text());
			fnSubmitProductList();
		});
	
	});
	
	// 화면 로딩시 검색조건 check 위민트 170131
	function fnLoadProductList(){
// 		console.log("fnLoadProductList.........");
		var _form = $(".formProdList");
// 		var _form = $("#formSearch");
		var brandCode = [];
		var sizeCode = [];
		var colorCode = [];
		var cateCode = [];

		var brand = $("#brand", _form).val();
		if(brand){
			var strBrand = brand.split(",");
			$.each( strBrand, function( index, val ){
				$("#"+val).attr("checked", true);
			});
		}
		
		var cateChk = $("#cateChk", _form).val();
		if(cateChk){
			var strCate = cateChk.split(",");
			$.each( strCate, function( index, val ){
				$("#cate_"+val).attr("checked", true);
			});
		}
		var size = $("#size", _form).val();
		if(size){
			var strSize = size.split(",");
			$.each( strSize, function( index, val ){
				$("#size_"+val).attr("checked", true);
			});
		}

		var color = $("#color", _form).val();
		if(color){
			var strColor = color.split(",");
			$.each( strColor, function( index, val ){
				$("#smart_search_color_"+val).attr("checked", true);
				if(val == "white" || val == "transparent"){
					$("#smart_search_color_"+val).parent().parent().addClass("light")
				}
			});
		}
	
		var sort = $("[name='sort']", _form).val();
		$("#sort_"+sort).prop("checked", true);

		var view_type = $("[name='view_type']", _form).val();
		if(!view_type)	view_type = "type-quarter";
		$("#"+view_type).addClass("active").trigger("click");
		
	}

	function filter_search(){
		$("[name='block']").val("");
		$("[name='gotopage']").val("");

		fnSubmitProductList();
	};
	
	// 상품 목록 검색 위민트 170131
	function fnSubmitProductList(){
		var _form = $(".formProdList");
		var brandCode = [];

		if(brandCode != ""){
			//배열에 code가 있는 경우 삭제
			var codeSize = brandCode.length;
			brandCode.splice(0,codeSize);
		}			
		$("input[name=brand]:checked", ".filter-wrap").each(function() {
			brandCode.push($(this).attr("ids"));
		});
		$("input[name=brand]", _form).val(brandCode);
		
		var cateCode = [];
		if(cateCode != ""){
			var codeSize = cateCode.length;
			cateCode.splice(0,codeSize);
		}			
		$("input[name=cateChk]:checked", ".filter-wrap").each(function() {
			cateCode.push($(this).attr("ids"));
		});
		$("input[name=cateChk]", _form).val(cateCode);
		
		var sizeCode = [];
		if(sizeCode != ""){
			var codeSize = sizeCode.length;
			sizeCode.splice(0,codeSize);
		}			
		$("input[name=size]:checked", ".filter-wrap").each(function() {
			sizeCode.push($(this).attr("ids"));
		});
		$("input[name=size]", _form).val(sizeCode);
		
		var colorCode = [];
		if(colorCode != ""){
			var codeSize = colorCode.length;
			colorCode.splice(0,codeSize);
		}			
		$("input[name=color]:checked").each(function() {
			colorCode.push($(this).attr("ids"));
		});
		$("input[name=color]", _form).val(colorCode);

		var price_start = $("#price-start").val();
		var price_end = $("#price-end").val();
		
		_form.find("[name='price_start']").val(price_start);
		_form.find("[name='price_end']").val(price_end);

		var view_type = ""; 
		if($("#type-half").hasClass("active")){
			view_type = "type-half";
		} else if($("#type-quarter").hasClass("active")){
			view_type = "type-quarter";
		}
		$("input[name=view_type]", _form).val(view_type);
		list_ajax();
		$(".slide_1").prop("checked", false);
		//_form.submit();
	}
</script>


