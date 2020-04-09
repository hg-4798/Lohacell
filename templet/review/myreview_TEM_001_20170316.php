<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

?>
<style>
/** 달력 팝업 **/
.calendar_pop_wrap {position:relative; background-color:#FFF;}
.calendar_pop_wrap .calendar_con {position:absolute; top:0px; left:0px;width:247px; padding:10px; border:1px solid #b8b8b8; background-color:#FFF;}
.calendar_pop_wrap .calendar_con .month_select { text-align:center; background-color:#FFF; padding-bottom:10px;}
.calendar_pop_wrap .calendar_con .day {clear:both;border-left:1px solid #e4e4e4;}
.calendar_pop_wrap .calendar_con .day th {background:url('../admin/img/common/calendar_top_bg.gif') repeat-x; width:34px; font-size:11px; border-top:1px solid #9d9d9d;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; padding:6px 0px 4px;}
.calendar_pop_wrap .calendar_con .day th.sun {color:#ff0012;}
.calendar_pop_wrap .calendar_con .day td {border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; background-color:#FFF; width:34px;  font-size:11px; text-align:center; font-family:tahoma;}
.calendar_pop_wrap .calendar_con .day td a {color:#35353f; display:block; padding:2px 0px;}
.calendar_pop_wrap .calendar_con .day td a:hover {font-weight:bold; color:#ff6000; text-decoration:none;}
.calendar_pop_wrap .calendar_con .day td.pre_month a {color:#fff; display:block; padding:3px 0px;}
.calendar_pop_wrap .calendar_con .day td.pre_month a:hover {text-decoration:none; color:#fff;}
.calendar_pop_wrap .calendar_con .day td.today {background-color:#52a3e7; }
.calendar_pop_wrap .calendar_con .day td.today a {color:#fff;}
.calendar_pop_wrap .calendar_con .close_btn {text-align:center; padding-top:10px;}
</style>

<?

if ( $isMobile ) {
    $s_curdate = date("YmdHis",strtotime("$s_year-$s_month-$s_day"));
    $e_curdate = date("Ymd235959",$etime);
} else {
	$s_curtime=strtotime("$s_year-$s_month-$s_day");
	$s_curdate=date("Ymd",$s_curtime)."000000";
	$e_curtime=strtotime("$e_year-$e_month-$e_day");
	$e_curdate=date("Ymd",$e_curtime)."999999";
}

# ====================================================================================================================================
# 작성하지 않은 리뷰 리스트
# 현재는 배송중부터 작성 가능하지만, 구매확정 이후 시점으로 변경해야 됨.2016-08-09 jhjeong
# ====================================================================================================================================
$sql  = "SELECT tblResult.* ";
$sql .= "FROM ";
$sql .= "   ( ";
$sql .= "       SELECT  a.*, b.regdt  ";
$sql .= "       FROM    tblorderproduct a LEFT JOIN tblorderinfo b ON a.ordercode = b.ordercode ";
$sql .= "       WHERE   b.id = '" . $_ShopInfo->getMemid()  . "' ";
//$sql .= "       AND     ( a.op_step = 3 OR a.op_step = 4 ) ";     // 상태를 상품별로 변경 (2016.07.13 - 김재수 * 결제완료에서 리뷰쓰기를 했을경우 구매확정이 된 오류)
$sql .= "       AND     (a.op_step = 4 and a.order_conf = '1') ";   // 구매 확정 이후에 리뷰 작성하게 수정 2016-08-12
$sql .= "       AND     ( b.regdt >= '{$s_curdate}' AND b.regdt <= '{$e_curdate}' ) ";
$sql .= "       ORDER BY a.idx DESC ";
$sql .= "   ) AS tblResult LEFT ";
$sql .= "   OUTER JOIN tblproductreview tpr ON tblResult.productcode = tpr.productcode and tblResult.ordercode = tpr.ordercode and tblResult.idx = tpr.productorder_idx ";
$sql .= "WHERE tpr.productcode is null ";
$sql .= "ORDER BY tblResult.idx desc ";



if ( $isMobile ) {
    $paging = new New_Templet_mobile_paging($sql, 3, 8, 'GoPageAjax1', true);
} else {
	$paging = new New_Templet_paging($sql,10,10,'GoPage',true);
}
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

$sql = $paging->getSql($sql);
$result=pmysql_query($sql,get_db_conn());
//exdebug($sql);

if ( $isMobile ) {
    $r_s_curdate = date("YmdHis",strtotime("$s_year-$s_month-$s_day"));
    $r_e_curdate = date("Ymd235959",$etime);
} else {
	$r_s_curtime=strtotime("$r_s_year-$r_s_month-$r_s_day");
	$r_s_curdate=date("Ymd",$r_s_curtime)."000000";
	$r_e_curtime=strtotime("$r_e_year-$r_e_month-$r_e_day");
	$r_e_curdate=date("Ymd",$r_e_curtime)."999999";
}

# ====================================================================================================================================
# 작성한 리뷰 리스트
# ====================================================================================================================================

$sql2  = "SELECT *  ";
$sql2 .= "FROM tblproductreview tpr ";
$sql2 .= "WHERE tpr.id = '" . $_ShopInfo->getMemid() . "' ";
$sql2 .= "AND ( tpr.date >= '{$r_s_curdate}' AND tpr.date <= '{$r_e_curdate}' ) ";
$sql2 .= "ORDER BY tpr.num desc ";
#echo $sql2;
//exdebug($sql2);

if ( $isMobile ) {
    $paging2 = new New_Templet_mobile_paging($sql2, 3, $listnum, 'GoPageAjax2', true);

	$t_count2 = $paging2->t_count;
	$gotopage2 = $paging2->gotopage;

	$sql2 = $paging2->getSql($sql2);
	$result2 = pmysql_query($sql2,get_db_conn());

    include($Dir.TempletDir."review/mobile/myreview_TEM001.php");
} else {
	$r_paging = new New_Templet_paging($sql2,10,10,'GoPage2',true);
	$r_t_count = $r_paging->t_count;
	$gotopage2 = $r_paging->gotopage;

	$sql2 = $r_paging->getSql($sql2);
	$result2 = pmysql_query($sql2,get_db_conn());
    //exdebug($sql2);
 ?>
  <!-- 네비게이션 -->
<div class="top-page-local">
	<ul>
		<li><a href="/">HOME</a></li>
		<li><a href="<?=$Dir?>front/mypage.php">마이 페이지</a></li>
		<li class="on">상품리뷰</li>
	</ul>
</div>
<!-- //네비게이션-->
<div id="contents">
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<!-- LNB -->
			<? include  "mypage_TEM01_left.php";  ?>
			<!-- //LNB -->

			<article class="mypage_content">
				<section class="mypage_main review">
					<ul class="my-tab-menu clear">
						<li class="<?=$viewtab['request']?>"><a>리뷰작성</a></li>
						<li class="<?=$viewtab['result']?>"><a>작성완료</a></li>
					</ul>
					<div class="mt-30">
						<ul class="review_benefit">
							<li>
								<div>
									<p><span>상품 리뷰</span> 작성 시<br>
									<em><?=number_format($pointSet['textr']['point'])?>AP</em> 지급</p>
								</div>
							</li>
							<li>
								<div>
									<p><span>포토 상품리뷰</span> 작성 시<br>
									<em><?=number_format($pointSet['photo']['point'])?>AP</em> 지급</p>
								</div>
							</li>
							<li>
								<div>
									<p><span>베스트 상품리뷰</span> 채택 시<br>
									<em><?=number_format($pointSet['best']['point'])?>AP</em> 지급</p>
								</div>
							</li>
						</ul>
					</div>

					<!-- 리뷰작성 -->
					<div class="mt-50 tab-menu-content <?=$viewtab['request']?>">
						<div class="order_right">
							<div class="total">총 <?=number_format($t_count)?>건</div>
							<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
							<div class="date-sort clear">
								<div class="type month">
									<p class="title">기간별 조회</p>
								<?
									if(!$day_division) $day_division = '1MONTH';

								?>
								<?foreach($arrSearchDate as $kk => $vv){?>
									<?
										$dayClassName = "";
										if($day_division != $kk){
											$dayClassName = '';
										}else{
											$dayClassName = 'on';
										}
									?>
									<button type="button" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this)"><span><?=$vv?></span></button>
								<?}?>
								</div>
								<div class="type calendar">
									<p class="title">일자별 조회</p>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="date1" id="" value="<?=$strDate1?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
									<span>-</span>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="date2" id="" value="<?=$strDate2?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
								</div>
								<button type="button" class="btn-go" onClick="javascript:CheckForm();"><span>검색</span></button>
							</div>
							</form>
						</div>
						<table class="th_top">
							<caption></caption>
							<colgroup>
								<col style="width:5%">
								<col style="width:12%">
								<col style="width:auto">
								<col style="width:12%">
								<col style="width:12%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">NO.</th>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">결제금액</th>
									<th scope="col">리뷰작성</th>
								</tr>
							</thead>
							<tbody>
<?
		$cnt=0;
		if($t_count){
			while($row=pmysql_fetch_object($result)) {

				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

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

                $file = getProductImage($Dir.DataDir.'shopimages/product/', $sub_row->tinyimage);

?>
								<tr class="bold">
									<td><?=$number?></td>
									<td class="date"><?=$order_date?></td>
									<td class="goods_info">
										<a href="<?=$Dir.FrontDir.'productdetail.php?productcode='.$sub_row->productcode?>">
											<img src="<?=$file?>" alt="마이페이지 상품 썸네일 이미지">
											<ul>
												<li>[<?=$sub_row->brandname?>]</li>
												<li><?=strip_tags($sub_row->productname)?></li>
											</ul>
										</a>
									</td>
									<td class="payment"><?=number_format($row->price)?>원</td>
									<td>
										<div class="btn_review">
											<a href="javascript:Review_Write('<?=$row->idx?>', '<?=$file?>', '<?=$sub_row->brandname?>', '<?=$sub_row->productname?>');" class="btn-line">리뷰작성</a>
                                            <input type=hidden name='modify_pdtimg' value="<?=$file?>">
                                            <input type=hidden name='modify_brandname' value="<?=$sub_row->brandname?>">
                                            <input type=hidden name='modify_productname' value="<?=$sub_row->productname?>">
										</div>
									</td>
								</tr>
<?
				$cnt++;
			}
		} else {
?>
								<tr>
									<td colspan="6">내역이 없습니다.</td>
								</tr>
<?
		}
?>
							</tbody>
						</table>
						<div class="list-paginate mt-30"><?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?></div>
					</div>
					<!-- // 리뷰작성 -->

					<!-- 작성완료 -->
					<div class="mt-50 tab-menu-content <?=$viewtab['result']?>">
						<div class="order_right">
							<div class="total">총 <?=number_format($r_t_count)?>건</div>
							<form name="form3" action="<?=$_SERVER['PHP_SELF']?>">
							<div class="date-sort clear">
								<div class="type month">
									<p class="title">기간별 조회</p>
								<?
									if(!$r_day_division) $r_day_division = '1MONTH';

								?>
								<?foreach($arrSearchDate as $kk => $vv){?>
									<?
										$dayClassName = "";
										if($r_day_division != $kk){
											$dayClassName = '';
										}else{
											$dayClassName = 'on';
										}
									?>
									<button type="button" class="<?=$dayClassName?>" onClick = "GoSearch3('<?=$kk?>', this)"><span><?=$vv?></span></button>
								<?}?>
								</div>
								<div class="type calendar">
									<p class="title">일자별 조회</p>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="r_date1" id="" value="<?=$r_strDate1?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
									<span>-</span>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="r_date2" id="" value="<?=$r_strDate2?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
								</div>
								<button type="button" class="btn-go" onClick="javascript:CheckForm3();"><span>검색</span></button>
							</div>
							</form>
						</div>
						<table class="th_top">
							<caption></caption>
							<colgroup>
								<col style="width:5%">
								<col style="width:12%">
								<col style="width:40%">
								<col style="width:auto">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">NO.</th>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">제목</th>
								</tr>
							</thead>
							<tbody>
<?
		$cnt=0;
		if($r_t_count){
			while($row=pmysql_fetch_object($result2)) {

				$number = ($r_t_count-($setup[list_num] * ($gotopage2-1))-$cnt);

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
                for ($i = 0; $i < $row->marks; $i++) {
                    //$marks .= '★';
                    $marks .= '<img src="/static/img/common/ico_star.png" />';
                }

                // 주문일
                $order_date = $row->regdt;
                if ( empty($order_date) ) {
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
                if ( !empty($row->upfile5) ) { array_push($arrUpFile, $row->upfile5); }

                // 리뷰 제목/내용
                $review_title   = $row->subject;
                $review_content = nl2br($row->content);

                if($row->best_type == "1") $best_img = "<img src='../static/img/icon/icon_review_best.gif' alt='상품평 베스트'>";
                else $best_img = "";

                if($row->type == "1") $photo_img = "<img src='../static/img/icon/icon_review_photo.gif' alt='포토상품평'>";
                else $photo_img = "";

                $file = getProductImage($Dir.DataDir.'shopimages/product/', $sub_row->tinyimage);
?>
								<tr class="bold">
									<td><?=$number?></td>
									<td class="date"><?=$order_date?></td>
									<td class="goods_info">
										<a href="<?=$Dir.FrontDir.'productdetail.php?productcode='.$sub_row->productcode?>">
											<img src="<?=$file?>" alt="마이페이지 상품 썸네일 이미지">
											<ul>
												<li>[<?=$sub_row->brandname?>]</li>
												<li><?=strip_tags($sub_row->productname)?></li>
											</ul>
										</a>
									</td>
									<td class="ta-l">
                                        <a href="javascript:Review_Modify('<?=$row->num?>', '<?=$file?>', '<?=$sub_row->brandname?>', '<?=$sub_row->productname?>','<?=$row->productcode?>','<?=$row->marks?>','<?=$row->ordercode?>','<?=$row->productorder_idx?>','<?=$row->up_rfile?>','<?=$row->upfile?>','<?=$row->up_rfile2?>','<?=$row->upfile2?>','<?=$row->up_rfile3?>','<?=$row->upfile3?>','<?=$row->up_rfile4?>','<?=$row->upfile4?>','<?=$row->up_rfile5?>','<?=$row->upfile5?>','<?=$row->size?>','<?=$row->foot_width?>','<?=$row->color?>','<?=$row->quality?>');" class="btn-review-detail">
                                        <?=strcutMbDot($review_title, 60)?> <?=$best_img?> <?=$photo_img?></a>
                                        <input type=hidden name=modify_subject id="modify_subject_<?=$row->num?>" value="<?=$review_title?>">
                                        <input type=hidden name=modify_content id="modify_content_<?=$row->num?>" value="<?=$row->content?>">
                                    </td>
								</tr>

<?
				$cnt++;
			}
		} else {
?>
								<tr>
									<td colspan="6">내역이 없습니다.</td>
								</tr>
<?
		}
?>
							</tbody>
						</table>
						<div class="list-paginate mt-30"><?=$r_paging->a_prev_page.$r_paging->print_page.$r_paging->a_next_page?></div>
					</div>
					<!-- // 주문취소/반품/교환 완료 -->
				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->


<!-- 상품리뷰 상세팝업 -->
<div class="layer-dimm-wrap pop-review-detail"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner w800">
		<h3 class="layer-title">HOT<span class="type_txt1">-T</span> 상품리뷰 작성</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">

            <form name='reviewForm' id='reviewForm' method='POST' action='' onSubmit="return false;">
			<table class="th_left">
				<caption>상품리뷰 작성/상세보기</caption>
				<colgroup>
					<col style="width:100px">
					<col style="width:auto">
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">상품</th>
						<td colspan="3" class="goods_info modify_info">
							<a href="javascript:void(0)">
								<img src="../static/img/test/@mypage_main_order1.jpg" alt="마이페이지 상품 썸네일 이미지">
								<ul class="bold">
									<li id="qna-brandname">[나이키]</li>
									<li id="qna-productname">루나에픽 플라이니트 MEN 신발 러닝</li>
								</ul>
							</a>
						</td>
					</tr>
<!--  					
					<tr>
						<th scope="row"><label for="inp_writer">만족도</label></th>
						<td>
							<div class="my-comp-select">
								<select title="" class="selectbox" name="review_vote" id="review_vote">
									<option value="5">★ ★ ★ ★ ★</option>
									<option value="4">★ ★ ★ ★ </option>
									<option value="3">★ ★ ★ </option>
									<option value="2">★ ★ </option>
									<option value="1">★</option>
								</select>
							</div>
						</td>
					</tr>
-->					
					<tr>
						<th scope="row"><label for="inp_writer">상품 평가</label></th>
						<td>
							<section class="wrap_select_rating">
								<div class="select_rating">
									<span>사이즈</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_size" id="review_size-2">
												<label for="review_size-2">작다</span>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_size" id="review_size-1">
												<label for="review_size-1" class="none">조금 작다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_size" id="review_size0" checked>
												<label for="review_size0">적당함</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_size" id="review_size1">
												<label for="review_size1" class="none">조금 크다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_size" id="review_size2">
												<label for="review_size2">크다</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>발볼 넓이</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_foot_width" id="foot_width-2">
												<label for="foot_width-2">작다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_foot_width" id="foot_width-1">
												<label for="foot_width-1" class="none">조금 작다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_foot_width" id="foot_width0" checked>
												<label for="foot_width0">적당함</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_foot_width" id="foot_width1">
												<label for="foot_width1" class="none">조금 크다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_foot_width" id="foot_width2">
												<label for="foot_width2">크다</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>색상</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_color" id="color-2">
												<label for="color-2">어둡다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_color" id="color-1">
												<label for="color-1" class="none">조금 어둡다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_color" id="color0" checked>
												<label for="color0">화면과 같다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_color" id="color1">
												<label for="color1" class="none">조금 밝다</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_color" id="color2">
												<label for="color2">밝다</label>
											</div>
										</li>
									</ul>
								</div>
								<div class="select_rating">
									<span>품질/만족도</span>
									<ul>
										<li>
											<div class="radiobox">
												<input type="radio" value="-2" name="review_quality" id="quality-2">
												<label for="quality-2">불만</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="-1" name="review_quality" id="quality-1">
												<label for="quality-1" class="none">조금 불만</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="0" name="review_quality" id="quality0" checked>
												<label for="quality0">보통</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="1" name="review_quality" id="quality1">
												<label for="quality1" class="none">조금 만족</label>
											</div>
										</li>
										<li>
											<div class="radiobox">
												<input type="radio" value="2" name="review_quality" id="quality2">
												<label for="quality2">만족</label>
											</div>
										</li>
									</ul>
								</div>
							</section>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_writer">제목 <span class="required">*</span></label></th>
						<td>
							<input type="text" name="inp_writer" id="inp_writer" title="제목 입력자리" style="width:100%;">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_content">내용 <span class="required">*</span></label></th>
						<td>
							<textarea name="inp_content" id="inp_content" cols="30" rows="10" style="width:100%"></textarea>
							<p class="s_txt">ㆍ배송, 상품문의, 취소, 교환 등의 문의사항은 1:1문의 또는 상담전화를 이용해 주시기 바랍니다</p>
						</td>
					</tr>
					<tr>
						<th scope="row">사진 <span class="required">*</span></th>
						<td>
							<!-- <form> -->
								<fieldset>
								<legend>상품 리뷰작성을 합니다.</legend>
								<ul class="reg-review">
									<li>
										<div class="add-photo-wrap">
											<div class="add-photo" id="add-photo1">
												<!--<button type="button">삭제</button>
												<p style="background:url(../static/img/test/@test_review_dum1.jpg) center no-repeat; background-size:contain"></p>-->
												<input type="file" name="up_filename[]" accept="image/*">
                                                <input type="hidden" id="file_exist" name="file_exist" value="N" />
                                                <input type="hidden" name="v_up_filename[]" id="upfile">
											</div>
											<div class="add-photo" id="add-photo2">
												<!--<button type="button">삭제</button>
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
							<p class="s_txt">ㆍ파일명 : 한글, 영문, 숫자 / 파일 용량 : 3M 이하 / 파일 형식 : GIF, JPG(JPEG)</p>
						</td>
					</tr>
				</tbody>
			</table>

            	<input type=hidden name="op_idx" id="op_idx" value="" />
            	<input type=hidden name="review_num" id="review_num" value="" />
                <input type="hidden" name="color" id="color" value="" />
    			<input type="hidden" name="size" id="size" value="" />
    			<input type="hidden" name="foot_width" id="foot_width" value="" />
   			    <input type="hidden" name="quality" id="quality" value="" />
            </form>

			<div class="btn_wrap"><a href="#" class="btn-type1" onclick='javascript:ajax_review_insert();'>저장</a></div>
		</div>
	</div>
</div>
<!-- // 상품리뷰 상세팝업 -->



<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>

<script type="text/javascript">

$(document).ready(function(){
    $('.layer-dimm-wrap .btn-close').click(function(){
        // 상단 탭 유지하고, 레이어 팝업창 초기화 위해서..
        //location.reload();
        document.form2.submit();
    });
});

function Review_Init(mode) {

    document.form2.review_type.value = mode;

 //   $("#review_vote").val("");
    $("#inp_writer").val("");
    $("#inp_content").val("");
    $("#op_idx").val("");

    $(".add-photo").find('p').remove();
    $(".add-photo").find('button').remove();
}

function Review_Write(op_idx, ptdimg, brandname, ptdname) {

    Review_Init('request');

    var num = op_idx;
    var modify_pdtimg       = ptdimg;
    var modify_brandname    = brandname;
    var modify_productname  = ptdname;
    //console.log(num);

    //Layer 에 값 채우기
    $(".modify_info img").attr({"src":modify_pdtimg});
    $("#qna-brandname").html("["+modify_brandname+"]");
    $("#qna-productname").html(modify_productname);
    $("#op_idx").val(num);


    $('.pop-review-detail').fadeIn();
}

function ajax_review_insert() {

    var op_idx          = $("#op_idx").val();
    var review_title    = $("#inp_writer").val().trim();
    var review_content  = $("#inp_content").val().trim();
//    var review_vote     = $("#review_vote option:selected").val();
    var review_num      = $("#review_num").val();
    var size = $(':radio[name="review_size"]:checked').val();
    var color = $(':radio[name="review_color"]:checked').val();
    var foot_width = $(':radio[name="review_foot_width"]:checked').val();
    var quality = $(':radio[name="review_quality"]:checked').val();
    //console.log(op_idx);
    //console.log(review_title);
    //console.log(review_content);
    //console.log(review_vote);
    //console.log(review_num);
    var mode            = "request";
    if(review_num > 0) mode = "result";

	if ( review_title == "" ) {
        alert("제목을 입력해 주세요.");
        $("#inp_writer").val('').focus();
    } else if ( review_content == "" ) {
        alert("내용을 입력해 주세요.");
        $("#inp_content").val('').focus();
    } else if ( chkReviewContentLength($("#inp_content")[0]) === false ) {
        $("#inp_content").focus();
    } else if ( size == "" ) {
    	alert("사이즈를 선택해 주세요");
    } else if ( color == "" ) {   
    	alert("색상을 선택해 주세요");
    } else if ( foot_width == "" ) {      
    	alert("발볼 넓이를 선택해 주세요");
    } else if ( quality == "" ) {           
    	alert("품질/만족도를 선택해 주세요");
    } else {
		$("#size").val(size);
		$("#foot_width").val(foot_width);
		$("#color").val(color);
		$("#quality").val(quality);
        var fd = new FormData($("#reviewForm")[0]);

        $.ajax({
            url: "ajax_insert_review_v2.php",
            type: "POST",
            data: fd,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
        }).success(function(data){
            if( data === "SUCCESS" ) {
                alert("리뷰가 등록되었습니다.");
                // 탭 선택 유지하기 위해 변경.
                //location.reload();
                document.form2.review_type.value = mode;
                document.form2.submit();
            } else {
                var arrTmp = data.split("||");
                if ( arrTmp[0] === "FAIL" ) {
                    alert(arrTmp[1]);
                } else {
                    alert("리뷰 등록이 실패하였습니다.");
                }
            }
        }).error(function(){
            alert("다시 시도해 주십시오.");
        });
    }
}

function Review_Modify(review_num, pdtimg, brandname, pdtname, pdtcode, marks, ordercode, op_idx, up_rfile, upfile,
                        up_rfile2, upfile2, up_rfile3, upfile3, up_rfile4, upfile4, up_rfile5, upfile5,size,foot_width,color,quality) {

    Review_Init('result');

    var review_num          = review_num;
    var modify_pdtimg       = pdtimg;
    var modify_brandname    = brandname;
    var modify_productname  = pdtname;
    var modify_subject      = $("#modify_subject_"+review_num).val();
    var modify_content      = $("#modify_content_"+review_num).val();
    console.log(review_num);
    console.log(modify_pdtimg);
    console.log(modify_brandname);
    console.log(modify_productname);
    console.log(modify_subject);
    //console.log(upfile);

    //Layer 에 값 채우기
    $(".modify_info img").attr({"src":modify_pdtimg});
    $("#qna-brandname").html("["+modify_brandname+"]");
    $("#qna-productname").html(modify_productname);
//    $("#review_vote").val(marks);
    $("#inp_writer").val(modify_subject);
    $("#inp_content").val(modify_content);
    $("#op_idx").val(op_idx);
    $("#review_num").val(review_num);
    $(".btn_wrap a").html("수정");

	$('input:radio[name="review_size"][value="'+size+'"]').prop('checked', true);
	$('input:radio[name="review_color"][value="'+color+'"]').prop('checked', true);
	$('input:radio[name="review_foot_width"][value="'+foot_width+'"]').prop('checked', true);
	$('input:radio[name="review_quality"][value="'+quality+'"]').prop('checked', true);

    if(upfile != ""){
        var this_photo = "add-photo1";
        //var imgpath = "http://test-hott.ajashop.co.kr/data/shopimages/review/"+upfile;
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile;
        var img = '<p style="background:url('+imgpath +') center no-repeat; background-size:contain"></p>';
        $("#add-photo1").prepend(img);
        $("#add-photo1").prepend('<button type="button" onClick="DeletePhoto(\''+this_photo+'\');">삭제</button>');
        $("#upfile").val(upfile);
    }

    if(upfile2 != ""){
        var this_photo = "add-photo2";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile2;
        var img = '<p style="background:url('+imgpath +') center no-repeat; background-size:contain"></p>';
        $("#add-photo2").prepend(img);
        $("#add-photo2").prepend('<button type="button" onClick="DeletePhoto(\''+this_photo+'\');">삭제</button>');
        $("#upfile2").val(upfile2);
    }

    if(upfile3 != ""){
        var this_photo = "add-photo3";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile3;
        var img = '<p style="background:url('+imgpath +') center no-repeat; background-size:contain"></p>';
        $("#add-photo3").prepend(img);
        $("#add-photo3").prepend('<button type="button" onClick="DeletePhoto(\''+this_photo+'\');">삭제</button>');
        $("#upfile3").val(upfile3);
    }

    if(upfile4 != ""){
        var this_photo = "add-photo4";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile4;
        var img = '<p style="background:url('+imgpath +') center no-repeat; background-size:contain"></p>';
        $("#add-photo4").prepend(img);
        $("#add-photo4").prepend('<button type="button" onClick="DeletePhoto(\''+this_photo+'\');">삭제</button>');
        $("#upfile4").val(upfile4);
    }

    if(upfile5 != ""){
        var this_photo = "add-photo5";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile5;
        var img = '<p style="background:url('+imgpath +') center no-repeat; background-size:contain"></p>';
        $("#add-photo5").prepend(img);
        $("#add-photo5").prepend('<button type="button" onClick="DeletePhoto(\''+this_photo+'\');">삭제</button>');
        $("#upfile5").val(upfile5);
    }


    $('.pop-review-detail').fadeIn();
}

function DeletePhoto(this_photo){

    console.log(this_photo);
    console.log(browser().version);
    $("#"+this_photo+"").find('p').remove();
    $("#"+this_photo+"").find('button').remove();
    if (parseInt(browser().version) > 0) {
        // ie 일때 input[type=file] init.
        $("#"+this_photo+"").find('input[type=file]').replaceWith( $("#"+this_photo+"").find('input[type=file]').clone(true) );
    } else {
        // other browser 일때 input[type=file] init.
        $("#"+this_photo+"").find('input[type=file]').val("");
    }
}

function browser() {
    var s = navigator.userAgent.toLowerCase();
    var match = /(webkit)[ \/](\w.]+)/.exec(s) ||
            /(opera)(?:.*version)?[ \/](\w.]+)/.exec(s) ||
            /(msie) ([\w.]+)/.exec(s) ||
            !/compatible/.test(s) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(s) || [];
    return { name: match[1] || "", version: match[2] || "0" };
}

</script>
<?}?>