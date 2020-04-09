<!-- start container -->
<div id="container" >



<input type=hidden name=tempkey>
<input type=hidden name=ordercode>
<input type=hidden name=type>
<input type=hidden name=ordercodeid value="<?=$ordercodeid?>">
<input type=hidden name=ordername value="<?=$ordername?>">


<?
$subTop_flag = 3;
//include ($Dir.MainDir."sub_top.php");
?>



<!-- 반품 신청 dimm layer -->
<div class="layer-dimm-wrap layer-refund regoods">
    <div class="dimm-bg"></div>
    <div class="layer-inner">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">

			<h4 class="table-title">취소/반품신청</h4>
			<table class="th-top util" id="rg_list">
				<colgroup>
					<col style="width:110px"><col style="width:100px"><col style="width:auto"><col style="width:100px"><col style="width:100px"><col style="width:100px"><col style="width:110px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">상품금액</th>
						<th scope="col">배송비</th>
						<th scope="col">할인금액</th>
						<th scope="col">결제금액</th>
					</tr>
				</thead>
				<tfoot class="refund-money">
				</tfoot>
				<tbody>
				</tbody>
			</table>

			<h4 class="table-title mt-50">환불방법 확인 및 기타사항</h4>
			<table class="th-left util">
				<caption>환불방법 선택</caption>
				<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
				<tr>
					<th scope="row">반품사유</th>
					<td class="reason">
<?php
					$oc_code_cnt = 0;
					foreach($oc_code as $key => $val) {
						if ($oc_code_cnt == 0) {
							$oc_code_sel	= " checked";
						} else {
							$oc_code_sel	= "";
						}
?>
						<input type="radio" name="b_sel_code" id="refund-type<?=$oc_code_cnt + 1?>" class="radio-def"<?=$oc_code_sel?> value="<?=$key?>">
						<label for="refund-type<?=$oc_code_cnt + 1?>"><?=$val?></label>
<?php
						$oc_code_cnt++;
					}
?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="exchange-info">상세사유</label></th>
					<td><textarea id="exchange-info" name="memo" cols="124" rows="5" class="textarea-def" placeholder="반품에 대한 상세사유를 입력해 주시기 바랍니다."></textarea></td>
				</tr>
<?
				if ($_ord->paymethod[0] == 'C') { // 카드결제일 경우
					$refund_text	= "신용카드 취소";
					$account_class	= " hide";
				} else if ($_ord->paymethod[0] == 'M') { // 휴대폰결제일 경우
					$refund_text	= "휴대폰결제 취소";
					$account_class	= " hide";
				} else if ($_ord->paymethod[0] == 'Y') { // 페이코결제일 경우
					$refund_text	= "PAYCO결제 취소";
					$account_class	= " hide";
				} else if ($_ord->paymethod[0] == 'V') { // 계좌이체결제일 경우
					$refund_text	= "계좌이체결제 취소";
					$account_class	= " hide";
				} else {
					$refund_text	= "계좌입금(무통장/가상계좌 입금의 경우는 계좌입금만 가능)";
					$account_class	= "";
				}
?>
				<tr>
					<th scope="row">환불방법</th>
					<td><span class='refund-way'><?=$refund_text?></span><span class='refund-way2 hide'>계좌입금(무통장/가상계좌 입금의 경우는 계좌입금만 가능)</span></td>
				</tr>
				<tr class="account-info<?=$account_class?>">
					<th scope="row">환불계좌정보</th>
					<td>
						<div class="select small">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value bankcode">은행선택</button>
							<ul class="a_list bank_list">
<?php
							foreach($oc_bankcode as $key => $val) {
?>
								<li><a href="javascript:;" bankcode='<?=$key?>'><?=$val?></a></li>
<?php
							}
?>
							</ul>
						</div>
						<label for="account-name">예금주</label>
						<input type="text" id="account-name" name="bankuser" class="input-def w100">
						<label for="account-number">계좌번호</label>
						<input type="text" id="account-number" name="bankaccount" class="input-def w200">
					</td>
				</tr>
				<tr class='re_addr'>
					<th scope="row">반송처 주소</th>
					<td class='re_addr_in'></td>
				</tr>
			</table>
			<div class="mypage-content-wrap">
				<dl class="attention">
					<dt>취소/반품신청 이용안내</dt>
					<dd>해당 상품을 취소/반품하려는 사유를 꼭 선택해 주세요.</dd>
					<dd>상품이 손상/훼손 되었거나 이미 사용하셨다면 반품이 불가능합니다.</dd>
					<dd>반품 사유가 단순변심, 구매자 사유일 경우반품 배송비를 상품과 함께 박스에 동봉해 주세요.</dd>
					<dd>배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다.</dd>
					<dd>반품 사유가 상품불량/파손, 배송누락/오배송 등 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다.</dd>
					<dd>상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다.</dd>
				</dl>
			</div>
			<div class="btn-place mt-30">
				<button class="btn-dib-function refundSubmit" type="submit"><span>신청</span></button>
				<button class="btn-dib-function line layer_close" type="button"><span>취소</span></button>
			</div>


        </div><!-- //.layer-content -->
    </div>
</div>

<!-- 교환신청 신청 dimm layer -->
<div class="layer-dimm-wrap layer-refund change">
    <div class="dimm-bg"></div>
    <div class="layer-inner">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">

			<h4 class="table-title">교환신청</h4>
			<table class="th-top util" id="cg_list">
				<colgroup>
					<col style="width:110px"><col style="width:100px"><col style="width:auto"><col style="width:300px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">변경할 옵션</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

			<h4 class="table-title mt-50">교환 사유 및 정보</h4>
			<table class="th-left util">
				<caption>교환사유 선택</caption>
				<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
				<tr>
					<th scope="row">교환 사유</th>
					<td class="reason">
<?php
					$oc_code_cnt = 0;
					foreach($oc_code as $key => $val) {
						if ($oc_code_cnt == 0) {
							$oc_code_sel	= " checked";
						} else {
							$oc_code_sel	= "";
						}
?>
						<input type="radio" name="c_sel_code" id="change-type<?=$oc_code_cnt + 1?>" class="radio-def"<?=$oc_code_sel?> value="<?=$key?>">
						<label for="change-type<?=$oc_code_cnt + 1?>"><?=$val?></label>
<?php
						$oc_code_cnt++;
					}
?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="exchange-info">상세사유</label></th>
					<td><textarea id="exchange-info" name="memo" cols="30" rows="10" class="textarea-def" placeholder="교환에 대한 상세사유를 입력해 주시기 바랍니다."></textarea></td>
				</tr>
				<tr class='re_addr'>
					<th scope="row">반송처 주소</th>
					<td class='re_addr_in'></td>
				</tr>
			</table>
			<div class="mypage-content-wrap">
				<dl class="attention">
					<dt>교환신청 이용안내</dt>
					<dd>교환을 원하는 상품 정보(디자인/사이즈등)를 상세히 기입해 주세요.</dd>
					<dd>교환은 같은 옵션상품만 가능합니다. 다른 옵션의 상품으로 교환을 원하실 경우, 반품 후 재 구매를 해주세요.</dd>
					<dd>상품이 손상/훼손 되었거나 이미 사용하셨다면 교환이 불가능합니다.</dd>
					<dd>교환 사유가 구매자 사유일 경우 왕복 교환 배송비를 상품과 함께 박스에 동봉해 주세요.</dd>
					<dd>교환 왕복 배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다.</dd>
					<dd>교환 사유가 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다.</dd>
					<dd>상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다.</dd>
				</dl>
			</div>
			<div class="btn-place mt-30">
				<button class="btn-dib-function refundSubmit" type="submit"><span>신청</span></button>
				<button class="btn-dib-function line layer_close" type="button"><span>취소</span></button>
			</div>


        </div><!-- //.layer-content -->
    </div>
</div>

	<div class="containerBody sub-page">
	<form name=form1 method=post action="<?=$_SERVER['PHP_SELF']?>">
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li><a href="mypage.php">MY PAGE</a></li>
				<li class="on"><a>주문배송조회</a></li>
			</ul>
		</div>

	<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
			<!---->
		</div><!-- //LNB -->

		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">

			<div class="my-order-detail">
            <div class="order-list-flow">
            	<dl>
                	<dt><span>01</span>주문접수</dt>
                    <dd>상품 주문접수,<br />입금 확인중입니다.</dd>
                </dl>
                <dl>
                	<dt><span>02</span>결제완료</dt>
                    <dd>결제 및 무통장 입금<br />확인 완료되었습니다.</dd>
                </dl>
                <dl>
                	<dt><span>03</span>배송준비중</dt>
                    <dd>상품을 준비하고<br />있습니다.</dd>
                </dl>
                <dl>
                	<dt><span>04</span>배송중</dt>
                    <dd>택배사 발송을 완료,<br />배송 중입니다.</dd>
                </dl>
                <dl>
                	<dt><span>05</span>배송완료</dt>
                    <dd>고객님이 상품을<br />수취한 상태입니다.</dd>
                </dl>
            </div>

			<div class="order-no"><? if( $_ord->staff_order == 'Y' ) { echo '임직원 '; } ?>주문 No. <?=$ordercode?> <span>(<? echo " ".substr($ordercode,0,4)."-".substr($ordercode,4,2)."-".substr($ordercode,6,2)." ".substr($ordercode,8,2).":".substr($ordercode,10,2).":".substr($ordercode,12,2); ?>)</span></div>
			
			<table class="th-top util">
				<colgroup>
					<col style="width:100px"><col style="width:auto"><col style="width:100px"><col style="width:100px"><col style="width:100px"><col style="width:110px"><col style="width:140px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">상품금액</th>
						<th scope="col">배송비</th>
						<th scope="col">주문금액</th>
						<th scope="col">진행단계</th>
						<th scope="col">주문관리</th>
					</tr>
				</thead>
				<tbody>

<?php
		$rspan_cnt	= 1;
		$ven_cnt	= 1;
		$can_cnt	= 0;
		$pr_idxs		= "";
		$op_cnt	= count($orProduct);
		foreach( $orProduct as $pr_idx=>$prVal ) { // 상품

			if ($pr_idxs == '') {
				$pr_idxs		.= $pr_idx;
			} else {
				$pr_idxs		.= "|".$pr_idx;
			}

			$file = getProductImage($Dir.DataDir.'shopimages/product/', $prVal->tinyimage);

			/*$optStr = '';
			$option1	 = '';
			$option2	 = '';

			if(strlen( $prVal->opt1_name ) > 0 ){
				$optStr = $prVal->option1.' : '.$prVal->opt1_name;
				$option1	= $prVal->option1.' : '.$prVal->opt1_name;
				if(strlen( $prVal->opt2_name ) > 0 ){
					$optStr .= ' / '.$prVal->option2." : ".$prVal->opt2_name;
					$option2	= $prVal->option2." : ".$prVal->opt2_name;
				}
				$optStr .= " / 수량 : ".number_format($prVal->option_quantity)."개";
			}*/

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
				$optStr .= " / 수량 : ".number_format($prVal->option_quantity)."개";
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
				$ven_cnt	= $orvender[$prVal->vender]['t_pro_count'];
				$rspan_cnt	= $orvender[$prVal->vender]['t_pro_count'];
			} else {
				$rspan_cnt--;
			}

			if ($ven_cnt == $rspan_cnt && $ven_cnt > 1) {
				$rowspan	= " rowspan='".$rspan_cnt."'";
			} else {
				$rowspan	= "";
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
			$pro_info	.= $prVal->sellprice."!@#";
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

			$pro_info	.= $can_deli_price."!@#";
			$pro_info	.= $can_total_price;
?>
					<tr id="idx_<?=$pr_idx?>" class='product_info'  info = "<?=$pro_info?>">
						<td><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prVal->productcode?>"><img src="<?=$file?>" border="0" class='img-size-mypage' ></a></td>
						<td class="ta-l">							
							<span class="brand-color">[<?=$prVal->brandname?>]</span><br>
							<span><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prVal->productcode?>"><?=$prVal->productname?></a></span><br>
							<span><?=$optStr?></span>	
							<?if ($prVal->option_price > 0) {?><br><span>(+ <?=number_format($prVal->option_price)?>원)</span><?}?>
						</td>
						<td><?if ($prVal->consumerprice > 0 && $prVal->consumerprice != $prVal->price) {?><del><?=number_format($prVal->consumerprice)?></del><br><?}?><strong><?=number_format($prVal->price)?></strong></td>
						<?if ($ven_cnt == $rspan_cnt) {?>
						<td<?=$rowspan?>><?=number_format($orvender[$prVal->vender]['t_deli_price'])?></td>
						<td<?=$rowspan?>><strong><?=number_format($orvender[$prVal->vender]['t_pro_price'])?></strong></td>
						<?}?>
						<td><?=$op_step[$prVal->op_step]?><?if($prVal->op_step == 3){?><br><button type="button" class="btn-dib-line CLS_delivery_tracking" urls = "<?=$delicomlist[$prVal->deli_com]->deli_url.$prVal->deli_num?>"><span>배송추적</span></button><?}?></td>
						<td>
							<? if ($prVal->op_step < 40) { //주문취소 신청및 완료상태가 아닌경우
									if( $prVal->op_step == 1 /* || $prVal->op_step == 2*/){ // 입금완료, 배송준비일 경우
										if ($op_cnt == 1 || ( $_ord->paymethod[0] == "M" && $_ord->paymethod[1] == "E" ) ) { 
                                            // 주문상품이 한개일경우 전체 취소로 한다.
                                            // 또는 다날 휴대폰 결제인 경우도 전체 취소로 한다. (부분취소 방법을 아직 알 수 없어서 이렇게 함)
											echo "-";
							?>
									<!-- button type="button" class="btn-function ord_cancel" ordercode = "<?=$ordercode?>" idxs = "<?=$pr_idxs?>" pc_type="ALL" paymethod="<?=$_ord->paymethod[0]?>"><span>주문취소</span></button -->	
							<?
										} else {
							?>
									<button type="button" class="btn-dib-line ord_cancel" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>"><span>주문취소</span></button>			
							<? 
										}
									} else if( $prVal->op_step == 3 ){ // 배송중일 경우
							?>
									<button type="button" class="btn-dib-line  ord_regoods" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>"><span>반품접수</span></button>			
									<button type="button" class="btn-dib-line  ord_change" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>"><span>교환접수</span></button>	
									<button type="button" class="btn-dib-line deli_ok" ordercode = "<?=$ordercode?>" idx = "<?=$pr_idx?>" pc_type="PART" paymethod="<?=$_ord->paymethod[0]?>"><span>구매확정</span></button>											
							<?
									} else if(  $prVal->op_step == 4) { //배송완료일 경우
							?>
								<button type="button" class="btn-dib-line" onClick="javascript:document.location.href='<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prVal->productcode?>'"><span>리뷰쓰기</span></button>			
							<?
									} else {
										echo "-";
									}
								} else {
									echo "-";
								}
							?></td>
					</tr>
<?php
				if ($prVal->op_step >= 40) $can_cnt++;
		} 
?>		
				</tbody>
			</table>
			<div class="total-price-sum">
				<ul>
					<li><span>총 주문금액</span><?=number_format($_ord->price)?></li>
					<li class="plus"><span>배송비</span><?=number_format($_ord->deli_price)?></li>
					<li class="minus"><span>총 할인금액</span><?=number_format($_ord->dc_price + $_ord->reserve)?></li>
					<li class="total"><span>총 결제금액</span><strong><?=number_format($_ord->price-$_ord->dc_price-$_ord->reserve+$_ord->deli_price)?></strong></li>
				</ul>
			</div>
<?
			if ($_ord->oi_step1 < 2 && $can_cnt == 0) {
				if ($_ord->oi_step1 == 0) {
					$add_class		= " ord_receive_cancel";
				} else {
					$add_class		= " ord_cancel";
				}
?>
			<div class="btn-total-cancle"><button class="btn-dib-line<?=$add_class?>" type="button" ordercode = "<?=$ordercode?>" idxs = "<?=$pr_idxs?>" pc_type="ALL" paymethod="<?=$_ord->paymethod[0]?>"><span>전체주문취소</span></button></div>
<?
			}
?>
			<div class="my-half-table">
				<div class="inner-left">
					<p class="title">주문자 정보</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">주문자</th>
							<td><?=$_ord->sender_name?></td>
						</tr>
						<tr>
							<th scope="row">휴대전화</th>
							<td><?=$_ord->sender_tel?></td>
						</tr>
						<tr>
							<th scope="row">전화번호</th>
							<td></td>
						</tr>
						<tr>
							<th scope="row">이메일</th>
							<td><?=$_ord->sender_email?></td>
						</tr>
					</table>
				</div>
				<div class="inner-right">
					<p class="title">배송지 정보</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">받는사람</th>
							<td><?=$_ord->receiver_name?></td>
						</tr>
						<tr>
							<th scope="row">휴대전화</th>
							<td><?=$_ord->receiver_tel2?></td>
						</tr>
						<tr>
							<th scope="row">전화번호</th>
							<td><?=$_ord->receiver_tel1?></td>
						</tr>
						<tr>
							<th scope="row">주소</th>
							<td><?if($_ord->deli_type == "2"){ echo "해당 주문은 고객 [직접수령] 입니다"; } else { echo $_ord->receiver_addr; }?></td>
						</tr>
						<tr>
							<th scope="row">배송 요청사항</th>
							<td>
								<?if($_ord->order_msg2){?>
									<?=$_ord->order_msg2?>
								<?}else{?>
									-
								<?}?>
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="my-half-table">
				<div class="inner-left">
					<p class="title">결제금액 할인</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">총 주문금액</th>
							<td><?=number_format($_ord->price)?></td>
						</tr>
						<tr>
							<th scope="row">배송비</th>
							<td><?=number_format($_ord->deli_price)?></td>
						</tr>
<?php
if(  $_ord->staff_order == 'N' ){
?>
						<tr>
							<th scope="row">상품할인</th>
							<td><?=number_format($t_product_sale)?></td>
						</tr>
						<tr>
							<th scope="row">쿠폰할인</th>
							<td><?=number_format($t_coupon_sale)?></td>
						</tr>
						<tr>
							<th scope="row">마일리지 사용</th>
							<td><?=number_format($_ord->reserve)?></td>
						</tr>
<?php
} else if( $_ord->staff_order == 'Y' ) {
?>
						<tr>
							<th scope="row">임직원 마일리지 사용</th>
							<td><?=number_format($_ord->reserve)?></td>
						</tr>
<?php
}
?>
						<tr>
							<th scope="row">결제금액</th>
							<td><?=number_format($_ord->price-$_ord->dc_price-$_ord->reserve+$_ord->deli_price)?></td>
						</tr>
					</table>
				</div>

<?
				$arpm=array("V"=>"실시간계좌이체","C"=>"신용카드","P"=>"매매보호 - 신용카드", "M"=>"핸드폰","B"=>"무통장입금","O"=>"가상계좌","Q"=>"매매보호 - 가상계좌","Y"=>"PAYCO");
?>
				<div class="inner-right">
					<p class="title">결제정보</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">결제방법</th>
							<td>
								<?=$arpm[$_ord->paymethod[0]]?>
<?
							if(strstr("CP", $_ord->paymethod[0])) {
?>
								&nbsp;&nbsp;<button href="#" class="btn-dib-line  pop_receiptCardView" type="button" ordercode = '<?=$ordercode?>' >
									<span>매출전표</span>
								</button>
<?
							}
?>
							</td>
						</tr>

<?	
				$_ord_pay_data	= str_replace(":", " ", $_ord->pay_data);
				$_ord_pay_data	= str_replace("예금주 ", "예금주:", $_ord_pay_data);
				$_ord_pay_data	= explode(" ", $_ord_pay_data);

				if(strstr("VCPM", $_ord->paymethod[0])) {
					$o_year = substr($ordercode, 0, 4);
					$o_month = substr($ordercode, 4, 2);
					$o_day = substr($ordercode, 6, 2);
					$o_hour = substr($ordercode, 8, 2);
					$o_min = substr($ordercode, 10, 2);
					$o_sec = substr($ordercode, 12, 2);

					$msg = $o_year."-".$o_month."-".$o_day." ".$o_hour.":".$o_min.":".$o_sec;
?>
						<tr>
							<th scope="row">승인일자</th>
							<td><?=$msg?></td>
						</tr>

<?
				} else if (strstr("BOQ", $_ord->paymethod[0])) {
					if ($_ord->bank_date >= 14) { 						
						$o_year = substr($_ord->bank_date, 0, 4);
						$o_month = substr($_ord->bank_date, 4, 2);
						$o_day = substr($_ord->bank_date, 6, 2);
						$o_hour = substr($_ord->bank_date, 8, 2);
						$o_min = substr($_ord->bank_date, 10, 2);
						$o_sec = substr($_ord->bank_date, 12, 2);

						$msg = $o_year."-".$o_month."-".$o_day." ".$o_hour.":".$o_min.":".$o_sec;
					} else {
						if($_ord->pay_flag=="0000"){
							$msg = "입금 대기중";
						}
					}
?>
						<tr>
							<th scope="row">입금은행</th>
							<td><?=$_ord_pay_data[0]?></td>
						</tr>
						<tr>
							<th scope="row">입금계좌</th>
							<td><?=$_ord_pay_data[1]." ".$_ord_pay_data[2]?></td>
						</tr>
<?
					if(strstr("B", $_ord->paymethod[0])) {
?>
						<tr>
							<th scope="row">입금자 명</th>
							<td><?=$_ord->bank_sender?></td>
						</tr>
<?
					}
?>
						<tr>
							<th scope="row">입금확인</th>
							<td><?=$msg?></td>
						</tr>
<?
				}
?>
					</table>
				</div>
			</div>

			<div class="btn-place">
				<a href="mypage_orderlist.php" class="btn-dib-function"><span>목록보기</span></a>
				<a href="/" class="btn-dib-function line"><span>쇼핑하기</span></a>
			</div>

<style type="text/css">
	.layer {display:none; position:fixed; _position:absolute; top:0; left:0; width:100%; height:100%; z-index:100;}
	.layer .bg {position:absolute; top:0; left:0; width:100%; height:100%; background:#000; opacity:.5; filter:alpha(opacity=50);}
	.layer .pop-layer {display:block;}

	.pop-layer {display:none; position: absolute; top: 50%; left: 50%; width: 410px; height:auto;  background-color:#fff; border: 2px solid #000; z-index: 10;}	
	.pop-layer .pop-container {padding: 20px 25px;}
	.pop-layer p.ctxt {color: #666; line-height: 25px;}
</style>
<div class="layer" style="display: none;">
	<div class="bg"></div>
	<div id="layer1" class="pop-layer">
		<div class="pop-container">
			<div class="pop-conts">
				<!--content //-->
				<h3 class="title mb_20">반송 신청</h3>
				<table class="th_left" cellpadding="0" cellspacing="0" border="0" width="100%">
					<colgroup>
						<col style="width:120px"><col style="width:auto">
					</colgroup>
					<tbody>
					<tr>
						<th>반송사유</th>
						<td>
							<textarea name="redelivery_reason_content" id="redelivery_reason_content" rows="10" style="width: 98%;resize: none;"></textarea>
							<input type="hidden" id="redelivery_ordercode" >
							<input type="hidden" id="redelivery_productcode" >
							<input type="hidden" id="redelivery_obj" >
						</td>
					</tr>
					</tbody>
				</table>
				<div class="ta_c mt_20">
					<a href="javascript:;" id="redelivery_reson_on" class="btn_D on">반송신청</a>
					<a href="javascript:;" id="redelivery_reson_close" class="btn_D cbtn">취소</a>
				</div>
				<!--// content-->
			</div>
		</div>
	</div>
</div>
</form>

<form name=kcpform method=post action="../paygate/A/cancel.php">
<input type=hidden name=sitecd value="<?=$pgid_info["ID"]?>">
<input type=hidden name=sitekey value="<?=$pgid_info["KEY"]?>">
<input type=hidden name=ordercode value="<?=$_ord->ordercode?>">
<input type=hidden name=paymethod value="<?=$_ord->paymethod[0]?>">
<input type=hidden name=return_host value="<?=urlencode($_SERVER['HTTP_HOST'])?>">
<input type=hidden name=return_script value="<?=str_replace($_SERVER['HTTP_HOST'],"",$_ShopInfo->getShopurl()).FrontDir."mypage_orderlist_view.php"?>">
<input type=hidden name=return_data value="ordercode=<?=$ordercode?>">
<input type=hidden name=return_type value="form">
</form>
