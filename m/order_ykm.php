<?
include_once('outline/header_m.php');

$Dir="../";
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/basket.class.php");
include_once($Dir."lib/order.class.php");
include_once($Dir."lib/delivery.class.php");
include_once($Dir."lib/coupon.class.php");

$basketidxs = $_REQUEST['basketidxs'];

# ������ ���ű�� �߰�
$staff_order = $_REQUEST['staff_order']; // ������ ���� type
if( $staff_order == '' || $staff_order == 'undefined' ) $staff_order = 'N'; // ���� ������ ����ó��
if( chk_staff_order( $staff_order ) == 0 ) { // 0 - ����ó�� 1 - �Ϲݱ��� 2 - ������ ����
    echo "<script>";
    echo "  alert('�߸��� ���� �Դϴ�.'); ";
    echo "  window.location.replace('basket.php')";
    echo "</script>";
    exit;
}

# ���� �Ⱦ� / ���� ���� ��ٱ��� ��ǰ ����
delDeliveryTypeData();



$Order = new Order();

$Delivery = new Delivery();

if( $_ShopInfo->getStaffYn() == 'Y' && $staff_order == 'Y' ) { // ������ �����̸�
	$staff_order = 'Y';
} else {
	$staff_order = 'N';
}
$Order->set_staff_order( $staff_order); // ������ ����

$Order->order_setting( $basketidxs ); //�ֹ��� ��ٱ��� ����

$_odata = $Order->get_order_object(); //�ֹ��� ���� ��ǰ����


// 2016-06-22 �ߺ��ֹ� ������ ���� �ֹ� check�� �߰�
/*$paycode = unique_id();
$orderChkQry = array();
foreach( $_odata as $dataKey => $dataVal ){
    $orderChkQry[] .= "( '".$paycode."', '".$dataVal['productcode']."', '".$dataVal['basketidx']."', '".$_ShopInfo->getMemid()."', '".date('YmdHis')."' )";
}
if( count( $orderChkQry ) > 0 ){
    $orderChkSql = "INSERT INTO tblorder_check ( paycode, productcode, basketidx, id, reg_date ) VALUES ";
    $orderChkSql.= implode( ',', $orderChkQry );
    pmysql_query( $orderChkSql, get_db_conn() );
}*/
foreach( $_odata as $_proData =>$_proObj ){
	//exdebug($_proObj['vender']);
	$brandVenderArr[$_proObj['brand']]	=  $_proObj['vender'];
}

$brandArr = ProductToBrand_Sort( $_odata );

// 2016-04-21
// ��ٱ����� ��ǰ�� �������� ����� �������� ���Ͽ� ��ǰ�� �ҷ����� ���� ��� ����ó��
if( count( $brandArr ) == 0 ){

    $sendReferer = parse_url( $_SERVER['HTTP_REFERER'] );

    if( strpos( $sendReferer['query'], 'basketidxs' ) === false ){

        echo "<script>";
        echo " alert( '��ǰ�� �������� �ʽ��ϴ�.' );";
        echo " window.location.replace('/m/basket.php'); ";
        echo "</script>";

    } else {

        $basketidx = substr( $sendReferer['query'], strpos( $sendReferer['query'], 'basketidxs=' ) + 11 );
        $basketidx_arr = explode( '|', $basketidx );

        if( strlen( $basketidx_arr[0] ) > 0 ){

            $b_sql = "SELECT productcode FROM tblbasket WHERE basketidx = '".$basketidx_arr[0]."' ";
            $b_res = pmysql_query( $b_sql, get_db_conn() );
            $b_row = pmysql_fetch_object( $b_res );
            pmysql_free_result( $b_res );

            echo "<script>";
            echo " alert( '�ٽ� �ֹ����ּ���.' );";
            echo " window.location.replace('/m/productdetail.php?productcode=".$b_row->productcode."'); ";
            echo "</script>";

        } else {

            echo "<script>";
            echo " alert( '��ǰ�� �������� �ʽ��ϴ�.' );";
            echo " window.location.replace('/m/basket.php'); ";
            echo "</script>";

        }

    }
    exit;
}

$Delivery->get_product( $_odata ); //��ۺ� ��������
$Delivery->set_deli_item();
$vender_info    = $Delivery->get_vender();
$vender_deli    = $Delivery->get_vender_deli();
$free_deli    = $Delivery->get_free_deli();
$product_deli = $Delivery->get_product_deli();

//��ǰ �̹��� ���
$productImgPath = $Dir.DataDir."shopimages/product/";

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

#ȸ�� ���� ����
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result = pmysql_query($sql,get_db_conn());
	if($row = pmysql_fetch_object($result)) {
		$reserve_chk= $row->reserve_chk;
        if( $staff_order == 'N' ) $user_reserve = $row->reserve;
        if( $staff_order == 'Y' ) $user_reserve = $row->staff_reserve;
		if($user_reserve>$reserve_limit) {
			$okreserve=$reserve_limit;
			$remainreserve=$user_reserve-$reserve_limit;
		} else {
			$okreserve=$user_reserve;
			$remainreserve=0;
		}

		//����� ����
		$dn_sql ="SELECT * FROM tbldestination WHERE mem_id = '".$_ShopInfo->getMemid()."' ORDER BY NO DESC";
		$dn_result = pmysql_query( $dn_sql, get_db_conn() );
		while( $dn_row = pmysql_fetch_object( $dn_result ) ){
			$dn_info[] = $dn_row;
			if ($dn_row->base_chk == 'Y') {
				$dn_name					= $dn_row->get_name;
				$dn_mobile				= $dn_row->mobile;
				$dn_post_code			= $dn_row->postcode;
				$dn_post_zonecode	= $dn_row->postcode_new;
				$dn_addr1					= $dn_row->addr1;
				$dn_addr2					= $dn_row->addr2;
			}
		}
		pmysql_free_result($dn_result);

		$home_addr="";
		
		if($dn_post_zonecode) {
			$home_zonecode	= $dn_post_zonecode;
			$home_post_ep		= explode("-", $dn_post_code);
			$home_post1			= $home_post_ep[0];
			$home_post2			= $home_post_ep[1];
			$home_post			= $dn_post_zonecode;
			$home_addr1			= $dn_addr1;
			$home_addr2			= $dn_addr2;
		} else {
			$home_zonecode	= "";
			$home_post1			= "";
			$home_post2			= "";
			$home_post			= "";
			$home_addr			= "";
			$home_addr1			= "";
			$home_addr2			= "";
		}

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
		$userName = $row->name;
		$email = $row->email;
		if (ord($row->mobile)) $mobile = $row->mobile;
		else if (ord($row->home_tel)) $mobile = $row->home_tel;
		else if (ord($row->office_tel)) $mobile = $row->office_tel;
		$mobile=explode("-",replace_tel(check_num($mobile)));
		$home_tel=explode("-",replace_tel(check_num($row->home_tel)));

		if($_ShopInfo->getStaffType()){
			$staff_limit_max = $row->staff_limit_max;
			$staff_limit = $row->staff_limit;
		}

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
if( get_session( "ACCESS" ) == 'app' ) $couponType = 'T';
else $couponType = 'M';

//������ �ֹ�����
$etcmessage=explode("=",$_data->order_msg);

#���� ����
$_CouponInfo = new CouponInfo();
# ȸ������
$_CouponInfo->search_member_coupon( $_ShopInfo->memid, 1, 1 );
$memCoupon      = $_CouponInfo->mem_coupon;

//$member_coupon = MemberCoupon( 1, 'P', 'BC' );

#��ǰ������ ��ٱ��� ������ ������
$basket_coupon = array();
$product_coupon = array();
$deliver_coupon = array();
$chk_coupon     = array();
$product_coupon_cnt = 0;
foreach( $memCoupon as $couponVal ){
	if( $couponVal->coupon_use_type == '1' && $couponVal->coupon_type != '9' ){
		if( array_search( $couponVal->coupon_code, $chk_coupon) === false ){
            $basket_coupon[] = $couponVal;
            $chk_coupon[]    = $couponVal->coupon_code;
        }
	} else if( $couponVal->coupon_type == '9' ) {
        $deliver_coupon[] = $couponVal;
    }else {
		$product_coupon[] = $couponVal;
	}
}

$coupon_cnt = 0;
if( strlen( $_ShopInfo->getMemid() ) > 0 ){
	$coupon_cnt = count( $memCoupon );
	$memsql = "SELECT reserve FROM tblmember WHERE id = '".$_ShopInfo->getMemid()."'";
	$memres = pmysql_query( $memsql, get_db_conn() );
	$mem_reserve = pmysql_fetch_object( $memres );
	pmysql_free_result( $memres );
}


# ��ǰ ��Ż��  + ��Ż ����
if( strlen( $basketidxs ) > 0 ){
    /*$total_sql = "
        SELECT
            SUM( ( sellprice + option_price ) * quantity  ) AS total_price_sum, SUM( quantity )::int AS total_qty
        FROM
            (
                SELECT 
                    p.sellprice, bk.quantity,  COALESCE( po.option_price, 0 ) AS option_price
                FROM 
                    tblbasket AS bk
                JOIN
                    tblproduct AS p ON ( bk.productcode = p.productcode )  
                LEFT JOIN
                    tblproduct_option AS po ON ( bk.productcode = po.productcode AND bk.optionarr = po.option_code )
                WHERE 
                    id = '".$_ShopInfo->getMemid()."' 
                AND
                    basketidx IN ( ".str_replace( '|', ',', $basketidxs )." )
        ) AS basket
    ";
	exdebug($total_sql);
    $total_res = pmysql_query( $total_sql, get_db_conn() );
    $total_row = pmysql_fetch_object( $total_res );
    pmysql_free_result( $total_res );*/


	//echo"<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";	
	$chk_total_pro_price	= 0; // ��ǰ�ݾ�
	$chk_total_deli_price	= 0; // ��۷�
	$chk_total_quantity		= 0; // ��ǰ����

	foreach( $brandArr as $brand=>$brandObj ){
		$vender	=$brandVenderArr[$brand];
		$product_price = 0;
		foreach( $brandObj as $product ) {
			$opt_price = 0; // ��ǰ�� ���ǰ�
			if( count( $product['option'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
				if( count( $product['option'] ) > 0 ){
					if( $product['option_type'] == 0 ){ // ������ �ɼ�
						$tmp_option = $product['option'][0];
						$opt_price += $tmp_option['option_price'] * $product['option_quantity'];
					}
					if( $product['option_type'] == 1 ){ // ������ �ɼ�
						foreach( $product['option'] as $optKey=>$optVal ){
							$opt_price += $optVal['option_price'] * $product['option_quantity'];
						}// option foreach
					}
				} // count option
			}// count option
			$product_price += ( $product['price']  * $product['quantity'] ) + $opt_price; //�ɼǰ��� ��ǰ���� �ջ����ش�
			$chk_total_quantity += $product['quantity'];
		}

		$vender_deli_price = 0;

		if( $vender_info[$vender] ){

			if( $product_deli[$vender] ){
				foreach( $product_deli[$vender] as $prDeliKey => $prDeliVal ){
					$vender_deli_price += $prDeliVal['deli_price'];
				}
			}
			$vender_deli_price += $vender_deli[$vender]['deli_price'];
		}

		if( $vender_info[$vender]['deli_select'] == '0' || $vender_info[$vender]['deli_select'] == '2' ) $chk_total_deli_price += $vender_deli_price;
		$chk_total_pro_price += $product_price;
	}

	$total_price_sum	= $chk_total_pro_price+$chk_total_deli_price;
	$total_qty			= $chk_total_quantity;

	//echo $total_price_sum."/".$total_qty;
	if($staff_order == 'Y') { // ������ �����̸�	
		// ������ ����Ʈ�� ERP���� �����´�.
		$staff_reserve		= getErpStaffPoint($_ShopInfo->getStaffCardNo());			// ������ ����Ʈ
		if($total_price_sum > $staff_reserve) { // ����Ʈ�� �����ϸ�
			echo "<script>";
			echo "  alert('�����Ͻ� ������ ����Ʈ�� �����մϴ�.'); ";
			echo "  window.location.replace('basket.php')";
			echo "</script>";
			exit;
		}
	}
}




# ��ǰ�� ��� üũ�� ���� ��ǰ ������ ���� �ɼ��� ��ǰ�� ������ ���� �� �� �ϱ� ���� �迭 ����
# �ɼ��� �������� ���� �Ѵٰ� �Ͽ� �������� ���� ���븸 �۾�
$stockArrayCheck = array();
foreach( $brandArr as $brand=>$brandObj ){
	foreach( $brandObj as $product ) {
		if( count( $product['option'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
			if( count( $product['option'] ) > 0 ){
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				if( $product['option_type'] == 0 ){ // ������ �ɼ�
					$tmp_option = $product['option'][0];
					$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
					foreach( $tmp_opt_subject as $optKey=>$optVal ){
						$stockArrayCheck[$product['prodcode'].$tmp_opt_contetnt[$optKey].$product['store_code']]['productname'] = $product['productname'];
						$stockArrayCheck[$product['prodcode'].$tmp_opt_contetnt[$optKey].$product['store_code']]['prodcode'] = $product['prodcode'];
						$stockArrayCheck[$product['prodcode'].$tmp_opt_contetnt[$optKey].$product['store_code']]['colorcode'] = $product['colorcode'];
						$stockArrayCheck[$product['prodcode'].$tmp_opt_contetnt[$optKey].$product['store_code']]['size'] = $tmp_opt_contetnt[$optKey];
						$stockArrayCheck[$product['prodcode'].$tmp_opt_contetnt[$optKey].$product['store_code']]['store_code'] = $product['store_code'];
						$stockArrayCheck[$product['prodcode'].$tmp_opt_contetnt[$optKey].$product['store_code']]['quantity'] += $product['quantity'];
						# ���� �ڵ尡 �������� ���� �ڵ� ���� ������ ���� ���´�. ���� �ڵ���� ��ǰ�� ���� �ɼ��� ��ü ��� ���ؾ� �ϱ� ����
						if($product['store_code']) $stockArrayCheck[$product['prodcode'].$tmp_opt_contetnt[$optKey]]['quantity'] += $product['quantity'];
					}
				}
			}
		}
	}
}
if(count($stockArrayCheck) > 0){
	foreach($stockArrayCheck as $k => $v){
		# ��ǰ�� ��� üũ
		if($v['prodcode'] && $v['colorcode']){
			$shopRealtimeStock = getErpPriceNStock($v['prodcode'], $v['colorcode'], $v['size'], $v['store_code']);
			if($v['quantity'] > $shopRealtimeStock['sumqty']){
				alert_go("[".$v['productname']."]��� �����մϴ�. \\r��ٱ��� �������� �̵��մϴ�.", "../m/basket.php");
				exit;
			}
		}
	}
}






?>
<!-- �����ȣ ���� -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>

// function openDaumPostcode() {
// 	new daum.Postcode({
// 		oncomplete: function(data) {
// 			// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.
// 			// �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ְ�, Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
// 			/*document.getElementById('rpost1').value = data.postcode1;
// 			document.getElementById('rpost2').value = data.postcode2;
// 			document.getElementById('raddr1').value = data.address;
// 			document.getElementById('raddr2').value = '';
// 			document.getElementById('raddr2').focus();*/
// 			//$("#rpost1").val(data.postcode1);
// 			$("#post5").val(data.zonecode);
// 			$("#post-code").val(data.postcode1);
// 			$("#rpost2").val(data.postcode2);
// 			$("#raddr1").val(data.address);
// 			$("#raddr2").val('');
// 			$("#raddr2").focus();
// 			$("#post").val( data.zonecode );
// 			//��ü �ּҿ��� ���� ���� �� ()�� ���� �ִ� �ΰ������� �����ϰ��� �� ���,
// 			//�Ʒ��� ���� ���Խ��� ����ص� �ȴ�. ���Խ��� �������� ������ �°� �����ؼ� ��� �����ϴ�.
// 			//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
// 			//document.getElementById('addr').value = addr;


// 		}
// 	}).open();
// }

<?php if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y"){?>
function coupon_check(temp){
	//���������� ����������� Ǯ���ش�
	payment_disabled_off();
	//usereserve remainreserve okreserve
	if(typeof(document.form1.coupon_dc)!="undefined") {
		document.form1.coupon_dc.value=0;
	}

	$(".CLS_saleCoupon").html('0��');
	if(typeof(document.form1.usereserve)!="undefined") {
		document.form1.usereserve.value=0;
	}

	$("#ID_coupon_code_layer").html('');
	reserve_check(temp);

	window.open("about:blank","couponpopup","width=650,height=650,toolbar=no,menubar=no,scrollbars=yes,status=no");
	document.couponform.submit();
}

// ������ üũ

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

function payment_disabled_off(){
    $("input[name='dev_payment']").each(function(){
        $(this).prop( 'disabled', false );
    });
}

function ordercancel(gbn) {
    if(gbn=="cancel" && document.form1.process.value=="N") {
        document.location.href="basket.php";
    }
}


function sel_paymethod(obj){

//    console.log(obj.value);

    var frm=document.form1;
    var	totp=uncomma(document.getElementById("price_sum").innerHTML);

    if (obj.value == 'B') {
        $("#card_type").removeClass('hide');
        $("#payco_notice").addClass('hide');
    } else if(obj.value == 'Y'){
        $("#payco_notice").removeClass('hide');
        $("#card_type").addClass('hide');
    } else {
        $("#card_type").addClass('hide');
        $("#payco_notice").addClass('hide');
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

function sel_account(obj){

    var frm=document.form1;

    frm.pay_data1.value=obj.value;
}

<!--
function CheckForm() {

    if($('#dev_agree').prop('checked') == false){
        alert("���ſ� �������� �����̽��ϴ�.");
        $('#dev_agree').focus();
    }else{

        paymethod=document.form1.paymethod.value.substring(0,1);

        if ( paymethod == "" ) {
            alert("���������� ������ �ּ���.");
            return false;
        }

        <?php  if(strlen($_ShopInfo->getMemid())==0) { ?>
        if(document.form1.sender_name.type=="text") {
            if(document.form1.sender_name.value.length==0) {
                alert("�ֹ��� ������ �Է��ϼ���.");
                document.form1.sender_name.focus();
                return;
            }
            if(!chkNoChar(document.form1.sender_name.value)) {
                alert("�ֹ��� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ), *(��ǥ)�� �Է��Ͻ� �� �����ϴ�.");
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


        if(document.form1.paymethod.value=='B' && document.form1.pay_data_sel.value==''){
            alert("�Աݰ��¸� �����ϼ���.");
            return;
        }

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
            alert("�޴º� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ), *(��ǥ)�� �Է��Ͻ� �� �����ϴ�.");
            document.form1.receiver_name.focus();
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

         $("input[name='home_tel']").val( $("#home_tel1").val()+'-'+$("#home_tel2").val()+'-'+$("#home_tel3").val() );

        if( document.form1.post.value.length==0 ) {
            alert("�����ȣ�� �����ϼ���.");
            openDaumPostcode();
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
            alert("���ּҿ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ), *(��ǥ)�� �Է��Ͻ� �� �����ϴ�.");
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

    // ���� ���
    set_coupon_layer();

    <?php  if ($_data->payment_type=="Y" || $_data->payment_type=="N") { ?>
        if(paymethod=="B" && document.form1.pay_data1.value.length==0) {
            if(typeof(document.form1.usereserve)!="undefined") {
                if(document.form1.usereserve.value<<?=$sumprice-$salemoney?>) {
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

        document.form1.receiver_addr.value = "�����ȣ : " + document.form1.post.value + "\n�ּ� : " + document.form1.raddr1.value + "  " + document.form1.raddr2.value;
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

            if(document.form1.addorder_msg=="[object]") {
                if(document.form1.order_msg.value.length>0) document.form1.order_msg.value+="\n";
                document.form1.order_msg.value+=document.form1.addorder_msg.value;
            }
            document.form1.process.value="Y";
            //document.form1.target = "PROCESS_IFRAME";

    <?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
            document.form1.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>order.php';
    <?php }?>

            document.form1.submit();
            //document.all.paybuttonlayer.style.display="none";
            $('div[name="paybuttonlayer"]').hide();
            //document.all.payinglayer.style.display="block";


            //if(paymethod!="B") ProcessWait("visible");

        } else {
            ordercancel();
        }
    }
}
//�ֹ� ���� ����
$(document).on( 'click', '#same_address', function(){
    if( $(this).prop( 'checked' ) ){
        $('#receiver_name').val( $('input[name="sender_name"]').val() );
        $('#receiver_tel11 > option').each( function(){
            if( $(this).val() == $('#home_tel1').val() ) {
                $(this).prop( 'selected', true );
            }
        });

        $('#receiver_tel12').val( $('#home_tel2').val() );
        $('#receiver_tel13').val( $('#home_tel3').val() );
        $('#receiver_tel21 > option').each( function(){
            if( $(this).val() == $('#sender_tel1').val() ) {
                $(this).prop( 'selected', true );
            }
        });
        $('#receiver_tel22').val( $('#sender_tel2').val() );
        $('#receiver_tel23').val( $('#sender_tel3').val() );
        $('#post').val( "<?=$home_post?>" );
        $('#raddr1').val( "<?=$home_addr1?>" );
        $('#raddr2').val( "<?=$home_addr2?>" );
        $('#post5').val( "<?=$home_zonecode?>" );
    } else {
        $('#receiver_name').val( '' );
        $('#receiver_tel11 > option').eq(0).prop( 'selected', true );
        $('#receiver_tel12').val( "" );
        $('#receiver_tel13').val( "" );
        $('#receiver_tel21 > option').eq(0).prop( 'selected', true );
        $('#receiver_tel22').val( '' );
        $('#receiver_tel23').val( '' );
        $('#post').val( "" );
        $('#post5').val( "" );
        $('#raddr1').val( "" );
        $('#raddr2').val( "" );
    }
});

function Dn_InReceiver(dn_data) {
	if(dn_data) {
		dn_data_ep		= dn_data.split("|@|");
		dn_mobile_ep		= dn_data_ep[3].split("-");
		dn_postcode_ep	= dn_data_ep[4].split("-");

        $('#receiver_name').val( dn_data_ep[2] );
        $('#receiver_tel11 > option').eq(0).prop( 'selected', true );
        $('#receiver_tel12').val( "" );
        $('#receiver_tel13').val( "" );

        $('#receiver_tel21 > option').each( function(){
            if( $(this).val() == dn_mobile_ep[0] ) {
                $(this).prop( 'selected', true );
            }
        });
        $('#receiver_tel22').val( dn_mobile_ep[1] );
        $('#receiver_tel23').val( dn_mobile_ep[2] );
        $('#post').val( dn_data_ep[5] );
        $('#raddr1').val( dn_data_ep[6] );
        $('#raddr2').val( dn_data_ep[7] );
        $('#post5').val( dn_data_ep[5] );

		$("#same_address").prop("checked", false);

	} else {
        $('#receiver_name').val( '' );
        $('#receiver_tel11 > option').eq(0).prop( 'selected', true );
        $('#receiver_tel12').val( "" );
        $('#receiver_tel13').val( "" );
        $('#receiver_tel21 > option').eq(0).prop( 'selected', true );
        $('#receiver_tel22').val( '' );
        $('#receiver_tel23').val( '' );
        $('#post').val( "" );
        $('#post5').val( "" );
        $('#raddr1').val( "" );
        $('#raddr2').val( "" );
	}

	$('div.layer_shipping_list .btn-close').trigger('click');
}
-->

</script>
<!-- �ֹ� ���� �˾� -->
	<div id='o-coupon-layer' class="layer-dimm-wrap layer_coupon_use">
		<div class="dimm-bg"></div>
		<!-- �ֹ� ��ǰ ���� -->
		<div id="product-coupon" class="layer-inner">
			<h3 class="layer-title">���� ����</h3>
			<button type="button" class="btn-close">â �ݱ� ��ư</button>
			<div class="layer-content">
				<div class="wrap_layer_con">
					<ul class="list">
<?php

foreach( $brandArr as $brand=>$brandObj ){
    $product_price = 0;
    $tmp_opt_price = 0;
	$brand_name = get_brand_name( $brand );
	$vender	=$brandVenderArr[$brand];
    foreach( $brandObj as $product ) {

        if( count( $product['option'] ) > 0){
             foreach( $product['option'] as $optKey=>$optVal ){
                $tmp_opt_price += $optVal['option_price'] * $product['quantity'];
            } // opt_subject foreach
        } // option if
        $product_price = ( $product['price']  * $product['quantity'] ) + $tmp_opt_price; //�ɼǰ��� ��ǰ���� �ջ����ش�
?>
						<li name="product_area">
							<div class="box_mylist">
								<div class="content">
									<a href="javascript:;">
										<figure class="mypage_goods">
											<div class="img"><img src="<?=getProductImage( $productImgPath, $product['tinyimage'] )?>" alt=""></div>
											<figcaption>
												<p class="brand">[<?=$brand_name?>]</p>
												<p class="name"><?=$product['productname']?></p>
											</figcaption>
										</figure>
									</a>
									<div class="select_coupon">
<?php
        foreach( $product_coupon as $prcouponKey=>$prcouponVal ){
            if( $_CouponInfo->check_coupon_product( $product['productcode'], 2, $prcouponVal ) ){
                // ������� üũ
                if( $prcouponVal->mini_quantity == 0 || ( $prcouponVal->mini_type == 'P' && $prcouponVal->mini_quantity <= $total_row->total_price_sum )
                    || ( $prcouponVal->mini_type == 'Q' && $prcouponVal->mini_quantity <= $total_row->total_qty )
                ){
                    $product_coupon_cnt++;
?>
										<label>
											<input type="radio" name="product_coupon[<?=$product['basketidx']?>]" class="custom_radio" value="<?=$prcouponVal->ci_no?>"
												data-cp-code="<?=$prcouponVal->coupon_code?>"
												data-pr-price="<?=$product_price?>"
												data-basketidx="<?=$product['basketidx']?>"
											><?=$prcouponVal->coupon_name?>
										</label>
<?php
                }
            } // couponProductCheck if
        } // product_coupoln forach
?>
										<label>
											<input type="radio" name="product_coupon[<?=$product['basketidx']?>]" class="custom_radio" value=""
												data-cp-code=""
												data-pr-price="<?=$product_price?>"
												data-basketidx="<?=$product['basketidx']?>"
												checked
											>��� ����
										</label>
									</div>
								</div>
								<!--div class="discount">���αݾ� <strong class="point-color">0��</strong></div-->
							</div>
						</li>
<?php
        # ��ٱ��� ���� ����
        foreach( $basket_coupon as $basketKey=>$basketVal ){
            if( !$_CouponInfo->check_coupon_product( $product['productcode'], 2, $basketVal ) ){
                unset( $basket_coupon[$basketKey] );
            }
        }
    } // venderObj foreach
} // venderArr foreach
?>
					</ul>
				</div>

				<button type="button" class="btn-point" id='pr-coupon-choise'>Ȯ��</button>
			</div><!-- //.layer-content -->
		</div><!-- //.layer-inner -->

		<!-- ��ٱ��� ���� -->
		<div id="basket-coupon" class="layer-inner">
			<h3 class="layer-title">���� ����</h3>
			<button type="button" class="btn-close">â �ݱ� ��ư</button>
			<div class="layer-content">
				<div class="wrap_layer_con">
					<ul class="list" name="basket_area">
<?php
foreach( $basket_coupon as $bcouponKey=>$bcouponVal ){
?>
						<li>
							<div class="coupon-wrap" name='basket-coupon-wrap' >
								<div class="coupon">
									<strong><?=$bcouponVal->sale_money?></strong>
									<? if( $bcouponVal->sale_type <= 2 ){ echo '%'; } else { echo '��'; } ?>
								</div>
								<div class="coupon-info">
									<p class="code"><?=$bcouponVal->coupon_code?></p>
									<p class="name">
										<input type="radio" name="basket_coupon" value="<?=$bcouponVal->ci_no?>"
											data-cp-code="<?=$bcouponVal->coupon_code?>"
										>
										<strong name='basket-coupon-label' ><?=$bcouponVal->coupon_name?></strong>
									</p>
								</div>
							</div>
						</li>
<?php
}
?>
						<li class='hide' >
							<div class="coupon-wrap">
								<div class="coupon"><strong>����</strong></div>
								<div class="coupon-info">
									<p class="code"></p>
									<p class="name">
										<input type="radio" name="basket_coupon" value=""
											data-cp-code=""
											checked
										>
										<strong>�������� ����</strong>
									</p>
								</div>
							</div>
						</li>
					</ul>
				</div>

				<button type="button" class="btn-point" id='bk-coupon-choise'>Ȯ��</button>
			</div><!-- //.layer-content -->
		</div><!-- //.layer-inner -->

    <!-- ��ۺ� ���� ���� -->
		<div id="delivery-coupon" class="layer-inner">
			<h3 class="layer-title">���� ����</h3>
			<button type="button" class="btn-close">â �ݱ� ��ư</button>
			<div class="layer-content">
				<div class="wrap_layer_con">
					<ul class="list" name="delivery_coupon_area">
<?php
foreach( $deliver_coupon as $dcouponKey=>$dcouponVal ){
?>
						<li>
							<div class="coupon-wrap">
								<div class="coupon"><strong>��ۺ�</strong></div>
								<div class="coupon-info">
									<p class="code"><?=$dcouponVal->coupon_code?></p>
									<p class="name">
										<input type="radio" name="delivery_coupon" value="<?=$dcouponVal->ci_no?>"
											data-cp-code="<?=$dcouponVal->coupon_code?>"
										>
										<strong><?=$dcouponVal->coupon_name?></strong>
									</p>
								</div>
							</div>
						</li>
						<li class='hide' >
							<div class="coupon-wrap">
							   <div class="coupon"><strong>����</strong></div>
								<div class="coupon-info">
									<p class="code"></p>
									<p class="name">
										<input type="radio" name="delivery_coupon" value=""
											data-cp-code=""
											checked
										>
										<strong>����</strong>
									</p>
								</div>
							</div>
						</li>
<?php
}
?>
					</ul>
				</div>

				<button type="button" class="btn-point" id='deli-coupon-choise'>Ȯ��</button>
			</div><!-- //.layer-content -->
		</div><!-- //.layer-inner -->
	</div><!-- //.layer_coupon_use -->
	<!-- //���� ���� ���̾��˾� -->

	<!-- ����� ��� �˾� -->
	<div class="layer-dimm-wrap layer_shipping_list">
		<div class="dimm-bg"></div>
		<div class="layer-inner">
			<h3 class="layer-title">����� ���</h3>
			<button type="button" class="btn-close">â �ݱ� ��ư</button>
			<div class="layer-content">
				<ul class="list_addr">
<?
foreach( $dn_info as $dn_vkey=>$dn_val ){
	//exdebug($dn_val);
?>
					<li>
						<a href="javascript:;" onClick="javascript:Dn_InReceiver('<?=$dn_val->no.'|@|'.$dn_val->destination_name.'|@|'.$dn_val->get_name.'|@|'.addMobile($dn_val->mobile).'|@|'.$dn_val->postcode.'|@|'.$dn_val->postcode_new.'|@|'.$dn_val->addr1.'|@|'.$dn_val->addr2?>')">
							<div class="clear">
								<p class="name"><?=$dn_val->destination_name?><?if($dn_val->base_chk=='Y') {?> <span class="tag">�⺻�����</span><?}?></p>
								<p class="tel"><?=addMobile($dn_val->mobile)?></p>
							</div>
							<p class="addr"><?=$dn_val->addr1.''.$dn_val->addr2?></p>
						</a>
						<!--button type="button" class="btn_delete"><img src="static/img/btn/btn_delete.png" alt="����"--></button>
					</li>
<?
}
?>
				</ul>
			</div>
		</div>
	</div>
	<!-- //����� ��� ���̾��˾� -->

	<section class="top_title_wrap">
		<h2 class="page_local">
			<a href="javascript:history.back();" class="prev"></a>
			<span>�ֹ�/�����ϱ�</span>
			<a href="<?=$Dir.MDir?>" class="home"></a>
		</h2>
	</section>

	<form id="form" name="form1" action="<?=$Dir.FrontDir?>ordersend.php" method="post">
	<input type='hidden' name='basketidxs' value='<?=$basketidxs?>' > <!-- ��ٱ��� ��ȣ -->
	<input type='hidden' name='staff_order' value='<?=$staff_order?>' > <!-- ������ ���� -->
	<input type='hidden' name='paycode' value='<?=$paycode?>' > <!-- �ֹ�üũ�� �ڵ� -->
	<input type='hidden' name='paymethod'>
	<input type='hidden' name='pay_data1'>
	<input type='hidden' name='pay_data2'>
	<input type='hidden' name='sender_resno'>
	<input type='hidden' name='sender_tel'>
	<input type='hidden' name='home_tel'>
	<input type='hidden' name='receiver_tel1'>
	<input type='hidden' name='receiver_tel2'>
	<input type='hidden' name='receiver_addr'>
	<input type='hidden' name='order_msg'>
	<input type="hidden" name="msg_type" value="1">
	<input type='hidden' name='process' id='process' value="N">
	<input type='hidden' name='escrow_limit' value="<?=$escrow_info["escrow_limit"]?>">
	<input type='hidden' name='escrowcash' value="<?=$escrow_info["escrowcash"]?>">
	<input type='hidden' name='coupon_code'>
	<input type='hidden' name='usereserve'>
	<input type='hidden' name='email'>
	<input type='hidden' name='mobile_num1'>
	<input type='hidden' name='mobile_num'>
	<input type='hidden' name='address'>
	<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
	<input type='hidden' name='shopurl' value="<?=$_SERVER['HTTP_HOST']?>">
	<?php }?>
	<input type="hidden" name="addorder_msg" value="">
	<input type="hidden" id="direct_deli" name="direct_deli" value="N">

	<div class="cart-order-wrap">

		<ul class="process_order clear">
			<li>��ٱ���</li>
			<li class="on">�ֹ��ϱ�</li>
			<li>�����Ϸ�</li>
		</ul>

<?php
$sumprice = 0;
$deli_price = 0; // ���� ��۷�
$deli_price2 = 0; //���� ��۷�
$total_reserve = 0; // ��ü ������
foreach( $brandArr as $brand=>$brandObj ){
	$vender_price = 0;
	$product_reserve = 0;
	$product_price = 0;
	$brand_name = get_brand_name( $brand );
	$vender	=$brandVenderArr[$brand];
?>
		<!-- ��ǰ�� ���� �ݺ� -->
		<h3 class="pro_title"><?=$brand_name?></h3>
		<section class="cart-list-wrap order">
            <ul class="list vender_product_list">
<?
	foreach( $brandObj as $product ) {
		$opt_price = 0; // ��ǰ�� ���ǰ�
		$pr_reserve = 0; //��ǰ�� ���ϸ���
        $tmp_pr_opt_price = 0;
        //exdebug( $product );
?>
				<!-- ��ǰ ����Ʈ �ݺ� -->
				<li class="vender_area">
					<div class="product_area">
						<div class="box_cart">
							<figure class="mypage_goods">
								<div class="img"><a href="<?=$Dir?>m/productdetail.php?productcode=<?=$product['productcode']?>"><img src="<?=getProductImage( $productImgPath, $product['tinyimage'] )?>" alt=""></a></div>
								<figcaption>
									<p class="brand">[<?=$brand_name?>]</p>
									<p class="name"><?=$product['productname']?></p>
<?php
		if( count( $product['option'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
?>
									<p class="shipping">
<?php
			$tmp_opt_content_html	= '';
			if( count( $product['option'] ) > 0 ){
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				if( $product['option_type'] == 0 ){ // ������ �ɼ�
					$tmp_option = $product['option'][0];
					$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
					foreach( $tmp_opt_subject as $optKey=>$optVal ){
						if ($tmp_opt_content_html !='') $tmp_opt_content_html	 .= ' / ';
						$tmp_opt_content_html	 .= '<span>'.$optVal.' : '.$tmp_opt_contetnt[$optKey].'</span>';
                        $tmp_pr_opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
				}
				if( $product['option_type'] == 1 ){ // ������ �ɼ�
					foreach( $product['option'] as $optKey=>$optVal ){
						$tmp_opt_content = explode( chr(30), $optVal['option_code'] );
						if ($tmp_opt_content_html !='') $tmp_opt_content_html	 .= ' / ';
						$tmp_opt_content_html	 .= '<span>'.$tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1].'</span>';
						$tmp_pr_opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
				}
			} // count option

			if( $product['text_opt_content'] ){ // �߰����� �ɼ�
				$tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
				$text_opt_content = explode( '@#', $product['text_opt_content'] );
				foreach( $text_opt_content as $textKey=>$textVal ){
					if( $textVal != '' ) {
						if ($tmp_opt_content_html !='') $tmp_opt_content_html	 .= ' / ';
						$tmp_opt_content_html	 .= '<span>'.$tmp_text_subejct[$textKey].' : '.$textVal.'</span>';
					}
				}
			}  // text_opt_content if
			if( $tmp_pr_opt_price > 0 ) $tmp_opt_content_html	 .= '<span>&nbsp;( + '.number_format( $tmp_pr_opt_price ).'��)</span>';
			if ($tmp_opt_content_html !='') $tmp_opt_content_html	 .= ' / ';
            $tmp_opt_content_html	 .= '<span>���� : '.number_format( $product['quantity'] )."��</span>";
			echo $tmp_opt_content_html;
?>
									</p>
<?php
        } // opt1_name len if
        $pr_reserve = getReserveConversion( $product['reserve'], $product['reservetype'], ( $product['price'] * $product['quantity'] ) + $tmp_pr_opt_price , "N" );
        $product_reserve += $pr_reserve; // ������ ��ǰ ���� ������
        $total_reserve += $pr_reserve;
        $product_price = ( $product['price']  * $product['quantity'] ) + $tmp_pr_opt_price; //�ɼǰ��� ��ǰ���� �ջ����ش�
        $vender_price += $product_price; // ������ ��ǰ����
?>
									<p class="price">
										<span class="point-color"><?=number_format( $product_price )?>��</span>
									</p>
								</figcaption>
							</figure>
						</div>
					</div>
				</li>
<?php
	} // vederObj foreach
?>
			</ul>
			<div class="pay-price">
				<section class="benefit bd-none">
					<article>
						<h4>�Ǹűݾ�</h4>
						<div class="price"><strong><?=number_format( $vender_price )?></strong>��</div>
					</article>
					<article>
						<h4 class="title-delivery">��ۺ�</h4>
						<div class="price">
<?php
    $vender_deli_price = 0;
    $deliver_text = '';
    if( $vender_info[$vender] ){
            if( $vender_info[$vender]['deli_select'] == '0' ){
                //$deliver_text = '����';
            } else if( $vender_info[$vender]['deli_select'] == '1' ) {
                $deliver_text = '����';
            } else if( $vender_info[$vender]['deli_select'] == '2' ) {

               // $deliver_text = "<div>\n";
                $deliver_text = "   <select name='deli_select[".$vender."]' data-vender='".$vender."' >\n";
                $deliver_text.= "       <option value='0' >����</option>\n";
                $deliver_text.= "       <option value='1' >����</option>\n";
                $deliver_text.= "   </select>\n";
                //$deliver_text.= "</div>\n";

            }
            if( $product_deli[$vender] ){
                foreach( $product_deli[$vender] as $prDeliKey => $prDeliVal ){
                    $vender_deli_price += $prDeliVal['deli_price'];
                }
            }
            $vender_deli_price += $vender_deli[$vender]['deli_price'];
    }
?>
							<?=$deliver_text?><input type='hidden' name='select_price[<?=$vender?>]' value='<?=$vender_deli_price?>' data-vender='<?=$vender?>' ><strong><?=number_format( $vender_deli_price )?></strong>��
						</div>
					</article>
				</section>
				<section>
					<h4>�ֹ��ݾ�</h4>
					<div class="price total"><strong><?=number_format( $vender_price + $vender_deli_price )?></strong>��</div>
				</section>
			</div>
		</section><!-- //.cart-list-wrap -->
<?php
    if( $vender_info[$vender]['deli_select'] == '0' || $vender_info[$vender]['deli_select'] == '2' ) $deli_price += $vender_deli_price;
    if( $vender_info[$vender]['deli_select'] == '1' ) $deli_price2 += $vender_deli_price;
	$sumprice += $vender_price;
} // venderArr foreach
$p_price=$sumprice+$sumpricevat;

if( strlen( $_ShopInfo->getMemid() ) == 0 ){ // �α����� ������ ���
	$total_reserve	= 0;
}
?>

		<input type="hidden" name="total_sum" id='total_sum' value="<?=$p_price?>">
		<input type="hidden" name="total_sumprice" id='total_sumprice' value="<?=$p_price?>">
		<input type='hidden' name='total_deli_price' id='total_deli_price' value="<?=$deli_price?>" >
		<input type='hidden' name='total_deli_price2' id='total_deli_price2' value="<?=$deli_price2?>" >
		<div class="total_order">
			<ul class="clear">
				<li>��ǰ �ݾ� �հ�<strong><?=number_format( $sumprice )?>��</strong></li>
				<!--li>����<strong>600��</strong></li-->
				<li>��ۺ�<strong><?=number_format( $deli_price )?>��</strong></li>
			</ul>
			<div class="total_price">
				<label>�����ݾ�</label>
				<span class="point-color">�� <?=number_format( $sumprice + $deli_price )?></span>
			</div>
		</div>

		<div class="order_table">
            <div id = "ID_prd_coupon_layer"></div>
            <div id = "ID_bk_coupon_layer"></div>
            <div id = "ID_deli_coupon_layer"></div>
			<h3>���� �� ���� ����</h3>
			<table class="my-th-left">
				<colgroup>
					<col style="width:30%;">
					<col style="width:70%;">
				</colgroup>
				<tbody>
					<tr>
						<th>�� �ֹ��ݾ�</th>
						<td><?=number_format( $sumprice )?>��</td>
					</tr>

<?php
if ((strlen($_ShopInfo->getMemid())>0 && $_data->reserve_maxuse>=0 && $user_reserve!=0) || (strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y")) {

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
	if( strlen( $_ShopInfo->getMemid() ) > 0 && $_CouponInfo->coupon_yn === true && $staff_order == 'N' ) {
?>
					<tr>
						<th>�� ��������</th>
						<td>
							<span class='CLS_prCoupon'>0</span>�� 
<?
        if( $product_coupon_cnt > 0 ){
?>
							<button type="button" id='prd-coupon-button' class="btn_coupon_use btn-def" onClick="javascript:prd_coupon_pop();">�������</button>
<?php
        } else {
?>
							<button type="button" class="btn_coupon_use btn-def" onClick="javascript:alert('��� ������ ������ �����ϴ�.');">�������</button>
<?php
        }
?>

						</td>
					</tr>
					<tr class='hide'>
						<th>��������</th>
						<td>
							<span class='CLS_bCoupon'>0</span>��
<?php
        if( count( $basket_coupon ) > 0 ){
?>
                            <button type="button" id='bk-coupon-button' class="btn_coupon_use btn-def"
							onClick="javascript:bk_coupon_pop();">�������</button>
<?php
        } else {
?>
                            <button type="button" class="btn_coupon_use btn-def" onClick="javascript:alert('��� ������ ������ �����ϴ�.');">�������</button>
<?php
        }
?>
<?php
        if( count( $deliver_coupon ) > 0 && ( $deli_price + $deli_price2 ) > 0 ){
?>
							<button type="button" id='deli-coupon-button' class="btn_coupon_use btn-def"
onClick="javascript:deli_coupon_pop();">�������</a>
<?php
        }
?>
						</td>
					</tr>
<?
	}
?>

<?php
    if ( strlen( $_ShopInfo->getMemid() ) > 0 && $_data->reserve_maxuse >= 0 && $user_reserve != 0 ){
?>
					<tr class='hide'>
						<th>����Ʈ ���</th>
						<td>
						<input type="hidden" name="okreserve" id='okreserve' value="<?=$user_reserve?>">
<?php
        if( $_data->reserve_maxprice > $sumprice ) {
?>
                         <input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
        }else if( $user_reserve >= $_data->reserve_maxuse ){
?>
						 <div class="my">
							<input type="number" name="usereserve" id="mileage-use" >
							<button class="btn-function" type='button' name='mileage-on' >���</button>
							<span class="now"><strong id="current_mileage"><?=number_format( $user_reserve )?></strong>P</span>
<?php
        }else{
?>
                          <input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
        }
?>
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
<?php
 }
?>
					<tr>
						<th>�� ��ۺ�</th>
						<td><span class='CLS_before_deli'><?=number_format( $deli_price )?></span>��</td>
					</tr>
					<tr class='hide'>
						<th>�� ��ۺ�(�ĺ�)</th>
						<td><span class='CLS_after_deli'><?=number_format( $deli_price2)?></span>��</td>
					</tr>
					<tr>
						<th>�� �����ݾ�</th>
						<td><strong class="point-color"><span  id="price_sum"><?=number_format($sumprice + $deli_price)?></span>��</strong></td>
					</tr>
					<tr>
						<th>�� ���� ����Ʈ</th>
						<td><strong><?=number_format($total_reserve)?>P</strong></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="order_table">
			<h3>�ֹ��� ����</h3>
			<table class="my-th-left form_table">
				<colgroup>
					<col style="width:30%;">
					<col style="width:70%;">
				</colgroup>
				<tbody>
					<tr>
						<th>�ֹ���</th>
						<td><input type="text" title="�̸�" name="sender_name" value="<?=$userName?>"></td>
					</tr>
					<tr>
						<th>�̸���</th>
						<td><input type="email" title="�̸���" id="user-email" name='sender_email' value='<?=$email?>'></td>
					</tr>
					<tr>
						<th>�޴���ȭ</th>
						<td>
							<div class="tel-input">
								<div class="tel_select">
									<select name="sender_tel1" id="sender_tel1" class="select_def">
                                        <option value="010" <? if( $mobile[0] == '010' ) { echo 'selected'; } ?> >010</option>
                                        <option value="011" <? if( $mobile[0] == '011' ) { echo 'selected'; } ?> >011</option>
                                        <option value="016" <? if( $mobile[0] == '016' ) { echo 'selected'; } ?> >016</option>
                                        <option value="017" <? if( $mobile[0] == '017' ) { echo 'selected'; } ?> >017</option>
                                        <option value="018" <? if( $mobile[0] == '018' ) { echo 'selected'; } ?> >018</option>
                                        <option value="019" <? if( $mobile[0] == '019' ) { echo 'selected'; } ?> >019</option>
									</select>
								</div>
								<div><input type="tel" name="sender_tel2" id='sender_tel2' value="<?=$mobile[1] ?>" maxlength='4' ></div>
								<div><input type="tel" name="sender_tel3" id='sender_tel3' value="<?=$mobile[2] ?>" maxlength='4' ></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>��ȭ��ȣ<span class="sm">(����)</span></th>
						<td>
							<div class="tel-input">
								<div class="tel_select">
									<select name="home_tel1" id='home_tel1' class="select_def">
										<option value="02">02</option>
										<option value="031">031</option>
										<option value="032">032</option>
										<option value="033">033</option>
										<option value="041">041</option>
										<option value="042">042</option>
										<option value="043">043</option>
										<option value="044">044</option>
										<option value="051">051</option>
										<option value="052">052</option>
										<option value="053">053</option>
										<option value="054">054</option>
										<option value="055">055</option>
										<option value="061">061</option>
										<option value="062">062</option>
										<option value="063">063</option>
										<option value="064">064</option>
										<option value="010" >010</option>
										<option value="011" >011</option>
										<option value="016" >016</option>
										<option value="017" >017</option>
										<option value="018" >018</option>
										<option value="019" >019</option>
									</select>
								</div>
								<div><input type="tel" id='home_tel2' name="home_tel2" maxlength='4' value='' ></div>
								<div><input type="tel" id='home_tel3' name="home_tel3" maxlength='4' value='' ></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->

		<div class="order_table">
			<h3>
				���������
				<label><input type="checkbox" id='same_address' class="checkbox_custom">�ֹ����� ����</label>
				<button type="button" id="btn_shipping_list" class="btn-line">����� ���</button>
			</h3>
			<table class="my-th-left form_table">
				<colgroup>
					<col style="width:30%;">
					<col style="width:70%;">
				</colgroup>
				<tbody>
					<tr>
						<th>�޴� ���</th>
						<td><input type="text" title="�̸�" name = 'receiver_name' id='receiver_name' ></td>
					</tr>
					<tr>
						<th>�޴���ȭ</th>
						<td>
							<div class="tel-input">
								<div class="tel_select">
									<select name="receiver_tel21" id="receiver_tel21" class="select_def">
										<option value="010" >010</option>
										<option value="011" >011</option>
										<option value="016" >016</option>
										<option value="017" >017</option>
										<option value="018" >018</option>
										<option value="019" >019</option>
									</select>
								</div>
								<div><input type="tel" name="receiver_tel22" id='receiver_tel22' maxlength='4' ></div>
								<div><input type="tel" name="receiver_tel23" id='receiver_tel23' maxlength='4' ></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>��ȭ��ȣ<span class="sm">(����)</span></th>
						<td>
							<div class="tel-input">
								<div class="tel_select">
									<select name="receiver_tel11" id='receiver_tel11' class="select_def">
										<option value="02">02</option>
										<option value="031">031</option>
										<option value="032">032</option>
										<option value="033">033</option>
										<option value="041">041</option>
										<option value="042">042</option>
										<option value="043">043</option>
										<option value="044">044</option>
										<option value="051">051</option>
										<option value="052">052</option>
										<option value="053">053</option>
										<option value="054">054</option>
										<option value="055">055</option>
										<option value="061">061</option>
										<option value="062">062</option>
										<option value="063">063</option>
										<option value="064">064</option>
										<option value="010" >010</option>
										<option value="011" >011</option>
										<option value="016" >016</option>
										<option value="017" >017</option>
										<option value="018" >018</option>
										<option value="019" >019</option>
									</select>
								</div>
								<div><input type="tel" id='receiver_tel12' name="receiver_tel12" maxlength='4' value='' ></div>
								<div><input type="tel" id='receiver_tel13' name="receiver_tel13" maxlength='4' value='' ></div>
							</div>
						</td>
					</tr>
					<tr>
						<th>�ּ�</th>
						<td>
                            <div class="addr_post">
								<input type="tel" id="post" name='post' class=""><input type='hidden' id='post5' name='post5' value='' ><a href="javascript:openDaumPostcode();" class="btn-def">�ּ�ã��</a>
								<div id="addressWrap" style="display:none;position:fixed;overflow:hidden;z-index:9999;-webkit-overflow-scrolling:touch;">
								<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;width:20px;right:0px;top:-1px;z-index:9999" onclick="foldDaumPostcode()" alt="���� ��ư">
								</div>
							</div>
                            <input type="text" class="addr" name = 'raddr1' id = 'raddr1' >
                            <input type="text" class="addr" name = 'raddr2' id = 'raddr2' >
						</td>
					</tr>
					<tr>
						<th>���<br> ��û����</th>
						<td>
							<select id='prmsg_chg'  class="select_def">
								<option value="">�����Է�</option>
								<option value="����� ���ǿ� �ð� �ּ���">����� ���ǿ� �ð� �ּ���</option>
								<option value="����� ���տ� �����ּ���">����� ���տ� �����ּ���</option>
								<option value="������� �����ּ���">������� �����ּ���</option>
								<option value="������� ��Ź�����">������� ��Ź�����</option>
								<option value="��ȭ���� �־��ּ���">��ȭ���� �־��ּ���</option>
								<option value="����Կ� �־��ּ���">����Կ� �־��ּ���</option>
							</select>
							<input type="text" class='mt-5' name = 'order_prmsg' id="order_prmsg" placeholder="���� �Է����ּ���(�ѱ� 30�� �̳�)" title="��� ��û����"></td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->

		<div class="select_payment">
			<h3>���� ���� ����</h3>
			<div>
				<ul class="list_payment clear" name='payment-category' >
					<?if(strstr("YC", $_data->payment_type) && ord($_data->card_id)) {?>
					<li<?if ($staff_order == 'Y'){?> class='hide'<?}?>><a href="javascript:payment_select('C');"<?if ($staff_order == 'N'){?> class='on'<?}?>>�ſ�ī��</a></li>
					<?}?>
					<?if($escrow_info["onlycard"]!="Y" && ord($_data->trans_id)){?>
					<li<?if ($staff_order == 'Y'){?> class='hide'<?}?>><a href="javascript:payment_select('V');">�ǽð� ������ü</a></li>
					<?}?>
					<?if($escrow_info["onlycard"]!="Y" && ord($_data->virtual_id)){?>
					<li<?if ($staff_order == 'Y'){?> class='hide'<?}?>><a href="javascript:payment_select('O');">�������</a></li>
					<?}?>

					<?if(( $escrow_info["escrowcash"]=="A" || ($escrow_info["escrowcash"]=="Y" && (int)($sumprice+$deli_price)>=$escrow_info["escrow_limit"])) ){?>
					<?
						$pgid_info="";
						$pg_type="";
						$pgid_info=GetEscrowType($_data->escrow_id);
						$pg_type=trim($pgid_info["PG"]);
					?>
						<?if(strstr("ABCDG",$pg_type)){?>
					<li<?if ($staff_order == 'Y'){?> class='hide'<?}?>><a href="javascript:payment_select('Q');">����ũ��(�������)</a></li>
						<?}?>
					<?}?>
					<li<?if ($staff_order == 'N'){?> class='hide'<?}?>><a href="javascript:payment_select('G');"<?if ($staff_order == 'Y'){?> class='on'<?}?>>������ ����Ʈ</a></li>

					<li class='hide'><a href="javascript:payment_select('Y');"><img src="static/img/common/logo_payco.gif" alt="PAYCO"></a></li>
					<?if($escrow_info["onlycard"]!="Y" && strstr("YN", $_data->payment_type)) {?>
					<li class='hide'><a href="javascript:payment_select('B');">������ �Ա�</a></li>
					<?}?>
					<!-- <?php if(strlen($_ShopInfo->getMemid())>0 ) { ?>
					<li><a href="javascript:payment_select('M');">�޴��� ����</a></li>
					<li><a></a></li>
					<li><a></a></li>
					<?php } ?> -->

				</ul>
				<div class='hide' id='div_payment' >
					<!-- �ſ�ī�� -->
					<input id="dev_payment2" class="dev_payment radio-def" name="dev_payment" type="radio" value="C" onclick="sel_paymethod(this);" checked >
					<!-- ������ü -->
					<input id="dev_payment3" class="dev_payment radio-def" name="dev_payment" type="radio" value="V" onclick="sel_paymethod(this);" >
					<!-- ������� -->
					<input id="dev_payment4" class="dev_payment radio-def" name="dev_payment" type="radio" value="O" onclick="sel_paymethod(this);" >
					<!-- ������ -->
					<input id="dev_payment1" class="dev_payment radio-def" name="dev_payment" type="radio" value="B" onclick="sel_paymethod(this);" >
					<!-- �޴��� -->
					<input id="dev_payment6" class="dev_payment radio-def" name="dev_payment" type="radio" value="M" onclick="sel_paymethod(this);">
					<!-- PAYCO -->
					<input id="dev_payment7" class="dev_payment radio-def" name="dev_payment" type="radio" value="Y" onclick="sel_paymethod(this);">
					<!-- ����ũ�� -->
					<input id="dev_payment5" class="dev_payment radio-def" name="dev_payment" type="radio" value="Q" onclick="sel_paymethod(this);" >
					<!-- ������ ����Ʈ -->
					<input id="dev_payment8" class="dev_payment radio-def" name="dev_payment" type="radio" value="G" onclick="sel_paymethod(this);" >

					<input id="dev_payment99" class="dev_payment radio-def" name="dev_payment" type="radio" value="" onclick="sel_paymethod(this);" >
				</div>

				<!-- PAYCO -->
				<div class="pay-type" id="payco_notice">
					<p>PAYCO�� NHN�������θ�Ʈ�� ���� ������ ������� �����Դϴ�.</p><br><p>�޴����� ī�� �����ڰ� �����ؾ� ���� �����ϸ�, �����ݾ� ������ �����ϴ�.</p>
				</div>
				<!-- //PAYCO -->

				<!-- �������Ա� -->
				<div class="pay-type hide" id="card_type">
					<ul class="form-input">
						<li>
							<h4>���༱��</h4>
							<div class="select-def">
								<select name='pay_data_sel' id="pay_data_sel" onchange="sel_account(this)" >
									<option value="1">������¸� �������ּ���</option>
<?php
foreach($bank_payinfo as $k => $v){
?>
									<option value="<?=$v?>" ><?=$v?></option>
<?php
}
?>
								</select>
							</div>
						</li>
						<li>
							<h4>�Ա��� ����</h4>
							<input type="text" name="bank_sender" >
						</li>

					</ul>
				</div>
				<!-- //�������Ա� -->

				<dl class="attention hide"><!-- �⺻ �ȳ����� -->
					<dt>���ǻ���</dt>
					<dd>�ſ�ī��/�ǽð� ��ü�� ���� ��. ������ �Ա��� ����Ȯ�� �� ��۵˴ϴ�.</dd>
					<dd>���༳�� �� �ֹ���ȣ���� ������ ���Ը��� ������°� �����˴ϴ�.</dd>
					<dd>�Ϻ� �ڵ�ȭ ���� ����, �����Ա��� ���ѵ� �� ������ �Աݿ��� ������ ���� ��Ȯ�� �ݾ��� �Ա� �ٶ��ϴ�.</dd>
				</dl>

				<div class="agree">
					<input type="checkbox" id="dev_agree">
					<label for="dev_agree">
						<span class="agree_tit">�����մϴ�.</span>
						������ ��ǰ, ����, �������, ���� ���� ���� ���� Ȯ���Ͽ����ϴ�. (���ڻ�ŷ��� �� 8�� �� 2��)
					</label>
				</div>

				<div class="btn-place" name="paybuttonlayer" ><a href="javascript:CheckForm();" class="btn-point">�����ϱ�</a></div>
			</div>
        </div>
		<!-- //[D] 2016. �ۺ� �۾� -->

    </div><!-- //.cart-order-wrap -->

</main>
</form>
<!-- ��� �ּҷ� ��ũ��Ʈ -->
<script>
    $(document).ready( function() {
        //�ֱٹ�������� �ҷ���
        delivery_addr( '0', '1', true );
        payment_select( "<?=$staff_order == 'Y'?'G':'C'?>" );
        //$('ul[name="payment-category"] > li > a').eq(0).trigger('click');
    });
    // ����� �ּҺҷ�����
    function delivery_addr( block, gotopage, type ){
        var ul_tag    = '<ul class="addres-list"></ul>';
        var li_tag    = '<li></li>';
        var paing_tag = '<div class="paginate"><div class="box"></div></div>';
        if( $('#addr_list').data('memid') == '' ) return;
        $.ajax({
            method   : 'POST',
            url      : '../front/ajax_delivery_addr.php',
            data     : { gotopage : gotopage, block : block },
            dataType : 'json'
        }).done( function( data ) {
            $('#addr_list').html( ul_tag );
            var ul_element = $('#addr_list').find('ul');
            if( data.num_rows > 0 ){
                $.each( data.delivery_addr, function( i, obj ){
                    $( ul_element ).append( li_tag );
                    var last_li = $( ul_element ).find('li').last();
                    var html = '';
                    html += '<div class="local">';
                    html += '   <p class="name">' + obj.receiver_name + '</p>'
                    html += '   <p>(' + obj.zip_code + ') ' + obj.address1 + '  ' + obj.address2  + '</p>'
                    html += '   <div class="tel">';
                    html += '       <span>' + obj.receiver_tel2 + '</span>';
                    html += '       <span>' + obj.receiver_tel1 + '</span>';
                    html += '   </div>';
                    html += '</div>';
                    html += '<div class="btn">';
                    html += '   <button class="btn-function" type="button" name="delivery_select" ><span>����</span></button>';
                    html += '   <div>';
                    html += '       <input type="hidden" name="return_name" value="' + obj.receiver_name + '" >'
                    html += '       <input type="hidden" name="return_zipcode" value="' + obj.zip_code + '" >'
                    html += '       <input type="hidden" name="return_addr1" value="' + obj.address1 + '" >'
                    html += '       <input type="hidden" name="return_addr2" value="' + obj.address2 + '" >'
                    html += '       <input type="hidden" name="return_mobile" value="' + obj.receiver_tel2 + '" >'
                    html += '       <input type="hidden" name="return_tel" value="' + obj.receiver_tel1 + '" >'
                    html += '   </div>';
                    html += '</div>';
                    $( last_li ).html( html );
                });
                $('#addr_list').append( paing_tag );
                var paging_element = $('#addr_list').find('div.box');

                $( paging_element ).html( '<ul>' + data.paging.print_page + '</ul>' );
                if( data.paging.a_prev_page != '' ) $( paging_element ).prepend( data.paging.a_prev_page );
                if( data.paging.a_next_page != '' ) $( paging_element ).append( data.paging.a_next_page );

                if( type ) { //ù �������� �ҷ������� �ֱٹ���� ������ �ҷ��´�
                    $('#addr_list').find('ul > li').eq(0).find('button[name="delivery_select"]').trigger('click');
                }
            } else {
                $( ul_element ).append( li_tag );
                var last_li = $( ul_element ).find('li').last();
                var html = '<div class="local"><p>��� ����� �����ϴ�.</p></div>';
                $( last_li ).html( html );
            }
        });
    }
    // ����� paging
    function GoPage( block, gotopage ){
        delivery_addr( block, gotopage );
    }
    // ����� ����
    $(document).on( 'click', 'button[name="delivery_select"]', function( event ){
        var select_inputs = $(this).next().find('input');
        var name    = $( select_inputs ).eq(0).val();
        var zipcode = $( select_inputs ).eq(1).val();
        var addr1   = $( select_inputs ).eq(2).val();
        var addr2   = $( select_inputs ).eq(3).val();
        var mobile  = $( select_inputs ).eq(4).val();
        var tel     = $( select_inputs ).eq(5).val();

        var array_mobile = mobile.split( '-' );
        var array_tel    = tel.split( '-' );

        $('#receiver_name').val( name );
        $.each( $('#receiver_tel21').find('option'), function( i, obj ){
            if( $(this).val() == array_mobile[0] ){
                $(this).prop( 'selected', true );
            } else {
                $(this).prop( 'selected', false );
            }
        });
        $('#receiver_tel22').val( array_mobile[1] );
        $('#receiver_tel23').val( array_mobile[2] );
        $.each( $('#receiver_tel11').find('option'), function( i, obj ){
            if( $(this).val() == array_tel[0] ){
                $(this).prop( 'selected', true );
            } else {
                $(this).prop( 'selected', false );
            }
        });
        $('#receiver_tel12').val( array_tel[1] );
        $('#receiver_tel13').val( array_tel[2] );
        $('#post').val( zipcode );
        $('#raddr1').val( addr1 );
        $('#raddr2').val( addr2 );

        $('ul[name="delivery-category"] > li > a').eq(0).trigger('click');
    });
    // ����� ���� tab
    $('ul[name="delivery-category"] > li > a').click( function(){
        var tab_num = $('ul[name="delivery-category"] > li > a').index ( $(this) );

        if( tab_num == 0 ){ // �ֱٹ����
            $('#addr_input').removeClass('hide');
            $('#addr_list').addClass('hide');
        } else if( tab_num == 1 ) { // ����ּҷ�
            $('#addr_input').addClass('hide');
            $('#addr_list').removeClass('hide');
        } else if( tab_num == 2 ){ // ���ο� �ּ�
            $('#addr_input').removeClass('hide');
            $('#addr_list').addClass('hide');

             $('#receiver_name').val( '' );
            $.each( $('#receiver_tel21').find('option'), function( i, obj ){
                if( i == 0 ) $(this).prop( 'selected', true );
                else $(this).prop( 'selected', false );
            });
            $('#receiver_tel22').val( '' );
            $('#receiver_tel23').val( '' );
            $.each( $('#receiver_tel11').find('option'), function( i, obj ){
                if( i == 0 ) $(this).prop( 'selected', true );
                else $(this).prop( 'selected', false );
            });
            $('#receiver_tel12').val( '' );
            $('#receiver_tel13').val( '' );
            $('#post').val( '' );
            $('#raddr1').val( '' );
            $('#raddr2').val( '' );
        }
    });

	$(document).ready( function() {
		//�ֹ� �޼��� ����
		$("#prmsg_chg").on('change', function(){
			$('#order_prmsg').val( $(this).val() );
			if( $(this).val() == '' )
				$('#order_prmsg').focus();
		} );
	});
</script>
<!-- //��� �ּҷ� ��ũ��Ʈ -->
<!-- ������� ���� ��ũ��Ʈ -->
<script>
    function payment_select( paymethod ){
        if ( paymethod == "" ) {
            // ��� �ִ� ���� ������ Ŭ���� ���
            alert("���������� ������ �ּ���.");

            $('.list_payment a').removeClass("on");  // ���� ���� ������ �ʱ�ȭ
            $('#dev_payment99').trigger('click');       // �ƹ��͵� �������� ���� ������ ó��
            $('#dev_agree').prop('checked', false);     // ���ŵ��� ���þȵǰ� ó��
            return false;
        }

        //paymethod => �Ա�����
        switch( paymethod ){
            case 'C' : // �ſ�ī��
                $('#dev_payment2').trigger('click');
                break;
            case 'V' : // ������ü
                $('#dev_payment3').trigger('click');
                break;
            case 'O' : // �������
                $('#dev_payment4').trigger('click');
                break;
            case 'B' : // ������
                $('#dev_payment1').trigger('click');
                break;
            case 'M' : // �޴���
                $('#dev_payment6').trigger('click');
                break;
            case 'Y' : // PAYCO
                $('#dev_payment7').trigger('click');
                break;
            case 'Q' : // ����ũ��
                $('#dev_payment5').trigger('click');
                break;
            case 'G' : // ����ũ��
                $('#dev_payment8').trigger('click');
                break;
            default : // �ƹ��͵� �������� ���� ���
                $('#dev_payment99').trigger('click');
                break;
        }
    }
</script>
<!-- //������� ���� ��ũ��Ʈ -->

<!-- ���� -->
<script>
    var _before_deliprice  = 0; // ���� ��۷�
    var _after_deliprice   = 0; // ���� ��۷�
    var _change_after_deli = 0; // ������ ���� ��۷�

    var _sum_price         = 0; // ��ǰ ������
	var _total_prdc        = 0; // ��ǰ������
	var _total_bdc         = 0; // ��ٱ��� ������
    var _before_deli_dc    = 0; // ���� ��ۺ� ����
    var _after_deli_dc     = 0; // �ĺ� ��ۺ� ����
	var _tital_mileage     = 0; // ���ϸ���

    var _prCoupon_area     = null; // ��ǰ�� ����
    var _prCouponObj       = [];   // ��ǰ���� ����
    var _bkCouponObj       = {};   // ��ٱ������� ����
    var _deliCouponObj     = {};   // ��ۺ� �������� ����

    var useand_pc_yn = "<?=$_CouponInfo->coupon['useand_pc_yn']?>";
    var all_type     = "<?=$_CouponInfo->coupon['all_type']?>";
    // ���� ���� �ʱ�ȭ
    $(document).ready( function() {

        _prCoupon_area    = $('li[name="product_area"]');
        _sum_price        = parseInt( $('#total_sum').val() );  // �� ��ǰ ����
        _before_deliprice = parseInt( $('#total_deli_price').val() ); // ���� ��۷�
        _after_deliprice  = parseInt( $('#total_deli_price2').val() ); // ���� ��۷�

        // ��ǰ���� obj�� �ʱ�ȭ
        $.each( _prCoupon_area, function( _i, _obj ) {
            var prCoupon = $(this).find('input:radio:checked');
            var tmp_obj = {
                "basketidx"     : prCoupon.data('basketidx'),
                "ci_no"         : prCoupon.val(),
                "coupon_code"   : prCoupon.data('cp-code'),
                "product_price" : prCoupon.data('pr-price')
            };
            _prCouponObj[_i] = { "obj" : tmp_obj };
        });

        // ��ٱ��� ���� obj�� �ʱ�ȭ
        _bkCouponObj = {
            "ci_no"       : "",
            "type"        : "",
            "coupon_code" : "",
            "coupon_type" : "",
            "sellprice"   : _sum_price,
            "dc"          : 0
        };

        // ��ۺ� ���� ���� obj�� �ʱ�ȭ
        _deliCouponObj = {
            "ci_no"       : "",
            "type"        : "",
            "coupon_code" : "",
            "coupon_type" : "",
            "sellprice"   : _sum_price,
            "dc"          : 0
        }

    });
    // ��ǰ���� Ŭ����
    $(document).on( 'click', 'input[name^=product_coupon]', function (event) {
        var area_idx    = _prCoupon_area.index( $(this).parent().parent().parent().parent().parent() );
        var basketidx   = $(this).data('basketidx');
        var ci_no       = $(this).val();
        var coupon_code = $(this).data('cp-code');
        var price       = $(this).data('pr-price');
        var tmp_obj     = {
            "basketidx"     : basketidx,
            "ci_no"         : ci_no,
            "coupon_code"   : coupon_code,
            "product_price" : price
        };

        if( _prCouponObj[area_idx].obj.ci_no == ci_no ) return; // ���� �缱�� ����

        $.each( _prCoupon_area, function( _i, _obj ) {
            $.each( $(this).find('input:radio'), function( i, obj ){
                if( basketidx != $(this).data('basketidx') ){
                    if( ci_no == $(this).val() && ci_no != '' ){ // ���� ������ �ߺ� ó���� �ȵǰ� disabled ��Ų��
                        $(this).attr( 'disabled', 'true' );
                    }
                    if( _prCouponObj[area_idx].obj.ci_no == $(this).val() ){ // ���� ���õǾ��� ������ disabled�� Ǯ���ش�
                        $(this).removeAttr( 'disabled' );
                    }
                }
            });

        });
        $.extend( _prCouponObj[area_idx].obj, tmp_obj );
    });

    //��ǰ���� ����
    $(document).on( 'click', '#pr-coupon-choise', function() {
        $('#product-coupon').fadeOut();
        $('#o-coupon-layer').hide();
        pc_price_sum();
    });
    //��ǰ���� ���
    $(document).on( 'click', '#pr-coupon-cancel', function() {
        $('#product-coupon').fadeOut();
        $('#o-coupon-layer').hide();
        prd_coupon_cancel();
    });

    // ��ǰ ���� �˾�
    function prd_coupon_pop(){
        //coupon_use_type( 1 );  // type 1 -> ��ǰ type 2 -> ��ٱ��� type 3 -> ���ϸ���
        var type = reset_dc();
        if( type && coupon_use_type( 1 ) ){
            $('#product-coupon').show();
            $('#basket-coupon').hide();
            $('#delivery-coupon').hide();
            $('#o-coupon-layer').fadeIn();
        }
    }
    // ��ǰ ���� �˾��ݱ�
    function prd_coupon_close(){
        $('#o-coupon-layer').hide();
        $('#product-coupon').fadeOut();
    }
    // ��ǰ ���� ��� / �ʱ�ȭ
    function prd_coupon_cancel(){
        $.each( _prCoupon_area, function( _i, _obj ) {
            $(this).find('input:radio').last().trigger('click');
        });
        pc_price_sum();
    }
    // ��ǰ���� ������ ��� �� �ջ�
    function pc_price_sum(){
        _total_prdc = 0;

        $.each( _prCouponObj , function( _i, _obj ){
            if( _obj.obj.ci_no != '' ){
                $.ajax({
                    method : "POST",
                    url : "../front/ajax_coupon_select.php",
                    data : { mode : 'P01', sellprice : _obj.obj.product_price , ci_no : _obj.obj.ci_no },
                    dataType : "json"
                }).done ( function( data ){
                    if( data.mini_price > _sum_price ){
                        alert('���� �ݾ��� ' + comma( data.mini_price ) + '�̻� �ֹ��� �����մϴ�.' );
                        prd_coupon_cancel();
                    } else {
                        var tmp_obj = {
                            coupon_type : data.coupon_type,
                            dc : data.dc,
                            type : data.type
                        }
                        $.extend( _obj.obj, tmp_obj );
                        _total_prdc += parseInt(data.dc);
                        total_dc_html();
                        $('#prd-coupon-button').html('������� �Ϸ�');
                    }
                });
            } else {
                $.extend( _obj.obj, { "dc" : 0 } );
                total_dc_html();
                $('#prd-coupon-button').html('�������');
            }
        });
    }
    //��ٱ��� ���� �˾�
    function bk_coupon_pop(){
        //coupon_use_type( 2 );  // type 1 -> ��ǰ type 2 -> ��ٱ��� type 3 -> ���ϸ���
        var type = reset_dc();
        if( type && coupon_use_type( 2 ) ){
            $('#o-coupon-layer').fadeIn();
			$('#basket-coupon').show();
			$('#product-coupon').hide();
			$('#delivery-coupon').hide();
        }
    }
    // ��ǰ ���� �˾��ݱ�
    function bk_coupon_close(){
        $('#o-coupon-layer').hide();
        $('#basket-coupon').fadeOut();
    }
    // ��ٱ��� ���� Ŭ��
    $(document).on( 'click', 'input[name="basket_coupon"]', function( event ){
        event.stopPropagation(); // �θ� ������ ����
        var ci_no       = $(this).val();
        var coupon_code = $(this).data('cp-code');
        var tmp_obj     = {
            "ci_no"       : ci_no,
            "coupon_code" : coupon_code,
        }

        $.extend( _bkCouponObj, tmp_obj );

    });

    // ��ٱ��� ���� ����
    $(document).on( 'click', '#bk-coupon-choise', function() {
        $('#basket-coupon').fadeOut();
        $('#o-coupon-layer').hide();
        bk_price_sum();
    });

    // ��ٱ��� ���� ���
    $(document).on( 'click', '#bk-coupon-cancel', function() {
        $('#basket-coupon').fadeOut();
        $('#o-coupon-layer').hide();
        bk_coupon_cancel();
    });
    $(document).on( 'click', 'strong[name="basket-coupon-label"]', function() {
        var list_idx = $('strong[name="basket-coupon-label"]').index( $(this) );
        $('input[name="basket_coupon"]').eq( list_idx ).trigger('click');
    });
    // ��ٱ��� ���� Ŭ��
    /*
    $(document).on( 'click', 'div[name="basket-coupon-wrap"]', function() {
        //event.stopPropagation();
        var list_idx = $('div[name="basket-coupon-wrap"]').index( $(this) );
        $('input[name="basket_coupon"]').eq( list_idx ).trigger('click');
        $('#bk-coupon-choise').trigger('click');
    });
    */
    //��ٱ��� ���� ������ �ջ�
    function bk_price_sum(){
        _total_bdc = 0;
        if( _bkCouponObj.ci_no != '' ){
            $.ajax({
                method : "POST",
                url : "../front/ajax_coupon_select.php",
                data : { mode : 'B01', ci_no : _bkCouponObj.ci_no, sellprice : ( _sum_price - _total_prdc ) },
                dataType : "json"
            }).done( function( data ) {
                if( data.mini_price > _sum_price ){
                    alert('���� �ݾ��� ' + comma( data.mini_price ) + '�̻� �ֹ��� ����� �����մϴ�.' );
                    bk_coupon_cancel()
                } else {
                    $.extend( _bkCouponObj, data );
                    _total_bdc = data.dc;
                    total_dc_html();
                    $('#bk-coupon-button').html('������� �Ϸ�');
                }
            });
        } else {
            $.extend( _bkCouponObj, { "dc" : 0 } );
            total_dc_html();
            $('#bk-coupon-button').html('�������');
        }
    }
    // ��ٱ��� ���� ���
    function bk_coupon_cancel(){
        $('input[name="basket_coupon"]').last().trigger('click');
        bk_price_sum();
    }

    //���ϸ��� �Է�
    $(document).on( 'click', 'button[name="mileage-on"]', function() {
        //coupon_use_type( 3 );  // type 1 -> ��ǰ type 2 -> ��ٱ��� type 3 -> ���ϸ���
        var element   = $("#mileage-use");
        var okreserve = parseInt( $('#okreserve').val() );
        var mileage   = parseInt( $( element ).val() );
        var sum_price = _sum_price + ( _before_deliprice - _change_after_deli ) - _total_prdc - _total_bdc;

        if( $( element ).val().length > 0 && coupon_use_type( 3 ) ){
            if( okreserve < mileage ) {
                if( okreserve > sum_price ){
                    alert('���� �ݾ׺��� ū ���� �Է��߽��ϴ�.');
                    $( element ).val( sum_price );
                } else {
                    alert('���� ���ϸ������� ū ���� �Է��߽��ϴ�.');
                    $( element ).val( okreserve );
                }
            } else {
                if( mileage > sum_price ){
                    alert('���� �ݾ׺��� ū ���� �Է��߽��ϴ�.');
                    $( element ).val( sum_price );
                    $("#current_mileage").html(0);
                } else {
                    $( element ).val( mileage );
                    $("#current_mileage").html(comma(okreserve - mileage));
                }
            }
        } else {
            $( element ).val( 0 );
        }

        set_mileage();
        total_dc_html();
    });
    // ���ϸ��� ����
    function set_mileage(){
        _tital_mileage = parseInt( $("#mileage-use").val() );
    }
    //���ϸ��� ���
    function mileage_cancel(){
        $("#mileage-use").val( 0 );
        _tital_mileage = parseInt( $("#mileage-use").val() );
    }
    // ��ۺ� �������� �˾�
    function deli_coupon_pop(){
		$('#basket-coupon').hide();
		$('#product-coupon').hide();
		$('#delivery-coupon').show();
        $('#o-coupon-layer').fadeIn();
    }

    // ��ۺ� �������� �˾��ݱ�
    function deli_coupon_close(){
        $('#o-coupon-layer').hide();
        $('#delivery-coupon').fadeOut();
    }

    // ��ۺ� ���� ���� ����
    $(document).on( 'click', '#deli-coupon-choise', function() {
        $('#delivery-coupon').fadeOut();
        $('#o-coupon-layer').hide();
        deli_price_sum();
    });

    // ��ۺ� ���� ���� ���
    $(document).on( 'click', '#deli-coupon-cancel', function() {
        $('#delivery-coupon').fadeOut();
        $('#o-coupon-layer').hide();
        deli_coupon_cancel();
    });

    // ��ۺ� ���� ���� Ŭ��
    $(document).on( 'click', 'input[name="delivery_coupon"]', function( event ){
        var ci_no       = $(this).val();
        var coupon_code = $(this).data('cp-code');
        var tmp_obj     = {
            "ci_no"       : ci_no,
            "coupon_code" : coupon_code,
        }
        if( delivery_price_check() ){
            $.extend( _deliCouponObj, tmp_obj );
            $('#deli-coupon-button').html('������� �Ϸ�');
        } else {
            //deli_coupon_cancel();
            //alert('��۷� ������ ����� �� �����ϴ�.');
            $('#deli-coupon-button').html('�������');
        }
    });
    //��ۺ� ���� ���� ������ �ջ�
    function deli_price_sum(){
        _before_deli_dc = 0;
        _after_deli_dc  = 0;
        if( _deliCouponObj.ci_no != '' ){
            $.extend( _deliCouponObj, { "dc" : _before_deliprice + _after_deliprice } );
            _before_deli_dc = _before_deliprice - _change_after_deli;
            _after_deli_dc  = _after_deliprice + _change_after_deli;
            total_dc_html();
        } else {
            $.extend( _deliCouponObj, { "dc" : 0 } );
            total_dc_html();
        }
    }
    // ��ۺ� ���� ���
    function deli_coupon_cancel(){
        $('input[name="delivery_coupon"]').last().trigger('click');
        deli_price_sum();
    }
    // ��ۺ� ������ ��� �������� üũ�Ѵ�
    function delivery_price_check(){
        var type = false;

        if( _before_deliprice > 0 || _after_deliprice > 0 ){ // ��۷ᰡ �����ϴ��� üũ
            if( _tital_mileage > 0 ){
                alert('���ϸ��� ����� ���� �˴ϴ�.');
                mileage_cancel();
            }
            type = true;
        }

        return type;
    }
    function coupon_use_type( type ){ // type 1 -> ��ǰ type 2 -> ��ٱ��� type 3 -> ���ϸ���
        var bdc_leng = 0;
		var prc_leng = 0;
        var return_data = false;

        if( _bkCouponObj.ci_no != '' ) {
            bdc_leng++;
        }

        $.each( _prCouponObj, function( _i, _obj ){
            if( _obj.obj.ci_no != '' ) {
                prc_leng++;
            }
        });

        // ���� ���ϸ��� ���û�� �Ұ�
        if( all_type != 'Y' ){
            return_data = true;
            if( ( type == 1 || type == 2 ) && _tital_mileage > 0 ){
                if( confirm( "������ ���ϸ����� ���û���� �Ұ����մϴ�.\n���ϸ����� �ٽ� �����Ͻðڽ��ϱ�?" ) ){
                    mileage_cancel();
                    total_dc_html();
                } else {
                    prd_coupon_cancel();
                    bk_coupon_cancel();
                    total_dc_html();
                }
            } else if( type == 3 && ( prc_leng > 0 || bdc_leng > 0 ) ) {
                if( confirm( "������ ���ϸ����� ���û���� �Ұ����մϴ�.\n������ �ٽ� �����Ͻðڽ��ϱ�?" ) ){
                    prd_coupon_cancel();
                    bk_coupon_cancel();
                    total_dc_html();
                } else {
                    mileage_cancel();
                    total_dc_html();
                    return_data = false;
                }
            }
        } else {
            return_data = true;
        }

        bdc_leng = 0;
        prc_leng = 0;
        if( _bkCouponObj.ci_no != '' ) {
            bdc_leng++;
        }

        $.each( _prCouponObj, function( _i, _obj ){
            if( _obj.obj.ci_no != '' ) {
                prc_leng++;
            }
        });

        // ��ǰ / ��ٱ��� ���� ���û�� X
        if( return_data ){
            if( useand_pc_yn != 'Y' ){
                /* ��ٱ��� ���� ������ ��ǰ������ �ʱ�ȭ��
                if( type == 1 && bdc_leng > 0 ){
                    if( confirm( "��ǰ������ ���������� ���û���� �Ұ����մϴ�.\n������ �ٽ� �����Ͻðڽ��ϱ�?" ) ){
                        bk_coupon_cancel();
                        total_dc_html();
                    }
                } else
                */
                if( type == 2 && prc_leng > 0 ){
                    if( confirm( "��ǰ������ ���������� ���û���� �Ұ����մϴ�.\n������ �ٽ� �����Ͻðڽ��ϱ�?" ) ){
                        prd_coupon_cancel();
                        total_dc_html();
                        return_data = true;
                    } else {
                        //bk_coupon_cancel();
                        //total_dc_html();
                        return_data = false;
                    }
                }
            }
        }

        return return_data;

    }
    // ��� ������ ���ϸ����� �ʱ�ȭ �Ѵ�
    function reset_dc(){
        // type 0 - ����ó��
        // type 1 - ��ǰ���� / ��ٱ��� ���� / ���ϸ��� ���
        // type 2 - ��ǰ���� / ��ٱ��� ���� ���
        // type 3 - ��ǰ���� ���

        var resetType = false;
        var msg       = '';
        var type      = coupon_state();

        if( type == 1 ) msg = '��ǰ���� / �������� / ���ϸ����� ';
        else if( type == 2 ) msg = '��ǰ���� / ����������  ';
        else if( type == 3 ) msg = '��ǰ������ ';

        if( type == 1 ||  type == 2 || type == 3 ){
            if( confirm('������ ' + msg + ' �ʱ�ȭ �˴ϴ�. ������ �ٽ� �����Ͻðڽ��ϱ�?') ){
                resetType = true;
            }
        }

        if( resetType ){
            if( type == 1 || type == 2 || type == 3 ) prd_coupon_cancel();
            if( type == 1 || type == 2 ) bk_coupon_cancel();
            if( type == 1 ) mileage_cancel();

            total_dc_html();
        }

        if( type == 0 ) resetType = true;

        return resetType;
    }
    // ���� / ���ϸ����� üũ�Ѵ�
    function coupon_state(){
        var type = 0;

        if( _tital_mileage > 0 ) {
            type = 1;
        } else if( _bkCouponObj.ci_no != '' ) {
            type = 2;
        }
        /*
        if( _bkCouponObj.ci_no != '' ) {
            $.each( _prCouponObj, function( _i, _obj ){
                if( _obj.obj.ci_no != '' ) {
                    type = 3;
                }
            });
        }
        */

        return type;
    }
    // ��ü ������ ����ؼ� ȭ�鿡 �ѷ���
    function total_dc_html() {
        var tmp_total_dc = 0;
        var tmp_price_sum = 0;
        tmp_total_dc = _sum_price + ( _before_deliprice - _before_deli_dc - _change_after_deli ) - _total_prdc - _total_bdc - _tital_mileage;
        if( tmp_total_dc >= 0 ){
            tmp_price_sum = tmp_total_dc;
        } else {
            tmp_price_sum = 0;
        }
        $('.CLS_after_deli').html( comma( _after_deliprice - _after_deli_dc + _change_after_deli ) );
        $('.CLS_before_deli').html( comma( _before_deliprice - _before_deli_dc - _change_after_deli ) );
        $('.CLS_prCoupon').html( comma( _total_prdc ) );
        $('.CLS_bCoupon').html( comma( _total_bdc ) );
        $('.CLS_saleMil').html( comma( _tital_mileage ) );
        $('#price_sum').html( comma( tmp_price_sum ) );
        $('#total_deli_price').val( _before_deliprice - _change_after_deli );
        $('#total_deli_price2').val( _after_deliprice + _change_after_deli );
        $('#total_sumprice').val( tmp_total_dc - ( _before_deliprice - _before_deli_dc - _change_after_deli ) );
    }
    // ������ �Ѿ �������� ���̾ �Ѱ��ش�
    function set_coupon_layer(){
        var prd_layer        = $('#ID_prd_coupon_layer'); // ��ǰ������ ��� ���̾� ��ġ
        var bk_layer         = $('#ID_bk_coupon_layer');  // ��ٱ��� ������ ��� ���̾� ��ġ
        var deli_layer       = $('#ID_deli_coupon_layer');  // ��ٱ��� ������ ��� ���̾� ��ġ
        var pr_coupon_html   = '';
        var bk_coupon_html   = '';
        var deli_coupon_html = '';

        // ��ǰ����
        $.each( _prCouponObj, function( _i, _obj ){
            if( _obj.obj.ci_no != '' ){
                pr_coupon_html += '<input type="hidden" name="prcoupon_bridx[]" value="' + _obj.obj.basketidx + '" >';
                pr_coupon_html += '<input type="hidden" name="prcoupon_ci_no[]" value="' + _obj.obj.ci_no + '" >';
            }
        });
        // ��ٱ��� ����
        if( _bkCouponObj.ci_no != '' ){
            bk_coupon_html += '<input type="hidden" name="bcoupon_ci_no[]" value="' + _bkCouponObj.ci_no + '" >';
        }
        //��ۺ� ����
        if( _deliCouponObj.ci_no != '' ) {
            deli_coupon_html += '<input type="hidden" name="dcoupon_ci_no" value="' + _deliCouponObj.ci_no + '" >';
            deli_coupon_html += '<input type="hidden" name="dcoupon_price" value="' + _deliCouponObj.dc + '" >';
        }

        $( prd_layer ).html( pr_coupon_html );
        $( bk_layer ).html( bk_coupon_html );
        $( deli_layer ).html( deli_coupon_html );

    }
    // ��۷� ��/���� ����
    $(document).on( 'change', 'select[name^="deli_select"]', function(){
        var vender            = $(this).attr('data-vender');
        var select_type       = $(this).val();
        var vender_deli_price = parseInt( $('input[name="select_price[' + vender + ']"]').val() );
        var msg               = '';
        var msg_type          = false;

        if( _deliCouponObj.ci_no != '' ){
            //alert('��ۺ� ���� ������ ���� �˴ϴ�.');
            msg      += ' ��ۺ� ';
            msg_type = true;
            deli_coupon_cancel();
        }

        if( _tital_mileage > 0 ){
            //alert('���ϸ��� ����� ���� �˴ϴ�.');
            if( msg_type ) msg += ', ���ϸ��� ';
            else msg += ' ���ϸ��� ';
            msg_type = true;
            mileage_cancel();
        }

        if( msg_type ) alert( msg + ' ����� ���� �˴ϴ�.');

        if( select_type == 1 ){
            _change_after_deli = _change_after_deli + vender_deli_price;
        } else {
            _change_after_deli = _change_after_deli - vender_deli_price;
        }

        total_dc_html();

    });

</script>
<!-- //���� -->


<DIV id="PAYWAIT_LAYER" style='position:absolute; left:50px; top:120px; width:503; height: 255; z-index:1; display:none'><a href="JavaScript:PaymentOpen();"><img src="<?=$Dir?>images/paywait.gif" align=absmiddle border=0 name=paywait galleryimg=no></a></DIV>
<IFRAME id="PAYWAIT_IFRAME" name="PAYWAIT_IFRAME" style="left:50px; top:120px; width:503; height: 255; position:absolute; display:none"></IFRAME>
<!--IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME style="display:''" width=100% height=300></IFRAME-->
<!--IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME width="100%" height="500" <?if(!isdev()){?>style="display:none"<?}?>></IFRAME-->
<IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME width="100%" height="500" style="display:none"></IFRAME>
<IFRAME id='CHECK_PAYGATE' name='CHECK_PAYGATE' style='display:none'></IFRAME>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    // �����ȣ ã�� ã�� ȭ���� ���� element
    var element_layer = document.getElementById('addressWrap');

    function foldDaumPostcode() {
        // iframe�� ���� element�� �Ⱥ��̰� �Ѵ�.
        element_layer.style.display = 'none';
    }

    function openDaumPostcode() {
        // ���� scroll ��ġ�� �����س��´�.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

                // �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
                // �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
                var fullAddr = data.address; // ���� �ּ� ����
                var extraAddr = ''; // ������ �ּ� ����

                // �⺻ �ּҰ� ���θ� Ÿ���϶� �����Ѵ�.
                if(data.addressType === 'R'){
                    //���������� ���� ��� �߰��Ѵ�.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // �ǹ����� ���� ��� �߰��Ѵ�.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // �������ּ��� ������ ���� ���ʿ� ��ȣ�� �߰��Ͽ� ���� �ּҸ� �����.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ִ´�.
                document.getElementById('post').value = data.zonecode; //5�ڸ� �������ȣ ���
                document.getElementById('raddr1').value = fullAddr;
                document.getElementById('raddr2').value = "";
	 			document.getElementById('raddr2').focus();

                // iframe�� ���� element�� �Ⱥ��̰� �Ѵ�.
                // (autoClose:false ����� �̿��Ѵٸ�, �Ʒ� �ڵ带 �����ؾ� ȭ�鿡�� ������� �ʴ´�.)
                element_layer.style.display = 'none';

                // �����ȣ ã�� ȭ���� ���̱� �������� scroll ��ġ�� �ǵ�����.
                document.body.scrollTop = currentScroll;
            },
            // �����ȣ ã�� ȭ�� ũ�Ⱑ �����Ǿ����� ������ �ڵ带 �ۼ��ϴ� �κ�. iframe�� ���� element�� ���̰��� �����Ѵ�.
            onresize : function(size) {
            		//console.log("Size:", size, element_layer)
                //element_layer.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_layer);

        // iframe�� ���� element�� ���̰� �Ѵ�.
        element_layer.style.display = 'block';

        // iframe�� ���� element�� ��ġ�� ȭ���� ����� �̵���Ų��.
        initLayerPosition();
    }

    // �������� ũ�� ���濡 ���� ���̾ ����� �̵���Ű���� �ϽǶ�����
    // resize�̺�Ʈ��, orientationchange�̺�Ʈ�� �̿��Ͽ� ���� ����ɶ����� �Ʒ� �Լ��� ���� ���� �ֽðų�,
    // ���� element_layer�� top,left���� ������ �ֽø� �˴ϴ�.
    function initLayerPosition(){
        var width = (window.innerWidth || document.documentElement.clientWidth)-20; //�����ȣ���񽺰� �� element�� width
        var height = (window.innerHeight || document.documentElement.clientHeight)-200; //�����ȣ���񽺰� �� element�� height
        var borderWidth = 1; //���ÿ��� ����ϴ� border�� �β�

        // ������ ������ ������ ���� element�� �ִ´�.
        element_layer.style.width = width + 'px';
        element_layer.style.height = height + 'px';
        element_layer.style.border = borderWidth + 'px solid';
        // ����Ǵ� ������ ȭ�� �ʺ�� ���� ���� �����ͼ� �߾ӿ� �� �� �ֵ��� ��ġ�� ����Ѵ�.
        element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
        element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
    }
</script>

<? include_once('outline/footer_m.php'); ?>
