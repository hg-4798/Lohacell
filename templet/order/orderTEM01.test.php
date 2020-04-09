

<form name=form1 action="<?=$Dir.FrontDir?>ordersend.php" method=post>
<input type=hidden name="addorder_msg" value="">
<!-- start container -->
<div id="container">
	<!-- start contents -->
	<div class="contents">
	
	<div class="title">
		<h2><img src="../image/cart/order_tit.gif" alt="주문서작성" /></h2>
		<div class="path">
			<ul>
				<li class="home">홈&nbsp;&gt;&nbsp;</li>
				<li>주문서작성</li>
			</ul>
		</div>
	</div>
	
	<div class="order_step">
		<ul>
			<li><img src="../image/cart/cart_nav1_01.gif" alt="step1" /></li>
			<li><img src="../image/cart/cart_nav1_02.gif" alt="step2" /></li>
			<li><img src="../image/cart/cart_nav1_03.gif" alt="step3" /></li>
			<li><img src="../image/cart/cart_nav1_04.gif" alt="step4" /></li>
		</ul>
	</div>

<h2 class="order_subtit"><img src="../image/cart/order_subtit_01.gif" alt="주문상세내역" /></h2>	

<div class="cart_listbar">
		<ul>
			<li class="cart_list2"><img src="../image/cart/table_tit_01.gif" alt="상품정보" /></li>
			<li class="cart_list3"><img src="../image/cart/table_tit_02.gif" alt="적립금" /></li>
			<li class="cart_list4"><img src="../image/cart/table_tit_03.gif" alt="판매가" /></li>
			<li class="cart_list5"><img src="../image/cart/table_tit_04.gif" alt="할인적용가" /></li>
			<li class="cart_list6"><img src="../image/cart/table_tit_05.gif" alt="수량" /></li>
			<li class="cart_list7"><img src="../image/cart/table_tit_06.gif" alt="합계" /></li>
		</ul>
</div>
<?php
	$sql = "SELECT b.vender FROM tblbasket a, tblproduct b WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
	$sql.= "AND a.productcode=b.productcode GROUP BY b.vender ";
	$res=pmysql_query($sql,get_db_conn());

	$cnt=0;
	$sumprice = 0;
	$deli_price = 0;
	$reserve = 0;
	$arr_prlist=array();
	while($vgrp=pmysql_fetch_object($res)) {
		$_vender=NULL;
		if($vgrp->vender>0) {
			$sql = "SELECT deli_price, deli_pricetype, deli_mini, deli_limit FROM tblvenderinfo WHERE vender='".$vgrp->vender."' ";
			$res2=pmysql_query($sql,get_db_conn());
			if($_vender=pmysql_fetch_object($res2)) {
				if($_vender->deli_price==-9) {
					$_vender->deli_price=0;
					$_vender->deli_after="Y";
				}
				if ($_vender->deli_mini==0) $_vender->deli_mini=1000000000;
			}
			pmysql_free_result($res2);
			
		}
		

		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.productcode,b.productname,b.sellprice,b.membergrpdc, b.option_reserve, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, ";
		$sql.= "b.etctype,b.deli_price,b.deli,b.sellprice*a.quantity as realprice, b.selfcode,a.assemble_list,a.assemble_idx,a.package_idx ";
		$sql.= "FROM tblbasket a, tblproduct b WHERE b.vender='".$vgrp->vender."' ";
		$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
		$sql.= "AND a.productcode=b.productcode ";
		//$sql.= "AND a.ord_state=true ";
		$sql.= "ORDER BY a.date DESC ";
		$result=pmysql_query($sql,get_db_conn());

		$mem_dc_price=0;  //회원등급에 의한 할인가 
		$vender_sumprice = 0;
		$vender_delisumprice = 0;//해당 입점업체의 기본배송비 총 구매액
		$vender_deliprice = 0;
		$deli_productprice=0;
		$deli_init = false;

		while($row = pmysql_fetch_object($result)) {

			if (strlen($row->option_price)>0 && $row->opt1_idx==0) {
				$sql = "DELETE FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
				$sql.= "AND productcode='".$row->productcode."' AND opt1_idx='".$row->opt1_idx."' ";
				$sql.= "AND opt2_idx='".$row->opt2_idx."' AND optidxs='".$row->optidxs."' ";
				pmysql_query($sql,get_db_conn());
				alert_go("필수 선택 옵션 항목이 있습니다.\\n옵션을 선택하신후 장바구니에\\n담으시기 바랍니다.",$Dir.FrontDir."productdetail.php?productcode=".$row->productcode);
			}
			if(preg_match("/^(\[OPTG)([0-9]{4})(\])$/",$row->option1)){
				$optioncode = substr($row->option1,5,4);
				$row->option1="";
				$row->option_price="";
				if(!empty($row->optidxs)) {
					$tempoptcode = rtrim($row->optidxs,',');
					$exoptcode = explode(",",$tempoptcode);

					$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='".$optioncode."' ";
					$resultopt = pmysql_query($sqlopt,get_db_conn());
					if($rowopt = pmysql_fetch_object($resultopt)){
						$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
						$opti=0;
						$optvalue="";
						$option_choice = $rowopt->option_choice;
						$exoption_choice = explode("",$option_choice);
						while(strlen($optionadd[$opti])>0){
							if($exoption_choice[$opti]==1 && $exoptcode[$opti]==0){
								$delsql = "DELETE FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
								$delsql.= "AND productcode='".$row->productcode."' ";
								$delsql.= "AND opt1_idx='".$row->opt1_idx."' AND opt2_idx='".$row->opt2_idx."' ";
								$delsql.= "AND optidxs='".$row->optidxs."' ";
								pmysql_query($delsql,get_db_conn());
								alert_go("필수 선택 옵션 항목이 있습니다.\\n옵션을 선택하신후 장바구니에\\n담으시기 바랍니다.",$Dir.FrontDir."productdetail.php?productcode=".$row->productcode);
							}
							if($exoptcode[$opti]>0){
								$opval = explode("",str_replace('"','',$optionadd[$opti]));
								$optvalue.= ", ".$opval[0]." : ";
								$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
								if ($exop[1]>0) $optvalue.=$exop[0]."(<font color=#FF3C00>+".number_format($exop[1])."원</font>)";
								else if($exop[1]==0) $optvalue.=$exop[0];
								else $optvalue.=$exop[0]."(<font color=#FF3C00>".number_format($exop[1])."원</font>)";
								$row->realprice+=($row->quantity*$exop[1]);
							}
							$opti++;
						}
						$optvalue = ltrim($optvalue,',');
					}
				}
			} else {
				$optvalue="";
			}

			$cnt++;

			$assemble_str="";
			$package_str="";
			if($row->assemble_idx>0 && strlen(str_replace("","",$row->assemble_list))>0) {
				$assemble_list_proexp = explode("",$row->assemble_list);
				$alprosql = "SELECT productcode,productname,sellprice FROM tblproduct ";
				$alprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_proexp)."') ";
				$alprosql.= "AND display = 'Y' ";
				$alprosql.= "ORDER BY FIELD(productcode,'".implode("','",$assemble_list_proexp)."') ";
				$alproresult=pmysql_query($alprosql,get_db_conn());
				
			//	$assemble_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
				$assemble_str.="		<td width=\"100%\">\n";
				$assemble_str.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
				
				$assemble_sellerprice=0;
				while($alprorow=@pmysql_fetch_object($alproresult)) {
					$assemble_str.="		<tr>\n";
					$assemble_str.="			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;\">\n";
					$assemble_str.="			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
					$assemble_str.="			<col width=\"\"></col>\n";
					$assemble_str.="			<col width=\"80\"></col>\n";
					$assemble_str.="			<col width=\"120\"></col>\n";
					$assemble_str.="			<tr>\n";
					$assemble_str.="				<td style=\"padding:4px;word-break:break-all;\"><font color=\"#000000\">".$alprorow->productname."</font>&nbsp;</td>\n";
					$assemble_str.="				<td align=\"right\" style=\"padding:4px;border-left:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\"><font color=\"#000000\">".number_format((int)$alprorow->sellprice)."원</font></td>\n";
					$assemble_str.="				<td align=\"center\" style=\"padding:4px;\">본 상품 1개당 수량1개</td>\n";
					$assemble_str.="			</tr>\n";
					$assemble_str.="			</table>\n";
					$assemble_str.="			</td>\n";
					$assemble_str.="		</tr>\n";
					$assemble_sellerprice+=$alprorow->sellprice;
				}
				@pmysql_free_result($alproresult);
				$assemble_str.="		</table>\n";
				$assemble_str.="		</td>\n";

				//######### 코디/조립에 따른 가격 변동 체크 ###############
				$price = $assemble_sellerprice*$row->quantity;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$assemble_sellerprice,"N");
				$sellprice=$assemble_sellerprice;
			} else if($row->package_idx>0 && strlen($row->package_idx)>0) {
				$package_str ="<a href=\"javascript:setPackageShow('packageidx".$cnt."');\">".$title_package_listtmp[$row->productcode][$row->package_idx]."(<font color=#FF3C00>+".number_format($price_package_listtmp[$row->productcode][$row->package_idx])."원</font>)</a>";
				
				$productname_package_list_exp = $productname_package_list[$row->productcode][$row->package_idx];
				if(count($productname_package_list_exp)>0) {
					//$packagelist_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
					$packagelist_str.="		<td width=\"100%\">\n";
					$packagelist_str.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
					
					for($i=0; $i<count($productname_package_list_exp); $i++) {
						$packagelist_str.="		<tr>\n";
						$packagelist_str.="			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;\">\n";
						$packagelist_str.="			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
						$packagelist_str.="			<col width=\"\"></col>\n";
						$packagelist_str.="			<col width=\"120\"></col>\n";
						$packagelist_str.="			<tr>\n";
						$packagelist_str.="				<td style=\"padding:4px;word-break:break-all;\"><font color=\"#000000\">".$productname_package_list_exp[$i]."</font>&nbsp;</td>\n";
						$packagelist_str.="				<td align=\"center\" style=\"padding:4px;border-left:1px #DDDDDD solid;\">본 상품 1개당 수량1개</td>\n";
						$packagelist_str.="			</tr>\n";
						$packagelist_str.="			</table>\n";
						$packagelist_str.="			</td>\n";
						$packagelist_str.="		</tr>\n";
					}
					$packagelist_str.="		</table>\n";
					$packagelist_str.="		</td>\n";
				} else {
					//$packagelist_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
					$packagelist_str.="		<td width=\"100%\">\n";
					$packagelist_str.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
					$packagelist_str.="		<tr>\n";
					$packagelist_str.="			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;padding:4px;word-break:break-all;\"><font color=\"#000000\">구성상품이 존재하지 않는 패키지</font></td>\n";
					$packagelist_str.="		</tr>\n";
					$packagelist_str.="		</table>\n";
					$packagelist_str.="		</td>\n";
				}
				//######### 옵션에 따른 가격 변동 체크 ###############
				if (strlen($row->option_price)==0) {
					$sellprice=$row->sellprice+$price_package_listtmp[$row->productcode][$row->package_idx];
					$price = $sellprice*$row->quantity;
					$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$sellprice,"N");
				} else if (strlen($row->opt1_idx)>0) {
					$option_price = $row->option_price;
					$pricetok=explode(",",$option_price);
					$priceindex = count($pricetok);
					$sellprice=$pricetok[$row->opt1_idx-1]+$price_package_listtmp[$row->productcode][$row->package_idx];
					$price = $sellprice*$row->quantity;
					$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$sellprice,"N");
				}
			} else {
				//######### 옵션에 따른 가격 변동 체크 ###############
				if (strlen($row->option_price)==0) {
					$price = $row->realprice;
					$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
					$sellprice=$row->sellprice;
				} else if (strlen($row->opt1_idx)>0) {
					$option_price = $row->option_price;
					$pricetok=explode(",",$option_price);
					$priceindex = count($pricetok);
					$price = $pricetok[$row->opt1_idx-1]*$row->quantity;
					$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
					$sellprice=$pricetok[$row->opt1_idx-1];
				}
			}

			//######### 상품 특별할인률 적용 ############
			$dc_data = $product->getProductDcRate($row->productcode);
			$salemoney = getProductDcPrice($sellprice,$dc_data[price]);
			$salereserve = getProductDcPrice($sellprice,$dc_data[reserve]);
			/*
			$grpdc_ex=explode(";",$row->membergrpdc);
			
			foreach($grpdc_ex as $v){
				$grpdc_data=explode("-",$v);
				$grpdc_arr[$grpdc_data[0]]=$grpdc_data[1];
			}
			
			$dc_per=0;
			$dc_per=$grpdc_arr['lv'.$group_level];
			if($sellprice>0){
				if(strlen($group_type)>0 && $group_type!=NULL ) {
					$salemoney=0;
					$salereserve=0;
					if($dc_per>0){
						$salemoney=round($sellprice*$dc_per/100,-1,PHP_ROUND_HALF_DOWN);
					}else{
						if($group_type=="SW" || $group_type=="SP") {
							if($group_type=="SW") {
								$salemoney=$group_addmoney;
							} else if($group_type=="SP") {
								$salemoney=round($sellprice*$group_addmoney/100,-1,PHP_ROUND_HALF_DOWN);
								
							}
						}
					}
					if($group_type=="RW" || $group_type=="RP" || $group_type=="RQ") {
						if($group_type=="RW") {
							$salereserve=$group_addmoney;
						} else if($group_type=="RP") {
							//$salereserve=$reserve*($group_addmoney-1);

							$salereserve=round($sellprice*$group_addmoney/100,-1,PHP_ROUND_HALF_DOWN);

						} else if($group_type=="RQ") {
							$salereserve=round($sellprice*$group_addmoney/100,-1,PHP_ROUND_HALF_DOWN);
						}
					}
				}
			}
			*/

			//######### 옵션별 적립금 적용 ############
			$option_reserve = explode(',',$row->option_reserve);
			
			if($option_reserve[$row->opt1_idx-1]>0){
				$tempreserve=$option_reserve[$row->opt1_idx-1];
			}
			
			//추가 적립금 적용
			$tempreserve+=$salereserve;

			//비회원이면 적립금 노출 X
			if(strlen($_ShopInfo->getMemgroup())==0) $tempreserve=0;

			//회원 할인율 적용
			$before_sellprice=$sellprice;
			$bf_price = $before_sellprice*$row->quantity;

			$sellprice=$sellprice-$salemoney;

			$price = $sellprice*$row->quantity;

			$bf_sumprice += $bf_price;
			$sumprice += $price;
			$vender_sumprice += $price;

			$mem_dc_price += $salemoney*$row->quantity;

			$deli_str = "";
			if (($row->deli=="Y" || $row->deli=="N") && $row->deli_price>0) {
				if($row->deli=="Y") {
					$deli_productprice += $row->deli_price*$row->quantity;
					$deli_str = "&nbsp;<font color=a00000>- 개별배송비<font color=#FF3C00>(구매수 대비 증가:".number_format($row->deli_price*$row->quantity)."원)</font></font>";
				} else {
					$deli_productprice += $row->deli_price;
					$deli_str = "&nbsp;<font color=a00000>- 개별배송비<font color=#FF3C00>(".number_format($row->deli_price)."원)</font></font>";
				}
			} else if($row->deli=="F" || $row->deli=="G") {
				$deli_productprice += 0;
				if($row->deli=="F") {
					$deli_str = "&nbsp;<font color=a00000>- 개별배송비<font color=#0000FF>(무료)</font></font>";
				} else {
					$deli_str = "&nbsp;<font color=a00000>- 개별배송비<font color=#38A422>(착불)</font></font>";
				}
			} else {
				$deli_init=true;
				$vender_delisumprice += $price;
			}

			$productname=$row->productname;

			$arr_prlist[$row->productcode]=$row->productname;

			$reserve += $tempreserve*$row->quantity;


			$bankonly_html = ""; $setquota_html = "";
			if (strlen($row->etctype)>0) {
				$etctemp = explode("",$row->etctype);
				for ($i=0;$i<count($etctemp);$i++) {
					switch ($etctemp[$i]) {
						case "BANKONLY": $bankonly = "Y";
							$bankonly_html = " <img src=".$Dir."images/common/bankonly.gif border=0 align=absmiddle> ";
							break;
						case "SETQUOTA":
							if ($_data->card_splittype=="O" && $price>=$_data->card_splitprice) {
								$setquotacnt++;
								$setquota_html = " <img src=".$Dir."images/common/setquota.gif border=0 align=absmiddle>";
								$setquota_html.= "</b><font color=black size=1>(";
								//$setquota_html.="3~";
								$setquota_html.= $_data->card_splitmonth.")</font>";
							}
							break;
					}
				}
			}
?>
<div class="cart_list">
		<ul>
			<li class="cart_list2">
			<span><a href="#">
			<?
			if(strlen($row->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)){
					$file_size=getImageSize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					echo "<img src=\"".$Dir.DataDir."shopimages/product/".$row->tinyimage."\"";
					if($file_size[0]>=$file_size[1]) echo " width=\"50\"";
					else echo " height=\"50\"";
					echo " border=\"0\" vspace=\"1\">";
				} else {
					echo "<img src=\"".$Dir."images/no_img.gif\" width=\"50\" border=\"0\" vspace=\"1\">";
				}
			?>
			</a></span>
			<span class="cart_item_name">
			<?=viewproductname($productname,$row->etctype,$row->selfcode,$row->addcode) ?><?=$bankonly_html?><?=$setquota_html?><?=$deli_str?><br />
			<?if (strlen($row->option1)>0 || strlen($row->option2)>0 || strlen($optvalue)>0) {?>
			<img src="<?=$Dir?>images/common/icn_option.gif" border="0" align="absmiddle" style="width:28px;height:16px">
			<?
					if (strlen($row->option1)>0 && $row->opt1_idx>0) {
						$temp = $row->option1;
						$tok = explode(",",$temp);
						$count=count($tok);
						echo $tok[0]." : ".$tok[$row->opt1_idx]."\n";
					} 
					if (strlen($row->option2)>0 && $row->opt2_idx>0) {
						$temp = $row->option2;
						$tok = explode(",",$temp);
						$count=count($tok);
						echo ",&nbsp; ".$tok[0]." : ".$tok[$row->opt2_idx]."\n";
					}
					if(strlen($optvalue)>0) {
						echo $optvalue."\n";
					} 
			}

			if (strlen($package_str)>0) { // 패키지 정보
			?>
				<img src="<?=$Dir?>images/common/icn_package.gif" border="0" align="absmiddle" style="width:28px;height:16px"> <?=(strlen($package_str)>0?$package_str:"")?>
			<?
			}
			if (strlen($assemble_str)>0) { // 코디/조립 정보
				echo "<table><tr>".$assemble_str."</tr></table>";
			}
			if (strlen($packagelist_str)>0) { // 패키지 정보
				//echo "<table><tr>".$packagelist_str."</tr></table>";
			}
			?>
			</span>

			</li>
			<li class="cart_list3"><p><?=number_format($tempreserve) ?>원</p></li>
			<li class="cart_list4"><p><?=number_format($before_sellprice)?>원</p></li>
			<li class="cart_list5"><p><?=number_format($sellprice)?>원</p></li>
			<li class="cart_list6"><p><?=$row->quantity?>개</p></li>
			<li class="cart_list7"><p><?=number_format($price) ?>원</p></li>
		</ul>
</div>

			
<?
		}

		pmysql_free_result($result);

		$vender_deliprice=$deli_productprice;

		if($_vender) {
			if($_vender->deli_price>0) {
				if($_vender->deli_pricetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if ($vender_delisumprice<$_vender->deli_mini && $deli_init) {
					$vender_deliprice+=$_vender->deli_price;
				}
			} else if(strlen($_vender->deli_limit)>0) {
				if($_vender->deli_pricetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}
				if($deli_init) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_vender->deli_limit);
					$vender_deliprice+=$delilmitprice;
				}
			}
		} else {
			if($_data->deli_basefee>0) {
				if($_data->deli_basefeetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if ($vender_delisumprice<$_data->deli_miniprice && $deli_init) {
					$vender_deliprice+=$_data->deli_basefee;
				}
			} else if(strlen($_data->deli_limit)>0) {
				if($_data->deli_basefeetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if($deli_init) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_data->deli_limit);
					$vender_deliprice+=$delilmitprice;
				}
			}
		}

		$deli_price+=$vender_deliprice;
?>

<?
	}
	//그룹배송비 무료처리
	if($group_deli_free=='1'){
		$deli_price=0;
	}

	pmysql_free_result($res);

	if ($cnt!=$setquotacnt && $setquotacnt>0 && $_data->card_splittype=="O") {
		echo "<script> alert('[안내] 무이자적용상품과 일반상품을 같이 주문시 무이자할부적용이 안됩니다.');</script>";
	}
	
?>


<div class="order_calculate">
<ul>
	<li>[ 상품합계금액 <span class="cart_price"><?=number_format($bf_sumprice)?></span>원&nbsp;/&nbsp;</li>
	<li>받으실적립금 <span class="cart_price"><?=number_format($reserve)?></span>원 ]</li>
	<li>총 결제금액 <span class="total_pay"><?=number_format($sumprice)?></span>원</li>
</ul>
</div>

<?  
	if(strlen($_ShopInfo->getMemid())>0) {
		

	//사은품 사용조건
	$gift_use_chk=0;
	$gift_type =explode('|',$_data->gift_type);
	
	if($gift_type[0]=='M' && strlen($_ShopInfo->getMemid())>0){
		$gift_use_chk=1;
	}else if($gift_type[0]=='C'){
		$gift_use_chk=1;
	}
	
	
  
	if($gift_use_chk==1){

		$imgpath_gift=$cfg_img_path['gift'];

		//사은품 쿼리
		$gift_sql = "SELECT * FROM tblgiftinfo WHERE gift_startprice<='".$sumprice."' AND gift_endprice>'".$sumprice."' ";
		$gift_sql.= "AND (gift_quantity is NULL OR gift_quantity>0) ORDER BY gift_regdate ";
		$gift_res=pmysql_query($gift_sql,get_db_conn());
		$gift_cnt=pmysql_num_rows($gift_res);
		$i=0;


		if($gift_cnt>0){
?>
<div class="order_table_wrap">
	<table class="layout" width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
		<th align=right class="t_img"><img src="../image/cart/order_step_tit01.gif" alt="사은품"/></th>
		<td>
		
			<table class="form_use" width= cellpadding=0 cellspacing=0 border=0>
				<caption>사은품</caption>
				<col width=100>
				

<!--  사은품선택 -->

		<tr>
			<td colspan=6>

			<h1 class="cart_title"></h1>
			<div class="bd_box_wrap">
				<div class="bd_box">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<colgroup>
							<col width="50%" /><col width="50%" />
						</colgroup>
						<tr>
						<?
							while($gift_row=pmysql_fetch_array($gift_res)){

								if (ord($gift_row['gift_image']) && file_exists($imgpath_gift.$gift_row['gift_image'])) {
									$gift_image_src=$imgpath_gift.$gift_row['gift_image'];
								} else {
									$gift_image_src="../images/no_img.gif";
								}
						?>
							<td style="padding:5px 0px;">
							
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<colgroup>
									<col width="80" /><col width="" />
								</colgroup>
								<tr>
									<td><img src="<?=$gift_image_src?>" style="width:68px;height:68px;border:0px" alt="" /></td>
									<td align=left>
										<input id="dev_gift<?=$i?>" type="radio" name="gift_sel" value="<?=$gift_row['gift_regdate']?>"  <?if($i==0){ echo "checked"; }?>/>
										<label  for="dev_gift<?=$i?>"><?=mb_strimwidth($gift_row['gift_name'], '0', '35', '..', 'euc-kr')?></label>
									</td>
								</tr>
							</table>
							
							</td>	
						<?
								$i++;
								if($i%2==0){
									
									echo "</tr><tr>";
								}
								
							}
							
						?>
						</tr>
					</table>
					</div>
			</div>
					

				
			
			</td>
		</tr>
		<tr><td style="height:10px;"></td></tr>


			</table>

		</td>
	</tr>
	</table>
</div>
<?
		}
	}
}
	//사은품 종료
	
	//구매금액대별 추가할인
	$tot_price_dc=0;
	$tot_dc_per=getTotalPriceDc($bf_sumprice);
	if($tot_dc_per)$tot_price_dc=round($sumprice*$tot_dc_per/100,-1,PHP_ROUND_HALF_DOWN);
		

	//상품구매가격(할인율 적용)에서 금액할인가 차감
	$sumprice = $sumprice - $tot_price_dc;
?>




<!-- 주문서작성 -->
<h2 class="order_subtit"><img src="../image/cart/order_subtit_02.gif" alt="주문서 작성" /></h2>	

<div class="order_table_wrap">
	<table class="layout" width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
		<th align=right class="t_img"><img src="../image/cart/order_step_tit01.gif" alt="주문자정보"/></th>
		<td>
		
			<table class="form_use" width= cellpadding=0 cellspacing=0 border=0>
				<caption>주문자정보</caption>
				<col width=100>
				<tr>
					<th>주문하시는분</th>
					<td>
					<?
						if(strlen($_ShopInfo->getMemid())>0) {
					?>
						<input type=text  name="sender_name" value="<?=$name?>" readonly style="font-weight:bold" style='border:0' required msgR="주문하시는분의 이름을 적어주세요">
					<?
						} else {
					?>
						<input type=text  name="sender_name" value="" style="font-weight:bold" style='border:0' required msgR="주문하시는분의 이름을 적어주세요">
					<?
						}
					?>	
					</td>
				</tr>
				<tr>
					<th>핸드폰번호</th>
					<td>
					<input type=text name="sender_tel1" value="<?=$mobile[0] ?>" size=3 maxlength=3 required onKeyUp="strnumkeyup(this)"> -
					<input type=text name="sender_tel2" value="<?=$mobile[1] ?>" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)"> -
					<input type=text name="sender_tel3" value="<?=$mobile[2] ?>" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)">
					</td>
				</tr>
				<tr>
					<th>이메일</th>
					<td><input type=text name="sender_email" value="<?=$email?>" required size=30></td>
				</tr>
			</table>

		</td>
	</tr>
	</table>
</div>

<div class="order_table_wrap">
	<table class="layout" width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
		<th align=right class="t_img"><img src="../image/cart/order_step_tit02.gif" alt="배송정보" /></th>
		<td>
		
			<table class="form_use" width= cellpadding=0 cellspacing=0 border=0>
				<caption>배송정보</caption>
				<col width=100>
				<tr>
					<th>배송지 확인</th>
					<td>
					<input type=checkbox name="same" value="Y" onclick="SameCheck(this.checked)"> 주문고객 정보와 동일합니다
					</td>
				</tr>
				<tr>
					<th>받으실분</th>
					<td><input type=text name="receiver_name" value="" required></td>
				</tr>
				<tr>
					<th>받으실곳</th>
					<td>
					<input type=text size=3 name="rpost1" readonly value="" required> -
					<input type=text size=3 name="rpost2" readonly value="" required>
					<div class="search_zipCode">
					<a href="javascript:get_post();"><img src="../image/cart/btnmini_postnb.gif"></a>
					<?if(strlen($_ShopInfo->getMemid())>0){?>
					<img src="../image/cart/btnmini_post.gif">
<!--					<input type="button" name="addrtype" value="과거 배송지" onclick="addrchoice()" style="border:none;">-->
					<?}?>
					</div>
					</td>
				</tr>
				<tr>
					<th></th>
					<td><input type=text name="raddr1" readonly value="" required size=70></td>
				</tr>
				<tr>
					<th></th>
					<td><input type=text name="raddr2" value="" required size=70></td>
				</tr>
				<tr>
					<th>전화번호</th>
					<td>
					<input type=text name="receiver_tel11" value="" size=3 maxlength=3 required onKeyUp="strnumkeyup(this)"> -
					<input type=text name="receiver_tel12" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)"> -
					<input type=text name="receiver_tel13" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)">
					</td>
				</tr>
				<tr>
					<th>핸드폰번호</th>
					<td>
					<input type=text name="receiver_tel21" value="" size=3 maxlength=3 required onKeyUp="strnumkeyup(this)"> -
					<input type=text name="receiver_tel22" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)"> -
					<input type=text name="receiver_tel23" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)">
					</td>
				</tr>
				<tr>
					<th>남기실 말씀</th>
					<td>

					<? if(count($arr_prlist)>1){ ?>
					
						<input type=hidden name=msg_type value="1">
						<textarea name="order_prmsg" style="WIDTH:100%;HEIGHT:40px;padding:5px;line-height:17px;border:solid 1;border-color:#DFDFDF;font-size:9pt;color:333333;"></textarea>
					<?}else{?>
						<input type="hidden" name="msg_type" value="2">
						<?
						$yy=0;
						while(list($key,$val)=each($arr_prlist)){
							
							echo $val."<br>";
							
						?>
						<textarea name="order_prmsg<?=$yy?>" style="WIDTH:100%;HEIGHT:20px;padding:5px;line-height:17px;border:solid 1;border-color:#DFDFDF;font-size:9pt;color:333333;"></textarea><br>
						<?
							$yy++;
						}
						?>
					<?}?>
					</td>
				</tr>

				<tr><td colspan=2>&nbsp;</td></tr>
				<?
					$tempmess="";
					if(strlen($etcmessage[1])>0){
						$day1=substr($etcmessage[1],0,2);
						$time1=substr($etcmessage[1],2,2);
						$time2=substr($etcmessage[1],4,2);
						$delidate=date("Ymd",strtotime("+{$day1} day"));
						$deliyear=substr($delidate,0,4);
						$delimon=substr($delidate,4,2);
						$deliday=substr($delidate,6,2);
				?>
				<tr>
					<th>희망 배송일자</th>
					<td>
						<input type=checkbox name="nowdelivery" value="Y" style="border:none;"> 가능한 빨리 배송요망 &nbsp;&nbsp;
						<select name="year" style="font-size:11px;">
						<?
						for($i=$deliyear;$i<=($deliyear+1);$i++) {
							$sel='';
							if($i==$deliyear) $sel.=" selected";
						?>
							<option value="<?=$i?>" style="#444444;" <?=$sel?>><?=$i?></option>							
						<?
						}
						?>
						</select>년
						<select name="mon" style="font-size:11px;">
						<?
						for($i=1;$i<=12;$i++) {
							$sel='';
							if($i==$delimon) $sel.=" selected";
						?>
							<option value="<?=$i?>" style="#444444;" <?=$sel?>><?=$i?></option>							
						<?
						}
						?>
						</select>월
						<select name="day" style="font-size:11px;">
						<?
						for($i=1;$i<=31;$i++) {
							$sel='';
							if($i==$deliday) $sel.=" selected";
						?>
							<option value="<?=$i?>" style="#444444;" <?=$sel?>><?=$i?></option>							
						<?
						}
						?>
						</select>일
					</td>
				</tr>	
				<?
					}
				?>
				<tr>
					<th>배송선택</th>
					<td class="select_deliv" ><input type="radio" name="deli_type" class="deli_type" value="0" checked> 기본배송
					<input type="radio" name="deli_type" class="deli_type" value="1">추가주문/묶음배송<div class="pack"><img src="../image/cart/pack_txt.gif"></div></td>
					
				</tr>
			</table>

		</td>
	</tr>
	</table>
</div>

<div class="order_table_wrap">
	<table class="layout" width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
		<th align=right class="t_img"><img src="../image/cart/order_step_tit03.gif" alt="결제금액" /></th>
		<td>
		
			<table class="form_use" width= cellpadding=0 cellspacing=0 border=0>
				<?
					$p_price=$sumprice+$deli_price+$sumpricevat;
				?>

				<input type="hidden" name="total_sum" value="<?=$p_price?>">
				<caption>결제금액</caption>
				<col width=100>
				<tr>
					<th>상품합계금액</th>
						<td><p id="paper_goodsprice" style="width:146px;text-align:right;font-weight:bold;float:left;margin:0"><?=number_format($bf_sumprice)?></p> 원</td>
					</tr>
					<tr>
						<th>배송비</th>
						<td>
						<div class="order_price_style02"><span id=delivery_price><?=number_format($deli_price)?></span>원</div>
						</td>
					</tr>
					<tr>
						<th>회원할인</th>
						<td id='memberdc'>
						<p class="order_price_style01" id="memberdc_price"><?=number_format($mem_dc_price)?></p> 원
						</td>
					</tr>
					<tr>
						<th>금액할인</th>
						<td id='pricedc'>
						<p class="order_price_style01" ><?=number_format($tot_price_dc)?></p> 원
						</td>
					</tr>


<?
		if ((strlen($_ShopInfo->getMemid())>0 && $_data->reserve_maxuse>=0 && $user_reserve!=0) || (strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y")) {

			if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y") {
?>
					
					<tr>
						<th>쿠폰 적용</th>
						<td>

						<table  cellpadding=0 cellspacing=0 border=0>
						<caption>쿠폰적용내역</caption>
						<tr>
							<input type=hidden name="coupon_code" size="19" readonly style="BACKGROUND-COLOR:#F7F7F7;" class="input">
							<input type=hidden name="bank_only" value="N">
							<th width=60 align=right>할인 :</th>
							<td class="appli_benefit">
							<input type=text name=coupon_dc id="coupon_dc" size=12 style="text-align:right" value=0 readonly> 원 
							<div class="search_coupon_order" style="width:210px;float:right"><a href="javascript:coupon_check();"><img src="../image/cart/btnmini_coopon.gif" align=absmiddle hspace=2></a><a href="javascript:coupon_cancel();"><img src="../image/cart/btnmini_cooponc.gif" align=absmiddle hspace=2>
							<!--<input type="button" value="쿠폰취소">--></a></div>
							</td>
						</tr>
						<tr>
							<th width=60 align=right>적립 :</th>
							<td class="appli_benefit">
							<input type=text name=coupon_reserve size=12 style="text-align:right" value=0 readonly> 원
							</td>
						</tr>
						</table>

						</td>
					</tr>
<? 
			}
			if (strlen($_ShopInfo->getMemid())>0 && $_data->reserve_maxuse>=0 && $user_reserve!=0) { 
?>
					<tr>
						<th valign=top class="appli_benefit">에코머니 적용</th>
						<td>
						<?
						if($okreserve<0){
							$okreserve=(int)($bf_sumprice*abs($okreserve)/100);
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
						<table cellpadding=0 cellspacing=0 border=0>
						<caption>에코머니 적용내역</caption>
						<div>
						<tr>
							<th style="width:79px;">에코머니 :</th>
							<td class="appli_benefit">	
							<?
							if($reserve_chk=='1' || ($okreserve+$remainreserve)>=3000){
								if($reserve_maxprice>$sumprice) {
							?>
									<font color=red>구매금액이 <?=number_format($reserve_maxprice)?>원 이상이면 사용가능합니다.</font>
									<input type="hidden " name="usereserve" value=0>
							<?  }else if($user_reserve>=$_data->reserve_maxuse){?>

									<input type=text  name="usereserve" id="usereserve" size=12 style="text-align:right" value=0 onKeyUp="reserve_check('<?=$okreserve?>');"> 원
									<B>←</B><input type=text name="okreserve" value="<?=$okreserve?>" size="10" onfocus="blur();" style="text-align:right;BACKGROUND-COLOR:#F7F7F7;" >원 사용가능(총 적립금 <?=number_format($remainreserve+$okreserve)?>원)
									<?if($user_reserve>$reserve_limit){?>
										<input type=hidden name="remainreserve" value="<?=$remainreserve?>" >	
									<?}?>
							<?	}else{?>
									<input type="hidden"  name="usereserve" id="usereserve" value=0>
									<font color=red><?=number_format($_data->reserve_maxuse)?>원 이상이면 사용가능합니다.(총 적립금 <?=number_format($remainreserve+$okreserve)?>원)</font>
							<?
								}
							}else{
							?>
								첫 에코머니 사용은 3,000원 이상부터 가능합니다.
								<input type="hidden"  name="usereserve" value=0>
							<?
							}
							?>
							</td>
						</tr>
						</div>
						</table>

						</td>
					</tr>
			<?}else{?>
					<input type=hidden name="usereserve" id="usereserve" value=0>
			<?}?>
<?}?>

		

		
					<tr>
						<th><a href="javascript:checkwinsize()">총 결제금액</a></th>
						<td><span id="price_sum"><?=number_format($sumprice+$deli_price+$sumpricevat)?></span>원</td>
					</tr>
			</table>
			<script>
			function checkwinsize(){
				$("#PAYWAIT_LAYER").show();

				var t = $(document).scrollTop();
				var w = ($(window).width()-$("#PAYWAIT_LAYER").width())/2;
				var h = ($(window).height()-$("#PAYWAIT_LAYER").height())/2;


//				$("#PAYWAIT_LAYER").css("position","absolute");
				$("#PAYWAIT_LAYER").css("z-index","1000");
				$("#PAYWAIT_LAYER").css("visibility","display");
				$("#PAYWAIT_LAYER").css("left",w);
				$("#PAYWAIT_LAYER").css("top",t+h);

				alert($("#PAYWAIT_LAYER").css("left"));
				alert($("#PAYWAIT_LAYER").css("top"));

//				$("#PAYWAIT_LAYER").css();
//				$("#PAYWAIT_LAYER").left(t+h);

			}
			</script>
		</td>
	</tr>
	</table>
</div>

<div class="order_table_wrap">
	<table class="layout" width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
		<td align=center><img src="../image/cart/insurance_txt.gif" alt="소비자피해보상 보험서비스 안내" style="padding-top:30px;padding-bottom:30px"/></td>
	</tr>
	</table>
</div>

<div class="order_table_wrap">
	<table class="layout" width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
		<th align=right class="t_img"><img src="../image/cart/order_step_tit04.gif" alt="결제수단" /></th>
		<td>
		
			<table class="form_use" width= cellpadding=0 cellspacing=0 border=0>
				<caption>결제수단</caption>
				<col width=100>
				<tr>
					<td style="width:100%">

					<table  summary="">
					<caption>결제수단 선택</caption>
						<col width=100>
							<tr>
								<td style="width:100px">일반결제</td>
								<td class="pay_type">
								<?
									//무통장
									if($escrow_info["onlycard"]!="Y" ) {
										if(strstr("YN", $_data->payment_type)) {//결제방법이 모든결제 OR 온라인결제가 선택되었을 경우
								?>
										<input id="dev_payment1" class="dev_payment" name="dev_payment" type="radio" value="B" onclick="sel_paymethod(this);" /><label for="dev_payment1">무통장입금</label>
								<?
										}
									}
									//신용카드
									if(strstr("YC", $_data->payment_type) && ord($_data->card_id)) {
								?>
										<input id="dev_payment2" class="dev_payment" name="dev_payment" type="radio" value="C" onclick="sel_paymethod(this);" /><label for="dev_payment2">신용카드</label>
								<?
									}
									
									//실시간계좌이체
									if($escrow_info["onlycard"]!="Y"&&!strstr($_SERVER["HTTP_USER_AGENT"],'Mobile')&&!strstr($_SERVER[HTTP_USER_AGENT],"Android")){
										if(ord($_data->trans_id)) {

								?>
										<input id="dev_payment3" class="dev_payment" name="dev_payment" type="radio" value="V" onclick="sel_paymethod(this);" /><label for="dev_payment3">계좌이체</label>
								<?
										}
									}
									//가상계좌
									if($escrow_info["onlycard"]!="Y" ) {
										if(ord($_data->virtual_id)) {
								?>
										<input id="dev_payment5" class="dev_payment" name="dev_payment" type="radio" value="O" onclick="sel_paymethod(this);" /><label for="dev_payment5">가상계좌</label>
								<?
										}
									}
									
									//에스크로
									if(($escrow_info["escrowcash"]=="A" || ($escrow_info["escrowcash"]=="Y" && (int)$chk_total_price>=$escrow_info["escrow_limit"])) && ord($_data->escrow_id)) {
										$pgid_info="";
										$pg_type="";
										$pgid_info=GetEscrowType($_data->escrow_id);
										$pg_type=trim($pgid_info["PG"]);
										if(strstr("ABCD",$pg_type)) {
								?>
										<input id="dev_payment6" class="dev_payment" name="dev_payment" type="radio" value="Q" onclick="sel_paymethod(this);" /><label for="dev_payment6">에스크로</label>
								<?
										}
									}
									//휴대폰
									if(ord($_data->mobile_id)) {
								?>
										<input id="dev_payment4" class="dev_payment" name="dev_payment" type="radio" value="M" onclick="sel_paymethod(this);" /><label for="dev_payment4">휴대폰</label>
								<?}?>

								</td>
							</tr>
							
							<tr>
								<th></th>
								<td class="small_red">(무통장입금의 경우 입금확인 후부터 배송단계가 진행됩니다)</td>
							</tr>
							<tr>
								<th></th>
								<td style="width:500px">


									<div class="card_type" id="card_type" style="display:none">
										<div class="table_style">
										<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="임금계좌를 선택" style="text-align:left">
											<colgroup>
												<?if($etcmessage[2]=="Y") {?><col width="20%" /><?}?>
												<col />
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
													<select name="pay_data_sel" id="pay_data_sel" onchange="sel_account(this)" style="width:350px">
														<?
														if(ord($arrpayinfo[1])==0) echo "<option value='' >입금 계좌번호 선택 (반드시 주문자 성함으로 입금)</option>";
														else echo "<option value='' style='color:#000000;'>{$arrpayinfo[1]}</option>";

														
														if (ord($arrpayinfo[0])) {
															$tok = strtok($arrpayinfo[0],",");
															$i=0;
															while ($tok && $i<2) {
																$i++;
																echo "<option value=\"{$tok}\" >{$tok}</option>";
																$tok = strtok(",");
															}
														}
														?>
														
													</select>
												</td>
											</tr>
											<?if($_ShopInfo->memid && $_ShopInfo->wsmember=="Y"){?>
											<tr>
												<th>영수증신청</th>
												<td>
												<input type="radio" name="receipt_yn" id="receipt_yn1" class="receipt_yn" value="N"> <label label for="receipt_yn1" style="font-weight:bold; font-size:12px;">미신청</label> ( 차후 영수증 발행요청시 총 결제금액의 10%(부가세)가 발생됩니다 )<br>
												<input type="radio" name="receipt_yn" id="receipt_yn2" class="receipt_yn" value="Y"> <label for="receipt_yn2"  style="font-weight:bold; font-size:12px;">신청</label> ( 영수증 발급시 총 할인액에 -7% 가 됩니다. )
												</td>
											</tr>
											<?}?>
										</table>
										<?
											if(abs($dc_cash_pay)){

												$dc_cash_pay=abs($dc_cash_pay);
												if($saletype=="Y") $dc_cash_pay_type='적립';
												else $dc_cash_pay_type='할인';
										?>
											<span class="small_red">※무통장 결제시 <?=$dc_cash_pay?>%가 추가<?=$dc_cash_pay_type?> 됩니다.</span>
										<?
											}
										?>
										</div>
									</div>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

		</td>
	</tr>
	</table>
</div>

<div class="order_table_wrap">
	<table class="layout" width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
		<td align=center>
			<img src="../image/cart/confirm_atten_txt.gif" alt="이용안내 필독 안내" style="padding-top:30px;padding-bottom:15px"/>
			<img src="../image/cart/order_atten_txt.gif" alt="주문완료 주의사항" style="padding-bottom:30px"/>
		</td>
	</tr>
	</table>
</div>

</div><!--order_sheet_detail_wrap 끝  -->


		<?if(strlen($_ShopInfo->getMemid())==0) {?>
		<table>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_stitle5.gif" vspace="3"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t1.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t1bg.gif"></td>
				<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t4.gif" border="0"></td>
			</tr>
			<tr>
				<td background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t2bg.gif"></td>
				<td style="padding:10px;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<col width="150"></col>
				<col></col>
				<tr>
					<td valign="top"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>비회원<br><img width=12 height=0>정보수집 동의</b></font></td>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td style="BORDER-RIGHT: #dfdfdf 1px solid; BORDER-TOP: #dfdfdf 1px solid; BORDER-LEFT: #dfdfdf 1px solid; BORDER-BOTTOM: #dfdfdf 1px solid;width:60%" bgColor="#ffffff"><DIV style="PADDING:5px;OVERFLOW-Y:auto;OVERFLOW-X:auto;HEIGHT:100px;width: 900px"><?=$privercybody?></DIV></td>
					</tr>
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
						<td align="center"><b><?=$_data->shopname?>의 <font color="#FF4C00">개인정보취급방침</FONT>에 동의하겠습니까?</b></td>
					</tr>
					<tr>
						<td align="center" style="padding-top:5px;"><input type=radio id=idx_dongiY name=dongi value="Y" style="border:none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dongiY><b><font color="#0099CC">동의합니다.</font></b></label><img width=10 height=0><input type=radio id="idx_dongiN" name=dongi value="N" style="border:none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dongiN><b><font color="#0099CC">동의하지 않습니다.</font></b></label></td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
				<td background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t4bg.gif"></td>
			</tr>
			<tr>
				<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t2.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t3bg.gif"></td>
				<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_t3.gif" border="0"></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		<?}?>

	</div><!-- //end contents -->
</div><!-- //end container -->

		
<!--footer start -->
<? include "_footer.html" ; ?> 

