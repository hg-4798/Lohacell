<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/basket.class.php");
include_once($Dir."lib/delivery.class.php");

//$basketidxs = $_POST['basketidxs'];

// 장바구니에 들어가있는 힛딜 상품들을 삭제한다.
if ( strlen($_ShopInfo->getMemid()) > 0 ) {
	// 로그인
	$directQuery = "a.id = '" . $_ShopInfo->getMemid() . "' ";
} else {
	// 비로그인
	$directQuery = "a.tempkey='".$_ShopInfo->getTempkey()."' AND a.id = '' ";
}
$query="delete from tblbasket where basketidx in (select max(a.basketidx) from tblbasket a 
		left join tblproduct b on(a.productcode=b.productcode) 
		where b.hotdealyn='Y' and ".$directQuery." group by a.basketidx)";
pmysql_query($query);
////////////////////////////////////////////////////////////////

# 매장 픽업 / 당일 수령 장바구니 상품 삭제
delDeliveryTypeData();





$Basket = new Basket(); //장바구니 초기화를 위해 불러온다 
$Basket->revert_item(); // 주문실패한 상품을 되돌린다.
$Delivery = new Delivery();

//상품 이미지 경로
function obejct_setting( $basket )
{
	$basket_object = '';
	$opt1 = '';
	$opt2 = '';
	$reserve = 0;
	$option_code = '';
	$option_price = 0;
	$option_quantity = 0;
	$option_type = 0;

	//ERP 상품을 쇼핑몰에 업데이트한다.
	if ($basket->opt1_idx == 'SIZE') {
		getUpErpProductUpdate($basket->productcode, $basket->opt2_idx);
	}

	$pr_sql = "SELECT pridx, productcode, productname, sellprice, consumerprice, ";
	$pr_sql.= "buyprice, reserve, reservetype, quantity, option1, option2, addcode, ";
	$pr_sql.= "maximage, minimage, tinyimage, deli, deli_price, display, selfcode, ";
	$pr_sql.= "vender, brand, min_quantity, max_quantity, setquota, supply_subject, deli_qty, ";
	$pr_sql.= "quantity, option2_tf ";
	//$sql.= "detail_deli, deli_min_price, deli_package ";
	$pr_sql.= "FROM tblproduct WHERE productcode = '".$basket->productcode."' ";
	$pr_result = pmysql_query( $pr_sql, get_db_conn() );
	$pr_row = pmysql_fetch_object( $pr_result );
	$select_product = $pr_row;
	pmysql_free_result( $pr_result );
	
	#상품별 적립금 세팅 '$reserveshow' 위치확인 필요
	$reserve = getReserveConversion( $select_product->reserve, $select_product->reservetype, ( $select_product->sellprice + $basket->pricearr ) * $basket->quantity , "N" );
	
	$opt1 = $basket->opt1_idx;
	$opt2 = $basket->opt2_idx;
	$option_price = $basket->pricearr;
	$option_type = $basket->op_type;
	$option_quantity = $basket->quantity;
	$text_opt_subject = $basket->text_opt_subject;
	$text_opt_content = $basket->text_opt_content;
	
	#기초정보 세팅
	$basket_object = array( 
		'basketidx'=>$basket->basketidx,
		'vender'=>$select_product->vender,
		'brand'=>$select_product->brand,
		'productcode'=>$select_product->productcode,
		'productname'=>$select_product->productname,
		'pr_quantity'=>$select_product->quantity,
		'price'=>$select_product->sellprice,
		'quantity'=>$basket->quantity,
		'reserve'=>$reserve,
		'selfcode'=>$select_product->selfcode,
		'addcode'=>$select_product->addcode,
		'tinyimage'=>$select_product->tinyimage,
		'opt1_name'=>$opt1,
		'opt2_name'=>$opt2,
		'text_opt_subject'=>$text_opt_subject,
		'text_opt_content'=>$text_opt_content,
		'text_opt_tf'=>$select_product->option2_tf,
		'option_price'=>$option_price,
		'option_quantity'=>$option_quantity,
		'option_type'=>$option_type,
		'deli'=>$select_product->deli,
		'deli_price'=>$select_product->deli_price,
		'deli_qty'=>$select_product->deli_qty, 
		'delivery_type'=>$basket->delivery_type,
		'reservation_date'=>$basket->reservation_date,
		'store_code'=>$basket->store_code,
		'post_code'=>$basket->post_code,
		'store_code'=>$basket->store_code,
		'address1'=>$basket->address1,
		'address2'=>$basket->address2,
		'prodcode'=>$basket->prodcode,
		'colorcode'=>$basket->colorcode
		//'detail_deli'=>$select_product->detail_deli,
		//'deli_min_price'=>$select_product->deli_min_price,
		//'deli_package'=>$select_product->deli_package
	);

	return $basket_object;
}

function basket_option( $productcode , $option_code = '', $option_type = 0 ){

	$option_sql = "SELECT option_num, option_code, productcode, option_price, option_quantity, option_quantity_noti, option_type, option_use  ";
	$option_sql.= "FROM tblproduct_option WHERE productcode = '".$productcode."' AND option_type = '".$option_type."' AND option_use = 1 ";
	if( strlen( $option_code ) > 0 ) $option_sql.= "AND option_code = '".$option_code."' ";
	$option_sql.= "ORDER BY option_num ASC ";
	$option_result = pmysql_query( $option_sql, get_db_conn() );
	while( $option_row = pmysql_fetch_object( $option_result ) ){
		$select_option[] = $option_row;
	}
	
	pmysql_free_result( $option_result );

	return $select_option;
}

foreach( $Basket->basket as $bkVal ){
	$basket[] = obejct_setting( $bkVal );

	//exdebug($bkVal);

	# 상품정보
	$bkProduct = $Basket->select_product( $bkVal->productcode );
	$option = array();
	# 옵션정보
	if( $bkVal->optionarr != '' ){
		if( $bkVal->op_type == 1 ){ // 독립형 옵션
			$tmp_option_subject = explode( '@#', $bkVal->opt1_idx );
			$tmp_option_content = explode( '@#', $bkVal->opt2_idx );
			foreach( $tmp_option_content as $contentKey=>$contentVal ){
				if( $contentVal != '' ){
					$opt2_val = $Basket->select_options( $bkVal->productcode, $contentVal, $bkVal->op_type );
					//exdebug($opt2_val);
					$option[$contentKey] = array(
						'option_code'          =>$opt2_val[0]->option_code,
						'option_price'         =>$opt2_val[0]->option_price,
						'option_quantity'      =>$opt2_val[0]->option_quantity,
						'option_quantity_noti' =>$opt2_val[0]->option_quantity_noti,
						'option_type'          =>$opt2_val[0]->option_type
					);
					$option_price += $opt2_val[0]->option_price;
				} else {
					$option[$contentKey] = array(
						'option_code'          =>'',
						'option_price'         =>0,
						'option_quantity'      =>0,
						'option_quantity_noti' =>0,
						'option_type'          =>1
					);
				}
			}

		} else { // 조합형 옵션
			$select_option = $Basket->select_options( $bkVal->productcode, $bkVal->optionarr, $bkVal->op_type );
			
			$option[] = array(
				'option_code'          =>$select_option[0]->option_code,
				'option_price'         =>$select_option[0]->option_price,
				'option_quantity'      =>$select_option[0]->option_quantity,
				'option_quantity_noti' =>$select_option[0]->option_quantity_noti,
				'option_type'          =>$select_option[0]->option_type
			);

			$option_price += $select_option[0]->option_price;
		}
	}
	$deli_obj[] = array(
		'vender'		=>$bkProduct->vender,
		'brand'		=>$bkProduct->brand,
		'productcode'	=>$bkProduct->productcode,
		'productname'	=>$bkProduct->productname,
		'quantity'		=>$bkVal->quantity,
		'deli'			=>$bkProduct->deli,
		'deli_price'	=>$bkProduct->deli_price,
		'deli_qty'		=>$bkProduct->deli_qty,
		'deli_select'	=>$bkProduct->deli_select,
		'price'			=>$bkProduct->sellprice,
		'delivery_type'=>$bkVal->delivery_type,
		'option'		=> $option
	);

	$brandVenderArr[$bkProduct->brand]	=  $bkProduct->vender;

}

$Delivery->get_product( $deli_obj );
$Delivery->set_deli_item();
$vender_info    = $Delivery->get_vender();
$vender_deli    = $Delivery->get_vender_deli();
$free_deli      = $Delivery->get_free_deli();
$product_deli   = $Delivery->get_product_deli();

$brandArr = ProductToBrand_Sort( $basket );


$productImgPath = $Dir.DataDir."shopimages/product/";
$coupon_cnt = 0;
if( strlen( $_ShopInfo->getMemid() ) > 0 ){
	$coupon_cnt = count( MemberCoupon( 1, 'P' ) );
	$memsql = "SELECT reserve FROM tblmember WHERE id = '".$_ShopInfo->getMemid()."'";
	$memres = pmysql_query( $memsql, get_db_conn() );
	$mem_reserve = pmysql_fetch_object( $memres );
	pmysql_free_result( $memres );
}




# 상품별 재고 체크를 위해 상품 재정렬 같은 옵션의 상품의 수량을 더한 후 비교 하기 위해 배열 셋팅
# 옵션은 조합형만 존재 한다고 하여 조합형에 대한 내용만 작업
$stockArrayCheck = array();
foreach( $brandArr as $brand=>$brandObj ){
	foreach( $brandObj as $product ) {
		if( strlen( $product['opt1_name'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
			if( strlen( $product['opt1_name'] ) > 0 ){
				if( $product['option_type'] == 0 ){ //조합형 옵션
					$tmpOptName = explode( '@#', $product['opt1_name'] );
					$tmpOptVal = explode( chr(30), $product['opt2_name'] );
					$tmpOptCnt	= 0;
					foreach( $tmpOptName as $tmpKey=>$tmpVal ){
						$stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey].$product['store_code']]['productcode'] = $product['productcode'];
						$stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey].$product['store_code']]['productname'] = $product['productname'];
						$stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey].$product['store_code']]['prodcode'] = $product['prodcode'];
						$stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey].$product['store_code']]['colorcode'] = $product['colorcode'];
						$stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey].$product['store_code']]['size'] = $tmpOptVal[$tmpKey];
						$stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey].$product['store_code']]['store_code'] = $product['store_code'];
						$stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey].$product['store_code']]['quantity'] += $product['quantity'];
						# 매장 코드가 있을때만 매장 코드 없이 수량을 더해 놓는다. 매장 코드없는 상품은 같은 옵션의 전체 재고를 비교해야 하기 때문
						if($product['store_code']) $stockArrayCheck[$product['prodcode'].$tmpOptVal[$tmpKey]]['quantity'] += $product['quantity'];
					}
				}
			}
		}
	}
}

$stockSoldoutArray = array();
if(count($stockArrayCheck) > 0){
	foreach($stockArrayCheck as $k => $v){
		# 상품별 재고 체크
		if($v['prodcode'] && $v['colorcode']){
			$shopRealtimeStock = getErpPriceNStock($v['prodcode'], $v['colorcode'], $v['size'], $v['store_code']);
			if($v['quantity'] > $shopRealtimeStock['sumqty']){
				$stockSoldoutArray[] = $v['productcode'].$v['size'].$v['store_code'];
			}
		}
	}
}
?>

<?php  include ($Dir.MainDir.$_data->menu_type.".php") ?>

<SCRIPT LANGUAGE="JavaScript">

$(document).ready( function() {
	var option_cnt = $('.opt-change-list').length;

	// 옵션 z-index 설정
	if( option_cnt > 0 ){
		$('.opt-change-list').each( function( idx, obj ) {
			$(this).css( 'z-index', ( option_cnt * 5 ) - ( idx * 5 ) );
		});
	}

});
//조합형 옵션 클릭
$(document).on( 'change', 'select.CLS_option_value', function( event ) {
	event.preventDefault();	
	var basket_num	=  $('td[name="NM_options"]').index($(this).parents('td[name="NM_options"]')); //장바구니 list 번호
	var code			= $(this).find("option:selected").attr('data-code'); // 선택된 옵션코드
	var qty				= $(this).find("option:selected").attr('data-qty'); // 선택된 옵션의 수량
	var option_idx		= $(this).parents('td[name="NM_options"]').find('.CLS_option_value').index($(this)); //해당  리스트의 옵션 번호

	select_option( basket_num, option_idx, code );
});
// 옵션 정보
function select_option( basket_num, option_idx, code ){

	var basket_selecter = $('td[name="NM_options"]').eq( basket_num );
	var option_selecter = $( basket_selecter ).find('.CLS_option_value');
	var productcode = $( basket_selecter ).attr('data-productcode');
	var option_maxcnt = $( basket_selecter ).find('.CLS_option_value').length;
	var constant_quantity = $( basket_selecter ).find('input[name="pr_quantity"]').val();
	//console.log( constant_quantity );
	if( jQuery.type( $( option_selecter ).eq( option_idx ) ) === "undefinded" ) return;
	
	option_selecter.each( function( i ) {
		if( i != option_idx && i > option_idx ){
			$(this).attr( 'data-option-code', '' );
			$(this).find('option').remove();
			$(this).append('<option value=\'\' data-qty=\'\' data-code=\'\' >'+$(this).attr("title")+'</option>');
		}
	});

	if( code == '' ) return;
	
	$( option_selecter ).eq( option_idx ).attr( 'data-option-code', code );
	
	$.ajax({
		type : "POST",
		url : "ajax_option_select.php",
		data : { productcode : productcode, option_code : code, idx : ( option_idx + 1 ) },
		dataType : "json"
	}).done( function( data ){
		if( !jQuery.isEmptyObject( data ) ){
			var option_html = '';
			// 옵션 select box를 넣어줌
			$.each( data , function( i, obj ){
				var data_code = '';
				
				if( code.length == 0 ){
					data_code = obj.code;
				} else {
					data_code = code + chr(30) + obj.code;
				}
				
				if( option_idx + 1 == option_maxcnt - 1 ){
					var soldout = '';
					var disabled_on = '';
					var price_text = '';
						if( obj.price != '' && obj.price > 0 ){
							price_text = ' ( + ' + comma( obj.price ) + ' 원 )';
						} else if( obj.price != '' && obj.price < 0 ) {
							price_text = ' ( - ' + comma( obj.price ) + ' 원 )';
						}
					if( obj.soldout == "1" && constant_quantity < 999999999 ) {
						soldout = '[품절]';
						disabled_on = ' disable';
					}
					option_html += '<option value=\''+data_code+'\' data-code="' + data_code + '" data-qty="' + obj.qty + '" ' + disabled_on + ' >' + soldout + obj.code + price_text + '</option>';
				} else {
					option_html += '<option value=\''+data_code+'\' data-code="' + data_code + '" data-qty="' + obj.qty + '" >' + obj.code + '</option>';
				}

			});

			$( option_selecter ).eq( option_idx + 1 ).append( option_html );

		}
	});

}
//독립형 옵션 change
$(document).on( 'change', 'select[name="op_alone_opt[]"]', function ( event ) {
	event.preventDefault();
	$(this).attr( 'data-option-code', $(this).find("option:selected").attr('data-code') );


});

function option_type( list_idx ){
	var option_type = []; // 해당 상품의 옵션을 확한다 ( 0 - 조합형, 1 - 독립형, 2 - 옵션없음 ); elemnt 반환
	
	if( $('td[name="NM_options"]').eq( list_idx ).find('.CLS_option_value').length > 0 ){ // 조합형
		option_type = [ 0, $('td[name="NM_options"]').eq( list_idx ).find('.CLS_option_value') ];
	} else if( $('td[name="NM_options"]').eq( list_idx ).find('select[name="op_alone_opt[]"]').length > 0 ){ // 독립형
		option_type = [ 1, $('td[name="NM_options"]').eq( list_idx ).find('select[name="op_alone_opt[]"]') ];
	} else { // 옵션없음
		option_type = [ 2, false ];
	}

	return option_type;
}
//상품의 옵션 상태를 확인한다 ( 0 - 옵션없음, 1 - 옵션 1만 존재, 2 - 옵션 2 존재 )
// 2016-02-03 유동혁
function option_case( list_idx ){

	var option_case = false;
	var this_option = $('td[name="NM_options"]').eq( list_idx ).find('.CLS_option_value');
	if( $(this_option).length > 0 ){ // jQuery.type( this_option ) !== "undefinded" ||
		option_case = $(this_option).length - 1;
	}
	return option_case;
}
//상품의 옵션 quantity를 넘겨준다
// 2016-02-03 유동혁
function quantity_case( list_idx ){

	var quantity = 0;
	var option_arr = option_type( list_idx );
	var err = true;
	
	if( option_arr[0] == 0 ){
		if( option_arr[1].eq( option_arr[1].length - 1 ).attr('data-option-code') != '' ){
			quantity = option_arr[1].eq( option_arr[1].length - 1 ).find("option:selected").attr('data-qty');
		} else {
			quantity = false;
		}
		
	} else if( option_arr[0] == 1 ){
		option_arr[1].each( function(){
			if( $(this).next().val() == 'T' && $(this).attr('data-option-code') == '' ) {
				err = false;
			}
		});

		if( err ){
			quantity = $('input[name="pr_quantity"]').eq( list_idx ).val();
		} else {
			quantity = false;
		}
	} else {
		quantity = $('input[name="pr_quantity"]').eq( list_idx ).val();
	}
	
	return quantity;
}

//수량 올리기
$(document).on( 'click', '.btn-plus', function() {
	var list_idx = $('.btn-plus').index( $(this) );
	var select_quantity = quantity_case( list_idx );
	var element_quantity = $('input[name="bk_quantity"]').eq( list_idx );
	var up_quantity = parseInt( $( element_quantity ).val() );
	var qty = 0;

	if( select_quantity ){
		qty = parseInt( select_quantity );
	} else {
		alert('옵션을 선택해 주시기 바랍니다.');
		return;
	}
	
	if( ( up_quantity + 1 ) > qty ) {
		alert('재고가 부족합니다.');
		$( element_quantity ).val( qty );
		return;
	} else {
		$( element_quantity ).val( up_quantity + 1 );
	}

} );
//수량 내리기
$(document).on( 'click', '.btn-minus', function() {

	var list_idx = $('.btn-minus').index( $(this) );
	var select_quantity = quantity_case( list_idx );
	var element_quantity = $('input[name="bk_quantity"]').eq( list_idx );
	var up_quantity = parseInt( $( element_quantity ).val() );
	var qty = 0;

	if( select_quantity ){
		qty = parseInt( select_quantity );
	} else {
		alert('옵션을 선택해 주시기 바랍니다.');
		return;
	}
	
	if( ( up_quantity - 1 )  < 1 ) {
		alert('상품수량을 1개 이상 선택하셔야 합니다.');
		return;
	} else {
		$( element_quantity ).val( up_quantity - 1 );
	}

} );

//상품수량 직접입력
$(document).on( 'keyup', 'input[name="bk_quantity"]', function( event ) {
	var list_idx = $('input[name="bk_quantity"]').index( $(this) );
	var select_quantity = quantity_case( list_idx );
	var up_quantity = parseInt( $(this).val() );
	var qty = 0;

	if( select_quantity ){
		qty = parseInt( select_quantity );
	} else {
		alert('옵션을 선택해 주시기 바랍니다.');
		$(this).val( 1 );
		return;
	}
	
	if( up_quantity < 1 ) {
		alert('상품수량을 1개 이상 선택하셔야 합니다.');
		$(this).val( 1 );
		return;
	} else if( up_quantity > qty ) {
		alert('재고가 부족합니다.');
		$(this).val( qty );
		return;
	}

});

//숫자키 이외의 것을 막음
$(document).on( 'keydown', 'input[name="bk_quantity"]', function( event ) {
	if( !isNumKey( event ) ) event.preventDefault();
});

//벤더별 전체 체크
$(document).on( 'click', '.allCheck', function ( event ) { 
	var list_idx = $('.allCheck').index( $(this) );
	$(this).prop( 'checked', function( idx, val ) {
		$('.CLS_basketTotalCount').eq( list_idx ).find('input[name="checkBasket"]').each( function() {
			$(this).prop( 'checked', val );
		});
	});
});
//개별 체크시 전체선택 확인
$(document).on( 'click', 'input[name="checkBasket"]', function ( evnet ) {
    var table_element = $(this).parent().parent().parent().parent();
    var table_chk_boxs = $( table_element ).find('input[name="checkBasket"]');
    var chk_all_ture = true;
    $(table_chk_boxs).each( function(){
        if( !$(this).prop('checked') ){
            chk_all_ture = false;
        }
    });

    if( chk_all_ture ) $(table_element).find('.allCheck').prop( 'checked', true );
    else $(table_element).find('.allCheck').prop( 'checked', false );
});

function basket_clear(){

	var basketidxs = '';
	var cnt = 0;
	$("input[name='checkBasket']").each( function( idx, obj ) {
		basketidxs += $(this).val() + '|';
		cnt++;
	});
	if( cnt == 0 ) {
		return;
	} else {
		basketidxs = basketidxs.substr( 0, basketidxs.length - 1 );
	}

	$.ajax({
		method : 'POST',
		url : 'confirm_basket_proc.php',
		data: { basketidxs : basketidxs, mode : 'delete' },
		dataType : 'json'
	}).done( function( data ) {
		if( data ){
			alert( data.msg );
			location.href="../front/basket.php";
		} else {
			alert('장바구니 삭제가 실패되었습니다.');
		}
	});

}

function select_delete(){
	var basketidxs = '';
	basketidxs = basket_select();
	if( basketidxs === false ) return;

	$.ajax({
		method : 'POST',
		url : 'confirm_basket_proc.php',
		data: { basketidxs : basketidxs, mode : 'delete' },
		dataType : 'json'
	}).done( function( data ) {
		if( data ){
			alert( data.msg );
			if (data.code == 'S01' || data.code == 'S02' || data.code == 'S03')
				location.href="../front/basket.php";
		} else {
			alert('장바구니 삭제가 실패되었습니다.');
		}
	});

}

function set_wish(){
	var cnt = 0;
	var err = 0;
	$(".CLS_wish").hide();

	$("input[name='checkBasket']").each( function( idx, obj ) {
		if( $(this).prop( 'checked' ) ) {
			if( $('td[name="NM_options"]').eq( idx ).attr('data-productcode').length > 0 ) {
				$.ajax({
					method : 'GET',
					url : 'ajax_set_product_wish_list.php',
					data: { prodcode : $('td[name="NM_options"]').eq( idx ).attr('data-productcode') }
				}).done( function( data ) {
					if( data == 'FAIL' ){
						err++;
					}
				});
			}
			cnt++;
		}
	});

	setTimeout( 
		function(){
			$(".CLS_wish").show();

			if( cnt == 0 ) {
				alert('하나 이상의 상품을 선택하셔야 합니다.');
				return false;
			}
			if( err > 0 ){
				alert('위시리스트 등록이 실패했습니다..');
				return false;
			} else {
				alert('위시리스트에 등록 되었습니다.');
			}
		}, 
		800
	);


}
//수량변경
$(document).on( 'click', '.CLS_quantity_change', function(){
	//var list_idx = $('.CLS_quantity_change').index( $(this) );
	var list_idx = $('tr[name="product_list"]').index( $(this).parent().parent().parent().parent() );
	var basketidx = $("input[name='checkBasket']").eq( list_idx ).val();
	var quantity = $('input[name="bk_quantity"]').eq( list_idx ).val();
	var is_option = false;

	if( $('tr[name="product_list"]').eq( list_idx ).find('div.opt-change-list').length ) is_option = true;

	$.ajax({
		method : 'POST',
		url : 'confirm_basket_proc.php',
		data : {
			mode : 'quantity_update', basketidx : basketidx, quantity : quantity, is_option : is_option
		},
		dataType : 'json'
	}).done( function( data ) {
		alert( data.msg );
		if (data.code == 'S01' || data.code == 'S02' || data.code == 'S03')
			location.href = 'basket.php';
	});
	
});
//옵션변경
$(document).on( 'click', '.CLS_option_change', function(){
	//var list_idx = $('.CLS_option_change').index( $(this) );
	var list_idx = $('tr[name="product_list"]').index( $(this).parent().parent().parent().parent().prev() );
	var list_element =  $('tr[name="product_list"]').eq( list_idx );
	var list_opt_element =  $('tr.opt-change').eq( list_idx );
	var basketidx = $("input[name='checkBasket']").eq( list_idx ).val();
	var quantity = $('input[name="bk_quantity"]').eq( list_idx ).val();
	var tmpCodeArr = [];
	var code = '';
	var tmpTextCodeArr = [];
	var textCode = '';
	var err = false;
	var option_type = 2;
	
	if( list_opt_element.find('div.opt-change-list').length <= 0 ) {
		return;
	} else if( list_opt_element.find('.CLS_option_value').length > 0 ) {
		code = list_opt_element.find('.CLS_option_value').eq( list_opt_element.find('.CLS_option_value ').length - 1 ).attr('data-option-code');
		err = true;
		option_type = 0;
	} else if ( list_opt_element.find('select[name="op_alone_opt[]"]').length > 0 ) {
		list_opt_element.find('select[name="op_alone_opt[]"]').each( function( i, obj ) {
			if( $(this).next().val() == 'T' &&  $(this).attr('data-option-code') == '' ){
				alert('필수옵션을 입력하셔야 합니다.');
				$(this).focus();
				err = false;
			} else {
				tmpCodeArr.push( $(this).attr('data-option-code') );
				err = true;
				option_type = 1;
			}
		});

		if( err ) {
			code = tmpCodeArr.join( '@#' );
		}
	} 
	
	if ( list_opt_element.find('input[name="add_option_val"]').length > 0 ) {
		list_opt_element.find('input[name="add_option_val"]').each( function( i, obj ) {
			if( $(this).next() == 'T' &&  $(this).val() == '' ){
				alert('필수옵션을 입력하셔야 합니다.');
				$(this).focus();
				err = false;
			} else {
				tmpTextCodeArr.push( $(this).val() );
				err = true;
			}
		});

		if( err ) {
			textCode = tmpTextCodeArr.join( '@#' );
		}
	}
	
	if( err ){
		$.ajax({
			method : 'POST',
			url : 'confirm_basket_proc.php',
			data : {
				mode : 'modify', basketidx : basketidx, quantity : quantity, 
				option_code : code, text_content : textCode, option_type : option_type
			},
			dataType : 'json'
		}).done( function( data ) {
			//console.log( data );
			alert( data.msg );
			if (data.code == 'S01' || data.code == 'S02' || data.code == 'S03')
				location.href = 'basket.php';
		});
	}


});

$(document).on( 'click', '.CLS_option_replace', function( event ){
	var list_idx = $('tr[name="product_list"]').index( $(this).parent().parent().parent().parent().prev() );
	var basketidx = $("input[name='checkBasket']").eq( list_idx ).val();
	var productcode = $('td[name="NM_options"]').eq( list_idx ).attr('data-productcode');

	if( confirm("옵션변경을 위하여 상품상세로 이동합니다.\n( 장바구니에 있는 상품은 삭제됩니다. )\n이동 하시겠습니까?") ){
		$.ajax({
			method : 'POST',
			url : 'confirm_basket_proc.php',
			data : {
				mode : 'delete', basketidxs : basketidx
			},
			dataType : 'json'
		}).done( function( data ) {
			if( data.code == 'S03'){
				location.href = '/front/productdetail.php?productcode=' + productcode;
			}
		});
	}
});

// 바로구매하기
function one_order(staffchk, basketidx){
	var basketidxs = basketidx;

	if( basketidxs === false ) return;
	
	$("#basketidxs").val( basketidxs );
	<?php if( strlen( $_ShopInfo->getMemid() ) == 0 ){ ?>
	    $('#orderfrm').attr( 'action', '/front/login.php?chUrl=/front/order.php?basketidxs=' + basketidxs );
	<?php } ?>
	$("#orderfrm").find("input[name='staff_order']").val(staffchk);
	$("#orderfrm").submit();
}

function select_order(staffchk){
	// 당일 수령 상품 수량 체크
	var delivery_Type_check = 0;
	$("input[name='checkBasket']:checked").each(function(){
		if($(this).data('delivery_type') == '2'){
			delivery_Type_check++;
		}
	});

	if(delivery_Type_check > 1){
		alert("당일수령 상품은 한 주문서에 하나만 주문이 가능합니다.");
	}else{
		var basketidxs = '';
		basketidxs = basket_select();
		if( basketidxs === false ) return;
		
		$("#basketidxs").val( basketidxs );
		<?php if( strlen( $_ShopInfo->getMemid() ) == 0 ){ ?>
			$('#orderfrm').attr( 'action', '/front/login.php?chUrl=/front/order.php?basketidxs=' + basketidxs );
		<?php } ?>
		$("#orderfrm").find("input[name='staff_order']").val(staffchk);
		$("#orderfrm").submit();
	}
}

function order(staffchk){
	var basketidxs = '';

	$("input[name='checkBasket']").each( function( idx, obj ) {
		$(this).prop( 'checked', true );
	});


	// 당일 수령 상품 수량 체크
	var delivery_Type_check = 0;
	$("input[name='checkBasket']:checked").each(function(){
		if($(this).data('delivery_type') == '2'){
			delivery_Type_check++;
		}
	});
	if(delivery_Type_check > 1){
		alert("당일수령 상품은 한 주문서에 하나만 주문이 가능합니다.");
	}else{
		basketidxs = basket_select();
		
		$("#basketidxs").val( basketidxs );
		<?php if( strlen( $_ShopInfo->getMemid() ) == 0 ){ ?>
			$('#orderfrm').attr( 'action', '/front/login.php?chUrl=/front/order.php?basketidxs=' + basketidxs );
		<?php } ?>
		$("#orderfrm").find("input[name='staff_order']").val(staffchk);
		$("#orderfrm").submit();
	}
}

function basket_select(){
	var basketidxs = '';
	var cnt = 0;
	$("input[name='checkBasket']").each( function( idx, obj ) {
		if( $(this).prop( 'checked' ) ) {
			basketidxs += $(this).val() + '|';
			cnt++;
		}
	});
	if( cnt == 0 ) {
		alert('하나 이상의 상품을 선택하셔야 합니다.');
		return false;
	} else {
		basketidxs = basketidxs.substr( 0, basketidxs.length - 1 );
	}

	return basketidxs;
}

// php chr() 대응
function chr(code)
{
    return String.fromCharCode(code);
}

//좋아요
function product_like(productCode, likeType){
	var memId = "<?=$_ShopInfo->getMemid()?>";

	if(memId != ""){		
		$.ajax({
			type: "POST",
			url: "ajax_hott_like_ok.php",
			data: "hott_code="+productCode+"&like_type="+likeType+"&section=product",
			dataType:"JSON",
	        success: function(data) {
	            alert(data[0]['msg']);
	            //alert(data[0]['cnt_all']);
                //alert(data[0]['div2']);
                $(".like_"+productCode).each(function(){
                    $(this).find('p').eq(1).remove();
                });   
                $(".like_"+productCode).find('div.btn_wrap').append(data[0]['div2']);
	        }, 
	        error: function(result) {
	            //alert(result.status + " : " + result.description);
	            alert("오류 발생!! 조금 있다가 다시 해주시기 바랍니다.");
	        }
		});
	}else{
		//로그인 상태가 아닐때 로그인 페이지로 이동
		var url = "../front/login.php?chUrl=/";
		$(location).attr('href',url);
	}	
}

</SCRIPT>



<?php
 include ($Dir.TempletDir."basket/basket{$_data->design_basket}_ykm.php");

$sql = "update tblbasket set ord_state=false where tempkey = '".$_ShopInfo->getTempkey()."' ";
pmysql_query($sql,get_db_conn());


?>
<form name='orderfrm' id='orderfrm' method='POST' action='<?=$Dir.FrontDir?>order_ykm.php' >
<input type='hidden' name='basketidxs' id='basketidxs' value='' >
<input type='hidden' name='staff_order' id='staff_order' value='' >
</form>
<?php
/*
foreach( $brandArr as $brandVal ){
    foreach( $brandVal as $productVal  ){
        echo '상품코드 : '.$productVal['productcode']."<br>";
        echo '상품명   : '.$productVal['productname']."<br>";
        echo '상품수량 : '.$productVal['quantity']."<br>";
        echo '상품가격 : '.( $productVal['price'] + $productVal['option_price'] ) * $productVal['quantity']."<br><br>";
    }
}


<!-- WIDERPLANET  SCRIPT START 2016.3.23 -->
<div id="wp_tg_cts" style="display:none;"></div>
<?php
$wptg_arr   = array();
$wptg_items = '';
foreach( $Basket->basket as $bkKey=>$bkVal ){
    $bkProduct = $Basket->select_product( $bkVal->productcode );
    $wptg_arr[] = '{i:"'.$bkProduct->productcode.'", t:"'.$bkProduct->productname.'" }';
}
$wptg_items = implode( ',', $wptg_arr );

?>
<script type="text/javascript">
var wptg_tagscript_vars = wptg_tagscript_vars || [];
wptg_tagscript_vars.push(
(function() {
	return {
		wp_hcuid:"",  	
		ti:"24558",
		ty:"Cart",
		device:"web"
		,items:[
			<?=$wptg_items?>
		]
	};
}));
</script>
<script type="text/javascript" async src="//cdn-aitg.widerplanet.com/js/wp_astg_4.0.js"></script>
<!-- // WIDERPLANET  SCRIPT END 2016.3.23 -->
*/
?>
<?
/*exdebug($Basket->basket);
foreach( $Basket->basket as $bkKey=>$bkVal ){
	echo $bkKey."=>".$bkVal;
}*/

?>
<?php include ($Dir."lib/bottom.php") ?>


</BODY>
</HTML>
