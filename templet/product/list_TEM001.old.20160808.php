<?php
/********************************************************************* 
// 파 일 명		: list_TEM001.php 
// 설     명		: 카테고리 상품 리스트 템플릿
// 상세설명	: 카테고리별 상품을 리스트로 진열 템플릿
// 작 성 자		: hspark
// 수 정 자		: 2016-02-01 / 유동혁
// 
// 
*********************************************************************/ 
?>

<!-- 2016-01-22 리스트 -->
	<div id="contents">
		<div class="containerBody sub-page">
			
			<div class="breadcrumb">
				<ul>
					<li><a href="javascript:;">HOME</a></li>

					<li><a href="<?=$Dir?>/front/productlist.php?code=<?=substr($thisCate[0]->category, 0, 3)?>"><?=$thisCate[0]->code_name?></a></li>
<?php
$thisCateName = '';
$thisCateCnt = count( $thisCate );
if( $thisCateCnt > 1 ){ // 1차 카테고리
	$thisCateName = $thisCate[1]->code_name;
?>
					<li <? if( count( $thisCate ) == 2 ){ echo 'class="on"'; } ?> ><a href="<?=$Dir?>/front/productlist.php?code=<?=$thisCate[1]->category?>"><?=$thisCate[1]->code_name?></a></li>
<?php
}
if( $thisCateCnt > 2 ){ // 2차 카테고리
	$thisCateName = $thisCate[2]->code_name;
?>
					<li <? if( count( $thisCate ) == 3 ){ echo 'class="on"'; } ?> ><a href="<?=$Dir?>/front/productlist.php?code=<?=$thisCate[2]->category?>"><?=$thisCate[2]->code_name?></a></li>
<?php
}
if( $thisCateCnt > 3 ) { // 3차 카테고리
	$thisCateName = $thisCate[3]->code_name;
?>
					<li <? if( count( $thisCate ) == 4 ){ echo 'class="on"'; } ?> ><a href="<?=$Dir?>/front/productlist.php?code=<?=$thisCate[3]->category?>"><?=$thisCate[3]->code_name?></a></li>
<?php
}
?>
				</ul>
			</div><!-- //.breadcrumb -->
			
			<div class="goods-list-wrap">

				<div class="goods-sort-wrap">
					<div class="inner">
						<h3 class="cate-name"><?=$thisCateName?><span class="goods_num">(<?=number_format( $total_cnt )?>)</span></h3>
						<div class="right-sort">
							<ul class="sort-type">
								<li><a <? if( $sort == 'order' ) { echo 'class="on"'; } ?> href="javascript:ChangeSort('order')" >NEW</a></li>
								<li><a <? if( $sort == 'best' ) { echo 'class="on"'; } ?> href="javascript:ChangeSort('best')">BEST</a></li>
								<li><a <? if( $sort == 'rcnt_desc' ) { echo 'class="on"'; } ?> href="javascript:ChangeSort('rcnt_desc')">REVIEW</a></li>
								<li><a <? if( $sort == 'price' ) { echo 'class="on"'; } ?> href="javascript:ChangeSort('price')">LOW PRICE</a></li>
								<li><a <? if( $sort == 'price_desc' ) { echo 'class="on"'; } ?> href="javascript:ChangeSort('price_desc')">HIGH PRICE</a></li>
							</ul>
							<div class="view-ea">
								<button <? if( $listnum == 25 ) { echo 'class="on"'; } ?> type="button" onclick="javascript:ChangeListnum( 25 )">25</button>
								<button <? if( $listnum == 50 ) { echo 'class="on"'; } ?> type="button" onclick="javascript:ChangeListnum( 50 )">50</button>
								<button <? if( $listnum == 75 ) { echo 'class="on"'; } ?> type="button" onclick="javascript:ChangeListnum( 75 )">75</button>
							</div>
						</div>
					</div>
					<!--span class="total-ea">
<?php
if( count( $thisLowCate ) > 0 ){
?>
						<ul>
<?php
	foreach( $thisLowCate as $lowcate_Key=>$lowcate_Val ){
		echo '<li><a href="'.$Dir.FrontDir.'productlist.php?code='.$lowcate_Val->page_code.'">'.$lowcate_Val->code_name.'</a></li>';
	}
?>
						</ul>
<?php
} else {
?>
						<!-- 총 <?=number_format( $total_cnt )?>개의 상품이 있습니다. -->
<?php
} // $thisLowCate if
?>
					<!--</span>-->
					
				</div><!-- //.goods-sort-wrap -->


<?
    if ( $code_d == '000' ) {

        $sql  = "SELECT code_a,code_b,code_c,code_d, code_a||code_b||code_c||code_d as cate_code,code_name,idx FROM tblproductcode ";
        $sql .= "WHERE code_a = '{$code_a}' AND code_b = '{$code_b}' AND code_c = '{$code_c}' AND code_d <> '000' ";
        $sql .= "AND group_code !='NO' AND display_list is NULL ";
        $sql .= "ORDER BY cate_sort ASC ";

        $result = pmysql_query($sql);
?>
				<ul class="goods-list-depth4">
<?      
        while ( $row = pmysql_fetch_object($result) ) { 
            $link_url = $Dir.FrontDir."productlist.php?code=" . $row->cate_code;
?>
			        <li><a href="<?=$link_url?>"><?=$row->code_name?></a></li>
<?      } ?>
				</ul>

<?  } ?>

<?php
//상품리스트
foreach( $list_array as $listKey=>$listVal ){	
	echo $listVal;
}
?>
			</div>

			<div class="list-paginate-wrap">
				<!-- <div class="list-paginate">
					<a href="#" class="prev-all">처음으로</a>
					<a href="#" class="prev">이전</a>
					<a href="#" class="on">1</a>
					<a href="#">2</a>
					<a href="#">3</a>
					<a href="#">4</a>
					<a href="#">5</a>
					<a href="#">6</a>
					<a href="#">7</a>
					<a href="#">8</a>
					<a href="#">9</a>
					<a href="#">10</a>
					<a href="#" class="next">다음</a>
					<a href="#" class="next-all">끝으로</a>
				</div> -->
				<div class="list-paginate">
<?php
	if( $paging->pagecount > 1 ){
		echo $paging->a_prev_page.$paging->print_page.$paging->a_next_page;
	}
?>					
				</div>
			</div><!-- //.list-paginate-wrap -->
			

		</div><!-- //.containerBody -->
	</div>
<!-- //2016-01-22 리스트 -->
