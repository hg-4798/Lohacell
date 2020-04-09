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
	<div class="containerBody sub-page">
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li><a href="mypage.php">MY PAGE</a></li>
			</ul>
		</div>

		<!-- LNB -->
		<div class="left_lnb">
			<? include  "mypage_TEM01_left.php";  ?>			
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">

			
			<div class="my-benefit-summary">
				<div class="inner-left">
					<div class="grade-pic">
						<figure>
							<img src="../static/img/icon/grade_family.gif" alt="family 등급">
							<!-- <img src="../static/img/icon/grade_brown_star.gif" alt="gold star 등급">
							<img src="../static/img/icon/grade_silver_star.gif" alt="sliver star 등급">
							<img src="../static/img/icon/grade_gold_star.gif" alt="gold star 등급">
							<img src="../static/img/icon/grade_vip.gif" alt="VIP 등급"> -->
							<figcaption class="grade family">FAMILY</figcaption>
						</figure>
					</div>
					<div class="benefit">
						<p class="my-grede">
							<strong>홍길동</strong> 님의 회원등급은 <strong class="grade family">FAMILY</strong> 입니다.
							<!-- <strong>홍길동</strong> 님의 회원등급은 <strong class="grade brown">BROWN STAR</strong>
							<strong>홍길동</strong> 님의 회원등급은 <strong class="grade silver">SILVER STAR</strong>
							<strong>홍길동</strong> 님의 회원등급은 <strong class="grade gold">GOLD STAR</strong>
							<strong>홍길동</strong> 님의 회원등급은 <strong class="grade vip">VIP</strong> -->
						</p>
						<ul>
							<li><span>마일리지</span><strong>3,000 <img src="../static/img/icon/icon_mileage.gif" alt="마일리지"></strong></li>
							<li><span>쿠폰</span><strong>2 <img src="../static/img/icon/icon_coupon.gif" alt="쿠폰"></strong></li>
							<li><span>1:1 문의내역</span><strong>5</strong></li>
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
								<td>1,450,000</td>
								<td>50,000</td>
								<td>5</td>
							</tr>
						</tbody>
					</table>
					<dl class="attention">
						<dt>등급 산정에 대한 정보</dt>
						<dd>구매금액 <strong>50,000원</strong> 주문횟수 <strong>5건</strong>을 충족하시면 <strong>VIP 등급으로 유지</strong></dd>
						<dd>최근 6개월 누적 구매 및 구매상품건수 기준 (두 기준 모두 충족시)</dd>
						<dd>구매상품에 대한 반품/취소 이력에 의해 일부 오차가 발생가능</dd>
					</dl>
				</div><!-- //.inner-left -->
			</div><!-- //.my-benefit-summary -->
			
			<div class="my-order-summary">
				<div class="mypage-title">주문배송조회<a href="#" class="see-more">SEE MORE</a></div>
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
						<tr>
							<td><strong>2015121412345</strong></td>
							<td>2016-01-28</td>
							<td>POSTCARD 01 외 1건</td>
							<td>11,400원</td>
							<td><span class="delivery-step">배송중</span><a href="#" class="btn-dib-line"><span>상세보기</span></a></td>
						</tr>
					</tbody>
				</table>
			</div><!-- //.my-order-summary -->

			<div class="my-half-table">
				<div class="inner-left">
					<div class="mypage-title">관심상품<a href="#" class="see-more">SEE MORE</a></div>
					<ul class="wish-list">
						<li>
							<a href="#">
								<img src="../static/img/test/@goods_thumb125_03.jpg" alt="105사이즈 썸네일">
								<div class="price-info-box">
									<p class="brand-nm">CASH</p>
									<p class="goods-nm">JEEP Kids Plate - JEEP Kids Plate - </p>
									<p class="price">68,000</p>
								</div>
							</a>
						</li>
						<li>
							<a href="#">
								<img src="../static/img/test/@goods_thumb125_03.jpg" alt="105사이즈 썸네일">
								<div class="price-info-box">
									<p class="brand-nm">CASH</p>
									<p class="goods-nm">JEEP Kids Plate - JEEP Kids Plate - </p>
									<p class="price">68,000</p>
								</div>
							</a>
						</li>
						<li>
							<a href="#">
								<img src="../static/img/test/@goods_thumb125_03.jpg" alt="105사이즈 썸네일">
								<div class="price-info-box">
									<p class="brand-nm">CASH</p>
									<p class="goods-nm">JEEP Kids Plate - JEEP Kids Plate - </p>
									<p class="price">68,000</p>
								</div>
							</a>
						</li>
						<li>
							<a href="#">
								<img src="../static/img/test/@goods_thumb125_03.jpg" alt="105사이즈 썸네일">
								<div class="price-info-box">
									<p class="brand-nm">CASH</p>
									<p class="goods-nm">JEEP Kids Plate - JEEP Kids Plate - </p>
									<p class="price">68,000</p>
								</div>
							</a>
						</li>
					</ul>
				</div><!-- //.inner-left -->
				<div class="inner-right">
					<div class="mypage-title">관심브랜드<a href="#" class="see-more">SEE MORE</a></div>
					<ul class="brand-list">
						<li>
							<div class="brand-show">
								<img src="../static/img/test/@brand_img01.jpg" alt="">
								<p class="brand-nm">ANGIE ANN</p>
								<div class="brand-more"><a href="#" class="view">BRAND VIEW</a></div>
							</div>
						</li>
						<li>
							<div class="brand-show">
								<img src="../static/img/test/@brand_img02.jpg" alt="">
								<p class="brand-nm">ANGIE ANN</p>
								<div class="brand-more"><a href="#" class="view">BRAND VIEW</a></div>
							</div>
						</li>
					</ul><!-- //.brand-list -->
				</div><!-- //.inner-right -->
			</div><!-- //.my-half-table -->

			<div class="my-half-table">
				<div class="inner-left">
					<div class="mypage-title">쿠폰<a href="#" class="see-more">SEE MORE</a></div>
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
							<tr>
								<td><strong>321654987</strong></td>
								<td class="subject">DECO&E, DECO&C HOMME 20%</td>
								<td><strong>10,000원 할인</strong></td>
								<td>2015~12-14 <br>~2015-12-31</td>
							</tr>
							<tr>
								<td><strong>321654987</strong></td>
								<td class="subject">DECO&E, DECO&C HOMME 20%</td>
								<td><strong>10,000원 할인</strong></td>
								<td>2015~12-14 <br>~2015-12-31</td>
							</tr>
						</tbody>
					</table>
				</div><!-- //.inner-left -->
				<div class="inner-right">
					<div class="mypage-title">1:1 문의<a href="#" class="see-more">SEE MORE</a></div>
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
							<tr>
								<td>배송문의</td>
								<td class="subject">운송장 조회가 안되요 빠른 답변 기다리는 중...</td>
								<td>2016-01-28</td>
								<td>미답변</td>
							</tr>
							<tr>
								<td>배송문의</td>
								<td class="subject">운송장 조회가 안되요 빠른 답변 기다리는 중...</td>
								<td>2016-01-28</td>
								<td><strong>답변완료</strong></td>
							</tr>
						</tbody>
					</table>
				
				</div><!-- //.inner-right -->
			</div><!-- //.my-half-table -->









<?		
	##### 최근 주문 정보
	$sql= "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 2 as ordertype ";
	$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
	if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
	else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
	else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
	$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
	$sql.= "ORDER BY ordercode DESC ";
	//echo $sql;
	if(!$limitpage) $limitpage = '10';
	$paging = new Tem001_saveheels_Paging($sql, 10, $limitpage, 'GoPage', true);
	$t_count = $paging->t_count;
	$gotopage = $paging->gotopage;

	$sql = $paging->getSql($sql);
	$result=pmysql_query($sql,get_db_conn());
?>
			<!-- 최근주문정보 -->
			<div class="table_wrap hide">
				<h3>최근주문정보 <span class="total">( Total : <strong><?=$t_count?></strong> )</span></h3>
				<p class="table_info"><a href="<?=$Dir.FrontDir?>mypage_orderlist.php">> 더보기</a></p>
				<table class="th_top">
					<colgroup>
						<col width="170" ><col width="85" ><col width="*px" ><col width="145" ><col width="100" >
					</colgroup>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>주문금액</th>
						<th>진행상태</th>
					</tr>
<?
	$cnt=0;
	while($row=pmysql_fetch_object($result)) {
		$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
			# 반송가능상품
			$redaliveryArray = array();
			$redelivery_sql = "SELECT redelivery_type,redelivery_date,redelivery_reason,deli_date FROM tblorderinfo ";
			$redelivery_sql.= "WHERE ordercode = '".$row->ordercode."' ";
			$redelivery_sql.= "AND deli_date != '' ";
			$redelivery_res = pmysql_query($redelivery_sql,get_db_conn());
			if($redelivery_row = pmysql_fetch_object($redelivery_res)){
				if(dateDiff(date("YmdHis"),$redelivery_row->deli_date) < 11){
					$redaliveryArray = $redelivery_row; 
				}
			}
			pmysql_free_result($redelivery_res);

		$ord_time=substr($row->ord_time,0,2).":".substr($row->ord_time,2,2).":".substr($row->ord_time,4,2);
?>
					<tr>
						<? $sql2 = "SELECT productcode, productname, MAX(deli_com) deli_com, MAX(deli_num) deli_num FROM tblorderproduct  ";
						$sql2 .= " WHERE ordercode='".$row->ordercode."' ";
						$sql2 .= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') AND option_type = 0 group by productcode, productname";
						$res22 = pmysql_query($sql2);
						$result2=pmysql_fetch_object($res22);
						$resultforcnt=pmysql_num_rows($res22);
						$deli_company = "";
						$deli_number = "";

						if($deli_company=="") $deli_company = $result2->deli_com;
						if($deli_number=="") $deli_number = $result2->deli_num;

						$imgRes = pmysql_query( "SELECT tinyimage FROM tblproduct WHERE productcode = '{$result2->productcode}' ",get_db_conn() );
						$imgRow = pmysql_fetch_object( $imgRes );
						if( is_file( $Dir.DataDir.'shopimages/product/'.$imgRow->tinyimage ) ) {
							$imgsrc = $Dir.DataDir.'shopimages/product/'.$imgRow->tinyimage;
						} else {
							$imgsrc = '../images/no_img.gif';
						}
						pmysql_free_result($imgRes);
						?>
						<td><a href="javascript:OrderDetail('<?=$row->ordercode?>')"><?=$row->ordercode?></a></td>
						<td class="ta_l">
							<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
								<img src="<?=$imgsrc?>" alt="" class='img-size-mypage' >
							</a>
						</td>
						<td class="ta_l">
							<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
								<? 
									switch($resultforcnt){
										case 1 : 
											echo $result2->productname;
										break;
										default : $resultforcnt--;
											echo $result2->productname." 외 ".$resultforcnt."건";										
									}							
								?>
							</a>
						</td>
						<td><b><?=number_format($row->price-$row->dc_price-$row->reserve+$row->deli_price)?>원</b></td>
						<td>
							<?
								if ($row->deli_gbn=="C") echo "주문취소";
								else if ($row->deli_gbn=="D") echo "취소요청";
								else if ($row->deli_gbn=="E") echo "환불대기";
								else if ($row->deli_gbn=="X") echo "배송준비";
								else if ($row->deli_gbn=="Y") {
									if ($row->receive_ok == '1') echo "<span style='color:#ff7200;'>배송완료</span>";
									else echo "배송중";
								} else if ($row->deli_gbn=="N") {
									if (strlen($row->bank_date)<12 && strstr("BOQ", $row->paymethod[0])) echo "주문접수";
									else if ($row->pay_admin_proc=="C" && $row->pay_flag=="0000") echo "결제취소";
									else if (strlen($row->bank_date)>=12 || $row->pay_flag=="0000") echo "결제완료";
									else echo "결제확인중";
								} else if ($row->deli_gbn=="S") {
									echo "배송준비";
								} else if ($row->deli_gbn=="R") {
									echo "반송처리";
								} else if ($row->deli_gbn=="H") {
									echo "배송완료 [정산보류]";
								}
								
								if($row->deli_gbn=="Y" && $redaliveryArray->redelivery_type == "Y"){
									echo "<br>";
									echo "<font color='red'>[반송신청]</font>";
								}
							?>
						</td>
					</tr>
<?
	$cnt++;
	}
?>
				</table>
				<div class="paging mt_30"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div>
			</div><!-- //최근주문정보 -->


		</div><!-- 내용 -->

	</div>
