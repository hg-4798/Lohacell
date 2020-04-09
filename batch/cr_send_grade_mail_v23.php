#!/usr/local/php/bin/php
<?php
#######################################################################################
# FileName          : run_send_grade_v2.php
# Desc              : 매년 2월1일 02시에 실행되어 등급조정 및 메일링과 쿠폰 지급(구매확정기준)
# Last Updated      : 2018.02.06
# By                : 
# FAMILY            : #3ca461
##!/usr/local/php/bin/php
# [batch]$ ./run_send_grade_v2.sh
#######################################################################################

$Dir="../";
include ($Dir."lib/init.php");
include ($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

// 도메인 정보
//$sql        = "SELECT shopurl FROM tblshopinfo LIMIT 1 ";
//$row        = pmysql_fetch_object(pmysql_query($sql));
//$shopurl    = $row->shopurl;
$shopurl    = "http://shinwonmall.com/";
// 구매확정 기준 등급정보 구하기

$to_dt = date("Y");
$Count_1y=date("Y", strtotime ( $to_dt." - 1Year " )) ;
$Count_2y=date("Y", strtotime ( $to_dt." - 2Year " )) ;
$from_dt = $Count_1y;
$from_dt2 = $Count_2y;

$sql = "select 	mem.id, mem.name, mem.email, mem.news_yn, to_char(to_date(mem.date,'YYYYMMDDSS'),'YYYY-MM-DD') as join_date,
                coalesce(ord.bf_group, '0001') as bf_group, coalesce(ord.af_group, '0001') as af_group, ordercode,
                coalesce(ord.act, 0) as act
        from
		(
			SELECT *
			FROM tblmember
			WHERE 1=1
		) mem
        left join
        (
            select name, id, email, news_yn, bf_group, m_grade as af_group, m_grade, act, ordercode
            from (
                    SELECT  name, id, min(email) as email, min(news_yn) as news_yn, min(bf_group) as bf_group, sum(act) as act,
                            sum(coalesce(op_ordprice,0) - coalesce(op_coupon,0) - coalesce(op_usepoint,0) + coalesce(op_deli_price,0)) M,
                            count(id) OC, string_agg(DISTINCT ordercode::varchar, ',') as ordercode,
							(CASE 
								WHEN sum(act) >= 5000000 THEN '0007'
								WHEN sum(act) >= 3000000 THEN '0000'
								WHEN sum(act) >= 2000000 THEN '0006'
								WHEN sum(act) >= 1000000 THEN '0005'
                             	WHEN sum(act) < 1000000 and sum(act) > 0 THEN '0003'
								WHEN sum(act) = 0 THEN '0001'
							END) AS M_grade
                    FROM
                    (
                        SELECT 'sale' as saletype, o.ordercode, min(o.id) as id, min(m.name) as name, min(coalesce(NULLIF(m.group_code, ''), '0001')) as bf_group,
                                min(m.email) as email, min(m.news_yn) as news_yn,
                                min(o.paymethod) as paymethod,
                                min(o.oldordno) as oldordno, min(is_mobile) as is_mobile,
                                min(o.bank_date) as cdt,
                                min(op.productname) as productname, count(op.productname) as cnt_prod,
                                sum( (op.price+op.option_price) * op.option_quantity) as op_ordprice,
                                sum(op.coupon_price) as op_coupon,
                                sum(op.use_point) as op_usepoint,
                                sum(op.deli_price) as op_deli_price,
                                sum( ((op.price+op.option_price) * op.option_quantity) - op.coupon_price - op.use_point + op.deli_price) as act
                        FROM    tblorderinfo o
                        JOIN    tblorderproduct op on o.ordercode = op.ordercode
                        join
						(
							SELECT *
							FROM tblmember
							WHERE 1=1
						) m on o.id = m.id
                        WHERE   1=1
                        AND     o.regdt >= '".$from_dt."0101000000' and o.regdt <= '".$from_dt."1231235959'
                        AND     o.oi_step1 in ('1', '2', '3', '4')
                        and op.op_step ='4'
                        GROUP BY o.ordercode
                    ) v
                    GROUP BY name, id
            ) z
            ORDER BY m_grade desc
        ) ord on mem.id = ord.id
        order by coalesce(ord.af_group, '0001') desc, mem.id asc
        ";

## 1. 등급 쿠폰 지급 및 등급 갱신, 히스토리 저장
$date_start = date("Ymd")."00";                 // 쿠폰 적용 시작일
$date_end = date("Ym").date("t")."23";          // 쿠폰 사용 마지막일

$result = pmysql_query($sql);
while($row = pmysql_fetch_object($result)) {

	$id = $row->id;
	$af_group = $row->af_group;
	$act = $row->act;
	
	if($row->ordercode==null || $row->ordercode==''){
		$arr_ordercode = '';
		$check_cnt=0;
	}else{
		$arr_ordercode = explode(",",$row->ordercode);
		$check_cnt = count($arr_ordercode);
	}

	if($row->af_group==0000){
		$sql2="select name, id, email, news_yn, bf_group, coalesce(NULLIF(M_grade,''), '0001') as af_group, act
            from (
                    SELECT  name, id, min(email) as email, min(news_yn) as news_yn, min(bf_group) as bf_group, sum(act) as act,
							(CASE 
								WHEN sum(act) >= 3000000 THEN '0007'
							END) AS M_grade
                    FROM
                    (
                        SELECT o.ordercode, min(o.id) as id, min(m.name) as name, min(coalesce(NULLIF(m.group_code, ''), '0001')) as bf_group,
                                min(m.email) as email, min(m.news_yn) as news_yn,
                                min(op.productname) as productname,
                                sum( ((op.price+op.option_price) * op.option_quantity) - op.coupon_price - op.use_point + op.deli_price) as act
                        FROM    tblorderinfo o
                        JOIN    tblorderproduct op on o.ordercode = op.ordercode
                        join
						(
							SELECT *
							FROM tblmember
							WHERE group_code not in ('0001','0003','0005','')
							AND id='".$id."'
						) m on o.id = m.id
                        WHERE   1=1
                        AND     o.regdt >= '".$from_dt2."0101000000' and o.regdt <= '".$from_dt2."1231235959'
                        AND     o.oi_step1 in ('1', '2', '3', '4')
                        and op.op_step ='4'
                        GROUP BY o.ordercode
                    ) v
                    GROUP BY name, id
            ) z
            ORDER BY m_grade desc";

		$result2 = pmysql_query($sql2);
		while($row2= pmysql_fetch_object($result2)) {
			$id2=$row2->id;
			$af_group2=$row2->af_group;
			$act2=$row2->act;
		}

		$act = $act + $act2;
		if($act >= 3000000 && $act2 >=3000000){
			$af_group = '0007';
		}else{
			$af_group = '0006';
		}
	}
	echo "<br>id = ".$id." / ";
	echo "<br>id2 = ".$id2." / ";
	echo "<br>id = ".$row->id." / ";
    echo "name = ".$row->name." / ";
    echo "news_yn = ".$row->news_yn." / "; 
    echo "date = ".$row->join_date." / ";  
    echo "bf_group = ".$row->bf_group." / ";
    echo "af_group = ".$af_group." / ";
    echo "act = ".$act." / ";
	echo "act2 = ".$act2." / ";
    echo "coupon_group = ".$af_group." / ";
    echo "email = ".$row->email." / ";
    echo "ismail = ".ismail( $row->email )." / ";
	echo "ordercode = ".print_r($arr_ordercode)." / <br>";
    echo "count_ordercode = ".$check_cnt." / ";

    // ========================================================================
    // 등급 쿠폰
    // ========================================================================
/*
		if($af_group != 0001 && ($row->bf_group != $af_group)){
			$coupon_sql = "SELECT coupon_name,coupon_code FROM tblcouponinfo where sel_group ='".$row->af_group."' order by date asc";
			$coupon_result = pmysql_query($coupon_sql);
			$i=0;
			while($coupon_row = pmysql_fetch_object($coupon_result)) {
				$coupon_code[$i] = $coupon_row->coupon_code;
				if($af_group==0003){
					echo "<br>coupon_code = ".$coupon_code[$i]." / ";
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
				}else if($af_group==0005){
					echo "<br>coupon_code = ".$coupon_code[$i]." / ";
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
				}else if($af_group==0006){
					echo "<br>coupon_code = ".$coupon_code[$i]." / ";
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
				}else if($af_group==0007){
					echo "<br>coupon_code = ".$coupon_code[$i]." / ";
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
					insert_coupon($coupon_code[$i], $row->id, $date_start, $date_end );
				}
				$i++;
			}
		}
*/
    // =========================================================================
    // 등급 갱신 및 히스토리 저장
    // =========================================================================
    $u_query = "update tblmember set group_code = '".$af_group."' where id='".$row->id."'";
    echo "\n";
	echo $u_query;
    echo "\n";
	pmysql_query( $u_query, get_db_conn() );

    $h_query = "insert into tblmemberchange
                (mem_id, before_group, after_group, accrue_price, change_date)
                values
                ('".$row->id."', '".$row->bf_group."', '".$af_group."', '".$act."', '".date("Y-m-d")."')
                ";
    echo $h_query;
	pmysql_query( $h_query, get_db_conn() );

/*
    //회원등급 상승시 sms 발송 20170913 bshan
    if($row->bf_group < $af_group){
        // SMS 수신을 동의한 경우에만 발송
        if( $row->news_yn == "Y" || $row->news_yn == "S" ) {
            //SMS 발송
            sms_autosend( 'mem_grade_up', $row->id, '', '' );
            //SMS 관리자 발송
            sms_autosend( 'admin_birth', $row->id, '', '' );
        }
    }
*/
    echo "\n";

	if($af_group == "0001"){
		$af_group_name = "WELCOME";
		$next_grade_price='999999';
	}else if($af_group == "0003"){ 
		$af_group_name = "BRONZE";
		$next_grade_price='1000000';
	}else if($af_group == "0005"){
		$af_group_name = "SILVER";
		$next_grade_price='2000000';
	}else if($af_group == "0006"){
		$af_group_name = "GOLD";
		$next_grade_price='5000000';
	}else if($af_group == "0007"){
		$af_group_name = "DIAMOND";
		$next_grade_price='0';
	}

	echo $mb_grade."<br>";
	echo $next_grade_price."<br>";
    echo "\n";

	$period = "전년도 기준";

    // 메일 발송 (welcome등급 메일 발송 안함.)
    if($af_group != '0001') {
       //SendBIrthDayMail($_data->shopname, $shopurl, $_data->design_mail, $_data->info_email, $row->id, $row->name, $row->bf_group, $af_group_name, $row->email, $row->news_yn, $row->act, $next_grade_price, $check_cnt,$period);
    }

}

pmysql_free_result($result);
echo "==============================================================================================================\n";

function SendBIrthDayMail($shopname, $shopurl, $mail_type, $info_email, $id, $name, $bf_group, $af_group, $email, $news_yn, $act, $n_price, $cnt, $period) {

    global $grade;

    // ========================================================================
    // 메일 제목
    // ========================================================================
    $subject_raw    =  "{$shopname} 회원등급 변경 안내메일입니다.";
    if ( ord( $subject_raw ) ) {
        $subject = "=?utf-8?b?".base64_encode( $subject_raw )."?=";
    }

    // ========================================================================
    // 메일 Form
    // ========================================================================
    $buffer     = "";
    $mailBody   = "";
    if(file_exists(DirPath.TempletDir."mail/grade_".$mail_type.".php")) {
        ob_start();
        include(DirPath.TempletDir."mail/grade_".$mail_type.".php");
        $buffer = ob_get_contents();
        ob_end_clean();
        $mailBody = $buffer;
    }

    // ========================================================================
    // 메일 준비
    // ========================================================================
    $header     = '';
    $body       = $mailBody;

    $pattern_arr = array(
        "[SHOP]"            => $shopname,               // 샵 명칭
        "[MB_GRADE]"       => $af_group,  // 이전 등급
        "[MEMBER_NAME]"     => $name,                   // 이름
        "[ACT]"     => number_format($act),                   // 이름
        "[CNT]"     => $cnt,                   // 이름
        "[PERIOD]"     => $period,                   // 기간문구
        "[URL]"             => $shopurl,                // shop url
        "[CURDATE]"         => date("Y-m-d H:i:s"),
    );

    // ========================================================================
    // 메일 내용 작성
    // ========================================================================
    if( ord( $body ) ) {
        unset( $pattern );
        unset( $replace );
        foreach( $pattern_arr as $k=>$v ){
            $pattern[] = $k;
            $replace[] = $v;
        }
        $body = str_replace( $pattern, $replace, $body );

        if ( ord( $shopname ) ) {
            $mailshopname = "=?utf-8?b?".base64_encode( $shopname )."?=";
        }

        $header = getMailHeader( $mailshopname, $info_email );
    }

    if( ismail( $email ) ) {
        // 이메일 주소가 있고 이메일 수신을 동의한 경우에만 발송
        if ( $email != "" && ( $news_yn == "Y" || $news_yn == "M" ) ) {

            sendmail( $email, $subject, $body, $header );
            echo "[메일발송] 제목 => " . $subject_raw . ", 이름 => " . $name . ", 이메일 => " . $email . ", ID => ".$id.", 등급 => ".$af_group."\n";
        }
    }
}
?>