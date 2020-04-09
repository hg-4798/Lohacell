<?php
class SMS extends COMMON {

    //기본값 설정
    var $etcmsg =""; //기타정보
    var $resdata =""; //처리결과 값
    var $status ="W"; //발신상태값(W:발신대기, Y: 정상, N:실패)
    var $msg_type ="S"; //메세지 구분 (S: SMS, L: LMS)
    var $send_key ="D79375b4593f6e8272"; //사이트키
    var $from_tel = '0808812001'; //발송번호, 사이트의 발송번호와 맞춰줘야함
    var $booking = 'N'; //예약중인지 발송중인지 여부 (Y:예약 , N:발송)
    var $_conts = array(
        //승인상태
        'msg_type'=>array(
            'S'=>1,
            'L'=>2
        )
    );

    //sms코드 정의
    var $_sms_code = array(
        'ORDER_001' => '주문완료/무통장입금계좌안내',
        'ORDER_002' => '결제완료/일반배송',
        'ORDER_003' => '배송중/일반배송',
        'ORDER_004' => '배송중/발송완료(부분배송)',
        'ORDER_005' => '배송완료/구매확정안내',
        'CANCEL_001' => '취소완료/무통장입금기한만료',
        'CANCEL_002' => '취소완료/무통장입금기한만료예정안내',
        'CANCEL_003' => '취소완료/취소완료(고객취소)',
        'EXCHANGE_001' => '교환접수/지정택배사',
        'REFUND_001' => '반품접수/지정택배사',
        'REFUND_002' => '반품완료/반품완료',
        'REFUND_003' => '환불완료/환불완료',
        'MEMBER_001' => '1:1문의/답변완료',
        'MEMBER_002' => '회원관리/정상가입안내',
        'COUPON_001' => '회원관리/회원등급',
        'COUPON_002' => '회원관리/생일기념',
        'COUPON_003' => '구매유도/첫구매유도',
        'COUPON_004' => '구매유도/휴면방어',
        'COUPON_005' => '고객행동패턴/로그인',
        'COUPON_006' => '고객행동패턴/장바구니',
        'COUPON_007' => '고객행동패턴/결제이탈'
    );

    var $_url = array(
        'order' => SHOPURL.'/m/mypage_orderlist.php',
        'qa' => SHOPURL.'/m/mypage_personal.php',
        'grade' => SHOPURL.'/m/benefit.php',
        'join' => SHOPURL.'/m/benefit.php',
        'coupon' => SHOPURL.'/m/mypage_coupon.php'
    );

    var $_jayjunurl = SHOPURL;


    /**
     * 발송정보처리
     *
     * @param [type] $smscode
     * @param [type] $to
     * @param array $argu
     * @return void
     *
     *
     *
     *  $sms_code, $data_key //주문 - ordercode, qna - qna pk, 회원관리 - member_id, 등급 - member_id, 쿠폰 - type_publish_kind(쿠폰종류)
     */
    public function send_sms($sms_code, $data_key, $argu = array())
    {
        //자체 sms발송 하는 문자
        $send_flag = false;
        switch($sms_code) {
            case 'COUPON_002' : //생일쿠폰발송 문자
                $this->sendBatchCouponSms($sms_code, $data_key);
                $send_flag = true;
                break;
            case 'ORDER_003': //발송완료(배송중처리시)
            case 'ORDER_005': //구매확정요청(배송완료처리즉시)
                $product_valid = implode(',',$argu);
                $this->sendDeliSms($sms_code,$data_key,$product_valid);
                $send_flag = true;
                break;
            default:
                break;
        }

        //자체 sms발송 한 문자일경우 아래의 sms발송 프로세스 생략
        if($send_flag) return;

        //호출 SMS코드로 저장된 SMS 정보 가져오기
        $smsinfo = $this->getSmsInfoRow($sms_code);
        $contents = $smsinfo['contents'];
        if ($smsinfo['admin_comment']) $this->etcmsg = $smsinfo['admin_comment'];
        $section = $smsinfo['section'];

        //print_r($section);

        //즉시 발급인지 아닌지
        $send_type = $smsinfo['send_type'] ?: 'D';

        $data = array();
        switch ($section) {
            case 'order' : //주문
                $data = $this->getOrderInfo($data_key);
                break;
            case 'refund' : //환불
                //$data = $this->getRefundInfo($data_key);
                break;
            case 'qa' :  //1:1문의
                $data = $this->getQaInfo($data_key);
                break;
            case 'coupon' : //쿠폰
                //$data = $this->getCouponInfo($data_key);
                break;
            case 'grade' : //등급변경
                $data = $this->getMemGradeInfo($data_key);
                break;
            case 'join' : //가입
                $data = $this->getMemInfo($data_key);
                break;
        }

        //값치환하기
        $contents = $this->setContents($data, $contents);

        $to_tel = str_replace('-', '', $data['to_tel']);

//        @TODO 문자발송확인을 위해 임시로 인덱스값 가져오는걸로 만들어 놓음
//        @TODO 다이렉트일경우와 아닌경우를 나누면 될듯
        //문자 로그테이블에 삽입
        $record = array(
            'title' => $smsinfo['title'],
            'msg' => $contents,
            'reg_date' => NOW,
            'from_tel' => $this->from_tel,
            'to_tel' => $to_tel,
            'etc_msg' => $this->etcmsg,
            'res_msg' => $this->resdata,
            'status' => $this->status,
            'msg_type' => $this->msg_type,
            'send_type' => $send_type,
        );

        $idx = $this->_log($record);
        //$idx = $this->_log($smsinfo['title'], $contents, $this->from_tel, $to_tel, $this->etcmsg, $this->resdata, $this->status, $this->msg_type);
        $this->curl_request_async(SHOPURL . "/proc/sms_send.proc.php", array('idx' => $idx));
    }

    /**
     * 설정된 문자 정보 가져오기
     *
     * @param [type] $sms_code
     * @return void
     */
    public function getSmsInfoRow($sms_code){
        $sql = "SELECT title, contents, send_type, admin_comment, section FROM tblsms_config WHERE sms_code = '{$sms_code}' ";
        $row = $this->adodb->getRow($sql);
        if($row['send_type']=='B'){
            $this->booking = 'Y'; //예약발송 문자
        }else{
            $this->booking = 'N'; //즉시발송 문자
        }
        return $row;
    }

    /**
     * 전송할 문자 데이터(문자로그) 가져오기
     *
     * @param [type] $idx
     * @return void
     */
    public function getSmsMsgRow($idx){
        $sql = "SELECT * FROM tblsms_log WHERE idx = {$idx} ";
        $row = $this->adodb->getRow($sql);

        return $row;
    }


    /**
     * 내용에 들어갈 값 치환 및 길이에 따라 문자종류 변경
     *
     * @param [type] $contents
     * @return $contents
     */
    public function setContents($data, $contents){
        $replaced_contents = $contents;
        if (is_array($data)) {
            foreach ($data as $r => $d) {
                $replace_text = '{' . $r . '}';
                $replaced_contents = str_replace($replace_text, $d, $replaced_contents);
            }
        }

        //contents 길이에 따라 문자종류 나누기
        if (strlen($replaced_contents) > 80) {
            $this->msg_type = 'L'; //메세지 구분 (S: SMS, L: LMS)
        }

        return $replaced_contents;
    }

    /**
     * 문자 상태 변경
     *
     * @param [type] $idx
     * @return void
     */ //W: 발송대기, I: 발송중, S:성공, F: 실패
    public function setSmsStatus($idx, $status){
        $success = true;
        $sql = "UPDATE tblsms_log SET res_msg = '발송중', status = '{$status}', send_date = now() WHERE idx = ".$idx;
        $rs = $this->adodb->Execute($sql);

        if(!$rs) $success=false;

        return $success;
    }

    /**
     * 실제 발송 함수 백그라운드 실행 처리(내부에서 send함수 호출함)
     *
     * @param [type] $uri
     * @param [type] $params
     * @return void
     */
    function curl_request_async($uri, $params, $type='POST') {
    	if(IS_DEV) return;
        //예약 문자일경우 발송 생략
        if($this->booking=='Y'){ return false;}

        //if ($_SERVER['HTTP_HOST'] == "www.iknowione.co.kr") {
        $command = "curl ";
        foreach ($params as $key => &$val)
            $command .= "-F '$key=$val' ";
        $command .= "$uri -s > /dev/null 2>&1 &";
        //$command .= "$uri >> ".DOC_ROOT."/proc/log/".date("Ymd").".txt";
        passthru($command);
        //}
    }


    /**
     * 예약 문자 발송처리
     *
     * @return void
     */
    public function send_book_sms()
    {
        $this->booking='N';
        $book_sql = "SELECT *
                FROM tblsms_log
                WHERE send_type = 'B'
                AND status = 'W'
                AND book_date <= now()
				ORDER BY idx asc
                ";
        $book_rs = $this->adodb->Execute($book_sql);
        while($book_row = $book_rs->FetchRow()) {
            $this->curl_request_async(SHOPURL."/proc/sms_send.proc.php", array('idx' => $book_row['idx']));
        }
    }

    /**
     * 실제발송처리
     *
     * @return void
     */
    public function send($idx='')
    {
        //입력된 문자로그 테이블에서 조회하여 발송
        $msg_row = $this->getSmsMsgRow($idx);

        //발송중으로 상태 변경
        $this->setSmsStatus($idx, "I");

        $this->from_tel = $msg_row['from_tel']; ///발신자번호
        $this->to_tel = $msg_row['to_tel']; //받는사람번호 여러개인경우 , 로 구분 최대 100개
        $this->title = $msg_row['title']; //LMS,MMS 일때 문자 제목
        $this->contents = $msg_row['msg']; //문자내용
        $msg_type = $msg_row['msg_type'];
        $this->msg_type = $this->_conts['msg_type'][$msg_type];  //문자 종류 1:단문 2:장문
        $send_type = $msg_row['send_type'];  //예약발송여부 (예약발송 B)
        $book_date = '';  //예약발송 할 날짜

        include_once(DOC_ROOT . "/third_party/SMS_Mothead/MotHead.lib.php");

        $MH_rd = array();
        $MH_rd['U_CODE'] = $this->send_key; ///발급받은 키 사이트의 기업연동->연동하기를 통해 발급받으세요.
        $MH_rd['U_FROM_NUM'] = $this->from_tel; ///발신자번호
        $MH_rd['U_TO_NUM'] = $this->to_tel; //받는사람번호 여러개인경우 , 로 구분 최대 100개
        $MH_rd['U_SUBJECT'] = $this->title; //LMS,MMS 일때 문자 제목
        $MH_rd['U_MSG'] = $this->contents; //문자내용

        /*        if($send_type=="B"){
                    $book_date = $msg_row['book_date'];
                }*/
        $MH_rd['U_SEND_DATE'] = $book_date; //발송예약일 현재시각 기준 30분 이후로 설정 가능 (Beta) //베타라 서비스 지원이 안된다고 함. 20181102 bshan
        $MH_rd['U_TYPE'] = $this->msg_type; //문자 종류 1:단문 2:장문
        $MH_ed['U_VAL'] = ""; //사용자 임의 변수 U_VAL 로 받을수 있음

        $MotHead = new MotHead_Send();

        $S_SR = $MotHead->Send($MH_rd);

        $S_status = $S_SR['status'];  ///서버통신 성공여부 true , false
        $S_code = $S_SR['code']; //해당 요청건의 고유코드 (Report 받을시 사용)
        $S_result = $S_SR['result']; ///0:실패 1:성공
        $S_msg = $S_SR['msg']; ///에러메세지
        $S_count = $S_SR['count']; //전송 요청 건수 -중복 자동 제거됨
        $S_u_val = $S_SR['U_VAL']; ///사용자 임의변수

        if ($S_status && $S_result == 1) {
            $sql = "UPDATE tblsms_log SET res_msg = '정상발송', status = 'S', send_date = now() WHERE idx = " . $idx;
        } else {
            $sql = "UPDATE tblsms_log SET res_msg = '발송실패 " . $S_msg . "', status = 'F' WHERE idx = " . $idx;
        }

        $this->adodb->Execute($sql);
    }



    /**
     * Undocumented function
     *
     * @param [type] $record
     * @return void
     */
    public function assistor($record)
    {
        if ($record['send_type'] == 'B') { //예약발송일 경우
            $book_date = date('Y-m-d 09:00:00'); //오늘의 9시로 예약 날짜 설정
            if ($book_date < NOW) { //예약 하려는 날의 9시가 지났으면 다음날 9시로 설정
                $tomorrow = strtotime('+1 day');
                $book_date = date('Y-m-d 09:00:00', $tomorrow);
            }
            $record['book_date'] = $book_date;
        }
        return $record;
    }

    /**
     * 로그저장(로그테이블저장)
     *
     * @return idx SMS 로그 pk
     */
    private function _log($record){
        $record = $this->assistor($record);
        $sql = sqlInsert($record, 'tblsms_log');
        $rs = $this->adodb->Execute($sql);
        $idx = $this->adodb->insert_id();
        /*        $sql = "INSERT INTO tblsms_log(title, msg, reg_date, from_tel, to_tel, etc_msg, res_msg, status, msg_type, book_date )";
                $sql .= " VALUES('" . $title . "','" . $msg . "',now(),'" . $from_tel . "','" . $to_tel . "','" . $etcmsg . "','" . $resdata . "','". $status. "','". $msg_type."', )RETURNING idx ";
                $row = pmysql_fetch_array(pmysql_query($sql,get_db_conn()));*/

        return $idx;
    }


    /**
     * 발송문자용 주문 정보 가져오기(주문취소,반품, 배송중, 배송완료 별도)
     *
     * @param [type] $order_code
     * @return void
     */
    private function getOrderInfo($order_num){
        global $_CONFIG;
        $sql = "
                SELECT *
                FROM tblorder_basic
                WHERE order_num = '{$order_num}'
                ";
        $row = $this->adodb->getRow($sql);

        $order_product = $row['order_title'];

        $payment_sql = "
                SELECT *
                FROM tblorder_payment
                WHERE order_num = '{$order_num}'
                ";

        $payment_row = $this->adodb->getRow($payment_sql);
        $res_info = $payment_row['res_info'];
        $res_info = unserialize($res_info);

        $row['pg_paymethod_txt'] = $_CONFIG['pay_method'][$row['pg_paymethod']]; //결제수단


        //print_r($sql);

        $return = array(
            'NM' => $row['buyer_name'],//주문자명
            'ORDERID'=> $row['order_num'],//주문번호
            'ORDERPRODUCT' => $order_product, //주문내용
            'PRICE' => $row['pay_pg'],//주문금액
            'PAYMETHOD' => $row['pg_paymethod_txt'],//결제방법
            'LIMITDATE' => date('Y-m-d H:i:s',strtotime($res_info['va_date'])),//무통장입금기한
            'BANK' =>$res_info['bankname'],//은행//무통장 계좌 은행
            'ACCOUNT' => $res_info['account'],//무통장 계좌번호
            'URL' => $this->_url['order'],//마이페이지 주문조회
            //'SENDDATE' => ,//무통장 입금기한(발송일)
            'to_tel' => $row['buyer_mobile'],//주문자 휴대전화
        );

        return $return;
    }


    /**
     * 문자 발송 (배송중, 배송완료 처리즉시)
     *
     * @param [type] $sms_code 문자코드
     * @param [type] $order_num 주문번호
     * @param string $product_idx 주문상품pk, 콤마로 다중연결
     * @return void
     */
    public function sendDeliSms($sms_code, $order_num, $product_idx=''){
        $sql = "
                SELECT *
                FROM tblorder_basic
                WHERE order_num = '{$order_num}'
                ";
        $row = $this->adodb->getRow($sql);

        //호출 SMS코드로 저장된 SMS 정보 가져오기
        $smsinfo = $this->getSmsInfoRow($sms_code);
        $base_contents = $smsinfo['contents'];
        if ($smsinfo['admin_comment']) $this->etcmsg = $smsinfo['admin_comment'];


        //예약발송 메세지 여부
        $send_type = $smsinfo['send_type'] ?: 'D';

        //배송사, 송장번호 조회
        $deli_array = array();

        $deli_sql = "
            SELECT *
            FROM tblorder_product
            WHERE idx in ({$product_idx})
            ";

        $deli_rs = $this->adodb->Execute($deli_sql);
        while($deli_row = $deli_rs->FetchRow()) {
            if (!$deli_row["delivery_company"] || !$deli_row["delivery_no"]) continue; //송장번호 및 택배사 정보가 없는경우 필터링

            $delivery_key = $deli_row["delivery_company"] . '_' . $deli_row["delivery_no"];
            $productcode = $deli_row['productcode']; //상품코드

            //송장번호가 다를경우
            if (!isset($deli_array[$delivery_key])) {
                //택배사
                if ($deli_row["delivery_company"]) {
                    $deli_com = $this->adodb->getOne("SELECT company_name FROM tbldelicompany WHERE code = '{$deli_row["delivery_company"]}'");
                }
                $deli_productname = $this->adodb->getOne("SELECT productname FROM tblproduct WHERE productcode = '{$productcode}'");
                $deli_array[$delivery_key] = array(
                    'ORDERPRODUCT' => $deli_productname,
                    'COUNT' => 0,
                    'DELICOM' => $deli_com,
                    'DELICODE' => $deli_row["delivery_no"],
                    'NM' => $row['buyer_name'],//주문자명
                    'ORDERID'=> $row['order_num'],//주문번호
                    'URL' => $this->_url['order'],//마이페이지 주문조회
                );
            } else {
                $deli_array[$delivery_key]['COUNT']++;
            }
        }

        $to_tel = str_replace('-', '', $row['buyer_mobile']);

        //송장번호기준으로 데이터 분류 후 문자 발송 처리
        foreach ($deli_array as $k){
            $data = $k;
            $last_productname = $k['ORDERPRODUCT'];
            if($k['COUNT'] > 0){
                $last_productname .= " 외 {$k['COUNT']}건";
            }
            $data["ORDERPRODUCT"] = $last_productname;

            //print_r($data);
            //값치환하기
            $contents = $this->setContents($data, $base_contents);


            //문자 로그테이블에 삽입
            $record = array(
                'title' => $smsinfo['title'],
                'msg' => $contents,
                'reg_date' => NOW,
                'from_tel' => $this->from_tel,
                'to_tel' => $to_tel,
                'etc_msg' => $this->etcmsg,
                'res_msg' => $this->resdata,
                'status' => $this->status,
                'msg_type' => $this->msg_type,
                'send_type' => $send_type,
            );

            $idx = $this->_log($record);

            $this->curl_request_async(SHOPURL . "/proc/sms_send.proc.php", array('idx' => $idx));
        }
    }

    /**
     * 발송문자용 주문취소,반품 정보 가져오기
     *
     * @param [type] $order_code
     * @return void
     */
    private function getRefundInfo($order_code, $product_code=''){
//        @TODO 주문진행되면 작업
        $sql = "
                SELECT oi.receiver_name, oi.receiver_tel2, oi.ordercode, op.productname, oi.price, oi.paymethod  
                FROM tblorderinfo  oi
                JOIN tblorderproduct op on oi.ordercode = op.ordercode
                WHERE oi.ordercode = '{$order_code}' ";
        $rs = $this->adodb->Execute($sql);
        $list = array();

        $cnt = 0;
        while($row = $rs->FetchRow()) {
            if($cnt==0) {
                $list['receiver_name'] = $row['recevier_name'];
                $list['receiver_tel2'] = $row['receiver_tel2'];
                $list['ordercode'] = $row['ordercode'];
                $list['productname'] = $row['productname'];
                $list['price'] = $row['price'];
                $list['paymethod'] = $row['paymethod'];
            }else {
                $cnt++;
            }
        }
        if($cnt > 0){
            $list['productname'] = $list['productname']."외 ".$cnt."개";
        }

        //@TODO 부분발송일경우 ?? 상태값으로 조회해서? 선택된 productcode들을 조회해서??????
        if(strlen($product_code)>0){
            $product_code_arr = explode(',',$product_code);
            $product_code = implode("','",$product_code_arr);
            /*$sql = "
                SELECT op.productname
                FROM tblorderinfo  oi
                JOIN tblorderproduct op on oi.ordercode = op.ordercode
                WHERE oi.ordercode = '{$order_code}'
                AND op.productcode in '{$product_code}' ";*/
            $sql = "
                    SELECT productname FROM tblproduct WHERE productcode IN ('{$product_code}')
            ";
            $rs = $this->adodb->Execute($sql);
            $cnt = 0;
            while($row = $rs->FetchRow()) {
                if($cnt==0) {
                    $list['productname'] = $row['productname'];
                }else {
                    $cnt++;
                }
            }
            if($cnt > 0){
                $list['productname'] = $list['productname']."외 ".$cnt."개";
            }
        }

        /*$return = array(
            'NM' => $list['receiver_name'], //이름
            'ORDERID'=> $list['ordercode'], //주문번호
            'ORDERPRODUCT' => $list['productname'], //주문상품 또는 부분발송상품
            'PRICE' => $list['price'],//(입금,결제) 금액
            'PAYMETHOD' => $list['paymethod'],//결제방법
            //@TODO 입금기한 구하기
            //'LIMITDATE' => //무통장입금기한
            //'BANK' =>//무통장 계좌 은행
            //'ACCOUNT' => //무통장 계좌번호
            //'DELICOM'=> //배송 택배사명
            //'DELICODE'=> //배송 송장번호
            'URL' => $this->_url['order'],//마이페이지 주문조회
            //'SENDDATE' => ,//무통장 입금기한(발송일)
            'to_tel' => $list['receiver_tel2']
        );*/

        $return = array(
            /*'NM' =>, //이름
            'ORDERID'=> $orderid, //주문번호
            'ORDERPRODUCT' =>, //주문상품 또는 부분발송상품
            'REFUNDPRICE' => //환불예정금액

            'REFUNDDATE' => //반품접수일
            'DELICOM'=> //수거택배사
            'SENDDATE' => //무통장 입금기한(발송일)

            //교환
            'ADDRESS' => //방문주소 (반품)
            'REPRODUCT' =>, //교환상품
            'REOPT' =>, //교환옵션
            'REQUANTITY' =>, //교환수량*/

            'URL' => $this->_url['refund'],//마이페이지 주문조회
            'to_tel' => $list['receiver_tel2']
        );

        return $return;
    }

    /**
     * 발송문자용 회원 정보 가져오기(회원등급관련 별도)
     *
     * @param [type] $memid
     * @return void
     */
    public function getMemInfo($memid){
        $sql = "
                SELECT name, mobile
                FROM tblmember
                WHERE id = '{$memid}' ";
        $row = $this->adodb->getRow($sql);

        $return = array(
            'NM' => $row['name'],
            'to_tel' => Common::Dectypt_AES128CBC($row['mobile'],JayjunKey,JayjunIvKey),
            'URL' => $this->_url['join'],
            'JAYJUNURL' => $this->_jayjunurl
        );

        return $return;
    }

    /**
     * 발송문자용 1:1 문의 정보 가져오기
     *
     * @param [type] $idx
     * @return void
     */
    private function getQaInfo($idx){
        $sql = "
                SELECT name, mobile, subject, date 
                FROM tblpersonal 
                WHERE idx = {$idx} ";
        $row = $this->adodb->getRow($sql);

        $return = array(
            'NM' => $row['name'], //작성자
            'DATE' => $row['date'],//문의날짜
            'Q_SUBJECT' => $row['subject'],//문의제목
            'URL' => $this->_url['qa'],
            'to_tel' => $row['mobile']//1:1문의 경로
        );

        return $return;
    }

    /**
     * 발송문자용 회원등급 정보 가져오기
     *
     * @param [type] $memid
     * @return void
     */
//    @TODO 이전 등급 혜택 구하기 등급변하고 데이터 넣으면 바로 적용돼서 셀렉트해올수있는지?
    private function getMemGradeInfo($memid){
        $current_date = date('Y-m-d',strtotime(NOW));
        $sql = "
            SELECT before_group, after_group
            FROM tblmemberchange
            WHERE mem_id = '{$memid}'
            AND before_group < after_group
            AND change_date = {$current_date}
            ";
        $row = $this->adodb->getRow($sql);

        //이전등급 혜택
        $sql = "
            SELECT group_name
            FROM tblmembergroup
            WHERE group_code = '{$row['before_group']}'
            ";
        $before_row = $this->adodb->getRow($sql);

        //변경된등급 혜택
        $sql = "
            SELECT group_name
            FROM tblmembergroup
            WHERE group_code = '{$row['after_group']}'
            ";
        $after_row = $this->adodb->getRow($sql);
        $meminfo = $this->getMemInfo($memid);
        $return = array(
            'NM' => $meminfo['NM'],
//            @TODO 등급혜택 내용 추가
            'BEFOREGRADE' => $before_row['group_name'], //이전등급 혜택
            'AFTERGRADE' => $before_row['after_name'], //변경된등급 혜택
            'URL' => $this->_url['grade'],
            'JAYJUNURL' => $this->_jayjunurl,
            'to_tel' => $meminfo['to_tel']
        );

        return $return;
    }

    /**
     * 발송문자용 쿠폰 정보 가져오기
     *
     * @param [type] $coupon_code
     * @return void
     */
    //sale_type 1: %적립, 2:할인, 3: 원 적립, 4: 원 할인
//    @TODO 쿠폰 리뉴얼 후에 중복사용가능 여부도 필요함. 유효기간도 구해줘야함.
    private function getCouponInfo($ci_no){
        $sale_type = '';
        $sql = "
                SELECT cissue.id, cinfo.sale_type, cinfo.sale_money, cissue.date_start, cissue.date_end, cinfo.coupon_type
                FROM tblcouponinfo cinfo
                JOIN tblcouponissue cissue ON cinfo.coupon_code = cissue.coupon_code
                WHERE cinfo.issue_status = 'Y' AND cissue.ci_no = {$ci_no} ";
        $row = $this->adodb->getRow($sql);

        //print_r($sql);

        $member = $this->getMemInfo($row['id']);
        $EDD = date('Y.m.d H',strtotime($row['date_end']."0000")); //@TODO DB 날짜 형식 변경시 변경해줘야함 지금형태 char(10)

        switch ($row['sale_type']){
            case '1' :
            case '2' :
                $sale_type = '%';
                break;
            case '3' :
            case '4' :
                $sale_type = '원';
        }
        $return = array(
            'NM' => $member['NM'], //이름
            'EDD' => $EDD,//유효기간
            'SALEPRICE' => $row['sale_money'], //쿠폰 할인금액, 할인율
            'SALETYPE' => $sale_type, //sale_type 1: %적립, 2:할인, 3: 원 적립, 4: 원 할인
//            @TODO 쿠폰타입, 중복사용가능 여부
            //'COUPONTYPE' => $row['coupon_type'], //
            //'DUPLE'=> $row[''],// 중복사용가능 일경우 : (중복사용가능), 아닐경우 : 빈칸

            'URL' => $this->_url['coupon'],//마이페이지 주문조회
            'to_tel' => $member['to_tel']
        );

        return $return;
    }


    /**
     * 생일 문자 발송
     *
     * @param [type] $sms_code 문자코드
     * @param [type] $coupon_type 쿠폰 종류
     * @return void
     */
    public function sendBatchCouponSms($sms_code, $coupon_type){

        //호출 SMS코드로 저장된 SMS 정보 가져오기
        $smsinfo = $this->getSmsInfoRow($sms_code);
        $base_contents = $smsinfo['contents'];
        if ($smsinfo['admin_comment']) $this->etcmsg = $smsinfo['admin_comment'];

        $send_type = $smsinfo['send_type'] ?: 'D';

        //발송 목록
        $birth_member_array = array();

        $birth_sql = "
            SELECT ci.ci_no, m.id, m.email, m.name, m.mobile, c.coupon_name, c.coupon_description, ci.date_end, c.sale_type, c.sale_price
            FROM tblcouponinfo c
            JOIN tblcouponissue ci ON c.idx = ci.coupon_code::integer
            JOIN tblmember m ON m.id = ci.id
            WHERE c.type_publish_kind = '".$coupon_type."'
            AND c.issue_status = 'Y'
            AND now() BETWEEN ci.date_start AND ci.date_end
            AND to_char(ci.date,'YYYY-MM') = to_char(now(),'YYYY-MM')
            ";

        $birth_rs = $this->adodb->Execute($birth_sql);
        while($birth_row = $birth_rs->FetchRow()) {
            if (!isset($deli_array[$birth_row['ci_no']])) { //쿠폰 발급 번호가 같으면 생략
                $birth_member_array[$birth_row['ci_no']] = array(
                    'NM' => $birth_row['name'],//쿠폰 발급받은 회원
                    'EDD' =>  date('Y.m.d H',strtotime($birth_row['date_end'])),//쿠폰 만료일자
                    'URL' => $this->_url['coupon'],//마이페이지 쿠폰
                    'to_tel' => Common::Dectypt_AES128CBC($birth_row['mobile'], JayjunKey, JayjunIvKey),
                );
            }
        }

        /*print_r($birth_member_array);
        exit;*/

        //쿠폰발급번호 기준으로 데이터 분류 후 문자 발송 처리
        foreach ($birth_member_array as $k){
            $data = $k;
            $to_tel = str_replace('-', '', $data['to_tel']);
            //print_r($data);
            //값치환하기
            $contents = $this->setContents($data, $base_contents);

            //문자 로그테이블에 삽입
            $record = array(
                'title' => $smsinfo['title'],
                'msg' => $contents,
                'reg_date' => NOW,
                'from_tel' => $this->from_tel,
                'to_tel' => $to_tel,
                'etc_msg' => $this->etcmsg,
                'res_msg' => $this->resdata,
                'status' => $this->status,
                'msg_type' => $this->msg_type,
                'send_type' => $send_type,
            );

            $idx = $this->_log($record);
            //$idx = $this->_log($smsinfo['title'], $contents, $this->from_tel, $to_tel, $this->etcmsg, $this->resdata, $this->status, $this->msg_type);

            $this->curl_request_async(SHOPURL . "/proc/sms_send.proc.php", array('idx' => $idx));
        }
    }

}

?>