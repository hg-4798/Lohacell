<? /* include "_header.html"; 파일없음 */ ?>

<!-- start container -->
<div id="container">
<?
if($_data->oneshot_ok=="Y") {
?>
	<!-- 스피드구매 -->
	<h1 class="sub_title">스피드구매!</h1>
	<div class="speed_buy_wrap">
		<div class="speed_buy">
<?
	$code_a=$_POST["code_a"];
	$code_b=$_POST["code_b"];
	$code_c=$_POST["code_c"];
	$code_d=$_POST["code_d"];
	$likecode=$code_a.$code_b.$code_c.$code_d;
?>
		<table cellpadding="0" cellspacing="0" border="0" width="70%" style="table-layout:fixed" align=center>
		<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
			<colgroup><col width="" /><col width="150" /></colgroup>
			<input type=hidden name=productcode>
			<input type=hidden name=quantity>
			<input type=hidden name=option1>
			<input type=hidden name=option2>
			<input type=hidden name=assembleuse>
			<input type=hidden name=package_num>
			<input type=hidden name=oneshot_primage>
			<tr valign=top>
				
				<td align="center">
				<table cellpadding="2" cellspacing="0">
				
				<tr>
					<td><select name="code_a" onchange="SearchChangeCate(this,1);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 1차 카테고리 선택 ---</option></SELECT></td>
					<td><select name="code_b" onchange="SearchChangeCate(this,2);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 2차 카테고리 선택 ---</option></SELECT></td>
					<td><select name="code_c" onchange="SearchChangeCate(this,3);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 3차 카테고리 선택 ---</option></SELECT></td>
				</tr>
				<TR>
					<TD><select name="code_d" onchange="CheckCode();" style="width:150;font-size:11px;"><option value="">--- 4차 카테고리 선택 ---</option></SELECT></td>
					<td colspan="2">
					<select name="tmpprcode" onchange="CheckProduct();" style="width:306px;font-size:11px;">
						<option value="">상품 선택</option>
<?
					if(strlen($likecode)==12) {
						$sql = "SELECT a.productcode,a.productname,a.sellprice,a.tinyimage,a.quantity,a.option1,a.option2,a.etctype,a.selfcode,a.assembleuse,a.package_num ";
						$sql.= "FROM tblproduct AS a ";
						$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
						$sql.= "WHERE a.productcode LIKE '".$likecode."%' AND a.display='Y' ";
						$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
						$sql.= "ORDER BY a.productname ";
						$result=pmysql_query($sql,get_db_conn());
						$ii=0;
						$prlistscript="<script>\n";
						while($row=pmysql_fetch_object($result)) {
							if(strlen(dickerview($row->etctype,$row->sellprice,1))==0) {
								$miniq = 1;
								if (strlen($row->etctype)>0) {
									$etctemp = explode("",$row->etctype);
									for ($i=0;$i<count($etctemp);$i++) {
										if (strpos($etctemp[$i],"MINIQ=")===0) $miniq=substr($etctemp[$i],6);  // 최소주문수량
									}
								}
								echo "<option value=\"".$ii."\">".strip_tags(str_replace("<br>", " ", viewselfcode($row->productname,$row->selfcode)))." - ".number_format($row->sellprice)."원";
								if(strlen($row->quantity)!=0 && $row->quantity<=0) echo " (품절)";
								echo "</option>\n";

								if(strlen($row->quantity)!=0 && $row->quantity<=0) {
									$tmpq=0;
								} else {
									$tmpq=$row->quantity;
									if($row->quantity==NULL) $tmpq=1000;
								}
								$prlistscript.="var plist=new pralllist();\n";
								$prlistscript.="plist.productcode='".$row->productcode."';\n";
								$prlistscript.="plist.tinyimage='".$row->tinyimage."';\n";
								$prlistscript.="plist.option1=1;\n";
								$prlistscript.="plist.option2=1;\n";
								$prlistscript.="plist.quantity=".$tmpq.";\n";
								$prlistscript.="plist.miniq=".$miniq.";\n";
								$prlistscript.="plist.assembleuse='".($row->assembleuse=="Y"?"Y":"N")."';\n";
								$prlistscript.="plist.package_num='".((int)$row->package_num>0?$row->package_num:"")."';\n";
								$prlistscript.="prall[".$ii."]=plist;\n";
								$prlistscript.="plist=null;\n";
								$ii++;
							}
						}
						pmysql_free_result($result);
						$prlistscript.="</script>\n";
					}
?>
					</SELECT>
					</td>
				</tr>
				</table>
				</td>
				<td align=center><a href="javascript:OneshotBasketIn();" class="btn_buy">장바구니담기</a></td>
			</tr>
		</form>
		</table>
			
		</div>
	</div>
<?
	$sql = "SELECT * FROM tblproductcode ";
	if(strlen($_ShopInfo->getMemid())==0 || $_ShopInfo->getMemid()=="deleted") {
		$sql.= "WHERE group_code='' ";
	} else {
		$sql.= "WHERE (group_code='' OR group_code='ALL' OR group_code='".$_ShopInfo->getMemgroup()."') ";
	}
	$sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') ORDER BY sequence DESC ";
	$i=0;
	$ii=0;
	$iii=0;
	$iiii=0;
	$strcodelist = "";
	$strcodelist.= "<script>\n";
	$result = pmysql_query($sql,get_db_conn());
	$selcode_name="";
	while($row=pmysql_fetch_object($result)) {
		$strcodelist.= "var clist=new CodeList();\n";
		$strcodelist.= "clist.code_a='".$row->code_a."';\n";
		$strcodelist.= "clist.code_b='".$row->code_b."';\n";
		$strcodelist.= "clist.code_c='".$row->code_c."';\n";
		$strcodelist.= "clist.code_d='".$row->code_d."';\n";
		$strcodelist.= "clist.type='".$row->type."';\n";
		$strcodelist.= "clist.code_name='".$row->code_name."';\n";
		if($row->type=="L" || $row->type=="T" || $row->type=="LX" || $row->type=="TX") {
			$strcodelist.= "lista[".$i."]=clist;\n";
			$i++;
		}
		if($row->type=="LM" || $row->type=="TM" || $row->type=="LMX" || $row->type=="TMX") {
			if ($row->code_c=="000" && $row->code_d=="000") {
				$strcodelist.= "listb[".$ii."]=clist;\n";
				$ii++;
			} else if ($row->code_d=="000") {
				$strcodelist.= "listc[".$iii."]=clist;\n";
				$iii++;
			} else if ($row->code_d!="000") {
				$strcodelist.= "listd[".$iiii."]=clist;\n";
				$iiii++;
			}
		}
		$strcodelist.= "clist=null;\n\n";
	}
	pmysql_free_result($result);
	$strcodelist.= "CodeInit();\n";
	$strcodelist.= "</script>\n";

	echo $strcodelist;

	echo $prlistscript;

	echo "<script>SearchCodeInit('".$code_a."','".$code_b."','".$code_c."','".$code_d."');</script>";
	
}
?>
	<!-- #스피드구매 -->


	<!-- start contents -->
	<div class="contents">
	
	<div class="title">
		<h2><img src="../image/cart/title_cart.gif" alt="장바구니" /></h2>
		<div class="path">
			<ul>
				<li class="home">홈&nbsp;&gt;&nbsp;</li>
				<li>장바구니</li>
			</ul>
		</div>
	</div>
	
	<div class="order_step">
		<ul>
			<li><img src="../image/cart/cart_nav_01.gif" alt="step1" /></li>
			<li><img src="../image/cart/cart_nav_02.gif" alt="step2" /></li>
			<li><img src="../image/cart/cart_nav_03.gif" alt="step3" /></li>
			<li><img src="../image/cart/cart_nav_04.gif" alt="step4" /></li>
		</ul>
	</div>
    <div class="cart_txt">
			<img src="../image/cart/cart_txt.gif" />
	</div>

<div class="cart_listbar">
		<ul>
			<li class="cart_list1" style="height:30px;margin-top:7px;"><input type="checkbox" name ="allCheck" class ="allCheck" checked ></li>
			<li class="cart_list2"><img src="../image/cart/table_tit_01.gif" alt="상품정보" /></li>
			<li class="cart_list3"><img src="../image/cart/table_tit_02.gif" alt="적립금" /></li>
			<li class="cart_list4"><img src="../image/cart/table_tit_03.gif" alt="판매가" /></li>
			<li class="cart_list5"><img src="../image/cart/table_tit_04.gif" alt="할인적용가" /></li>
			<li class="cart_list6"><img src="../image/cart/table_tit_05.gif" alt="수량" /></li>
			<li class="cart_list7"><img src="../image/cart/table_tit_06.gif" alt="합계" /></li>
		</ul>
</div>

<?
$sql = "SELECT b.vender FROM tblbasket a, tblproduct b WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode GROUP BY b.vender ";
$res=pmysql_query($sql,get_db_conn());

$cnt=0;
$sumprice = 0;
$deli_price = 0;
$reserve = 0;
$formcount=0;

while($vgrp=pmysql_fetch_object($res)) {
	//1. vender가 0이 아니면 해당 입점업체의 배송비 추가 설정값을 가져온다.
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
	
	$sql = "SELECT a.basketidx, a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.productcode,b.productname,b.sellprice,b.membergrpdc, b.option_reserve,";
	$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, ";
	$sql.= "b.etctype,b.deli_price, b.deli,b.sellprice*a.quantity as realprice, b.selfcode,a.assemble_list,a.assemble_idx,a.package_idx ";
	//$sql.= ", c.assemble_type, c.assemble_title ";
	$sql.= "FROM tblbasket a, tblproduct b ";
	//$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
	$sql.= "WHERE b.vender='".$vgrp->vender."' ";
	$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
	$sql.= "AND a.productcode=b.productcode order by basketidx desc";
	$result=pmysql_query($sql,get_db_conn());

	$vender_sumprice = 0;	//해당 입점업체의 총 구매액
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
		if(preg_match("/^\[OPTG\d{4}\]$/",$row->option1)){
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
?>
<form name=form_<?=$formcount?> method=post action="<?=$Dir.FrontDir?>basket.php"><?$formcount++;?>
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=productcode value="<?=$row->productcode?>">
<input type=hidden name=orgquantity value="<?=$row->quantity?>">
<input type=hidden name=orgoption1 value="<?=$row->opt1_idx?>">
<input type=hidden name=orgoption2 value="<?=$row->opt2_idx?>">
<input type=hidden name=opts value="<?=$row->optidxs?>">
<input type=hidden name=brandcode value="<?=$brandcode?>">
<input type=hidden name=assemble_list value="<?=$row->assemble_list?>">
<input type=hidden name=assemble_idx value="<?=$row->assemble_idx?>">
<input type=hidden name=package_idx value="<?=$row->package_idx?>">

<?
		$assemble_str="";
		$package_str="";
		$packagelist_str="";
		if($row->assemble_idx>0 && strlen(str_replace("","",$row->assemble_list))>0) {
			$assemble_list_proexp = explode("",$row->assemble_list);
			$alprosql = "SELECT productcode,productname,sellprice FROM tblproduct ";
			$alprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_proexp)."') ";
			$alprosql.= "AND display = 'Y' ";
			$alprosql.= "ORDER BY FIELD(productcode,'".implode("','",$assemble_list_proexp)."') ";
			$alproresult=pmysql_query($alprosql,get_db_conn());

			//$assemble_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
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
				$packagelist_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
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
		
		

		
//		echo $salemoney;
//		$salemoney = getProductDcPrice($sellprice,$dc_data[price]);
		

		######### 상품 특별할인률 적용 ############
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
			if(strlen($group_type)>0 && $group_type!=NULL) {
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


		//회원 할인율 적용
		$before_sellprice=$sellprice;
		$bf_price = $before_sellprice*$row->quantity;
		$sellprice = $sellprice - $salemoney;
//		$sellprice= getProductDcPrice($sellprice,$dc_data[price]);
		$price = $sellprice*$row->quantity;

		//추가 적립금 적용
		$tempreserve+=$salereserve;
		
		//비회원이면 적립금 노출 X
		if(strlen($_ShopInfo->getMemgroup())==0) $tempreserve=0;

		//######### 옵션에 따른 가격 변동 체크 끝 ############
		$bf_sumprice += $bf_price;
		$sumprice += $price;
		$vender_sumprice += $price;

		//################ 개별 배송비 체크 #################
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
		//###################################################
		$productname=$row->productname;

		$reserve += $tempreserve*$row->quantity;

		//######## 특수값체크 : 현금결제상품//무이자상품 #####
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
							//if ($card_type=="IN" || $card_type=="BO") $setquota_html.="2~";
							//else                  $setquota_html.="3~";
							$setquota_html.="3~";
							$setquota_html.= $_data->card_splitmonth.")</font>";
						}
						break;
				}
			}
		}

?>
<div class="cart_list">
		<ul>
			<li class="cart_list1"><p><input name = "checkProduct" class = "checkProduct" value = "<?=$row->basketidx?>" type="checkbox" checked></p></li>
			<li class="cart_list2">
			<span>
			<?
				if(strlen($row->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)){
					$file_size=getImageSize($Dir.DataDir."shopimages/product/".$row->tinyimage);
			?>
				<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row->productcode?>"><img src="<?=$Dir.DataDir?>shopimages/product/<?=$row->tinyimage?>" <?if($file_size[0]>=$file_size[1]){ echo " width='50'"; }else{ echo "height='50'"; }?>></a>
			<?	
				} else {
			?>
					<img src="<?=$Dir?>images/no_img.gif" width="50">
			<?			
				}
			?>
			</span>
			<span class="cart_item_name">
			<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row->productcode?>">
				<?=viewproductname($productname,$row->etctype,$row->selfcode,$row->addcode) ?><?=$bankonly_html?><?=$setquota_html?><?=$deli_str?>
			</a> 
			<br>
			<?
				if (strlen($row->option1)>0 || strlen($row->option2)>0 || strlen($optvalue)>0) {// 특징 및 선택사항이 있으면
			?>	
			<img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icon002.gif" border="0" align="absmiddle" style="width:22px;height:14px">
			<?
				// ###### 특성 #########
				if (strlen($row->option1)>0) {
					$temp = $row->option1;
					$tok = explode(",",$temp);
					$count=count($tok);
					echo "$tok[0] ";
					
			?>
					<select name=option1 size=1 onchange="CheckForm('upd',<?=$formcount-1?>)">
			<?
					for($i=1;$i<$count;$i++){
						if(strlen($tok[$i])>0){
			?>
						<option value=<?=$i?> <?if($i==$row->opt1_idx) echo " selected";?>><?=$tok[$i]?></option>
			<?
						}
					}
			?>
					</select>

			<?
				}
				if (strlen($row->option2)>0) {
					$temp = $row->option2;
					$tok = explode(",",$temp);
					$count=count($tok);
					echo "$tok[0] ";
			?>
					<select name=option2 size=1 onchange="CheckForm('upd',<?=$formcount-1?>)">
			<?
					for($i=1;$i<$count;$i++){
						if(strlen($tok[$i])>0){
			?>
							<option value=<?=$i?> <?if($i==$row->opt2_idx) echo " selected";?>><?=$tok[$i]?></option>
			<?
						}
					}
			?>
					</select>
			<?
				}
				if(strlen($optvalue)>0) {
					echo $optvalue."</font>\n";
				}
			}

			if (strlen($package_str)>0) { // 패키지 정보
			?>
			<img src="<?=$Dir?>images/common/icn_package.gif" border="0" align="absmiddle" style="width:36px;height:16px"> <?=(strlen($package_str)>0?$package_str:"")?>
			<?
			}
			if (strlen($packagelist_str)>0) { // 패키지 정보
				echo "<table><tr>".$packagelist_str."</tr></table>";
			}
			if (strlen($assemble_str)>0) { // 코디 정보
				echo "<table><tr>".$assemble_str."</tr></table>";
			}
			?>

			</span>

			</li>
			<li class="cart_list3"><p><?=number_format($tempreserve) ?>원</p></li>
			<li class="cart_list4"><p><?=number_format($before_sellprice)?>원</p></li>
			<li class="cart_list5"><p><?=number_format($sellprice)?>원</p></li>
			<li class="cart_list6">
			<p class="chg_cart">
			<span><input  name="quantity" size="2" value="<?=$row->quantity ?>" class="line" onkeyup="strnumkeyup(this)" type="text"></span>
			<span><a href="javascript:change_quantity('dn',<?=$formcount-1;?>)"><img src="../image/cart/c_minus_btn.jpg" style="cursor:pointer"></a></span>
			<span><a href="javascript:change_quantity('up',<?=$formcount-1;?>)"><img src="../image/cart/c_plus_btn.jpg" style="cursor:pointer"></a></span>
			<span><a href="javascript:CheckForm('upd',<?=$formcount-1?>)"><img src="../image/cart/c_modify_btn.gif" type="image"></a></span>
			</p>
			</li>
			<li class="cart_list7"><p><?=number_format($price)?>원</p></li>
		</ul>
</div>
</form>

<script language='javascript'>
	_A_amt[i]='<?=price?>';
	_A_nl[i]='<?=$row->quantity ?>';
	_A_pl[i]='<?=$row->productcode?>';
	_A_pn[i]='<?=$row->productname?>';
	_A_ct[i]='';
	i++;
</script>

<?
	}
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
		//해당 입점업체의 상품구매액, 배송비 등의 결제금액을 구한다.

		pmysql_free_result($res);
		
		if($cnt==0) {
?>
		<div class="cart_list">
		<ul>
			<li style="width:100%">쇼핑하신 상품이 없습니다.</li>
		</ul>
		</div>
<?
		}
?>

<div class="order_calculate">
<ul>
	<li>[ 상품합계금액 <span class="cart_price"><?=number_format($bf_sumprice)?></span>원&nbsp;/&nbsp;</li>
	<li>받으실적립금 <span class="cart_price"><?=number_format($reserve)?></span>원 ]</li>
	<li>총 결제금액 <span class="total_pay"><?=number_format($sumprice)?></span>원</li>
</ul>
</div>



<div class="cart_bt">
<a href="javascript:history.go(-1)"><img src="../image/cart/btn_back.gif"></a>
<a href="javascript:CheckForm('del_chk','')"><img src="../image/cart/btn_del.gif"></a>
<a href="javascript:CheckForm('wish_chk','')"><img src="../image/cart/btn_wishList.gif"></a>
<?
		if(strlen($code)>0) {
			if($brandcode>0) {
				$shopping_url=$Dir.FrontDir."productblist.php?code=".substr($code,0,12)."&brandcode=".$brandcode;
			} else {
				$shopping_url=$Dir.FrontDir."productlist.php?code=".substr($code,0,12);
			}
		} else {
			$shopping_url=$Dir.MainDir."main.php";
		}
?>
<a href="<?=$shopping_url?>"><img src="../image/cart/btn_shopping.gif"></a>
<A HREF="<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php")?>"></a>
<a href="javascript:basket_clear();"><img src="../image/cart/btn_empty.gif"></a>
<a href="#1" class="estimate"><img src="../image/cart/btn_print_off.gif"></a>
<a href="#1" class="selectProduct"><img src="../image/cart/btn_order.gif"></a>
</div>

<div>
<img src="../image/cart/cart_banner.jpg" usemap="#cart_detail">
<map name="cart_detail" id="cart_detail">
  <area shape="rect" coords="1001,2,1100,144" href="/board/board.php?pagetype=view&num=194684&board=qana&block=&gotopage=1&search=&s_check=" />
</map>
</div>

	</div><!-- //end contents -->
</div><!-- //end container -->


<!--footer start -->
<? /* include "_footer.html"; 파일없음 */ ?> 