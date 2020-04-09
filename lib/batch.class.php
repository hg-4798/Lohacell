<?php
class BATCH extends COMMON {

	public function log_file($log_content, $log_path='') {
		$dir = DOC_ROOT.'/log/batch/'.date('Ym'); //dirname(.DIRECTORY_SEPARATOR.$log_path);

		if(!is_dir($dir)) {
			mkdir($dir,0777);
		}

		if(!$log_path) $log_path = date('d').".txt";
		if(!($fp = fopen($dir.DIRECTORY_SEPARATOR.$log_path, "a+"))) return 0;

		ob_start();
		echo "\n\n--------------------------------\n";
		echo "DATE :".date('Y-m-d H:i:s')."\n";
		echo "--------------------------------\n";
		print_r($log_content);
		$ob_msg = ob_get_contents();
		ob_clean();

		if(fwrite($fp, " ".$ob_msg."\n") === FALSE) {
			fclose($fp);
			return 0;
		}
		fclose($fp);
		return 1;
	}

	public function createSumViewYear($time = "-1 year"){
		$timestamp = strtotime($time);
		$create_sql = "
            CREATE OR REPLACE VIEW tblmembersumprice_view_year AS
            SELECT m.id, sum(op.price_end) as sum_year_price
            FROM tblmember m
            LEFT JOIN tblorder_basic AS ob ON
                (ob.member_id = m.id)
            LEFT JOIN tblorder_product op ON ob.order_num = op.order_num
            LEFT JOIN tblproduct AS p ON
                ((op.productcode = p.productcode AND op.option_type='option') OR (op.option_code = p.productcode AND op.option_type='product'))
            WHERE op.order_status>0 
            AND to_char(op.date_order_6, 'YYYY-MM') >= '".date('Y-m',$timestamp)."'
            AND concat(op.order_status, op.cs_type, op.cs_status) IN('600')
            GROUP BY m.id
            ";
		$rs = $this->adodb->Execute($create_sql);
		return $rs;
	}

	//최근 1년 누적금액에 대하여 등급 변경 배치
	public function updateGrade(){
		//1년 누적 view테이블 생성
		$rs = $this->createSumViewYear();
		if(!$rs){
			echo "뷰테이블 생성 실패";
			return false;
		}else {
			$base_sql = "
            SELECT *, (SELECT group_code FROM tblmembergroup WHERE gradeinfo.sum_year_price between group_ap_s AND group_ap_e LIMIT 1) as after_grade
            FROM (
                    SELECT m.id, coalesce(msvy.sum_year_price,0) as sum_year_price, m.group_code as before_grade
                    FROM tblmember m 
                    LEFT JOIN tblmembersumprice_view_year msvy on m.id = msvy.id
                    JOIN tblmembergroup mg ON m.group_code = mg.group_code
            ) AS gradeinfo
            ";
			$base_rs = $this->adodb->Execute($base_sql);
			$update_member_list_arr = array();
			while ($member_row = $base_rs->FetchRow()) {
				if ($member_row['before_grade'] != $member_row['after_grade']) {
					$update_member_list_arr[] = $member_row;
				}
			}
			//print_r($base_sql);

			foreach ($update_member_list_arr as $key => $val) {
				//echo "<br>UPDATE tblmember SET group_code = '".$val['after_grade']."' WHERE id = '".$val['id']."'";
				$update_rs = $this->adodb->Execute("UPDATE tblmember SET group_code = '" . $val['after_grade'] . "' WHERE id = '" . $val['id'] . "'"); //회원 등급 업데이트 처리
				if ($update_rs) {
					echo "회원 : " . $val['id'] . " 등급 :" . $val['after_grade'] . " update 성공 \n";
				} else {
					echo "회원 : " . $val['id'] . " 등급 :" . $val['after_grade'] . " update 실패 \n";
				}
			}
		}
	}
}
?>