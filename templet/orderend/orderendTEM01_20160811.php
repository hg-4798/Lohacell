<!-- 메인 컨텐츠 -->
<div class="containerBody sub-page">
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li class="on"><a>주문완료</a></li>
			</ul>
		</div>

		<div class="cart-wrap">

			<div class="cart-my-benefit">
				<div class="inner-flow">
                	<ul>
                    	<li><span>01</span> 쇼핑백</li>
                        <li><span>02</span> 주문결제</li>
                        <li class="on"><span>03</span> 주문완료</li>
                    </ul>
                </div>
<?php
if( strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
				<div class="inner-benefit ">
					<p><strong><?=$_ShopInfo->memname?></strong>님의 혜택정보</p>
					<p>마일리지 <strong><?=number_format( $mem_reserve->reserve )?>M</strong> l 할인쿠폰 <strong><?=number_format( $coupon_cnt )?>장</strong></p>
					<p class="hide">비회원 구매시 할인/쿠폰과 이벤트 등의<br>혜택을 받으실 수 없습니다.</p>
				</div>
<?php
} else {
?>
				<div class="inner-benefit hide"><!-- 비회원일 경우 출력 -->
					<p class="no-member">비회원 구매시 할인/쿠폰과 이벤트 <br>등의 혜택을 받으실 수 없습니다.</p>
				</div>
<?php
}
?>
			</div>

			<div class="end-ment"></div>




		<div class="order-end-wrap">

<?php
	if (strstr("B", $_ord->paymethod[0]) || (strstr("VOQCPMY", $_ord->paymethod[0]) && strcmp($_ord->pay_flag,"0000")==0)){
?>
			<div class="end-ment">
				<h5 class="title">주문이 정상적으로 처리되었습니다.<br> 주문번호 : <?=$ordercode?></h5>
				<p>(입금기한 내에 상품이 판매종료, 품절될 경우 예약이 자동으로 취소됩니다.)</p>
				<!-- <div class=""><?=$_data->shopname?>를 이용해 주셔서 감사합니다.</div>
				<div class="">주문번호 : <?=$ordercode?></div> -->
			</div>
<?php
	}else{
?>
			<div class="end-ment">
				<h5 class="title">결제가 취소 되었습니다.</h5>
			</div>
<?	} ?>
			<!-- 주문 상품 -->
			<h4 class="table-title">주문상품 정보</h4>
<?php
$sumprice = 0;
$in_reserve = 0;
foreach( $venderArr as $vender => $productArr ){
	$venderName = get_vender_name( $vender );
	$vender_price = 0;
	$vender_deli_price = 0;
?>

			<table class="th-top util">
				<caption>주문상품 정보</caption>
				<colgroup>
					<col style="width:100px" >
					<col style="width:auto" >
					<col style="width:130px" >
					<col style="width:240px" >
					<!-- <col style="width:110px" > -->
					<col style="width:110px" >
					<col style="width:110px" >
				</colgroup>

				<thead>
					<tr>
						<th scope="col" colspan="2"><strong>업체 배송 상품 [<?=$venderName?>]</strong></th>
						<th scope="col">수량</th>
						<th scope="col">옵션</th>
						<!-- <th scope="col">사이즈</th> -->
						<th scope="col">적립 마일리지</th>
						<th scope="col">상품금액</th>
					</tr>
				</thead>

				<tbody>
<?php
	$product_price = 0;
	foreach( $productArr as $key=>$val ){
		$product_price = ( $val['price'] + $val['option_price'] ) * $val['quantity'];
		$vender_price += $product_price;
		$vender_deli_price += $val['deli_price'];
		$in_reserve += $val['reserve'];
?>
					<tr class="cart-item-tr">
						<td class="thumb-img">
							<img class="img-size-mypage" src="<?= getProductImage( $imgPath, $val['tinyimage'] )?>">
						</td>
						<td class="ta-l">
							<span class="brand-color"><?=$venderName?></span><br>
							<span><?=$val['productname']?></span><br>
<?php
		if( strlen( $val['opt1_name'] ) > 0  || strlen( $val['text_opt_subject'] ) > 0 ){
?>
							<span>
<?php
			$tmp_opt_subject = explode( '@#', $val['opt1_name'] );
			$tmp_opt_content = explode( chr(30), $val['opt2_name'] );
			foreach( $tmp_opt_subject as $subjectKey=>$subjectVal ){
				echo ' [ '.$subjectVal.' : '.$tmp_opt_content[$subjectKey].' ] <br>';
			} // opt_subject foreach

			if( strlen( $val['text_opt_subject'] ) > 0 ){
				$tmp_text_opt_subject = explode( '@#', $val['text_opt_subject'] );
				$tmp_text_opt_content = explode( '@#', $val['text_opt_content'] );
				foreach( $tmp_text_opt_subject as $subjectKey=>$subjectVal ){
					echo ' [ '.$subjectVal.' : '.$tmp_text_opt_content[$subjectKey].' ] <br>';
				} // opt_subject foreach
			}
			echo ' 추가금액 '.number_format( $val['option_price'] * $val['quantity'] );
?>
							</span><br>
<?php
		} // opt1_name len if
?>
							<span>배송비 <?=number_format( $val['deli_price'] )?></span>
						</td>
						<td>
							<?=$val['quantity']?>
						</td>
						<td>
<?php
		if( strlen( $val['opt1_name'] ) > 0 ){
?>
							<span>
<?php
			$tmp_opt_subject = explode( '@#', $val['opt1_name'] );
			$tmp_opt_content = explode( chr(30), $val['opt2_name'] );
			foreach( $tmp_opt_subject as $subjectKey=>$subjectVal ){
				echo $subjectVal.' : '.$tmp_opt_content[$subjectKey].'<br>';
			} // opt_subject foreach

			if( strlen( $val['text_opt_subject'] ) > 0 ){
				$tmp_text_opt_subject = explode( '@#', $val['text_opt_subject'] );
				$tmp_text_opt_content = explode( '@#', $val['text_opt_content'] );
				foreach( $tmp_text_opt_subject as $subjectKey=>$subjectVal ){
					echo $subjectVal.' : '.$tmp_text_opt_content[$subjectKey].'<br>';
				} // opt_subject foreach
			}

?>
							</span><br>
<?php
		} else {// opt1_name len if
            echo "-";
        }

		if( strlen( $_ShopInfo->getMemid() ) == 0 ){ // 로그인을 안했을 경우
			$val['reserve']	= 0;
		}
?>
						</td>
						<!-- <td>

                        </td> -->
						<td>
							<?=number_format( $val['reserve'] )?>M
						</td>
						<td class="price-with-del">
<?php
		if( $val['consumerprice'] > 0 ){
?>
							<del><?=number_format( $val['consumerprice'] * $val['quantity'] )?></del>
<?php
		}
?>
							<?=number_format( $product_price )?>
						</td>
					</tr>
<?php
	} // product foreach
?>
				</tbody>

				<tfoot>
					<tr>
						<td colspan="7" bgcolor="#fafafa">
							합계 <?=number_format( $vender_price )?>
							<span>+</span>
							배송비 <?=number_format( $vender_deli_price )?>
							<span>=</span>
							주문 금액 <strong><?=number_format( $vender_price + $vender_deli_price )?></strong>
							<span class="delivery-ment">[<?=$venderName?>] 제품으로만 <?=number_format( $val['deli_mini'] )?> 이상 구매시 무료배송됩니다.</span>
						</td>
					</tr>
				</tfoot>
			</table>
<?php
} // vender foreach
$script_price = $_ord->price + $_ord->deli_price - $_ord->dc_price - $_ord->reserve;
if( strlen( $_ShopInfo->getMemid() ) == 0 ){ // 로그인을 안했을 경우
	$in_reserve	= 0;
}
?>
			<div class="total-price-sum">
				<ul>
					<li>
						<span>총 주문금액</span>
						<?=number_format( $_ord->price )?>
					</li>
					<li class="minus">
						<span>총 할인금액</span>
						<?=number_format( $_ord->dc_price + $_ord->reserve )?>
					</li>
					<li class="plus">
						<span>배송비</span>
						<?=number_format ( $_ord->deli_price )?>
					</li>
					<li class="total">
						<span>총 결제금액</span>
						<strong><?=number_format( $_ord->price + $_ord->deli_price - $_ord->dc_price - $_ord->reserve )?></strong>
					</li>
					<li><span>적립 마일리지</span><?=number_format( $in_reserve )?>M </li>
				</ul>
			</div>



<?php
/*
	if($_ord->deli_type == "2"){
?>
			<div class="cart_order_wrap">
				<table class="info_table">
				<caption>해당 주문은 고객 [직접수령] 입니다</caption></td>
				</table>
			</div>
<?php
	}
	if( strlen( trim($_ord->overseas_code) ) > 0 ){
?>
			<div class="cart_order_wrap">
				<table class="info_table">
				<caption>해당 주문은 해외배송이 포함되어있습니다.<br><br>[ 통관번호 : <font color='red'><?=$_ord->overseas_code?></font> ]</caption></td>
				</table>
			</div>
<?php
	}
	*/
?>
			<?
				if(strstr("VCPMY", $_ord->paymethod[0])) {
					$arpm=array("V"=>"실시간계좌이체","C"=>"신용카드","P"=>"매매보호 - 신용카드", "M"=>"핸드폰", "Y"=>"PAYCO");
					$subject = "결제일자";
					$o_year = substr($ordercode, 0, 4);
					$o_month = substr($ordercode, 4, 2);
					$o_day = substr($ordercode, 6, 2);
					$o_hour = substr($ordercode, 8, 2);
					$o_min = substr($ordercode, 10, 2);
					$o_sec = substr($ordercode, 12, 2);

					$msg = $o_year."-".$o_month."-".$o_day." ".$o_hour.":".$o_min.":".$o_sec;
				} else if (strstr("BOQ", $_ord->paymethod[0])) {
					$arpm=array("B"=>"무통장입금","O"=>"가상계좌","Q"=>"매매보호 - 가상계좌");
					if(strstr("B", $_ord->paymethod[0])){
						$msg = "<div>입금자 : ".$_ord->bank_sender."</div> <div>계좌 : ".$_ord->pay_data."</div>";
					}else{
						if($_ord->pay_flag=="0000"){
							$msg = "<div>(입금확인후 배송이 됩니다.)</div>";
						}
						if(strstr("O", $_ord->paymethod[0])){
							$msg = "<div>가상계좌 : ".$_ord->pay_data."</div> ".$msg;
						}else if(strstr("Q", $_ord->paymethod[0])){
							$msg = "<div>매매보호 - 가상계좌 : ".$_ord->pay_data."</div> ".$msg;
						}
					}
					$subject = "추가정보";
				}
			?>

			<h4 class="table-title">결제정보</h4>
			<table class="th-left-util">
				<caption>결제 정보</caption>
				<colgroup><col style="width:10%" ><col style="width:40%" ><col style="width:10%" ><col style="width:40%" ></colgroup>
				<tr>
					<th scope="col">결제금액</th>
					<td><b><?=number_format($_ord->price-$_ord->dc_price-$_ord->reserve+$_ord->deli_price)?>원</b></td>
					<th scope="col">결제방법</th>
					<td><?=$arpm[$_ord->paymethod[0]]?></td>
				</tr>
				<tr>
					<th scope="row"><?=$subject?></th>
					<td colspan=3><?=$msg?></td>
				</tr>
			</table>

			<h4 class="table-title">배송지 정보</h4>
			<table class="th-left-util">
				<caption>배송지 정보</caption>
				<colgroup><col style="width:10%" ><col style="width:40%" ><col style="width:10%" ><col style="width:40%" ></colgroup>
				<tr>
					<th scope="row">받는 사람</th>
					<td colspan=3><?=$_ord->receiver_name?></td>
				</tr>
				<tr>
					<th scope="row">휴대전화</th>
					<td><?=$_ord->receiver_tel2?></td>
					<th scope="row">전화번호</th>
					<td><?=$_ord->receiver_tel1?></td>
				</tr>
				<tr>
					<th scope="row">주소</th>
					<td colspan=3><?=$_ord->receiver_addr?></td>
				</tr>
				<tr>
					<th scope="row">배송 요청사항</th>
					<td  colspan=3>
						<?if($_ord->order_msg2){?>
							<?=$_ord->order_msg2?>
						<?}else{?>
							-
						<?}?>
					</td>
				</tr>
			</table>




			<div class="btn-place">
				<a href="javascript:;" class = 'CLS_OrderView btn-dib-function'><span>주문내역 확인</span></a>
				<a href="javascript:;" class = 'CLS_GoToMain btn-dib-function line'><span>쇼핑 계속하기</span></a>
			</div>


		</div>

</div><!-- //메인 컨텐츠 -->

<form name='mypageOrderViewFrm' method='POST' action='<?=$Dir.FrontDir?>mypage_orderlist_view.php'>
	<input type='hidden' name='ordercode' value = '<?=$ordercode?>'>
</form>

<?
	$strCriteo = '';
	if(count($arrCriteo)>0){
		$arrCriteoReSettings = array();
		foreach($arrCriteo as $dc){
			$arrCriteoReSettings[] = '{ i: "'.$dc['code'].'", t: "'.$dc['name'].'", p: "'.$dc['price'].'", q: "'.$dc['ea'].'" }';
		}
		$strCriteo = implode(", ", $arrCriteoReSettings);
?>

<?
	}
?>
