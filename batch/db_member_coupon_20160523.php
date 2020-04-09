<?php
@set_time_limit(0);
header("Content-type: text/html; charset=utf-8");
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

echo "Start ".date("Y-m-d H:i:s")."<br>";
echo "<hr>";

// insert coupon
$coupon_arr = array( '16183418', '10939241', '23093725', '43093627' ); // 쿠폰 크릭 발급

//" [가정의달] MAN 10% 할인쿠폰
$cou_16183418 = array( 
        'cutegirlram',
        'snapyn',
        'hoesang2',
        'gurong33'
    );
// [가정의달] WOMAN 10% 할인쿠폰
$cou_10939241 = array(
        'mathis',
        'vivid',
        'chejare',
        'kmjgirls',
        'khee1257',
        'jeanjacques',
        'dusdh82',
        'akahwang',
        'lingjuli',
        'js1919',
        'kuk7712',
        'qhr122604',
        'cejung0505',
        'gogojeongmin',
        'hwangsama33',
        'antique',
        'mind0519',
        'tlswotnr7',
        'rompbb',
        'suezla',
        'yigeee'
    );
//"[가정의달] LIFE 10% 할인쿠폰"

$cou_23093725 = array(
        'babara85',
        'heay22',
        'shinsun87',
        'dmsal618',
        'mj30201',
        'kangfield',
        'ae2683',
        'ses4930',
        'yunhwa2267',
        'dong',
        'ikazeus',
        'kkyssa9355',
        'sunmi0515',
        'mhp83',
        'd0329y'
    );
// "[가정의달] KIDS 10% 할인쿠폰"

$cou_43093627 = array(
        'qsu02p',
        'ju0713'
);

$member_arr = array( $cou_16183418, $cou_10939241, $cou_23093725, $cou_43093627 );
$total_coupon = 0;
BeginTrans();
for( $i = 0; $i < count( $coupon_arr); $i++ ){
    $tmp_member = '';
    $sql        = '';
    $batch_text = '';
    if( count( $member_arr[$i] ) > 0 ){
        $tmp_member = implode( "','", $member_arr[$i] );
        /*
        $sql = "
              INSERT INTO tblcouponissue ( coupon_code, id, date_start, date_end, date ) 
              (
                SELECT coupon.coupon_code, in_member.id, coupon.date_start, coupon.date_end, to_char( now() , 'YYYYMMDDHH24' ) AS date
                  FROM
                  (
                    SELECT
                      id
                    FROM 
                      tblmember
                    WHERE
                      id NOT IN ('".$tmp_member."')
                  ) AS in_member,
                  (
                    SELECT coupon_code, 
                      (
                        CASE 
                          WHEN time_type = 'D' THEN date_start
                          WHEN time_type = 'P' THEN to_char( now() , 'YYYYMMDDHH24' )
                        END
                      ) AS date_start,
                      (
                        CASE 
                          WHEN time_type = 'D' THEN date_end
                          WHEN time_type = 'P' THEN 
                          (
                            CASE
                              WHEN to_char( ( now()::date + abs( date_start::int ) + 1 ) - interval '1 hour' , 'YYYYMMDDHH24' ) < date_end 
                                THEN to_char( ( now()::date + abs( date_start::int ) + 1 ) - interval '1 hour' , 'YYYYMMDDHH24' )
                              ELSE date_end
                            END
                          )
                          END
                      ) AS date_end
                    FROM tblcouponinfo info
                    WHERE coupon_code = '".$coupon_arr[$i]."' 
                  ) AS coupon
                ) RETURNING coupon_code, id, date_start, date_end, date 
        ";
        */
        # TEST용
        
        $sql = "
                SELECT coupon.coupon_code, in_member.id, coupon.date_start, coupon.date_end, to_char( now() , 'YYYYMMDDHH24' ) AS date
                  FROM
                  (
                    SELECT
                      id
                    FROM 
                      tblmember
                    WHERE
                      id NOT IN ('".$tmp_member."')
                  ) AS in_member,
                  (
                    SELECT coupon_code, 
                      (
                        CASE 
                          WHEN time_type = 'D' THEN date_start
                          WHEN time_type = 'P' THEN to_char( now() , 'YYYYMMDDHH24' )
                        END
                      ) AS date_start,
                      (
                        CASE 
                          WHEN time_type = 'D' THEN date_end
                          WHEN time_type = 'P' THEN 
                          (
                            CASE
                              WHEN to_char( ( now()::date + abs( date_start::int ) + 1 ) - interval '1 hour' , 'YYYYMMDDHH24' ) < date_end 
                                THEN to_char( ( now()::date + abs( date_start::int ) + 1 ) - interval '1 hour' , 'YYYYMMDDHH24' )
                              ELSE date_end
                            END
                          )
                          END
                      ) AS date_end
                    FROM tblcouponinfo info
                    WHERE coupon_code = '".$coupon_arr[$i]."' 
                  ) AS coupon
        ";
        
        echo "########################################################################################<br>";
        echo "Step 1"."<br>";
        echo "<hr>";
        echo "sql = <br>";
        exdebug( $sql );
        echo "<br>";
        echo "<hr>";
        $result = pmysql_query( $sql, get_db_conn() );
        if( $err = pmysql_error() ) {
            echo $err."<br>";
            RollbackTrans();
            exit;
        }
        echo "<hr>";
        echo "Step 2"."<br>";
        echo "<hr>";

        $coupon_rows = pmysql_num_rows( $result );
        echo "rows = ".$coupon_rows."<br>";
        $total_coupon += $coupon_rows;
        if( $coupon_rows > 0 ){
            # insert된 쿠폰수만큼 issue_no를 update 해준다
            $update_coupon_qry = "UPDATE tblcouponinfo SET issue_no = issue_no + ".$coupon_rows." ";
            $update_coupon_qry.= " ,issue_type = 'N' ";
            $update_coupon_qry.= "WHERE coupon_code = '".$coupon_arr[$i]."' ";
            //pmysql_query($update_coupon_qry, get_db_conn());
            echo "sql = ".$update_coupon_qry."<br>";
            echo "<hr>";
            /*
            if( $err = pmysql_error() ) {
                echo $err."<br>";
                RollbackTrans();
                exit;
            }
            */

            $batch_text .= "## 20160523_coupon_insert === Coupon Code : ".$coupon_arr[$i]." === [ Date : ".date('Y-m-d H:i:s')." ] , ".$coupon_rows." ROW ## \n\n";
            $insertCnt = 0;
            while( $insert_row = pmysql_fetch_object( $result ) ){
                //coupon_code, id, date_start, date_end, date
                //$batch_text.=" Coupon Code : ".$insert_row->coupon_code." \n";
                //$batch_text.=" ID          : ".$insert_row->id." \n";
                //$batch_text.=" Date Start  : ".$insert_row->date_start." \n";
                //$batch_text.=" Date End    : ".$insert_row->date_end." \n";
                //$batch_text.=" Insert Dat  : ".$insert_row->date." \n";
                //$batch_text.="\n";
                if( $insertCnt == 0 ){
                    $batch_text.="------------------------------------------------\n";
                    $batch_text.=" Coupon Code : ".$insert_row->coupon_code." \n";
                    $batch_text.=" Date Start  : ".$insert_row->date_start." \n";
                    $batch_text.=" Date End    : ".$insert_row->date_end." \n";
                    $batch_text.=" Insert Dat  : ".$insert_row->date." \n";
                    $batch_text.="-------------------------------------------------\n\n";
                    $batch_text.="#ID => \n";
                }
                $batch_text.= " '".$insert_row->id."', \n";
                $insertCnt++;
            }
            $batch_text.="\n";
            pmysql_free_result( $result );
            $batch_text.= "## ////////////////////////////////////////////////////////////////////////////////////////// ##\n\n";
            echo "INSERT DATA "."<br>";   
            echo "<hr>";
            exdebug( $batch_text);
            echo "<br>";
            echo "<hr>";
        }

    }
}
# 임시 롤백
RollbackTrans();
//CommitTrans();
# 파일 로그를 남긴다
/*
$log_folder = DirPath.DataDir."backup/deco_InsertCoupon_".date("Ym");
if( !is_dir( $log_folder ) ){
    mkdir( $log_folder, 0700 );
    chmod( $log_folder, 0777 );
}
$file = $log_folder."/deco_InsertCoupon_".date("Ymd").".txt";
if( !is_file( $file ) ){
    $f = fopen( $file, "a+" );
    fclose( $f );
    chmod( $file, 0777 );
}
file_put_contents( $file, $batch_text, FILE_APPEND );
*/
echo "Total : ".number_format( $total_coupon )."<br>";
echo "End ".date("Y-m-d H:i:s")."<br>";
exit;

?>