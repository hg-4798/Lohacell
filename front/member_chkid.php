<?php
session_start();
/**
 *
 * 기존에 회원가입이 되어 있는지 안되어 있는지 체크하는 페이지
 * member_agree.php의 ifram에서 실행하는 페이지
 * 본래 member_join.php에서 체크해야 하지만 세이브힐즈의 경우 member_agree.php에서
 * 가입 결과를 레이어 팝업으로 띄워야 하기에 여기에서 체크한 다음 그 결과로
 * ipin_chk()를 호출함
 *
 *
 *
 * 아이핀 인증의 경우 가입 확인 여부를 확인할 수 있지만
 * 핸드폰 인증의 경우 가입 여부를 확인 할 수 없다.
 * 따라서 핸드폰 인증의 경우 본인 인증이 되면
 * 무조건 회원가입 폼으로 넘어간다.
 *
 */


$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

#####실명인증 결과에 따른 분기


$CertificationData = pmysql_fetch_object(pmysql_query("select realname_id, realname_password, realname_check, realname_adult_check, ipin_id, ipin_password, ipin_check, ipin_adult_check from tblshopinfo"));

if($CertificationData->realname_check || $CertificationData->ipin_check){



    if($_SESSION[ipin][dupinfo]){
        #####아이핀 인증의 경우
        $member_out=pmysql_fetch_object(pmysql_query("select count(*) as memberout_cnt from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}' and member_out ='Y' "));
        if($member_out->memberout_cnt > 0){
            ?>
            <script>
                parent.certi_return('3','','','','');
            </script>
            <?php
            exit;
        }

        $check_ipin=pmysql_fetch_object(pmysql_query("select count(id) as check_id from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}'"));
        $check_ipin_data = pmysql_fetch_object(pmysql_query("select id,name,date,member_out from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}'"));
        $check_full_id = $check_ipin_data->id;
        $check_ipin_data->id = substr($check_ipin_data->id,0,-4)."****";

        if($check_ipin->check_id){

            if($_SESSION[sns][sns_login_id]){

                 $check_sns=pmysql_fetch_object(pmysql_query("select count(id) as check_sns from tblmember_sns where id='{$check_full_id}'"));
                list($sns_id,$date,$sns_type)=pmysql_fetch_array(pmysql_query("select id,date_insert,sns_type from tblmember_sns where id='{$check_full_id}'"));
                if($check_sns->check_sns > 0){

                    ?>
                    <script>
                        parent.sns_certi_return('2','<?=$sns_type?>','<?=$date?>');
                        //parent.sns_certi_return('2','<?=$check_ipin_data->name?>','<?=$check_ipin_data->id?>','<?=$check_full_id ?>','<?=$check_ipin_data->date?>');
                    </script>
                    <?php
                    exit;
                } else if($check_ipin->member_out == 'S') {
                    ?>
                    <script>
                        parent.certi_return('1','','','','');
                    </script>
                    <?
                }else {

                    if ($_SESSION[sns][sns_login_id]) {
                        if ($_SESSION[sns][sns_type] == "kt") {
                            $sns_type = "KAKAO";
                        } else if ($_SESSION[sns][sns_type] == "nv") {
                            $sns_type = "NAVER";
                        }else if ($_SESSION[sns][sns_type] == "fb") {
                            $sns_type = "FACEBOOK";
                        }
                        $sns_sql = "UPDATE tblmember SET sns_type = '{$_SESSION[sns][sns_login_id]}' WHERE id = '{$check_full_id}'";
                        pmysql_query($sns_sql, get_db_conn());

                        $sns_insert = "INSERT INTO tblmember_sns(id,name,sns_email,date_insert,sns_type) VALUES (
							'{$check_full_id}',
							'{$check_ipin_data->name}',
							'{$_SESSION[sns][sns_email]}',
							'{NOW}',
							'{$sns_type}') ";

                        //echo $sns_naver;exit;
                        pmysql_query($sns_insert, get_db_conn());
                        ?>
                        <script>
                            parent.sns_certi_return('0','<?=$sns_type?>','<?=$date?>','<?=$check_full_id ?>','<?=$check_ipin_data->date?>');

                        </script>
                        <?php
                        exit;
                    }
                }
            }

            ?>
            <script>
                parent.certi_return('0','<?=$check_ipin_data->name?>','<?=$check_ipin_data->id?>','<?=$check_full_id ?>','<?=$check_ipin_data->date?>');
            </script>
            <?php

        }else{
            ?>
            <script>
                parent.certi_return('1','','','','');
            </script>
            <?php

        }
    }else if($_SESSION[ipin][name]){
        #####핸드폰 인증의 경우
        ?>2
        <script>
            parent.certi_return('1','','','');
        </script>
        <?php
    }
}

?>