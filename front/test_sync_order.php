<? 
 $Dir="../"; 
 include_once($Dir."lib/init.php"); 
 include_once($Dir."lib/lib.php"); 
 $ordercode = $_GET['ordercode']; 
//echo $ordercode;

 $Sync = new Sync(); 
 $arrayDatax = array( 'ordercode'=>$ordercode ); 
 $srtn = $Sync->OrderInsert($arrayDatax);
 echo $srtn;

?>
