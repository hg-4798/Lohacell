<?php
class MAIL extends COMMON {

    //기본값 설정
    var $etcmsg =""; //기타정보
    var $resdata =""; //처리결과 값
    var $status ="W"; //발신상태값(W:발신대기, Y: 정상, N:실패)
    var $from_name='아이노아이원'; //보내는 사람 이름
    var $from_mail='cs@jayjun.co.kr';

    //메일코드 정의
    var $_mail_code = array(
        'join' => '회원가입',
        'memberout' => '회원탈퇴',
        'inactive' => '휴면계정 전환안내',
        'order_check' => '주문완료',
        'order_pay' => '결제완료',
        'shipment' => '상품발송',
        'refund' => '반품신청',
        'exchange' => '교환신청',
        'order_cancel_end' => '주문취소완료',
        'inquiry_answer' => '1:1문의 답변완료',
        'grade' => '회원등급안내'
    );

    var $arrayCustomerHeadTitle = array(
        "1"=>"로그인",
        "2"=>"회원가입",
        "3"=>"구매관련",
        "4"=>"배송관련",
        "5"=>"결제관련",
        "6"=>"매장관련",
        "7"=>"기타"
    );

    var $_url = array(
        'order' => SHOPURL.'/front/mypage_orderlist.php',
        'qa' => SHOPURL.'/front/mypage_personal.php',
        'grade' => SHOPURL.'/front/benefit.php',
        'join' => SHOPURL.'/front/benefit.php',
        'coupon' => SHOPURL.'/front/mypage_coupon.php'
    );

    public function send_mail($mail_code, $data_key, $argu = array()){

        //자체 발송 하는 메일
        $send_flag = false;
        switch($mail_code) {
            case 'shipment': //발송완료(배송중처리시)
                $product_valid = implode(',',$argu);
                $this->sendDeliMail($mail_code, $data_key, $product_valid);
                $send_flag = true;
                break;
            case 'grade': //회원등급 안내 메일
                $this->sendGradeMail($mail_code);
                $send_flag = true;
                break;
	        case 'inactive': //휴면 예정 안내 메일
		        $this->sendSleepMail($mail_code);
		        $send_flag = true;
		        break;
            default:
                break;
        }

        //자체 sms발송 한 문자일경우 아래의 sms발송 프로세스 종료
        if($send_flag) return;

        //호출 mail코드로 저장된  정보 가져오기
        $mailinfo = $this->getMailInfoRow($mail_code);
        $mail_template = $mailinfo['template_name'];
        if($mailinfo['admin_comment']) $this->etcmsg = $mailinfo['admin_comment'];
        $section = $mailinfo['section'];

        //print_r($section);

        //즉시 발급인지 아닌지
        $send_type = $mailinfo['send_type']?:'D';

        $data=array();
        switch($section){
            case 'order' : //주문
                $data = $this->getOrderBasic($data_key);
                break;
            case 'refund' : //반품
                $data = $this->getRefundInfo($data_key);
                break;
            case 'qa' :  //1:1문의
                $data = $this->getQaInfo($data_key);
                break;
            case 'memberout' : //탈퇴
                $data = $this->getMemOutInfo($data_key);
                break;
            case 'grade' : //등급변경
                $data = $this->getMemGradeInfo($data_key);
                break;
            case 'join' : //가입
                $data = $this->getMemInfo($data_key);
                break;
	        case 'exchange' : //교환
		        $data = $this->getExchangeInfo($data_key);
		        break;
	        case 'order_cancel_end' : //주문취소완료
		        $data = $this->getCancelInfo($data_key);
		        break;
        }

        //메일 내용 셋팅
        $assign = $data;

        //pre($data);

        //메일 내용 적용
        $body = _fetch('mail/'.$mail_template.'.html', $assign);

        /*print_r($body);
        exit;*/
        //메일 로그테이블에 삽입
        $idx = $this->_log($mailinfo['title'], pg_escape_string($body), $this->from_mail, $data['to'], $this->etcmsg, $this->resdata, $this->status);

        //백그라운드 실행
        $this->curl_request_async(SHOPURL."/proc/mail_send.proc.php", array('idx'=>$idx));

        //$this->send($data['to'],$title,$body);
    }

    /**
     * 설정된 메일 정보 가져오기
     *
     * @param [type] $sms_code
     * @return void
     */
    public function getMailInfoRow($mail_code){
        $sql = "SELECT title, template_name, send_type, admin_comment, section FROM tblmail_config WHERE template_name = '{$mail_code}' ";
        $row = $this->adodb->getRow($sql);

        //print_r($sql);
        return $row;
    }


    /**
     * 실제 발송 함수 백그라운드 실행 처리(내부에서 send함수 호출함)
     *
     * @param [type] $uri
     * @param [type] $params
     * @return void
     */
    function curl_request_async($uri, $params, $type='POST') {
        $command = "curl ";
        foreach ($params as $key => &$val)
            $command .= "-F '$key=$val' ";
        $command .= "$uri -s > /dev/null 2>&1 &";
        //$command .= "$uri >> ".DOC_ROOT."/proc/log/".date("Ymd").".txt";
        passthru($command);
    }

    /**
     * 전송할 문자 데이터(문자로그) 가져오기
     *
     * @param [type] $idx
     * @return void
     */
    public function getMailMsgRow($idx){
        $sql = "SELECT * FROM tblmail_log WHERE idx = {$idx} ";
        $row = $this->adodb->getRow($sql);

        return $row;
    }


    /**
     * 메일 상태 변경
     *
     * @param [type] $idx
     * @return void
     */ //W: 발송대기, I: 발송중, S:성공, F: 실패
    public function setMailStatus($idx, $status){
        $success = true;
        $sql = "UPDATE tblmail_log SET res_msg = '발송중', status = '{$status}', send_date = now() WHERE idx = ".$idx;
        $rs = $this->adodb->Execute($sql);

        if(!$rs) $success=false;

        return $success;
    }



    /**
     * 실제발송처리
     *
     * @return void
     */
    public function send($idx='')
    {
        /*return array(
            'to'=>$to,
            'title'=>$title,
            'body'=>$body,
        );*/

        //입력된 메일로그 테이블에서 조회하여 발송
        $msg_row = $this->getMailMsgRow($idx);

        //발송중으로 상태 변경
        $this->setMailStatus($idx,"I");

        $this->from_mail = $msg_row['from_mail']; ///발신자번호
        $this->to_mail = $msg_row['to_mail']; //받는사람번호 여러개인경우 , 로 구분 최대 100개
        $this->title = $msg_row['title']; //LMS,MMS 일때 문자 제목
        $this->contents =  $msg_row['msg']; //문자내용


            $res = array();
            $res["res"] = "N";
            $res["msg"] = "";
            $res["no"] = "";
            $phpmailer = new PHPMailer;

//$mail->SMTPDebug = 3; // Enable verbose debug output

            $phpmailer->isSMTP(); // Set mailer to use SMTP
            $phpmailer->Host = 'mail.ajashop.co.kr'; // Specify main and backup SMTP servers
            $phpmailer->SMTPAuth = true; // Enable SMTP authentication
            $phpmailer->Username = 'jayjun'; // SMTP username
            $phpmailer->Password = 'J2jUn&&24'; // SMTP password
            $phpmailer->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $phpmailer->Port = 587; // TCP port to connect to
            $phpmailer->CharSet = "UTF-8"; //

            $phpmailer->setFrom($this->from_mail, $this->from_name);
            $phpmailer->addAddress($this->to_mail); // Add a recipient
//    $mail->addAddress("omkjuly84@naver.com"); // Add a recipient

//$mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name
            $phpmailer->isHTML(true); // Set email format to HTML

            $phpmailer->Subject =  $this->title;
            $phpmailer->Body = $this->contents;
//    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


            if(!$phpmailer->send()) {
                $res["msg"] = $phpmailer->ErrorInfo;
                $sql = "UPDATE tblmail_log SET res_msg = '발송실패 ".$phpmailer->ErrorInfo."', status = 'F', send_date = now() WHERE idx = ".$idx;
            } else {
                $sql = "UPDATE tblmail_log SET res_msg = '정상발송', status = 'S', send_date = now() WHERE idx = ".$idx;
                $res["msg"] = "success";
            }
        $this->adodb->Execute($sql);

        return true;
    }


    /**
     * 로그저장(로그테이블저장)
     *
     * @return void
     */
    private function _log($title, $msg, $from_mail, $to_mail, $etcmsg='', $resdata='', $status='W'){
        $sql = "INSERT INTO tblmail_log(title, msg, reg_date, from_mail, to_mail, etc_msg, res_msg, status )";
        $sql .= " VALUES('" . $title . "','" . $msg . "',now(),'" . $from_mail . "','" . $to_mail . "','" . $etcmsg . "','" . $resdata . "','". $status. "')RETURNING idx ";
        $row = pmysql_fetch_array(pmysql_query($sql,get_db_conn()));
//print_r($sql);
        return $row['idx'];
    }


    /**
     * 발송메일용 회원 정보 가져오기(회원등급관련 별도)
     *
     * @param [type] $memid
     * @return void
     */
    private function getMemInfo($memid){
        $sql = "
                SELECT id, name, email, date
                FROM tblmember
                WHERE id = '{$memid}' ";

        $row = $this->adodb->getRow($sql);

        $return = array(
            'name' => $row['name'],
            'to' => Common::Dectypt_AES128CBC($row['email'],JayjunKey,JayjunIvKey),
            'id' => $row['id'],
            'date' => date('Y.m.d',strtotime($row['date'])),
            'url' => SHOPURL."/front/login.php",
            'key' => Common::Enctypt_AES128CBC($row["id"],JayjunKey,JayjunIvKey)
        );

        return $return;
    }


    /**
     * 발송메일용 탈퇴회원 정보 가져오기
     *
     * @param [type] $memid
     * @return void
     */
    private function getMemOutInfo($memid){
        $sql = "
                SELECT id, name, email, date
                FROM tblmemberout
                WHERE id = '{$memid}' ";

        $row = $this->adodb->getRow($sql);

        $return = array(
            'name' => $row['name'],
            'to' => Common::Dectypt_AES128CBC($row['email'],JayjunKey,JayjunIvKey),
            'id' => $row['id'],
            'date' => date('Y.m.d',strtotime($row['date'])),
        );

        return $return;
    }

    /**
     * 발송메일용 1:1 문의 정보 가져오기
     *
     * @param [type] $idx
     * @return void
     */
    private function getQaInfo($idx){
        $sql = "
                SELECT * FROM tblpersonal WHERE idx = {$idx} ";
        $row = $this->adodb->getRow($sql);

        $return = array(
            'name' => $row['name'],
            'date' => date('Y-m-d',strtotime($row['date'])),//문의날짜
            'subject' => $row['subject'],//문의내용
            'content' => $row['content'],//문의제목
            'to' => $row['email'],
            'head_title' => $this->arrayCustomerHeadTitle[$row['head_title']],
            're_date' => date('Y.m.d',strtotime($row['re_date'])),//답변날짜
            're_content' => $row['re_content'],
            'url' => SHOPURL."/front/mypage_personal.php"
        );

        return $return;
    }


    /**
     * 메일발송용 주문 정보 가져오기(주문취소,반품 별도)
     *
     * @param [type] $order_code
     * @return void
     */

    private function getOrderBasic($order_num, $product_code=''){
        global $_CONFIG;
        $sql = "
                SELECT *
                FROM tblorder_basic
                WHERE order_num = '{$order_num}'
                ";
        $row = $this->adodb->getRow($sql);


        $payment_sql = "
                SELECT *
                FROM tblorder_payment
                WHERE order_num = '{$order_num}'
                ";

        $payment_row = $this->adodb->getRow($payment_sql);
        $res_info = $payment_row['res_info'];
        $res_info = unserialize($res_info);



//        $product_sql = "
//                SELECT p.prodcode, p.tinyimage, p.productname, op.productcode, op.price_end, op.mileage_expect, op.option_type, op.option_code
//                FROM tblorder_product op
//                JOIN tblproduct p ON op.productcode = p.productcode
//                WHERE order_num = '{$order_num}'
//        ";
//
//        $product_rs = $this->adodb->Execute($product_sql);
//        $product_list = array();
//
//        //print_r($sql);
//        $cnt = 0;
//        while($product_row = $product_rs->FetchRow()) {
//            switch ($product_row['option_type']) {
//                case 'option' :
//                    $detail_sql = "SELECT *
//                                   FROM tblproduct_option
//                                   WHERE option_num = '{$product_row['option_code']}'
//                                  ";
//                    break;
//                case 'product' :
//                    $detail_sql = "SELECT *
//                                   FROM tblproduct
//                                   WHERE productcode = '{$product_row['option_code']}'
//                                  ";
//                    break;
//                case 'gift' :
//                    $detail_sql = "";
//                    break;
//            }
//            $product_row['detail'] = $this->adodb->getRow($detail_sql);
//            $product_list[] = $product_row;
//        }


        $row['pg_paymethod_txt'] = $_CONFIG['pay_method'][$row['pg_paymethod']]; //결제수단

        $return = array(
            'receiver_name' => $row['receiver_name'],
            'receiver_zipcode' => $row['receiver_zipcode'],//우편번호
            'receiver_addr' => $row['receiver_addr'],//주소
            'receiver_addr_detail' => $row['receiver_addr_detail'],//상세주소
            'receiver_mobile' => $row['receiver_mobile'],//휴대전화
            'receiver_memo' => $row['receiver_memo'],//배송메모

            'receiver_tel' => $row['receiver_tel'],//전화번호
            'buyer_name' => $row['buyer_name'],//주문자명
            'buyer_email' => $row['buyer_email'],//주문자 메일
            'buyer_mobile' => $row['buyer_mobile'],//주문자 휴대전화
            'buyer_tel' => $row['buyer_tel'],//주문자 전화번호

            'sum_consumer' => $row['sum_consumer'],//총 상품금액
            'sum_pr_discount' => $row['sum_discount'],//총 상품할인금액
            'pay_pg' => $row['pay_pg'],//총 결제금액(상품구매금액 + 배송비 - 총 할인금액)
            'coupon_discount' => $row['coupon_product_discount']+$row['coupon_basket_discount'],//쿠폰 할인금액
            'use_point' => $row['use_point'],//구매사용 포인트
            'use_mileage' => $row['use_mileage'],//구매사용 마일리지

            'pay_delivery' => $row['pay_delivery']-$row['coupon_delivery_discount'],//결제 배송비( 책정된 배송비 - 배송비쿠폰 가격)
            'sum_mileage' => $row['sum_mileage'],//지급예정 마일리지
            'sum_end' => $row['sum_end'],//주문금액
            'order_num' => $row['order_num'],//주문번호
            'order_title' => $row['order_title'], //주문내용

            'va_date' => date('Y-m-d H:i:s',strtotime($res_info['va_date'])),//입금기한
            'bankname' => $res_info['bankname'],//은행
            'account' => $res_info['account'],//계좌번호
            'depositor' => $res_info['depositor'],//예금주
            'member_id' => $res_info['member_id'],//주문자 ID
            'pg_paymethod' => $row['pg_paymethod_txt'], //결제수단

            'to' => $row['buyer_email'],
            'date_insert' => date('Y-m-d',strtotime($row['date_insert'])),//주문일

//            @TODO url처리
            'url' => SHOPURL."/front/mypage.php"
        );

/*        print_r($product_list);
        exit;*/
        return $return;
    }


    /**
     * 메일 발송 (배송중 처리즉시)
     *
     * @param [type] $sms_code 메일코드
     * @param [type] $order_num 주문번호
     * @param string $product_idx 주문상품pk, 콤마로 다중연결
     * @return void
     */
    public function sendDeliMail($mail_code, $order_num, $product_idx=''){
        $sql = "
                SELECT *
                FROM tblorder_basic
                WHERE order_num = '{$order_num}'
                ";
        $row = $this->adodb->getRow($sql);

        //호출 mail코드로 저장된  정보 가져오기
        $mailinfo = $this->getMailInfoRow($mail_code);
        $base_mail_template = $mailinfo['template_name'];
        if($mailinfo['admin_comment']) $this->etcmsg = $mailinfo['admin_comment'];

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
            $option_code = $deli_row['option_code']; //상품코드
            $option_type = $deli_row['option_type'];

            $Product = new PRODUCT();
	        $product = array();
            if($option_type =='option') {
	            $product = $Product->getRowSimple($productcode, false, 'productname, use_imgurl, tinyimage');
                $option = $Product->getOptionRow($option_code,'option_num, option_name');
            }
            else {
	            $product = $Product->getRowSimple($option_code, false, 'productname, use_imgurl, tinyimage');
                $option = array();
            }
			//자체 이미지 일경우(url이미지가 아닐경우)
	        if($product['use_imgurl'] == 'N'){
		        $product['tinyimage'] = SHOPURL.$product['tinyimage'];
	        }

	        $product['option_type'] = $option_type;
	        $product['option_info'] = $option;

            //송장번호가 다를경우
            if (!isset($deli_array[$delivery_key])) {
                //택배사
                if ($deli_row["delivery_company"]) {
                    $deli_com = $this->adodb->getOne("SELECT company_name FROM tbldelicompany WHERE code = '{$deli_row["delivery_company"]}'");
                }

                $deli_array[$delivery_key] = array(
                    'deli_com' => $deli_com, //택배사
                    'deli_code' => $deli_row["delivery_no"], //송장번호
                    'order_num' => $row['order_num'],//주문번호
                    'date_insert' => date('Y-m-d',strtotime($row['date_insert'])),//주문일


                    'receiver_name' => $row['receiver_name'],
                    'receiver_zipcode' => $row['receiver_zipcode'],//우편번호
                    'receiver_addr' => $row['receiver_addr'],//주소
                    'receiver_addr_detail' => $row['receiver_addr_detail'],//상세주소
                    'receiver_mobile' => $row['receiver_mobile'],//휴대전화
                    'receiver_memo' => $row['receiver_memo'],//배송메모

                    'receiver_tel' => $row['receiver_tel'],//전화번호
                    'buyer_name' => $row['buyer_name'],//주문자명
                    'buyer_email' => $row['buyer_email'],//주문자 메일
                    'buyer_mobile' => $row['buyer_mobile'],//주문자 휴대전화
                    'buyer_tel' => $row['buyer_tel'],//주문자 전화번호

                    'to' => $row['buyer_email'],
                    'url' => $this->_url['order'],//마이페이지 주문조회
                );

                $deli_array[$delivery_key]['product'][$productcode.$option_code] = array(
                    'price_end' => $deli_row['price_end'],
                    'mileage_expect' => $deli_row['mileage_expect'],
                    'quantity' => 1
                );

                foreach($product as $k => $v){
                    $deli_array[$delivery_key]['product'][$productcode.$option_code][$k] = $v;
                }

            } else {
                //같은 상품이라면
                if(isset($deli_array[$delivery_key]['product'][$productcode.$option_code])){
                    $deli_array[$delivery_key]['product'][$productcode.$option_code]['quantity']++;
                    $deli_array[$delivery_key]['product'][$productcode.$option_code]['price_end'] += $deli_row['price_end'];
                    $deli_array[$delivery_key]['product'][$productcode.$option_code]['mileage_expect'] += $deli_row['mileage_expect'];
                }else {
                    $deli_array[$delivery_key]['product'][$productcode.$option_code] = array(
                        'price_end' => $deli_row['price_end'],
                        'mileage_expect' => $deli_row['mileage_expect'],
                        'quantity' => 1
                    );

                    foreach($product as $k => $v){
                        $deli_array[$delivery_key]['product'][$productcode.$option_code][$k] = $v;
                    }
                }
            }
        }

        //송장번호기준으로 데이터 분류 후 문자 발송 처리
        foreach ($deli_array as $k){
            $data = $k;

            //print_r($data);

            //메일 내용 적용
            $body = _fetch('mail/'.$base_mail_template.'.html', $data);

            //메일 로그테이블에 삽입
            $idx = $this->_log($mailinfo['title'], pg_escape_string($body), $this->from_mail, $data['to'], $this->etcmsg, $this->resdata, $this->status);

            //백그라운드 실행
            $this->curl_request_async(SHOPURL."/proc/mail_send.proc.php", array('idx'=>$idx));
        }
    }

    /**
     * 개별 메일 발송 (배송중 처리즉시)
     *
     * @param [type] $sms_code 메일코드
     * @param [type] $order_num 주문번호
     * @param string $product_idx 주문상품pk, 콤마로 다중연결
     * @return void
     */
    public function sendMemberMail($title, $body, $from='', $to)
    {
        //메일 로그테이블에 삽입
        $idx = $this->_log($title, pg_escape_string($body), $from, $to, "개별메일발송", $this->resdata, $this->status);

        //백그라운드 실행
        $this->curl_request_async(SHOPURL . "/proc/mail_send.proc.php", array('idx' => $idx));
    }


    /**
     * 등급별 메일 발송 (배송중 처리즉시)
     *
     * @param [type] $sms_code 메일코드
     * @param [type] $order_num 주문번호
     * @param string $product_idx 주문상품pk, 콤마로 다중연결
     * @return void
     */
    public function sendGradeMail($mail_code){

        //호출 mail코드로 저장된  정보 가져오기
        $mailinfo = $this->getMailInfoRow($mail_code);
        $base_mail_template = $mailinfo['template_name'];
        if($mailinfo['admin_comment']) $this->etcmsg = $mailinfo['admin_comment'];

        //등급 메일 보낼 목록 및 정보
        $grademail_array = array();

        $member_sql = "
            SELECT m.id, m.name, m.email, m.group_code, mg.group_name, mg.group_ap_s, mg.group_ap_e, msvy.sum_year_price, (SELECT group_ap_s FROM tblmembergroup WHERE group_level > mg.group_level LIMIT 1) as next_level_price
            FROM tblmember m
            JOIN tblmembergroup mg ON m.group_code = mg.group_code
            LEFT JOIN tblmembersumprice_view_year msvy ON m.id = msvy.id
            WHERE m.member_out = 'N'
            AND m.news_yn IN ('Y','M')
            ";

        $member_rs = $this->adodb->Execute($member_sql);
        while($member_row = $member_rs->FetchRow()) {
            $member_row['email'] = Common::Dectypt_AES128CBC($member_row['email']);
            $member_row['group_img'] = SHOPURL."/data/shopimages/grade/groupimg_".$member_row['group_code'].".png";
            $member_row['next_need_price'] = $member_row['next_level_price'] - $member_row['sum_price_year'];
            $member_row['key'] = Common::Enctypt_AES128CBC($member_row["id"],JayjunKey,JayjunIvKey);
            $grademail_array[] = $member_row;
        }

        $grade_array = array();
        $grade_sql = "
            SELECT group_code, group_name, group_description, group_ap_s, group_ap_e
            FROM tblmembergroup
            ORDER BY group_level DESC
            ";

        $grade_rs = $this->adodb->Execute($grade_sql);
        while($grade_row = $grade_rs->FetchRow()) {
            $member_row['grade_img'] = SHOPURL."/data/shopimages/grade/groupimg_".$member_row['group_code'].".png";

            $grade_array[] = $grade_row;
        }


        //송장번호기준으로 데이터 분류 후 문자 발송 처리
        foreach ($grademail_array as $k){
            $data = $k;
            $data['grade_info'] = $grade_array;
            $data['url'] = $this->_url['grade']; //마이페이지 등급혜택 페이지

			//$data;
            //메일 내용 적용
            $body = _fetch('mail/'.$base_mail_template.'.html', $data);

            //메일 로그테이블에 삽입
            $idx = $this->_log($mailinfo['title'], pg_escape_string($body), $this->from_mail, $data['email'], $this->etcmsg, $this->resdata, $this->status);

            //백그라운드 실행
            $this->curl_request_async(SHOPURL."/proc/mail_send.proc.php", array('idx'=>$idx));
        }
    }

	/**
	 * 휴면예정 메일 발송
	 *
	 * @param [type] $sms_code 메일코드
	 * @param [type]
	 * @return void
	 */
	public function sendSleepMail($mail_code){

		//호출 mail코드로 저장된  정보 가져오기
		$mailinfo = $this->getMailInfoRow($mail_code);
		$base_mail_template = $mailinfo['template_name'];
		if($mailinfo['admin_comment']) $this->etcmsg = $mailinfo['admin_comment'];

		//등급 메일 보낼 목록 및 정보
		$sleepmail_array = array();

		$member_sql = "
				SELECT * FROM tblmember
				WHERE member_out = 'N' 
				AND logindate < to_char(now()- interval '11 month', 'YYYYMMDD000000') AND date < to_char(now()- interval '11 month', 'YYYYMMDD000000')
				ORDER BY name ASC
            ";

		$member_rs = $this->adodb->Execute($member_sql);
		while($member_row = $member_rs->FetchRow()) {
			if(!$member_row['logindate']){
				$member_row['logindate'] = $member_row['date'];
			}
			$member_row['email'] = Common::Dectypt_AES128CBC($member_row['email']);
			$member_row['mobile'] = Common::Dectypt_AES128CBC($member_row['mobile']);
			$sleepmail_array[] = $member_row;
		}


		foreach ($sleepmail_array as $k){
			$data = $k;
			$data['url'] = $this->_url['grade']; //마이페이지 등급혜택 페이지

			//$data;
			//메일 내용 적용
			$body = _fetch('mail/'.$base_mail_template.'.html', $data);

			//메일 로그테이블에 삽입
			$idx = $this->_log($mailinfo['title'], pg_escape_string($body), $this->from_mail, $data['email'], $this->etcmsg, $this->resdata, $this->status);

			//백그라운드 실행
			$this->curl_request_async(SHOPURL."/proc/mail_send.proc.php", array('idx'=>$idx));
		}
	}


	/**
	 * 메일발송용 반품 정보 가져오기(주문취소, 별도)
	 *
	 * @param [type] $order_code
	 * @return void
	 */

	private function getRefundInfo($return_idx, $product_code=''){
		//반품요청서 정보
		$return_sql = "
				select rt.order_num, ob.buyer_email, rt.receiver_mobile, rt.receiver_name, rt.receiver_zipcode
					, rt.receiver_addr, rt.receiver_addr_detail, rt.receiver_tel, rt.date_status_1, rt.refund_idx
				from tblorder_return as rt
				JOIN tblorder_basic ob ON rt.order_num = ob.order_num
				where rt.idx =  {$return_idx}
                ";
		$return_info_row = $this->adodb->getRow($return_sql);

		//환불정보 (반품배송비)
		$refund_sql = "
				select pay_delivery
				from tblorder_refund
				where idx =  {$return_info_row['refund_idx']}
                ";
		$refund_info = $this->adodb->getRow($refund_sql);

		//반품상품 정보
        $product_sql = "
                SELECT p.prodcode, p.use_imgurl, p.tinyimage, p.productname, op.productcode, op.price_end, op.mileage_expect, op.option_type, op.option_code
                FROM tblorder_product op
                JOIN tblproduct p ON op.productcode = p.productcode
                WHERE cs_idx = '{$return_idx}' AND cs_type = 'R'
        ";

        $product_rs = $this->adodb->Execute($product_sql);
        $product_list = array();

		$Product = new PRODUCT();
        //print_r($sql);
        //$cnt = 0;
        while($product_row = $product_rs->FetchRow()) {
            switch ($product_row['option_type']) {
                case 'option' :
	                $option = $Product->getOptionRow($product_row['option_code'],'option_num, option_name');
                    break;
                case 'product' :
	                $product = $Product->getRowSimple($product_row['option_code'], false, 'productname, tinyimage');
	                $product_row['productname'] = $product['productname'];
	                $product_row['tinyimage'] = $product['tinyimage'];
	                $option = array();
                    break;
                case 'gift' :
	                $option = "";
                    break;
            }
	        //자체 이미지 일경우(url이미지가 아닐경우)
	        if($product_row['use_imgurl'] == 'N'){
		        $product_row['tinyimage'] = SHOPURL.$product_row['tinyimage'];
	        }
	        $product_row['option_info'] = $option;
	        $product_row['quantity'] = 1;
            if(isset($product_list[$product_row['productcode'].$product_row['option_code']])){
	            $product_list[$product_row['productcode'].$product_row['option_code']]['quantity']++;
	            $product_list[$product_row['productcode'].$product_row['option_code']]['price_end'] += $product_row['price_end'];
	            $product_list[$product_row['productcode'].$product_row['option_code']]['mileage_expect'] += $product_row['mileage_expect'];
            }else {
	            $product_list[$product_row['productcode'].$product_row['option_code']] = $product_row;
            }
        }

		//$row['pg_paymethod_txt'] = $_CONFIG['pay_method'][$row['pg_paymethod']]; //결제수단

		$return = array(
			'receiver_name' => $return_info_row['receiver_name'],
			'receiver_zipcode' => $return_info_row['receiver_zipcode'],//우편번호
			'receiver_addr' => $return_info_row['receiver_addr'],//주소
			'receiver_addr_detail' => $return_info_row['receiver_addr_detail'],//상세주소
			'receiver_mobile' => $return_info_row['receiver_mobile'],//휴대전화

			'receiver_tel' => $return_info_row['receiver_tel'],//전화번호
			'buyer_email' => $return_info_row['buyer_email'],//주문자 메일
			'order_num' => $return_info_row['order_num'],//주문번호
			'date_refund' => date('Y-m-d',strtotime($return_info_row['date_status_1'])),//반품신청일
			'product_list' => $product_list,
			'refund_info' => $refund_info, //반품배송비

			'to' => $return_info_row['buyer_email'],
//            @TODO url처리
			'url' => SHOPURL."/front/mypage.php"
		);

		/*        print_r($product_list);
				exit;*/
		return $return;
	}


	/**
	 * 메일발송용 교환 정보 가져오기(주문취소, 별도)
	 *
	 * @param [type] $order_code
	 * @return void
	 */

	private function getExchangeInfo($exchange_idx, $product_code=''){
		//교환요청서 정보
		$exchange_sql = "
				 select re.order_num, ob.buyer_email, re.receiver_mobile, re.receiver_name, re.receiver_zipcode
					, re.receiver_addr, re.receiver_addr_detail, re.receiver_tel, re.date_status_1, re.delivery_pay, delivery_pay_method
				from tblorder_exchange as re
				JOIN tblorder_basic ob ON re.order_num= ob.order_num
				where re.idx =  {$exchange_idx}
                ";
		$exchange_info_row = $this->adodb->getRow($exchange_sql);

		switch ($exchange_info_row['delivery_pay_method']){
			case 'append' : $delivery_pay_method = '동봉';
				break;
			case 'bank' : $delivery_pay_method = '계좌입금 : 신한은행 140-011-167492 | 예금주 : 제이준코스메틱(주)';
				break;
		}

		//교환상품 정보
		$product_sql = "
                SELECT p.prodcode, p.use_imgurl, p.tinyimage, p.productname, op.productcode, op.price_end, op.mileage_expect, op.option_type, op.option_code, oep.exchange_option_code
                FROM tblorder_exchange_product as oep
                JOIN tblorder_product as op ON oep.order_product_idx = op.idx
                JOIN tblproduct p ON op.productcode = p.productcode
                WHERE oep.exchange_idx = {$exchange_idx}
        ";

		$product_rs = $this->adodb->Execute($product_sql);
		$product_list = array();

		$Product = new PRODUCT();
		//print_r($sql);
		//$cnt = 0;
		while($product_row = $product_rs->FetchRow()) {
			switch ($product_row['option_type']) {
				case 'option' :
					$option = $Product->getOptionRow($product_row['option_code'],'option_num, option_name');
					$exchange_option = $Product->getOptionRow($product_row['exchange_option_code'],'option_num, option_name');
					break;
				case 'product' :
					$product = $Product->getRowSimple($product_row['option_code'], false, 'productname, tinyimage');
					$product_row['productname'] = $product['productname'];
					$product_row['tinyimage'] = $product['tinyimage'];
					$option = array();
					break;
				case 'gift' :
					$option = "";
					break;
			}

			//자체 이미지 일경우(url이미지가 아닐경우)
			if($product_row['use_imgurl'] == 'N'){
				$product_row['tinyimage'] = SHOPURL.$product_row['tinyimage'];
			}
			$product_row['option_info'] = $option;
			$product_row['quantity'] = 1;
			if(isset($product_list[$product_row['productcode'].$product_row['option_code']])){
				$product_list[$product_row['productcode'].$product_row['option_code']]['quantity']++;
				$product_list[$product_row['productcode'].$product_row['option_code']]['price_end'] += $product_row['price_end'];
				$product_list[$product_row['productcode'].$product_row['option_code']]['mileage_expect'] += $product_row['mileage_expect'];
			}else {
				$product_list[$product_row['productcode'].$product_row['option_code']] = $product_row;
			}
		}

		//$row['pg_paymethod_txt'] = $_CONFIG['pay_method'][$row['pg_paymethod']]; //결제수단

		$return = array(
			'receiver_name' => $exchange_info_row['receiver_name'],
			'receiver_zipcode' => $exchange_info_row['receiver_zipcode'],//우편번호
			'receiver_addr' => $exchange_info_row['receiver_addr'],//주소
			'receiver_addr_detail' => $exchange_info_row['receiver_addr_detail'],//상세주소
			'receiver_mobile' => $exchange_info_row['receiver_mobile'],//휴대전화

			'receiver_tel' => $exchange_info_row['receiver_tel'],//전화번호
			'buyer_email' => $exchange_info_row['buyer_email'],//주문자 메일
			'order_num' => $exchange_info_row['order_num'],//주문번호
			'date_refund' => date('Y-m-d',strtotime($exchange_info_row['date_status_1'])),//교환신청일
			'product_list' => $product_list,
			'delivery_pay' => $exchange_info_row['delivery_pay'], //교환배송비
			'delivery_pay_method' => $delivery_pay_method, //교환배송비 결제방법
			'exchange_option' => $exchange_option, //교환옵션

			'to' => $exchange_info_row['buyer_email'],
//            @TODO url처리
			'url' => SHOPURL."/front/mypage.php"
		);

        /*print_r($return);
		exit;*/
		return $return;
	}


	/**
	 * 메일발송용 주문 정보 가져오기(주문취소,반품 별도)
	 *
	 * @param [type] $order_code
	 * @return void
	 */

	private function getCancelInfo($cancel_idx, $product_code=''){
		//취소요청서 정보
		$cancel_sql = "
				select oc.order_num, oc.date_insert, oc.refund_idx, oc.reason, oc.memo, ob.buyer_email, ob.buyer_mobile, ob.buyer_name
				from tblorder_cancel as oc
				join tblorder_basic ob ON oc.order_num= ob.order_num
				where oc.idx = {$cancel_idx}
                ";
		$cancel_info_row = $this->adodb->getRow($cancel_sql);

		//환불정보
		$Order = new ORDER();
		$refund_info_row=$Order->getRefundRow($cancel_info_row['refund_idx']);

		//취소상품 정보
		$product_sql = "
                SELECT p.prodcode, p.use_imgurl, p.tinyimage, p.productname, op.productcode, op.price_end, op.mileage_expect, op.option_type, op.option_code
                FROM tblorder_product as op 
                JOIN tblproduct p ON op.productcode = p.productcode
                WHERE op.cs_idx = {$cancel_idx} AND op.cs_type = 'C'
        ";

		$product_rs = $this->adodb->Execute($product_sql);
		$product_list = array();

		$Product = new PRODUCT();
		//print_r($sql);
		//$cnt = 0;
		while($product_row = $product_rs->FetchRow()) {
			switch ($product_row['option_type']) {
				case 'option' :
					$option = $Product->getOptionRow($product_row['option_code'],'option_num, option_name');
					break;
				case 'product' :
					$product = $Product->getRowSimple($product_row['option_code'], false, 'productname, tinyimage');
					$product_row['productname'] = $product['productname'];
					$product_row['tinyimage'] = $product['tinyimage'];
					$option = array();
					break;
				case 'gift' :
					$option = "";
					break;
			}
			//자체 이미지 일경우(url이미지가 아닐경우)
			if($product_row['use_imgurl'] == 'N'){
				$product_row['tinyimage'] = SHOPURL.$product_row['tinyimage'];
			}

			$product_row['option_info'] = $option;
			$product_row['quantity'] = 1;
			if(isset($product_list[$product_row['productcode'].$product_row['option_code']])){
				$product_list[$product_row['productcode'].$product_row['option_code']]['quantity']++;
				$product_list[$product_row['productcode'].$product_row['option_code']]['price_end'] += $product_row['price_end'];
				$product_list[$product_row['productcode'].$product_row['option_code']]['mileage_expect'] += $product_row['mileage_expect'];
			}else {
				$product_list[$product_row['productcode'].$product_row['option_code']] = $product_row;
			}
		}

		//$row['pg_paymethod_txt'] = $_CONFIG['pay_method'][$row['pg_paymethod']]; //결제수단

		$return = array(
			'order_num' => $cancel_info_row['order_num'],//주문번호
			'date_refund' => date('Y-m-d',strtotime($cancel_info_row['date_insert'])),//주문취소일
			'buyer_name' => $cancel_info_row['buyer_name'],
			'buyer_mobile' => $cancel_info_row['buyer_mobile'],//휴대전화
			'reason' => $cancel_info_row['reason'],//주문취소이유
			'memo' => $cancel_info_row['memo'],//주문취소 상세내용

			'price_total' => $refund_info_row['price_total'],//총 상품금액
			'refund_method' => $refund_info_row['refund_method'],//환불수단
			'bank_info'=> $refund_info_row['bank_info'],//환불계좌정보
			'price_product' => $refund_info_row['price_product'],//총 상품금액
			'price_delivery' => $refund_info_row['price_delivery'],//배송비
			'refund_cash' => $refund_info_row['refund_cash'],//현금화불액
			'refund_mileage' => $refund_info_row['refund_mileage'],//환불마일리지
			'refund_point' => $refund_info_row['refund_point'],//환불포인트
			'refund_card' => $refund_info_row['refund_card'],//카드취소액
			'refund_vcnt' => $refund_info_row['refund_vcnt'],//가상계좌취소액
			'product_list' => $product_list,

			'to' => $cancel_info_row['buyer_email'],
//            @TODO url처리
			'url' => SHOPURL."/front/mypage.php"
		);

		/*print_r($return);
		exit;*/
		return $return;
	}
}
?>