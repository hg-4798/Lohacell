<?
	$sql = "SELECT 
					a.productcode, a.productname, a.price, a.reserve, a.opt1_name, a.opt2_name,
					a.tempkey, a.addcode, a.quantity, a.order_prmsg, a.selfcode,
					a.package_idx, a.assemble_idx, a.assemble_info, b.tinyimage 
				FROM 
					tblorderproduct a LEFT JOIN tblproduct b on a.productcode=b.productcode
				WHERE 
					a.ordercode='".$ordercode."' 
				ORDER BY productcode ASC ";
	$result=pmysql_query($sql,get_db_conn());
	$sumprice=0;
	$sumreserve=0;
	$totprice=0;
	$totreserve=0;
	$totquantity=0;
	$cnt=0;
	$etcdata=array();
	$prdata=array();

	//총 주문금액대별 할인금액
	list($tot_price_dc)=pmysql_fetch("select tot_price_dc from tblorderinfo where ordercode='".$ordercode."'");
	//
	
?>
<!-- 메인 컨텐츠 -->
<div class="main_wrap">
		<?
		$subTop_flag = 3;
		//include ($Dir.MainDir."sub_top.php");
		?>
		<div class="cart_wrap">
		<div class="cart_complete_wrap">
			<h3 class="title mt_20">
				장바구니
				<p class="line_map"><a>장바구니</a> &gt; <a>주문/결제</a> &gt; <a class="on">주문완료</a></p>
			</h3>
<?php
	if (strstr("B", $_ord->paymethod[0]) || (strstr("VOQCPM", $_ord->paymethod[0]) && strcmp($_ord->pay_flag,"0000")==0)){
?>
			<div class="message_wrap m0">
				<h5><strong>주문</strong>이 정상적으로 <strong>완료</strong>되었습니다.</h5>
				<div class="ng_14 ta_c mt_10"><?=$_data->shopname?>를 이용해 주셔서 감사합니다.</div>
				<div class="mt_30 ta_c">주문번호 : <?=$ordercode?></div>
			</div>
<?php
	}else{
?>
			<div class="message_wrap m0">
				<h5><strong>결제</strong>가 <strong>취소</strong>되었습니다.</h5>
				<!--<div class="ng_14 ta_c mt_10">디지아톰을 이용해 주셔서 감사합니다.</div>-->
			</div>
<?	} ?>
			<!-- 주문 상품 -->
			<table class="list_table" summary="담은 상품의 정보, 판매가, 수량, 할인금액, 결제 예정가, 적립금을 확인할 수 있습니다.">
				<caption>주문 상품</caption>
				<colgroup>
					<col style="width:auto" />
					<col style="width:95px" />
					<col style="width:85px" />
					<col style="width:85px" />
					<!--<col style="width:95px" />-->
				</colgroup>
				<thead>
					<tr>
						<th scope="col">상품정보</th>
						<th scope="col">결제가</th>
						<th scope="col">수량</th>
						<th scope="col">총 결제가</th>
						<!--<th scope="col">적립금</th>-->
					</tr>
				</thead>
				<tbody>
				<?
					$i=0;
					$arrCriteo = array();
					while($row=pmysql_fetch_object($result)) {
						$i++;

						$optvalue="";
						if(preg_match("/^(\[OPTG)([0-9]{3})(\])$/",$row->opt1_name)) {
							$optioncode=$row->opt1_name;
							$row->opt1_name="";
							$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='".$ordercode."' AND productcode='".$row->productcode."' ";
							$sql.= "AND opt_idx='".$optioncode."' ";
							$result2=pmysql_query($sql,get_db_conn());
							if($row2=pmysql_fetch_object($result2)) {
								$optvalue=$row2->opt_name;
							}
							pmysql_free_result($result2);
						}

						$isnot=false;
						if (substr($row->productcode,0,3)!="999" && substr($row->productcode,0,3)!="COU") {
							$no++;
							$sumreserve=$row->reserve*$row->quantity;
							$totreserve+=$sumreserve;
							$totquantity+=$row->quantity;
							$isnot=true;
						}


						if(preg_match("/^(COU)([0-9]{8})(X)$/",$row->productcode)) {
							#쿠폰
							$etcdata[]=$row;
							continue;
						} else if(preg_match("/^(9999999999)([0-9]{1})(X)$/",$row->productcode)) {
							#99999999999X : 현금결제시 결제금액에서 추가적립/할인
							#99999999998X : 에스크로 결제시 수수료
							#99999999997X : 부가세(VAT)
							#99999999990X : 상품배송비
							$etcdata[]=$row;
							continue;
						} else {
							#진짜상품
							$prdata[]=$row;
						}

						$sumprice=$row->price*$row->quantity;
						$totprice+=$sumprice;
						
						$msgOption = array();
						if(strlen($row->opt1_name)>0 || strlen($row->opt2_name)>0 || strlen($optvalue)>0 || strlen(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info))))>0) {
							if(strlen($row->opt1_name)>0 || strlen($row->opt2_name)>0 || strlen($optvalue)>0) {
								if(strlen($row->addcode)>0) $msgOption[] = "특징 : ".$row->addcode."&nbsp;&nbsp;";
								if(strlen($row->opt1_name)>0) $msgOption[] = " ".$row->opt1_name." ";
								if(strlen($row->opt2_name)>0) $msgOption[] = " ".$row->opt2_name." ";
								if(strlen($optvalue)>0) $msgOption[] = $optvalue;
								$row->addcode="";
							}
						} else if(strlen($row->addcode)>0) {
							if(strlen($row->addcode)>0) echo "특징 : ".$row->addcode;
						}
						$arrCriteo[$i][code] = $row->productcode;
						$arrCriteo[$i][name] = $row->productname;
						$arrCriteo[$i][price] = $row->price;
						$arrCriteo[$i][ea] = $row->quantity;
				?>
					<tr>
						<td class="info">
							<script language='javascript'>
								_A_amt[<?=$i?>]='<?=$row->price*$row->quantity?>';
								_A_nl[<?=$i?>]='<?=$row->quantity?>';
								_A_pl[<?=$i?>]='<?=$row->productcode?>';
								_A_pn[<?=$i?>]='<?=$row->productname?>';
								_A_ct[<?=$i?>]='';
							</script>
							<a href="productdetail.php?productcode=<?=$row->productcode?>" target="_self">
								<?if(strlen($row->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)){?>
									<?$file_size=getImageSize($Dir.DataDir."shopimages/product/".$row->tinyimage);?>
									<img src="<?=$Dir.DataDir?>shopimages/product/<?=$row->tinyimage?>" <?if($file_size[0]>=$file_size[1]){ echo " width='126'"; }else{ echo "height='126'"; }?>>
								<?}elseif(strlen($row->tinyimage)!=0 && file_exists($Dir.$row->tinyimage)){?>
									<?$file_size=getImageSize($Dir.$row->tinyimage);?>
									<img src="<?=$Dir.$row->tinyimage?>" <?if($file_size[0]>=$file_size[1]){ echo " width='126'"; }else{ echo "height='126'"; }?>>
								<?}else{?>
									<img src="<?=$Dir?>images/no_img.gif" width="126">
								<?}?>
								<span class="name"><?=viewselfcode($row->productname,$row->selfcode)?><br />
									<span class="option"><?=implode("<br>", $msgOption)?></span>
								</span>
							</a>
						</td>
						<td><strong><?=number_format($row->price)?></strong></td>
						<td><?=$row->quantity?>개</td>
						<td><strong><?=number_format($row->price*$row->quantity)?></strong></td>
					<!--	<td class="point"><img src="../img/icon/cart_point_icon.gif" alt="적립금" /><?=number_format($row->reserve*$row->quantity)?></td> -->
					</tr>
				<?
					}
				?>
				</tbody>
				<?	
					$couponNameArray = $couponDcpriceArray = $couponReserveArray = $couponMsgArray = array();
					$cashNameArray = $cashDcpriceArray = $cashReserveArray = array();
					$escrowNameArray = $escrowDcpriceArray = $escrowReserveArray = array();
					$deliveryNameArray = $deliveryDcpriceArray = $deliveryReserveArray = $deliveryMsgArray = array();
					$vatNameArray = $vatDcpriceArray = array();
					$etcprice=0;
					$etcreserve=0;
					for($i=0;$i<count($etcdata);$i++) {
						#쿠폰
						if(preg_match("/^(COU)([0-9]{8})(X)$/",$etcdata[$i]->productcode)) {
							$etcprice+=$etcdata[$i]->price;
							$etcreserve+=$etcdata[$i]->reserve;
							$couponNameArray[] = $etcdata[$i]->productname;
							$couponDcpriceArray[] = $etcdata[$i]->price;
							$couponReserveArray[] = $etcdata[$i]->reserve;
							$couponMsgArray[] = $etcdata[$i]->order_prmsg;
						} else if(preg_match("/^(9999999999)([0-9]{1})(X)$/",$etcdata[$i]->productcode)) {
							#99999999999X : 현금결제시 결제금액에서 추가적립/할인
							#99999999998X : 에스크로 결제시 수수료
							#99999999997X : 부가세(VAT)
							#99999999990X : 상품배송비
							if($etcdata[$i]->productcode=="99999999999X") {
								$etcprice+=$etcdata[$i]->price;
								$etcreserve+=$etcdata[$i]->reserve;
								$cashNameArray[] = $etcdata[$i]->productname;
								$cashDcpriceArray[] = $etcdata[$i]->price;
								$cashReserveArray[] = $etcdata[$i]->reserve;
							} else if($etcdata[$i]->productcode=="99999999998X") {
								$etcprice+=$etcdata[$i]->price;
								$etcreserve+=$etcdata[$i]->reserve;
								$escrowNameArray[] = $etcdata[$i]->productname;
								$escrowDcpriceArray[] = $etcdata[$i]->price;
								$escrowReserveArray[] = $etcdata[$i]->reserve;
							} else if($etcdata[$i]->productcode=="99999999990X") {
								$deliveryNameArray[] = $etcdata[$i]->productname;
								$deliveryDcpriceArray[] = $etcdata[$i]->price;
								$deliveryReserveArray[] = $etcdata[$i]->reserve;
								$deliveryMsgArray[] = $etcdata[$i]->order_prmsg;
							} else if($etcdata[$i]->productcode=="99999999997X") {
								$vatNameArray[] = $etcdata[$i]->productname;
								$vatDcpriceArray[] = $etcdata[$i]->price;
							}
						}
					}
					$dc_price=(int)$_ord->dc_price;
					$salemoney=0;
					$salereserve=0;
					$groupNameArray = $groupDcpriceArray = $groupReserveArray = $groupMsgArray = array();
					if($dc_price<>0) {
						if($dc_price>0) $salereserve=$dc_price;
						else $salemoney=-$dc_price;
						if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
							$sql = "SELECT b.group_name FROM tblmember a, tblmembergroup b ";
							$sql.= "WHERE a.id='".$_ord->id."' AND b.group_code=a.group_code AND SUBSTR(b.group_code,1,1)!='M' ";
							$result=pmysql_query($sql,get_db_conn());
							if($row=pmysql_fetch_object($result)) {
								$group_name=$row->group_name;
							}
							pmysql_free_result($result);
						}
						$groupNameArray[] = $group_name;
						$groupDcpriceArray[] = $salemoney;
						$groupReserveArray[] = $salereserve;
					}
				?>
				<tfoot>
					<tr>
						<td colspan="6">
							<div class="result_box">
								<span class="total">
									<span class="txt">총 판매가</span>
									<strong class="number"><?=number_format($totprice)?> 원</strong>
								</span>
								<img class="icon" src="../img/icon/cart_list_icon_minus.gif" alt="-" />
								<span class="total">
									<span class="txt">총 할인금액</span>
									<strong class="number"><?=number_format($etcprice-$_ord->reserve-$tot_price_dc-$salemoney)?> 원</strong>
								</span>
								<img class="icon" src="../img/icon/cart_list_icon_plus.gif" alt="+" />
								<span class="total">
									<span class="txt">총 배송비</span>
									<strong class="number"><?=number_format($_ord->deli_price)?> 원</strong>
								</span>
								<img class="icon" src="../img/icon/cart_list_icon_equals.gif" alt="=" />
								<span class="total_payment">
									<span class="txt">총 결제 금액</span>
									<strong class="number"><?=number_format($_ord->price)?><span>원</span></strong>
								</span>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
			<!-- // 주문 상품 -->

			<?if($_ord->deli_type == "2"){?>
			<div class="cart_order_wrap">
				<table class="info_table">
				<caption>해당 주문은 고객 [직접수령] 입니다</caption></td>
				</table>
			</div>
			<?}?>
			<div class="cart_order_wrap">
				<table class="info_table">
					<caption>배송지 정보</caption>
					<colgroup><col style="width:110px" /><col style="width:440px" /><col style="width:110px" /><col style="width:440px" /></colgroup>
					<tr>
						<th class="bg" scope="row">받는분</th>
						<td class="txt" colspan=3><?=$_ord->receiver_name?></td>
					</tr>
					<tr>
						<th class="bg" scope="row">전화번호</th>
						<td class="txt" ><?=$_ord->receiver_tel1?></td>
						<th class="bg" scope="row">전화번호</th>
						<td class="txt" ><?=$_ord->receiver_tel2?></td>
					</tr>
					<tr>
						<th class="bg" scope="row">주소</th>
						<td class="txt"  colspan=3><?=$_ord->receiver_addr?></td>
					</tr>
					<tr>
						<th class="bg" scope="row">요청사항</th>
						<td class="txt"  colspan=3>
							<?if($_ord->order_msg2){?>
								<?=$_ord->order_msg2?>
							<?}else{?>
								-
							<?}?>
						</td>
					</tr>
				</table>

				<?
					if(strstr("VCPM", $_ord->paymethod[0])) {
						$arpm=array("V"=>"실시간계좌이체","C"=>"신용카드","P"=>"매매보호 - 신용카드", "M"=>"핸드폰");
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
							$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>입금자 : ".$_ord->bank_sender."</div> <div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>계좌 : ".$_ord->pay_data."</div>";
						}else{
							if($_ord->pay_flag=="0000"){
								$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>(입금확인후 배송이 됩니다.)</div>";
							}
							if(strstr("O", $_ord->paymethod[0])){
								$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>가상계좌 : ".$_ord->pay_data."</div> ".$msg;
							}else if(strstr("Q", $_ord->paymethod[0])){
								$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>매매보호 - 가상계좌 : ".$_ord->pay_data."</div> ".$msg;
							}
						}
						$subject = "추가정보";
					}
				?>

				<table class="info_table">
					<caption>결제 정보</caption>
					<colgroup><col style="width:110px" /><col style="width:440px" /><col style="width:110px" /><col style="width:440px" /></colgroup>
					<tr>
						<th class="bg" scope="row">총 결제금액</th>
						<td class="txt dahong"><b><?=number_format($_ord->price)?>원</b></td>
						<th class="bg" scope="row">할인내역</th>
						<td class="txt">쿠폰 -<?=number_format(abs($etcprice-$tot_price_dc-$salemoney))?>원 / 적립금 -<?=number_format($_ord->reserve)?>원</td>
					</tr>
					<tr>
						<th class="bg" scope="row">결제방법</th>
						<td class="txt"><?=$arpm[$_ord->paymethod[0]]?></td>
						<th class="bg" scope="row"><?=$subject?></th>
						<td class="txt"><?=$msg?></td>
					</tr>
				</table>
			</div>

			<div class="ta_c mt_50 pb_50">
				<a href="javascript:;" class = 'CLS_GoToMain btn_D on'>메인으로 이동</a>
				<!--ordercode-->
				<a href="javascript:;" class = 'CLS_OrderView btn_D'>주문/배송 조회</a>
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
