
<!-- 네비게이션 -->
<div class="top-page-local">
	<ul>
		<li><a href="/">HOME</a></li>
		<li><a href="<?=$Dir?>front/mypage.php">마이 페이지</a></li>
		<li><a href="<?=$Dir?>front/mypage_orderlist.php">주문/배송</a></li>
		<li class="on">주문/배송 상세</li>
	</ul>
</div>
<!-- //네비게이션-->
<div id="contents">
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<!-- LNB -->
			<?php
			include ($Dir.FrontDir."mypage_TEM01_left.php");
			?>
			<!-- //LNB -->

			<article class="mypage_content">
				<section class="mypage_main">
					<div class="title_box_border">
						<h3>주문/배송 상세</h3>
					</div>
					<form name=form1 method=post action="<?=$_SERVER['PHP_SELF']?>">
					<input type=hidden name=tempkey>
					<input type=hidden name=ordercode>
					<input type=hidden name=type>
					<input type=hidden name=ordercodeid value="<?=$ordercodeid?>">
					<input type=hidden name=ordername value="<?=$ordername?>">
					<input type=hidden name=refund_bankcode value="<?=$refund_bankcode?>">
					<input type=hidden name=refund_bankaccount value="<?=$refund_bankaccount?>">
					<input type=hidden name=refund_bankuser value="<?=$refund_bankuser?>">
					<input type=hidden name=refund_bankusertel value="<?=$refund_bankusertel?>">
					<!-- 주문상세내역 리스트 -->
					<div class="order_list_wrap mt-50">
						<div class="order_right">
							<div class="total">총 <?=number_format(count($orProduct))?>건</div>
							<div class="txt_right">
								<span>주문번호 : <em class="type_txt2"><?=$ordercode?></em></span>
								<span>주문 날짜 : <?=substr($ordercode,0,4)."-".substr($ordercode,4,2)."-".substr($ordercode,6,2)?></span>
							</div>
						</div>
						<table class="th_top order_detail">
							<caption></caption>
							<colgroup>
								<col style="width:auto">
								<col style="width:5%">
								<col style="width:11%">
								<col style="width:6%">
								<col style="width:9%">
								<col style="width:12%">
								<col style="width:10%">
								<col style="width:10%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">상품정보</th>
									<th scope="col">수량</th>
									<th scope="col">상품금액</th>
									<th scope="col">배송정보</th>
									<th scope="col">배송비</th>
									<th scope="col">결제금액</th>
									<th scope="col">상태</th>
									<th scope="col">취소/확정/리뷰</th>
								</tr>
							</thead>
							<tbody>

<?php
		$rspan_cnt	= 1;
		$ven_cnt	= 1;
		$can_cnt	= 0;
		$pr_idxs		= "";
		$op_cnt	= count($orProduct);
		$op_step_chk	= "";
		$op_step_cnt	= 0;
		$chkDeliveryType = true;
		foreach( $orProduct as $pr_idx=>$prVal ) { // 상품

			$storeData = getStoreData($prVal->store_code);
			if($prVal->delivery_type == '2'){
				# 당일 배송이 있는지 체크
				# 당일 배송이 있을 경우 배송지 정보수정을 막기 위해.
				$chkDeliveryType = false;
			}

			if ($pr_idxs == '') {
				$pr_idxs		.= $pr_idx;
			} else {
				$pr_idxs		.= "|".$pr_idx;
			}

			if ($op_step_chk == "") {
				$op_step_chk = $prVal->op_step;
			} else {
				if ($op_step_chk != $prVal->op_step) {
					$op_step_cnt++;
				}
			}


			$file = getProductImage($Dir.DataDir.'shopimages/product/', $prVal->tinyimage);

			$optStr	= "";
			$option1	 = $prVal->opt1_name;
			$option2	 = $prVal->opt2_name;

			if( strlen( trim( $prVal->opt1_name ) ) > 0 ) {
				$opt1_name_arr	= explode("@#", $prVal->opt1_name);
				$opt2_name_arr	= explode(chr(30), $prVal->opt2_name);
				for($g=0;$g < sizeof($opt1_name_arr);$g++) {
					if ($g > 0) $optStr	.= " / ";
					$optStr	.= $opt1_name_arr[$g].' : '.$opt2_name_arr[$g];
				}
			}

			if( strlen( trim( $prVal->text_opt_subject ) ) > 0 ) {
				$text_opt_subject_arr	= explode("@#", $prVal->text_opt_subject);
				$text_opt_content_arr	= explode("@#", $prVal->text_opt_content);

				for($s=0;$s < sizeof($text_opt_subject_arr);$s++) {
					if ($text_opt_content_arr[$s]) {
						if ($optStr != '') $optStr	.= " / ";
						$optStr	.= $text_opt_subject_arr[$s].' : '.$text_opt_content_arr[$s];
					}
				}
			}

			if ($rspan_cnt == 1) {
				$ven_cnt	= $orvender[$prVal->brand]['t_pro_count'];
				$rspan_cnt	= $orvender[$prVal->brand]['t_pro_count'];
			} else {
				$rspan_cnt--;
			}

			if ($ven_cnt == $rspan_cnt && $ven_cnt > 1) {
				$rowspan	= " rowspan='".$rspan_cnt."'";
			} else {
				$rowspan	= "";
			}

			//배송비로 인한 보여지는 가격 재조정
			$can_deli_price	= 0;
			$can_total_price	= (($prVal->price + $prVal->option_price) * $prVal->option_quantity) - ($prVal->coupon_price + $prVal->use_point) + $prVal->deli_price;

			list($od_deli_price, $product)=pmysql_fetch_array(pmysql_query("select deli_price, product from tblorder_delivery WHERE ordercode='".trim($ordercode)."' and product LIKE '%".$prVal->productcode."%'"));
			//echo $od_deli_price;
			if ($od_deli_price) { //배송료 상세정보에 배송료가 있으면
				// 주문건 묶여있는 상품들중에 현재 주문상품을 제외한것중 1개를 가져온다.
				list($op_idx)=pmysql_fetch_array(pmysql_query("SELECT idx FROM tblorderproduct where ordercode='".trim($ordercode)."' and productcode in ('".str_replace(",","','", $product)."') and idx != '".$pr_idx."' and op_step < 40 limit 1"));
				//echo "SELECT idx FROM tblorderproduct where ordercode='".trim($ordercode)."' and productcode in ('".str_replace(",","','", $product)."') and idx != '".$pr_idx."' and op_step < 40 limit 1<br>";
				if ($op_idx) { // 상품이 있으면
					if ($prVal->deli_price > 0) $can_total_price	= $can_total_price - $od_deli_price;
				} else {
					$can_deli_price	= $od_deli_price;
				}
			}

			$pro_info	 = $prVal->productcode."!@#";
			$pro_info	.= substr($ordercode,0,4)."-".substr($ordercode,4,2)."-".substr($ordercode,6,2)."!@#";
			$pro_info	.= $file."!@#";
			$pro_info	.= $prVal->brandname."!@#";
			$pro_info	.= $prVal->productname."!@#";
			$pro_info	.= $optStr."!@#";
			$pro_info	.= $option1."!@#";
			$pro_info	.= $option2."!@#";
			$pro_info	.= $prVal->text_opt_subject."!@#";
			$pro_info	.= $prVal->text_opt_content."!@#";
			$pro_info	.= $prVal->option_price_text."!@#";
			$pro_info	.= $prVal->consumerprice."!@#";
			$pro_info	.= ($prVal->price + $prVal->option_price)."!@#";
			$pro_info	.= $prVal->deli_price."!@#";
			$pro_info	.= $prVal->coupon_price."!@#";
			$pro_info	.= $prVal->use_point."!@#";
			$pro_info	.= (($prVal->price + $prVal->option_price) * $prVal->option_quantity) - ($prVal->coupon_price + $prVal->use_point) + $prVal->deli_price."!@#";
			$pro_info	.= $prVal->option_type."!@#";
			$pro_info	.= $prVal->option1_tf."!@#";
			$pro_info	.= $prVal->option2_tf."!@#";
			$pro_info	.= $prVal->option2_maxlen."!@#";

			//입점업체 정보 관련
			if($prVal->vender>0) {
				$sql = "SELECT deli_info, re_addrinfo ";
				$sql.= "FROM tblvenderstore ";
				$sql.= "WHERE vender='{$prVal->vender}' ";
				$result=pmysql_query($sql,get_db_conn());
				if($_vdata=pmysql_fetch_object($result)) {
					$tempvdeli_info=explode("=", stripslashes($_vdata->deli_info));
					if ($_vdata->deli_info && $tempvdeli_info[0]=="Y") {
						$tempaddr_info=explode("|@|",$_vdata->re_addrinfo);
						$pro_info	.=  "(".$tempaddr_info[0].") ".$tempaddr_info[3]."!@#";
					} else {
						$pro_info	.=  "!@#";
					}
				} else {
					$pro_info	.=  "!@#";
				}
				pmysql_free_result($result);
			} else {
				$pro_info	.=  "!@#";
			}

            list($stock_yn) = pmysql_fetch("Select store_stock_yn from tblorderproduct Where idx = ".$pr_idx."");
            if($stock_yn == "N") $stock_status = "<br>(재고부족)";
            else $stock_status = "";

			$pro_info	.= $can_deli_price."!@#";
			$pro_info	.= $can_total_price."!@#";
			$pro_info	.= ($prVal->option_quantity)."!@#";
			$pro_info	.= $arrDeliveryType[$prVal->delivery_type]."!@#";
			$pro_info	.= $prVal->delivery_type."!@#";
			$pro_info	.= $prVal->reservation_date."!@#";
			$pro_info	.= $storeData['name'];
?>
								<tr id="idx_<?=$pr_idx?>" class="bold"  info = "<?=$pro_info?>">
									<td class="goods_info">
										<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prVal->productcode?>">
											<img src="<?=$file?>" alt="<?=$prVal->productname?>">
											<ul>
												<li>[<?=$prVal->brandname?>]</li>
												<li><?=$prVal->productname?></li>
												<li><?=$optStr?><?if ($prVal->option_price > 0) {?>(+ <?=number_format($prVal->option_price)?>원)<?}?></li>
											</ul>
										</a>
									</td>
									<td><?=number_format($prVal->option_quantity)?></td>
									<td><?=number_format($prVal->price)?>원</td>
									<td>
										<?
											$prVal->delivery_type = $prVal->delivery_type ? $prVal->delivery_type : "0";
										?>
										<span class = 'CLS_store_layer_open' data-delivery_type = '<?=$prVal->delivery_type?>'><?=$arrDeliveryType[$prVal->delivery_type]?></span>
										<div class = "CLS_store_layer">
											<?if($storeData['name'] && $prVal->delivery_type != '2'){	//2016-10-07 libe90 매장발송 정보표시?>
												<p style = 'color:blue;'>[<?=$arrDeliveryType[$prVal->delivery_type]?>] <?=$storeData['name']?></p>
												<?if($prVal->delivery_type == '1'){?>
													<p style = 'color:blue;'>예약일 : <?=$prVal->reservation_date?></p>
												<?}?>
											<?}else if($prVal->delivery_type == '2'){?>
												<p style = 'color:blue;'>[<?=$arrDeliveryType['2']?>] <?=$storeData['name']?></p>
												<p style = 'color:blue;'>주소 : <?=$_ord_receiver_addr?></p>
											<?}?>
										</div>
									</td>
									<?if ($ven_cnt == $rspan_cnt) {?>
									<td<?=$rowspan?>><?=($orvender[$prVal->brand]['t_deli_price'] > 0)?number_format($orvender[$prVal->brand]['t_deli_price'])."원":"무료"?></td>
									<td class="payment2"<?=$rowspan?>><?=number_format($orvender[$prVal->brand]['t_pro_price'])?>원</td>
									<?}?>
									<td>
										<span><?=GetStatusOrder("p", $_ord->oi_step1, $_ord->oi_step2, $prVal->op_step, $prVal->redelivery_type, $prVal->order_conf)?></span><?=$stock_status?>
									<?
									 if( $prVal->op_step == 3 ){ // 배송중일 경우
									?>
										<div class="btn_wrap">
											<p><a href="javascript:;" class="btn-type1 CLS_delivery_tracking" urls = "<?=$delicomlist[$prVal->deli_com]->deli_url.$prVal->deli_num?>">배송추적</a></p>
										</div>
									<?
									}
									?>
									</td>
									<td>
								<? if ($prVal->op_step < 40) { //주문취소 신청및 완료상태가 아닌경우
										if( $prVal->op_step == 1/* || $prVal->op_step == 2 */){ // 입금완료, 배송 준비중일 경우
											if ($op_cnt == 1 || ( $_ord->paymethod[0] == "M" && $_ord->paymethod[1] == "E" ) ) {
												// 주문상품이 한개일경우 전체 취소로 한다.
												// 또는 다날 휴대폰 결제인 경우도 전체 취소로 한다.
												echo "-";
											} else {
								?>
										<div class="btn_wrap">
											<p><a href="javascript:;" class="btn-type1 ord_cancel" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>">환불</a></p>
										</div>
								<?
											}
										} else if( $prVal->op_step == 3 ){ // 배송중일 경우
								?>
										<div class="btn_wrap">
											<p>
												<a href="javascript:;" class="btn-line btn-take-back ord_regoods" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>">반품</a>
												<a href="javascript:;" class="btn-line ord_change" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>">교환</a>
											</p>
											<p><a href="javascript:;" class="btn-type1 deli_ok" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>">구매확정</a></p>
										</div>
								<?
										} else if(  $prVal->op_step == 4) { //배송완료일 경우
											//if ($prVal->order_conf =='1') { // 구매확정인 경우
								?>
										<div class="btn_wrap">
											<p><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prVal->productcode?>" class="btn-type1">리뷰작성</a></p>
										</div>
								<?
											//} else {
								?>
										<!--<div class="btn_wrap">
											<p>
												<a href="javascript:;" class="btn-line btn-take-back ord_regoods" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>">반품</a>
												<a href="javascript:;" class="btn-line ord_change" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>">교환</a>
											</p>
											<p><a href="javascript:;" class="btn-type1 ord_conf" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>">구매확정</a></p>
										</div>-->
								<?
											//}
										} else {
											echo "-";
										}
									} else {
                                        if($prVal->op_step == "40" && $_ord->oi_step1 == "3") {
                                            //echo "-"."/".$_ord->oi_step1."/".$_ord->oi_step2."/".$prVal->op_step."/".$prVal->redelivery_type."/".$prVal->order_conf;
                                ?>
                                            <p><a href="javascript:;" class="btn-type1 ord_req_cancel" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" oc_no ="<?=$prVal->oc_no?>">신청철회</a></p>
                                <?
                                        } else {
                                            echo "-";
                                        }
									}
								?>
									</td>
								</tr>
<?php
				if ($prVal->op_step >= 40) $can_cnt++;
		}
?>
							</tbody>
						</table>
						<div class="total_wrap">
							<div class="total_price clear">
								<span>총 결제금액</span>
								<ul class="clear">
									<li><div>상품 금액 합계 <em><?=number_format($_ord->price)?>원</em></div></li>
									<li><div>할인 <em><?=number_format($_ord->dc_price + $_ord->reserve)?>원</em></div></li>
									<li><div>배송비 <em><?=number_format($_ord->deli_price)?>원</em></div></li>
									<li><div><p>결제금액</p> <em><?=number_format($_ord->price-$_ord->dc_price-$_ord->reserve+$_ord->deli_price)?>원</em></div></li>
								</ul>
							</div>
						</div>
<?
			if ($_ord->oi_step1 < 2 && $can_cnt == 0 && $op_step_cnt == 0) {
				if ($_ord->oi_step1 == 0) {
					$add_text			= "취소";
					$add_class		= " ord_receive_cancel";
				} else {
					$add_text			= "환불";
					$add_class		= " ord_cancel";
				}
?>
						<div class="btn_all_cancel"><a href="javascript:;" class="btn-line<?=$add_class?>" ordercode = "<?=$ordercode?>" idxs = "<?=$pr_idxs?>" pc_type="ALL" paymethod="<?=$_ord->paymethod[0]?>">전체주문<?=$add_text?></a></div>
<?
			}
?>
					</div>
					<!-- // 주문상세내역 리스트 -->

					<!-- 결제정보, 배송지정보 -->
					<div class="table_col2 mt-40">
						<!-- 결제정보 -->
						<div class="fl-l">
							<div class="title_wrap">
								<h3>결제 정보</h3>
							</div>
							<div>
								<table class="th_left">
								<caption></caption>
								<colgroup>
									<col style="width:160px">
									<col style="width:auto">
								</colgroup>
								<tbody>
									<tr>
										<th scope="row">주문금액</th>
										<td><?=number_format($_ord->price)?>원</td>
									</tr>
									<tr>
										<th scope="row">쿠폰할인</th>
										<td><?=($t_product_sale > 0)?number_format($t_product_sale)."원":"없음"?></td>
									</tr>
									<tr>
										<th scope="row">배송비</th>
										<td><?=($_ord->deli_price > 0)?number_format($_ord->deli_price)."원":"무료"?></td>
									</tr>
									<tr>
										<th scope="row">총 결제금액</th>
										<td><?=number_format($_ord->price-$_ord->dc_price-$_ord->reserve+$_ord->deli_price)?>원</td>
									</tr>
									<tr>
										<th scope="row">결제수단</th>
										<td><?=$arpm[$_ord->paymethod[0]]?></td>
									</tr>
<?
								if(strstr("VCPMY", $_ord->paymethod[0])) {
									$subject = "결제일자";
									$o_year = substr($ordercode, 0, 4);
									$o_month = substr($ordercode, 4, 2);
									$o_day = substr($ordercode, 6, 2);
									$o_hour = substr($ordercode, 8, 2);
									$o_min = substr($ordercode, 10, 2);
									$o_sec = substr($ordercode, 12, 2);

									$msg = $o_year."-".$o_month."-".$o_day." ".$o_hour.":".$o_min.":".$o_sec;
								} else if (strstr("BOQ", $_ord->paymethod[0])) {
									$_ord_pay_data = explode(" ", $_ord->pay_data);
									if ($_ord->bank_date >= 14) {
										$o_year = substr($_ord->bank_date, 0, 4);
										$o_month = substr($_ord->bank_date, 4, 2);
										$o_day = substr($_ord->bank_date, 6, 2);
										$o_hour = substr($_ord->bank_date, 8, 2);
										$o_min = substr($_ord->bank_date, 10, 2);
										$o_sec = substr($_ord->bank_date, 12, 2);

										$bank_date_msg = $o_year."-".$o_month."-".$o_day." ".$o_hour.":".$o_min.":".$o_sec;
									}
									if(strstr("B", $_ord->paymethod[0])){
										$subject = "입금자명";
										$subject2 = "입금은행";
										$subject3 = "입금계좌";
										$msg = $_ord->bank_sender;
										$msg2 = $_ord_pay_data[0];
										$msg3 = $_ord_pay_data[1].' '.$_ord_pay_data[2];
										if ($bank_date_msg) {
											$subject4	= "입금확인";
											$msg4		= $bank_date_msg;
										}
									}else{
										$subject = "입금은행";
										$subject2 = "입금계좌";
										$msg = $_ord_pay_data[0];
										$msg2 = $_ord_pay_data[1].' '.$_ord_pay_data[2];
										if ($bank_date_msg) {
											$subject3	= "입금확인";
											$msg3		= $bank_date_msg;
										} else {
											if($_ord->pay_flag=="0000"){
												$subject3 = "입금확인";
												$msg3 = "입금 대기중";
											}
										}
									}
								}
?>
								<?if ($subject) {?>
								<tr>
									<th scope="row"><?=$subject?></th>
									<td><?=$msg?></td>
								</tr>
								<?}?>
								<?if ($subject2) {?>
								<tr>
									<th scope="row"><?=$subject2?></th>
									<td><?=$msg2?></td>
								</tr>
								<?}?>
								<?if ($subject3) {?>
								<tr>
									<th scope="row"><?=$subject3?></th>
									<td><?=$msg3?></td>
								</tr>
								<?}?>
								<?if ($subject4) {?>
								<tr>
									<th scope="row"><?=$subject4?></th>
									<td><?=$msg4?></td>
								</tr>
								<?}?>
								</tbody>
								</table>
							</div>
						</div>
						<!-- // 결제정보 -->

						<!-- 배송지 정보 -->
						<div class="fl-r">
							<div class="title_wrap">
								<h3>배송지 정보</h3>
								<?if ($_ord->oi_step1 < 2 && $_ord->oi_step2 < 40 && $op_step_cnt == 0 && $op_step_chk != 3 && $chkDeliveryType == true) {?>
								<span class="btn_address"><a href="javascript:;" class="btn-type1 btn-pop-address">배송지 변경</a></span>
								<?}?>
							</div>
							<div>
								<table class="th_left">
								<caption></caption>
								<colgroup>
									<col style="width:160px">
									<col style="width:auto">
								</colgroup>
								<tbody>
									<tr>
										<th scope="row">받는사람</th>
										<td id="td_receiver_name"><?=$_ord->receiver_name?></td>
									</tr>
									<tr>
										<th scope="row">휴대폰</th>
										<td id="td_receiver_tel2"><?=$_ord->receiver_tel2?></td>
									</tr>
									<tr>
										<th scope="row" rowspan="2">주소</th>
										<td rowspan="2" id="td_receiver_addr"><?if($_ord->deli_type == "2"){ echo "해당 주문은 고객 [직접수령] 입니다"; } else { echo str_replace("주소 :","]",str_replace("우편번호 : ","[ ",$_ord->receiver_addr)); }?></td>
									</tr>
									<tr></tr>
									<tr>
										<th scope="row">배송 요청사항</th>
										<td><?=$_ord->order_msg2?$_ord->order_msg2:"-"?></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<!-- // 배송정보 -->
					</div>
					</form>
					<div class="btn_confirmation mt-50"><a href="mypage_orderlist.php" class="btn-type1 c1">확인</a></div>
				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->

<!-- 반품/환불 신청 dimm layer -->
<div class="layer-dimm-wrap pop-take-back layer-refund regoods">
    <div class="dimm-bg"></div>
    <div class="layer-inner w1000">
        <h3 class="layer-title">HOT<span class="type_txt1">-T</span> 환불/반품신청</h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content scroll">
			<table class="th_top util" id="rg_list">
				<caption></caption>
				<colgroup>
					<col style="width:10%">
					<col style="width:auto">
					<col style="width:5%">
					<col style="width:10%">
					<col style="width:10%">
					<col style="width:10%">
					<col style="width:11%">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col">상품정보</th>
						<th scope="col">수량</th>
						<th scope="col">상품금액</th>
						<th scope="col">배송비</th>
						<th scope="col">할인금액</th>
						<th scope="col">결제금액</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="total_wrap" id="rg_total">
				<div class="total_price clear refund-money">
				</div>
			</div>
			<div class="txt_area">
				<strong>* 유의사항</strong>
				<span class="ml-20">ㆍ할인 금액, 배송비를 제외된 금액으로 환불됩니다.</span>
				<span class="ml-40">
					ㆍ결제 수단별 환불 방법과 환불소요기간에 차이가 있습니다.
					<a href="#" class="btn"><img src="/sinwon/web/static/img/btn/btn_minutely.gif"></a>
				</span>
			</div>
			<!-- 환불정보입력 -->
			<div class="table_col2 mt-40">

				<div class="fl-l">
					<div class="refund-reason-info">
						<table class="th_left">
						<caption></caption>
						<colgroup>
							<col style="width:100px">
							<col style="width:auto">
						</colgroup>
						<tbody>
							<tr id="tr_refund">
								<th scope="row">환불사유 <span class="required">*</span></th>
								<td>
									<div class="my-comp-select">
										<select name="b_sel_code" id="refund-type" style="width:150px" class="tab-select">
											<option value="">선택</option>
<?php
										$oc_reason_sub_code_html = "";
										$oc_reason_sub_code_html .= '<div class="mt-10 checkbox-set">';
										foreach($cancel_oc_code as $key => $val) {
?>
											<option value="<?=$key?>"><?=$val['name']?></option>
<?
											if($val['detail_code']) {
												$oc_rsc_addClass	= " hide";
												$oc_reason_sub_code_html .= '
													<div class="mt-10 CLS_sel_sub_code chk_sub_code_'.$key.$oc_rsc_addClass.'">
												';
												foreach($val['detail_code'] as $c2key => $c2val) {
													$oc_reason_sub_code_html	.= '
																						<input id="checkbox-'.$key.$c2key.'" class="b_sel_sub_code" type="checkbox" name="b_sel_sub_code" value="'.$c2key.'">
																						<label for="checkbox-'.$key.$c2key.'">'.$c2val.'</label>
																					';
												}
												$oc_reason_sub_code_html .= '</div>';
											}
										}
										$oc_reason_sub_code_html .= '</div>';
?>
										</select>
									</div>
									<?=$oc_reason_sub_code_html?>
								</td>
							</tr>
							<tr id="tr_return">
								<th scope="row">반품사유 <span class="required">*</span></th>
								<td>
									<div class="my-comp-select">
										<select name="b_sel_code2" id="refund-type" style="width:150px" class="tab-select">
											<option value="">선택</option>
<?php
										$oc_reason_sub_code_html = "";
										$oc_reason_sub_code_html .= '<div class="mt-10 checkbox-set">';
										foreach($return_oc_code as $key => $val) {
?>
											<option value="<?=$key?>"><?=$val['name']?></option>
<?
											if($val['detail_code']) {
												$oc_rsc_addClass	= " hide";
												$oc_reason_sub_code_html .= '
													<div class="mt-10 CLS_sel_sub_code chk_sub_code_'.$key.$oc_rsc_addClass.'">
												';
												foreach($val['detail_code'] as $c2key => $c2val) {
													$oc_reason_sub_code_html	.= '
																						<input id="checkbox-'.$key.$c2key.'" class="b_sel_sub_code" type="checkbox" name="b_sel_sub_code" value="'.$c2key.'">
																						<label for="checkbox-'.$key.$c2key.'">'.$c2val.'</label>
																					';
												}
												$oc_reason_sub_code_html .= '</div>';
											}
										}
										$oc_reason_sub_code_html .= '</div>';
?>
										</select>
									</div>
									<?=$oc_reason_sub_code_html?>
									
									<!-- 20161018 반품사유에 따른 체크사항 -->
									<!-- 
									<div class="mt-10 checkbox-set">
										<input id="checkbox-51" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="1">
										<label for="checkbox-51">갑피불량</label>

										<input id="checkbox-52" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="2">
										<label for="checkbox-52">인솔불량</label>

										<input id="checkbox-53" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="3">
										<label for="checkbox-53">재봉불량</label>

										<input id="checkbox-54" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="4">
										<label for="checkbox-54">오염</label>

										<input id="checkbox-55" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="5">
										<label for="checkbox-55">스크레치</label>

										<input id="checkbox-56" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="6">
										<label for="checkbox-56">접착불량</label>

										<input id="checkbox-57" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="7">
										<label for="checkbox-57">로고불량</label>

										<input id="checkbox-58" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="8">
										<label for="checkbox-58">뒤축불량</label>

										<input id="checkbox-59" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="9">
										<label for="checkbox-59">TAG없음</label>

										<input id="checkbox-510" class="chk_agree checkbox-def" type="checkbox" name="b_sel_sub_code" value="10">
										<label for="checkbox-510">기타</label>
									</div>
									 -->
									<!-- // 20161018 반품사유에 따른 체크사항 -->

								</td>
							</tr>
							<tr>
								<th scope="row">상세사유 <span class="required">*</span></th>
								<td>
									<textarea id="refund-reason-info" name="memo" cols="5" rows="3" style="width:360px"></textarea>
								</td>
							</tr>
<?
				if ($_ord->paymethod[0] == 'C') { // 카드결제일 경우
					$refund_text	= "신용카드 취소";
					$account_disabled	= " disabled";
				} else if ($_ord->paymethod[0] == 'M') { // 휴대폰결제일 경우
					$refund_text	= "휴대폰결제 취소";
					$account_disabled	= " disabled";
				} else if ($_ord->paymethod[0] == 'Y') { // 페이코결제일 경우
					$refund_text	= "PAYCO결제 취소";
					$account_disabled	= " disabled";
				} else if ($_ord->paymethod[0] == 'V') { // 계좌이체결제일 경우
					$refund_text	= "계좌이체결제 취소";
					$account_disabled	= " disabled";
				} else if ($_ord->paymethod[0] == 'G') { // 임직원 포인트결제일 경우
					$refund_text	= "임직원 포인트 환원";
					$account_disabled	= " disabled";
				} else {
					$refund_text	= "계좌입금(무통장/가상계좌 입금의 경우는 계좌입금만 가능)";
					$account_disabled	= "";
				}
?>
							<tr>
								<th scope="row">환불방법 <span class="required">*</span></th>
								<td><span class='refund-way'><?=$refund_text?></span><span class='refund-way2 hide'>계좌입금(무통장/가상계좌 입금의 경우는 계좌입금만 가능)</span></td>
							</tr>
						</tbody>
						</table>
					</div>
				</div>

				<div class="fl-r">
					<div class="account-info">
						<table class="th_left">
						<caption></caption>
						<colgroup>
							<col style="width:100px">
							<col style="width:auto">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">은행명 <span class="required">*</span></th>
								<td>
									<div class="my-comp-select">
										<select name="bankcode" id="refund-type-bankcode" <?=$account_disabled?>>
											<option value="" selected>선택</option>
<?php
										foreach($oc_bankcode as $key => $val) {
?>
											<option value="<?=$key?>"><?=$val?></option>
<?php
										}
?>
										</select>
									</div></td>
							</tr>
							<tr>
								<th scope="row">계좌번호 <span class="required">*</span></th>
								<td><input type="text" class='chk_only_number' id="account-number" name="bankaccount" maxlength="20" placeholder="하이픈(-) 없이 입력" title="계좌번호 입력자리" style="ime-mode:disabled;"<?=$account_disabled?>></td>
							</tr>
							<tr>
								<th scope="row">예금주 <span class="required">*</span></th>
								<td><input type="text" id="account-name" name="bankuser" maxlength="20" placeholder="이름" title="이름 입력자리"<?=$account_disabled?>></td>
							</tr>
							<tr>
								<th scope="row">연락처 <span class="required">*</span></th>
								<td><input type="text" class='chk_only_number' id="account-tel" name="bankusertel" maxlength="20" placeholder="하이픈(-) 없이 입력" title="연락처 입력자리" style="ime-mode:disabled;"<?=$account_disabled?>></td>
							</tr>
						</tbody>
						</table>
					</div>
				</div>
				<!-- // 환불정보입력 -->
			</div>

			<!-- [D] 택배비 추가 -->
			<div class="parcel-wrap" id="parcel_pay">
				<span class="tit">택배비 발송:</span>
				<?
				$oc_delivery_fee_type_cnt = 0;
				foreach($delivery_fee_type as $key => $val) {
 				?>
				<?if($key  == "3"){ ?>
					<p>
						<input type="radio"  id="radio-delivery-fee<?=$key?>" value="<?=$key ?>" name="return_deli_type" >
						<label for="radio-delivery-fee<?=$key?>"><?=$val ?></label>
						<input type="text" value="" name="return_deli_memo" id="return_deli_memo" placeholder="입금자명">
					</p>
				<?}else{ ?>
					<input type="radio" id="radio-delivery-fee<?=$key?>" value="<?=$key ?>" name="return_deli_type" >
					<label for="radio-delivery-fee<?=$key?>"><?=$val ?></label>
				<?} ?>
			<?} ?>
			</div>
			<input type=hidden name="return_deli_price" id="return_deli_price" value=""  >
			<input type="hidden" name="return_deli_receipt" id="return_deli_receipt" title="택배비 수령" value=""></td>
			<input type="hidden" name="receiver_tel1" id="receiver_tel1" value="<?=$_ord->receiver_tel1?>"

			<!-- [D] 택배비 추가 -->

			<!-- 안내 -->
			<div class="list_text mt-20">
				<h3>유의사항</h3>
				<ul>
					<li>ㆍ상품이 손상/훼손 되었거나 이미 사용하셨다면 반품이 불가능합니다</li>
					<li>ㆍ반품 사유가 단순변심, 구매자 사유일 경우반품 배송비를 상품과 함께 박스에 동봉해 주세요</li>
					<li>ㆍ배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다</li>
					<li>ㆍ반품 사유가 상품불량/파손, 배송누락/오배송 등 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다</li>
					<li>ㆍ상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다</li>
				</ul>
			</div>
			<!-- // 안내 -->

			<div class="btn_wrap"><a href="javascript:;" class="btn-type1 c1 refundSubmit">신청</a></div>
		</div>
	</div>
</div>
<!-- // 반품신청 팝업 -->

<!-- 교환신청 신청 dimm layer -->
<div class="layer-dimm-wrap pop-exchange layer-refund change">
    <div class="dimm-bg"></div>
    <div class="layer-inner w1000">
        <h3 class="layer-title">HOT<span class="type_txt1">-T</span> 교환신청</h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">
			<table class="th_top util" id="cg_list">
				<caption></caption>
				<colgroup>
					<col style="width:10%">
					<col style="width:auto">
					<col style="width:20%">
					<col style="width:12%">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col">상품정보</th>
						<th scope="col">변경할 옵션</th>
						<th scope="col">결제금액</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

			<!-- 교환정보 입력 -->
			<div class="exchange exchange-reason-info">
			<table class="th_left">
				<caption></caption>
				<colgroup>
					<col style="width:100px">
					<col style="width:auto">
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">교환사유 <span class="required">*</span></th>
						<td>
							<div class="my-comp-select">
								<select name="c_sel_code" id="change-type" style="width:150px" class="tab-select">
									<option value="" selected>선택</option>
<?php
										$oc_reason_sub_code_html = "";
										$oc_reason_sub_code_html .= '<div class="mt-10 checkbox-set">';
										foreach($exchange_oc_code as $key => $val) {
?>
											<option value="<?=$key?>"><?=$val['name']?></option>
<?
											if($val['detail_code']) {
												$oc_rsc_addClass	= " hide";
												$oc_reason_sub_code_html .= '
													<div class="mt-10 CLS_sel_sub_code chk_sub_code_'.$key.$oc_rsc_addClass.'">
												';
												foreach($val['detail_code'] as $c2key => $c2val) {
													$oc_reason_sub_code_html	.= '
																						<input id="checkbox-'.$key.$c2key.'" class="b_sel_sub_code" type="checkbox" name="b_sel_sub_code" value="'.$c2key.'">
																						<label for="checkbox-'.$key.$c2key.'">'.$c2val.'</label>
																					';
												}
												$oc_reason_sub_code_html .= '</div>';
											}
										}
										$oc_reason_sub_code_html .= '</div>';
?>
								</select>
							</div>
							<?=$oc_reason_sub_code_html?>
						</td>
					</tr>
					<tr>
						<th scope="row">상세사유 <span class="required">*</span></th>
						<td>
							<textarea id="exchange-reason-info" name="memo" cols="5" rows="3" style="width:100%"></textarea>
						</td>
					</tr>
				</tbody>
				</table>
			</div>
			<!-- // 교환정보 입력 -->

			<!-- [D] 택배비 추가 -->
			<div class="parcel-wrap on">
				<span class="tit">택배비 발송:</span>
				<?
				$oc_delivery_fee_type_cnt = 0;
				foreach($delivery_fee_type as $key => $val) {
 				?>
				<?if($key  == "3"){ ?>
					<p>
						<input type="radio"  id="radio-delivery-fee<?=$key?>" value="<?=$key ?>" name="return_deli_type" >
						<label for="radio-delivery-fee<?=$key?>"><?=$val ?></label>
						<input type="text" value="" name="return_deli_memo" id="return_deli_memo2" placeholder="입금자명">
					</p>
				<?}else{ ?>
					<input type="radio" id="radio-delivery-fee<?=$key?>" value="<?=$key ?>" name="return_deli_type" >
					<label for="radio-delivery-fee<?=$key?>"><?=$val ?></label>
				<?} ?>
			<?} ?>
			</div>

			<!-- [D] 택배비 추가 -->

			<!-- 안내 -->
			<div class="list_text mt-20">
				<h3>유의사항</h3>
				<ul>
					<li>ㆍ 교환은 같은 옵션상품만 가능합니다. 다른 옵션의 상품으로 교환을 원하실 경우, 반품 후 재구매를 해주세요</li>
					<li>ㆍ 상품이 손상/훼손되었거나 이미 사용하셨다면 교환이 불가능합니다</li>
					<li>ㆍ 교환 사유가 구매자 사유일 경우 왕복 교환 배송비를 상품과 함께 박스에 동봉해 주세요</li>
					<li>ㆍ 교환 왕복 배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다</li>
					<li>ㆍ 교환 사유가 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다</li>
					<li>ㆍ 상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다</li>
				</ul>
			</div>
			<!-- // 안내 -->
		</div>
		<div class="btn_wrap"><a href="javascript:;" class="btn-type1 c1 refundSubmit">신청</a></div>
	</div>
</div>
<!-- // 교환신청 팝업 -->

<!-- 배송지 변경 팝업-->
<div class="layer-dimm-wrap pop-address-add"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner w500">
		<h3 class="layer-title">HOT<span class="type_txt1">-T</span> 배송지 변경</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<table class="th_left">
			<caption></caption>
			<colgroup>
				<col style="width:100px">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<tr>
					<th scope="row">배송지 선택 <span class="required">*</span></th>
					<td>
						<div class="my-comp-select" style="width:150px">
							<select name="destination_sel" id="destination-sel" style="width:150px">
								<option value="" selected desinfo="">직접입력</option>
							<?foreach( $deliAddressList as $key=>$val ){?>
								<option value="<?=$val->no?>" desinfo="<?=$val->destination_name."|@|".$val->get_name."|@|".str_replace('-','',$val->mobile)."|@|".$val->postcode."|@|".$val->postcode_new."|@|".$val->addr1."|@|".$val->addr2."|@|".$val->base_chk?>"><?=$val->destination_name.$val->base_chk_text?></option>
							<?}?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">배송지 명 <span class="required">*</span></th>
					<td>
						<input type="text"name="destination_name" id="destination_name"  placeholder="배송지 명" title="배송지 명 입력자리" label = "배송지 명" class="required_value" maxlength="20" style="width:200px;">
						<span class='base_chk_area'><input id="base_chk" name="base_chk" type="checkbox" class="chk_agree checkbox-def ml-5 base_chk" value="Y"><label for="base_chk"> 기본 배송지로 설정</label></span>
					</td>
				</tr>
				<tr>
					<th scope="row">받는사람 <span class="required">*</span></th>
					<td>
						<input type="text" class="required_value" name="get_name" id="get_name" placeholder="이름" title="이름 입력자리" label = "받는사람" maxlength="20" style="width:200px;">
					</td>
				</tr>
				<tr>
					<th scope="row">휴대폰 <span class="required">*</span></th>
					<td><input type="text" class="chk_only_number required_value" name="mobile" id="mobile" placeholder="하이픈(-) 없이 입력" title="휴대폰 입력자리" label = "휴대폰" maxlength="20" style="width:200px;ime-mode:disabled;"></td>
				</tr>
				<tr>
					<th scope="row">주소 <span class="required">*</span></th>
					<td>
						<div class="input-wrap">
							<p><input type="text" class="required_value" name="postcode_new" id="postcode_new" title="우편번호 앞자리" label = "우편번호" style="width:130px;" readonly><a href="javascript:search_zip();" class="ml-5 btn-type1 btn_sh_zip">주소찾기</a></p>
							<p><input type="hidden" name="postcode" id="postcode" title="우편번호(구)" style="width:130px;"></p>
							<p><input type="text" class="required_value" name="addr1" id="addr1" title="주소" label = "주소"  style="width:100%;" readonly></p>
							<p><input type="text" class="required_value" name="addr2" id="addr2" title="상세주소" label = "상세주소" style="width:100%;"></p>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
			<div class="btn_wrap mt-40"><a href="javascript:;" class="btn-type1 c1 btnAddressSubmit" ordercode = "<?=$ordercode?>">신청</a></div>
		</div>
	</div>
</div>
<!-- // 배송지 변경 팝업 -->

<script>
// 셀렉트 탭
$(window).ready(function(){

	var blockNum = 4;
	var showNum = "";
	$('.tab-select').on('change', function(){
/*			if($(this).children('option:selected').index() == blockNum)
		{
			$('.parcel-wrap').addClass('on');
		}else{
			$('.parcel-wrap').removeClass('on');
		}
*/
		var val = $(this).val();
		if(showNum == ""){
			showNum = val;
			$('.chk_sub_code_'+val).show();
		}else{
			$('.chk_sub_code_'+val).show();
			$('.chk_sub_code_'+showNum).hide();
			showNum = val;
		}

	});

	//택배비 셋팅
	$("input[name=return_deli_type]").change(function() {
		var val = $(this).val();
		if(val == "1" || val == "3"){
			$("input[name=return_deli_price]").val("5000");
		}else if(val == "2"){
			$("input[name=return_deli_price]").val("2500");
		}else{
			$("input[name=return_deli_price]").val("0");
		}
	});

});
</script>
