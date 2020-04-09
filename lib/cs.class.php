<?php
/**
 * CS
 */
class CS extends ORDER {
	public function __construct() {
		parent::__construct();
	}

	public function escrow($order_num, $mod_type,$mod_desc='') {
		//에스크로 결제여부체크
		$payment_info = $this->getPaymentRow($order_num);
		if($payment_info['escrow_yn'] !='Y') return true; //에스크로 결제 아닌경우 패스

		$escrow_info = array(
			"req_tx" => "mod_escrow",
			"mod_type" => $mod_type,
			"tno" => $payment_info['tno'],
			'mod_desc'=>$mod_desc
		);

		if($mod_type == 'STE1') { //배송시작처리
			//배송정보
			$delivery_info = $this->getProductRow("order_num='{$order_num}'");
			//택배사정보
			$deli_company = $this->getDeliveryCompanyPair();
			$escrow_info['deli_numb'] = $delivery_info['delivery_no']; //송장번호
			$escrow_info['deli_corp'] = $deli_company[$delivery_info['delivery_company']]['company_name']; //택배사명
		}
		// pre($escrow_info);
		$http_status = 0;
		// $url = HOST.'/third_party/pg/NHNKCP/hub_escrow.php';
		$url = 'https://dev-jayjun.ajashop.co.kr/third_party/pg/NHNKCP/hub_escrow.php';

		$rs = $this->curl($url, $escrow_info, $http_status);
		$rs_data = json_decode($rs, true);
		// pre($rs_data);
	
		if($rs_data['success']) {
			return true;
		}
		else {
			$this->error($rs_data['data']['res_msg']);
			return false;
		}

	}
}
?>