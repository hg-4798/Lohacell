<?php
				$headers = array(
						'Content-Type:application/json',
						'Authorization:key=AIzaSyCN3PAJs3LIAvwAmhJdwOYZbbIkkwnrAeE'
				);
			 
				// Ǫ�� ����, data �κ��� �����Ӱ� ����� Ŭ���̾�Ʈ���� �б��� �� ����.
				$arr = array();
				$arr['data'] = array();

				$strMsg = "�����, ���� �Ϸ� �Ǽ���";

				$k = 0;
				//$arr['data']['message'] = '111::::Ǫ�� �׽�Ʈ02::::Ǫ�� ���� ABCD~::::http://dev3-franchise.synccommerce.co.kr/sale/edit';
				$arr['data']['message'] = $strNo."::::".$strTitle."::::".$strMsg."::::".$strUrl."::::".$strBigPicture;
				//$arr['registration_ids'][] = "cTE8vXz86FE:APA91bE8e6k12fp7Cp2WYC0JRJscVBfWuEo2rlxrulLsi8A-VtRKm2niJYKHHrvqA3php1GGrS4dK0yFdBxqNwBLgT4jyRTycTLYWZ6QnKX3Xb04_nH54wlA18Rvj61ELOtCDNgyF6Z";
				$arr['registration_ids'][] = "eF4Z8q2HsV4:APA91bGAdKViIrWMO5JSNI0kTm-9S0zv6L5W3llJcuaEGdLSOVzHaCqaFvHULvL7W06QeW1f-UbBwX0h5X-mq5uA8F1zUeO_vzCNe5oTDLC32LQsNkIFljLRfQl3F5D93Vm8mf-dDdTk";
				
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
			 
				// Ǫ�� ���� ��� ��ȯ.
				$obj = json_decode($response);
			 
				// Ǫ�� ���۽� ���� ���� ��ȯ.
				$cnt = $obj->{"success"};
				if(!$cnt) $cnt = 0;
				$totalSendCount += $cnt;

				print_r($obj);