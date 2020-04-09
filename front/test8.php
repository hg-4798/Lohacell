<?php
$sqlPush = "select * from tblpushlist where no = '".$pushNum."'";
		$resultPush = pmysql_query($sqlPush, get_db_conn());
		$rowPush = pmysql_fetch_object($resultPush);

		$startDate = $rowPush->push_start_date;
		$endDate = $rowPush->push_end_date;
		$memSearchType = $rowPush->mem_search_type;

		if($memSearchType == 'a'){
			$sql = "select id, push_token, push_token_ios from tblmember where (push_token != '' or push_token_ios != '')";
			if($_SERVER['REMOTE_ADDR']=='218.234.32.4') $sql .= "and id='dong' ";
			$result = pmysql_query($sql,get_db_conn());
		}else{
			$searchsql[] = "1=1";
			$searchsql[] = "(b.push_token != '' or b.push_token_ios != '')";
			if($startDate && $endDate) {
				$date_start = str_replace("-","",$startDate)."000000";
				$date_end = str_replace("-","",$endDate)."235959";

				$searchsql[] = "ordercode >= '{$date_start}' AND ordercode <= '{$date_end}' ";
			}
			
			$sql = "
							select 
								a.id, b.push_token, b.push_token_ios
							from 
								tblorderinfo a JOIN 
								tblmember b on a.id = b.id 
							where 
								".implode(" AND ", $searchsql)."
							group by a.id, b.push_token, b.push_token_ios";
			$result = pmysql_query($sql,get_db_conn());
		}

		$arrMsgDb = $arrMsgDbIos = array();
		$pushCount1st = $pushCount2ns = -1;


		while ($row=pmysql_fetch_object($result)) {
			if($row->push_token){
				$pushCount2ns++;
				if($pushCount2ns%900 == 0){
					$pushCount2ns = 0;
					$pushCount1st++;
					$arrMsgDb[$pushCount1st][$pushCount2ns][token] = $row->push_token;
				}else{
					$arrMsgDb[$pushCount1st][$pushCount2ns][token] = $row->push_token;
				}
			}

			if($row->push_token_ios){
				$arrMsgDbIos[] = $row->push_token_ios;
			}
		}
		pmysql_free_result($result);


		//debug($arrMsgDb);
		$strNo = $pushNum;
		$strTitle = $rowPush->push_title;
		$strMsg = $rowPush->push_content;
		$strUrl = $rowPush->push_url;
		$strBigPicture = $rowPush->push_img;
		if(!$strBigPicture) $strBigPicture = "/";

		$totalSendCount = 0;




		if($os == "Android"){
			# 안드로이드의 경우 처리


			foreach($arrMsgDb as $tokenArrkey => $tokenArrVal){
				//debug($tokenArrVal);
				// 헤더 부분
				// AIzaSyC5qK5vntLcd17n3otSjulmkIoT1TkqgMQ
				// AIzaSyBNi68syLTcNEyduuw_mFe3vqjCZwzOzYI
				$headers = array(
						'Content-Type:application/json',
						'Authorization:key=AIzaSyCN3PAJs3LIAvwAmhJdwOYZbbIkkwnrAeE'
				);
			 
				// 푸시 내용, data 부분을 자유롭게 사용해 클라이언트에서 분기할 수 있음.
				$arr = array();
				$arr['data'] = array();

				foreach($tokenArrVal as $k => $v){
					//$arr['data']['message'] = '111::::푸시 테스트02::::푸시 내용 ABCD~::::http://dev3-franchise.synccommerce.co.kr/sale/edit';
					$arr['data']['message'] = $strNo."::::".$strTitle."::::".$strMsg."::::".$strUrl."::::".$strBigPicture;
					$arr['registration_ids'][$k] = $v['token'];
				}
				#exdebug($arr);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
				$response = curl_exec($ch);
				curl_close($ch);
			 
				// 푸시 전송 결과 반환.
				$obj = json_decode($response);
			 
				// 푸시 전송시 성공 수량 반환.
				$cnt = $obj->{"success"};
				if(!$cnt) $cnt = 0;
				$totalSendCount += $cnt;
			}




		}else{
			# 아이폰의 경우 처리
			$iOsMsg = "";
				 
			$apnsPort = 2195;
			 
			// dev
			//$apnsHost = 'gateway.sandbox.push.apple.com';
			//$apnsCert = $_SERVER['DOCUMENT_ROOT']."/apns_develop.pem";  
			 
			// production
			$apnsHost = 'gateway.push.apple.com';
			$apnsCert = $_SERVER['DOCUMENT_ROOT']."/apns.pem";  
			 
			//debug($arrMsgDbIos);
			foreach($arrMsgDbIos as $tokenArrVal){
				//$token = $v['token'];
				//$token = "7d6bf9217274c1817111e56715c6ba640d7e60d170be79c93c2c9ed0627c643e";
				$streamContext = stream_context_create();
				stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);

				$apns = null;
				$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);

				if ($apns) {
					$payload['aps'] = array('alert' => $strMsg, 'badge' => 1, 'sound' => 'default');
					$output = json_encode($payload);
					$token = pack('H*', str_replace(' ', '', $tokenArrVal));
					$apnsMessage = chr(0) . chr(0) . chr(32) . $token . chr(0) . chr(strlen($output)) . $output;
					$writeFlag = fwrite($apns, $apnsMessage);
					$totalSendCount++;


					# 전송 실패
					/*
					if(!$writeFlag){
						$errorResponse = @fread($apns, 6);
						if ($errorResponse != FALSE) {
							$iOsMsg .= '에러값 = [' . $errorResponse . ']   <br>';
						}
					}else{
						$totalSendCount++;
					}
					*/
				}else{
					# 전송 실패
				}
				fclose($apns);
			}
		}






		/*
		$arrMsg = array();
		//$arrMsg[0][token] = "APA91bFFHvzdRDdcXvDDPnmOG1vWeAa9XF7DunNSMGf4Uvlbm5w9PjO7ySLz2wi3eSm9TnrSFG6YE7KcG62_MXHowzVdkjJwh8igrzSteSLOk-P_mSrl95AsNbA3ujYvO74gk5quDrbu";
		$arrMsg[0][token] = "WEB_554cdf518aada";
		$arrMsg[1][token] = "WEB_55141da86be63";
		debug($arrMsg);
		*/

		$push_insert_sql = "UPDATE tblpushlist SET push_count = '".$totalSendCount."' WHERE no = '".$pushNum."'";
		pmysql_query($push_insert_sql,get_db_conn());

		return $totalSendCount;