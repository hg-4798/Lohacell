
<!-- 메인 컨텐츠 -->

<div class="">

	<!--사은품 팝업 ㅠㅠ-->
	<?
	$gift ="";
	$gift_sql = " 
		select a.banner_t_link,c.tinyimage,c.productcode,c.pridx,c.productname,c.sellprice,c.consumerprice,d.brandname,b.no  
		from tblmainbannerimg a 
		join tblmainbannerimg_product b on a.no = b.tblmainbannerimg_no
		join tblproduct c on b.productcode = c.productcode
		left join tblproductbrand d on c.brand = d.bridx
		where a.banner_no = 900;
	"; //banner_no 900으로 세팅됨 
	$gift_result = pmysql_query($gift_sql);
	while($row = pmysql_fetch_object($gift_result) ){
		$gift[] = $row;
	}

	$chk_gift_price = $gift[0]->banner_t_link;

	?>
	<div class="layer-dimm-wrap gift-use-layer">
		<div class="dimm-bg"></div>
		<div class="layer-inner order-coupon-list" id="gift_area"> <!-- layer-class 부분은 width,height, - margin 값으로 구성되며 클래스명은 자유 -->
			<h3 class="layer-title"></h3>
			<button type="button" class="btn-close" id="gift_close">창 닫기 버튼</button>
			<div class="layer-content js-scroll">
				<p class="title">사은품 선택하기</p>
				<div class="gift_pop_list">
					<ul>
					<?foreach($gift as $gkey=>$gval){?>
						<li>
							<a href="#">
								<div class="img">
									<img src='<?=getProductImage( $productImgPath, $gval->tinyimage )?>' >
								</div>
								<div class="info">
									<p class="brand"><?=$gval->brandname?></p>
									<p class="comment"><?=$gval->productname?></p>
									<p class="price"><del><?=number_format($gval->consumerprice)?></del><strong><?=number_format($gval->sellprice)?></strong></p>
								</div>
							</a>
							<div class="select_area">
								<input type="radio" name="selectGift" <?if( $gkey==0 ){?>checked<?}?> value="<?=$gval->pridx?>" data-no="<?=$gval->no?>">
							</div>
						</li>
					<?}?>
						<!--
						<li>
							<a href="#">
								<div class="img"><img src="../data/shopimages/product/001002002002000194/001002002002000194_20161013105808_thum2_500X500.jpg" alt=""></div>
								<div class="info">
									<p class="brand">BOOSTICSUPPLY</p>
									<p class="comment">[3/30 예약발송][17SS 10% SALE]</p>
									<p class="price"><strong>318,000</strong></p>
								</div>
							</a>
							<div class="select_area">
								<input type="radio" name="selectGift">
							</div>
						</li>
						<li>
							<a href="#">
								<div class="img"><img src="../data/shopimages/product/001002002002000194/001002002002000194_20161013105808_thum2_500X500.jpg" alt=""></div>
								<div class="info">
									<p class="brand">BOOSTICSUPPLY</p>
									<p class="comment">[17SS PRE-ORDER]</p>
									<p class="price"><del>350,000</del><strong>318,000</strong></p>
								</div>
							</a>
							<div class="select_area">
								<input type="radio" name="selectGift">
							</div>
						</li>
						<li>
							<a href="#">
								<div class="img"><img src="../data/shopimages/product/001002002002000194/001002002002000194_20161013105808_thum2_500X500.jpg" alt=""></div>
								<div class="info">
									<p class="brand">BOOSTICSUPPLY</p>
									<p class="comment">[김새론, 레인보우 지숙, 아이린, 김새론, 레인보우 지숙, 아이린]</p>
									<p class="price"><strong>318,000</strong></p>
								</div>
							</a>
							<div class="select_area">
								<input type="radio" name="selectGift">
							</div>
						</li>
						-->
					</ul>
				</div>
				<!-- <div class="goods-coupon">
					<div class="inner">
						<p class="pic">
				
							<img src='' >
						</p>
						<p class="info">
							
							<span>상품명</span>
						</p>
					</div>
					
					<ul class="coupon-choice2">
						<li>
							<input type="radio" class="radio-def" >
						</li>
					</ul>
				                    <p class="coupon-price" id="coupon_price_<?=$productIndex?>" name='NM_coupon_price' >
							dd
					</p>
				</div> -->
				<div class="btn-place mb-20">
					<button class="btn-dib-function" id="sel_gift"><span>사은품선택 완료</span>
					</button>
				</div>
			</div><!-- //.layer-content -->
		</div>
	</div>

<script>
$(document).on("click","#sel_gift",function(){
	//익스 9에서 preventDefault가 안돌아가는 문제로 인해 조건 추가
	if(window.event.preventDefault){
		window.event.preventDefault();
	} else {
		window.event.returnValue = false;
	}
	var gift_code = $(":input:radio[name=selectGift]:checked");
	$("#gift").val( gift_code.val() );
	$("#gift_no").val( gift_code.data('no') );
	$("#gift_close").trigger("click");
});
</script>
<!--//사은품 팝업 ㅠㅠ-->

	<!-- 리뷰작성팝업 -->
	<div class="layer-dimm-wrap coupon-use-layer">
		<div class="dimm-bg"></div>
		<div class="layer-inner order-coupon-list"> <!-- layer-class 부분은 width,height, - margin 값으로 구성되며 클래스명은 자유 -->
			<h3 class="layer-title"></h3>
			<button type="button" class="btn-close">창 닫기 버튼</button>
			<div class="layer-content js-scroll">
				<p class="title">상품 쿠폰 선택</p>
<?php
$couponIndex = 0; // radiobox 고유번호
$productIndex = 0; // 상품별 radiobox 고유번호
foreach( $venderArr as $vender=>$vederObj ){
	foreach( $vederObj as $product ) {
		$tmp_opt_price = 0;
?>
				<div class="goods-coupon">
					<div class="inner">
						<p class="pic">

							<img src='<?=getProductImage( $productImgPath, $product['tinyimage'] )?>' >
						</p>
						<p class="info">
							<span><?=get_vender_name( $vender )?></span>
							<span><?=$product['productname']?></span>
<?php
		if( count( $product['option'] ) > 0 ){
			if( $product['option_type'] == 1 ){ // 독립형 옵션
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				foreach( $product['option'] as $optKey=>$optVal ){
					$tmp_opt_content = explode( chr(30), $optVal['option_code'] );
					echo $tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1].'<br>';
					$tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
				} // option foreach
			} else { // 조합형 옵션
				$tmp_option = $product['option'][0];
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
				foreach( $tmp_opt_subject as $optKey=>$optVal ){
					echo $optVal.' : '.$tmp_opt_contetnt[$optKey].'<br>';
				}
				$tmp_opt_price += $tmp_option['option_price'] * $product['option_quantity'];

			} // option_type else
		} // count option if

		if( $product['text_opt_content'] ){ // 추가문구 옵션
			$tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
			$text_opt_content = explode( '@#', $product['text_opt_content'] );
			foreach( $text_opt_content as $textKey=>$textVal ){
				if( $textVal != '' ) {
					echo $tmp_text_subejct[$textKey].' : '.$textVal.'<br>';
				}
			}
		}
		$prPrice = ( $product['price'] * $product['option_quantity'] ) + $tmp_opt_price;
?>
						</p>

					</div>
<!--
						<li>
							<input type="radio" class="radio-def" name="coupon_select[<?=$productIndex?>]" id='no_<?=$couponIndex?>' value='<?=$prcouponVal->ci_no?>' data-bridx='<?=$product['basketidx']?>' idx='<?=$productIndex?>' data-sellprice='<?=$prPrice?>' >
							<label for="no_<?=$couponIndex?>"><?=$prcouponVal->coupon_name?></label>
						</li>

-->
					<!-- type="checkbox" class="checkbox-def" -->
					<ul class="coupon-choice2" data-productcode='<?=$product['productcode']?>' >
<?php

		foreach( $product_coupon as $prcouponKey=>$prcouponVal ){

			if( $_CouponInfo->check_coupon_product( $product['productcode'], 2, $prcouponVal ) && $product['couponyn']!="N"){
                // 사용조건 체크
                if( $prcouponVal->mini_quantity == 0 || ( $prcouponVal->mini_type == 'P' && $prcouponVal->mini_quantity <= $total_row->total_price_sum )
                    || ( $prcouponVal->mini_type == 'Q' && $prcouponVal->mini_quantity <= $total_row->total_qty )
                ){
?>

						<li>
							<input type="radio" class="radio-def" id='no_<?=$couponIndex?>' name='coupon_select[<?=$productIndex?>]'
								value='<?=$prcouponVal->ci_no?>' data-sellprice='<?=$prPrice?>' data-bridx='<?=$product['basketidx']?>'
								idx='<?=$productIndex?>' >
							<label for="no_<?=$couponIndex?>" ><?=$prcouponVal->coupon_name?></label>
						</li>
<?php
				$couponIndex++;
                }
			} // coupon_chk if
		} // product_coupon foreach
?>
						<li>
							<input type="radio" class="radio-def" name="coupon_select[<?=$productIndex?>]" id='coupon-a<?=$productIndex?>'
							value='' data-prdouct='' checked idx='<?=$productIndex?>' data-bridx='' data-sellprice='0' >
							<label for="coupon-a<?=$productIndex?>">선택안함</label>
						</li>
					</ul>
                    <p class="coupon-price" id="coupon_price_<?=$productIndex?>" name='NM_coupon_price' >
							할인금액 0
					</p>
				</div>
<?php
	$productIndex++;
	} // $vednerObj foreach
}  // venderArr foreach
?>
				<div class="btn-place mb-20"><button class="btn-dib-function" onclick='javascript:set_prcoupon();' ><span>OK</span></button></div>
			</div><!-- //.layer-content -->
		</div>
	</div><!-- //리뷰작성팝업 -->

	<div class="containerBody sub-page">

		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li class="on"><a><? if($staff_order == 'Y') { echo '임직원 '; } ?>주문결제</a></li>
			</ul>
		</div>

		<div class="cart-wrap">
			<div class="cart-my-benefit">
				<div class="inner-flow">
                	<ul>
                    	<li><span>01</span> 쇼핑백</li>
                        <li class="on"><span>02</span> <? if($staff_order == 'Y') { echo '임직원 '; } ?>주문결제</li>
                        <li><span>03</span> 주문완료</li>
                    </ul>
                </div>
				<div class="inner-benefit ">
					<p><strong><?=$_ShopInfo->memname?></strong>님의 혜택정보</p>
					<p>마일리지 <strong><?=number_format($mem_reserve->reserve)?>M</strong> l 할인쿠폰 <strong><?=number_format($coupon_cnt)?>장</strong></p>
					<p class="hide">비회원 구매시 할인/쿠폰과 이벤트 등의<br>혜택을 받으실 수 없습니다.</p>
				</div>
				<div class="inner-benefit hide"><!-- 비회원일 경우 출력 -->
					<p class="no-member">비회원 구매시 할인/쿠폰과 이벤트 <br>등의 혜택을 받으실 수 없습니다.</p>
				</div>
			</div>

			<!-- 주문 상품 -->
			<h4 class="table-title">주문상품 정보</h4>
				<!-- 벤더별 -->
<?php
$sumprice = 0;
$deli_price = 0; // 선불 배송료
$deli_price2 = 0; //착불 배송료
$reserveprice=0;
$couponprice=0;
$no_total_price =0; //구매 상한가 미 포함 시킬 가격

foreach( $venderArr as $vender=>$vederObj ){
	$vender_price = 0;
	$product_reserve = 0;
	$product_price = 0;
	$vender_name = get_vender_name( $vender );
?>
			<table class="th-top util order-tb-margin" summary="담은 상품의 정보, 판매가, 수량, 할인금액, 결제 예정가, 적립금을 확인할 수 있습니다.">
				<caption>01. 주문 상품</caption>
				<colgroup>
					<col style="width:100px" >
					<col style="width:auto" >
					<col style="width:130px" >
					<col style="width:130px" >
					<!-- <col style="width:110px" > -->
					<col style="width:110px" >
					<col style="width:105px" >
				</colgroup>

				<thead>
					<tr>
						<th scope="col" colspan="2"><strong>업체 배송 상품 [<?=$vender_name?>]</strong></th>
						<th scope="col">수량</th>
						<th scope="col">옵션</th>
						<!-- <th scope="col">추가문구 옵션</th> -->
						<th scope="col">적립 마일리지</th>
						<th scope="col">상품금액</th>
					</tr>
				</thead>
<!-- 상품단위 시작 -->
<?php
	foreach( $vederObj as $product ) {

		$opt_price = 0; // 상품별 옶션가
		$pr_reserve = 0; //상품별 마일리지
        $tmp_opt_price = 0;
?>
				<tbody>
					<tr>
						<td class="order-item-tr">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
								<img class="img-size-mypage" src="<?=getProductImage( $productImgPath, $product['tinyimage'] )?>" >
							</a>
						</td>
						<td class="ta-l">
							<span class="name">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
								<?=$product['productname']?>
							</a>
							<br>
<?php
		if( count( $product['option'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
			echo "<div>";
			if( count( $product['option'] ) > 0 ){
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				if( $product['option_type'] == 0 ){ // 조합형 옵션
					$tmp_option = $product['option'][0];
					$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
					foreach( $tmp_opt_subject as $optKey=>$optVal ){
						echo $optVal.' : '.$tmp_opt_contetnt[$optKey].'<br>';
                        $tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
				}
				if( $product['option_type'] == 1 ){ // 독립형 옵션
					foreach( $product['option'] as $optKey=>$optVal ){
						$tmp_opt_content = explode( chr(30), $optVal['option_code'] );
						echo $tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1].'<br>';
						$tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
				}
			} // count option

			if( $product['text_opt_content'] ){ // 추가문구 옵션
				$tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
				$text_opt_content = explode( '@#', $product['text_opt_content'] );
				foreach( $text_opt_content as $textKey=>$textVal ){
					if( $textVal != '' ) {
						echo $tmp_text_subejct[$textKey].' : '.$textVal.'<br>';
					}
				}
			}  // text_opt_content if
            if( $tmp_opt_price > 0 ){
    			echo '추가금액 : '.number_format( $tmp_opt_price );
            }
			echo "</div><br>";
		}  // count option || text_opt_subject if
?>
								<?=$optText?>
							</span>
							<?if($product['memberorder']=="M"){?>
							<br><font style="color:red">[회원 전용 상품입니다]</font>
							<?}?>
						</td>
						<td><?=$product['quantity']?></td>
						<td>
<?php
            if( count( $product['option'] ) > 0 || strlen( $product['text_opt_content'] ) > 0 ){
                if( count( $product['option'] ) > 0 ){
                    if( $product['option_type'] == 1 ){ // 독립형 옵션
                        $tmp_opt_subject = explode( '@#', $product['option_subject'] );
                        foreach( $product['option'] as $optKey=>$optVal ){
                            $tmp_opt_content = explode( chr(30), $optVal['option_code'] );
                            echo $tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1].'<br>';
                            $opt_price += $optVal['option_price'] * $product['option_quantity'];
                        } // option foreach
                    } else { // 조합형 옵션
                        $tmp_option = $product['option'][0];
                        $tmp_opt_subject = explode( '@#', $product['option_subject'] );
                        $tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
                        foreach( $tmp_opt_subject as $optKey=>$optVal ){
                            echo $optVal.' : '.$tmp_opt_contetnt[$optKey].'<br>';
                        }
                        $opt_price += $tmp_option['option_price'] * $product['option_quantity'];

                    } // option_type else
                } // count option if
?>
<?php
                if( $product['text_opt_content'] ){ // 추가문구 옵션
                    $tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
                    $text_opt_content = explode( '@#', $product['text_opt_content'] );
                    foreach( $text_opt_content as $textKey=>$textVal ){
                        if( $textVal != '' ) {
                            echo $tmp_text_subejct[$textKey].' : '.$textVal.'<br>';
                        }
                    }
                }
            } else {
                echo "-";
            }
			$pr_reserve = getReserveConversion( $product['reserve'], $product['reservetype'], ( $product['price'] * $product['quantity'] ) + $opt_price , "N" );
			$product_reserve += $pr_reserve; // 벤더별 상품 예상 적립금
			$product_price = ( $product['price']  * $product['quantity'] ) + $opt_price; //옵션가와 상품가를 합산해준다
			$vender_price += $product_price; // 벤더별 상품가격
			if($product['reserveyn']!="N")$reserveprice+=$product_price; // 적립금이 사용가능한 상품의 총합계를 구한다.
			if($product['couponyn']!="N")$couponprice+=$product_price; // 적립금이 사용가능한 상품의 총합계를 구한다.

			if($product['couponyn']=="N"){//구매상한가에 미 모함할 가격을 합산한다.
				$no_total_price += $product_price;
			}

			if( strlen( $_ShopInfo->getMemid() ) == 0 || $product['reservein_no']=="Y"){ // 로그인을 안했을 경우
				$pr_reserve	= 0;
			}
?>
						</td>
						<!-- <td>

						</td> -->
						<!-- <td><?=number_format($bVal['sellprice'])?></td> -->
						<td class="point"><? if( $staff_order == 'N' ) { echo number_format( $pr_reserve ); } else { echo '0'; } ?> M</td>
						<td><strong><?=number_format($product_price)?></strong></td>
					</tr>
				</tbody>
<!-- //상품단위 종료 -->
<?php
		# 장바구니 쿠폰 제외
		foreach( $basket_coupon as $basketKey=>$basketVal ){
			if( !$_CouponInfo->check_coupon_product( $product['productcode'], 2, $basketVal ) ){
				unset( $basket_coupon[$basketKey] );
			}
		}

		# 기획전 쿠폰 제외
        foreach( $promo_coupon as $promoKey=>$promoVal ){
            if( !$_CouponInfo->check_coupon_promo( $product['productcode'], $promoVal ) ){
                unset( $promo_coupon[$promoKey] );
            }
        }
	} //foreach
?>
				<tfoot>
<?php
	$vender_deli_price = 0;
	if( $vender_info[$vender] ){
?>
					<tr>
						<td colspan="7" bgcolor="#fafafa">
							합계 <?=number_format( $vender_price )?>
							<span>+</span>

							배송비
<?php
			if( $vender_info[$vender]['deli_select'] == '0' ){
				//echo '선불';
			} else if( $vender_info[$vender]['deli_select'] == '1' ) {
				//echo '착불';
			} else if( $vender_info[$vender]['deli_select'] == '2' ) {
?>
                            <select name='deli_select[<?=$vender?>]' data-vender='<?=$vender?>' >
                                <option value='0' >선불</option>
                                <option value='1' >착불</option>
                            </select>
<?php
			}

			if( $product_deli[$vender] ){
				foreach( $product_deli[$vender] as $prDeliKey => $prDeliVal ){
					$vender_deli_price += $prDeliVal['deli_price'];
				}
			}
			$vender_deli_price += $vender_deli[$vender]['deli_price'];
?>
							<?=number_format( $vender_deli_price )?>
                            <input type='hidden' name='select_price[<?=$vender?>]' value='<?=$vender_deli_price?>' data-vender='<?=$vender?>' >
							<span>=</span>
							주문 금액 <strong><?=number_format( $vender_price + $vender_deli_price )?></strong>
<?php
    if( $vender_info[$vender]['deli_price_min'] != 0 ){
?>
							<span class="delivery-ment">[<?=$vender_name?>] 제품으로만 <?=number_format( $vender_info[$vender]['deli_price_min'] )?>원 이상 구매시 무료배송됩니다.</span>
<?php
    }
?>
						</td>
					</tr>
<?php
	}
?>
				</tfoot>

			</table>

			<!-- //벤더별 -->
<?php
	if( $vender_info[$vender]['deli_select'] == '0' || $vender_info[$vender]['deli_select'] == '2' ) $deli_price += $vender_deli_price;
    if( $vender_info[$vender]['deli_select'] == '1' ) $deli_price2 += $vender_deli_price;
	$sumprice += $vender_price;

} // foreach
?>
			<!-- // 주문 상품 -->
			<div class="order-float-cover">
				<!-- 고객정보 -->
				<div class="orderer_area">
					<h4 class="table-title">주문고객 정보</h4>
					<table class="th-left-util" summary="주문자명, 주소, 휴대폰 번호, 이메일을 작성할 수 있습니다.">
						<caption>02. 고객정보</caption>
						<colgroup>
							<col style="width:106px" >
							<col style="width:auto" >
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">주문자</th>
								<td class="name">
<?php
if(strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
										<!-- 요청에 의해 readonly를 뺌 2015 12 09 유동혁 -->
										<input class="input-def" type='text'  name="sender_name" value="<?=$userName?>" style="font-weight:bold" style='border:0' required msgR="주문하시는분의 이름을 적어주세요">
<?php
} else {
?>
										<input type='text'  name="sender_name" value="" style="font-weight:bold" style='border:0' required msgR="주문하시는분의 이름을 적어주세요">
<?php
} // else
?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-email">이메일</label></th>
								<td>
									<div class="email-cover">
										<input type="text" id="user-email" name='sender_email' class="input-def w240" title="이메일 입력자리" value='<?=$email?>' onkeyup="domail_list_up(this.value)" >
										<ul class="domain-list">
											<li><a href="javascript:;" onClick="email_in(this)" alt='@naver.com'>@naver.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@gmail.com'>@gmail.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@nate.com'>@nate.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@daum.net'>@daum.net</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@yahoo.com'>@yahoo.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@hotmail.com'>@hotmail.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@hanmail.com'>@hanmail.com</a></li>
										</ul>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-phone">휴대전화</label></th>
								<td>
									<div class="select w80 small" style="z-index:250">
										<input type='hidden' name="sender_tel1" id='sender_tel1' value="<?=$mobile[0]?$mobile[0]:'010'?>" >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='senderPhon' ><?=$mobile[0]?$mobile[0]:'010'?></button>
										<ul class="a_list">
											<li><a href="#1"><span>010</span></a></li>
											<li><a href="#2"><span>011</span></a></li>
											<li><a href="#3"><span>016</span></a></li>
											<li><a href="#4"><span>017</span></a></li>
											<li><a href="#5"><span>018</span></a></li>
											<li><a href="#6"><span>019</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-phone" name="sender_tel2" value="<?=$mobile[1] ?>" maxlength='4' class="input-def w80" title="휴대전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" name="sender_tel3" value="<?=$mobile[2] ?>" maxlength='4' class="input-def w80" title="휴대전화 마지막 입력자리">
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-tel">전화번호(선택)</label></th>
								<td>
									<div class="select w80 small">
										<input type='hidden' name="home_tel1" id='home_tel1' value="" >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='senderTel'>02</button>
										<ul class="a_list">
											<li><a href="#1"><span>02</span></a></li>
											<li><a href="#2"><span>031</span></a></li>
											<li><a href="#3"><span>032</span></a></li>
											<li><a href="#4"><span>033</span></a></li>
											<li><a href="#5"><span>041</span></a></li>
											<li><a href="#6"><span>042</span></a></li>
											<li><a href="#7"><span>043</span></a></li>
											<li><a href="#8"><span>044</span></a></li>
											<li><a href="#9"><span>051</span></a></li>
											<li><a href="#10"><span>052</span></a></li>
											<li><a href="#11"><span>053</span></a></li>
											<li><a href="#12"><span>054</span></a></li>
											<li><a href="#13"><span>055</span></a></li>
											<li><a href="#14"><span>061</span></a></li>
											<li><a href="#15"><span>062</span></a></li>
											<li><a href="#16"><span>063</span></a></li>
											<li><a href="#17"><span>064</span></a></li>
											<li><a href="#18"><span>010</span></a></li>
											<li><a href="#19"><span>011</span></a></li>
											<li><a href="#20"><span>016</span></a></li>
											<li><a href="#21"><span>017</span></a></li>
											<li><a href="#22"><span>018</span></a></li>
											<li><a href="#23"><span>019</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-tel" name="home_tel2" maxlength='4' class="input-def w80" title="집전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" name="home_tel3" id='home_tel3' maxlength='4' class="input-def w80" title="집전화 마지막 입력자리">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- // 고객정보 -->

				<!-- 배송지정보 -->
				<ul class="order-delivery-tabs" name="delivery-category">
					<li><a class="idx-menu on">최근배송지</a></li>
					<li><a class="idx-menu ">배송주소록</a></li>
					<li><a class="idx-menu ">새로운주소</a></li>
				</ul>


				<div class="address_area">
					<h4 class="table-title mt-30">배송지 정보 <span class="display_same"><span class="same_box"><input type='checkbox' name="same" value="Y" onclick="SameCheck(this.checked)" id="dev_orderer" class="checkbox-def"><label for="dev_orderer">주문고객과 동일한 정보 사용</label></span></span></h4>

					<!-- 최근배송지 탭 -->
					<div class="idx-content on">
					<table class="th-left-util"  id="addr_input" summary="수령자명, 주소, 전화번호, 휴대폰번호, 이메일, 배송 메시지를 작성할 수 있습니다.">
						<caption>03. 배송지 정보 </caption>
						<colgroup>
							<col style="width:106px" >
							<col style="width:auto" >
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">받는 사람</th>
								<td class="name">
									<input class="input-def" type="text" name = 'receiver_name'  id = 'receiver_name'  required msgR="주문하시는 분 이름을 입력하세요." >
									<!-- <a href="javascript:addrchoice();" target="_self"><img src="../img/button/cart_order_address_list_btn.gif" alt="배송지 목록" ></a> -->
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-phone">휴대전화</label></th>
								<td>
									<div class="select w80 small">
										<input  type="hidden" name="receiver_tel21" id='receiver_tel21'>
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='receiverPhon' name="receiverPhon" >010</button>
										<ul class="a_list">
											<li><a href="#1"><span>010</span></a></li>
											<li><a href="#2"><span>011</span></a></li>
											<li><a href="#3"><span>016</span></a></li>
											<li><a href="#4"><span>017</span></a></li>
											<li><a href="#5"><span>018</span></a></li>
											<li><a href="#6"><span>019</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-phone" name="receiver_tel22" class="input-def w80" maxlength='4' onKeyUp="strnumkeyup(this)" required title="휴대전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" name="receiver_tel23" class="input-def w80" maxlength='4' onKeyUp="strnumkeyup(this)" required title="휴대전화 마지막 입력자리">
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-tel">전화번호(선택)</label></th>
								<td>
									<div class="select w80 small">
										<input type="hidden" name="receiver_tel11" id='receiver_tel11'  >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='receiverTel' name="receiverTel">02</button>
										<ul class="a_list">
											<li><a href="#1"><span>02</span></a></li>
											<li><a href="#2"><span>031</span></a></li>
											<li><a href="#3"><span>032</span></a></li>
											<li><a href="#4"><span>033</span></a></li>
											<li><a href="#5"><span>041</span></a></li>
											<li><a href="#6"><span>042</span></a></li>
											<li><a href="#7"><span>043</span></a></li>
											<li><a href="#8"><span>044</span></a></li>
											<li><a href="#9"><span>051</span></a></li>
											<li><a href="#10"><span>052</span></a></li>
											<li><a href="#11"><span>053</span></a></li>
											<li><a href="#12"><span>054</span></a></li>
											<li><a href="#13"><span>055</span></a></li>
											<li><a href="#14"><span>061</span></a></li>
											<li><a href="#15"><span>062</span></a></li>
											<li><a href="#16"><span>063</span></a></li>
											<li><a href="#17"><span>064</span></a></li>
											<li><a href="#18"><span>010</span></a></li>
											<li><a href="#19"><span>011</span></a></li>
											<li><a href="#20"><span>016</span></a></li>
											<li><a href="#21"><span>017</span></a></li>
											<li><a href="#22"><span>018</span></a></li>
											<li><a href="#23"><span>019</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-tel" class="input-def w80" name="receiver_tel12" maxlength='4' onKeyUp="strnumkeyup(this)" required title="집전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" class="input-def w80" name="receiver_tel13" maxlength='4' onKeyUp="strnumkeyup(this)" required title="집전화 마지막 입력자리">
								</td>
							</tr>
							<tr class="line-duble">
								<th scope="row" valign="top"><label for="post-code">주소</label></th>
								<td>
									<ul>
										<li>
											<input type='hidden' id='post5' name='post5' value='' >
											<input type="hidden" id="post-code" name = 'rpost1' class="input-def w80" title="우편번호 앞 입력자리">
											<!-- <span class="txt-lh">-</span> -->
											<input type="hidden" class="input-def w80" name = 'rpost2' id = 'rpost2' title="우편번호 뒤 입력자리">
											<input type="text" class="input-def w80" name = 'post' id = 'post' title="우편번호 통합">
											<a href="javascript:openDaumPostcode();" class="btn-dib-line ">우편번호</a>
										</li>
										<li>
											<input type="text" class="input-def w300" name = 'raddr1' id = 'raddr1' title="기본 주소 입력자리">
											<input type="text" class="input-def w250" name = 'raddr2' id = 'raddr2' title="상세 주소 입력자리">
										</li>
									</ul>
								</td>
							</tr>
							<tr class="line-duble">
								<th scope="row" valign="top"><label for="delivery-needs">배송 요청사항</label></th>
								<td>
									<ul>
										<li>
											<div class="select w300 small">
											<input type="hidden" name="msg_type" value="1">
												<span class="ctrl"><span class="arrow"></span></span>
												<button type="button" class="my_value" ><span>직접입력</span></button>
												<ul class="a_list" id='prmsg_chg' >
                                                    <li><a href="javascript:;">직접입력</a></li>
													<li><a href="javascript:;">부재시 경비실에 맡겨 주세요</a></li>
                                                    <li><a href="javascript:;">부재시 문앞에 놓아주세요</a></li>
                                                    <li><a href="javascript:;">배송전에 연락주세요</a></li>
                                                    <li><a href="javascript:;">빠른배송 부탁드려요</a></li>
                                                    <li><a href="javascript:;">소화전에 넣어주세요</a></li>
                                                    <li><a href="javascript:;">배관함에 넣어주세요</a></li>
												</ul>
											</div>
										</li>
										<li>
											<input type="text" class="input-def w300" name = 'order_prmsg' id="delivery-needs" title="배송요청 입력자리">
										</li>
									</ul>
								</td>
							</tr>

							<? if( false ){ ?>
							<tr>
								<th>배송비 결제</th>
								<td>
									<input type=radio name=deli_type class="deli_type" value="0" checked > 선불
									<input type=radio name=deli_type class="deli_type" id="deli_type1" value="1" > 착불
								</td>
							</tr>
							<? }else{ ?>
                                <!-- 배송비는 벤더별 기준이기에 0으로 고정 -->
								<input type="hidden" id='deli_type' name=deli_type value="0" >
							<? } ?>

						</tbody>
					</table>
					</div>

					<!-- 배송주소록 탭 -->
					<div class="idx-content on">
						<div class="addres-list-wrap" id='addr_list'>
						<!--
							<ul class="addres-list">
								<li>
									<div class="local">
										<p class="name">홍길동</p>
										<p>(49071) 부산 영도구 나눔길 1 (영선동2가)  1234</p>
										<div class="tel"><span>010-1234-1234</span><span>02-1234-1234</span></div>
									</div>
									<div class="btn">
										<button class="btn-function" type="button" name=""><span>선택</span></button>
										<button class="btn-function" type="button" name=""><span>삭제</span></button>
										<div>
											<input type="hidden" name="return_name" value="홍길동"><input type="hidden" name="return_zipcode" value="49071">
											<input type="hidden" name="return_addr1" value="부산 영도구 나눔길 1 (영선동2가)">
											<input type="hidden" name="return_addr2" value="1234"><input type="hidden" name="return_mobile" value="010-1234-1234">
											<input type="hidden" name="return_tel" value="02-1234-1234">
										</div>
									</div>
								</li>
								<li>
									<div class="local">
										<p class="name">홍길동</p>
										<p>(49071) 부산 영도구 나눔길 1 (영선동2가)  1234</p>
										<div class="tel"><span>010-1234-1234</span><span>02-1234-1234</span></div>
									</div>
									<div class="btn">
										<button class="btn-function" type="button" name=""><span>선택</span></button>
										<button class="btn-function" type="button" name=""><span>삭제</span></button>
										<div>
											<input type="hidden" name="return_name" value="홍길동"><input type="hidden" name="return_zipcode" value="49071">
											<input type="hidden" name="return_addr1" value="부산 영도구 나눔길 1 (영선동2가)">
											<input type="hidden" name="return_addr2" value="1234"><input type="hidden" name="return_mobile" value="010-1234-1234">
											<input type="hidden" name="return_tel" value="02-1234-1234">
										</div>
									</div>
								</li>
							</ul>
							<div class="list-paginate">
								<a class="on">1</a>
							</div>-->
						</div>
					</div>

					<!-- 새로운주소 탭 -->
					<!--
					<div class="idx-content">
						새로운주소
					</div>-->
					<!-- // 배송지정보 -->


				</div>
				<!-- 할인정보 -->
<?php
if ((strlen($_ShopInfo->getMemid())>0 && $_data->reserve_maxuse>=0 && $user_reserve!=0) || (strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y")) {
?>

				<div class="dc_area" style=''>
					<h4 class="table-title">할인정보</h4>
					<table class="th-left-util" summary="할인받을 쿠폰 및 적립금을 입력할 수 있습니다.">
						<caption>04. 할인정보</caption>
						<colgroup>
							<col style="width:106px" >
							<col style="width:auto" >
						</colgroup>
<?php
	if($okreserve<0){
		$okreserve=(int)($sumprice*abs($okreserve)/100);
		if($reserve_maxprice>$sumprice) {
			$okreserve=$user_reserve;
			$remainreserve=0;
		} else if($okreserve>$user_reserve) {
			$okreserve=$user_reserve;
			$remainreserve=0;
		} else {
			$remainreserve=$user_reserve-$okreserve;
		}
	}
?>

<?php
	if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && $staff_order == 'N' ) {
?>

						<tr>
							<th scope="row">상품쿠폰</th>
							<td><button class="btn-dib-line coupon-use" type="button"><span>쿠폰사용</span></button></td>
						</tr>
						<tr>
							<th scope="row">할인쿠폰</th>
							<td>
								<div class="select w300 small">
									<span class="ctrl"><span class="arrow"></span></span>
									<button type="button" class="my_value CLS_coupon_value"><span>쿠폰선택</span></button>

									<ul class="a_list">
<?php
		//쿠폰사용할수없는 상품을 체크하기위해 추가
		if($couponprice){

		foreach( $basket_coupon as $bcouponKey=>$bcouponVal ){
                // 사용조건 체크
                if( $bcouponVal->mini_quantity == 0 || ( $bcouponVal->mini_type == 'P' && $bcouponVal->mini_quantity <= $total_row->total_price_sum)
                    || ( $bcouponVal->mini_type == 'Q' && $bcouponVal->mini_quantity <= $total_row->total_qty )
                ){
?>
									<?if( ($total_row->total_price_sum - $no_total_price) > $bcouponVal->mini_price){?>
										<li><a href="javascript:set_bcoupon('<?=$bcouponVal->ci_no?>');"><?=$bcouponVal->coupon_name?></a></li>
									<?}?>
<?php
                }
		} // $basket_coupon foreach

        foreach( $promo_coupon as $pcouponKey=>$pcouponVal ){
                // 사용조건 체크
                if( $pcouponVal->mini_quantity == 0 || ( $pcouponVal->mini_type == 'P' && $pcouponVal->mini_quantity <= $total_row->total_price_sum)
                    || ( $pcouponVal->mini_type == 'Q' && $pcouponVal->mini_quantity <= $total_row->total_qty )
                ){
?>
                                    <?if( ($total_row->total_price_sum - $no_total_price) > $pcouponVal->mini_price){?>
                                        <li><a href="javascript:set_bcoupon('<?=$pcouponVal->ci_no?>');"><?=$pcouponVal->coupon_name?></a></li>
                                    <?}?>
<?php
                }
        } // $promo_coupon foreach
		}
?>
									</ul>

								</div>
								<div id = "ID_coupon_code_layer"></div>
							</td>
						</tr>
<?php
        if( count( $deliver_coupon ) > 0 ){
?>
                        <tr>
                            <th scope='row'>배송비 무료 쿠폰</th>
                            <td>
                                <input type='checkbox' name='dcoupon_ci_no' value='<?=$deliver_coupon[0]->ci_no?>' > <?=$deliver_coupon[0]->coupon_name?>
                                <input type='hidden' name='dcoupon_price' value='0' >
                            </td>
                        </tr>
<?php
        }
	}
?>

<?php
	if ( strlen( $_ShopInfo->getMemid() ) > 0 && $_data->reserve_maxuse >= 0 && $user_reserve != 0 ){

?>
						<tr>
							<th scope="row"><label for="mileage-use"><? if($staff_order == 'Y') { echo '임직원 '; } ?>마일리지</label></th>
							<td>
								<input type="hidden" name="okreserve" id='okreserve' value="<?=$user_reserve?>">
								<input type="hidden" name="reserveynsum" id='reserveynsum' value="<?=$reserveprice?>">

<?
		if(!$reserveprice){
?>
								<span>적립금 사용불가 상품입니다.</span>
								<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}else if( $_data->reserve_maxprice > $sumprice ) {
?>
								<span>구매금액이 <?=number_format( $_data->reserve_maxprice )?>원 이상이면 사용가능합니다.</span>
								<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}else if( $user_reserve >= $_data->reserve_maxuse ){
?>
								<input type="text" name="usereserve" id="mileage-use" class="input-def" title="마일리지 금액 자리" value='0'>
								<span>(보유 <? if($staff_order == 'Y') { echo '임직원 '; } ?> 마일리지 : <?=number_format( $user_reserve )?>M)</span>
<?php
		}else{
?>
								<span>
									<?=number_format($_data->reserve_maxuse)?>원 이상이면 사용가능합니다.
									(보유 마일리지 : <?=number_format( $user_reserve )?>M)
								</span>
								<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}
?>

								<!-- <input type="text" name="usereserve" id="mileage-use" class="input-def" title="마일리지 금액 자리">
								<span>(보유 마일리지 : 350M)</span> -->
							</td>
						</tr>
<?php
	} else {
?>
							<input type="hidden" name="usereserve" id="mileage-use" value='0'>
							<input type="hidden" name="okreserve" id='okreserve' value="0">
<?php
	}
?>
					</table>
				</div>
<?php
}
?>
				<!-- // 할인정보 -->

				<!-- 결제수단 -->
				<div class="means_area">
					<h4 class="table-title">결제하기</h4>
					<ul class="means CLS_paymentArea pay-type">

						<?if(strstr("YC", $_data->payment_type) && ord($_data->card_id)) {?>
							<li><input id="dev_payment2" class="dev_payment radio-def" name="dev_payment" type="radio" value="C" onclick="sel_paymethod(this);" > <label for="dev_payment2">신용카드</label></li>
						<?}?>

						<?if($escrow_info["onlycard"]!="Y" && !strstr($_SERVER["HTTP_USER_AGENT"],'Mobile') && !strstr($_SERVER[HTTP_USER_AGENT],"Android") && ord($_data->trans_id)){?>
							<li><input id="dev_payment3" class="dev_payment radio-def" name="dev_payment" type="radio" value="V" onclick="sel_paymethod(this);" > <label for="dev_payment3">계좌이체</label></li>
						<?}?>

						<?if($escrow_info["onlycard"]!="Y" && ord($_data->virtual_id)){?>
							<li><input id="dev_payment4" class="dev_payment radio-def" name="dev_payment" type="radio" value="O" onclick="sel_paymethod(this);" > <label for="dev_payment4">가상계좌</label></li>
						<?}?>

						<?if($escrow_info["onlycard"]!="Y" && strstr("YN", $_data->payment_type)) {?>
							<li><input id="dev_payment1" class="dev_payment radio-def" name="dev_payment" type="radio" value="B" onclick="sel_paymethod(this);" > <label for="dev_payment1">무통장입금</label></li>
						<?}?>

                        <?//if(ord($_data->mobile_id)){?>
                            <!--li><input id="dev_payment6" class="dev_payment" name="dev_payment" type="radio" value="M" onclick="sel_paymethod(this);" disabled > <label for="dev_payment6">휴대폰</label></li-->
                        <?//}?>

                        <li><input id="dev_payment6" class="dev_payment radio-def" name="dev_payment" type="radio" value="M" onclick="sel_paymethod(this);"> <label for="dev_payment6">휴대폰</label></li>
                        <li><input id="dev_payment7" class="dev_payment radio-def" name="dev_payment" type="radio" value="Y" onclick="sel_paymethod(this);"> <label for="dev_payment7">PAYCO</label></li>

						<?if(( $escrow_info["escrowcash"]=="A" || ($escrow_info["escrowcash"]=="Y" && (int)($sumprice+$deli_price)>=$escrow_info["escrow_limit"])) ){?>
						<?
							$pgid_info="";
							$pg_type="";
							$pgid_info=GetEscrowType($_data->escrow_id);
							$pg_type=trim($pgid_info["PG"]);
						?>
							<?if(strstr("ABCD",$pg_type)){?>
								<li><input id="dev_payment5" class="dev_payment radio-def" name="dev_payment" type="radio" value="Q" onclick="sel_paymethod(this);" > <label for="dev_payment5">에스크로 ( 가상계좌 )</label></li>
							<?}?>
						<?}?>
					</ul>

					<div class="pay-type-card" id="card_type" style="display:none">
						<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="임금계좌를 선택">
							<colgroup>
								<?if($etcmessage[2]=="Y") {?><col width="20%" ><?}?>
								<col >
							</colgroup>
							<?if($etcmessage[2]=="Y") {?>
							<tr>
								<th>입금자명</th>
								<td>
									<input type="text" name="bank_sender" value="" >
								</td>
							</tr>
							<?}?>
							<tr>
								<th>입금계좌</th>
								<td>
									<select name="pay_data_sel" id="pay_data_sel" onchange="sel_account(this)" style="width:350px;">
										<option value='' >입금 계좌번호 선택 (반드시 주문자 성함으로 입금)</option>
										<?foreach($bank_payinfo as $k => $v){?>
										<option value="<?=$v?>" ><?=$v?></option>
										<?}?>
									</select>
								</td>
							</tr>
						</table>
					</div>

					<div class="pay-type-card" id="payco_notice" style="display:none">
						<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="임금계좌를 선택">
							<tr>
								<td>
                                    <p>
                                        PAYCO는 NHN엔터테인먼트가 만든 안전한 간편결제 서비스입니다.<br>휴대폰과 카드 명의자가 동일해야 결제 가능하며, 결제금액 제한은 없습니다.
                                    </p>
								</td>
							</tr>
						</table>
					</div>

					<ul class="pay-type-attention">
						<li>신용카드/ 실시간 이체는 결제 후, 무통장입금은 입금확인 후 배송이 이루어집니다.</li>
						<li>은행 설정 시 주문번호마다 별도의 고객님만의 가상계좌가 생성됩니다. (주문완료 후 7일 이내 미입금 시 자동취소)</li>
						<li>일부 자동화 기기(CD/ATM)는 현금, 통장 입금이 제한될 수 있으며 입금오류 방지를 위해 정확한 금액을 입금 바랍니다.</li>
					</ul>

				</div>
				<!-- // 결제수단 -->

				<!-- 결제하기 -->
				<div class="payment-area">
					<div class="no-member-agree hide"><!-- 비회원일경우 출력됩니다. -->
						<input type="checkbox" id="no-member-check" class="checkbox-def">
						<label for="no-member-check">이용약관 및 개인정보 취급방침에 동의합니다.</label>
						<textarea name="" id="" cols="30" rows="10">이용약관 나옴다! 'ㅁ'!</textarea>
					</div>

					<div class="content">
						<?//$p_price=$sumprice+$deli_price+$sumpricevat; +$deli_price ?>
						<?$p_price=$sumprice+$sumpricevat;?>
						<?if( $sumprice >= $chk_gift_price){$chk_gift='T';}else{$chk_gift='F';}?>
						<input type="hidden" name="total_couponynsum" id='total_couponynsum' value="<?=$couponprice?>">
						<input type="hidden" name="total_sum" id='total_sum' value="<?=$p_price?>">
						<input type="hidden" name="total_sumprice" id='total_sumprice' value="<?=$p_price?>">
						<input type='hidden' name='total_deli_price' id='total_deli_price' value="<?=$deli_price?>" >
                        <input type='hidden' name='total_deli_price2' id='total_deli_price2' value="<?=$deli_price2?>" >
						<input type='hidden' name='gift' id='gift'>
						<input type='hidden' name='gift_no' id='gift_no'>
						<input type='hidden' name='chk_gift' id='chk_gift' value="<?=$chk_gift?>">
						<p class="title">결제 금액</p>
						<ul>
							<li>
								<span class="txt"><strong>전체상품금액</strong></span>
								<span class="price">
									<strong id="paper_goodsprice" ><?=number_format($sumprice)?></strong>
								</span>
							</li>
							<li>
								<span class="txt">배송료 (선불) </span>
								<span class="price order_price_style02">
									<font id='delivery_price'><?=number_format($deli_price)?></font>
								</span>
							</li>
							<li>
								<span class="txt">배송료 (착불) </span>
								<span class="price order_price_style02">
									<font id='delivery_price2'><?=number_format($deli_price2)?></font>
								</span>
							</li>
							<li>
								<span class="txt"><? if($staff_order == 'Y') { echo '임직원 '; } ?>마일리지 사용</span>
								<span class="price CLS_saleMil">0</span>
							</li>
							<li>
								<span class="txt">할인쿠폰</span>
								<span class="price CLS_bCoupon">0</span>
							</li>
							<li>
								<span class="txt">상품쿠폰</span>
								<span class="price CLS_prCoupon">0</span>
							</li>
							<li class="last-price">
								<span class="txt">총 결제금액</span>
								<span class="price"><strong id="price_sum"><?=number_format($sumprice+$deli_price)?></strong></span>
							</li>
						</ul>
					</div>
					<div class="payment-agree">
						<div class="agree-box"><input id="dev_agree" type="checkbox" class="checkbox-def"><label for="dev_agree">동의합니다.(전자상거래법 제 8조 제 2항)</label></div>
						<p>주문하실 상품, 가격, 배송정보, 할인내역 등을 <br>최종 확인하였으며,구매에 동의하시겠습니까?</p>
					</div>

					<div class="btn-place pay">
						<div id="paybuttonlayer" name="paybuttonlayer" style="display:block;">
							<a href="javascript:CheckForm()" onmouseover="window.status='결제';return true;" target="_self" class="btn-dib-function" ><span>CHECK OUT</span></a>


						
							<!-- <input type="button" value="사은품 레이어 ㅠㅠ" class="gift-use" > -->

							<!-- <a href="javascript:ordercancel('cancel')" onmouseover="window.status='취소';return true;" target="_self" class="btn_B" style="width:144px">취소하기</a> -->
						</div>
						<div id="payinglayer" name="payinglayer" style="display:none;">
							<img src="<?=$Dir?>img/common/paying_wait.gif" border=0>
						</div>
					</div>
					
					<!--사은품 배너 ㅠㅠ-->
	
					
					<div style="width:350px; padding-top:30px;">
							<script>
								//결제 > 쿠폰 레이어 창
								$(document).on("click",".gift-use",function(){
									if( $("#chk_gift").val() =='T'){
										$('div.gift-use-layer').fadeIn('fast');
									}
								});

							</script>
						<?if($chk_gift=='F'){?>
						<span style="color:red;">잠깐!)<?=number_format($chk_gift_price - $sumprice)?>원</span><span style="color:black"> 추가 구매 시 사은품이 증정됩니다</span>
						<br><br>
						<img src="gift_order.jpg" style="width:320px;" class="">
						<?}else{?>
						<span style="color:red;">잠깐!)<?=number_format($chk_gift_price)?>이상</span> <span style="color:black">구매하셔서, 사은품이 증정될 예정입니다 <br>
						<b>↓↓</b>아래 배너를 클릭하세요</span>
						<br> ※할인으로 구매금액 미충족시 사은품 선택불가 합니다
						<br><br>
						<img src="gift_order.jpg" style="width:320px;" class="gift-use">
						<?}?>
						
					</div>

				</div>
				<!-- // 결제하기 -->

			</div>
		</div>

	</div>


</div><!-- //메인 컨텐츠 -->
