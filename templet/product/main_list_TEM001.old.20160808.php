<?php
/********************************************************************* 
// 파 일 명		: main_list_TEM001.php 
// 설     명	: 대카테고리 템플릿
// 상세설명	    : 대카테고리에 노출하는 배너 및 상품 리스트 
// 작 성 자		: moondding2
// 수 정 자		: 2016.01.25 - 최문성
*********************************************************************/ 

$cate_code = $_GET['code'];
$imagepath=$Dir.DataDir."shopimages/product/";

// ===================================================================
// 대카테고리 이름 조회
// ===================================================================
$sql  = "select * from tblproductcode where code_a = '{$cate_code}' and code_b = '000' limit 1";
$result = pmysql_query($sql);
$row = pmysql_fetch_object($result);
$cate_name = $row->code_name;

// ===================================================================
// 브랜드 리스트 
// ===================================================================
//$sql  = "select * from tblproductbrand where productcode_a = '{$cate_code}' and display_yn = 1 order by bridx asc ";
$sql  = "SELECT a.* ";
$sql .= "FROM tblproductbrand a LEFT JOIN tblvenderinfo b ON a.vender = b.vender ";
$sql .= "WHERE a.productcode_a = '{$cate_code}' and a.display_yn = 1 AND b.disabled = 0 ";
$sql .= "ORDER BY a.brandname asc ";
//$sql .= "LIMIT 20 ";
$result = pmysql_query($sql);

$idx = 0;
$countPerList = 10;

$brand_list_html = '';
$brand_list_html .= '<ul>';

while ( $row = pmysql_fetch_array($result) ) {
	$brand_list_html .= '<li><label><input type="checkbox" id="' . $row['bridx'] . '"><span>' . $row['brandname'] . '</span></label></li>';
}

$brand_list_html .= '</ul>';

// ===================================================================
// ITEM 리스트
// ===================================================================
$sql  = "SELECT code_a,code_b,code_c,code_d, code_a||code_b||code_c||code_d as cate_code,code_name,idx FROM tblproductcode ";
$sql .= "WHERE code_a = '" . $cate_code . "' AND code_b <> '000' AND code_c = '000' AND ( group_code !='NO' AND is_hidden = 'N' ) AND display_list is NULL ";
$sql .= "ORDER BY cate_sort ASC ";
$result = pmysql_query($sql);

$arrSecondCategory = array();
while ( $row = pmysql_fetch_object($result) ) {
	array_push($arrSecondCategory, array($row->code_b, $row->code_name));
}
pmysql_free_result($result);

$item_idx = 1;
$item_list_html = '';
foreach ( $arrSecondCategory as $key => $arrData ) {
	$code_b         = $arrData[0];
	$code_b_name    = $arrData[1];

	$sql  = "SELECT code_a,code_b,code_c,code_d, code_a||code_b||code_c||code_d as cate_code,code_name,idx FROM tblproductcode ";
	$sql .= "WHERE code_a = '" . $cate_code . "' AND code_b = '" . $code_b . "' ";
	$sql .= "AND code_c <> '000' AND code_d = '000' AND ( group_code !='NO' AND is_hidden = 'N' ) AND display_list is NULL ";
	$sql .= "ORDER BY cate_sort ASC ";

	$result = pmysql_query($sql);

	$secondCateCode = $cate_code . $code_b . '000000';

	$item_list_html .= '
                <li>
                        <a href="../front/productlist.php?code=' . $secondCateCode . '">' . $code_b_name . '</a>
                            <ul class="category-sub" >';

	while ( $row = pmysql_fetch_object($result) ) {
		$item_list_html .= '<li><a href="../front/productlist.php?code=' . $row->cate_code . '">' . $row->code_name . '</a></li>';
	}
	pmysql_free_result($result);

	$item_list_html .= '
                            </ul>
                <li>';

	$item_idx++;
}
// ===================================================================
// 대카테고리 상단 배너 롤링
// ===================================================================
$sql  = "SELECT * FROM tblmainbannerimg ";
$sql .= "WHERE banner_no = 90 and banner_hidden = 1 and banner_category like '{$cate_code}%' ";
$sql .= "ORDER BY banner_sort asc ";
$result = pmysql_query($sql);

$arrTopBannerImg = array();
while ($row = pmysql_fetch_array($result)) {
    array_push($arrTopBannerImg, array("/data/shopimages/mainbanner/" . $row['banner_img'], $row['banner_link'], $row['banner_target']));
}

// ===================================================================
// TODAY_PICK
// ===================================================================

$week_num = date("w");
if ( $week_num == 0 ) { // 오늘이 일요일이면
    $week_num = 7;
}

$fieldName = "productcode{$week_num}";
$sql  = "SELECT {$fieldName} FROM tblproduct_todaynweekly_list ";
$sql .= "WHERE type = 'T' and cate like '{$cate_code}%' ";
$result = pmysql_query($sql);

$today_pick_html = '';
while ($row = pmysql_fetch_array($result) ) {
    $prod_sql  = "SELECT productcode, productname, sellprice, consumerprice, brand, maximage, minimage, tinyimage, over_minimage, ";
    $prod_sql .= "mdcomment, review_cnt, icon, soldout, quantity ";
    $prod_sql .= "FROM tblproduct WHERE display = 'Y' and productcode = '{$row[$fieldName]}' limit 1";
    $arrProd = productlist_print($prod_sql, "W_005");

    $today_pick_html = $arrProd[0];
}

// ===================================================================
// WEELKY BEST 7
// ===================================================================
$sql  = "SELECT * FROM tblproduct_todaynweekly_list ";
$sql .= "WHERE type = 'W' and cate like '{$cate_code}%' ";
$result = pmysql_query($sql);
$row = pmysql_fetch_array($result);

$arrProdCode = array();
$arrProdCodeForWhere = array();
for ( $i = 1; $i <= 7; $i++ ) {
    array_push($arrProdCode, $row["productcode".$i]);
    array_push($arrProdCodeForWhere, "'" . $row["productcode".$i] . "'");
}
$productcodes = (implode(",", $arrProdCodeForWhere));   

// -----------------------------------------------------
// 대카테고리 > BEST WEEKLY 7에 사용할 아이콘 정보
// -----------------------------------------------------
$sql  = "SELECT * FROM tblproduct_weekly_icon WHERE cate like '{$cate_code}%' ";
$result = pmysql_query($sql);
$row = pmysql_fetch_object($result);
pmysql_free_result($result);

$icon_info = array();
if ( $row ) {
    $icon_info = array(
        $row->icon1,
        $row->icon2,
        $row->icon3,
        $row->icon4,
        $row->icon5,
        $row->icon6,
        $row->icon7,
    );
}

$weekly_best_html = '';

$prod_sql  = "SELECT productcode, productname, sellprice, consumerprice, soldout, quantity, brand, maximage, minimage, tinyimage, mdcomment, over_minimage, review_cnt, icon ";
$prod_sql .= "FROM tblproduct WHERE display = 'Y' and productcode in ( {$productcodes} ) limit 7";

$arrProd = productlist_print($prod_sql, "W_006", $arrProdCode, null, $icon_info);

$weekly_best_html = $arrProd[0];

// =========================================================================
// 대카테고리 중간 탭 상품1,2,3,4 (NEW ARRIVAL / MD PICK / ONLY CASH ....)
// =========================================================================

$arrBannerNo = array(95,96,97,98);
$middle_tab_list_html = '';
$middle_tab_product_html = '';
for ( $i = 0; $i < count($arrBannerNo); $i++ ) {
    // 관련 상품 리스트 출력
    $sql  = "SELECT tblmainbannerimg_product.*, tblmainbannerimg.banner_title ";
    $sql .= "FROM tblmainbannerimg_product left join tblmainbannerimg on tblmainbannerimg_product.tblmainbannerimg_no = tblmainbannerimg.no ";
    $sql .= "WHERE tblmainbannerimg_no = ";
    $sql .= "( SELECT no FROM tblmainbannerimg WHERE banner_no = {$arrBannerNo[$i]} and banner_category like '{$cate_code}%' and banner_hidden = 1 ";
    $sql .= "ORDER BY banner_number desc limit 1 ) ";
    $sql .= "ORDER BY no asc ";
    $result = pmysql_query($sql);

    $arrProdCode = array();
    $arrProdCodeForWhere = array();
    $bannerTitle = "";

    $cnt = 0;
    while ($row = pmysql_fetch_array($result)) {
        array_push($arrProdCode, $row['productcode']);    
        array_push($arrProdCodeForWhere, "'" . $row['productcode'] . "'");
        $bannerTitle = $row['banner_title'];

        $cnt++;
    }

    if ( $cnt == 0 ) { continue; }

    $productcodes = (implode(",", $arrProdCodeForWhere));   

    $prod_sql  = "SELECT productcode, productname, sellprice, consumerprice, soldout, quantity, brand, maximage, minimage, tinyimage, over_minimage, mdcomment, review_cnt, icon ";
    $prod_sql .= "FROM tblproduct WHERE display = 'Y' and productcode in ( {$productcodes} ) ";
    $arrProd = productlist_print($prod_sql, "W_001", $arrProdCode);

    $rollingClass = "";
    if ( count($arrProd) >= 2 ) {
        $rollingClass = "goods-list-rolling";
    }

    $middle_tab_product_html .= '
        <div class="tab-sub">
            <div class="listbox with-btn-rolling-big" data-element="content">
            <div class="list ' . $rollingClass . '">';
 
    // 상품리스트    
    foreach($arrProd as $prod) {
        $middle_tab_product_html .= $prod;
    }

    $middle_tab_product_html .= '
        </div>
      </div>
    </div>';

    $onClass = ""; 
    if ( $i == 0 ) { 
        $onClass = "on";
    }

    $middle_tab_list_html .= "<li class=\"{$onClass}\">{$bannerTitle}</li>";
}

// ===================================================================
// BEST REVIEW 컨텐츠 생성
// ===================================================================
$sql  = "SELECT * FROM tblmainbannerimg_product ";
$sql .= "WHERE tblmainbannerimg_no = ";
$sql .= "( SELECT no FROM tblmainbannerimg ";
$sql .= "WHERE banner_no = 104 and banner_category like '{$cate_code}%' and banner_hidden = 1 order by banner_number asc limit 1 ) ";
$sql .= "ORDER BY no asc ";
$result = pmysql_query($sql);

$review_rolling_count = 0;
$review_rolling_html = '';
while ( $row = pmysql_fetch_array($result) ) {

    $review_rolling_html .= '<div class="review_box">';
    
    // 상품 정보
    $prod_sql  = "SELECT productcode, productname, sellprice, consumerprice, soldout, quantity, brand, maximage, minimage, tinyimage, over_minimage, mdcomment, review_cnt, icon ";
    $prod_sql .= "FROM tblproduct WHERE display = 'Y' and productcode = '{$row[productcode]}' limit 1";
    $arrProd = productlist_print($prod_sql, "W_007");
    $review_rolling_html .= $arrProd[0];

    if ( $arrProd[0] ) {
        // 리뷰 정보
        $review_sql  = "SELECT a.*, b.review_cnt FROM tblproductreview a, tblproduct b ";
        $review_sql .= "WHERE a.productcode = '{$row[productcode]}' and a.productcode = b.productcode ";
        $review_sql .= "ORDER BY a.date desc LIMIT 3 ";
        $review_result = pmysql_query($review_sql);

        $review_rolling_html .= '<ul class="review_list">';
        while ( $review_row = pmysql_fetch_array($review_result) ) {
            // 별점
            $stars_html = '';
            for ( $i = 0; $i < $review_row[marks]; $i++ ) {
                $stars_html .= '<img src="../static/img/icon/star_small.png" alt="별">';
            }

            // 등록일
            $reg_date = substr($review_row[date], 0, 4) . "." . substr($review_row[date], 4, 2) . "." . substr($review_row[date], 6, 2);

            /*$review_rolling_html .= '
                <li><a href="/front/productdetail.php?productcode=' . $row['productcode'] . '#tab-product-review"> <span class="star">' . $stars_html . '</span> <span class="comment">' . strip_tags($review_row['subject']) . " " . strip_tags($review_row['content']) . '</span> <span class="hit">HIT:' . $review_row['hit'] . '</span> <span class="id">ID : ' . $review_row['id'] . '</span> <span class="date">DATE : ' . $reg_date . '</span> </a></li>';*/

            $review_rolling_html .= '
                <li><a href="/front/review.php"> <span class="star">' . $stars_html . '</span> <span class="comment">' . strip_tags($review_row['subject']) . " " . strip_tags($review_row['content']) . '</span> <span class="hit">HIT:' . $review_row['hit'] . '</span> <span class="id">ID : ' . setIDEncryp($review_row['id']) . '</span> <span class="date">DATE : ' . $reg_date . '</span> </a></li>';
        }
        $review_rolling_count++;
    }

    $review_rolling_html .= '</ul></div>';
}

// ===================================================================
// 대카테고리 하단 NEWBRAND 배너/상품
// ===================================================================

$arrBannerNo = array(91);

$bottom_new_brand_tab_title = "";
$bottom_new_brand_html = '';

for ( $i = 0; $i < count($arrBannerNo); $i++ ) {
    $sql  = "SELECT * FROM tblmainbannerimg ";
    $sql .= "WHERE banner_no = {$arrBannerNo[$i]} and banner_category like '{$cate_code}%' and banner_hidden = 1 ORDER BY banner_number desc limit 1 ";
    $result = pmysql_query($sql);
    $row = pmysql_fetch_object($result);
    $bottom_new_brand_tab_title = $row->banner_up_title;
    $bannerimg_no = $row->no;
    pmysql_free_result($result);

    // 관련 상품 리스트 출력
    $sql  = "SELECT tblmainbannerimg_product.*, tblmainbannerimg.banner_title, tblmainbannerimg.banner_name, tblmainbannerimg.banner_img, ";
    $sql .= "tblmainbannerimg.banner_link, tblmainbannerimg.banner_n_link, tblmainbannerimg.banner_target ";
    $sql .= "FROM tblmainbannerimg_product left join tblmainbannerimg on tblmainbannerimg_product.tblmainbannerimg_no = tblmainbannerimg.no ";
    $sql .= "WHERE tblmainbannerimg_no = {$bannerimg_no} ";
//    $sql .= "( SELECT no FROM tblmainbannerimg WHERE banner_no = {$arrBannerNo[$i]} and banner_category like '{$cate_code}%' and banner_hidden = 1 ";
//    $sql .= "ORDER BY banner_number desc limit 1 ) ";
    $sql .= "ORDER BY no asc ";
    $result = pmysql_query($sql);

    $arrProdCode = array();
    $arrProdCodeForWhere = array();
    $bannerImg = "";
    $bannerTitle = "";
    $bannerName = "";
    $bannerLink = "";
    $bannerTextLink = "";
    $bannerTarget = "";
    while ($row = pmysql_fetch_array($result)) {
        array_push($arrProdCode, $row['productcode']);    
        array_push($arrProdCodeForWhere, "'" . $row['productcode'] . "'");
        $bannerTitle = $row['banner_title'];
        $bannerName = $row['banner_name'];
        $bannerImg = $row['banner_img'];
        $bannerLink = $row['banner_link'];
        $bannerTextLink = $row['banner_n_link'];
        $bannerTarget = $row['banner_target'];
    }
    pmysql_free_result($result);

    $productcodes = (implode(",", $arrProdCodeForWhere));   

    if ( $i == 0 ) { 
        $bottom_new_brand_html .= '
          <h4> 
            <span class="img">';

        if ( !empty($bannerLink) ) {
            $bottom_new_brand_html .= '<a href="' . $bannerLink . '" target="' . $bannerTarget . '">';
        }

        $bottom_new_brand_html .= '<img src="/data/shopimages/mainbanner/' . $bannerImg . '" alt="">';

        if ( !empty($bannerLink) ) {
            $bottom_new_brand_html .= '</a>';
        }

        $bottom_new_brand_html .= '</span>';

        $bottom_new_brand_html .= '<span class="cate">';

/*
        if ( !empty($bannerTextLink) ) {
          $bottom_new_brand_html .= '<a href="' . $bannerTextLink . '" target="' . $bannerTarget . '">';
        }
*/

        $bottom_new_brand_html .= $bannerTitle;

/*
        if ( !empty($bannerLink) ) {
            $bottom_new_brand_html .= '</a>';
        }
*/

        $bottom_new_brand_html .= '</span><span class="name">' . $bannerName . '</span></h4>';   
    }

    $prod_sql  = "SELECT productcode, productname, sellprice, consumerprice, soldout, quantity, brand, maximage, minimage, tinyimage, over_minimage, mdcomment, review_cnt, icon ";
    $prod_sql .= "FROM tblproduct WHERE display = 'Y' and productcode in ( {$productcodes} ) ";
    $arrProd = productlist_print($prod_sql, "W_008", $arrProdCode);

    $bottom_new_brand_html .= $arrProd[0];
}

// ===================================================================
// 대카테고리 하단 BESTBRAND 배너/상품
// ===================================================================
$arrBannerNo = array(92);
$bottom_best_brand_tab_title = "";
$bottom_best_brand_html = '';
for ( $i = 0; $i < count($arrBannerNo); $i++ ) {
    $sql  = "SELECT * FROM tblmainbannerimg ";
    $sql .= "WHERE banner_no = {$arrBannerNo[$i]} and banner_category like '{$cate_code}%' and banner_hidden = 1 ORDER BY banner_number desc limit 1 ";
    $result = pmysql_query($sql);
    $row = pmysql_fetch_object($result);
    $bottom_best_brand_tab_title = $row->banner_up_title;
    $bannerimg_no = $row->no;
    pmysql_free_result($result);

    // 관련 상품 리스트 출력
    $sql  = "SELECT tblmainbannerimg_product.*, tblmainbannerimg.banner_title, tblmainbannerimg.banner_name, tblmainbannerimg.banner_img, ";
    $sql .= "tblmainbannerimg.banner_link, tblmainbannerimg.banner_n_link, tblmainbannerimg.banner_target ";
    $sql .= "FROM tblmainbannerimg_product left join tblmainbannerimg on tblmainbannerimg_product.tblmainbannerimg_no = tblmainbannerimg.no ";
    $sql .= "WHERE tblmainbannerimg_no = {$bannerimg_no} ";
//    $sql .= "( SELECT no FROM tblmainbannerimg WHERE banner_no = {$arrBannerNo[$i]} and banner_category like '{$cate_code}%' and banner_hidden = 1 ";
//    $sql .= "ORDER BY banner_number desc limit 1 ) ";
    $sql .= "ORDER BY no asc ";
    $result = pmysql_query($sql);

    $arrProdCode = array();
    $arrProdCodeForWhere = array();
    $bannerTitle = "";
    $bannerName = "";
    $bannerImg = "";
    $bannerLink = "";
    $bannerTextLink = "";
    $bannerTarget = "";
    while ($row = pmysql_fetch_array($result)) {
        array_push($arrProdCode, $row['productcode']);    
        array_push($arrProdCodeForWhere, "'" . $row['productcode'] . "'");
        $bannerTitle = $row['banner_title'];
        $bannerName = $row['banner_name'];
        $bannerImg = $row['banner_img'];
        $bannerLink = $row['banner_link'];
        $bannerTextLink = $row['banner_n_link'];
        $bannerTarget = $row['banner_target'];
    }
    $productcodes = (implode(",", $arrProdCodeForWhere));   

    if ( $i == 0 ) { 
        $bottom_best_brand_html .= '
        <h4> 
            <span class="img">';

        if ( !empty($bannerLink) ) {
            $bottom_best_brand_html .= '<a href="' . $bannerLink . '" target="' . $bannerTarget . '">';
        }

        $bottom_best_brand_html .= '<img src="/data/shopimages/mainbanner/' . $bannerImg . '" alt="">';

        if ( !empty($bannerLink) ) {
            $bottom_best_brand_html .= '</a>';
        }

        $bottom_best_brand_html .= '</span><span class="cate">';

/*
        if ( !empty($bannerTextLink) ) {
            $bottom_best_brand_html .= '<a href="' . $bannerTextLink . '" target="' . $bannerTarget . '">';
        }
*/

        $bottom_best_brand_html .= $bannerTitle;

/*
        if ( !empty($bannerLink) ) {
            $bottom_best_brand_html .= '</a>';
        }
*/

        $bottom_best_brand_html .= '</span><span class="name">' . $bannerName . '</span></h4>';   
    }

    $prod_sql  = "SELECT productcode, productname, sellprice, consumerprice, soldout, quantity, brand, maximage, minimage, tinyimage, over_minimage, mdcomment, review_cnt, icon ";
    $prod_sql .= "FROM tblproduct WHERE display = 'Y' and productcode in ( {$productcodes} ) ";
    $arrProd = productlist_print($prod_sql, "W_008", $arrProdCode);

    $bottom_best_brand_html .= $arrProd[0];
}



?>

<!-- 메인 컨텐츠 -->
<script>
    $(document).ready(function(){
    });

    function goProductDetail(locationUrl){
        location.href = locationUrl;
    }
</script>

<div id="contents" class="catemainPage"> 
  <!-- 브레드크럼 -->
  <div class="breadcrumb">
    <ul>
      <li><a href="/">HOME</a></ li>
      <li class="on"><a href="<?=$_SERVER['REQUEST_URI']?>"><?=$cate_name?></a></li>
    </ul>
  </div>
  <!-- 브레드크럼 -->
  <div class="hot_container">
    <div class="tabwrap lnbcate_wrap" data-ui="TabMenu">
      <div class="listwrap">
        <h2>
          <button class="on" type="button" title="선택됨" data-element="menu"><span>ITEM</span></button>
        </h2>

        <div class="listbox on" data-element="content">
            <?=$item_list_html?>
        </div>
      </div>
      <div class="listwrap">
        <h2>
          <button class="" type="button" title="" data-element="menu"><span>BRAND</span></button>
        </h2>
        <div class="listbox" data-element="content">
			<?=$brand_list_html?>
          <div class="brandsch">
            <form name="search_frm" id="search_frm" action="/front/productsearch.php" onSubmit="chkSearchWord(this); return false;">
              <input type="hidden" name="thr" value="sw" />
              <input type="hidden" name="cate" value="brand" /> <!-- 브랜드로 검색하게끔 -->
              <fieldset>
                <legend>ALL BRAND SEARCH</legend>
                <input type="text" name="search" id="search" title="검색어 입력">
                <button type="submit">검색하기</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="mainbanner_wrap with-btn-rolling">
    <div class="main_banner">
        <?php 
            foreach ( $arrTopBannerImg as $arrData ) { 
                echo '<div>';
                if ( !empty($arrData[1]) ) { 
                    echo '<a href="' . $arrData[1] . '" target="' . $arrData[2] . '">';
                }
                echo '<img src="' . $arrData[0] . '" alt="">';
                if ( !empty($arrData[1]) ) {
                    echo '</a>';
                }
                echo '</div>';
            } 
        ?>
    </div>
</div>
    <div class="today_box">
      <h3>today pick</h3>
        <?=$today_pick_html?>
    </div>
  </div>
  <!--//1단 -->

  <div class="best7wrap">
    <h2>WEEKLY BEST 7</h2>
    <?=$weekly_best_html?>
  </div>
  <!--//2단 -->

 
  <!--div class="tabwrap goodwrap depth1 with-btn-rolling-big "  data-ui="TabMenu"-->

<div class="category-tab-wrap">
    <div class="category-underline" style="left: 429px; width: 23px;"></div>
    <ul class="category-tab">
        <?=$middle_tab_list_html?>
    </ul>
</div>
    
    <?=$middle_tab_product_html?>
    
    <script>
$(function() {	
	
	$(".goodwrap .listwrap").each(function (e) {		
		var $ui = $(this);
		var $list = $ui.find(".list");
		var $content = $ui.find(".list");
        //var $prv = $list.find(".toggleslide .prev");
	    //var $nxt = $list.find(".toggleslide .next");	  
    	var swipes = []

$content.each(function (i, obj) {
    swipes[i] = $(this).bxSlider({
        mode: 'horizontal',
        
        startSlide:0,
		minSlides: 1,
        maxSlides: 1,
        moveSlides: 1,	
        slideMargin: 0,	
		infiniteLoop: false,
        hideControlOnEnd: true,	
        pager: false,
        controls:true
    })

   
    
   
});
});
});  
  </script> 
  <!--/div-->

<!-- //3단 -->

<div class="review_wrap ">
  <h2>BEST REVIEW <a href="/front/review.php" class="see-more">SEE MORE</a></h2>
  <div class="inner  with-btn-rolling-big">
  <div class="review_container">
    <?=$review_rolling_html?>
  </div>
  </div><!-- //.inner -->

<?php
        if ( $review_rolling_count > 2 ) {
?>
            <script>
            $(document).ready(function(){
              $('.review_container').bxSlider({
                  infiniteLoop: true,
                  pager: false,
                  auto: false
              });  
            });
            </script> 
<?php
        }
?>
</div>

<!-- //4단 -->
<div class="tabwrap brand_wrap" data-ui="TabMenu">
<h2>BRAND</h2>
  <div class="listwrap">
    <h3>
      <button class="on" type="button" title="선택됨" data-element="menu"><span><?=$bottom_new_brand_tab_title?></span></button>
    </h3>
    <div class="listbox on" data-element="content">
        <?=$bottom_new_brand_html?>
    </div>
  </div>
  
  <div class="listwrap">
    <h3>
      <button class="" type="button" title="" data-element="menu"><span><?=$bottom_best_brand_tab_title?></span></button>
    </h3>
    <div class="listbox " data-element="content">
        <?=$bottom_best_brand_html?>
    </div>
  </div>
  </div>
  <!-- //5단 -->
  
    
</div>
<!-- //contents -->

<!--///////////////////////////////////////////  미리보기 popup을 위한 div   &&  장바구니 -->
<div id="overDiv" style="position:absolute;top:0px;left:0px;z-index:100;display:none;" class="alpha_b60" ></div>
<div class="popup_preview_warp" style="margin-left: 50%;left: -459px;display:none;" ></div>

<form name=form1 id = 'ID_goodsviewfrm' method=post action="<?=$Dir.FrontDir?>basket.php">
	<input type="hidden" name="productcode"></input>
</form>
<!--///////////////////////////////////////////-->
<script type="text/javascript">


function CheckForm(gbn,temp2) {


	if(gbn=="ordernow") {
		document.form1.ordertype.value="ordernow";
	}

	if (gbn != "ordernow"){
		document.form1.action="../front/confirm_basket.php";
		document.form1.target="confirmbasketlist";
		document.form1.productcode.value= temp2;
		window.open("about:blank","confirmbasketlist","width=401,height=309,scrollbars=no,resizable=no, status=no,");
		document.form1.submit();
		document.back.submit();
	}

}

function change_quantity(gbn) {
	tmp=document.form1.quantity.value;
	if(gbn=="up") {
		tmp++;
	} else if(gbn=="dn") {
		if(tmp>1) tmp--;
	}
	var cons_qu = $("#constant_quantity").val();
	if (cons_qu != "" && cons_qu != "0"){
		if (cons_qu<tmp){
			alert('재고량이 부족 합니다.');
			return;
		}
	} else if(cons_qu == "0") {
		alert('품절 입니다.');
		return;
	}

	<?php  if($_pdata->assembleuse=="Y") { ?>
		if(getQuantityCheck(tmp)) {
			if(document.form1.assemblequantity) {
				document.form1.assemblequantity.value=tmp;
			}
			document.form1.quantity.value=tmp;
			setTotalPrice(tmp);
		} else {
			alert('구성상품 중 '+tmp+'보다 재고량이 부족한 상품있어서 변경을 불가합니다.');
			return;
		}
	<?php  } else { ?>
		var tmp_price = $("#ID_goodsprice").val();
		tmp_price = Number(tmp_price)*Number(tmp);
		setDeliPrice(tmp_price,tmp);
		$("#result_total_price").html(jsSetComa(tmp_price));
		document.form1.quantity.value=tmp;
	<?php  } ?>

}


$(document).ready(function() {
	//Default Action
	var defaultType = 0;
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li").each(function(){
		if($(this).attr("class")=="active"){
			defaultType = 1;
			var tabId = $(this).find("a").attr("href");
			$(tabId).show();
		}
	});
	if(defaultType == 0){
		$("ul.tabs li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
	}

	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});

	$('.new_goods4ea ul.list li').mouseenter(function(){
	$(this).find('.layer_goods_icon').show();
	});
	$('.new_goods16ea ul.list li').mouseenter(function(){
	$(this).find('.layer_goods_icon').show();
	});
	$('.in_icon').mouseleave(function(){
	$('.layer_goods_icon').hide();
	});

<?php if($max>0){ ?>
	$("#middle_slide").sudoSlider({
		effect: "slide",
		speed:1000,
		continuous:true,
		slideCount:4,
		prevNext:false,
		moveCount:1,
		customLink:'.prevnext',
		auto:false,
		animationZIndex:0,
	});
<? } ?>
});

</script>
<script>
$(document).ready(function(){

  $(".layer_goods_icon").on("click",function(e){
    	var target = e.target
    	if($(target).attr("class") == "cart" || $(target).attr("class") == "view" ) return; 
    	location.href = $(this).attr("link_url");
    });
    
    $(".cart").on("click",function(e){
    	var chkOption = $(this).attr("option_chk");
    	var chkLink = $(this).attr("cart_chk");
    	if(chkOption == 1){
			CheckForm('',chkLink);
		}else if(chkOption == 3){
	    	$("#productlist_basket").attr("action","../front/productlist_basket.php");
	    	$("#productlist_basket").attr("target","basketOpen");
	    	$("#productcode2").val(chkLink);
			window.open("","basketOpen","width=440,height=420,scrollbars=no,resizable=no, status=no,");
			$("#productlist_basket").submit();
		} 
    });
 
/*   
    //이걸 왜 걸어놨을까....

    $(".view").on("click",function(){
    	location.href = $(this).attr("link_url");
    });
*/

});

// ALL BRAND SEARCH 일 경우
function chkSearchWord(obj) {

    var searchObj = $(obj).find("input[name='search']");

    if ( $(searchObj).val().trim() === "" ) {
        alert("검색어를 입력해주세요.");
        $(searchObj).val("").focus();
        return false;
    }

    $(obj).submit();
}

</script>

<form name="productlist_basket" id="productlist_basket">
<input type="hidden" name="productcode2" id="productcode2">
</form>


<form name="back" action="../front/productdetail.php">
<input type="hidden" name="back2" value="1">
</form>

