<?

$subTitle = "�ֹ��ϱ�";
include_once('outline/header_m.php');

include_once($Dir."lib/product.class.php");
$product = new PRODUCT();

if($_data->card_payfee<-50){
	$dc_cash_pay = $_data->card_payfee+50;
	$saletype="Y";
}else{
	$dc_cash_pay = $_data->card_payfee;
}
//phpinfo();
//exdebug($_COOKIE);
//��������
#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
$escrow_info = GetEscrowType($_data->escrow_info);
if(ord($escrow_info["escrow_limit"])==0) $escrow_info["escrow_limit"]=100000;
if(ord($_data->escrow_id) && ($escrow_info["escrowcash"]=="Y" || $escrow_info["escrowcash"]=="A")) {
	$escrowok="Y";
} else {
	$escrowok="N";
	$escrow_info["escrowcash"]="";
	if($escrow_info["onlycash"]!="Y" && (ord($escrow_info["onlycard"])==0 && ord($escrow_info["nopayment"])==0)) $escrow_info["onlycash"]="Y";
}

$arrpayinfo=explode("=",$_data->bank_account);
$bank_payinfo = explode(",", $arrpayinfo[0]);

$cardid_info=GetEscrowType($_data->card_id);
//�������� ����


//��ٱ��� ����Ű Ȯ��
if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
	$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
}

if($_REQUEST[selectItem]){
	$sql = "update tblbasket set tempkey='".$_ShopInfo->getTempkey()."' where tempkey = '".$_ShopInfo->getTempkeySelectItem()."'";
	pmysql_query($sql,get_db_conn());

	$_REQUEST[selectItem]=str_replace("selectItem=","",$_REQUEST[selectItem]);
	$arrSelectItem = explode("|", $_REQUEST[selectItem]);
	foreach($arrSelectItem as $k => $v){
		if($v) $arrItemSetting[] = $v;
	}
	$strWhere = implode("', '", $arrItemSetting);

	$selectItemQuery = "UPDATE tblbasket SET tempkey = '".$_ShopInfo->getTempkeySelectItem()."' WHERE basketidx not in ('".$strWhere."') AND tempkey='".$_ShopInfo->getTempkey()."'";
	pmysql_query($selectItemQuery);
}
if($_REQUEST[allcheck]){
	$sql = "update tblbasket set tempkey='".$_ShopInfo->getTempkey()."' where tempkey = '".$_ShopInfo->getTempkeySelectItem()."'";
	pmysql_query($sql,get_db_conn());

}


$basketsql2 = "SELECT a.productcode,a.package_idx,a.quantity,c.package_list,c.package_title,c.package_price ";
$basketsql2.= "FROM tblbasket AS a, tblproduct AS b, tblproductpackage AS c ";
$basketsql2.= "WHERE a.productcode=b.productcode ";
$basketsql2.= "AND b.package_num=c.num ";
$basketsql2.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
$basketsql2.= "AND a.package_idx>0 ";
$basketsql2.= "AND b.display = 'Y' ";
$basketresult2 = pmysql_query($basketsql2,get_db_conn());
$basketcnt=pmysql_num_rows($basketresult2);

while($basketrow2=@pmysql_fetch_object($basketresult2)) {
	if(ord($basketrow2->package_title) && ord($basketrow2->package_idx) && $basketrow2->package_idx>0) {
		$package_title_exp = explode("",$basketrow2->package_title);
		$package_price_exp = explode("",$basketrow2->package_price);
		$package_list_exp = explode("", $basketrow2->package_list);

		$title_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx] = $package_title_exp[$basketrow2->package_idx];

		if(strlen($package_list_exp[$basketrow2->package_idx])>1) {
			$basketsql3 = "SELECT productcode,quantity,productname,tinyimage,sellprice FROM tblproduct ";
			$basketsql3.= "WHERE pridx IN ('".str_replace(",","','",ltrim($package_list_exp[$basketrow2->package_idx],','))."') ";
			$basketsql3.= "AND display = 'Y' ";

			$basketresult3 = pmysql_query($basketsql3,get_db_conn());
			$sellprice_package_listtmp=0;
			while($basketrow3=@pmysql_fetch_object($basketresult3)) {
				$assemble_proquantity[$basketrow3->productcode]+=$basketrow2->quantity;
				$productcode_package_listtmp[] = $basketrow3->productcode;
				$quantity_package_listtmp[] = $basketrow3->quantity;
				$productname_package_listtmp[] = $basketrow3->productname;
				$tinyimage_package_listtmp[] = $basketrow3->tinyimage;
				$sellprice_package_listtmp+= $basketrow3->sellprice;
			}
			@pmysql_free_result($basketresult3);

			if(count($productcode_package_listtmp)>0) {  //��ٱ��� ��Ű�� ��ǰ ���� ��½� �ʿ��� ����
				$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=0;
				if((int)$sellprice_package_listtmp>0) {
					$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=(int)$sellprice_package_listtmp;
					if(ord($package_price_exp[$basketrow2->package_idx])) {
						$package_price_expexp = explode(",",$package_price_exp[$basketrow2->package_idx]);
						if(ord($package_price_expexp[0]) && $package_price_expexp[0]>0) {
							$sumsellpricecal=0;
							if($package_price_expexp[1]=="Y") {
								$sumsellpricecal = ((int)$sellprice_package_listtmp*$package_price_expexp[0])/100;
							} else {
								$sumsellpricecal = $package_price_expexp[0];
							}
							if($sumsellpricecal>0) {
								if($package_price_expexp[2]=="Y") {
									$sumsellpricecal = $sellprice_package_listtmp-$sumsellpricecal;
								} else {
									$sumsellpricecal = $sellprice_package_listtmp+$sumsellpricecal;
								}
								if($sumsellpricecal>0) {
									if($package_price_expexp[4]=="F") {
										$sumsellpricecal = floor($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									} else if($package_price_expexp[4]=="R") {
										$sumsellpricecal = round($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									} else {
										$sumsellpricecal = ceil($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									}
									$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=$sumsellpricecal;
								}
							}
						}
					}
				}

				$productcode_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $productcode_package_listtmp;
				$quantity_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $quantity_package_listtmp;
				$productname_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $productname_package_listtmp;
				$tinyimage_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $tinyimage_package_listtmp;
			}

			$productcode_package_listtmp=array();
			$quantity_package_listtmp=array();
			$productname_package_listtmp=array();
		}
	}
}
@pmysql_free_result($basketresult2);

#��������ľ�
$errmsg="";
$sql = "SELECT a.quantity as sumquantity,a.opt1_idx, b.productcode,b.productname,b.display,b.quantity, ";
$sql.= "b.option_quantity,b.option_ea,b.etctype,b.group_check,b.assembleuse,a.assemble_list AS basketassemble_list ";
$sql.= ", c.assemble_list,a.package_idx ";
$sql.= "FROM tblbasket a, tblproduct b ";
$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode ";
$result=pmysql_query($sql,get_db_conn());
$assemble_proquantity_cnt=0;
while($row=pmysql_fetch_object($result)) {

	##### �ɼǺ� �Ǹż��� �� ���� ������ ���� ####
	if($row->option_ea && !$row->option_quantity){
		$temp_option_ea = explode(",",$row->option_ea);
		$row->sumquantity = $temp_option_ea[($row->opt1_idx-1)]*$row->sumquantity;
	}

	if($row->display!="Y") {
		$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
	}

	$assemble_list_exp = array();
	if(ord($errmsg)==0 && $row->assembleuse=="Y") { // ����/�ڵ� ��ǰ ��Ͽ� ���� ������ǰ üũ
		if(ord($row->assemble_list)==0) {
			$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
		} else {
			$assemble_list_exp = explode("",$row->basketassemble_list);
		}
	}

	if($row->group_check!="N") {
		if(strlen($_ShopInfo->getMemid())>0) {
			$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
			$sqlgc.= "WHERE productcode='{$row->productcode}' ";
			$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
			$resultgc=pmysql_query($sqlgc,get_db_conn());
			if($rowgc=@pmysql_fetch_object($resultgc)) {
				if($rowgc->groupcheck_count<1) {
					$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
				}
				@pmysql_free_result($resultgc);
			} else {
				$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
			}
		} else {
			$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\\n";
		}
	}

	$package_productcode_tmp = array();
	$package_quantity_tmp = array();
	$package_productname_tmp = array();
	if(ord($errmsg)==0 && $row->package_idx>0) { // ��Ű�� ��ǰ ��Ͽ� ���� ������ǰ üũ
		if(count($productcode_package_list[$row->productcode][$row->package_idx])>0) {
			$package_productcode_tmp = $productcode_package_list[$row->productcode][$row->package_idx];
			$package_quantity_tmp = $quantity_package_list[$row->productcode][$row->package_idx];
			$package_productname_tmp = $productname_package_list[$row->productcode][$row->package_idx];
		}
	}

	if(ord($errmsg)==0) {
		$miniq=1;
		$maxq="?";
		if(ord($row->etctype)) {
			$etctemp = explode("",$row->etctype);
			for($i=0;$i<count($etctemp);$i++) {
				if(strpos($etctemp[$i],"MINIQ=")===0)     $miniq=substr($etctemp[$i],6);
				if(strpos($etctemp[$i],"MAXQ=")===0)      $maxq=substr($etctemp[$i],5);
			}
		}

		if(strlen(dickerview($row->etctype,0,1))>0) {
			$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʽ��ϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
		}
	}

	if(ord($errmsg)==0) {
		if ($miniq!=1 && $miniq>1 && $row->sumquantity<$miniq)
			$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� �ּ� {$miniq}�� �̻� �ֹ��ϼž� �մϴ�.\\n";

		if ($maxq!="?" && $maxq>0 && $row->sumquantity>$maxq)
			$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� �ִ� {$maxq}�� ���Ϸ� �ֹ��ϼž� �մϴ�.\\n";

		if(ord($row->quantity)) {
			if ($row->sumquantity>$row->quantity) {
				if ($row->quantity>0)
					$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$row->quantity} �� �Դϴ�.")."\\n";
				else
					$errmsg.= "[".str_replace("'","",$row->productname)."]��ǰ�� ��� �ٸ��� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
			}
		}
		if($assemble_proquantity_cnt==0) { //�Ϲ� �� ������ǰ���� ��� ��������
			///////////////////////////////// �ڵ�/���� ������� ���� ��� üũ ///////////////////////////////////////////////
			$basketsql = "SELECT productcode,assemble_list,quantity,assemble_idx FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$basketresult = pmysql_query($basketsql,get_db_conn());
			while($basketrow=@pmysql_fetch_object($basketresult)) {
				if($basketrow->assemble_idx>0) {
					if(ord($basketrow->assemble_list)) {
						$assembleprolistexp = explode("",$basketrow->assemble_list);
						for($i=0; $i<count($assembleprolistexp); $i++) {
							if(ord($assembleprolistexp[$i])) {
								$assemble_proquantity[$assembleprolistexp[$i]]+=$basketrow->quantity;
							}
						}
					}
				} else {
					$assemble_proquantity[$basketrow->productcode]+=$basketrow->quantity;
				}
			}
			@pmysql_free_result($basketresult);
			$assemble_proquantity_cnt++;
		}
		if(count($assemble_list_exp)>0) { // ������ǰ�� ��� üũ
			$assemprosql = "SELECT productcode,quantity,productname FROM tblproduct ";
			$assemprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_exp)."') ";
			$assemprosql.= "AND display = 'Y' ";
			$assemproresult=pmysql_query($assemprosql,get_db_conn());
			while($assemprorow=@pmysql_fetch_object($assemproresult)) {
				if(ord($assemprorow->quantity)) {
					if($assemble_proquantity[$assemprorow->productcode]>$assemprorow->quantity) {
						if($assemprorow->quantity>0) {
							$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".str_replace("'","",$assemprorow->productname)."] ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$assemprorow->quantity} �� �Դϴ�.")."\\n";
						} else {
							$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".str_replace("'","",$assemprorow->productname)."] �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
						}
					}
				}
			}
			@pmysql_free_result($assemproresult);
		} else if(count($package_productcode_tmp)>0) {
			//$package_productcode_tmpexp = explode("",$package_productcode_tmp);
			//$package_quantity_tmpexp = explode("",$package_quantity_tmp);
			//$package_productname_tmpexp = explode("",$package_productname_tmp);
			$package_productcode_tmpexp = $package_productcode_tmp;
			$package_quantity_tmpexp = $package_quantity_tmp;
			$package_productname_tmpexp = $package_productname_tmp;
			for($i=0; $i<count($package_productcode_tmpexp); $i++) {
				if(ord($package_productcode_tmpexp[$i])) {
					if(ord($package_quantity_tmpexp[$i])) {
						if($assemble_proquantity[$package_productcode_tmpexp[$i]] > $package_quantity_tmpexp[$i]) {
							if($package_quantity_tmpexp[$i]>0) {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".str_replace("'","",$package_productname_tmpexp[$i])."] ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$package_quantity_tmpexp[$i]} �� �Դϴ�.")."\\n";
							} else {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".str_replace("'","",$package_productname_tmpexp[$i])."] �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
							}
						}
					}
				}
			}
		} else { // �Ϲݻ�ǰ�� ��� üũ
			if(ord($row->quantity)) {
				if($assemble_proquantity[$assemprorow->productcode]>$row->quantity) {
					if ($row->quantity>0) {
						$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$row->quantity} �� �Դϴ�.")."\\n";
					} else {
						$errmsg.= "[".str_replace("'","",$row->productname)."]��ǰ�� ��� �ٸ��� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
					}
				}
			}
		}
		if(ord($row->option_quantity)) {
			$sql = "SELECT opt1_idx, opt2_idx, quantity FROM tblbasket ";
			$sql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$sql.= "AND productcode='{$row->productcode}' ";
			$result2=pmysql_query($sql,get_db_conn());
			while($row2=pmysql_fetch_object($result2)) {
				$optioncnt = explode(",",ltrim($row->option_quantity,','));
				$optionvalue=$optioncnt[($row2->opt2_idx==0?0:($row2->opt2_idx-1))*10+($row2->opt1_idx-1)];

				if($optionvalue<=0 && $optionvalue!="") {
					$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� �ɼ��� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
				} else if($optionvalue<$row2->quantity && $optionvalue!="") {
					$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ���õ� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\\n";
				}
			}
			pmysql_free_result($result2);
		}
	}
}
pmysql_free_result($result);

if(ord($errmsg)) {
	echo "<html></head><body onload=\"alert('{$errmsg}');location.href='basket.php';\"></body></html>";
	exit;
}
$sql = "SELECT b.vender FROM tblbasket a, tblproduct b WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode GROUP BY b.vender ";
$res=pmysql_query($sql,get_db_conn());
$basket_num_cnt=pmysql_num_rows($res);

if($basket_num_cnt=="0"){
	echo "<html></head><body onload=\"alert('������ ��ҵǾ����ϴ�. �ٽ� �ֹ����ֽʽÿ�.');location.href='/m';\"></body></html>";
	exit;
}

$card_miniprice=$_data->card_miniprice;
$deli_area=$_data->deli_area;
$admin_message = $_data->order_msg;
$reserve_limit = $_data->reserve_limit;
$reserve_maxprice = $_data->reserve_maxprice;
$total_reserve = 0; //������������� ����
if($reserve_limit==0) $reserve_limit=1000000000000;
if($_data->rcall_type=="Y") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="N") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="M") {
	$rcall_type="Y";
	$bankreserve="N";
} else {
	$rcall_type="N";
	$bankreserve="N";
}
$etcmessage=explode("=",$admin_message);

$user_reserve=0;
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result = pmysql_query($sql,get_db_conn());
	if($row = pmysql_fetch_object($result)) {

		$reserve_chk= $row->reserve_chk;
		$user_reserve = $row->reserve;
		if($user_reserve>$reserve_limit) {
			$okreserve=$reserve_limit;
			$remainreserve=$user_reserve-$reserve_limit;
		} else {
			$okreserve=$user_reserve;
			$remainreserve=0;
		}
		$home_addr="";
		if(strlen($row->home_post)==6) {
			$home_post1=substr($row->home_post,0,3);
			$home_post2=substr($row->home_post,3,3);
		}
		$row->home_addr = str_replace("\"","",$row->home_addr);
		$home_addr = explode("=",$row->home_addr);
		$home_addr1 = $home_addr[0];
		$home_addr2 = $home_addr[1];

		$office_addr="";
		if(strlen($row->office_post)==6) {
			$office_post1=substr($row->office_post,0,3);
			$office_post2=substr($row->office_post,3,3);
		}
		$row->office_addr = str_replace("\"","",$row->office_addr);
		$office_addr = explode("=",$row->office_addr);
		$office_addr1 = $office_addr[0];
		$office_addr2 = $office_addr[1];

		$name = $row->name;
		$email = $row->email;
		if (ord($row->mobile)) $mobile = $row->mobile;
		else if (ord($row->home_tel)) $mobile = $row->home_tel;
		else if (ord($row->office_tel)) $mobile = $row->office_tel;
		$mobile=explode("-",replace_tel(check_num($mobile)));
		$home_tel=explode("-",replace_tel(check_num($row->home_tel)));

		$group_code=$row->group_code;
		pmysql_free_result($result);
		if(ord($group_code) && $group_code!=NULL) {
			$sql = "SELECT * FROM tblmembergroup WHERE group_code='{$group_code}' AND SUBSTR(group_code,1,1)!='M' ";
			$result=pmysql_query($sql,get_db_conn());
			if($row=pmysql_fetch_object($result)){
				$group_code = $row->group_code;
				$group_level=$row->group_level;
				$group_deli_free=$row->group_deli_free;
				$org_group_name=$row->group_name;  //�׷������� ���� �߰�
				$group_name=$row->group_name;
				$group_type=substr($row->group_code,0,2);
				$group_usemoney=$row->group_usemoney;
				$group_addmoney=$row->group_addmoney;
				$group_payment=$row->group_payment;
				if($group_payment=="B") $group_name.=" (���ݰ�����)";
				else if($group_payment=="C") $group_name.=" (ī�������)";
			}
			pmysql_free_result($result);
		}
	} else {

		$_ShopInfo->setMemid("");
	}
}

$sql = "SELECT privercy FROM tbldesign ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
	$privercy_exp = @explode("=", $row->privercy);
	$privercybody=$privercy_exp[1];
}
pmysql_free_result($result);

if(ord($privercybody)==0) {
	$buffer = file_get_contents($Dir.AdminDir."privercy2.txt");
	$privercybody=$buffer;
}

$pattern=array("[SHOP]","[NAME]","[EMAIL]","[TEL]");
$replace=array($_data->shopname,$_data->privercyname,"<a href=\"mailto:{$_data->privercyemail}\">{$_data->privercyemail}</a>",$_data->info_tel);
$privercybody = str_replace($pattern,$replace,$privercybody);


?>
<script>
<?php if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y"){?>
function coupon_check(temp){
	document.form1.coupon_dc.value=0;
	document.form1.usereserve.value=0;
	$("#ID_coupon_code_layer").html('');
	reserve_check(temp);

	window.open("about:blank","couponpopup","width=650,height=650,toolbar=no,menubar=no,scrollbars=yes,status=no");
	document.couponform.submit();
}
<?php }?>


<?php if(strlen($_ShopInfo->getMemid())>0){?>
function coupon_cancel(){
	$("#ID_coupon_code_layer").html("");
	document.form1.coupon_code.value=0;
	document.form1.coupon_dc.value=0;
	document.form1.coupon_reserve.value=0;

	document.getElementById("sumprice").value=parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value));

	document.getElementById("price_sum").innerHTML=comma(parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value)));

	payment_reset();
}

function reserve_check(temp) {
	r_type="<?=$rcall_type?>";
	
	var total_reserve = 0;
	$("#beforehand_reserve").attr("checked",false);
	
	if(r_type=="N" && document.form1.coupon_code.value){
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('������ �������� ���� ����� �Ұ����մϴ�.');
		//return;
	}
	temp=parseInt(temp);
	if(isNaN(document.form1.usereserve.value)) {
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('���ڸ� �Է��ϼž� �մϴ�.');
		//return;
	}
	if(parseInt(document.form1.usereserve.value)>temp) {
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('��밡�� ������ ���� ���ų� �Ȱ��� �Է��ϼž� �մϴ�.');
		//return;
	}
	if(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value)>parseInt(document.form1.total_sum.value)){
		//document.getElementById("dc_price").innerHTML=comma(parseInt(document.form1.coupon_dc.value));
		document.getElementById("price_sum").innerHTML=comma(parseInt(document.form1.total_sum.value)-parseInt(document.form1.coupon_dc.value));
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('�� �հ� �ݾ� ���� ���ų� �Ȱ��� �Է��ϼž� �մϴ�.');
		//return;
	}
	document.form1.okreserve.value=temp - document.form1.usereserve.value;
	document.form1.usereserve.value=temp - document.form1.okreserve.value;

	//document.getElementById("dc_price").innerHTML=comma(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value));
	
	document.getElementById("sumprice").value=parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value));

	//document.getElementById("sumprice").value=parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value) + parseInt(total_reserve));

	document.getElementById("price_sum").innerHTML=comma(parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value)));

	//document.getElementById("price_sum").innerHTML=comma(parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value) + parseInt(document.form1.usereserve.value) ));

	payment_reset();
}
<?php }?>

function payment_reset(){

	for(var i=0;i<document.getElementsByName("dev_payment").length;i++){
		document.getElementsByName("dev_payment")[i].checked=false;
	}
}

function comma(x)
{
	var temp = "";
	var x = String(uncomma(x));

	num_len = x.length;
	co = 3;
	while (num_len>0){
		num_len = num_len - co;
		if (num_len<0){
			co = num_len + co;
			num_len = 0;
		}
		temp = ","+x.substr(num_len,co)+temp;
	}
	return temp.substr(1);
}
function uncomma(x)
{
	var reg = /(,)*/g;
	x = parseInt(String(x).replace(reg,""));
	return (isNaN(x)) ? 0 : x;
}

$(document).ready(function(){//��������� �߰�
	$("#beforehand_reserve").on("click",function(){
		alert('ok');
		var total_sum = $("input[name='total_sum']").val();
		if($(this).prop("checked")){
			$("#price_sum").html( comma( parseInt(total_sum) - parseInt($("#total_reserve").val()) - parseInt($("#coupon_dc").val()) - parseInt($("#usereserve").val()) ) +'��' );
			$(".CLS_beforehand_reserve").html( comma( parseInt($("#total_reserve").val()) )+'��' );
		}else{
			$("#price_sum").html( comma( parseInt(total_sum) - parseInt($("#coupon_dc").val()) - parseInt($("#usereserve").val()) ) +'��' );
			$(".CLS_beforehand_reserve").html( 0+'��' );
		}
	});
});

$(document).ready(function(){
	/*$(document).click(function(e){
		console.log(e.target);
	});*/
	
	$('.CLS_sameAddress').click(function(){
		if($(this).attr('idx') == '1'){
			document.form1.receiver_name.value=document.form1.sender_name.value;
			document.form1.receiver_tel11.value="<?=$home_tel[0]?>";
			document.form1.receiver_tel12.value="<?=$home_tel[1]?>";
			document.form1.receiver_tel13.value="<?=$home_tel[2]?>";
			document.form1.receiver_tel21.value=document.form1.sender_tel1.value;
			document.form1.receiver_tel22.value=document.form1.sender_tel2.value;
			document.form1.receiver_tel23.value=document.form1.sender_tel3.value;

			document.form1.rpost1.value="<?=$home_post1?>";
			document.form1.rpost2.value="<?=$home_post2?>";
			document.form1.raddr1.value="<?=$home_addr1?>";
			document.form1.raddr2.value="<?=$home_addr2?>";
			$(this).attr('idx', '2');
		}else{
			document.form1.receiver_name.value="";
			document.form1.receiver_tel11.value="";
			document.form1.receiver_tel12.value="";
			document.form1.receiver_tel13.value="";
			document.form1.receiver_tel21.value="";
			document.form1.receiver_tel22.value="";
			document.form1.receiver_tel23.value="";

			document.form1.rpost1.value='';
			document.form1.rpost2.value='';
			document.form1.raddr1.value='';
			document.form1.raddr2.value='';
			$(this).attr('idx', '1');
		}
	})
})

function SameCheck(checked) {
	if(checked) {
		document.form1.receiver_name.value=document.form1.sender_name.value;
		document.form1.receiver_tel11.value="<?=$home_tel[0]?>";
		document.form1.receiver_tel12.value="<?=$home_tel[1]?>";
		document.form1.receiver_tel13.value="<?=$home_tel[2]?>";
		document.form1.receiver_tel21.value=document.form1.sender_tel1.value;
		document.form1.receiver_tel22.value=document.form1.sender_tel2.value;
		document.form1.receiver_tel23.value=document.form1.sender_tel3.value;

		document.form1.rpost1.value="<?=$home_post1?>";
		document.form1.rpost2.value="<?=$home_post2?>";
		document.form1.raddr1.value="<?=$home_addr1?>";
		document.form1.raddr2.value="<?=$home_addr2?>";
	} else {
		document.form1.receiver_name.value="";
		document.form1.receiver_tel11.value="";
		document.form1.receiver_tel12.value="";
		document.form1.receiver_tel13.value="";
		document.form1.receiver_tel21.value="";
		document.form1.receiver_tel22.value="";
		document.form1.receiver_tel23.value="";

		document.form1.rpost1.value='';
		document.form1.rpost2.value='';
		document.form1.raddr1.value='';
		document.form1.raddr2.value='';
	}
}

function get_post() {
	/*window.open("<?=$Dir.FrontDir?>addr_search.php?form=form1&post=rpost&addr=raddr1&gbn=2","f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");*/
	postopen('form1','rpost','raddr','2');
}

function postopen(form,post,addr,gbn){
	var param = "form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn;

	$("#postiframe").attr("src","addr_search_doro.php?"+param);
	$("#zip_view_n").dialog({
		resizable:false,
		width:'90%',
		height:'740',
		modal: true,
		title: "�������� ã��",
		position:{
			my:"center",
			at:"center",
			of:document
		},
	});
}

function postclose(){
	$("#zip_view_n").dialog("close");
	$("#postiframe").attr("src","addr_search_doro.php?form=form1&post=rpost&addr=raddr&gbn=2");
}

function sel_paymethod(obj){

	var frm=document.form1;
	var	totp=uncomma(document.getElementById("price_sum").innerHTML);

	if(obj.value=='B'){
		document.getElementById("card_type").style.display="block";
	}else{
		document.getElementById("card_type").style.display="none";
		frm.pay_data1.value='';
	}

	if(obj.value=='Q'){

		if(frm.escrowcash.value=='Y' && (frm.escrow_limit.value>parseInt(totp))){

			alert('�� �����ݾ���'+frm.escrowcash.value+'�̻��϶��� ����ũ�� ������ �����մϴ�.');
			frm.paymethod.value='';
			obj.checked=false;
			return;
		}
	}

	frm.paymethod.value=obj.value;
}
function ordercancel(gbn) {
	if(gbn=="cancel" && document.form1.process.value=="N") {
		document.location.href="basket.php";
	}
	/*
	else {
		if (PROCESS_IFRAME.chargepop) {
			if (gbn=="cancel") alert("����â�� �������Դϴ�. ����Ͻ÷��� ����â���� ����ϱ⸦ ��������.");
			PROCESS_IFRAME.chargepop.focus();
		} else {
			PROCESS_IFRAME.PaymentOpen();
			ProcessWait('visible');
		}
	}*/
}

function sel_account(obj){

	var frm=document.form1;

	frm.pay_data1.value=obj.value;
}

</script>
<link type="text/css" href="css/nmobile.css" rel="stylesheet">

<script id="delivery"></script>

<main id="content" class="subpage">



<section id="order">
<form id="form" name="form1" action="ordersend_test.php" method="post">
<input type=hidden id="direct_deli" name="direct_deli" value="Y">
<div id="apply_coupon"></div>

<!-- 01 �ֹ������� -->

<?
if( strlen($_ShopInfo->getMemid())==0){
?>
<!-- ��ȸ������ -->

<article class="join_step01 no_member">
<h2>��ȸ�� ����</h2>
<?/*?>
	<section>
		<h3>���� ȸ�� ���</h3>
		<div  class="form">
		<!--{ = include_file( "proc/_agreement.txt" ) }-->
		</div>
		<div class="RadioCheckbox">
			<p><input name="order_private" id="order_private" type="checkbox" value="y" style="vertical-align:middle" required msgR="�����̿����� ���Ǹ� �ϼž߸� �ֹ��� �����մϴ�."/> <label for="order_private">�̿����� Ȯ���Ͽ����� ���뿡 �����մϴ�.</label></p>
		</div>
	</section>
	<?*/?>
	<section>
		<h3>������������ �� �̿뿡 ���� �ȳ�</h3>
		<div  class="form">
		<?=$privercybody?>
		</div>
		<div class="RadioCheckbox">
			<p><input id=idx_dongiY name=dongi value="Y" type="checkbox" style="vertical-align:middle" required msgR="�����̿����� ���Ǹ� �ϼž߸� �ֹ��� �����մϴ�."/> <label for="order_private">�̿����� Ȯ���Ͽ����� ���뿡 �����մϴ�.</label></p>
		</div>
	</section>
</article>

<?}?>
<article class="order_wrap" >
<section class="cart_list">
<h3>- �ֹ���ǰ ����Ʈ ���</h3>
	<table>
		<caption class="hide">�ֹ���ǰ ����Ʈ ���</caption>
		<colgroup><col width="25%" /><col width="75%" /></colgroup>
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


		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.productcode,b.productname,b.sellprice,b.membergrpdc, b.option_reserve, b.consumerprice, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, ";
		$sql.= "b.etctype,b.deli_price,b.deli,b.sellprice*a.quantity as realprice, b.selfcode,a.assemble_list,a.assemble_idx,a.package_idx ";
		$sql.= "FROM tblbasket a, tblproduct b WHERE b.vender='".$vgrp->vender."' ";
		$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
		$sql.= "AND a.productcode=b.productcode ";
		//$sql.= "AND a.ord_state=true ";
		$sql.= "ORDER BY a.date DESC ";


		$result=pmysql_query($sql,get_db_conn());

		$mem_dc_price=0;  //ȸ����޿� ���� ���ΰ�
		$vender_sumprice = 0;
		$vender_delisumprice = 0;//�ش� ������ü�� �⺻��ۺ� �� ���ž�
		$vender_deliprice = 0;
		$deli_productprice=0;
		$deli_init = false;
		$total_reserve =0;//���������ɺ���
		while($row = pmysql_fetch_object($result)) {
			/*$groupPriceList = $product->getProductGroupPrice($row->productcode);
			if ($groupPriceList) { // �Ϲ� �� ����ȸ�� �ݾ� ���ý� �α��� �Ǿ��մ� user ��޿� ���� �Ǹ� �ݾ� ����
				$row->sellprice = $groupPriceList[sellprice];
				$row->consumerprice = $groupPriceList[consumerprice];
				$row->consumer_reserve = $groupPriceList[consumer_reserve];
			}
			//ȯ������
			$row->sellprice = exchageRate($row->sellprice);
			$row->consumerprice = exchageRate($row->consumerprice);
			$row->consumer_reserve = exchageRate($row->consumer_reserve);
			$row->realprice = exchageRate($row->realprice);
			*/

			$total_reserve += getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");

			if (strlen($row->option_price)>0 && $row->opt1_idx==0) {
				$sql = "DELETE FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
				$sql.= "AND productcode='".$row->productcode."' AND opt1_idx='".$row->opt1_idx."' ";
				$sql.= "AND opt2_idx='".$row->opt2_idx."' AND optidxs='".$row->optidxs."' ";
				pmysql_query($sql,get_db_conn());
				alert_go("�ʼ� ���� �ɼ� �׸��� �ֽ��ϴ�.\\n�ɼ��� �����Ͻ��� ��ٱ��Ͽ�\\n�����ñ� �ٶ��ϴ�.",$Dir.FrontDir."productdetail.php?productcode=".$row->productcode);
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
								alert_go("�ʼ� ���� �ɼ� �׸��� �ֽ��ϴ�.\\n�ɼ��� �����Ͻ��� ��ٱ��Ͽ�\\n�����ñ� �ٶ��ϴ�.",$Dir.FrontDir."productdetail.php?productcode=".$row->productcode);
							}
							if($exoptcode[$opti]>0){
								$opval = explode("",str_replace('"','',$optionadd[$opti]));
								$optvalue.= ", ".$opval[0]." : ";
								$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
								if ($exop[1]>0) $optvalue.=$exop[0]."(<font color=#FF3C00>+".number_format($exop[1])."��</font>)";
								else if($exop[1]==0) $optvalue.=$exop[0];
								else $optvalue.=$exop[0]."(<font color=#FF3C00>".number_format($exop[1])."��</font>)";
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

			//	$assemble_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
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
					$assemble_str.="				<td align=\"right\" style=\"padding:4px;border-left:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\"><font color=\"#000000\">".number_format((int)$alprorow->sellprice)."��</font></td>\n";
					$assemble_str.="				<td align=\"center\" style=\"padding:4px;\">�� ��ǰ 1���� ����1��</td>\n";
					$assemble_str.="			</tr>\n";
					$assemble_str.="			</table>\n";
					$assemble_str.="			</td>\n";
					$assemble_str.="		</tr>\n";
					$assemble_sellerprice+=$alprorow->sellprice;
				}
				@pmysql_free_result($alproresult);
				$assemble_str.="		</table>\n";
				$assemble_str.="		</td>\n";

				//######### �ڵ�/������ ���� ���� ���� üũ ###############
				$price = $assemble_sellerprice*$row->quantity;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$assemble_sellerprice,"N");
				$sellprice=$assemble_sellerprice;
			} else if($row->package_idx>0 && strlen($row->package_idx)>0) {
				$package_str ="<a href=\"javascript:setPackageShow('packageidx".$cnt."');\">".$title_package_listtmp[$row->productcode][$row->package_idx]."(<font color=#FF3C00>+".number_format($price_package_listtmp[$row->productcode][$row->package_idx])."��</font>)</a>";

				$productname_package_list_exp = $productname_package_list[$row->productcode][$row->package_idx];
				if(count($productname_package_list_exp)>0) {
					//$packagelist_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
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
						$packagelist_str.="				<td align=\"center\" style=\"padding:4px;border-left:1px #DDDDDD solid;\">�� ��ǰ 1���� ����1��</td>\n";
						$packagelist_str.="			</tr>\n";
						$packagelist_str.="			</table>\n";
						$packagelist_str.="			</td>\n";
						$packagelist_str.="		</tr>\n";
					}
					$packagelist_str.="		</table>\n";
					$packagelist_str.="		</td>\n";
				} else {
					//$packagelist_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
					$packagelist_str.="		<td width=\"100%\">\n";
					$packagelist_str.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
					$packagelist_str.="		<tr>\n";
					$packagelist_str.="			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;padding:4px;word-break:break-all;\"><font color=\"#000000\">������ǰ�� �������� �ʴ� ��Ű��</font></td>\n";
					$packagelist_str.="		</tr>\n";
					$packagelist_str.="		</table>\n";
					$packagelist_str.="		</td>\n";
				}
				//######### �ɼǿ� ���� ���� ���� üũ ###############
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
				//######### �ɼǿ� ���� ���� ���� üũ ###############
				if (strlen($row->option_price)==0) {
					$price = $row->realprice;
					$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
					$sellprice=$row->sellprice;
				} else if (strlen($row->opt1_idx)>0) {
					$option_price = $row->option_price;
					$pricetok=explode(",",$option_price);
					$priceindex = count($pricetok);
					$price = $pricetok[$row->opt1_idx-1]+$row->quantity;
					$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
					$sellprice=$row->sellprice+$pricetok[$row->opt1_idx-1];
				}
			}

			### Ÿ�� ���� / ������ Ư�� �������� �� ����
			$timesale_sellprice = 0;
			$timesale_sellprice = getSpeDcPrice($row->productcode);
			if($timesale_sellprice > 0) $sellprice = $timesale_sellprice;

			//######### ��ǰ Ư�����η� ���� ############
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

			//######### �ɼǺ� ������ ���� ############
			$option_reserve = explode(',',$row->option_reserve);

			if($option_reserve[$row->opt1_idx-1]>0){
				$tempreserve=$option_reserve[$row->opt1_idx-1];
			}

			//ȸ�� ������ ����
			$before_sellprice=$sellprice;
			$bf_price = $before_sellprice*$row->quantity;
			$sellprice=$sellprice-$salemoney;
			$price = $sellprice*$row->quantity;

			//�߰� ������ ����
			$tempreserve+=$salereserve;

			if($row->consumerprice > $before_sellprice){
				$salemoney+=$row->consumerprice-$before_sellprice;
				$before_sellprice=$row->consumerprice;
			}

			//��ȸ���̸� ������ ���� X
			if(strlen($_ShopInfo->getMemgroup())==0) $tempreserve=0;

			//$bf_sumprice += $row->sellprice*$row->quantity;
			$bf_sumprice += $sellprice*$row->quantity;
			$sumprice += $price;
			$vender_sumprice += $price;

			$mem_dc_price += $salemoney*$row->quantity;

			$deli_str = "";
			if (($row->deli=="Y" || $row->deli=="N") && $row->deli_price>0) {
				if($row->deli=="Y") {
					$deli_productprice += $row->deli_price*$row->quantity;
					$deli_str = "&nbsp;<font color=a00000>- ������ۺ�<font color=#FF3C00>(���ż� ��� ����:".number_format($row->deli_price*$row->quantity)."��)</font></font>";
				} else {
					$deli_productprice += $row->deli_price;
					$deli_str = "&nbsp;<font color=a00000>- ������ۺ�<font color=#FF3C00>(".number_format($row->deli_price)."��)</font></font>";
				}
			} else if($row->deli=="F" || $row->deli=="G") {
				$deli_productprice += 0;
				if($row->deli=="F") {
					$deli_str = "&nbsp;<font color=a00000>- ������ۺ�<font color=#0000FF>(����)</font></font>";
				} else {
					$deli_str = "&nbsp;<font color=a00000>- ������ۺ�<font color=#38A422>(����)</font></font>";
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
		<tr>
			<td class="thumb">
			<!--
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
?>-->
			<? if(strlen($row->tinyimage)>0 && file_exists("../data/shopimages/product/".$row->tinyimage)) {?>
				<img src="<?="../data/shopimages/product/".$row->tinyimage?>" border="0">
			<?	} else {?>
				<img src="<?=$Dir?>images/no_img.gif" border="0">
			<?	}?>
			</td>
			<td class="left" style="position:relative;">
				<span class="name"><b><?=viewproductname($productname,$row->etctype,$row->selfcode,$row->addcode) ?></b><?=$bankonly_html?><?=$setquota_html?><?=$deli_str?></span>
				<?if (strlen($row->option1)>0 || strlen($row->option2)>0 || strlen($optvalue)>0) {?>
				<span>
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
				?>
				</span>
				<?
				}
				?>
				<div class="order_price"><span class="quantity">���� : <?=$row->quantity?>��</span> <span  class="price">�ݾ� : <em><?=number_format($sellprice*$row->quantity) ?>��</em></span></div>
			</td>
		</tr>
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
	}
	//�׷��ۺ� ����ó��
	if($group_deli_free=='1'){
		$deli_price=0;
	}

	pmysql_free_result($res);

	if ($cnt!=$setquotacnt && $setquotacnt>0 && $_data->card_splittype=="O") {
		echo "<script> alert('[�ȳ�] �����������ǰ�� �Ϲݻ�ǰ�� ���� �ֹ��� �������Һ������� �ȵ˴ϴ�.');</script>";
	}
?>
	</table>
</section>

<article class="order_step_tap" id="orderStep1">
	<section>
		<ul>
		    <li><a href="" class="on">�ֹ�������</a></li>
			<li><a href="#orderStep2">���������</a></li>
			<li><a href="#orderStep3">�����ݾ�Ȯ��</a></li>
			<li><a href="#orderStep4">�����������</a></li>
		</ul>
	</section>
</article>
<section>
	<div  class="delivery">
		<div  class="member_info">
			<table>
				<caption class="hide">������� Ȯ�� ���̺�</caption>
				<colgroup><col width="20%" /><col width="*" /></colgroup>
				<tr>
					<th scope="row">�ֹ���</th>
					<td><input type="text" name="sender_name" value="<?=$name?>" style="float:left"></td>
				</tr>
				<tr>
					<th scope="row">�̸���</th>
					<td><input type="text" name="sender_email" value="<?=$email?>" style="float:left" /></td>
				</tr>
				<tr>
					<th scope="row">�ڵ��� ��ȣ</th>
					<td>
						<input type="tel" name="sender_tel1" value="<?=$mobile[0] ?>" size=3 maxlength=3 required style="float:left"/><input type="text" readonly value="-" style="float:left;border:0;width:5px;">
						<input type="tel" name="sender_tel2" value="<?=$mobile[1] ?>" size=4 maxlength=4 required style="float:left"/><input type="text" readonly value="-" style="float:left;border:0;width:5px;">
						<input type="tel" name="sender_tel3" value="<?=$mobile[2] ?>" size=4 maxlength=4 required style="float:left"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
</section>

<article class="order_step_tap" id="orderStep2">
	<section>
		<ul>
			<li><a href="#orderStep1">�ֹ�������</a></li>
			<li><a href="#orderStep2" class="on">���������</a></li>
			<li><a href="#orderStep3">�����ݾ�Ȯ��</a></li>
			<li><a href="#orderStep4">�����������</a></li>
		</ul>
	</section>
</article>
<style>
#zipcode_layer{
	z-index:10000;
	display:none;
	border:2px solid;
	position:absolute;
	height:;
	margin: 5px 0 5px 5px;
	overflow:hidden;
	-webkit-overflow-scrolling:touch;
}

#btnCloseLayer{
	position: absolute;
	right: 2%;
	top: 2%;
	z-index: 100;
}

input.cbtn {
  display: inline-block;
  height: 25px;
  padding: 0 14px 0;
  background-color: #3c77eb;
  font-size: 12px;
  color: #fff !important;
  line-height: 25px;
  border-radius: 3px;
}

</style>

<div id="zipcode_layer" style="color:black;">
<!--<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode()" alt="�ݱ� ��ư">-->
<input type="button" id="btnCloseLayer" onclick="closeDaumPostcode()" class="cbtn" value="�ݱ�" alt="�ݱ� ��ư">
</div>

<script src="http://dmaps.daum.net/map_js_init/postcode.js"></script>
<script>
	var element = document.getElementById('zipcode_layer');
	function closeDaumPostcode() {
		  element.style.display = 'none';

	}

	function showPostcode() {
		var winWidth = $(window).width();
		var winHeight = $(window).height();
		$("#zipcode_layer").css("width",winWidth-20);
		$("#zipcode_layer").css("height",winHeight-(winHeight/2));
		new daum.Postcode({
			oncomplete: function(data) {
				document.getElementById('rpost1').value = data.postcode1;
				document.getElementById('rpost2').value = data.postcode2;
				document.getElementById('raddr1').value = data.address;
				document.getElementById('raddr2').focus();
				element.style.display = 'none';
			},
			onresize : function(size){


			},
			width : '100%',
			height : '125%'
		}).embed(element);
		element.style.display = 'block';
	}
	$(window).resize(function(){
		var winWidth = $(window).width();
		var winHeight = $(window).height();
		$("#zipcode_layer").css("width",winWidth-20);
		$("#zipcode_layer").css("height",winHeight-(winHeight/2));
	});
</script>
<article class="order_wrap" >
	<section>
		<div  class="delivery">
			<div  class="member_info">
				<table>
					<caption class="hide">������� Ȯ�� ���̺�</caption>
					<colgroup><col width="20%" /><col width="*" /></colgroup>
					<tr>
						<th scope="row">�޴� ���</th>
						<td>
							<ul class="ul_zipcode">
								<li><input type="text" name="receiver_name" value="" required style="float:left"/> <a href="javascript:;" class="btn_a CLS_sameAddress" idx = '1' style="float:left">�ֹ��� �ּҿ� ����</a></li>
							</ul>
							<!--span class="ad"><input type="checkbox" name="same" value="Y" onclick="SameCheck(this.checked)"> &nbsp;&nbsp;<font for="same">�ֹ��� �ּҿ� ����</font></span-->
						</td>
					</tr>
					<tr>
						<th scope="row">������ �ּ�</th>
						<td>
							<div id="zipcode_list" style="position:relative;">
								<input type="text" name="rpost1" id="rpost1" size=3 readonly value="" required label="�����ȣ" style="float:left"/><input type="text" readonly value="-" style="float:left;border:0;width:5px;">
								<input type="text" name="rpost2" id="rpost2" size=3 readonly value="" required label="�����ȣ" style="float:left"/>
								<ul class="ul_zipcode" style="position:absolute;top:0;left:100px">
									<li><a href="javascript:showPostcode();" class="btn_a" style="float:left;">���������˻�</a></li>
								</ul>
								<div class="inprow address" style="height: 0px">
									<input type="text" name="raddr1" id="raddr1" readonly value="" required label="������ �ּ�" style="float:left"/>
									<input type="text" name="raddr2" id="raddr2" value="" required label="�����ּ�" style="float:left"/>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">��ȭ��ȣ</th>
						<td>
							<input type="tel" name="receiver_tel11" value="" size=3 maxlength=3 required onKeyUp="strnumkeyup(this)" style="float:left"/><input type="text" readonly value="-" style="float:left;border:0;width:5px;">
							<input type="tel" name="receiver_tel12" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)" style="float:left"/><input type="text" readonly value="-" style="float:left;border:0;width:5px;">
							<input type="tel" name="receiver_tel13" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)" style="float:left"/>
						</td>
					</tr>
					<tr>
						<th scope="row">�ڵ��� ��ȣ</th>
						<td>
							<input type="tel" name="receiver_tel21" value="" size=3 maxlength=3 required onKeyUp="strnumkeyup(this)" style="float:left"/><input type="text" readonly value="-" style="float:left;border:0;width:5px;">
							<input type="tel" name="receiver_tel22" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)" style="float:left"/><input type="text" readonly value="-" style="float:left;border:0;width:5px;">
							<input type="tel" name="receiver_tel23" value="" size=4 maxlength=4 required onKeyUp="strnumkeyup(this)" style="float:left"/>
						</td>
					</tr>
					<tr>
						<th scope="row">��û����</th>
						<td>
							<div class="inprow address">
								<input type=hidden name=msg_type value="1">
								<input  type="text" name="order_prmsg">
								<input type="hidden" id="deli_type" name="deli_type" value="0">
							</div>
						</td>
					</tr>
					<!--tr>
					<th scope="row">��ۼ���</th>
					<td>
					<div id="paper_delivery_menu">

					<div><input type="radio" name="deli_type" class="deli_type" value="0" checked> �⺻���&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="deli_type" class="deli_type" value="1">�߰��ֹ�/�������</div>

					</div>
					</td>
					</tr-->
				</table>
			</div>
		</div>
	</section>
</article>

<article class="order_step_tap" id="orderStep3">
	<section>
		<ul>
			<li><a href="#orderStep1">�ֹ�������</a></li>
			<li><a href="#orderStep2">���������</a></li>
			<li><a href="#orderStep3" class="on">�����ݾ�Ȯ��</a></li>
			<li><a href="#orderStep4">�����������</a></li>
		</ul>
	</section>
</article>
<?
$p_price=$sumprice+$deli_price+$sumpricevat;


list($group_name)=pmysql_fetch_array(pmysql_query("select group_name from tblmembergroup where group_code='".$_ShopInfo->memgroup."'"));
?>

<input type="hidden" name="total_sum" value="<?=$p_price?>">
<section class="cart_list">
	<ul class="goods_price">
		<li><span class="title">��ǰ�ݾ��հ�</span> <span class="price" id="paper_goodsprice"><?=number_format($bf_sumprice)?><em>��</em></span></li>
		<!--<li><span class="title">ȸ����ǰ����(<em class="no4"><?=$group_name?$group_name:"��"?></em> ȸ��)</span> <span class="price no2" id='memberdc'><?=number_format($mem_dc_price)?><em>��</em></span></li>-->
		<li><span class="title">��ۺ��հ�</span> <div id="paper_delivery_msg1"><span class="price no3"><font id="delivery_price"><?=number_format($deli_price)?></font><em>��</em></span></div><div id="paper_delivery_msg2"></div></li>
	</ul>

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
    <div class="delivery">
		<div id = "ID_coupon_code_layer"></div>
		<div class="point coupon">
			<span class="txt">�������� </span>
			<input type=hidden name="coupon_code" size="19" readonly style="BACKGROUND-COLOR:#F7F7F7;" class="input">
			<input type=hidden name="bank_only" value="N">
			<?if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y"){?>
			<a href="javascript:coupon_check(<?=$okreserve?>);" class="apply_coupon">
				<input type="button" value="��ȸ" class="apply_coupon"/>
			</a>
			<input type="button" value="���" onclick="javascript:coupon_cancel();"/>
			<?}?>
			<div class="inp">
				 <p><label>����</label><input type="text" name=coupon_dc id="coupon_dc" value="0" readonly/>&nbsp; ��</p>
				 <p><label>����</label><input type="text" name=coupon_reserve value="0" readonly/>&nbsp; ��</p>
			</div>
		</div>
		<div id="coupon_list"></div>
		<div class="point">
			<span class="txt">������ ���� <em>( ��밡�������� : <?=number_format($okreserve)?>�� ) </em></span>
		<!--div class="inp">
					 <p><input type="text" name=coupon_reserve value="0" readonly/>&nbsp; ��</p>
			</div-->

				<?
					if($reserve_chk=='1' || ($okreserve+$remainreserve)>=3000){
						if($reserve_maxprice>$sumprice) {
					?>
							<p class="info">�ر��űݾ��� <?=number_format($reserve_maxprice)?>�� �̻��̸� ��밡���մϴ�.</p>
							<input type=hidden name="okreserve" value="<?=$okreserve?>" >
							<input type="hidden " name="usereserve" id="usereserve" value=0>
					<?  }else if($user_reserve>=$_data->reserve_maxuse){?>
							<div class="inp">
								<input type="text" name="usereserve" id="usereserve" size="7" style="text-align:right" value="0" onkeyup="reserve_check('<?=$okreserve?>');" onkeydown="if (event.keyCode == 13) {return false;}"> &nbsp; ��
							</div>
							<input type=hidden name="okreserve" value="<?=$okreserve?>" >
							<?if($user_reserve>$reserve_limit){?>
								<input type=hidden name="remainreserve" value="<?=$remainreserve?>" >
							<?}?>
					<?	}else{?>
							<input type=hidden name="okreserve" value="<?=$okreserve?>" >
							<input type="hidden"  name="usereserve" id="usereserve" value=0>
							<p class="info">�غ����������� <?=number_format($_data->reserve_maxuse)?>���̻� �� ��� ����Ͻ� �� �ֽ��ϴ�.(�� ������ <?=number_format($remainreserve+$okreserve)?>��)</p>
					<?
						}
					}else{
					?>
						<input type=hidden name="okreserve" value="<?=$okreserve?>" >
						<input type="hidden"  name="usereserve" id="usereserve" value=0>
					<?
					}
				?>

		</div>

		<div class="point">
			<span class="txt">��� ����</span>
			<input type="checkbox" name="beforehand_reserve" id="beforehand_reserve" value="Y"/>&nbsp *������ �������� ��� ����ϴ� ����Դϴ�
			<input type="hidden" id="total_reserve" value="<?=$total_reserve?>">
		</div>
	</div>

	<ul class="goods_price" style="border-top:1px solid #ccc">
	<li class="payment_total"><span class="title">�� �����ݾ�</span> <span class="price_total"  id="price_sum"><?=number_format($sumprice+$deli_price+$sumpricevat)?>��</span></li>
	<!--li><span class="title">��ǰ�ݾ��հ�</span> <span class="price" id="paper_goodsprice">{=number_format(cart->goodsprice)}<em>��</em></span></li>
	<li><span class="title">ȸ�� ����</span> <span class="price no2" id='memberdc'>{=number_format(cart->dcprice)}<em>��</em></span></li>
	<li><span class="title">������ ���</span> <span class="price no2" id='memberdc'>0000<em>��</em></span></li>
	<li><span class="title">��ۺ��հ�</span> <div id="paper_delivery_msg1"><span class="price no3"><font id="paper_delivery"></font><em>��</em></span></div><div id="paper_delivery_msg2"></div></li>
	<li><span class="title">�� �����Ͻ� �ݾ�(��ۺ�:<em class="deliy_type">���� </em>)</span> <span class="price_total" id=paper_settlement></span><em>��</em></li-->
   </ul>
</section>



<article class="order_step_tap" id="orderStep4">
	<section>
		<ul>
			<li><a href="#orderStep1">�ֹ�������</a></li>
			<li><a href="#orderStep2">���������</a></li>
			<li><a href="#orderStep3">�����ݾ�Ȯ��</a></li>
			<li><a href="#orderStep4" class="on">�����������</a></li>
		</ul>
	</section>
</article>

<section>
	<div  class="delivery">
		<div  class="member_info">
		 <table>
		  <caption class="hide">����������� ���̺�</caption>
			<colgroup>
			<col width="20%" />
			<col width="*" />
			</colgroup>
			  <tr>
				<th scope="row"">�������</th>
				<td>
					<div  class="delivery">
					  <div  class="member_info">
					   <input type="hidden" name="escrow" value="N" />
					   <ul class="payment_list">
					   			<?
									//������
									if($escrow_info["onlycard"]!="Y" ) {
										if(strstr("YN", $_data->payment_type)) {//��������� ������ OR �¶��ΰ����� ���õǾ��� ���
								?>
										<li><input id="dev_payment1" class="dev_payment" name="dev_payment" type="radio" value="B" onclick="sel_paymethod(this);" /><label for="dev_payment1">�������Ա�</label></li>
								<?
										}
									}
									//�ſ�ī��
									if(strstr("YC", $_data->payment_type) && ord($_data->card_id)) {
								?>
										<li><input id="dev_payment2" class="dev_payment" name="dev_payment" type="radio" value="C" onclick="sel_paymethod(this);" /><label for="dev_payment2">�ſ�ī��</label></li>
								<?
									}
									/*
									//�ǽð�������ü
									if($escrow_info["onlycard"]!="Y"&&!strstr($_SERVER["HTTP_USER_AGENT"],'Mobile')&&!strstr($_SERVER[HTTP_USER_AGENT],"Android")){
										if(ord($_data->trans_id)) {

								?>
										<li><input id="dev_payment3" class="dev_payment" name="dev_payment" type="radio" value="V" onclick="sel_paymethod(this);" /><label for="dev_payment3">������ü</label></li>
								<?
										}
									}
									*/
									//�������
									if($escrow_info["onlycard"]!="Y" ) {
										if(ord($_data->virtual_id)) {
								?>
										<li><input id="dev_payment5" class="dev_payment" name="dev_payment" type="radio" value="O" onclick="sel_paymethod(this);" /><label for="dev_payment5">�������</label></li>
								<?
										}
									}
								?>
								<!--
								<?

									//����ũ��
									if(($escrow_info["escrowcash"]=="A" || ($escrow_info["escrowcash"]=="Y" && (int)$chk_total_price>=$escrow_info["escrow_limit"])) && ord($_data->escrow_id)) {
										$pgid_info="";
										$pg_type="";
										$pgid_info=GetEscrowType($_data->escrow_id);
										$pg_type=trim($pgid_info["PG"]);
										if(strstr("ABCD",$pg_type)) {
								?>
										<li><input id="dev_payment6" class="dev_payment" name="dev_payment" type="radio" value="Q" onclick="sel_paymethod(this);" /><label for="dev_payment6">����ũ��</label></li>
								<?
										}
									}
								?>
								-->
								<!--
								<?if(ord($_data->mobile_id)) {?>
										<li><input id="dev_payment4" class="dev_payment" name="dev_payment" type="radio" value="M" onclick="sel_paymethod(this);" /><label for="dev_payment4">�޴���</label></li>
								<?}?>
								-->
					   </ul>
					   </div>
					</div>

				</td>
			  </tr>
			  
			  <!--��۹�� ���� ���� 150825���� -->
			
				<tr>
					<th scope="row"">�������</th>
					<td>
						<div  class="delivery">
							<div  class="member_info">
								<input type="hidden" >
								<ul class="payment_list">
					   				<li><input type=radio name=delivery checked value="1">�ù�</li>
									<li><input type=radio name=delivery value="2">��������</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			
			  <tr>
				<td colspan="2" style="border-bottom:0">
					<div class="card_type" id="card_type" style="display:none">
						<div class="table_style">
						<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="�ӱݰ��¸� ����" style="text-align:left">
							<colgroup>
								<?if($etcmessage[2]=="Y") {?><col width="20%" /><?}?>
								<col />
							</colgroup>
							<?if($etcmessage[2]=="Y") {?>
							<tr>
								<th>�Ա��ڸ�</th>
								<td>
									<input type="text" name="bank_sender" value="" >
								</td>
							</tr>
							<?}?>
							<tr>
								<th>�Աݰ���</th>
								<td>
									<select name="pay_data_sel" id="pay_data_sel" onchange="sel_account(this)" style="width:100%">
										<?
										if(ord($arrpayinfo[1])==0) echo "<option value='' >�Ա� ���¹�ȣ ���� (�ݵ�� �ֹ��� �������� �Ա�)</option>";
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
								<th>��������û</th>
								<td>
								<input type="radio" name="receipt_yn" id="receipt_yn1" class="receipt_yn" value="N" checked> <label label for="receipt_yn1" style="font-weight:bold; font-size:12px;">�̽�û</label> ( ���� ������ �����û�� �� �����ݾ��� 10%(�ΰ���)�� �߻��˴ϴ� )<br>
								<input type="radio" name="receipt_yn" id="receipt_yn2" class="receipt_yn" value="Y"> <label for="receipt_yn2"  style="font-weight:bold; font-size:12px;">��û</label> ( ������ �߱޽� �� ���ξ׿� -7% �� �˴ϴ�. )
								</td>
							</tr>
							<?}?>
						</table>
						<?
							if(abs($dc_cash_pay)){

								$dc_cash_pay=abs($dc_cash_pay);
								if($saletype=="Y") $dc_cash_pay_type='����';
								else $dc_cash_pay_type='����';
						?>
							<span class="small_red">�ع����� ������ <?=$dc_cash_pay?>%�� �߰�<?=$dc_cash_pay_type?> �˴ϴ�.</span>
						<?
							}
						?>
						</div>
					</div>

				</td>
			</tr>
			  <tr>
			  	<td colspan=2  style="border-bottom:0">
					<div class="payment_info">�� (�������Ա��� ��� �Ա�Ȯ�� �ĺ��� ��۴ܰ谡 ����˴ϴ�)</div>
					<div class="payment_info viewIphoneOnly">�� <em>���� -> ��޼��� -> �˾� ���� -> ����</em>���� �����ϼž� ī��/������� ������ �����մϴ�.</div>
					<div class="payment_info viewIphoneOnly">�� IOS7���� �̻��� ��� <em>���� -> Safari -> ��Ű ���� -> ����</em>���� �����ϼž� ī��/������� ������ �����մϴ�.</div>
				</td>
			  </tr>
			</table>
		</div>
	</div>
  </section>
</main>

  <section class="btn_zone" style="margin-top:0">
	<ul class="btn_01">
		<li><a href="javascript:CheckForm();" class="cart_order" style="background:rgb(110,110,110);color:#FFFFFF;">�����ϱ�</a></li>
		<li><a href="javascript:ordercancel('cancel');" class="cart_empty" style="color: #FFFFFF;">����ϱ�</a></li>
	</ul>

</section>




<!--{ ? _pg_mobile.receipt=='Y' && _set.receipt.order=='Y' }-->
<!-- 05 ���ݿ��������� -->
<div  id="cash">
<hr class="wline" />

<fieldset>
<legend>- ���ݿ���������</legend>
	<!--{ = include_file( "proc/_cashreceiptOrder.htm" ) }-->
</fieldset>
</div>
<!--{ / }-->
<input type=hidden name=process id=process value="N">
<input type=hidden name=escrow_limit value="<?=$escrow_info["escrow_limit"]?>">
<input type=hidden name=escrowcash value="<?=$escrow_info["escrowcash"]?>">
<input type=hidden name=paymethod>
<!--input type=text name=bank_sender-->
<input type=hidden name=pay_data1>
<input type=hidden name=pay_data2>
<input type=hidden name=sender_resno>
<input type=hidden name=sender_tel>
<input type=hidden name=receiver_tel1>
<input type=hidden name=receiver_tel2>
<input type=hidden name=receiver_addr>
<input type=hidden name=order_msg>
<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
<input type=hidden name=shopurl value="<?=$_SERVER['HTTP_HOST']?>">
<?php }?>

</form>

</section>

<form name=couponform action="coupon_m.php" method='post' target='couponpopup'>
<input type=hidden name=sumprice id="sumprice" value="<?=$sumprice?>">
<input type=hidden name=chk_mobile value="true">
</form>



<div id=dynamic></div>

<div class="modalpop zip_view" id="zip_view_n" style="display: none;">
	<iframe src="popup_zipcode.php?form=form1&post=rpost&addr=rpost1&address1=raddr1&address2=raddr2&gbn=2" id="postiframe" style="width:100%; height:100%; border:none" frameborder="no"></iframe>
</div>

<script>

function CheckForm() {

	//alert("ok");
	paymethod=document.form1.paymethod.value.substring(0,1);



	<?php  if(strlen($_ShopInfo->getMemid())==0) { ?>
	if(document.form1.dongi.checked!=true) {
		alert("����������ȣ��å�� �����ϼž� ��ȸ�� �ֹ��� �����մϴ�.");
		document.form1.dongi.focus();
		return;
	}
	if(document.form1.sender_name.type=="text") {
		if(document.form1.sender_name.value.length==0) {
			alert("�ֹ��� ������ �Է��ϼ���.");
			document.form1.sender_name.focus();
			return;
		}
		if(!chkNoChar(document.form1.sender_name.value)) {
			alert("�ֹ��� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
			document.form1.sender_name.focus();
			return;
		}
	}
	<?php  } ?>

	ispaymentcheck=false;
	for(i=0;i<document.form1.dev_payment.length;i++) {
		if(document.form1.dev_payment[i].checked) {
			ispaymentcheck=true;
			break;
		}
	}
	if(ispaymentcheck==false) {
		alert("��������� �����ϼ���.");
		document.form1.paymethod.value="";
		return;
	}


	if(document.form1.paymethod.value=='B' && document.form1.bank_sender.value==''){
		alert("�Ա��ڸ��� �Է��ϼ���.");
		document.form1.bank_sender.focus();
		return;
	}


	/*if(document.form1.paymethod.value=='B' && document.form1.pay_data_sel.value==''){
		alert("�Աݰ��¸� �����ϼ���.");
		return;
	}*/
	<?if($_ShopInfo->memid && $_ShopInfo->wsmember=="Y"){?>
	if(document.form1.paymethod.value=='B' && $(".receipt_yn:checked").length==0){
		alert("������ ��û���θ� �����ϼ���.");
		return;
	}
	<?}?>

	if(document.form1.sender_tel1.value.length==0) {
		alert("�ֹ��� ��ȭ��ȣ�� �Է��ϼ���.");
		document.form1.sender_tel1.focus();
		return;
	}
	if(document.form1.sender_tel2.value.length==0) {
		alert("�ֹ��� ��ȭ��ȣ�� �Է��ϼ���.");
		document.form1.sender_tel2.focus();
		return;
	}
	if(document.form1.sender_tel3.value.length==0) {
		alert("�ֹ��� ��ȭ��ȣ�� �Է��ϼ���.");
		document.form1.sender_tel3.focus();
		return;
	}
	if(!IsNumeric(document.form1.sender_tel1.value)) {
		alert("�ֹ��� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form1.sender_tel1.focus();
		return;
	}
	if(!IsNumeric(document.form1.sender_tel2.value)) {
		alert("�ֹ��� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form2.sender_tel2.focus();
		return;
	}
	if(!IsNumeric(document.form1.sender_tel3.value)) {
		alert("�ֹ��� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form3.sender_tel3.focus();
		return;
	}
	document.form1.sender_tel.value=document.form1.sender_tel1.value+"-"+document.form1.sender_tel2.value+"-"+document.form1.sender_tel3.value;

	if(document.form1.sender_email.value.length>0) {
		if(!IsMailCheck(document.form1.sender_email.value)) {
			alert("�ֹ��� �̸��� ������ �߸��Ǿ����ϴ�.");
			document.form1.sender_email.focus();
			return;
		}
	}

	if(document.form1.receiver_name.value.length==0) {
		alert("�޴º� ������ �Է��ϼ���.");
		document.form1.receiver_name.focus();
		return;
	}
	if(!chkNoChar(document.form1.receiver_name.value)) {
		alert("�޴º� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
		document.form1.receiver_name.focus();
		return;
	}
	if(document.form1.receiver_tel11.value.length==0) {
		alert("�޴º� ��ȭ��ȣ�� �Է��ϼ���.");
		document.form1.receiver_tel11.focus();
		return;
	}
	if(document.form1.receiver_tel12.value.length==0) {
		alert("�޴º� ��ȭ��ȣ�� �Է��ϼ���.");
		document.form1.receiver_tel12.focus();
		return;
	}
	if(document.form1.receiver_tel13.value.length==0) {
		alert("�޴º� ��ȭ��ȣ�� �Է��ϼ���.");
		document.form1.receiver_tel13.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel11.value)) {
		alert("�޴º� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form1.receiver_tel11.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel12.value)) {
		alert("�޴º� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form1.receiver_tel12.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel13.value)) {
		alert("�޴º� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form1.receiver_tel13.focus();
		return;
	}
	document.form1.receiver_tel1.value=document.form1.receiver_tel11.value+"-"+document.form1.receiver_tel12.value+"-"+document.form1.receiver_tel13.value;

	if(document.form1.receiver_tel21.value.length==0) {
		alert("�޴º� �����ȭ��ȣ�� �Է��ϼ���.");
		document.form1.receiver_tel21.focus();
		return;
	}
	if(document.form1.receiver_tel22.value.length==0) {
		alert("�޴º� �����ȭ��ȣ�� �Է��ϼ���.");
		document.form1.receiver_tel22.focus();
		return;
	}
	if(document.form1.receiver_tel23.value.length==0) {
		alert("�޴º� �����ȭ��ȣ�� �Է��ϼ���.");
		document.form1.receiver_tel23.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel21.value)) {
		alert("�޴º� �����ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form1.receiver_tel21.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel22.value)) {
		alert("�޴º� �����ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form1.receiver_tel22.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel23.value)) {
		alert("�޴º� �����ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
		document.form1.receiver_tel23.focus();
		return;
	}
	document.form1.receiver_tel2.value=document.form1.receiver_tel21.value+"-"+document.form1.receiver_tel22.value+"-"+document.form1.receiver_tel23.value;

	if(document.form1.rpost1.value.length==0 || document.form1.rpost2.value.length==0) {
		alert("�����ȣ�� �����ϼ���.");
		get_post();
		return;
	}
	if(document.form1.raddr1.value.length==0) {
		alert("�ּҸ� �Է��ϼ���.");
		document.form1.raddr1.focus();
		return;
	}
	if(document.form1.raddr2.value.length==0) {
		alert("���ּҸ� �Է��ϼ���.");
		document.form1.raddr2.focus();
		return;
	}
	if(!chkNoChar(document.form1.raddr2.value)) {
		alert("���ּҿ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
		document.form1.raddr2.focus();
		return;
	}


	if(paymethod.length==0) {
		alert('���� ������ �������ּ���.');
		//orderpaypop();
		return;
	}


<?php  if(strlen($_ShopInfo->getMemid())>0) { ?>
	<?php  if($_data->reserve_maxuse>=0 && ord($okreserve) && $okreserve>0) { ?>
	if(document.form1.usereserve.value > <?=$okreserve?>) {
		alert("������ ��밡�ɱݾ׺��� Ů�ϴ�.");
		document.form1.usereserve.focus();
		return;
	} else if(document.form1.usereserve.value < 0) {
		alert("�������� 0������ ũ�� ����ϼž� �մϴ�.");
		document.form1.usereserve.focus();
		return;
	}
	<?php  } ?>

	<?php  if($_data->reserve_maxuse>=0 && ord($okreserve) && $okreserve>0 && $_data->coupon_ok=="Y" && $rcall_type=="N") { ?>
	if(document.form1.usereserve.value>0 && document.form1.coupon_code.value.length==8){
		alert('�����ݰ� ������ ���ÿ� ����� �Ұ����մϴ�.\n���߿� �ϳ��� ����Ͻñ� �ٶ��ϴ�.');
		document.form1.usereserve.focus();
		return;
	}
	<?php  } ?>

	<?php  if($_data->reserve_maxuse>=0 && $bankreserve=="N") { ?>
	if (document.form1.usereserve.value>0) {
		if(paymethod!="B" && paymethod!="V" && paymethod!="O" && paymethod!="Q") {
			alert('�������� ���ݰ����ÿ��� ����� �����մϴ�.\n���ݰ����� ������ �ּ���');
			document.form1.paymethod.value="";
			return;
		}
	}
	<?php  } ?>
<?php  } ?>

<?php  if ($_data->payment_type=="Y" || $_data->payment_type=="N") { ?>
	if(paymethod=="B" && document.form1.pay_data1.value.length==0) {
		if(typeof(document.form1.usereserve)!="undefined") {
			if(document.form1.usereserve.value<<?=$sumprice-$salemoney?>) {
				alert(<?=$_data->payment_type?>);
				alert("������ �����ϼ���.");
				//orderpaypop();
				return;
			}
		} else {

			alert("������ �����ϼ���.");
			//orderpaypop();
			return;
		}
	}
<?php  } ?>

	prlistcnt="<?=$arr_prlist?>"+0;
	if(document.form1.msg_type.value=="1") {
		message_len = document.form1.order_prmsg.value.length;
		message_end = document.form1.order_prmsg.value.charCodeAt(message_len-1);
		if (message_len>0 && (message_end==39 || message_end==34 || message_end==92) ) {
			document.form1.order_prmsg.value += " ";
		}
	} else if(document.form1.msg_type.value=="2") {
		for(j=0;j<prlistcnt;j++) {
			message_len = document.form1["order_prmsg"+j].value.length;
			message_end = document.form1["order_prmsg"+j].value.charCodeAt(message_len-1);
			if (message_len>0 && (message_end==39 || message_end==34 || message_end==92) ) {
				document.form1["order_prmsg"+j].value += " ";
			}
		}
	}

	document.form1.receiver_addr.value = "�����ȣ : " + document.form1.rpost1.value + "-" + document.form1.rpost2.value + "\n�ּ� : " + document.form1.raddr1.value + "  " + document.form1.raddr2.value;

<?php  if($_data->coupon_ok=="Y" && strlen($_ShopInfo->getMemid())>0) { ?>
	if (document.form1.bank_only.value=="Y") {
		if(paymethod!="B" && paymethod!="V" && paymethod!="O" && paymethod!="Q") {
			alert("�����Ͻ� ������ ���ݰ����� �����մϴ�.\n���ݰ����� ������ �ּ���");
			document.form1.paymethod.value="";
			return;
		}
	}
<?php  } ?>
	document.form1.order_msg.value="";
	if(document.form1.process.value=="N") {
	<?php  if(ord($etcmessage[1])) {?>
		if(document.form1.nowdelivery.checked) {
			document.form1.order_msg.value+="<font color=red>�������� : ������ �������</font>";
		} else {
			document.form1.order_msg.value+="<font color=red>�������� : "+document.form1.year.value+"�� "+document.form1.mon.value+"�� "+document.form1.day.value+"��";
			<?php  if(strlen($etcmessage[1])==6) { ?>
			document.form1.order_msg.value+=" "+document.form1.time.value;
			<?php  } ?>
			document.form1.order_msg.value+="</font>";
		}
	<?php  } ?>

	<?php  if($etcmessage[2]=="Y") { ?>
		if(document.form1.bank_sender.value.length>1 && (document.form1.paymethod.length==null && paymethod=="B")) {
			if(document.form1.order_msg.value.length>0) document.form1.order_msg.value+="\n";
			document.form1.order_msg.value+="�Ա��� : "+document.form1.bank_sender.value;
		}
	<?php  } ?>

		//������ �߰���۷� Ȯ��
	<?php
/*
		echo "address = \" \"+document.form1.raddr1.value;\n";
		$array_deli = explode("|",$_data->deli_area);
		$cnt= floor(count($array_deli)/2);
		for($i=0;$i<$cnt;$i++){
			$subdeli=explode(",",$array_deli[$i*2]);
			$subcnt=count($subdeli);
			echo "if(";
			for($j=0;$j<$subcnt;$j++){
				if($j!=0) echo " || ";
				echo "address.indexOf(\"{$subdeli[$j]}\")>0";
			}
			echo "){ if(!confirm('";
			if($array_deli[$i*2+1]>0) echo "�ش� ������ ��۷� ".number_format($array_deli[$i*2+1])."���� �߰��˴ϴ�.";
			else echo "�ش� ������ ��۷� ".number_format(abs($array_deli[$i*2+1]))."���� ���ε˴ϴ�.";
			echo "')) return;}\n";
		}
*/
	?>


		if(document.form1.addorder_msg=="[object]") {
			if(document.form1.order_msg.value.length>0) document.form1.order_msg.value+="\n";
			document.form1.order_msg.value+=document.form1.addorder_msg.value;
		}
		document.form1.process.value="Y";
		if(document.form1.paymethod.value=='B'){
			document.form1.target = "PROCESS_IFRAME";
		}else{
			document.form1.target = "";
			document.form1.action = "orderproc.php";

		}

<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
		document.form1.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>order.php';
<?php }?>

		document.form1.submit();

		document.all.paybuttonlayer.style.display="none";
		document.all.payinglayer.style.display="block";


		if(paymethod!="B") ProcessWait("visible");

	} else {
		ordercancel();
	}
}

</script>

<!-- //��۹�� ������ ���� �߰��� ��ũ��Ʈ 150820����-->
<script>
function numberFormat(num) {//�޸���� �Լ�
	var pattern = /(-?[0-9]+)([0-9]{3})/;
	while(pattern.test(num)) {
		num = num.replace(pattern,"$1,$2");
	}
	return num;
}
$(document).ready(function(){
	//$sumprice+$deli_price+$sumpricevat
	var price = Number('<?=$sumprice?>') + Number('<?=$sumpricevat?>');
	var deli = '<?=$deli_price?>';
	var sumprice = Number(price) + Number(deli);
	price = numberFormat(String(price));
	deli = numberFormat(String(deli));
	sumprice = numberFormat(String(sumprice));
	$('input:radio[name=delivery]').change(function(){
		if($(this).val() == "1"){
			$("#direct_deli").val('N');
			$("#deli_type").val('0');
			$("#delivery_price").html(deli);
			$("#price_sum").html(sumprice+"��");
		
		}
		if($(this).val() == "2"){
			$("#direct_deli").val('Y');
			$("#delivery_price").html('0');
			$("#price_sum").html(price+"��");
			$("#deli_type").val('2');
			window.open("../front/direct_popup.php","direct_popup","scrollbars=no,resizable=no, status=no,");
		}
	});
});
</script>

<DIV id="PAYWAIT_LAYER" style='position:absolute; left:50px; top:120px; width:503; height: 255; z-index:1; display:none'><a href="JavaScript:PaymentOpen();"><img src="<?=$Dir?>images/paywait.gif" align=absmiddle border=0 name=paywait galleryimg=no></a></DIV>
<IFRAME id="PAYWAIT_IFRAME" name="PAYWAIT_IFRAME" style="left:50px; top:120px; width:503; height: 255; position:absolute; display:none"></IFRAME>
<!--IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME style="display:''" width=100% height=300></IFRAME-->
<!--IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME width="100%" height="500" <?if(!isdev()){?>style="display:none"<?}?>></IFRAME-->
<IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME width="100%" height="500" style="display:none"></IFRAME>

<? include_once('outline/footer_m.php'); ?>