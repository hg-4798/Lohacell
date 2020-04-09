<script type="text/javascript">
	$(function(){
		$('a.pop_benefit').click(function(){
			$('div.pop_member_benefit').css('display','block');
		});
		$('a.close').click(function(){
			$('div.pop_member_benefit').css('display','none');
		});
	});
</script>


<?php
$subTop_flag = 3;
//include ($Dir.MainDir."sub_top.php");
?>
	<div class="containerBody sub-page hide">
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li class="on"><a href="mypage.php">MY PAGE</a></li>
			</ul>
		</div>

		<!-- LNB -->
		<div class="left_lnb">
			<? include  "mypage_TEM01_left.php";  ?>
		</div><!-- //LNB -->

		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">

<?
$mem_grade			= $_mdata->group_name;
$mem_grade_img	= "../static/img/icon/grade_".str_replace(" ", "_", strtolower($mem_grade)).".gif";
$mem_grade_text	= strtoupper($mem_grade);
$mem_grade_class	= str_replace(" star", "", $mem_grade);

?>
			<div class="my-benefit-summary">
				<div class="inner-left">
					<div class="grade-pic">
						<figure>
							<img src="<?=$mem_grade_img?>" alt="<?=$mem_grade_text?> 등급">
							<figcaption class="grade <?=$mem_grade_class?>"><?=$mem_grade_text?></figcaption>
						</figure>
					</div>
					<div class="benefit">
						<p class="my-grede">
							<strong><?=$_mdata->name?></strong> 님의 회원등급은 <br><strong class="grade <?=$mem_grade_class?>"><?=$mem_grade_text?></strong> 입니다.
						</p>
						<ul>
							<li><a href="/front/mypage_reserve.php"><span>마일리지</span><strong><?=number_format($_mdata->reserve)?> <img src="../static/img/icon/icon_mileage.gif" alt="마일리지"></strong></a></li>
							<li><a href="/front/mypage_coupon.php"><span>쿠폰</span><strong><?=number_format($coupon_cnt)?> <img src="../static/img/icon/icon_coupon.gif" alt="쿠폰"></strong></a></li>
							<li><a href="/front/mypage_personal.php"><span>1:1 문의내역</span><strong><?=number_format($personal_cnt)?></strong></a></li>
						</ul>
						<a href="/front/mypage_orderlist.php" class="btn-dib-line"><span>구매내역 보기</span></a>
					</div>
				</div><!-- //.inner-left -->
				<div class="inner-right">
					<table class="th-top util">
						<caption>나의 구매 누적금액, 다음 등급에 필요한 금액과 주문 건수를 확인</caption>
						<colgroup><col style="width:33%"><col style="width:auto"><col style="width:33%"></colgroup>
						<thead>
							<tr>
								<th scope="col">누적 구매 금액</th>
								<th scope="col">필요 구매 금액</th>
								<th scope="col">필요 주문 건수</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?=number_format($tot_ord_price)?></td>
								<td><?=number_format($tot_need_price)?></td>
								<td><?=number_format($tot_need_cnt)?></td>
							</tr>
						</tbody>
					</table>
					<dl class="attention">
						<dt>등급 산정에 대한 정보</dt>
						<!-- <dd>구매금액 <strong><?=number_format($n_group_orderprice_s)?>원</strong> 주문횟수 <strong><?=number_format($n_group_ordercnt_s)?>건</strong>을 충족하시면 <strong><?=strtoupper($n_group_name)?> 등급으로 <?=$n_group_status?></strong></dd> -->
						<dd>최근 6개월 누적 구매 및 구매상품건수 기준 (두 기준 모두 충족시)</dd>
						<dd>구매상품에 대한 반품/취소 이력에 의해 일부 오차가 발생가능</dd>
					</dl>
				</div><!-- //.inner-left -->
			</div><!-- //.my-benefit-summary -->

<?

		// 주문 내역 (취소(접수,진행,완료) 및 배송완료 제외)
		$ord_sql = "SELECT a.ordercode, min(a.id) as id, min(a.price) as price, min(a.deli_price) as deli_price, min(a.dc_price) as dc_price, min(a.reserve) as reserve, min(a.paymethod) as paymethod, min(a.sender_name) as sender_name, min(a.receiver_name) as receiver_name, min(a.oi_step1) as oi_step1, min(a.oi_step2) as oi_step2, min(a.redelivery_type) as redelivery_type, min(productname) as productname, (select count(*) from tblorderproduct op where op.ordercode = a.ordercode) prod_cnt FROM tblorderinfo a join tblorderproduct b on a.ordercode = b.ordercode join tblvenderinfo v on b.vender = v.vender WHERE a.id='".$_ShopInfo->getMemid()."' ";
		$ord_sql.= "AND b.option_type = 0 AND ( (a.oi_step1 in (0,1,2,3) And a.oi_step2 = 0) ) ";
		$ord_sql.= "GROUP BY a.ordercode ";
		$ord_sql.= "ORDER BY a.ordercode DESC LIMIT 2 ";

		$ord_result	= pmysql_query($ord_sql,get_db_conn());
		$ord_cnt		= pmysql_num_rows($ord_result);
?>
			<div class="my-order-summary">
				<div class="mypage-title">주문배송조회<a href="/front/mypage_orderlist.php" class="see-more">SEE MORE</a></div>
				<table class="th-top util">
					<caption>주문내역 요약</caption>
					<colgroup>
						<col style="width:190px" >
						<col style="width:170px" >
						<col style="width:auto" >
						<col style="width:150px" >
						<col style="width:140px" >
					</colgroup>
					<thead>
						<tr>
							<th scope="col">주문번호</th>
							<th scope="col">주문일</th>
							<th scope="col">상품명</th>
							<th scope="col">결제금액</th>
							<th scope="col">상태</th>
						</tr>
					</thead>
					<tbody>

<?
		if ($ord_cnt	 > 0) {
			while($ord_row=pmysql_fetch_object($ord_result)) {

				$ord_date	= substr($ord_row->ordercode,0,4)."-".substr($ord_row->ordercode,4,2)."-".substr($ord_row->ordercode,6,2);

				$ord_title	= $ord_row->productname;
				if ($ord_row->prod_cnt > 1) {
					$ord_title	.= " 외 ".($ord_row->prod_cnt - 1)."건";
				}

?>
						<tr>
							<td><strong><?=$ord_row->ordercode?></strong></td>
							<td><?=$ord_date?></td>
							<td><?=$ord_title?></td>
							<td><?=number_format($ord_row->price-$ord_row->dc_price-$ord_row->reserve+$ord_row->deli_price)?></td>
							<td><span class="delivery-step"><?=$o_step[$ord_row->oi_step1][$ord_row->oi_step2]?></span><a href="javascript:OrderDetail('<?=$ord_row->ordercode?>')" class="btn-dib-line"><span>상세보기</span></a></td>
						</tr>
<?
			}
		} else {
?>
						<tr>
							<td colspan="5">내역이 없습니다.</td>
						</tr>
<?
		}
		pmysql_free_result($ord_result);
?>
					</tbody>
				</table>
			</div><!-- //.my-order-summary -->

<?
		$wp_sql = "SELECT to_char(cast(substr(a.date,0,9) as date),'YYYY-MM-DD') as regdt,a.opt1_idx,a.opt2_idx,a.optidxs,b.productcode,b.productname,b.sellprice,b.sellprice as realprice, ";
		$wp_sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, ";
		$wp_sql.= "b.etctype,a.wish_idx,a.marks,a.memo,b.selfcode,b.assembleuse,b.package_num, (SELECT brandname FROM tblproductbrand WHERE bridx = b.brand) as brandname ";
        $wp_sql.= "FROM tblwishlist a, tblproduct b ";
		$wp_sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
		$wp_sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' ";
		$wp_sql.= "AND a.productcode=b.productcode AND b.display='Y' ";
		$wp_sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
		$wp_sql.= "ORDER BY a.date DESC LIMIT 4";

		$wp_result	= pmysql_query($wp_sql,get_db_conn());
		$wp_cnt		= pmysql_num_rows($wp_result);
?>
			<div class="my-half-table">
				<div class="inner-left">
					<div class="mypage-title">관심상품<a href="/front/wishlist.php" class="see-more">SEE MORE</a></div>
					<ul class="wish-list">
<?
		if ($wp_cnt	 > 0) {
			while($wp_row=pmysql_fetch_object($wp_result)) {


						$file = getProductImage($Dir.DataDir.'shopimages/product/', $wp_row->tinyimage);

?>
						<li>
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$wp_row->productcode?>">
								<img src="<?=$file?>" alt="<?=$wp_row->productname?>">
								<div class="price-info-box">
									<p class="brand-nm"><?=$wp_row->brandname?></p>
									<p class="goods-nm"><?=$wp_row->productname?></p>
									<p class="price"><?=number_format($wp_row->sellprice)?></p>
								</div>
							</a>
						</li>
<?
			}
		} else {
?>
								<div class="ta_c"><p>내역이 없습니다.</p></div>
<?
		}
		pmysql_free_result($wp_result);
?>

					</ul>
				</div><!-- //.inner-left -->

<?
		$wb_sql  = "SELECT tblResult.wish_idx, tblResult.bridx, tblResult.regdt, tblResult.brandname, tvia.s_img ";
		$wb_sql .= "FROM ( ";
		$wb_sql .= "SELECT a.wish_idx, a.bridx, to_char(cast(substr(a.date,0,9) as date),'YYYY-MM-DD') as regdt, b.brandname, b.vender ";
		$wb_sql .= "FROM tblbrandwishlist a, tblproductbrand b WHERE a.bridx = b.bridx and a.id = '" . $_ShopInfo->getMemid() . "' ";
		$wb_sql.= "ORDER BY a.date DESC ";
		$wb_sql .= ") AS tblResult LEFT JOIN tblvenderinfo_add tvia ON tblResult.vender = tvia.vender LIMIT 2";

		$wb_result	= pmysql_query($wb_sql,get_db_conn());
		$wb_cnt		= pmysql_num_rows($wb_result);
?>
				<div class="inner-right">
					<div class="mypage-title">관심브랜드<a href="/front/wishlist_brand.php" class="see-more">SEE MORE</a></div>
					<ul class="brand-list">
<?
		if ($wb_cnt	 > 0) {
			while($wb_row=pmysql_fetch_object($wb_result)) {

					if(strlen($wb_row->s_img)!=0 && file_exists($Dir.DataDir."shopimages/vender/".$wb_row->s_img)){
						$file = $Dir.DataDir.'shopimages/vender/'.$wb_row->s_img;
					} else {
						$file = $Dir."/images/common/noimage.gif";
					}
?>

						<li>
							<div class="brand-show">
								<img src="<?=$file?>" alt="<?=$wb_row->brandname?>">
								<p class="brand-nm"><?=$wb_row->brandname?></p>
								<div class="brand-more"><a href="/front/brand_detail.php?bridx=<?=$wb_row->bridx?>" class="view">BRAND VIEW</a></div>
							</div>
						</li>
<?
			}
		} else {
?>
							<div class="ta_c"><p>내역이 없습니다.</p></div>
<?
		}
		pmysql_free_result($wb_result);
?>
					</ul><!-- //.brand-list -->
				</div><!-- //.inner-right -->
			</div><!-- //.my-half-table -->

<?
		$cu_sql = "SELECT issue.coupon_code, issue.id, issue.date_start, issue.date_end, ";
		$cu_sql.= "issue.used, issue.issue_member_no, issue.issue_recovery_no, issue.ci_no, ";
		$cu_sql.= "info.coupon_name, info.sale_type, info.sale_money, info.amount_floor, ";
		$cu_sql.= "info.productcode, info.use_con_Type1, info.use_con_type2, info.description, ";
		$cu_sql.= "info.use_point, info.vender, info.delivery_type, info.coupon_use_type, ";
		$cu_sql.= "info.coupon_type, info.sale_max_money, info.coupon_is_mobile ";
		$cu_sql.= "FROM tblcouponissue issue ";
		$cu_sql.= "JOIN tblcouponinfo info ON info.coupon_code = issue.coupon_code ";
		$cu_sql.= "WHERE issue.id = '".$_ShopInfo->getMemid()."' ";
		$cu_sql.= "ORDER BY issue.date_end DESC LIMIT 2";

		$cu_result	= pmysql_query($cu_sql,get_db_conn());
		$cu_cnt		= pmysql_num_rows($cu_result);
?>
			<div class="my-half-table">
				<div class="inner-left">
					<div class="mypage-title">쿠폰<a href="/front/mypage_coupon.php" class="see-more">SEE MORE</a></div>
					<table class="th-top util">
						<caption>쿠폰내역 요약</caption>
						<colgroup>
							<col style="width:105px" >
							<col style="width:auto" >
							<col style="width:110px" >
							<col style="width:90px" >
						</colgroup>
						<thead>
							<tr>
								<th scope="col">쿠폰번호</th>
								<th scope="col">쿠폰명</th>
								<th scope="col">쿠폰혜택</th>
								<th scope="col">유효기간</th>
							</tr>
						</thead>
						<tbody>
<?
		if ($cu_cnt	 > 0) {
			while($cu_row=pmysql_fetch_object($cu_result)) {

				if($cu_row->sale_type<=2) {
					$cu_dan="%";
				} else {
					$cu_dan="원";
				}
				if($cu_row->sale_type%2==0) {
					$cu_sale = "할인";
				} else {
					$cu_sale = "적립";
				}

				$t = sscanf($cu_row->date_start,'%4s%2s%2s%2s%2s%2s');
				$s_time = strtotime("{$t[0]}-{$t[1]}-{$t[2]} {$t[3]}:00:00");
				$t = sscanf($cu_row->date_end,'%4s%2s%2s%2s%2s%2s');
				$e_time = strtotime("{$t[0]}-{$t[1]}-{$t[2]} {$t[3]}:00:00");

				$cu_date=date("Y-m-d",$s_time)."<br>~".date("Y-m-d",$e_time);

?>
							<tr>
								<td><strong><?=$cu_row->coupon_code?></strong></td>
								<td class="subject"><?=$cu_row->coupon_name?></td>
								<td><strong><?=number_format($cu_row->sale_money).$cu_dan.' '.$cu_sale?></strong></td>
								<td><?=$cu_date?></td>
							</tr>
<?
			}
		} else {
?>
							<tr>
								<td colspan="4">내역이 없습니다.</td>
							</tr>
<?
		}
		pmysql_free_result($cu_result);
?>
						</tbody>
					</table>
				</div><!-- //.inner-left -->

<?

		$ps_sql = "SELECT idx,subject,date,re_date,head_title FROM tblpersonal ";
		$ps_sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		$ps_sql.= "ORDER BY idx DESC LIMIT 2";

		$ps_result	= pmysql_query($ps_sql,get_db_conn());
		$ps_cnt		= pmysql_num_rows($ps_result);
?>
				<div class="inner-right">
					<div class="mypage-title">1:1 문의<a href="/front/mypage_personal.php" class="see-more">SEE MORE</a></div>
					<table class="th-top util">
						<caption>쿠폰내역 요약</caption>
						<colgroup>
							<col style="width:105px" >
							<col style="width:auto" >
							<col style="width:110px" >
							<col style="width:90px" >
						</colgroup>
						<thead>
							<tr>
								<th scope="col">상담분류</th>
								<th scope="col">제목</th>
								<th scope="col">작성일</th>
								<th scope="col">상태</th>
							</tr>
						</thead>
						<tbody>
<?
		if ($ps_cnt	 > 0) {
			while($ps_row=pmysql_fetch_object($ps_result)) {

					$ps_date = substr($ps_row->date,0,4)."-".substr($ps_row->date,4,2)."-".substr($ps_row->date,6,2);

					if(strlen($ps_row->re_date)==14) {
						$ps_status	= "답변완료";
					} else {
						$ps_status	= "미답변";
					}

?>
							<tr>
								<td><?=$arrayCustomerHeadTitle[$ps_row->head_title]?></td>
								<td class="subject"><a href="/front/mypage_personal.php?mode=view&idx=<?=$ps_row->idx?>"><?=strip_tags($ps_row->subject)?></a></td>
								<td><?=$ps_date?></td>
								<td><?=$ps_status?></td>
							</tr>
<?
			}
		} else {
?>
							<tr>
								<td colspan="4">내역이 없습니다.</td>
							</tr>
<?
		}
		pmysql_free_result($ps_result);
?>
						</tbody>
					</table>

				</div><!-- //.inner-right -->
			</div><!-- //.my-half-table -->





		</div><!-- 내용 -->

	</div>
<!-- // [2016.07.29] 기존 소스 -->