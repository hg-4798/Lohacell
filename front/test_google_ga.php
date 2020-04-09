<?php
// header("Content-Type: text/html; charset=UTF-8");
// $Dir="../";
// include_once($Dir."lib/init.php");
// include_once($Dir."lib/lib.php");
// include_once($Dir."lib/shopdata.php");

?>
<html>
<head>
<title>test ga</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=UTF-8">
</head>
<body>
<SCRIPT LANGUAGE="JavaScript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	ga('create', 'UA-98275559-1', 'auto');			// UA-98275559-1 | UA-98413097-1
	ga('send', 'pageview');
	
	ga('require', 'ecommerce', 'ecommerce.js');
	ga('ecommerce:addTransaction', { 
	  'id': 'a10010101', 						// 시스템에서 생성된 주문번호. 필수. 
	  'affiliation': 'O', 						// 제휴사이름. 선택사항. 
	  'revenue': '1000', 						// 구매총액. 필수. 
	  'shipping': '2500', 						// 배송비. 선택사항. 
	  'tax': '0' 								// 세금. 선택사항.
	});
	ga('ecommerce:addItem', { 
	  'id': 'a10010101', 						// 시스템에서 생성된 주문번호. 필수. 
	  'name': 'test_name', 					// 제품명. 필수. 
	  'sku': 't000100010001', 				// SKU 또는 제품고유번호. 선택사항. 
	  'category': 'hello01', 				// 제품 분류. 
	  'price': '1000', 					// 제품 단가. 
	  'quantity': '1' 					// 제품 수량.
	});
	ga('ecommerce:send');
</SCRIPT>
</body>
</html>
