<?php
$bridx          = $_REQUEST['bridx'];
if ( $bridx === "" ) {
    echo "<script type='text/javascript'>alert('해당 브랜드가 존재하지 않습니다.'); history.go(-1);</script>";
    exit;
}
if(!is_numeric($bridx)) exit;
$sort           = $_REQUEST['sort']?:"order";
//$sort           = $_REQUEST['sort'];

if ( $isMobile ) {
    $listnum        = $_REQUEST['listnum']?:20;
} else {
    $listnum        = $_REQUEST['listnum']?:50;
}

$search_word    = $_REQUEST['search_word']?:"";
$cate_code      = $_REQUEST['cate_code'];
$cate_code_a    = substr($cate_code, 0, 3);
$cate_code_b    = substr($cate_code, 3, 3);
$cate_code_c    = substr($cate_code, 6, 3);
$cate_code_d    = substr($cate_code, 9, 3);

// ======================================================================================
// 브랜드 정보 조회
// ======================================================================================

$sql  = "SELECT * FROM tblproductbrand WHERE bridx = '{$bridx}' AND 1=1 ";
$row  = pmysql_fetch_object(pmysql_query($sql));

$brand_name = $row->brandname;
$brand_cate = $row->productcode_a;
$venderIdx  = $row->vender;

/*
if ( empty($cate_code) ) {
    $cate_code = $brand_cate;
}
*/

$sql  = "SELECT * ";
$sql .= "FROM tblvenderinfo_add ";
$sql .= "WHERE vender = '{$venderIdx}' ";
$row  = pmysql_fetch_object(pmysql_query($sql));

$brand_desc = $row->description;

// 롤링할 이미지
$arrRollingBannerImg = array();
for ( $i = 1; $i <= 10; $i++ ) {
    $varName = "b_img" . $i;

    if ( !empty($row->$varName) ) {
        if( is_file("../data/shopimages/vender/".$row->$varName) ){
          array_push($arrRollingBannerImg, $row->$varName);
        }
    }
}

if ( $isMobile ) {
    $rolling_html = '';
    if ( count($arrRollingBannerImg) >= 1 ) {
        $rolling_html = '
                <div class="js-brand-visual">
                    <div class="js-brand-visual-list">
                        <ul>';

        $bannerCount = 0;
        foreach ( $arrRollingBannerImg as $key => $val ) {
            if ( !empty($val) ) {
                $rolling_html .= '<li class="js-brand-visual-content"><a href="javascript:;"><img src="/data/shopimages/vender/' . $val . '" alt=""></a></li>';
                $bannerCount++;
            }
        }

        $rolling_html .= '
                        </ul>
                    </div>';

        if ( $bannerCount >= 2 ) {
            $rolling_html .= '
                        <button class="js-brand-visual-arrow" data-direction="prev" type="button"><img src="./static/img/btn/btn_slider_arrow_prev.png" alt="이전"></button>
                        <button class="js-brand-visual-arrow" data-direction="next" type="button"><img src="./static/img/btn/btn_slider_arrow_next.png" alt="다음"></button>';
        }

        $rolling_html .= '
                </div>';
    }
}

// ======================================================================================
// 찜한 리스트(로그인한 상태인 경우)
// ======================================================================================
$arrBrandWishList = array();
$onBrandWishClass = "";
if (strlen($_ShopInfo->getMemid()) > 0) {
    $sql  = "SELECT a.bridx, b.brandname ";
    $sql .= "FROM tblbrandwishlist a LEFT JOIN tblproductbrand b ON a.bridx = b.bridx ";
    $sql .= "WHERE id = '" . $_ShopInfo->getMemid() . "' ";
    $sql .= "ORDER BY wish_idx desc ";

    $result = pmysql_query($sql);
    while ($row = pmysql_fetch_array($result)) {
        $arrBrandWishList[$row['bridx']] = $row['brandname'];

        // 내가 찜한 브랜드인 경우
        if ( $row['bridx'] == $bridx ) {
            $onBrandWishClass = "on";
        }
    }
}

// ======================================================================================
// 관련 프로모션 정보
// ======================================================================================

// 기획전 중에서 현재 진행중인것들을 조회

/*
$sql  = "SELECT a.special_list, c.idx, c.title ";
$sql .= "FROM tblspecialpromo a ";
$sql .= "   LEFT JOIN tblpromotion b ON a.special::integer = b.seq ";
$sql .= "   LEFT JOIN tblpromo c ON b.promo_idx = c.idx ";
$sql .= "WHERE c.display_type in ('A', 'P') and current_date <= c.end_date ";
$sql .= "ORDER BY c.rdate desc ";
*/

$sql  = "SELECT idx, title, bridx_list ";
$sql .= "FROM tblpromo ";
$sql .= "WHERE display_type in ('A', 'P') and hidden = '1' AND current_date <= end_date AND current_date >= start_date ";
$sql .= "ORDER BY rdate desc ";

$result = pmysql_query($sql);

$bLoopBreak = false;
$limitCount = 2;
$arrPromotionIdx = array();
$arrPromotionTitle = array();
while ($row = pmysql_fetch_array($result)) {
    $promo_idx          = $row['idx'];
    $promo_title        = $row['title'];
    $promo_bridx_list   = $row['bridx_list'];

    $sub_sql  = "SELECT a.special_list ";
    $sub_sql .= "FROM tblspecialpromo a LEFT JOIN tblpromotion b ON a.special::integer = b.seq ";
    $sub_sql .= "WHERE b.promo_idx = '{$promo_idx}' ";

    $sub_result = pmysql_query($sub_sql);

    if ( pmysql_num_rows($sub_result) == 0 ) {
        // 상품이 등록되어 있지 않은 프로모션
        if ( strpos($promo_bridx_list, ",{$bridx},") !== false ) {
            if ( count($arrPromotionIdx) < $limitCount && !in_array($promo_idx, $arrPromotionIdx) ) {
                array_push($arrPromotionIdx, $promo_idx);
                array_push($arrPromotionTitle, $promo_title);
            }
        }
    } else {
        while ( $sub_row = pmysql_fetch_object($sub_result) ) {
            $special_list   = trim($sub_row->special_list, ",");
            $special_list   = str_replace(",", "','", $special_list);

            // 해당 브랜드에 속한 상품 리스트 조회
            $sub_sql2  = "SELECT count(*) ";
            $sub_sql2 .= "FROM tblbrandproduct ";
            $sub_sql2 .= "WHERE bridx = {$bridx} AND productcode in ( '{$special_list}' ) ";
            $sub_sql2 .= "LIMIT 1 ";

            $sub_row2  = pmysql_fetch_object(pmysql_query($sub_sql2));

            if ( $sub_row2->count >= 1 ) {
                if ( count($arrPromotionIdx) < $limitCount && !in_array($promo_idx, $arrPromotionIdx) ) {
                    array_push($arrPromotionIdx, $promo_idx);
                    array_push($arrPromotionTitle, $promo_title);
                }
            }
        }
    }
    pmysql_free_result($sub_result);
}

// ======================================================================================
// 브랜드 관련 상품 리스트
// ======================================================================================
//20171214 중복 상품 발생하여 임시방편으로 GROUP BY 추가함
$tmp_sort=explode("_",$sort);

$prod_sql  = "SELECT a.productcode, a.productname, a.sellprice, a.consumerprice, a.soldout, a.quantity, a.brand, a.maximage, a.minimage, a.tinyimage, a.over_minimage, ";
$prod_sql .= "a.mdcomment, a.review_cnt, a.icon, ";
$prod_sql .= "(a.consumerprice - a.sellprice) as diffprice ";
$prod_sql .= "FROM tblproduct a LEFT JOIN tblbrandproduct b ON a.productcode = b.productcode ";

if ( !empty($cate_code) ) {
    $prod_sql .= "LEFT JOIN tblproductlink c ON b.productcode = c.c_productcode ";
}

$prod_sql .= "WHERE a.display = 'Y' and b.bridx = {$bridx} ";

if ( !empty($search_word) ) {
    $prod_sql .= "AND a.productname like '%{$search_word}%' ";
}

if ( !empty($cate_code) ) {
    // 뒤에 '0'을 모두 제거
    $prod_sql .= "AND ( c.c_maincate = 1 AND c.c_category like '" . rtrim($cate_code, "0") . "%' ) ";
}
$prod_group = " GROUP BY a.productcode, a.productname, a.sellprice, a.consumerprice, a.soldout, a.quantity, a.brand, a.maximage, a.minimage, a.tinyimage, a.over_minimage, a.mdcomment, a.review_cnt, a.icon, diffprice ";
$prod_order = " ORDER BY ";
if ( $tmp_sort[0] == "rcnt" ) {
    // REVIEW
    $prod_group .= ", a.review_cnt ";
    $prod_order .= "a.review_cnt ".$tmp_sort[1];
} else if ( $tmp_sort[0]=="price" ) {
    // PRICE
    $prod_group .= ", a.sellprice ";
    $prod_order .= "a.sellprice ".$tmp_sort[1];
} else if ( $tmp_sort[0]=="best" ) {
    // BEST
    $prod_group .= ", a.vcnt ";
    $prod_order .= "a.vcnt desc";
} else if ( $tmp_sort[0]=="sale" ) {
    // SALE (정가 - 판매가 값이 큰순으로 정렬)
    $prod_group .= ", diffprice ";
    $prod_order .= "diffprice desc";
} else {
    // NEW
    $prod_group .= ", b.start_no ";
    $prod_order .= "b.start_no asc";
}
$prod_group .= ", a.regdate, a.modifydate ";
$prod_order .= ", a.regdate desc, a.modifydate desc";

$prod_sql .= $prod_group.$prod_order;

if ( $isMobile ) {
    $paging = new New_Templet_mobile_paging($prod_sql, 5, $listnum, 'GoPage', true);
} else {
    $paging = new New_Templet_paging($prod_sql,10,$listnum,'GoPage',true);
}
$t_count    = $paging->t_count;
$gotopage   = $paging->gotopage;

$prod_sql   = $paging->getSql($prod_sql);
$total_cnt  = $paging->t_count;

//exdebug($prod_sql);

if ( $isMobile ) {
    $arrProd = productlist_print($prod_sql, "W_015", null, $listnum);
} else {
    $arrProd = productlist_print($prod_sql, "W_010", null, $listnum);
}

if ( $isMobile ) {
    include($Dir.TempletDir."brand/mobile/brand_detail_TEM001.php");
} else {
?>

<div id="contents">
        <div class="containerBody brand-page">

            <div class="breadcrumb">
                <ul>
                    <li><a href="/">HOME</a></li>
                    <li><a href="/front/brand.php">BRAND</a></li>
                    <li class="on"><a href="/front/brand_detail.php?bridx=<?=$bridx?>"><?=$brand_name?></a></li>
                </ul>
            </div><!-- //.breadcrumb -->

            <div class="ta-r">
                <div class="select small">
                    <span class="ctrl"><span class="arrow"></span></span>
                    <button type="button" class="my_value">My Favorite Brand</button>
                    <ul class="a_list">
                        <?php foreach ( $arrBrandWishList as $t_bridx => $t_brandname ) { ?>
                        <li><a href="/front/brand_detail.php?bridx=<?=$t_bridx?>"><?=$t_brandname?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <?php
                // 브랜드 제목이 없고 롤링이미지가 없는 경우
                $hideClass = "";
                if ( empty($brand_name) && count($arrRollingBannerImg) == 0 ) {
                    $hideClass = "hide";
                }
            ?>

            <div class="cash-made-wrap <?=$hideClass?>">
                <div class="made-introduce">
                    <div class="inner-intro js-scroll">
                        <div class="title-wrap"><p class="title <?=$onBrandWishClass?>" onclick="javascript:setBrandWishList(this, '<?=$bridx?>', '/front/brand_detail.php?bridx=<?=$bridx?>');"></p><?=$brand_name?></div>

						<p class="ment ">
                            <?=$brand_desc?>
                        </p>
                    </div>

                    <?php if ( count($arrPromotionIdx) >= 1 ) { ?>
                    <dl class="related-promotion-list">
                        <dt>related promotion</dt>
                        <?php for ($i = 0; $i < count($arrPromotionIdx); $i++) { ?>
                            <dd><a href="/front/promotion_detail.php?idx=<?=$arrPromotionIdx[$i]?>">&gt; <?=$arrPromotionTitle[$i]?></a></dd>
                        <?php } ?>
                    </dl>
                    <?php }?>
                </div>
                <div class="made-rolling-banner with-btn-rolling">
                    <ul class="made-list" id="cash-made">
                        <?php for ( $i = 0; $i < count($arrRollingBannerImg); $i++ ) { ?>
                        <li><img src="/data/shopimages/vender/<?=$arrRollingBannerImg[$i]?>" alt=""></li>
                        <?php } ?>
                    </ul>
                </div>
            </div><!-- //.cash-made-wrap -->

            <div class="goods-sort-wrap">
            	<div class="select_left">
            		<div class="select small">
                    <span class="ctrl"><span class="arrow"></span></span>

                    <?
                        $sql  = "SELECT code_name FROM tblproductcode ";
                        $sql .= "WHERE code_a = '{$brand_cate}' AND code_b = '000' ";
                        $sql .= "AND group_code !='NO' AND display_list is NULL ";

                        list($cateName) = pmysql_fetch($sql);

                        $firstItem = "ALL";
                        if ( !empty($cate_code) ) {
                            $firstItem = $cateName;
                        }
                    ?>

                    <button type="button" class="my_value"><span><?=$firstItem?></span></button>
                    <ul class="a_list">
                        <li><a href="javascript:;" onclick="javascript:selectCategory('', '1', true);">ALL</a></li>
                        <li><a href="javascript:;" onclick="javascript:selectCategory('<?=$brand_cate?>', '1', true);"><?=$cateName?></a></li>
                    </ul>
            		</div>

                    <?php if( $cate_code_a != "000" && $cate_code_a != "" ) { ?>
            		<div class="select small" id="cate_list_div_2">
                    <span class="ctrl"><span class="arrow"></span></span>
                    <button type="button" class="my_value"><span id="cate_list_name_2">ALL</span></button>
                    <ul class="a_list" id="cate_list_2">
                    </ul>
            		</div>
                    <?php } ?>

                    <?php if( $cate_code_b != "000" && $cate_code_b != "" ) { ?>
            		<div class="select small" id="cate_list_div_3">
                    <span class="ctrl"><span class="arrow"></span></span>
                    <button type="button" class="my_value"><span id="cate_list_name_3">ALL</span></button>
                    <ul class="a_list" id="cate_list_3">
                    </ul>
            		</div>
                    <?php } ?>

                    <?php if( $cate_code_c != "000" && $cate_code_c != "" ) { ?>
            		<div class="select small" id="cate_list_div_4" style="display:none;">
                    <span class="ctrl"><span class="arrow"></span></span>
                    <button type="button" class="my_value"><span id="cate_list_name_4">ALL</span></button>
                    <ul class="a_list" id="cate_list_4">
                    </ul>
            		</div>
                    <?php } ?>

                </div>
            	<div class="select small">
                    <span class="ctrl"><span class="arrow"></span></span>

                    <?php
                        $firstItemName = "NEW";
                        if( $sort == 'order' )          { $firstItemName = "NEW"; }
                        elseif( $sort == 'best' )       { $firstItemName = "BEST"; }
                        elseif( $sort == 'sale' )       { $firstItemName = "SALE"; }
                        elseif( $sort == 'rcnt_desc' )  { $firstItemName = "REVIEW"; }
                        elseif( $sort == 'price' )      { $firstItemName = "LOW PRICE"; }
                        elseif( $sort == 'price_desc' ) { $firstItemName = "HIGH PRICE"; }
/*
                        elseif( $sort == 'price' )      { $firstItemName = "PRICE↓"; }
                        elseif( $sort == 'price_desc' ) { $firstItemName = "PRICE↑"; }
*/
                    ?>

                    <button type="button" class="my_value"><span><?=$firstItemName?></span></button>
                    <ul class="a_list">
                        <li><a href="javascript:;" onClick="javascript:ChangeSort('order')">NEW</a></li>
                        <li><a href="javascript:;" onClick="javascript:ChangeSort('best')">BEST</a></li>
                        <li><a href="javascript:;" onClick="javascript:ChangeSort('sale')">SALE</a></li>
                        <li><a href="javascript:;" onClick="javascript:ChangeSort('rcnt_desc')">REVIEW</a></li>
                        <li><a href="javascript:;" onClick="javascript:ChangeSort('price')">LOW PRICE</a></li>
                        <li><a href="javascript:;" onClick="javascript:ChangeSort('price_desc')">HIGH PRICE</a></li>
                    </ul>
            	</div>
                <div class="search-box-def hide">
                    <form onSubmit="return false;">
                        <fieldset>
                            <legend>상품검색어 입력</legend>
                            <input type="text" title="검색어 입력자리" name="keyword" value="<?=$search_word?>" />
                            <button type="submit" onClick="javascript:SearchPage();">검색하기</button>
                        </fieldset>
                    </form>
                </div>
                <div class="view-ea">
                </div>
            </div><!-- //.goods-sort-wrap -->

            <ul class="goods-list">
                <?=$arrProd[0]?>
            </ul><!-- //.goods-list -->

            <div class="list-paginate-wrap">
                <div class="list-paginate">
                <?php
                    if( $paging->pagecount > 1 ){
                        echo $paging->a_prev_page.$paging->print_page.$paging->a_next_page;
                    }
                ?>
                </div>
            </div><!-- //.list-paginate-wrap -->


        </div><!-- //.containerBody -->
    </div><!-- //contents -->

<?php
}
?>

<script type="text/javascript">
function GoPage(block,gotopage) {
	document.form2.block.value=block;
	document.form2.gotopage.value=gotopage;
	document.form2.submit();
}

function SearchPage() {
    var keywordVal = $("input[name='keyword']").val().trim();

    if ( keywordVal == "" ) {
        alert("검색어를 입력해 주세요.");
        $("input[name='keyword']").val("").focus();
        return false;
    }

    document.form2.search_word.value = keywordVal;
	document.form2.submit();
}

function changeCategory(obj) {
    document.form2.cate_code.value= $(obj).val();
    document.form2.submit();
}

function ChangeSort(val,type) {
    if(type)document.form2.listnum.value=type;
    document.form2.block.value="";
    document.form2.gotopage.value="";
    document.form2.sort.value=val;
    document.form2.submit();
}

function ChangeSort2(obj) {
    document.form2.block.value="";
    document.form2.gotopage.value="";
    document.form2.sort.value=$(obj).val();
    document.form2.submit();
}

function ChangeListnum(val) {
    document.form2.block.value="";
    document.form2.gotopage.value="";
    document.form2.listnum.value=val;
    document.form2.submit();
}

function selectCategory(cate_code, depth, isSubmit) {

    if ( isSubmit === true ) {
        document.form2.cate_code.value= cate_code;
        document.form2.submit();
    } else {
        depth = parseInt(depth);

        var params = {
            cate_code : cate_code,
            depth : depth
        };

        $.ajax({
            type: "get",
            url: "/front/ajax_get_category_list.php",
            data: params
        }).success(function ( result ) {
            if ( result != "" ) {
                arrResult = result.split("||");

                $("#cate_list_" + (depth+1)).html(arrResult[1]);

                if ( arrResult[0] != "" ) {
                    $("#cate_list_name_" + (depth+1)).html(arrResult[0]);
                } else {
                    $("#cate_list_name_" + (depth+1)).html("ALL");
                }

                if ( arrResult[1] == "" ) {
                    $("#cate_list_div_" + (depth+1)).hide();
                } else {
                    $("#cate_list_div_" + (depth+1)).show();
                }

            }
        }).fail(function () {
            //alert('다시 시도해 주세요.');
        });
    }
}

$(document).ready(function() {
    <?if ( !$isMobile ) { ?>
        <?if ($cate_code_a != "000") { ?>
        selectCategory('<?=$cate_code?>', 1, false);
        <?} ?>

        <?if ($cate_code_b != "000") { ?>
        selectCategory('<?=$cate_code?>', 2, false);
        <?} ?>

        <?if ($cate_code_c != "000") { ?>
        selectCategory('<?=$cate_code?>', 3, false);
        <?} ?>
    <?} ?>
});

</script>

<form name=form2 method=get action="<?=$_SERVER['PHP_SELF']?>">
    <input type=hidden name=listnum value="<?=$listnum?>">
    <input type=hidden name=sort value="<?=$sort?>">
    <input type=hidden name=block value="<?=$block?>">
    <input type=hidden name=gotopage value="<?=$gotopage?>">
    <input type=hidden name=bridx value="<?=$bridx?>">
    <input type=hidden name=vender value="<?=$venderIdx?>">
    <input type=hidden name=search_word value="<?=$search_word?>">
    <input type=hidden name=cate_code value="<?=$cate_code?>">
</form>
