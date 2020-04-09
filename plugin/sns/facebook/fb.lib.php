<?

	// library �ε�, ���� ���� ��
	#include_once("/plugin/sns/facebook/src/facebookoauth.php");
	include_once(dirname(__FILE__)."/src/facebookoauth.php");


	function connectFacebookUser($consumer_key, $consumer_secret){
		$connection = new FacebookOAuth($consumer_key, $consumer_secret);
		$access_token = $connection->getAccessToken($_REQUEST['code']);

		$token = $access_token['oauth_token'];

		$connection = new FacebookOAuth($consumer_key, $consumer_secret, $token);

		$parameters['fields'] = "id,name,email,gender,birthday,link";
		# $parameters['access_token'] = $token;

		$user = $connection->get('/me', $parameters);

		return $user;
	}
?>