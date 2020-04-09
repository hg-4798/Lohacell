

<!-- start container -->
<div id="container">
	<div class="containerBody sub-page">
		
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li class="on"><a>쇼핑백</a></li>
			</ul>
		</div>

		<div class="cart-wrap">

			<div class="cart-my-benefit">
				<div class="inner-flow">
                	<ul>
                    	<li class="on"><span>01</span> 쇼핑백</li>
                        <li><span>02</span> 주문결제</li>
                        <li><span>03</span> 주문완료</li>
                    </ul>
                </div>
<?php
if( strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
				<div class="inner-benefit ">
					<p><strong><?=$_ShopInfo->memname?></strong>님의 혜택정보</p>
					<p>마일리지 <strong><?=number_format($mem_reserve->reserve)?>M</strong> l 할인쿠폰 <strong><?=number_format($coupon_cnt)?>장</strong></p>
					<p class="hide">비회원 구매시 할인/쿠폰과 이벤트 등의<br>혜택을 받으실 수 없습니다.</p>
				</div>
<?php
}
?>
				<div class="inner-benefit hide"><!-- 비회원일 경우 출력 -->
					<p class="no-member">비회원 구매시 할인/쿠폰과 이벤트 <br>등의 혜택을 받으실 수 없습니다.</p>
				</div>
			</div>

			<p class="save-ment">상품은 최대 60일 보관되며 상품 소진 시 삭제됩니다.</p>

			<!-- 담은 상품 -->
			<!-- 벤더 단위 시작 -->
<?php
$all_reserve = 0;
$bf_sumprice = 0;
$all_deli_price = 0;
foreach( $venderArr as $vender=>$vederObj ){
	$vender_name = get_vender_name( $vender );
?>
			<h4 class="table-title first">업체 배송 상품 [<?=$vender_name?>]</h4>
			<table class="th-top util CLS_basketTotalCount" summary="담은 상품의 정보, 판매가, 수량, 할인금액, 결제 예정가, 적립금을 확인할 수 있습니다.">
				<caption>담은 상품<span>(ㅡ)</span></caption>
				<colgroup>
					<col style="width:40px"><col style="width:102px"><col style="width:auto"><col style="width:152px">
					<col style="width:335px"><col style="width:119px"><col style="width:126px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col"><input class="checkbox-def allCheck" type="checkbox" title="담은 상품 전체선택" ></th>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">수량</th>
						<th scope="col">옵션</th>
						<th scope="col">마일리지</th>
						<th scope="col">주문금액</th>
					</tr>
				</thead>
				<tbody class="basket-item">
				<!-- 상품단위 시작 -->
<?php
	$product_price = 0;
	foreach( $vederObj as $product ) {
		$all_reserve += $product['reserve'];
		$product_price = ( $product['price'] + $product['option_price'] ) * $product['option_quantity'];
		$bf_sumprice += $product_price;
?>
				
					<tr name='product_list' >
						<td>
							<input class="checkbox-def" type="checkbox" name='checkBasket' id='' value='<?=$product['basketidx']?>' >
						</td>
						<td class="info">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
								<!-- 상품 이미지 -->
								<img class="img-size-mypage" src='<?=getProductImage($productImgPath,$product['tinyimage'])?>' >
							</a>
						</td>
						<td class="ta-l">
							<span class="brand-nm"><?=$vender_name?></span>
							<span class="name">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
								<?=$product['productname']?>
							</a>
<?php 
	if( strlen( $product['opt1_name'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
?>
							<div class="opt-plus">[ 옵션 : 
<?php
		if( strlen( $product['opt1_name'] ) > 0 ){ // 옵션

			if( $product['option_type'] == 0 ){ //조합형 옵션
				$tmpOptName = explode( '@#', $product['opt1_name'] );
				$tmpOptVal = explode( chr(30), $product['opt2_name'] );
				foreach( $tmpOptName as $tmpKey=>$tmpVal ){
					if( $tmpVal ){
						echo '/&nbsp;'.$tmpVal.' : '.$tmpOptVal[$tmpKey].'&nbsp;';
					}
				}
			}

			if( $product['option_type'] == 1 ){ // 독립형 옵션
				$tmpOptName = explode( '@#', $product['opt1_name'] );
				$tmpOptVal = explode( '@#', $product['opt2_name'] );

				//exdebug($tmpOptName);
				//exdebug($tmpOptVal);

				foreach( $tmpOptName as $tmpKey=>$tmpVal ){
					if( $tmpVal ){
						$tmpOptVal1	=	explode( chr(30), $tmpOptVal[$tmpKey]);
						echo '/&nbsp;'.$tmpVal.' : '.$tmpOptVal1[1];
					}
				}
			}

		}

		if( strlen( $product['text_opt_subject'] ) > 0 ) { // 추가 문구 옵션
			$tmpOptSubject = explode( '@#', $product['text_opt_subject'] );
			$tmpOptContent = explode( '@#', $product['text_opt_content'] );
			foreach( $tmpOptSubject as $tmpKey=>$tmpVal ){
				if( $tmpVal ){
					echo '/&nbsp;'.$tmpVal.' : '.$tmpOptContent[$tmpKey];
				}
			}
		}

		if( strlen( $product['opt1_name'] ) > 0 && $product['option_price'] > 0 ){
			echo '/&nbsp;추가금액 : '.number_format( $product['option_price'] ).' 원';
		}

?>

							]</div>

<?php
	}
?>
							</span>
						</td>
						<!-- <td><strong><?=number_format($row_sellprice)?></strong></td> -->
						<td class="cart-ea-choice">
							<div class="ea-select small">
								<button type="button" class="btn-minus"><span>-</span></button>
								<button type="button" class="btn-plus"><span>+</span></button>
								<input type="text" name='bk_quantity' value="<?=$product['quantity']?>">
								<input type='hidden' name='pr_quantity' value='<?=$product['pr_quantity']?>'>
							</div>
							<div style='margin-top: 3px;'>
								<a href="javascript:;" class="CLS_quantity_change" ><img src="../static/img/btn/c_modify_btn.gif" type="image" ></a>
							</div>
						</td>
						<td name='NM_options' data-productcode='<?=$product['productcode']?>' >
						<!-- 옵션정보 시작 -->
<?php
		
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
					$get_option = get_option( $product['productcode'], $optCode, $optNameKey ); //옵션정보
					//exdebug( $get_option );
?>
							<ul class="opt-change">
								<li>
									<span><?=$optNameVal?></span>
									<div class="select small CLS_options" >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value CLS_option_value selected" data-option-code='<?=$tmpOptCode?>' ><span><?=$tmpOptVal[$optNameKey]?></span></button>
										<ul class="a_list CLS_option_select" >
											<li>
												<a href="javascript:;" data-qty='' data-code='' >
													선택
												</a>
											</li>
<?php
					if( count( $get_option ) > 0 ){
							foreach( $get_option as $optVal ){
								$option_qty = $optVal['qty']; // 수량
								$option_disable = ''; // disabled
								$option_text = ''; // 품절 text
								$option_hover = ''; // 선택값 li class
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
									$option_disable = 'li-disable';
									$option_text = '[품절]&nbsp;';
								}
								if( strlen( $optCode ) > 0 ) {
									if( $tmpOptCode == $optCode.chr(30).$optVal["code"] ) $option_hover = 'class="hover"';
								} else {
									if( $tmpOptCode == $optVal["code"] ) $option_hover = 'class="hover"';
								}

								if( $optNameKey > 0 ){
									$data_code = $optCode.chr(30).$optVal["code"];
								} else {
									$data_code = $optVal["code"];
								}
								
?>
											<li <?=$option_hover?>>
												<a href="javascript:;" <?=$option_disable?> 
													data-qty='<?=$option_qty?>' data-code='<?=$data_code?>' >
													<?=$option_text.$optVal["code"].$priceText?>
												</a>
											</li>
<?php
							} // get_option foreach
					} // option1 if
?>
										</ul>
									</div>
								</li>
							</ul>
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
						$tmpName = ' 선택 ';
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
							<ul class="opt-change" name='alone_option' >
								<li>
									<span><?=$optNameVal?></span>
									<div class="select small" >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" name='alone_option_val' class="my_value selected" data-option-code='<?=$tmpOptVal[$optNameKey]?>' ><?=$tmpName?><?=$tmpOptionPriceText?></button>
										<ul class="a_list " >
											<li>
												<a href="javascript:;" name='alone_change' data-qty='' data-code='' >
													선택
												</a>
											</li>
<?php
					if( $optNameVal != ''){
						foreach( $alone_option[$optNameVal] as $aloneKey=>$aloneVal ){
							if( $tmpOptVal[$optNameKey] == $aloneVal->option_code ) {
								$option_hover = 'class="hover"';
							} else {
								$option_hover = '';
							}
							$priceText = "";
							if( $aloneVal->option_price > 0 ){
								$priceText = ' ( + '.number_format( $aloneVal->option_price ).' 원 )';
							} else if( $aloneVal->option_price < 0 ) {
								$priceText = ' ( - '.number_format( $aloneVal->option_price ).' 원 )';
							}
?>
											<li <?=$option_hover?> >
												<a href="javascript:;" name='alone_change' data-qty='<?=$aloneVal->option_quantity?>' data-code='<?=$aloneVal->option_code?>' >
													<?=$aloneKey.$priceText?>
												</a>
											</li>
<?php
						} // alone_option foreach
					} // tmpOptName if
?>
										</ul>
<?php
					if( $aloneVal->option_tf == 'T' ) {
						echo ' * 필수';
						$tmp_alone_tf = 'T';
					} else {
						$tmp_alone_tf = 'F';
					}
?>
										<input type='hidden' name='alone_option_tf' value='<?=$tmp_alone_tf?>' >
									</div>
								</li>
							</ul>
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
							<ul class="opt-change" name='add_option'>
								<li>
									<span><?=$optSubVal?></span>
									<div class="select" >
										<input type='text' name='add_option_val' value='<?=$tmpOptContent[$optSubKey]?>' >
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
							</ul>
<?php
			} // tmpOpt2Name foreach
		} // opt2_name if
		if( ( strlen( $product['opt1_name'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ) && $display_cnt < 3  ){
?>
							<ul class="opt-change">
								<li class="btn-place">
									<a href="javascript:;" class="CLS_option_change" >
										<img src="../static/img/btn/c_modify_btn.gif" type="image" >
									</a>
								</li>
							</ul>
<?php
		} else if( $display_cnt > 2 ) {
?>
							<ul class="opt-change">
								<li class="btn-place">
									<a href="javascript:;" class="CLS_option_replace" >
										<img src="../static/img/btn/c_modify_btn.gif" type="image" >
									</a>
								</li>
							</ul>
<?php
		} else {
            echo "-";
        }
?>
						<!-- // 옵션정보 종료 -->
						</td>
						<td class="point"><?=number_format( $product['reserve'] )?>M</td>
						<td><strong><?=number_format( $product_price )?></strong></td>
					</tr>
				
<?php
	} // vederObj foreach
?>
				</tbody>
				<!-- // 상품단위 종료 -->
				<tfoot>
<?php
	if( $vender_info[$vender] ){
		if( $vender_info[$vender]['deli_type'] == '1' && $vender_deli[$vender]['deli_price'] > 0 ){
?>
					<tr>
						<td colspan="9">
							<strong>
								배송비 
<?php 
            /*
			if( $vender_info[$vender]['deli_select'] == '0' ){
				echo '선불';
			} else if( $vender_info[$vender]['deli_select'] == '1' ) {
				echo '착불';
			} else if( $vender_info[$vender]['deli_type'] == '2' ) {
				echo '선불 / 착불 선택';
			} 
            */
?>								
								<?=number_format( $vender_deli[$vender]['deli_price'] )?>
							</strong>
<?php
            if( $vender_info[$vender]['deli_price_min'] != 0 ){
?>
							[<?=$vender_name?>] 제품으로만 <?=number_format( $vender_info[$vender]['deli_price_min'] )?> 이상 구매 시 무료배송됩니다.
<?php
            }
?>
						</td>
					</tr>
<?php
			$all_deli_price += $vender_deli[$vender]['deli_price'];
		} else {
?>
					<tr>
						<td colspan="9"><strong>[<?=$vender_name?>] 배송비 무료</strong></td>
					</tr>
<?php
		}
		if( $product_deli[$vender] ){
?>
					<tr>
						<td colspan="9">
<?php
			foreach( $product_deli[$vender] as $prDeliKey => $prDeliVal ){
?>
						<strong>
							개별 배송비 
<?php
                /*
				if( $vender_info[$vender]['deli_select'] == '0' ){
					echo '선불';
				} else if( $vender_info[$vender]['deli_select'] == '1' ) {
					echo '착불';
				} else if( $vender_info[$vender]['deli_type'] == '2' ) {
					echo '선불 / 착불 선택';
				}
                */
?>
							<?=number_format( $prDeliVal['deli_price'] )?>
						</strong> 
						<?=$prDeliVal['productname']?>
<?php
				$all_deli_price += $prDeliVal['deli_price'];
			}
?>						
						</td>
					</tr>
<?php
		}
	} else {
?>
					<tr>
						<td colspan="9"></td>
					</tr>
<?php
	}
?>

				</tfoot>
			</table>
<?php
} // venderArr foreach

if( strlen( $_ShopInfo->getMemid() ) == 0 ){ // 로그인을 안했을 경우
	$all_reserve	= 0;
}
?>
			<!-- // 담은 상품 -->
			<!-- //벤더단위 종료 -->

			<div class="btn-place function">
				<a class="btn-dib-line" href="javascript:select_delete();" target="_self">선택상품 삭제</a>
				<a class="btn-dib-line" href="javascript:basket_clear();" target="_self">전체상품 삭제</a>
				<a class="btn-dib-line CLS_wish" href="javascript:set_wish();" target="_self">위시리스트 담기</a>
			</div>


			<h4 class="table-title">결제 정보</h4>
			<div class="total-price-sum">
				<ul>
					<li><span>총 주문금액</span><?=number_format($bf_sumprice)?></li>
					<li class="minus"><span>총 할인금액</span><?=number_format(0)?></li>
					<li class="plus"><span>배송비</span><?=number_format($all_deli_price)?></li>
					<li class="total"><span>총 결제금액</span><strong><?=number_format($bf_sumprice+$all_deli_price)?></strong></li>
					<li><span>적립 마일리지</span><?=number_format( $all_reserve )?>M</li>
				</ul>
			</div>

			<div class="btn-place">
				<a href="<?=$Dir.FrontDir?>login.php?buy=1&chUrl=<?=urlencode($Dir.FrontDir."order.php")?>"></a>
				<a href="javascript:select_order('N');" class="selectProduct btn-dib-function line"><span>선택상품 주문</span></a>
				<a href="javascript:javascript:order('N');" class="allBuyProduct btn-dib-function line"><span>전체상품 주문</span></a>
				<?if($_ShopInfo->getStaffYn() == 'Y'){?><a href="javascript:select_order('Y');" class="selectProduct btn-dib-function line w150"><span>선택상품 임직원주문</span></a><?}?>
				<?if($_ShopInfo->getStaffYn() == 'Y'){?><a href="javascript:javascript:order('Y');" class="allBuyProduct btn-dib-function line w150"><span>전체상품 임직원주문</span></a><?}?>
				<a href="../" target="_self" class="btn-dib-function"><span>쇼핑 계속하기</span></a>
			</div>
			
			
			
			<div class="button_area hide">
				<div class="button_right" style="margin-bottom:95px">
					<a href="javascript:;"  class="estimate_sheet  btn_B wide">견적서 출력</a>
					<!-- 추가--> 

					<a href="../" target="_self" class="btn_B wide">쇼핑 계속하기</a>
					<a href="<?=$Dir.FrontDir?>login.php?buy=1&chUrl=<?=urlencode($Dir.FrontDir."order.php")?>"></a>
					<a href="javascript:;" class="selectProduct btn_B wide">선택상품 주문</a>
					<a href="javascript:;" class="allBuyProduct btn_A wide">전체상품 주문</a>
				</div>
			</div>
		</div>

	</div>
	<!-- //메인 컨텐츠 -->
		
</div>

