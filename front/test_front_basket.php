<?php

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


function procductSellState($productcode,$opt1=NULL,$opt2=NULL){
	
	// 카테고리 숨김 확인
	$cate_code = substr($productcode,0,12);
	$group_sql = "
		SELECT group_code 
		FROM tblproductcode 
		WHERE code_a||code_b||code_c||code_d = '{$cate_code}' 
	" ;
	$group_res = pmysql_query($group_sql,get_db_conn());
	$group_row = pmysql_fetch_object($group_res);
	pmysql_free_result($group_res);
	if($group_row->group_code == "NO"){
		return  $returndata = array("result"=>"false","message"=>"카테고리 숨김");
	}
	// 제품 수량 및 옵션 수량 체크	
	$quantity_state_sql = "
		SELECT quantity,option_quantity,option1,option2 
		FROM tblproduct 
		WHERE productcode = '{$productcode}' 
	";
	$quantity_state_res = pmysql_query($quantity_state_sql,get_db_conn());
	$quantity_state_row = pmysql_fetch_object($quantity_state_res);
	pmysql_free_result($quantity_state_res);
	//수량
	if($quantity_state_row->quantity == 0){
		return $returndata = array("result"=>"false","message"=>"상품수량 없음");;
	}
	
	//옵션 수량
	$option_quantity = explode(",",$quantity_state_row->option_quantity);
	$option_chk = 0;
	//옵션 1 타입 
	$option1 = explode(",",$quantity_state_row->option1);
	if($quantity_state_row->option1){
		$option_chk = 1;
		$option1 = explode(",",$quantity_state_row->option1);
	}
	//옵션 2 타입
	if($quantity_state_row->option2){
		$option_chk = 2;
		$option2 = explode(",",$quantity_state_row->option2);
	}

	if($opt1 == NULL){	//장바구니에 옵션1이 없으나 DB에 옵션이 있을경우
		if($option_chk > 0){
			$returndata = array("result"=>"false","message"=>"상품이 존재하지 않습니다.");
		}
	}
	
	if($opt2 == NULL){	//장바구니에 옵션2가 없으나 DB에 옵션이 있을경우
		if($option_chk > 1){
			$returndata = array("result"=>"false","message"=>"상품이 존재하지 않습니다.");
		}
	}
	
	switch($option_chk){
		case 0 : 
			$returndata = array("result"=>"true","message"=>"상품이 존재합니다.");
			break;
		case 1 :
			if($option1[$opt1] == ""){	// 옵션 내용이 없는 경우
				$returndata = array("result"=>"false","message"=>"상품이 존재하지 않습니다.");
			}else if($option_quantity[$opt1] == ""){	//옵션1 제고값이 있는경우
				$returndata = array("result"=>"false","message"=>"옵션1의 제고가 없습니다.");
			}else{
				$returndata = array("result"=>"true","message"=>"상품이 존재합니다.".$option1[$opt1]."명".$option_quantity[$opt1]."개");
			}
			break;
		case 2 :
			$opt2_num = ((((int)$opt2 - 1) * 10) + 1) + (int)$opt1; //ex) 1=[1~10],2=[11~20],3=[21~30],...
			if($option2[$opt2] == ""){	//옵션2의 내용이 없는 경우
				$returndata = array("result"=>"false","message"=>"상품이 존재하지 않습니다.");;
			}else if($option_quantity[$opt2_num] == "") {		//옵션2 제고가 없는 경우
				$returndata = array("result"=>"false","message"=>"옵션2의 제고가 없습니다.");;
			}else {
				$returndata = array("result"=>"true","message"=>"상품이 존재합니다.".$option_quantity[$opt2_num]."개 ".$option2[$opt2]."명");
			}		
			break;
		default :
			$returndata = array("result"=>"false","message"=>"값이 존재하지 않습니다.");
			break;
	}
	
	return $returndata;
	
}

$aaa = procductSellState('003005001000000002',2);
exdebug($aaa);

?>