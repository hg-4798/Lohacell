<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}


$code_a=$_POST["code_a"];
$code_b=$_POST["code_b"];
$code_c=$_POST["code_c"];
$code_d=$_POST["code_d"];
$likecode=$code_a.$code_b.$code_c.$code_d;

$one_start = "<form name=form1 method=post action=\"{$_SERVER['PHP_SELF']}\">\n";
$one_start.= "<input type=hidden name=productcode>\n";
$one_start.= "<input type=hidden name=quantity>\n";
$one_start.= "<input type=hidden name=option1>\n";
$one_start.= "<input type=hidden name=option2>\n";
$one_start.= "<input type=hidden name=assembleuse>\n";
$one_start.= "<input type=hidden name=package_num>\n";

$one_code_a = "<select name=code_a style=\"{$code_a_style}\" onchange=\"SearchChangeCate(this,1);CheckCode();\">\n";
$one_code_a.= "<option value=\"\">--- 1차 카테고리 선택 ---</option>\n";
$one_code_a.= "</select>\n";

$one_code_b = "<select name=code_b style=\"{$code_b_style}\"  onchange=\"SearchChangeCate(this,2);CheckCode();\">\n";
$one_code_b.= "<option value=\"\">--- 2차 카테고리 선택 ---</option>\n";
$one_code_b.= "</select>\n";

$one_code_c = "<select name=code_c style=\"{$code_c_style}\"  onchange=\"SearchChangeCate(this,3);CheckCode();\">\n";
$one_code_c.= "<option value=\"\">--- 3차 카테고리 선택 ---</option>\n";
$one_code_c.= "</select>\n";

$one_code_d = "<select name=code_d style=\"{$code_d_style}\"  onchange=\"CheckCode();\">\n";
$one_code_d.= "<option value=\"\">--- 4차 카테고리 선택 ---</option>\n";
$one_code_d.= "</select>\n";

$one_prlist = "<select name=tmpprcode style=\"{$prlist_style}\" onchange=\"CheckProduct();\">\n";
$one_prlist.= "<option value=\"\">상품 선택</option>\n";
if(strlen($likecode)==12) {
	$sql = "SELECT a.productcode,a.productname,a.sellprice,a.tinyimage,a.quantity,a.option1,a.option2, ";
	$sql.= "a.etctype,a.selfcode,a.assembleuse,a.package_num FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= "WHERE a.productcode LIKE '{$likecode}%' AND a.display='Y' ";
	$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
	$sql.= "ORDER BY a.productname ";
	$result=pmysql_query($sql,get_db_conn());
	$ii=0;
	$prlistscript="<script>\n";
	while($row=pmysql_fetch_object($result)) {
		if(strlen(dickerview($row->etctype,$row->sellprice,1))==0) {
			$miniq = 1;
			if (ord($row->etctype)) {
				$etctemp = explode("",$row->etctype);
				for ($i=0;$i<count($etctemp);$i++) {
					if (strpos($etctemp[$i],"MINIQ=")===0) $miniq=substr($etctemp[$i],6);  // 최소주문수량
				}
			}
			$one_prlist.= "<option value=\"{$ii}\">".strip_tags(str_replace("<br>", " ", viewselfcode($row->productname,$row->selfcode)))." - ".number_format($row->sellprice)."원";
			if(strlen($row->quantity)!=0 && $row->quantity<=0) $one_prlist.= " (품절)";
			$one_prlist.= "</option>\n";

			if(strlen($row->quantity)!=0 && $row->quantity<=0) {
				$tmpq=0;
			} else {
				$tmpq=$row->quantity;
				if($row->quantity==NULL) $tmpq=1000;
			}
			$prlistscript.="var plist=new pralllist();\n";
			$prlistscript.="plist.productcode='{$row->productcode}';\n";
			$prlistscript.="plist.tinyimage='{$row->tinyimage}';\n";
			$prlistscript.="plist.option1=1;\n";
			$prlistscript.="plist.option2=1;\n";
			$prlistscript.="plist.quantity={$tmpq};\n";
			$prlistscript.="plist.miniq={$miniq};\n";
			$prlistscript.="plist.assembleuse='".($row->assembleuse=="Y"?"Y":"N")."';\n";
			$prlistscript.="plist.package_num='".((int)$row->package_num>0?$row->package_num:"")."';\n";
			$prlistscript.="prall[{$ii}]=plist;\n";
			$prlistscript.="plist=null;\n";
			$ii++;
		}
	}
	pmysql_free_result($result);
	$prlistscript.="</script>\n";
}

$sql = "SELECT * FROM tblproductcode WHERE 1=1 ";
if(strlen($_ShopInfo->getMemid())==0 || $_ShopInfo->getMemid()=="deleted") {
	$sql.= "AND group_code='' ";
} else {
	$sql.= "AND (group_code='' OR group_code='ALL' OR group_code='".$_ShopInfo->getMemgroup()."') ";
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
	$strcodelist.= "clist.code_a='{$row->code_a}';\n";
	$strcodelist.= "clist.code_b='{$row->code_b}';\n";
	$strcodelist.= "clist.code_c='{$row->code_c}';\n";
	$strcodelist.= "clist.code_d='{$row->code_d}';\n";
	$strcodelist.= "clist.type='{$row->type}';\n";
	$strcodelist.= "clist.code_name='{$row->code_name}';\n";
	if($row->type=="L" || $row->type=="T" || $row->type=="LX" || $row->type=="TX") {
		$strcodelist.= "lista[{$i}]=clist;\n";
		$i++;
	}
	if($row->type=="LM" || $row->type=="TM" || $row->type=="LMX" || $row->type=="TMX") {
		if ($row->code_c=="000" && $row->code_d=="000") {
			$strcodelist.= "listb[{$ii}]=clist;\n";
			$ii++;
		} else if ($row->code_d=="000") {
			$strcodelist.= "listc[{$iii}]=clist;\n";
			$iii++;
		} else if ($row->code_d!="000") {
			$strcodelist.= "listd[{$iiii}]=clist;\n";
			$iiii++;
		}
	}
	$strcodelist.= "clist=null;\n\n";
}
pmysql_free_result($result);
$strcodelist.= "CodeInit();\n";
$strcodelist.= "</script>\n";

$one_end = "</form>\n";
$one_end.= $strcodelist."\n";
$one_end.= $prlistscript."\n";
$one_end.= "<script>SearchCodeInit('{$code_a}','{$code_b}','{$code_c}','{$code_d}');</script>";

$one_primg="<img src=\"{$Dir}images/common/basket/oneshot_primageU.gif\" border=0 width=50 height=50 name=oneshot_primage>";

/* 장바구니 상품 관련 시작 */
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
	$_vender=null;
	if($vgrp->vender>0) {
		$sql = "SELECT deli_price, deli_pricetype, deli_mini, deli_limit FROM tblvenderinfo WHERE vender='{$vgrp->vender}' ";
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

	$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.productcode,b.productname,b.sellprice, ";
	$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, ";
	$sql.= "b.etctype,b.deli_price, b.deli,b.sellprice*a.quantity as realprice, b.selfcode,a.assemble_list,a.assemble_idx,a.package_idx ";
	//$sql.= ", c.assemble_type, c.assemble_title ";
	$sql.= "FROM tblbasket a, tblproduct b WHERE b.vender='{$vgrp->vender}' ";
	//$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
	$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
	$sql.= "AND a.productcode=b.productcode ";
	$sql.= "ORDER BY a.date DESC ";
	$result=pmysql_query($sql,get_db_conn());
	$prcnt=pmysql_num_rows($result);

	$vender_sumprice = 0;	//해당 입점업체의 총 구매액
	$vender_delisumprice = 0;//해당 입점업체의 기본배송비 총 구매액
	$vender_deliprice = 0;
	$deli_productprice=0;
	$deli_init = false;
	$kk=0;
	while($row = pmysql_fetch_object($result)) {
		$kk++;
		if (ord($row->option_price) && $row->opt1_idx==0) {
			$sql = "DELETE FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$sql.= "AND productcode='{$row->productcode}' AND opt1_idx='{$row->opt1_idx}' ";
			$sql.= "AND opt2_idx='{$row->opt2_idx}' AND optidxs='{$row->optidxs}' ";
			pmysql_query($sql,get_db_conn());

			echo "<script>alert('필수 선택 옵션 항목이 있습니다.\\n옵션을 선택하신후 장바구니에\\n담으시기 바랍니다.');location.href=\"".$Dir.FrontDir."productdetail.php?productcode={$row->productcode}\";</script>";
			exit;
		}
		if(preg_match("/^\[OPTG\d{4}\]$/",$row->option1)){
			$optioncode = substr($row->option1,5,4);
			$row->option1="";
			$row->option_price="";
			if(!empty($row->optidxs)) {
				$tempoptcode = rtrim($row->optidxs,',');
				$exoptcode = explode(",",$tempoptcode);

				$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='{$optioncode}' ";
				$resultopt = pmysql_query($sqlopt,get_db_conn());
				if($rowopt = pmysql_fetch_object($resultopt)){
					$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
					$opti=0;
					$optvalue="";
					$option_choice = $rowopt->option_choice;
					$exoption_choice = explode("",$option_choice);
					while(ord($optionadd[$opti])){
						if($exoption_choice[$opti]==1 && $exoptcode[$opti]==0){
							$delsql = "DELETE FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
							$delsql.= "AND productcode='{$row->productcode}' ";
							$delsql.= "AND opt1_idx='{$row->opt1_idx}' AND opt2_idx='{$row->opt2_idx}' ";
							$delsql.= "AND optidxs='{$row->optidxs}' ";
							pmysql_query($delsql,get_db_conn());
							echo "<script>alert('필수 선택 옵션 항목이 있습니다.\\n옵션을 선택하신후 장바구니에\\n담으시기 바랍니다.');location.href=\"".$Dir.FrontDir."productdetail.php?productcode={$row->productcode}\";</script>";
							exit;
						}
						if($exoptcode[$opti]>0){
							$opval = explode("",str_replace('"','',$optionadd[$opti]));
							$optvalue.= ", {$opval[0]} : ";
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

		$tempbasket.=$mainbasket;

		$cnt++;

		$basket_start = "<form name=form_{$formcount} method=post action=\"".$Dir.FrontDir."basket.php\">\n"; $formcount++;
		$basket_start.= "<input type=hidden name=mode>\n";
		$basket_start.= "<input type=hidden name=code value=\"{$code}\">\n";
		$basket_start.= "<input type=hidden name=productcode value=\"{$row->productcode}\">\n";
		$basket_start.= "<input type=hidden name=orgquantity value=\"{$row->quantity}\">\n";
		$basket_start.= "<input type=hidden name=orgoption1 value=\"{$row->opt1_idx}\">\n";
		$basket_start.= "<input type=hidden name=orgoption2 value=\"{$row->opt2_idx}\">\n";
		$basket_start.= "<input type=hidden name=opts value=\"{$row->optidxs}\">\n";
		$basket_start.= "<input type=hidden name=assemble_list value=\"{$row->assemble_list}\">\n";
		$basket_start.= "<input type=hidden name=assemble_idx value=\"{$row->assemble_idx}\">\n";
		$basket_start.= "<input type=hidden name=package_idx value=\"{$row->package_idx}\">\n";

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

			$assemble_str.="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";

			$assemble_sellerprice=0;
			while($alprorow=@pmysql_fetch_object($alproresult)) {
				$assemble_str.="<tr>\n";
				$assemble_str.="	<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;\">\n";
				$assemble_str.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
				$assemble_str.="	<col width=\"\"></col>\n";
				$assemble_str.="	<col width=\"80\"></col>\n";
				$assemble_str.="	<col width=\"120\"></col>\n";
				$assemble_str.="	<tr>\n";
				$assemble_str.="		<td style=\"padding:4px;word-break:break-all;\"><font color=\"#000000\">{$alprorow->productname}</font>&nbsp;</td>\n";
				$assemble_str.="		<td align=\"right\" style=\"padding:4px;border-left:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\"><font color=\"#000000\">".number_format((int)$alprorow->sellprice)."원..</font></td>\n";
				$assemble_str.="		<td align=\"center\" style=\"padding:4px;\">본 상품 1개당 수량1개</td>\n";
				$assemble_str.="	</tr>\n";
				$assemble_str.="	</table>\n";
				$assemble_str.="	</td>\n";
				$assemble_str.="</tr>\n";
				$assemble_sellerprice+=$alprorow->sellprice;
			}
			@pmysql_free_result($alproresult);
			$assemble_str.="</table>\n";

			//######### 코디/조립에 따른 가격 변동 체크 ###############
			$price = $assemble_sellerprice*$row->quantity;
			$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$assemble_sellerprice,"N");
			$sellprice=$assemble_sellerprice;
		} else if($row->package_idx>0 && ord($row->package_idx)) {
			$package_str ="<a href=\"javascript:setPackageShow('packageidx{$cnt}');\">{$title_package_listtmp[$row->productcode][$row->package_idx]}(<font color=#FF3C00>+".number_format($price_package_listtmp[$row->productcode][$row->package_idx])."원</font>)</a>";
			$productname_package_list_exp = $productname_package_list[$row->productcode][$row->package_idx];
			$packagelist_str.="<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
			$packagelist_str.="<tr id=\"packageidx{$cnt}\" style=\"display:none;\">\n";
			if(count($productname_package_list_exp)>0) {
				$packagelist_str.="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
				$packagelist_str.="		<td width=\"100%\">\n";
				$packagelist_str.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";

				for($i=0; $i<count($productname_package_list_exp); $i++) {
					$packagelist_str.="		<tr>\n";
					$packagelist_str.="			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;\">\n";
					$packagelist_str.="			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
					$packagelist_str.="			<col width=\"\"></col>\n";
					$packagelist_str.="			<col width=\"120\"></col>\n";
					$packagelist_str.="			<tr>\n";
					$packagelist_str.="				<td style=\"padding:4px;word-break:break-all;\"><font color=\"#000000\">{$productname_package_list_exp[$i]}</font>&nbsp;</td>\n";
					$packagelist_str.="				<td align=\"center\" style=\"padding:4px;border-left:1px #DDDDDD solid;\">본 상품 1개당 수량1개</td>\n";
					$packagelist_str.="			</tr>\n";
					$packagelist_str.="			</table>\n";
					$packagelist_str.="			</td>\n";
					$packagelist_str.="		</tr>\n";
				}
				$packagelist_str.="		</table>\n";
				$packagelist_str.="		</td>\n";
			} else {
				$packagelist_str.="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
				$packagelist_str.="		<td width=\"100%\">\n";
				$packagelist_str.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
				$packagelist_str.="		<tr>\n";
				$packagelist_str.="			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;padding:4px;word-break:break-all;\"><font color=\"#000000\">구성상품이 존재하지 않는 패키지</font></td>\n";
				$packagelist_str.="		</tr>\n";
				$packagelist_str.="		</table>\n";
				$packagelist_str.="		</td>\n";
			}
			$packagelist_str.="</tr>\n";
			$packagelist_str.="</table>\n";

			//######### 옵션에 따른 가격 변동 체크 ###############
			if (ord($row->option_price)==0) {
				$sellprice=$row->sellprice+$price_package_listtmp[$row->productcode][$row->package_idx];
				$price = $sellprice*$row->quantity;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$sellprice,"N");
			} else if (ord($row->opt1_idx)) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$sellprice=$pricetok[$row->opt1_idx-1]+$price_package_listtmp[$row->productcode][$row->package_idx];
				$price = $sellprice*$row->quantity;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$sellprice,"N");
			}
		} else {
			//######### 옵션에 따른 가격 변동 체크 ###############
			if (ord($row->option_price)==0) {
				$price = $row->realprice;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
				$sellprice=$row->sellprice;
			} else if (ord($row->opt1_idx)) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1]*$row->quantity;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
				$sellprice=$pricetok[$row->opt1_idx-1];
			}
		}
		//######### 옵션에 따른 가격 변동 체크 끝 ############
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
		if (ord($row->etctype)) {
			$etctemp = explode("",$row->etctype);
			for ($i=0;$i<count($etctemp);$i++) {
				switch ($etctemp[$i]) {
					case "BANKONLY": $bankonly = "Y";
						$bankonly_html = " <img src={$Dir}images/common/bankonly.gif border=0 align=absmiddle> ";
						break;
					case "SETQUOTA":
						if ($_data->card_splittype=="O" && $price>=$_data->card_splitprice) {
							$setquotacnt++;
							$setquota_html = " <img src={$Dir}images/common/setquota.gif border=0 align=absmiddle>";
							$setquota_html.= "</b><font color=black size=1>(";
							//if ($card_type=="IN" || $card_type=="BO") $setquota_html.="2~";
							//else                  $setquota_html.="3~";
							$setquota_html.="3~";
							$setquota_html.= $_data->card_splitmonth.")</font>";
						}
						break;
				}
			}
		} // $row_count 값과 setquotacnt값이 같으면 무이자결제가능하게 데이터를 보낸다.

		if(strlen($row->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)){
			$file_size=getImageSize($Dir.DataDir."shopimages/product/".$row->tinyimage);
			$basket_primg = "<img src=\"".$Dir.DataDir."shopimages/product/{$row->tinyimage}\"";
			if($file_size[0]>=$file_size[1]) $basket_primg.=" width=50";
			else $basket_primg.=" height=50";
			$basket_primg.=">";
		} else {
			$basket_primg="<img src=\"{$Dir}images/no_img.gif\" width=50>";
		}

		$basket_prname = "<a href=\"".$Dir.FrontDir."productdetail.php?productcode={$row->productcode}\"><font color=#373737><b>".viewproductname($productname,$row->etctype,$row->selfcode)."</b>".$bankonly_html.$setquota_html.$deli_str."";

		if(ord($row->addcode)==0) $basket_addcode1="";
		else $basket_addcode1="-".$row->addcode;
		$basket_addcode2=$row->addcode;

		if ($_data->reserve_maxuse>=0) {
			$basket_reserve=number_format($tempreserve);
		} else {
			$basket_reserve="없음";
		}
		$basket_sellprice=number_format($sellprice);
		$basket_quantity="<input name=quantity value=\"{$row->quantity}\" size=3 maxlength=4 onkeyup=\"strnumkeyup(this)\">";
		$basket_qup="\"javascript:change_quantity('up',".($formcount-1).")\"";
		$basket_qdn="\"javascript:change_quantity('dn',".($formcount-1).")\"";
		$basket_qupdate="\"javascript:CheckForm('upd',".($formcount-1).")\"";
		$basket_price=number_format($price);

		if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
			$basket_wishlist="javascript:go_wishlist('".($formcount-1)."')";
		} else {
			$basket_wishlist="javascript:check_login()";
		}
		$basket_del="javascript:CheckForm('del',".($formcount-1).")";
		
		
		#비즈 스프링용 템프릿 변수 추가
		if($biz[bizNumber]){
			$basket_del_biz="onMouseDown=\"eval('try{ _trk_clickTrace( \'SCO\', \'".$productname."\' ); }catch(_e){ }');\"";
		}else{
			$basket_del_biz="";
		}

		$basket_option="";
		$tempoption="";
		if (ord($row->option1) || ord($row->option2) || ord($optvalue)) {
			if (ord($row->option1)) {
				$temp = $row->option1;
				$tok = explode(",",$temp);
				$count=count($tok);
				$basket_option.="$tok[0] ";
				$basket_option.="<select name=option1 size=1 onchange=\"CheckForm('upd',$formcount-1)\">\n";
				for($i=1;$i<$count;$i++){
					if(ord($tok[$i])){
						$basket_option.="<option value=\"$i\"";
						if($i==$row->opt1_idx) $basket_option.=" selected";
						$basket_option.=">$tok[$i]\n";
					}
				}
				$basket_option.="</select></font>\n";
			}
			if (ord($row->option2)) {
				$temp = $row->option2;
				$tok = explode(",",$temp);
				$count=count($tok);
				$basket_option.="$tok[0] ";
				$basket_option.="<select name=option2 size=1 onchange=\"CheckForm('upd',$formcount-1)\">\n";
				for($i=1;$i<$count;$i++){
					if(ord($tok[$i])){
						$basket_option.="<option value=\"$i\"";
						if($i==$row->opt2_idx) $basket_option.=" selected";
						$basket_option.=">$tok[$i]\n";
					}
				}
				$basket_option.="</select></font>\n";
			}
			if(ord($optvalue)) {
				$basket_option.= $optvalue."</font>\n";
			}
			$tempoption=$optionbasket;
			$tempoption=str_replace("[BASKET_OPTION]",$basket_option,$tempoption);
		}

		$basket_end = "</form>\n";

		$assemblevalue="";
		if(ord($assemble_str)) {
			$assemblevalue=$assemblebasket;
			$assemblevalue=str_replace("[BASKET_ASSEMBLE]",$assemble_str,$assemblevalue);
		}

		$packagevalue="";
		if(ord($package_str)) {
			$packagevalue=$packagebasket;
			$packagevalue=str_replace("[BASKET_PACKAGE]",$package_str,$packagevalue);
		}

		$packagelistvalue="";
		if(ord($packagelist_str)) {
			$packagelistvalue=$packagelistbasket;
			$packagelistvalue=str_replace("[BASKET_PACKAGELIST]",$packagelist_str,$packagelistvalue);
		}

		$pattern=array("[BASKET_PRIMG]","[BASKET_PRNAME]","[BASKET_ADDCODE1]","[BASKET_ADDCODE2]","[BASKET_RESERVE]","[BASKET_SELLPRICE]","[BASKET_QUANTITY]","[BASKET_QUP]","[BASKET_QDN]","[BASKET_QUPDATE]","[BASKET_PRICE]","[BASKET_WISHLIST]","[BASKET_DEL]","[FORBASKET]","[FORENDBASKET]","[OPTIONVALUE]","[ASSEMBLEVALUE]","[PACKAGEVALUE]","[GROUPBASKETVALUE]","[PACKAGELISTVALUE]");

		$groupbasketvalue="";
		if($prcnt==$kk) $groupbasketvalue="[GROUPBASKETVALUE]";

		$replace=array($basket_primg,$basket_prname,$basket_addcode1,$basket_addcode2,$basket_reserve,$basket_sellprice,$basket_quantity,$basket_qup,$basket_qdn,$basket_qupdate,$basket_price,$basket_wishlist,$basket_del,$basket_start,$basket_end,$tempoption,$assemblevalue,$packagevalue,$groupbasketvalue,$packagelistvalue, $basket_del_biz);

		$tempbasket=str_replace($pattern,$replace,$tempbasket);
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
		} else if(ord($_vender->deli_limit)) {
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
		} else if(ord($_data->deli_limit)) {
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

	$group_deliprice=number_format($vender_deliprice);
	$group_totprice=number_format($vender_sumprice);

	$pattern=array("[GROUP_DELIPRICE]","[GROUP_TOTPRICE]","[BASKET_GROUPSTART]","[BASKET_GROUPEND]");
	$replace=array($group_deliprice,$group_totprice,"","");
	$tempgroupbasket=str_replace($pattern,$replace,$groupbasket);

	$pattern=array("[GROUPBASKETVALUE]");
	$replace=array($tempgroupbasket);
	$tempbasket=str_replace($pattern,$replace,$tempbasket);
}
pmysql_free_result($res);



if($sumprice>0) {
	if($_data->ETCTYPE["VATUSE"]=="Y") {
		$sumpricevat=return_vat($sumprice);
		$basket_productpricevat=($sumpricevat>0?"+ ":"").number_format($sumpricevat);
	} else {
		$sumpricevat=0;
		$basket_productpricevat=0;
	}
	$basket_productprice=number_format($sumprice);
	$basket_deliprice=($deli_price>0?"+ ":"").number_format($deli_price);
	$basket_totreserve=number_format($reserve);
	$basket_totprice=number_format($sumprice+$deli_price+$sumpricevat);

	$originalbasket=$ifbasket;
	$pattern=array("[BASKETVALUE]","[BASKET_TOTPRICE]","[BASKET_PRODUCTPRICE]","[BASKET_DELIPRICE]","[BASKET_TOTRESERVE]","[BASKET_PRODUCTPRICEVAT]");
	$replace=array($tempbasket,$basket_totprice,$basket_productprice,$basket_deliprice,$basket_totreserve,$basket_productpricevat);
	$originalbasket=str_replace($pattern,$replace,$originalbasket);
} else {
	$originalbasket=$nobasket;
}
/* 장바구니 상품 관련 끝 */


$royalvalue="";
$royal_img="";
$royal_msg1="";
$royal_msg2="";
if(strlen($_ShopInfo->getMemid())>0 && strlen($_ShopInfo->getMemgroup())>0 && $_ShopInfo->getMemgroup()!="M") {
	$arr_dctype=array("B"=>"현금","C"=>"카드","N"=>"");
	$sql = "SELECT a.name,b.group_code,b.group_name,b.group_payment,b.group_usemoney,b.group_addmoney ";
	$sql.= "FROM tblmember a, tblmembergroup b WHERE a.id='".$_ShopInfo->getMemid()."' AND b.group_code=a.group_code ";
	$sql.= "AND SUBSTR(b.group_code,1,1)!='M' ";
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		if(file_exists($Dir.DataDir."shopimages/groupimg_{$row->group_code}.gif")) {
			$royal_img="<img src=\"".$Dir.DataDir."shopimages/etc/groupimg_{$row->group_code}.gif\" border=0>";
		} else {
			$royal_img="<img src=\"{$Dir}images/common/group_img.gif\" border=0>\n";
		}
		$royal_msg1="<B>{$row->name}</B>님은 <B><FONT COLOR=\"#EE1A02\">[{$row->group_name}]</FONT></B> 회원입니다.";
		$royal_msg2 = "<B>{$row->name}</B>님이 <FONT COLOR=\"#EE1A02\"><B>".number_format($row->group_usemoney)."원</B></FONT> 이상 {$arr_dctype[$row->group_payment]}구매시,";
		$type=substr($row->group_code,0,2);
		if($type=="RW") $royal_msg2.="적립금에 ".number_format($row->group_addmoney)."원을 <font color=#EE1A02><B>추가 적립</B></font>해 드립니다.";
		else if($type=="RP") $royal_msg2.="구매 적립금의 ".number_format($row->group_addmoney)."배를 <font color=#EE1A02><B>적립</B></font>해 드립니다.";
		else if($type=="SW") $royal_msg2.="구매금액 ".number_format($row->group_addmoney)."원을 <font color=#EE1A02><B>추가 할인</B></font>해 드립니다.";
		else if($type=="SP") $royal_msg2.="구매금액의 ".number_format($row->group_addmoney)."%를 <font color=#EE1A02><B>추가 할인</B></font>해 드립니다.";

		$pattern=array("[ROYAL_IMG]","[ROYAL_MSG1]","[ROYAL_MSG2]");
		$replace=array($royal_img,$royal_msg1,$royal_msg2);
		$royalvalue=str_replace($pattern,$replace,$mainroyal);
	}
	pmysql_free_result($result);
}

if(ord($code)) {
	$basket_shopping=$Dir.FrontDir."productlist.php?code=".substr($code,0,12);
} else {
	$basket_shopping=$Dir.MainDir."main.php";
}
$basket_clear="\"javascript:basket_clear()\"";

if($sumprice==0) {
	$basket_order="\"javascript:alert('장바구니에 담긴 상품이 없습니다.')\"";
} else if ($sumprice>=$_data->bank_miniprice) {
	$basket_order=$Dir.FrontDir."login.php?chUrl=".urlencode($Dir.FrontDir."order.php");
} else {
	$basket_order="\"javascript:alert('주문가능한 최소 금액은 ".number_format($_data->bank_miniprice)."원 입니다')\"";
}
