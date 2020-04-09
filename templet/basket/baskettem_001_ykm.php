<div id="contents">
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<article class="cart_wrap">
				<section class="mypage_main">
					<div class="order_flow">
						<ul>
							<li class="on"><div>장바구니</div></li>
							<li><div>주문하기</div></li>
							<li><div>결제완료</div></li>
						</ul>
					</div>

					<!-- 담은 상품 -->
					<!-- 벤더 단위 시작 -->
<?php
$all_reserve = 0;
$bf_sumprice = 0;
$all_deli_price = 0;
foreach( $brandArr as $brand=>$brandObj ){
	$brand_name = get_brand_name( $brand );
	$vender	=$brandVenderArr[$brand];
?>
					<div class="title_type1">
						<h3 class="txt_s">[<?=$brand_name?>]</h3>
					</div>

					<!-- 장바구니 리스트1 -->
					<div class="order_list_wrap cart">
						<table class="th_top CLS_basketTotalCount">
							<caption></caption>
							<colgroup>
								<col style="width:5%">
								<col style="width:auto">
								<col style="width:10%">
								<col style="width:10%">
								<col style="width:10%">
								<col style="width:10%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="adf" class="allCheck"></th>
									<th scope="col">상품정보</th>
									<th scope="col">옵션</th>
									<th scope="col">수량</th>
									<th scope="col">결제금액</th>
									<th scope="col">구매/좋아요</th>
								</tr>
							</thead>
							<tbody>
				<!-- 상품단위 시작 -->
<?php
	$product_price = 0;
	foreach( $brandObj as $product ) {
		$sizeString = "";
		$storeData = getStoreData($product['store_code']);
		$all_reserve += $product['reserve'];
		$product_price = ( $product['price'] + $product['option_price'] ) * $product['option_quantity'];
		$bf_sumprice += $product_price;

        $hsql = "Select count(*) From tblhott_like Where like_id = '".$_ShopInfo->getMemid()."' and section = 'product' and hott_code = '".$product['productcode']."'";
        list($hcnt) = pmysql_fetch($hsql, get_db_conn());

        if($hcnt) {
            $like_type = "unlike";
            $like_class = "user_like";
        }else {
            $like_type = "like";
            $like_class = "user_like_none";
        }
?>
								<tr class="bold" name='product_list'>
									<td><input type="checkbox" name='checkBasket' id='' value='<?=$product['basketidx']?>' data-delivery_type = "<?=$product['delivery_type']?>"></td>
									<td class="goods_info">
										<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
											<img src="<?=getProductImage($productImgPath,$product['tinyimage'])?>" alt="<?=$product['productname']?>">
											<ul>
												<li>[<?=$brand_name?>]</li>
												<li><?=$product['productname']?></li>
												<?if($product['delivery_type'] == '1'){?>
													<li style = 'color:blue;'>[<?=$arrDeliveryType['1']?>] <?=$storeData['name']?></li>
													<li style = 'color:blue;'>예약일 : <?=$product['reservation_date']?></li>
												<?}else if($product['delivery_type'] == '2'){?>
													<li style = 'color:blue;'>[<?=$arrDeliveryType['2']?>] <?=$storeData['name']?></li>
													<li style = 'color:blue;'>주소 : [<?=$product['post_code']?>] <?=$product['address1']?> <?=$product['address2']?></li>
												<?}?>
											</ul>
										</a>
									</td>
									<td>
<?php
		if( strlen( $product['opt1_name'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
?>
										<span class="opt_txt">
<?php
			if( strlen( $product['opt1_name'] ) > 0 ){ // 옵션

				if( $product['option_type'] == 0 ){ //조합형 옵션
					$tmpOptName = explode( '@#', $product['opt1_name'] );
					$tmpOptVal = explode( chr(30), $product['opt2_name'] );
					$tmpOptCnt	= 0;
					foreach( $tmpOptName as $tmpKey=>$tmpVal ){
						if( $tmpVal ){
							if ($tmpOptCnt > 0) echo '/&nbsp;';
							echo $tmpVal.':'.$tmpOptVal[$tmpKey].'&nbsp;';
							$sizeString = $tmpOptVal[$tmpKey];
							$tmpOptCnt++;
						}
					}
				}

				if( $product['option_type'] == 1 ){ // 독립형 옵션
					$tmpOptName = explode( '@#', $product['opt1_name'] );
					$tmpOptVal = explode( '@#', $product['opt2_name'] );
					$tmpOptCnt	= 0;
					foreach( $tmpOptName as $tmpKey=>$tmpVal ){
						if( $tmpVal ){
							$tmpOptVal1	=	explode( chr(30), $tmpOptVal[$tmpKey]);
							if ($tmpOptCnt > 0) echo '/&nbsp;';
							echo $tmpVal.':'.$tmpOptVal1[1].'&nbsp;';
							$sizeString = $tmpOptVal1[1];
						}
					}
				}

			}

			if( strlen( $product['text_opt_subject'] ) > 0 ) { // 추가 문구 옵션
				$tmpOptSubject = explode( '@#', $product['text_opt_subject'] );
				$tmpOptContent = explode( '@#', $product['text_opt_content'] );
				foreach( $tmpOptSubject as $tmpKey=>$tmpVal ){
					if( $tmpVal ){
						echo '/&nbsp;'.$tmpVal.':'.$tmpOptContent[$tmpKey].'&nbsp;';
					}
				}
			}

			if( strlen( $product['opt1_name'] ) > 0 && $product['option_price'] > 0 ){
				echo '&nbsp;( + '.number_format( $product['option_price'] ).' 원)';
			}

?>
										</span>
										<?if($product['delivery_type'] == '0'){?>
											<div class="btn_opt1"><button type="button" class="btn-change tr-open"><span>변경</span></button></div>
										<?}?>
<?php
		} else {
?>
										<span class="opt_txt">-</span>
<?
		}
?>
									</td>
									<td>
										<p><?=number_format($product['quantity'])?>개</p>
										<?if($product['delivery_type'] == '0'){?>
											<div class="btn_opt1"><button type="button" class="btn-change tr-open"><span>변경</span></button></div>
										<?}?>
										<div style = 'margin-top:3px;'>
											<span style = 'color:red;'>
												<?if(in_array(($product['productcode'].$sizeString.$product['store_code']), $stockSoldoutArray, true)){?>
													[<?=$sizeString?>] 재고부족
												<?}?>
											</span>
										</div>
									</td>
									<td class="payment"><?=number_format( $product_price )?>원</td>
									<td class="like_<?=$product['productcode']?>">
										<div class="btn_wrap">
											<p class="btn_order"><a href="javascript:one_order('N','<?=$product['basketidx']?>');" class="btn-type1">바로구매</a></p>
											<?if($_ShopInfo->getStaffYn() == 'Y'){?>
											<p class="btn_order"><a href="javascript:one_order('Y','<?=$product['basketidx']?>');" class="btn-type1">임직원구매</a></p>
											<?}?>
											<p class="<?=$like_class?>"><a href="javascript:product_like('<?=$product['productcode']?>', '<?=$like_type?>')">좋아요</a></p>
										</div>
									</td>
								</tr>
								<!-- [D] 옵션변경시 노출 영역 -->
								<tr class="opt-change">
									<td colspan="6" name='NM_options' data-productcode='<?=$product['productcode']?>' >
						<!-- 옵션정보 시작 -->
<?php
		if( strlen( $product['opt1_name'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
?>
										<div class="wrap" >
											<label for="">상품옵션</label>
<?
		//explode( '@#', $product['opt1_name'] );
		//explode( '@#', $product['text_opt_subject'] );
		$display_cnt = 0;
		if( strlen( $product['opt1_name'] ) > 0 ){
			$display_cnt += count( explode( '@#', $product['opt1_name'] ) );
		}
		if( strlen( $product['text_opt_subject'] ) > 0 ){
			$display_cnt += count( explode( '@#', $product['text_opt_subject'] ) );
		}
		if( strlen( $product['opt1_name'] ) > 0 && $display_cnt < 3 ){
			if( $product['option_type'] == 0 ){ //조합형 옵션
				$tmpOptName = explode( '@#', $product['opt1_name'] );
				$tmpOptVal = explode( chr(30), $product['opt2_name'] );
				$option_depth = count( $tmpOptName );
				//$get_option_arr = get_option( $product['productcode'], '', 0, 'all' ); //옵션정보
				foreach( $tmpOptName as $optNameKey=>$optNameVal ){
					$tmpOptCode = ''; //자신의 옵션값
					$get_option = ''; // 옵션정보
					$optCode = ''; // 부모 옵션값
					for( $code_i = 0; $code_i < $optNameKey + 1; $code_i++ ){

						if( $code_i == 0 ){
							$tmpOptCode .= $tmpOptVal[$code_i];
						} else {
							$tmpOptCode .= chr(30).$tmpOptVal[$code_i];
						}
					}

					$optCode = substr( $tmpOptCode, 0, strrpos( $tmpOptCode, chr(30) ) ); // 해당 옵션 부모값
					$get_option = get_option( $product['productcode'], $optCode, $optNameKey, 'all' ); //옵션정보
					//exdebug( $get_option );
?>
											<div class="opt-change-list">
												<select name='op_opt[]' class="my_value CLS_option_value" title="<?=$optNameVal?>" data-option-code='<?=$tmpOptCode?>'>
													<option value="" data-qty='' data-code=''><?=$optNameVal?></option>
<?php
					if( count( $get_option ) > 0 ){
							foreach( $get_option as $optVal ){
								$option_qty = $optVal['qty']; // 수량
								$option_disable = ''; // disabled
								$option_text = ''; // 품절 text
								$option_selected = ''; // 선택값 selected
								$priceText = '';

								if( ( $optNameKey + 1 == $option_depth ) && $optVal['price'] > 0 ){
									$priceText = ' ( + '.number_format($optVal['price']).' 원 )';
								} else if( ( $optNameKey + 1 == $option_depth ) && $optVal['price'] < 0 ) {
									$priceText = ' ( - '.number_format($optVal['price']).' 원 )';
								}
								if(
									( $option_qty !== null && $option_qty <= 0 ) &&
									( ( $option_depth > 0 && $nameKey != 0 ) || ( $option_depth == 1 && $optNameKey == 0 ) ) &&
									$product['quantity'] < 999999999
								){
									$option_disable = ' disabled';
									$option_text = '[품절]';
								}
								if( strlen( $optCode ) > 0 ) {
									if( $tmpOptCode == $optCode.chr(30).$optVal["code"] ) $option_selected = ' selected';
								} else {
									if( $tmpOptCode == $optVal["code"] ) $option_selected = ' selected';
								}

								if( $optNameKey > 0 ){
									$data_code = $optCode.chr(30).$optVal["code"];
								} else {
									$data_code = $optVal["code"];
								}

?>
													<option value="<?=$oVal["code"]?>" <?=$option_disable?> data-qty='<?=$option_qty?>' data-code='<?=$data_code?>'<?=$option_selected?>><?=$option_text.$optVal["code"].$priceText?></option>
<?php
							} // get_option foreach
					} // option1 if
?>
												</select>
											</div>


<?php
				} // tmpOptName foreach
			} // option_type  0 if

			if( $product['option_type'] == 1 ){ // 독립형 옵션
				$alone_option = get_alone_option( $product['productcode'] );
				$tmpOptName = explode( '@#', $product['opt1_name'] );
				$tmpOptVal = explode( '@#', $product['opt2_name'] );
				//exdebug( $alone_option );
				foreach( $tmpOptName as $optNameKey=>$optNameVal ){
					$tmpOptName = explode( chr(30), $tmpOptVal[$optNameKey] );
					if( $tmpOptName[1] == '' ) {
						$tmpName = $optNameVal;
					} else {
						$tmpName = $tmpOptName[1];

						$tmpOptionPrice	= $alone_option[$tmpOptName[0]][$tmpOptName[1]]->option_price;
						$tmpOptionPriceText	= "";
						if( $tmpOptionPrice > 0 ){
							$tmpOptionPriceText = ' ( + '.number_format( $tmpOptionPrice ).' 원 )';
						} else if( $aloneVal->option_price < 0 ) {
							$tmpOptionPriceText = ' ( - '.number_format( $tmpOptionPrice ).' 원 )';
						}
					}
?>
											<div class="opt-change-list" name='alone_option'>
												<select name='op_alone_opt[]' class="my_value" title="<?=$optNameVal?>" data-option-code='<?=$tmpOptVal[$optNameKey]?>'>
													<option value="" data-qty='' data-code=''><?=$optNameVal?></option>
<?php
					if( $optNameVal != ''){
						foreach( $alone_option[$optNameVal] as $aloneKey=>$aloneVal ){
							if( $tmpOptVal[$optNameKey] == $aloneVal->option_code ) {
								$option_selected = ' selected';
							} else {
								$option_selected = '';
							}
							$priceText = "";
							if( $aloneVal->option_price > 0 ){
								$priceText = ' ( + '.number_format( $aloneVal->option_price ).' 원 )';
							} else if( $aloneVal->option_price < 0 ) {
								$priceText = ' ( - '.number_format( $aloneVal->option_price ).' 원 )';
							}
?>
													<option value="<?=$oVal["code"]?>" <?=$option_disable?> data-qty='<?=$aloneVal->option_quantity?>' data-code='<?=$aloneVal->option_code?>'<?=$option_selected?>><?=$aloneKey.$priceText?></option>
<?php
						} // alone_option foreach
					} // tmpOptName if
?>
<?php
					if( $aloneVal->option_tf == 'T' ) {
						echo ' * 필수';
						$tmp_alone_tf = 'T';
					} else {
						$tmp_alone_tf = 'F';
					}
?>
												</select>
												<input type='hidden' name='alone_option_tf' value='<?=$tmp_alone_tf?>' >
											</div>
<?php
				} // tmp_option_subject foreach
			} // option_type 1 if

		} //opt1_name if


		if( strlen( $product['text_opt_subject'] ) > 0 && $display_cnt < 3 ){ // 추가문구 옵션
			$tmpOptSubject = explode( '@#', $product['text_opt_subject'] );
			$tmpOptContent = explode( '@#', $product['text_opt_content'] );
			$tmpOpt_tf     = explode( '@#', $product['text_opt_tf'] );
			foreach( $tmpOptSubject as $optSubKey=>$optSubVal ){
?>
											<div class="opt-change-list" name='add_option'>
												<input type='text' name='add_option_val' value='<?=$tmpOptContent[$optSubKey]?>'  placeholder="<?=$optSubVal?>">
<?php
					if( $tmpOpt_tf[$optSubKey] == 'T' ) {
						echo ' * 필수';
						$tmp_tf = 'T';
					} else {
						$tmp_tf = 'F';
					}
?>
												<input type='hidden' name='add_option_tf[]' value='<?=$tmp_tf?>' >
											</div>
<?php
			} // tmpOpt2Name foreach
		} // opt2_name if

		if( (( strlen( $product['opt1_name'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ) && $display_cnt < 3) || ($display_cnt > 2)  ){
			if( ( strlen( $product['opt1_name'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ) && $display_cnt < 3  ){
				$btn_opt_addClass	= " CLS_option_change";
			} else if( $display_cnt > 2 ) {
				$btn_opt_addClass	= " CLS_quantity_change";
?>
											<div class="btn_opt">2개이상 옵션변경은 상품상세에서 가능합니다.<button type="button" class="btn-change ml-10 CLS_option_replace"><span>확인</span></button></div>

<?
			}
?>
											<label for="">상품수량</label>
											<div class="ea-select">
												<div class="ea">
													<button type="button" class="minus btn-minus">차감</button>
													<button type="button" class="plus btn-plus">증가</button>
													<input type="text" name='bk_quantity' value="<?=$product['quantity']?>">
													<input type='hidden' name='pr_quantity' value='<?=$product['pr_quantity']?>'>
												</div>
											</div>

											<div class="btn_opt">
												<button class="btn-change<?=$btn_opt_addClass?>" type="submit"><span>확인</span></button>
												<button class="btn-change tr-close CLS_option_close" type="button"><span>취소</span></button>
											</div>
<?php
		}
?>

										</div>
<?
}
?>
									</td>
								</tr>
								<!-- // [D] 옵션변경시 노출 영역 -->
<?php
	} // brandObj foreach
?>
							</tbody>
							<tfoot>
								<tr class="bg">
									<td colspan="6">
<?php
	if( $vender_info[$vender] ){
		if( $vender_info[$vender]['deli_type'] == '1' && $vender_deli[$vender]['deli_price'] > 0 ){
?>
									[<?=$brand_name?>] 배송비 <?=number_format( $vender_deli[$vender]['deli_price'] )?>원

<?php
            if( $vender_info[$vender]['deli_price_min'] != 0 ){
?>
									&nbsp;(<?=$brand_name?> 제품으로만 <?=number_format( $vender_info[$vender]['deli_price_min'] )?>원 이상 구매 시 무료배송됩니다.)
<?php
            }
?>
<?php
			$all_deli_price += $vender_deli[$vender]['deli_price'];
		} else {
?>
									[<?=$brand_name?>] 배송비 무료
<?php
		}
		if( $product_deli[$vender] ){
?>
<?php
			$prDeliCnt	= 0;
			foreach( $product_deli[$vender] as $prDeliKey => $prDeliVal ){
?>
									<br><?=$prDeliVal['productname']?> 배송비 <?=number_format( $prDeliVal['deli_price'] )?>
<?php
				$all_deli_price += $prDeliVal['deli_price'];
			}
?>
<?php
		}
	} else {
?>
<?php
	}
?>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<!-- // 장바구니 리스트 -->
<?php
} // brandArr foreach

if( strlen( $_ShopInfo->getMemid() ) == 0 ){ // 로그인을 안했을 경우
	$all_reserve	= 0;
}
?>
			<!-- // 담은 상품 -->
			<!-- //벤더단위 종료 -->

					<div class="total-price-box clear">
						<div class="button">
							<button type="button" class="btn-line" onClick="javascript:select_delete();"><span>선택상품 삭제</span></button>
							<button type="button" class="btn-line" onClick="javascript:basket_clear();"><span>전체 삭제</span></button>
						</div>
						<!--<div class="last-price">
							합계 208,600 원 + 배송비 0 원 = <em class="type_txt2">208,600원</em>
						</div>-->
					</div>

					<div class="total_wrap">
						<div class="total_price clear">
							<span>총 결제금액</span>
							<ul class="clear">
								<li><div>상품 금액 합계 <em><?=number_format($bf_sumprice)?>원</em></div></li>
								<li class="hide"><div>할인 <em><?=number_format(0)?>원</em></div></li>
								<li><div>배송비 <em><?=number_format($all_deli_price)?>원</em></div></li>
								<li><div><p>결제금액</p> <em><?=number_format($bf_sumprice+$all_deli_price)?>원</em></div></li>
							</ul>
						</div>
					</div>

					<div class="btn_confirmation mt-50">
						<a href="javascript:select_order('N');" class="btn-type1 selectProduct">선택상품 주문</a>
						<a href="javascript:order('N');" class="btn-type1 c1 allBuyProduct">전체상품 주문</a>
					<?if($_ShopInfo->getStaffYn() == 'Y'){?>
						<a href="javascript:select_order('Y');" class="btn-type1 selectProduct">선택상품 임직원주문</a>
						<a href="javascript:order('Y');" class="btn-type1 c1 allBuyProduct">전체상품 임직원주문</a>
					<?}?>
					</div>

				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->