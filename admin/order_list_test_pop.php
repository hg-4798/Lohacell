<?php 
Header('Content-Type: text/html; charset=utf-8');
$Dir = "../";

include_once($Dir."/lib/init.php");
include_once($Dir."/lib/lib.php");
include_once($Dir."/lib/order.class.php");

$_POST['ordercode'];
$_POST['productcode'];
$count = $_POST['quantity'];

$sql="SELECT product_serial FROM tblorderproduct where ordercode ='".$_POST['ordercode']."' and productcode='".$_POST['productcode']."'" ;
$result=pmysql_query($sql,get_db_conn());
$row=pmysql_fetch_array($result); 
$serial_arr = explode("|",$row[0]);
?>

<html>
<head><meta http-equiv="CONTENT-TYPE" content="text/html; charset=utf-8"></head>
<body>
<form action="order_chg_deli_indb_test.php?ordcode=<?=$_POST['ordercode']?>"  method=POST > 
	<input type=hidden value="<?=$_POST['ordercode']?>"		name=ordercode  readonly=readonly></input>
	<input type=hidden value="<?=$_POST['productcode']?>"	name=productcode  readonly=readonly></input>

<table>
	<tr>
		<td style = 'text-align:center;'>주문 번호 </td>
		<td>  <?=$_POST['ordercode']?></td>
	</tr>
	<tr>
		<td style = 'text-align:center;'>상품 번호 </td>
		<td>  <?=$_POST['productcode']?></td>
	</tr>
	<?for($i=0 ; $i<$count ; $i++){?>
		<tr>
			 <td style = 'text-align:center;'>serial <?=($i+1)?> </td>
			 <td><input type=text value="<?=$serial_arr[$i]?>"  
			name="product_serial[<?=$_POST['ordercode']?>][<?=$_POST['productcode']?>][<?=$i?>]" ></input><p>
			</td>
		</tr>
	<?}?>
	<tr>
		<td colspan=2 style='text-align:center;'>
			<img src="images/btn_deliinfomodify.gif" onclick=submit() />
		</td>
	</tr>
</table>


</form>
</body>
</html>