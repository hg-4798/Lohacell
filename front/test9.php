<?php 
		$deviceToken[] = "af5c5cfe2d9d13f7b6e2b58bb37755e789edf1932a8db4e437723ce2a2e8cd99"; // 디바이스토큰ID
		$deviceToken[] = "af5c5cfe2d9d13f7b6e2b58bb37755e789edf1932a8db4e437723ce2a2e8cd99"; // 디바이스토큰ID1537df4a44e4c4813eb2ce12abc3d1c18e92450e96fe3657fef2551f8a53ebe7
		//$deviceToken[] = "1537df4a44e4c4813eb2ce12abc3d1c18e92450e96fe3657fef2551f8a53ebe7";	d80328c70b52a12f3d63b157ef4e19396c0a503c7b3322701dd43f721f3e5db4	
		$message = "대리님 받으셨나요?"; // 전송할 메시지  'Message Received Test'
		$deviceToken[] = "26417d4ea2490aed05a5610d037f6a81a2d9003eac560e51ed4ac0e0e8906adf"; 

		$apnsHost = 'gateway.push.apple.com';
		$apnsCert = $_SERVER['DOCUMENT_ROOT']."/apns.pem";  

		$apnsPort = 2195;

		$payload = array('aps' => array('alert' => $message, 'badge' => 1, 'sound' => 'default'));
		$payload = json_encode($payload);

		$iOsMsg = "";
		foreach($deviceToken as $tokenData){
			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
			//stream_context_set_option($ctx, 'ssl', 'passphrase', "123456");

			if($tokenData){
				$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
				
				if($apns)
				{ 
					$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $tokenData)) . chr(0) . chr(strlen($payload)) . $payload;
					$writeFlag = fwrite($apns, $apnsMessage,strlen($apnsMessage));
					echo $writeFlag."</br>";
				}else{
					$errorResponse = @fread($apns, 6);
					$iOsMsg .= '에러값 = [' . $errorResponse . '/'.$error . '/'. $errorString . ']   <br>';
				}
				@socket_close($apns);
				fclose($apns);
			}
		}
	print_r($iOsMsg);