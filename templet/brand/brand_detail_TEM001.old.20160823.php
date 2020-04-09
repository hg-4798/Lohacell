<?php
$bridx          = $_REQUEST['bridx'];

if ( $bridx === "" ) {
    echo "<script type='text/javascript'>alert('해당 브랜드가 존재하지 않습니다.'); history.go(-1);</script>";
    exit;
}

$sort           = $_REQUEST['sort']?:"order";
//$sort           = $_REQUEST['sort'];

if ( $isMobile ) {
    $listnum        = $_REQUEST['listnum']?:10;
} else {
    $listnum        = $_REQUEST['listnum']?:25;
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

$sql  = "SELECT * FROM tblproductbrand WHERE bridx = {$bridx} ";
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
$sql .= "WHERE vender = {$venderIdx} ";
$row  = pmysql_fetch_object(pmysql_query($sql));

$brand_desc = $row->description;

// 롤링할 이미지
$arrRollingBannerImg = array();
for ( $i = 1; $i <= 10; $i++ ) {
    $varName = "b_img" . $i;

    if ( !empty($row->$varName) ) {
        array_push($arrRollingBannerImg, $row->$varName);
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
$sql .= "WHERE display_type in ('A', 'P') and hidden = '1' AND current_date <= end_date ";
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

if ( $tmp_sort[0] == "rcnt" ) {
    // REVIEW
    $prod_sql .= "ORDER BY a.review_cnt ".$tmp_sort[1];
} else if ( $tmp_sort[0]=="price" ) {
    // PRICE
    $prod_sql .= "ORDER BY a.sellprice ".$tmp_sort[1];
} else if ( $tmp_sort[0]=="best" ) {
    // BEST
    $prod_sql .= "ORDER BY a.vcnt desc";
} else if ( $tmp_sort[0]=="sale" ) {
    // SALE (정가 - 판매가 값이 큰순으로 정렬)
    $prod_sql .= "ORDER BY diffprice desc";
} else {
    // NEW
    $prod_sql .= "ORDER BY b.start_no asc";
}
$prod_sql .= ", a.regdate desc, a.modifydate desc";

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


<!-- [D] 20160822 브랜드 퍼블리싱 추가 -->
<main id="contents">
	<div class="goods-list">
		<!-- 상품리스트 - 사이드바 -->
		<div class="goods-list-sidebar">
			<div class="category">
				<h2>MEN</h2>
				<nav>
					<!-- (D) 선택된 li에 class="on" title="선택됨"을 추가합니다. -->
					<ul class="category-main">
						<li>
							<a href="javascript:void(0);">라이프스타일</a>
							<ul class="category-sub">
								<li class="on" title="선택됨"><a href="javascript:void(0);">캔버스/슬립온</a></li>
								<li><a href="javascript:void(0);">스니커즈</a></li>
								<li><a href="javascript:void(0);">하이탑</a></li>
							</ul>
						</li>
						<li>
							<a href="javascript:void(0);">스포츠</a>
							<ul class="category-sub">
								<li><a href="javascript:void(0);">러닝&#38;워킹</a></li>
								<li><a href="javascript:void(0);">트레이닝</a></li>
								<li><a href="javascript:void(0);">퍼포먼스 슈즈</a></li>
							</ul>
						</li>
						<li>
							<a href="javascript:void(0);">시즌</a>
							<ul class="category-sub">
								<li><a href="javascript:void(0);">샌달&#38;슬라이드</a></li>
								<li><a href="javascript:void(0);">워커&#38;부츠</a></li>
							</ul>
						</li>
						<li><a href="javascript:void(0);">테니스</a></li>
						<li><a href="javascript:void(0);">액세서리&#38;용품</a></li>
					</ul>
				</nav>
			</div>
			<div class="sorting">
				<section class="sorting-brand on">
					<h6>BRAND</h6>
					<ul>
						<li><label><input type="checkbox"><span>나이키</span></label></li>
						<li><label><input type="checkbox"><span>아디다스</span></label></li>
						<li><label><input type="checkbox"><span>아디다스 네오</span></label></li>
						<li><label><input type="checkbox"><span>리복</span></label></li>
						<li><label><input type="checkbox"><span>푸마</span></label></li>
						<li><label><input type="checkbox"><span>버켄스탁</span></label></li>
						<li><label><input type="checkbox"><span>스케쳐스</span></label></li>
						<li><label><input type="checkbox"><span>라코스테</span></label></li>
						<li><label><input type="checkbox"><span>캔버스</span></label></li>
						<li><label><input type="checkbox"><span>수페르크록스</span></label></li>
						<li><label><input type="checkbox"><span>블루마운틴</span></label></li>
						<li><label><input type="checkbox"><span>슈마커 키즈</span></label></li>
						<li><label><input type="checkbox"><span>케즈</span></label></li>
						<li><label><input type="checkbox"><span>러그즈</span></label></li>
						<li><label><input type="checkbox"><span>락스프링</span></label></li>
						<li><label><input type="checkbox"><span>닥터마틴</span></label></li>
						<li><label><input type="checkbox"><span>디씨슈즈</span></label></li>
						<li><label><input type="checkbox"><span>플로시</span></label></li>
					</ul>
					<a class="btn-toggle" href="javascript:void(0);" title="접어놓기"><span>BRAND 정렬</span></a>
				</section>
				<section class="sorting-size on">
					<h6>SIZE</h6>
					<ul>
						<li><label><input type="checkbox"><span>220</span></label></li>
						<li><label><input type="checkbox"><span>225</span></label></li>
						<li><label><input type="checkbox"><span>230</span></label></li>
						<li><label><input type="checkbox"><span>235</span></label></li>
						<li><label><input type="checkbox"><span>240</span></label></li>
						<li><label><input type="checkbox"><span>245</span></label></li>
						<li><label><input type="checkbox"><span>250</span></label></li>
						<li><label><input type="checkbox"><span>255</span></label></li>
						<li><label><input type="checkbox"><span>260</span></label></li>
						<li><label><input type="checkbox"><span>265</span></label></li>
						<li><label><input type="checkbox"><span>270</span></label></li>
						<li><label><input type="checkbox"><span>275</span></label></li>
						<li><label><input type="checkbox"><span>280</span></label></li>
						<li><label><input type="checkbox"><span>285</span></label></li>
						<li><label><input type="checkbox"><span>290</span></label></li>
						<li><label><input type="checkbox"><span>300</span></label></li>
					</ul>
					<a class="btn-toggle" href="javascript:void(0);" title="접어놓기"><span>SIZE 정렬</span></a>
				</section>
				<section class="sorting-color on">
					<h6>COLOR</h6>
					<!-- (D) 투명, 흰색 등 색이 밝아 체크 색상이 검은색인 것은 li에 class="light" 을 추가합니다. -->
					<ul>
						<li class="light"><label><input type="checkbox"><span><img src="../static/img/test/@test_color1.png" alt="clear"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color2.png" alt="black"></span></label></li>
						<li class="light"><label><input type="checkbox"><span><img src="../static/img/test/@test_color3.png" alt="white"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color4.png" alt="red"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color5.png" alt="orange"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color6.png" alt="yellow"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color7.png" alt="olive"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color8.png" alt="sky blue"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color9.png" alt="purple"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color10.png" alt="pink"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color11.png" alt="brown"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color12.png" alt="dark gray"></span></label></li>
						<li class="light"><label><input type="checkbox"><span><img src="../static/img/test/@test_color13.png" alt="gray"></span></label></li>
						<li class="light"><label><input type="checkbox"><span><img src="../static/img/test/@test_color14.png" alt="gold"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color15.png" alt="light gray"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color16.png" alt="blue"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color17.png" alt="green"></span></label></li>
						<li><label><input type="checkbox"><span><img src="../static/img/test/@test_color18.png" alt="many colors"></span></label></li>
					</ul>
					<a class="btn-toggle" href="javascript:void(0);" title="접어놓기"><span>COLOR 정렬</span></a>
				</section>
				<button class="btn-submit" type="submit"><span>선택조건 검색</span></button>
			</div>
		</div>
		<!-- // 상품리스트 - 사이드바 -->


		<!-- 브랜드 비쥬얼 영역 -->
		<div class="brand_visual mb-20">
			<ul>
				<li><a href="#"><img src="../static/img/test/@test_brand_banner.jpg" alt=""></a></li>
				<li><a href="#"><img src="../static/img/test/@test_brand_banner.jpg" alt=""></a></li>
				<li><a href="#"><img src="../static/img/test/@test_brand_banner.jpg" alt=""></a></li>
			</ul>
		</div>

		<!-- 상품리스트 - 상품 -->
		<section class="goods-list-item">
			<h3>캔버스/슬립온<span class="num">(120)</span></h3>
			<div class="comp-select sorting">
				<select title="정렬">
					<option value="0">추천순</option>
					<option value="1">인기순</option>
					<option value="2">상품평순</option>
					<option value="3">좋아요순</option>
				</select>
			</div>
			<!--
				(D) 별점은 .comp-star > strong에 width:n%로 넣어줍니다.
				좋아요를 선택하면 버튼에 class="on" title="선택됨"을 추가합니다.
				페이지 변경할 때 페이지 리로드가 아닌 ajax로 연동하거나,
				더보기 등으로 리스트 하단에 상품이 추가될 경우,
				컬러 썸네일 스크립트 적용을 위해 내용 변경 후 color_slider_control() 함수를 호출해주세요.
			-->
			<ul class="comp-goods item-list">
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list5.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list6.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list7.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list8.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list9.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list1.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list2.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list3.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
						</figcaption>
					</figure>
				</li>
				<li>
					<figure>
						<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list4.jpg" alt=""></a>
						<div class="color-thumb">
							<ul>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
								<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
							</ul>
						</div>
						<figcaption>
							<a href="javascript:void(0);">
								<strong class="brand">ADIDAS</strong>
								<p class="title">Adidas Gazelle 아디다스 가젤</p>
								<span class="price"><strong>100,000원</strong></span>
								<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
							</a>
							<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
						</figcaption>
					</figure>
				</li>
			</ul>
			<div class="list-paginate mt-20">
				<span class="border_wrap">
					<a href="#" class="prev-all">처음으로</a>
					<a href="#" class="prev">이전</a>
				</span>
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
				<span class="border_wrap">
					<a href="#" class="next">다음</a>
					<a href="#" class="next-all">끝으로</a>
				</span>
			</div>
		</section>
		<!-- 상품리스트 - 상품 -->
	</div>
</main>
<!-- // [D] 20160822 브랜드 퍼블리싱 추가 -->

<div id="contents" class="hide">
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
                    <button <? if( $listnum == 25 ) { echo 'class="on"'; } ?> type="button" onclick="javascript:ChangeListnum( 25 )">25</button>
                    <button <? if( $listnum == 50 ) { echo 'class="on"'; } ?> type="button" onclick="javascript:ChangeListnum( 50 )">50</button>
                    <button <? if( $listnum == 75 ) { echo 'class="on"'; } ?> type="button" onclick="javascript:ChangeListnum( 75 )">75</button>
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

