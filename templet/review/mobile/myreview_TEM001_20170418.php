<?php
// =========================================================================
// 상단 리뷰 배너
// =========================================================================
$sql  = "SELECT banner_target, banner_link, banner_img_m FROM tblmainbannerimg ";
$sql .= "WHERE banner_no = 94 and banner_hidden='1' ORDER BY banner_sort LIMIT 1";
list($banner_target, $banner_link, $banner_img)=pmysql_fetch($sql);

$banner_html = '';
if ( !empty($banner_link) ) {
    $banner_html .= '<a class="bth-review-banner" href="' . $banner_link . '" target="' . $banner_target . '">';
}
$banner_html .= '<img src="' . getProductImage($Dir.DataDir.'shopimages/mainbanner/', $banner_img) . '" alt="">';
if ( !empty($banner_link) ) {
    $banner_html .= '</a>';
}

$tebmenu  = $_REQUEST['tebmenu'];
include_once($Dir."conf/config.ap_point.php");
?>


<!-- <div class="sub-title">
    <h2>상품리뷰</h2>
    <a class="btn-prev" href="mypage.php"><img src="./static/img/btn/btn_page_prev.png" alt="이전 페이지"></a>
</div> -->

<section class="top_title_wrap">
	<h2 class="page_local">
		<a href="<?=$Dir.MDir?>mypage.php" class="prev"></a>
		<span>상품리뷰</span>
		<a href="/m/shop.php" class="home"></a>
	</h2>
</section>

<div class="mypage_sub">

	<!-- [D] 2016. 퍼블 작업 -->
	<ul class="tabmenu_cancellist clear">
		<li class="<?=$tebmenu == ''?'on':''?>" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?tebmenu='">리뷰작성</li>
		<li class="<?=$tebmenu == 'on'?'on':''?>" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>?tebmenu=on'">작성완료</li>
	</ul>

	<div class="select_sorting clear">
		<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
			<input type="hidden" name="date1" id="" value="<?=$strDate1?>">
		    <input type="hidden" name="date2" id="" value="<?=$strDate2?>">
			<ul class="category_faq clear">
				<li <?if($day_division == '1MONTH'){?>class="on"<?}?>><button type="button" onClick = "GoSearch('1MONTH', this)">1개월</button></li>
				<li <?if($day_division == '3MONTH'){?>class="on"<?}?>><button type="button" onClick = "GoSearch('3MONTH', this)">3개월</button></li>
				<li <?if($day_division == '6MONTH'){?>class="on"<?}?>><button type="button" onClick = "GoSearch('6MONTH', this)">6개월</button></li>
				<li <?if($day_division == '12MONTH'){?>class="on"<?}?>><button type="button" onClick = "GoSearch('12MONTH', this)">12개월</button></li>
			</ul>
		</form>
	</div>

	<div class="review_benefit">
		<ul class="clear">
			<li>상품 리뷰<br> 작성 시 <strong class="point-color"><?=number_format($pointSet['textr']['point'])?>AP 지급</strong></li>
			<li>포토상품 리뷰<br> 작성 시 <strong class="point-color"><?=number_format($pointSet['photo']['point'])?>AP 지급</strong></li>
			<li>베스트상품 리뷰<br> 채택 시 <strong class="point-color"><?=number_format($pointSet['best']['point'])?>AP 지급</strong></li>
		</ul>
	</div>

	<?php
		// ======================================================================
		// 리뷰작성 리스트
		// ======================================================================

		$num_rows = pmysql_num_rows($result);
	?>
	<?if($tebmenu == ""){ ?>
	<div class="idx-content on">
	<?}else{ ?>
	<div class="idx-content">
	<?} ?>
	<?php	
	if ( $num_rows > 0 ) {
	?>

		<div class="<?php if ( $num_rows > 0 ) echo "wrap_review_list"; ?>">
			<ul class="reivew-list" id="product_review_write">

			<?
				$cnt = 0;
				while ( $row = pmysql_fetch_object($result) ) {
					// 상품 정보
					$sub_sql  = "SELECT a.*, b.brandname ";
					$sub_sql .= "FROM tblproduct a LEFT JOIN tblproductbrand b ON a.brand = b.bridx ";
					$sub_sql .= "WHERE a.productcode = '" . $row->productcode . "' ";
					$sub_row = pmysql_fetch_object(pmysql_query($sub_sql));

					// 옵션명 리스트
					$arrOpt1 = array();
					if ( !empty($row->opt1_name) ) {
						$arrOpt1 = explode("@#", $row->opt1_name);
					}

					// 옵션값 리스트
					$arrOpt2 = array();
					if ( !empty($row->opt2_name) ) {
						$arrOpt2 = explode(chr(30), $row->opt2_name);
					}

					// 옵션 정보
					$arrOptions = array();
					for ( $i = 0; $i < count($arrOpt1); $i++ ) {
						if ( $arrOpt1[$i] && $arrOpt2[$i] ) {
							array_push($arrOptions, $arrOpt1[$i] . " : " . $arrOpt2[$i]);
						}
					}

					// 수량
					if ( !empty($row->quantity) ) {
						array_push($arrOptions, "수량 : " . number_format($row->quantity) . "개");
					}

					// 주문일
					$order_date = substr($row->regdt, 0, 4) . "-" . substr($row->regdt, 4, 2) . "-" . substr($row->regdt, 6, 2);
					if ( empty($row->regdt) ) {
						$order_date = substr($row->ordercode, 0, 4) . "-" . substr($row->ordercode, 4, 2) . "-" . substr($row->ordercode, 6, 2);
					}

					$consumer_class = "";
					if ( $sub_row->consumerprice <= 0 || $sub_row->consumerprice == $sub_row->sellprice ){
						$consumer_class = "hide";
					}
					
					$orderDate = date("Y-m-d",strtotime($row->date));
			?>

				<p class="date_order">주문날짜 : <?=$orderDate ?></p>
				<div class="box_mylist">
					<div class="content">
						<a href="../m/productdetail.php?productcode=<?=$row->productcode?>">
							<figure class="mypage_goods">
								<div class="img"><img src="<?=getProductImage($Dir.DataDir.'shopimages/product/',$sub_row->tinyimage)?>" alt="주문상품 이미지"></div>
								<figcaption>
									<p class="brand">[<?=$sub_row->brandname?>]</p>
									<p class="name"><?=$sub_row->productname?></p>
									<p class="price"><span class="point-color"><?=number_format($sub_row->sellprice)?>원</span></p>
								</figcaption>
							</figure>
						</a>
						<div class="btnwrap">
							<ul class="ea1"><!-- [D] 버튼이 한개인 경우 ul에 ea1 클래스 -->
								<li><button type="button" class="btn-def light" onclick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>','<?=$row->num?>');">리뷰작성</button></li>
							</ul>
						</div>
					</div>
				</div>

				<!-- <li>
					<div class="item-info-wrap">
						<p class="thumb"><a href="../m/productdetail.php?productcode=<?=$row->productcode?>"><img src="<?=getProductImage($Dir.DataDir.'shopimages/product/',$sub_row->tinyimage)?>" alt=""></a></p>
						<div class="price-info">
							<span class="brand-nm"><?=$sub_row->brandname?></span>
							<span class="goods-nm"><?=$sub_row->productname?></span>
							<span class="price"><del class="<?=$consumer_class?>"><?=number_format($sub_row->consumerprice)?></del><strong><?=number_format($sub_row->sellprice)?></strong></span>
							<button class="btn-function" type="button" onClick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>');"><span>리뷰쓰기</span></button>
						</div>
					</div>
				</li> -->
			<?
					$cnt++;
				} // end of while
				pmysql_free_result($result);

				if ( $cnt == 0 ) {
					//echo "<tr><td colspan='4'>리뷰작성 가능한 내용이 없습니다.</td></tr>";
				}
			?>
			</ul>
		</div>

		<?php if ( $cnt > 0 ) { ?>
		<div class="list-paginate mt-20">
			<div class="box" id="product_review_write_paging">
				<?=$paging->a_prev_page . $paging->print_page . $paging->a_next_page?>
			</div>
		</div>
		<?php } ?>

		<?php } else { ?>
		<div class="none-ment margin">
			<p>작성 가능한 상품이 없습니다.</p>
		</div>
		<?php } ?>

	</div><!-- //리뷰작성 리스트 -->

    <?php
    // ======================================================================
    // 작성완료 리스트
    // ======================================================================

    $num_rows = pmysql_num_rows($result2);
    ?>	
    <?if($tebmenu == ""){ ?>
	<div class="idx-content">
	<?}else{ ?>
	<div class="idx-content <?=$tebmenu ?>">
	<?} ?>
    <?php

    if ( $num_rows > 0 ) {
?>
        <div class="<?php if ( $num_rows > 0 ) echo "wrap_review_list"; ?>">
		<ul class="reivew-list" id="product_writed_review">
<?
        $cnt = 0;
        while($row=pmysql_fetch_object($result2)) {

            // 상품 정보
            $sub_sql  = "SELECT *, b.brandname ";
            $sub_sql .= "FROM tblproduct a LEFT JOIN tblproductbrand b ON a.brand = b.bridx ";
            $sub_sql .= "WHERE a.productcode = '" . $row->productcode . "' ";
            $sub_row = pmysql_fetch_object(pmysql_query($sub_sql));

            // 옵션 정보
            $arrOptions = array();

            // 옵션1
            if ( !empty($sub_row->option1) && !empty($row->opt1_name) ) {
                array_push($arrOptions, $sub_row->option1 . " : " . $row->opt1_name);
            }

            // 옵션2
            if ( !empty($sub_row->option2) && !empty($row->opt2_name) ) {
                array_push($arrOptions, $sub_row->option2 . " : " . $row->opt2_name);
            }

            // 수량
            if ( !empty($row->quantity) ) {
                array_push($arrOptions, "수량 : " . number_format($row->quantity) . "개");
            }

            // 별점
            $marks = '';
            //for ($i = 0; $i < $row->marks; $i++) {
            for ($i = 0; $i < $row->quality+3; $i++) {
                $marks .= '<img src="./static/img/icon/icon_star.png" />';
            }

            // 주문일
            $order_date = $row->regdt;
            if ( empty($order_date) && !empty($row->ordercode) ) {
                $order_date = substr($row->ordercode, 0, 4) . "-" . substr($row->ordercode, 4, 2) . "-" . substr($row->ordercode, 6, 2);
            }

            // 작성일
            $write_date = substr($row->date, 0, 4) . "-" . substr($row->date, 4, 2) . "-" . substr($row->date, 6, 2);

            // 업로드 이미지 정보
            $arrUpFile = array();

            if ( !empty($row->upfile) ) { array_push($arrUpFile, $row->upfile); }
            if ( !empty($row->upfile2) ) { array_push($arrUpFile, $row->upfile2); }
            if ( !empty($row->upfile3) ) { array_push($arrUpFile, $row->upfile3); }
            if ( !empty($row->upfile4) ) { array_push($arrUpFile, $row->upfile4); }

            // 리뷰 제목/내용
            $review_title   = $row->subject;
            $review_file   = $row->upfile;
            $review_content = nl2br($row->content);
            //$review_mark = $row->marks * 20;
            $review_mark = ($row->quality+3) * 20;
            $review_best = $row->best_type;
            #파일여부경로
            $filepath = $Dir.DataDir."shopimages/review/";

            // 리뷰 댓글
            $comment_sql  = "SELECT * ";
            $comment_sql .= "FROM tblproductreview_comment ";
            $comment_sql .= "WHERE pnum = ".$row->num."";
            
            $coment_result = pmysql_query($comment_sql);
            while ( $comment_row = pmysql_fetch_array($coment_result) ) {
            	$arrReviewComment[] = $comment_row;
            }	
                        
    ?>
			<p class="date_order">주문날짜 : <?=$order_date?></p>
			<div class="box_mylist">
				<div class="content">
					<a href="../m/productdetail.php?productcode=<?=$row->productcode?>">
						<figure class="mypage_goods">
							<div class="img"><img src="<?=getProductImage($Dir.DataDir.'shopimages/product/',$sub_row->tinyimage)?>" alt="주문상품 이미지"></div>
							<figcaption>
								<p class="brand">[<?=$sub_row->brandname?>]</p>
								<p class="name"><?=$sub_row->productname?></p>
								<?if($sub_row->consumerprice != $sub_row->sellprice){ ?>
								<p class="price"><del><?=number_format($sub_row->consumerprice)?></del>  <span class="point-color"><?=number_format($sub_row->sellprice)?></span></p>
								<?}else{ ?>
								<p class="price"><span class="point-color"><?=number_format($sub_row->consumerprice)?></span></p>
								<?} ?>
							</figcaption>
						</figure>
					</a>
				</div>
			</div>
			<div class="review_read">
				<div class="title">
					<span class="comp-star star-score"><strong style="width:<?=$review_mark ?>%;">5점만점에 5점</strong></span>
					<p class="subject"><?=$review_title?></p>
					<?if( is_file($filepath.$review_file) ){ ?>
					<img src="static/img/icon/icon_photo.png" class="icon_photo" alt="photo"><!-- [D] 포토 리뷰인 경우 사진 아이콘 출력 -->
					<?} ?>
					<?if($review_best == "1"){ ?>
					<img src="static/img/icon/icon_best.png" class="icon_best" alt="best"><!-- [D] 베스트 리뷰인 경우 베스트 아이콘 출력 -->
					<?} ?>
				</div>
				<div class="content">
					<?=$review_content?>
					<?
						foreach ( $arrUpFile as $key => $val ) {
							echo "<img src='" . $Dir.DataDir."shopimages/review/" . $val . "' /> <br/>";
						}
						echo "<br/>";
					?>

					<div class="btn_area">
						<button class="btn-function" type="button" onClick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>', '<?=$row->num?>');"><span>수정</span></button>
						<button class="btn-function" type="button" onClick="javascript:delete_review('<?=$row->num?>');"><span>삭제</span></button>
					</div>
					<div class="reply_view">
					<!-- <?if(count($arrReviewComment) > 0){ 
						foreach( $arrReviewComment as $key=>$val ){
							$comment_date = date("Y-m-d",strtotime($val['regdt']));
						?>
							<div class="reply">
								<p class="date"><?=$comment_date ?></p>
								<p class="txt_reply"><?=$val['content']?></p>
								<div class="btn_area">
									<?if($_ShopInfo->getMemid() == $val['id']) {?>
									<button class="btn-function" type="button" onClick="delete_review_comment('<?=$val['no'] ?>','<?=$row->num?>');">삭제</button>
									<?} ?>
								</div>
							</div>
						<?} ?>
					<?} ?>-->
					</div>
					
				</div>
			</div>

			<!-- <li class="js-myreview-accordion">
				<div class="item-info-wrap vm js-review-item">
					<p class="thumb"><a href="../m/productdetail.php?productcode=<?=$row->productcode?>"><img src="<?=getProductImage($Dir.DataDir.'shopimages/product/',$sub_row->tinyimage)?>" alt=""></a></p>
					<div class="price-info">
						<div class="star-point">
							<?=$marks?>
							<span class="date"><?=$write_date?></span>
						</div>
						<div class="subject">
							<?=$review_title?>
						</div>

					</div>
				</div>
				<div class="reply-box js-review-content">
					<div class="content">
						<?=$review_content?>
						<?
							foreach ( $arrUpFile as $key => $val ) {
								echo "<img src='" . $Dir.DataDir."shopimages/review/" . $val . "' /> <br/>";
							}
							echo "<br/>";
						?>

						<div class="ta-r">
						<button class="btn-function" type="button" onClick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>', '<?=$row->num?>');"><span>수정</span></button>
						<button class="btn-function" type="button" onClick="javascript:delete_review('<?=$row->num?>');"><span>삭제</span></button>
						</div>
					</div>
				</div>
			</li>
 -->
    <?php
            $cnt++;
        }
    ?>
                 </ul>
        </div>

		<?php if ( $cnt > 0 ) { ?>
		<div class="list-paginate mt-20">
			<div class="box" id="product_writed_review_paging">
				<?=$paging2->a_prev_page . $paging2->print_page . $paging2->a_next_page?>
			</div>
		</div>
		<?php } ?>

    <?php
    } else {
    ?>
		<div class="none-ment margin">
			<p>작성 가능한 상품이 없습니다.</p>
		</div>
    <?php
    }
    ?>
	</div><!-- //작성완료 리스트 -->
	<!-- //[D] 2016. 퍼블 작업 -->


	<!-- [D] 기존 내용 hide -->
    <div class="js-tab-component hide">
        <div class="content-tab">
            <div class="js-menu-list">
                <div class="js-tab-line"></div>
                <ul>
                    <li class="js-tab-menu on"><a href="#"><span>작성 가능한 상품리뷰</span></a></li>
                    <li class="js-tab-menu"><a href="#"><span>작성한 상품리뷰</span></a></li>
                </ul>
            </div>
        </div>

        <?php
            // ======================================================================
            // 작성 가능한 상품 리뷰 리스트
            // ======================================================================

            $num_rows = pmysql_num_rows($result);
        ?>
        <div class="js-tab-content">
        <?php
            if ( $num_rows > 0 ) {
        ?>

            <div class="<?php if ( $num_rows > 0 ) echo "reivew-list-wrap"; ?>">
                <ul class="reivew-list">

                        <?
                            $cnt = 0;
                            while ( $row = pmysql_fetch_object($result) ) {
                                // 상품 정보
                                $sub_sql  = "SELECT *, b.brandname ";
                                $sub_sql .= "FROM tblproduct a LEFT JOIN tblproductbrand b ON a.brand = b.bridx ";
                                $sub_sql .= "WHERE a.productcode = '" . $row->productcode . "' ";
                                $sub_row = pmysql_fetch_object(pmysql_query($sub_sql));

                                // 옵션명 리스트
                                $arrOpt1 = array();
                                if ( !empty($row->opt1_name) ) {
                                    $arrOpt1 = explode("@#", $row->opt1_name);
                                }

                                // 옵션값 리스트
                                $arrOpt2 = array();
                                if ( !empty($row->opt2_name) ) {
                                    $arrOpt2 = explode(chr(30), $row->opt2_name);
                                }

                                // 옵션 정보
                                $arrOptions = array();
                                for ( $i = 0; $i < count($arrOpt1); $i++ ) {
                                    if ( $arrOpt1[$i] && $arrOpt2[$i] ) {
                                        array_push($arrOptions, $arrOpt1[$i] . " : " . $arrOpt2[$i]);
                                    }
                                }

                                // 수량
                                if ( !empty($row->quantity) ) {
                                    array_push($arrOptions, "수량 : " . number_format($row->quantity) . "개");
                                }

                                // 주문일
                                $order_date = substr($row->regdt, 0, 3) . "-" . substr($row->regdt, 4, 2) . "-" . substr($row->regdt, 6, 2);
                                if ( empty($row->regdt) ) {
                                    $order_date = substr($row->ordercode, 0, 3) . "-" . substr($row->ordercode, 4, 2) . "-" . substr($row->ordercode, 6, 2);
                                }

                                $consumer_class = "";
                                if ( $sub_row->consumerprice <= 0 || $sub_row->consumerprice == $sub_row->sellprice ){
                                    $consumer_class = "hide";
                                }
                        ?>

                            <li>
                                <div class="item-info-wrap">
                                    <p class="thumb"><a href="../m/productdetail.php?productcode=<?=$row->productcode?>"><img src="<?=getProductImage($Dir.DataDir.'shopimages/product/',$sub_row->tinyimage)?>" alt=""></a></p>
                                    <div class="price-info">
                                        <span class="brand-nm"><?=$sub_row->brandname?></span>
                                        <span class="goods-nm"><?=$sub_row->productname?></span>
                                        <span class="price"><del class="<?=$consumer_class?>"><?=number_format($sub_row->consumerprice)?></del><strong><?=number_format($sub_row->sellprice)?></strong></span>
                                        <button class="btn-function" type="button" onClick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>');"><span>리뷰쓰기</span></button>
                                    </div>
                                </div>
                            </li>
                        <?
                                $cnt++;
                            } // end of while
                            pmysql_free_result($result);

                            if ( $cnt == 0 ) {
                                //echo "<tr><td colspan='4'>리뷰작성 가능한 내용이 없습니다.</td></tr>";
                            }
                        ?>

                    </ul>
                </div>

                <?php if ( $cnt > 0 ) { ?>
                <div class="paginate">
                    <div class="box" id="product_review_write_paging">
                        <?=$paging->a_prev_page . $paging->print_page . $paging->a_next_page?>
                    </div>
                </div>
                <?php } ?>

            <?php } else { ?>
              <div class="none-ment margin">
                <p>작성 가능한 상품이 없습니다.</p>
              </div>
            <?php } ?>

        </div><!-- //작성가능한 상품리뷰 -->

    <?php
    // ======================================================================
    // 내가 작성한 상품 리뷰 리스트
    // ======================================================================

    $num_rows = pmysql_num_rows($result2);
    ?>
        <div class="js-tab-content">
    <?php

    if ( $num_rows > 0 ) {
?>
            <div class="<?php if ( $num_rows > 0 ) echo "reivew-list-wrap"; ?>">
                <ul class="reivew-list" id="product_writed_review">

<?
        $cnt = 0;
        while($row=pmysql_fetch_object($result2)) {

            // 상품 정보
            $sub_sql  = "SELECT *, b.brandname ";
            $sub_sql .= "FROM tblproduct a LEFT JOIN tblproductbrand b ON a.brand = b.bridx ";
            $sub_sql .= "WHERE a.productcode = '" . $row->productcode . "' ";
            $sub_row = pmysql_fetch_object(pmysql_query($sub_sql));

            // 옵션 정보
            $arrOptions = array();

            // 옵션1
            if ( !empty($sub_row->option1) && !empty($row->opt1_name) ) {
                array_push($arrOptions, $sub_row->option1 . " : " . $row->opt1_name);
            }

            // 옵션2
            if ( !empty($sub_row->option2) && !empty($row->opt2_name) ) {
                array_push($arrOptions, $sub_row->option2 . " : " . $row->opt2_name);
            }

            // 수량
            if ( !empty($row->quantity) ) {
                array_push($arrOptions, "수량 : " . number_format($row->quantity) . "개");
            }

            // 별점
            $marks = '';
            //for ($i = 0; $i < $row->marks; $i++) {
            for ($i = 0; $i < $row->quality+3; $i++) {
                $marks .= '<img src="./static/img/icon/icon_star.png" />';
            }

            // 주문일
            $order_date = $row->regdt;
            if ( empty($order_date) && !empty($row->ordercode) ) {
                $order_date = substr($row->ordercode, 0, 3) . "-" . substr($row->ordercode, 4, 2) . "-" . substr($row->ordercode, 6, 2);
            }

            // 작성일
            $write_date = substr($row->date, 0, 4) . "-" . substr($row->date, 4, 2) . "-" . substr($row->date, 6, 2);

            // 업로드 이미지 정보
            $arrUpFile = array();

            if ( !empty($row->upfile) ) { array_push($arrUpFile, $row->upfile); }
            if ( !empty($row->upfile2) ) { array_push($arrUpFile, $row->upfile2); }
            if ( !empty($row->upfile3) ) { array_push($arrUpFile, $row->upfile3); }
            if ( !empty($row->upfile4) ) { array_push($arrUpFile, $row->upfile4); }

            // 리뷰 제목/내용
            $review_title   = $row->subject;
            $review_content = nl2br($row->content);
    ?>
                        <li class="js-myreview-accordion">
                            <div class="item-info-wrap vm js-review-item">
                                <p class="thumb"><a href="../m/productdetail.php?productcode=<?=$row->productcode?>"><img src="<?=getProductImage($Dir.DataDir.'shopimages/product/',$sub_row->tinyimage)?>" alt=""></a></p>
                                <div class="price-info">
                                    <div class="star-point">
                                        <?=$marks?>
                                        <span class="date"><?=$write_date?></span>
                                    </div>
                                    <div class="subject">
                                        <?=$review_title?>
                                    </div>

                                </div>
                            </div>
                            <div class="reply-box js-review-content">
                                <div class="content">
                                    <?=$review_content?>
                                    <?
                                        foreach ( $arrUpFile as $key => $val ) {
                                            echo "<img src='" . $Dir.DataDir."shopimages/review/" . $val . "' /> <br/>";
                                        }
                                        echo "<br/>";
                                    ?>

                                    <div class="ta-r">
                                    <button class="btn-function" type="button" onClick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>', '<?=$row->num?>');"><span>수정</span></button>
                                    <button class="btn-function" type="button" onClick="javascript:delete_review('<?=$row->num?>');"><span>삭제</span></button>
                                    </div>
                                </div>
                            </div>
                        </li>

    <?php
            $cnt++;
        }
    ?>
                </ul>
            </div>

            <?php if ( $cnt > 0 ) { ?>
            <div class="paginate mb-20">
                <div class="box">
                    <?=$paging2->a_prev_page . $paging2->print_page . $paging2->a_next_page?>
                </div>
            </div>
            <?php } ?>

    <?php
    } else {
    ?>
              <div class="none-ment margin">
                <p>작성한 상품 리뷰가 없습니다.</p>
              </div>
    <?php
    }
    ?>

        </div><!-- //내가 작성한 상품리뷰 -->
    </div>
	<!-- //[D] 기존 내용 hide -->

</div><!-- //.mypage_sub -->

<form name=reviewWriteForm method="POST" action="mypage_review_write.php">
<input type="hidden" name="productcode" />
<input type="hidden" name="ordercode" />
<input type="hidden" name="productorder_idx" />
<input type="hidden" name="review_num" />
</form>

<form name=form2 method=GET action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
<input type=hidden name=ordgbn value="<?=$ordgbn?>">
<input type=hidden name=limitpage class = 'CLS_limit_page_val' value="<?=$limitpage?>">
<input type=hidden name=s_year value="<?=$s_year?>">
<input type=hidden name=s_month value="<?=$s_month?>">
<input type=hidden name=s_day value="<?=$s_day?>">
<input type=hidden name=e_year value="<?=$e_year?>">
<input type=hidden name=e_month value="<?=$e_month?>">
<input type=hidden name=e_day value="<?=$e_day?>">
<input type=hidden name=day_division value="<?=$day_division?>">
<input type=hidden name=tebmenu value="<?=$tebmenu?>">
</form>

<SCRIPT LANGUAGE="JavaScript">


var listnum = "<?=$listnum?>";

function send_review_write_page(productcode, ordercode, productorder_idx, review_num) {
    if ( review_num == undefined ) {
        review_num = 0;
    }

    var frm = document.reviewWriteForm;

    frm.productcode.value = productcode;
    frm.ordercode.value = ordercode;
    frm.productorder_idx.value = productorder_idx;
    frm.review_num.value = review_num;
    frm.submit();
}

function delete_review(review_num) {
    if ( confirm("삭제하시겠습니까?") ) {
        $.ajax({
            type        : "GET",
            url         : "/front/ajax_delete_review.php",
            contentType : "application/x-www-form-urlencoded; charset=UTF-8",
            data        : { review_num : review_num }
        }).done(function ( data ) {
            if ( data === "SUCCESS" ) {
                alert("리뷰가 삭제되었습니다.");
                location.href = "/m/mypage_review.php";
            }
        });
    }
}

//댓글 삭제
/*function delete_review_comment(review_comment_num, review_num) {

    if ( review_comment_num != "" ) {
        if ( confirm("댓글을 삭제하시겠습니까?") ) {
            $.ajax({
                type        : "GET",
                url         : "../front/ajax_delete_review_comment.php",
                data        : { review_comment_num : review_comment_num }
            }).done(function ( result ) {
                if ( result == "SUCCESS" ) {
                    alert("댓글이 삭제되었습니다.");

                    GoPageAjax2(0, 0, review_num);
                } else {
                    alert("댓글이 삭제가 실패했습니다.");
                }
            });
        }
    }
}*/

function GoPageAjax1(block,gotopage) {
    var listnum = 3;
    var s_date  = "";
    var e_date  = "";
    var mode    = "1";

    var params = {
        block : block,
        gotopage : gotopage,
        listnum : listnum,
        mode : mode,
        start_date : s_date,
        end_date : e_date,
        isMobile : "1",
    };

    $.ajax({
        type        : "GET",
        url         : "/front/ajax_get_myreview_list.php",
        contentType : "application/x-www-form-urlencoded; charset=UTF-8",
        data        : params
    }).done(function ( data ) {
        var arrData = data.split("|||");

        $("#product_review_write").html(arrData[0]);
        $("#product_review_write_paging").html(arrData[1]);


//        $("#product_review_write_count").html("Total(" + arrData[2] + ")");
    });
}

function GoPageAjax2(block,gotopage) {
    var listnum = 3;
    var s_date  = "";
    var e_date  = "";
    var mode    = "2";

    var params = {
        block : block,
        gotopage : gotopage,
        listnum : listnum,
        mode : mode,
        start_date : s_date,
        end_date : e_date,
        isMobile : "1"/*,
        review_num : review_num*/
    };

    $.ajax({
        type        : "GET",
        url         : "/front/ajax_get_myreview_list.php",
        contentType : "application/x-www-form-urlencoded; charset=UTF-8",
        data        : params
    }).done(function ( data ) {
        var arrData = data.split("|||");
		$("#product_writed_review").html(arrData[0]);
        $("#product_writed_review_paging").html(arrData[1]);
    	var reviewOn = $('.review_read');
    	reviewOn.on('click',function(e){
    		var idx = reviewOn.index($(this));

    		if(reviewOn.attr("class") == "review_read on"){
        		reviewOn.eq(idx).removeClass('on');
    		}else{
        		reviewOn.eq(idx).addClass('on');
    		}		
    	});
        
//         $(".js-myreview-accordion").accordion({ menu:".js-review-item", content:".js-review-content" });

//        $("#product_writed_review_count").html("Total(" + arrData[2] + ")");
    });
}

var NowTime=parseInt(<?=time()?>);
function GoSearch(gbn, obj) {

	var s_date = new Date(NowTime*1000);
	switch(gbn) {
		case "1MONTH":
			s_date.setMonth(s_date.getMonth()-1);
			break;
		case "3MONTH":
			s_date.setMonth(s_date.getMonth()-3);
			break;
		case "6MONTH":
			s_date.setMonth(s_date.getMonth()-6);
			break;
		case "9MONTH":
			s_date.setMonth(s_date.getMonth()-9);
			break;
		case "12MONTH":
			s_date.setFullYear(s_date.getFullYear()-1);
			break;
		default :
			break;
	}
	e_date = new Date(NowTime*1000);

	//======== 시작 날짜 셋팅 =========//
	var s_month_str = str_pad_right(parseInt(s_date.getMonth())+1);
	var s_date_str = str_pad_right(parseInt(s_date.getDate()));
	
	// 폼에 셋팅
	document.form2.s_year.value = s_date.getFullYear();
	document.form2.s_month.value = s_month_str;
	document.form2.s_day.value = s_date_str;
	//날짜 칸에 셋팅
	var s_date_full = s_date.getFullYear()+"-"+s_month_str+"-"+s_date_str;
	document.form1.date1.value=s_date_full;
	//======== //시작 날짜 셋팅 =========//
	
	//======== 끝 날짜 셋팅 =========//
	var e_month_str = str_pad_right(parseInt(e_date.getMonth())+1);
	var e_date_str = str_pad_right(parseInt(e_date.getDate()));

	// 폼에 셋팅
	document.form2.e_year.value = e_date.getFullYear();
	document.form2.e_month.value = e_month_str;
	document.form2.e_day.value = e_date_str;

	document.form2.day_division.value = gbn;
	
	//날짜 칸에 셋팅
	var e_date_full = e_date.getFullYear()+"-"+e_month_str+"-"+e_date_str;
	document.form1.date2.value=e_date_full;
	//======== //끝 날짜 셋팅 =========//

    document.form2.submit();
}

function str_pad_right(num){
	
	var str = "";
	if(num<10){
		str = "0"+num;
	}else{
		str = num;
	}
	return str;
}
</SCRIPT>
