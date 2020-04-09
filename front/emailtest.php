<?php 
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//SendOrderMail("test","http://nexolve.ajashop.co.kr","002","test@nexolve.ajashop.com","2014082018353201204AX","N","Y","");


//SendOrderMail("test","http://nexolve.ajashop.co.kr","TEM_001","test@nexolve.ajashop.com","2014082018353201204AX","N","Y","");


//SendJoinMail("test", "http://nexolve.ajashop.co.kr", "TEM_001", $join_msg, "test@nexolve.ajashop.com", "redsaurs@duometis.co.kr", "테스트", "redsaurs");

//SendPassMail("test", "http://nexolve.ajashop.co.kr", "TEM_001", "test@nexolve.ajashop.com", "redsaurs@duometis.co.kr", "테스트", "redsaurs", "34514DFD");

SendBankMail("test", "http://nexolve.ajashop.co.kr", "TEM_001", "test@nexolve.ajashop.com", "redsaurs@duometis.co.kr", "2014082018353201204AX");

?>