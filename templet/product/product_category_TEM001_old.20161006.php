<?
if (strpos($_SERVER["REQUEST_URI"],'brand_detail.php') !== false) {					// BRAND 페이지
	$cate_type_code	= "BD";
} else if (strpos($_SERVER["REQUEST_URI"],'productlist.php') !== false) {			// 카테고리 상품 리스트 페이지
	$cate_type_code	= "PL";
} else if (strpos($_SERVER["REQUEST_URI"],'productsearch.php') !== false) {		// 상품 검색 리스트 페이지
	$cate_type_code	= "PS";
}

?>
<!-- 상품리스트 - 사이드바 -->
<div class="goods-list-sidebar">
	<div class="category">
<?
	if ($cate_type_code == 'BD' || $cate_type_code	== "PS") { // BRAND, 상품 검색 리스트 좌측 메뉴
			if ($cate_type_code == 'BD') {
				$cl_def_href	= $Dir.FrontDir."brand_detail.php?bridx=".$bridx;
			} else if ($cate_type_code	= "PS") {
				$cl_def_href	= $Dir.FrontDir."productsearch.php?search=".$search;
			}
?>
		<h2>
			<?if ($cate_type_code == 'PS') {?>
			CATEGORY
			<?} else {?>
			<a href="<?=$cl_def_href?>">CATEGORY</a>
			<?}?>
			</h2>
		<nav>
			<!-- (D) 선택된 li에 class="on" title="선택됨"을 추가합니다. -->
			<ul class="category-main">
			<?if ($cate_type_code == 'PS') {?>
				<li>
					<a href="<?=$cl_def_href?>">ALL<!--?=$all_t_count!=''?'('.number_format($all_t_count).')':'(0)'?--></a>
				</li>
			<?}?>
			<?
			//전체 카테고리를 배열로 가져온다
			$allCategoryList	= getAllCategoryList();
			foreach ( $allCategoryList as $allCateAKey => $allCateAVal ) {
				$cl_a_code		= $allCateAKey;
				$cl_a_name	= $allCateAVal['name'];
				$cl_a_pcnt		= "";
				//if ($cate_type_code == 'PS')
					//$cl_a_pcnt = $all_c_count[$cl_a_code]!=''?'('.number_format($all_c_count[$cl_a_code]).')':'(0)';
			?>
				<li>
					<a href="<?=$cl_def_href?>&sel_cate_code=<?=$cl_a_code?>"><?=$cl_a_name?><?=$cl_a_pcnt?></a>
					<ul class="category-sub">
			<?
				foreach ( $allCateAVal['code_b'] as $allCateBKey => $allCateBVal ) {
					foreach ( $allCateBVal['code_c'] as $allCateCKey => $allCateCVal ) {
						$cl_c_code		= $allCateAKey.$allCateBKey.$allCateCKey;
						$cl_c_name	= $allCateCVal['name'];
						$cl_c_pcnt		= "";
						if ($cate_type_code == 'PS')
							$cl_c_pcnt = $all_c_count[$cl_c_code]!=''?'('.number_format($all_c_count[$cl_c_code]).')':'(0)';
			?>
						<li<?if($cl_c_code == $sel_cate_code) {?> class="on" title="선택됨"<?}?>><a href="<?=$cl_def_href?>&sel_cate_code=<?=$cl_c_code?>"><?=$cl_c_name?><?=$cl_c_pcnt?></a></li>
			<?
						foreach ( $allCateCVal['code_d'] as $allCateDKey => $allCateDVal ) {
							$cl_d_code		= $allCateAKey.$allCateBKey.$allCateCKey.$allCateDKey;
							$cl_d_name	= $allCateDVal['name'];
							$cl_d_pcnt		= "";
							if ($cate_type_code == 'PS')
								$cl_d_pcnt = $all_c_count[$cl_d_code]!=''?'('.number_format($all_c_count[$cl_d_code]).')':'(0)';
			?>
						<li<?if($cl_d_code == $sel_cate_code) {?> class="on" title="선택됨"<?}?>><a href="<?=$cl_def_href?>&sel_cate_code=<?=$cl_d_code?>"><?=$cl_d_name?><?=$cl_d_pcnt?></a></li>
			<?
						}
					}
				}
			?>
					</ul>
				</li>					
			<?
			}
			?>
			</ul>
		</nav>
<?
	}
?>
<?
	if ($cate_type_code	== "PL") { // 상품 리스트 좌측 메뉴
?>
	<?
		//전체 카테고리를 배열로 가져온다
		$allCategoryList	= getAllCategoryList();
		$allCateAKey	= $code_a;
		$cl_a_code		= $allCateAKey;
		$cl_a_name	= $allCategoryList[$code_a]['name'];
	?>
		<h2><a href="<?=$Dir.FrontDir."productlist.php?code=".$cl_a_code?>"><?=$cl_a_name?></a></h2>
		<nav>
			<ul class="category-main">
			<?
				foreach ( $allCategoryList[$code_a]['code_b'] as $allCateBKey => $allCateBVal ) {
				$cl_b_code		= $allCateAKey.$allCateBKey.'000000';
				$cl_b_name	= $allCateBVal['name'];
			?>
				<li>
					<a href="<?=$Dir.FrontDir?>productlist.php?code=<?=$cl_b_code?>"><?=$cl_b_name?></a>
					<ul class="category-sub">
			<?
					foreach ( $allCateBVal['code_c'] as $allCateCKey => $allCateCVal ) {
						$cl_c_code		= $allCateAKey.$allCateBKey.$allCateCKey.'000';
						$cl_c_name	= $allCateCVal['name'];
			?>
						<li<?if($cl_c_code == $code) {?> class="on" title="선택됨"<?}?>><a href="<?=$Dir.FrontDir?>productlist.php?code=<?=$cl_c_code?>"><?=$cl_c_name?></a></li>
			<?
						foreach ( $allCateCVal['code_d'] as $allCateDKey => $allCateDVal ) {
							$cl_d_code		= $allCateAKey.$allCateBKey.$allCateCKey.$allCateDKey;
							$cl_d_name	= $allCateDVal['name'];
			?>
						<li<?if($cl_d_code == $code) {?> class="on" title="선택됨"<?}?>><a href="<?=$Dir.FrontDir?>productlist.php?code=<?=$cl_d_code?>"><?=$cl_d_name?></a></li>
			<?
						}
					}
			?>			
					</ul>
				</li>
			<?
				}
			?>
			</ul>
		</nav>
<?
	}
?>
	</div>
	<div class="sorting">	
<?
	if (	$cate_type_code	== "PL" || $cate_type_code	== "PS") { // 상품 리스트, 검색 좌측 메뉴
?>
		<section class="sorting-brand on">
			<h6>BRAND</h6>
			<ul>
			<?
				$t_getBrandList	= getAllBrandList();
				foreach( $t_getBrandList as $t_brandKey => $t_brandVal){
			?>
				<li>
					<label><input type="checkbox" class="CLS_brandchk" name="brandchk" id="<?=$t_brandVal->bridx?>" ids="<?=$t_brandVal->bridx?>"><span><?=$t_brandVal->brandname?></span></label>
				</li>
			<?
				}
			?>
			</ul>
			<a class="btn-toggle" href="javascript:void(0);" title="접어놓기"><span>BRAND 정렬</span></a>
		</section>
<?
	}
?>
		<section class="sorting-size on">
			<h6>SIZE</h6>
			<ul>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_220" name="sizechk" ids="220"><span>220</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_225" name="sizechk" ids="225"><span>225</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_230" name="sizechk" ids="230"><span>230</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_235" name="sizechk" ids="235"><span>235</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_240" name="sizechk" ids="240"><span>240</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_245" name="sizechk" ids="245"><span>245</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_250" name="sizechk" ids="250"><span>250</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_255" name="sizechk" ids="255"><span>255</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_260" name="sizechk" ids="260"><span>260</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_265" name="sizechk" ids="265"><span>265</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_270" name="sizechk" ids="270"><span>270</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_275" name="sizechk" ids="275"><span>275</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_280" name="sizechk" ids="280"><span>280</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_285" name="sizechk" ids="285"><span>285</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_290" name="sizechk" ids="290"><span>290</span></label></li>
				<li><label><input type="checkbox" class="CLS_sizechk" id="size_300" name="sizechk" ids="300"><span>300</span></label></li>
			</ul>
			<a class="btn-toggle" href="javascript:void(0);" title="접어놓기"><span>SIZE 정렬</span></a>
		</section>
		<section class="sorting-color on">
			<h6>COLOR</h6>
			<!-- (D) 투명, 흰색 등 색이 밝아 체크 색상이 검은색인 것은 li에 class="light" 을 추가합니다. -->
			<ul>
				<?
				$checked['color'][$color_name] = "checked";
				$arrDbColorCode = dataColor(); 
				?>
				<?foreach($arrDbColorCode as $ck => $cv){?>
				<li id="color_<?=$cv->cno?>" ><label><input type="radio" name="smart_search_color" id="smart_search_color" idx="<?=$cv->cno?>" value="<?=$cv->color_name?>" <?=$checked[color][$cv->color_name]?> hidden>
					<span><img src="../static/img/test/<?=$cv->color_img?>" alt="<?=$cv->color_name?>" title="<?=$cv->color_name?>"></span></label>
				</li>
				<?}?>
			</ul>
			<a class="btn-toggle" href="javascript:;" title="접어놓기"><span>COLOR 정렬</span></a>
		</section>
		<button class="btn-submit SelCbscSearchBtn" type="button"><span>선택조건 검색</span></button>
	</div>
</div>
<!-- // 상품리스트 - 사이드바 -->