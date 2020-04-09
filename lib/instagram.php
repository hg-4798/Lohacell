<?php
/*
* admin/sns_list.php
* access_token 을 받아올 때의 return_uri 는 Instagram에 등록되어있어야 한다.
*/
$code = $_GET['code'];
if($code){
$insta = new Instagram;
	//$s_check	= $_COOKIE[insta_brand];

	$data = array("code"=>$code, "redirect_uri"=>Instagram::$redirect_uri);
	$insta->api = "oauth/access_token";
	$res = $insta->get_json($data);
	if($res){
		$token = $res->access_token;
		//setcookie("insta_token", $token, 0, "/".RootPath, getCookieDomain());
		setcookie("insta_token", $token, time()+31536000);
		echo "<script>window.opener.location='/admin/sns_list.php?mode=search';self.close();</script>";
		exit;
	}
}

/**
* Instagram API
* Sendbox 모드로 사용하며, Sendbox user로 등록된 사용자만 조회할 수 있다.
*
* User : Jayjun (개발서버)
* client_id :
* client_secret :
* redirect_uri : http://www.dev-jayjun.ajashop.co.kr/admin/sns_list.php
*/
class Instagram {
	public $debug = false;
	public $domain = "https://api.instagram.com";
	public static $jayjun_client_id = "b5da706dc8f04f14a9dc77e83fc1fd62";
	public static $jayjun_client_secret = "b9958081c96c4570b983f369053c67f9";
	public static $redirect_uri = SHOPURL."/admin/sns_list.php";

	public $client_id = "b5da706dc8f04f14a9dc77e83fc1fd62";
	public $client_secret = "b9958081c96c4570b983f369053c67f9";
	public $grant_type = "authorization_code";
	public $method = 1; //0=GET, 1=POST

	function __construct($mode=0) {
		if($this->debug){
			echo "<h3>Debug</h3>\r\n";
			echo "<table style='width:800px'>\r\n";
		}
	}

	function close_table() {
		if($this->debug) echo "</table>\r\n";
	}

	public function debug($param, $subtitle="") {
		if($this->debug){
			$bt = debug_backtrace();
			$method = $bt[1]["class"]."::".$bt[1]["function"];
			if($subtitle) $method = "{$method}<br />→<strong>{$subtitle}</strong>";
			echo "<tr>\r\n";
			echo "<td>{$method}</td><td class='debug'><xmp>";
			print_r($param);
			echo "</xmp></td>\r\n";
			echo "</tr>\r\n";
		}
	}

	public function get_json($data){
		if($this->api=="oauth/access_token"){
			$data["client_id"]     = $this->client_id;
			$data["client_secret"] = $this->client_secret;
			$data["grant_type"]    = $this->grant_type;
		}
		foreach($data as $name => $value){
			$t_param[] = "{$name}={$value}";
		}
		$this->debug($this->api,"API");
		$this->debug($data,"Input DATA");
		//this->param = mb_convert_encoding(implode("&",$t_param), 'EUC-KR', 'UTF-8');
		$this->param = implode("&",$t_param);
		$this->url = "{$this->domain}/{$this->api}";
		if(!$this->method) $this->url .= "?".$this->param;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, $this->method);
		if($this->method) curl_setopt($ch, CURLOPT_POSTFIELDS, $this->param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('charset="utf-8"'));
		$result = curl_exec($ch);
		curl_close($ch);
		$this->debug($result,"Output");
		$this->close_table();
		return json_decode($result);
	}
}