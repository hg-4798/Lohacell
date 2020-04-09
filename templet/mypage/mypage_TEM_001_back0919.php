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


<!-- 메인 컨텐츠 -->
<div class="main_wrap">
	<div class="mypage1100">
		<!-- LNB -->
		<div class="left_lnb">
			<? include  "mypage_TEM01_left.php";  ?>			
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section">
			
			<div class="pop_member_benefit">
				<div class="pop_layer_type01">
					<h4><img src="../img/icon/icon_pop_benefit_member.gif" alt="" />회원등급 혜택안내</h4>
					<a href="javascript:;" class="close">닫기</a>
				</div>
				<div class="benefit">
					<h6><strong><?=$_ShopInfo->memname?></strong> 고객님은 <span><?=$grp_name?></span> 회원입니다.</h6>
					<div class="coupon_point">
						<span class="coupon"><?=number_format($cnt_coupon)?> <em>장</em></span>
						<span class="point"><?=number_format($reserve)?> <em>P</em></span>
					</div>
					<div class="ta_c mt_30">
						<a href="/front/mypage_grade.php"><img src="../img/button/btn_mybenefit_view.gif" alt="나의 헤택보기" /></a>
						<a href="http://<?=$_SERVER['HTTP_HOST']?>"><img src="../img/button/btn_shopping_continue.gif" alt="계속 쇼핑하기" /></a>
					</div>
				</div>
			</div>

			<!-- 나의쇼핑정보 -->
			<div class="my_main_benefit">
				<ul class="grade">
					<li class="grade_info">
						<dl>
							<dt><?=$_ShopInfo->memname?> 고객님의 등급은<strong><?=$grp_name?></strong>입니다.</dt>
							<dd class="btn"><a href="javascript:;" class="pop_benefit"><img src="../img/button/btn_benefit_info.gif" alt="회원등급혜택" /></a></dd>
							<dd class="ment">
								<?=$levelMassage?>
							</dd>
							<?if(ord($staff_type)){?>
								<dd class="btn"><a href="/front/staff_zone.php"><img src="../img/button/btn_staff_buy_page.gif" alt="사내 구매페이지" /></a></dd>			
							<?}?>
						</dl>
					</li>
				</ul>
				<p class="coupon"><?=number_format($cnt_coupon)?><span>장</span></p>
				<p class="milage"><?=number_format($reserve)?><span>P</span></p>
			</div><!-- //나의쇼핑정보 -->

			<!-- 최근주문정보 -->
			<div class="table_wrap">
				<h3>최근주문정보 <span class="total">( Total : <strong><?=$t_count?></strong> )</span></h3>
				<p class="table_info"><a href="<?=$Dir.FrontDir?>mypage_orderlist.php">> 더보기</a></p>
				<table class="th_top">
					<colgroup>
						<col width="125" /><col width="85" /><col width="*px" /><col width="145" /><col width="100" />
					</colgroup>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>옵션</th>
						<th>주문금액</th>
						<!--th>진행상태</th-->
					</tr>
					<?php
						if(count($ord_data)>0){
						foreach($ord_data as $k=>$od){
							$sql_prd = "SELECT a.*, b.maximage, b.minimage, b.tinyimage FROM tblorderproduct as a ";
							$sql_prd.= "LEFT JOIN tblproduct as b on a.productcode = b.productcode ";
							$sql_prd.= "WHERE a.ordercode='".$od->ordercode."' AND length(a.productcode) > 17";
							# 쿠폰 / 배송비등 상품이 아닌것 빼기 위해  AND length(a.productcode) > 17 조건 추가 2014-08-20 11:11
							$res_prd = pmysql_query($sql_prd);
							$cnt_prd = pmysql_num_rows($res_prd);	//상품 개수
							
							if($cnt_prd>1){
							#####주문상품 개수가 2개 이상일때
								$i = 0;	//rowspan을 적용하기 위한 count
								while($row_prd = pmysql_fetch_array($res_prd)){
									#### 상품 옵션
									if($row_prd['opt1_name']){
										unset($opt1_name);
										$opt1_name_arr = explode(':',$row_prd['opt1_name']);
										$opt1_name = $opt1_name_arr[1];
									}
									if($row_prd['opt2_name']){
										unset($opt2_name);
										$opt2_name_arr = explode(':',$row_prd['opt2_name']);
										$opt2_name = $opt2_name_arr[1];
									}
									#### //상품 옵션
									
									
									if($i<1){
										#### 주문 상품들중 첫번째 상품
										#### (rowspan을 적용하는 td)
										$i++;
					?>
					<tr>
						<td rowspan=<?=$cnt_prd?>>
							<a href="javascript:OrderDetail('<?=$od->ordercode?>')">
								<?=$od->ordercode?>
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>">
								<img src="<?=$Dir.ImageDir?>product/<?=$row_prd['tinyimage']?>" alt="" style="width: 75px;"/>
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>">
								<?=$row_prd['productname']?>
							</a>
						</td>
						<td><?=$opt1_name?></td>
						<td><?=number_format($row_prd['price']);?>원</td>
						<!--td rowspan=<?=$cnt_prd?>>
							<a href="#" class="btn_mypage_s"><?=$od->pay_proc?></a>
						</td-->
					</tr>
					<?php
									}else{
										#### 나머지 상품들
					?>
					<tr>
						<td class="ta_l"><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>"><img src="<?=$Dir.ImageDir?>product/<?=$row_prd['tinyimage']?>" alt="" style="width: 75px;"/></a></td>
						<td class="ta_l"><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>"><?=$row_prd['productname']?></a></td>
						<td><?=$opt1_name?></td>
						<td><?=number_format($row_prd['price']);?>원</td>
					</tr>	
					<?php					
									}
								}
					?>
					<?php
							}else{
							##### 주문 상품이 하나일때
							$row_prd = pmysql_fetch_array($res_prd);
							#### 상품 옵션
							if($row_prd['opt1_name']){
								unset($opt1_name);
								$opt1_name_arr = explode(':',$row_prd['opt1_name']);
								$opt1_name = $opt1_name_arr[1];
							}
							if($row_prd['opt2_name']){
								unset($opt2_name);
								$opt2_name_arr = explode(':',$row_prd['opt2_name']);
								$opt2_name = $opt2_name_arr[1];
							}
							#### //상품 옵션
					?>
					<tr>
						<td>
							<a href="javascript:OrderDetail('<?=$od->ordercode?>')">
								<?=$od->ordercode?>
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>">
								<img src="<?=$Dir.ImageDir?>product/<?=$row_prd['tinyimage']?>" alt="" style="width: 75px;"/>
							</a>
						</td>
						<td class="ta_l"><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>"><?=$row_prd['productname']?></a></td>
						<td><?=$opt1_name?></td>
						<td><?=number_format($row_prd['price']);?>원</td>
						<!--td><a href="#" class="btn_mypage_s"><?=$od->pay_proc?></a></td-->
					</tr>
					<?php
							}
						}
						}else{
					?>
						<tr>
							<td colspan = '5' style = 'text-align:center;'>주문이 없습니다.</td>
						</tr>	
					<?
						}
					?>
				</table>
			</div><!-- //최근주문정보 -->

			<!-- 최근&관심상품 -->
			<div class="mypage_main_today">
				<div class="today">
					
					<div class="table_wrap">
						<h3>최근 본 상품 <span class="total">( Total : <strong><?=$prdt_no?></strong> )</span></h3>
						<!--p class="table_info"><a href="#">> 더보기</a></p-->
						<div class="today_wish">
						<?php
							$rec_i=0;
							while($row_recent = pmysql_fetch_array($res_recent)){
								if($rec_i<2){
						?>
							<ul class="my_main_today">
								<li class="goods_img">
									<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_recent['productcode']?>">
										<img src="<?=$Dir.ImageDir?>product/<?=$row_recent['tinyimage']?>" alt="" />
									</a>
								</li>
								<li class="goods_info">
									<span class="name">
										<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_recent['productcode']?>">
											<?=$row_recent['productname']?>
										</a>
									</span>
									<br />
									<span class="price">
										<?php if($row_recent['consumerprice']){ ?>
										<span><?=number_format($row_recent['consumerprice'])?> 원</span>
										<?php } ?>
										<?=number_format($row_recent['sellprice'])?> 원
									</span><br />
									<!--a href="#" class="btn_mypage_vs">구매하기</a-->
								</li>
							</ul>
						<?php
									
								}
								$rec_i++;
							}
						?>
						</div>
					</div>

				</div>
				<div class="wish">
					
					<div class="table_wrap">
						<h3>관심상품 <span class="total">( Total : <strong><?=$t_count_wish?></strong> )</span></h3>
						<!--p class="table_info"><a href="<?=$Dir.FrontDir?>wishlist.php">> 더보기</a></p-->
						<div class="today_wish">
						<?php
							if($t_count_wish){
								while($row_wish = pmysql_fetch_array($res_wish)){
						?>
							<ul class="my_main_wish">
								<li>
									<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_wish['productcode']?>">
										<img src="<?=$Dir.ImageDir?>product/<?=$row_wish['tinyimage']?>" alt="" style="height: 130px;" />
									</a>
								</li>
								<li class="name">
									<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_wish['productcode']?>">
										<?=$row_wish['productname']?>
									</a>
								</li>
								<li class="price">
									<?php if($row_wish['consumerprice']){ ?>
									<span><?=number_format($row_wish['consumerprice'])?></span>
									<?php } ?>
									<?=number_format($row_wish['sellprice'])?>
								</li>
								<li>
									<!--a href="#" class="btn_mypage_vs">구매하기</a-->
								</li>
							</ul>
						<?php
								}
							}else{
						?>
							<ul class="my_main_wish">
								<li>관심상품이 없습니다.</li>
							</ul>
						<?php
							}
						?>
						</div>
					</div>

				</div>
			</div><!-- //최근&관심상품 -->


		</div><!-- 내용 -->

	</div>
</div><!-- //메인 컨텐츠 -->