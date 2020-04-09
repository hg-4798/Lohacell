<?php
$basename=basename($_SERVER["PHP_SELF"]);

$img_path = "/data/shopimages/staritem/";
$tblName   = "tblstaritem";
$banner_img_path = "http://www.JayJun.com/SE2/images/2017/best.gif";

// ===================================================================================
// 리스트 조회하기
// ===================================================================================
$listnum = 6; // service
//    $listnum = 3;   // dev

// 전체 건수 조회
$t_sql    = "SELECT count(*) FROM {$tblName} where hidden = 1 ";
list($total_row_count) = pmysql_fetch($t_sql);

/*무한스크롤 삭제(1)
 /* if ( $isMobile ) {
 $t_sql    = "SELECT * FROM {$tblName} where hidden = 1 ORDER BY sort asc, no desc ";
 } else {
 $t_sql    = "SELECT * FROM {$tblName} where hidden = 1 ORDER BY sort asc, no desc limit {$listnum} ";
 } */
$t_sql    = "SELECT * FROM {$tblName} where hidden = 1 ORDER BY sort asc, no desc ";

$result = pmysql_query($t_sql);

$list_html = '';

while ($row = pmysql_fetch_array($result)) {
    
    
    $list_html .= '
					<li>
						<a href="'.$row['link'].'">
							<div class="brand-nm">'.$row['brandtitle'].'</div>
							<div class="caption">
								<p class="tit">'.$row['title'].'</p>
								<p class="comment">'.$row['subtitle'].'</p>
							</div>
							<div class="thumb"><img src="'.$img_path.$row['img'].'" alt="STAR ITEM 이미지" width="240" height="240"></div>
						</a>
					</li>';
}


// ==================================================================================
// 대카테고리 정보 조회
// ==================================================================================
$arrCategoryCode = array("");    // 전체

$category_sql  = "select * from tblproductcode where code_b = '000' and is_hidden = 'N' order by cate_sort asc ";
$category_result = pmysql_query($category_sql);

$categoryHtml = "";

/* if ( $isMobile ) {
 $categoryHtml .= '<li class="js-staritem-menu-content CLASS_TAB on" id="tab_000"><a href="javascript:;" onClick="javascript:changeStarItemTab(\'000\');"><span>ALL</span></a></li>';
 } */

while( $category_row = pmysql_fetch_array( $category_result ) ){
    /*     if ( $isMobile ) {
     $categoryHtml .= '<li class="js-staritem-menu-content CLASS_TAB" id="tab_'. $category_row['code_a'] . '""><a href="javascript:;" onClick="javascript:changeStarItemTab(\'' . $category_row['code_a'] . '\');"><span>' . $category_row['code_name'] . '</span></a></li>';
     } else { */
    $categoryHtml .= '<li class="CLASS_TAB" id="'. $category_row['code_a'] . '""><a onClick="javascript:changeStarItemTab(\'' . $category_row['code_a'] . '\');"><span>' . $category_row['code_name'] . '</span></a></li>';
    /*     } */
}
?>
<div id="contents">
    <div class="containerBody sub-page">

        <div class="promotion-wrap">
			<div class="breadcrumb studio-top">
				<ul>
					<li><a href="/">HOME</a></li>
					<li class="on"><a href="<?=$_SERVER['PHP_SELF']?>">BEST</a></li>
				</ul>
			</div>
            
            <div class="promotion-area">
				<!-- <h3 class="staritem title">디자이너가 선택한 <?=date("n")?>월의 스타상품</h3> -->
				<img src="<?=$banner_img_path?>" alt="STAR ITEM 배너">
				<div class="staritem category-tab-wrap">
					<div class="category-underline"></div>
					<ul class="category-tab">
                        <li id="" class="on"><a onClick="javascript:changeStarItemTab('')";>ALL</a></li>
                        <?=$categoryHtml?>
					</ul>
				</div><!-- //.category-tab-wrap -->
				<ul class="staritem-list">
                     <?=$list_html?>
                </ul><!-- //.star-press-list -->
            </div><!-- //.star-press-list-wrap -->

        </div><!-- //.promotion-wrap -->

    </div><!-- //.containerBody -->
</div><!-- //contents -->

<div id="create_openwin" style="display:none"></div>

<script type="text/javascript">

    function changeStarItemTab(cate_code) {
        $(".CLASS_TAB").removeClass("on");
        $("#"+cate_code).addClass("on");
        
        $.ajax({
            type: "get",
            url: "/front/ajax_get_staritem_category_list.php",
            data: 'cate_code='+cate_code
        }).success(function ( result ) {
            $('.staritem-list').empty();
            $('.staritem-list').append(result);
        });
    }
    
</script>



<!-- 무한스크롤 삭제(2)
<script type="text/javascript">
    var page = 2;
    var endpage = false;

    var $win = $(window),
    	$doc = $(document);

	$win.scroll(function()
			{		
			if($win.scrollTop() == $doc.height() - $win.height())
			{			
				if(!endpage){	
				$.ajax({
		            type: "get",
		            url: "/front/ajax_get_staritem_list.php",
		            data: 'gotopage=' + page + '&list_num=<?=$listnum?>'
		        }).success(function ( result ) {

		            var arrTmp = result.split("||");

		            if ( arrTmp[0] == "END" ) {
		                // 마지막 페이지인 경우 더보기 숨김
		            	endpage = true;
		            } else {
		                // 더보기 링크를 다음페이지로 셋팅
		                page++;
		            }
		            if ( arrTmp[1] != "" ) {
		                // 추가 내용이 있으면 기존꺼에 추가
		                $('.staritem-list').append(arrTmp[1]);
		            }
		        });
			}
			}
			});


</script>
 -->