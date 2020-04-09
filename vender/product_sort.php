<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

//exdebug($_POST);
//exdebug($_GET);

$mode       = $_POST["mode"];
$listnum    = $_POST["listnum"]?$_POST["listnum"]:'20';
$disptype   = $_POST["disptype"];

$imagepath=$Dir.DataDir."shopimages/product/";

list($bridx) = pmysql_fetch("select bridx from tblproductbrand where vender = '".$_VenderInfo->getVidx()."'");
//exdebug($bridx);

if ($mode=="sequence") {

	$productcode = $_POST['productcode'];
	$cnt = count($productcode);

	for($i=0;$i<$cnt;$i++){

		if(strpos($type,'T')===FALSE) {
			$sql = "UPDATE tblbrandproduct SET  ";
			$sql.= " start_no=".$i." ";
			$sql.= "WHERE bridx = $bridx ";
            $sql.= "AND productcode='{$productcode[$i]}' ";
		} 
		//exdebug($sql);
		pmysql_query($sql,get_db_conn());
	}

    echo "<html></head><body onload=\"alert('상품순서 변경이 완료되었습니다.');\"></body></html>";
} 

include("header.php"); 
?>
<script type="text/javascript" src="lib.js.php"></script>
<!-- <script type="text/javascript" src="calendar.js.php"></script> -->
<script src="../js/jquery-1.12.1.min.js" type="text/javascript"></script>
<script language="JavaScript">

var iciRow, preRow;
var objArray = new Array();
var objidxArray = new Array();

$(document).ready(function(){
	$(".spoitClass").click(function(){
		if ($(this).hasClass('selected') === true) {			
			$(this).removeClass('selected');  
			spoit(this, 'non');
			//console.log("non");
		} else if ($(this).hasClass('selected') === false) {			
			$(this).addClass('selected');
			//console.log("sel");
			spoit(this, 'sel');
		}
	});
});

function spoit(obj, chk){
	iciRow = obj;
	iciHighlight(chk);
}

function array_sort(){
	objArray = new Array();
	objidxArray = new Array();
	$("#spoitTable .selected").each(function(index) {
		objArray.push(this);
		objidxArray.push($(".spoitClass").index(this));
	});
}

function iciHighlight(chk){
	if (chk == 'non'){
		iciRow.style.backgroundColor = "";
	}else{
		iciRow.style.backgroundColor = "#FFF4E6";
	}
	array_sort();	
}

function moveTree1(idx){
	if (objArray.length > 0) {	
		var idx			= 0;
		var chkFirst	= objidxArray[idx];
		if (chkFirst > 0) {
			for(var k = 0; k < objArray.length; k++){
				$(objArray[k]).insertBefore($(objArray[k]).prev());
			}
			array_sort();
		}
	}
}
function moveTree2(idx){	
    //console.log(objArray);
	if (objArray.length > 0) {	
		var idx		= objArray.length - 1;
		var chkEnd= objidxArray[idx];
		if ((chkEnd+1) < $(".spoitClass").length) {
			objidxArray = new Array();
			for(var k = objArray.length-1; k >= 0; k--){
				$(objArray[k]).insertAfter($(objArray[k]).next());
				objidxArray.push($(".spoitClass").index(objArray[k]));
			}
			array_sort();
		}
	}
}


$(function() {
	$('body').keydown(function( event ) {
        //console.log(event.keyCode);
		event.preventDefault();
		$("body").trigger('focus');
		if (iciRow==null) return;
		switch (event.keyCode){
			case 38: moveTree1(-1); break;
			case 40: moveTree2(1); break;
		}
		return false;
	});
});

function SearchPrd() {
	document.sForm.submit();
}

function move_save()
{
	if (!confirm("저장하시겠습니까?")) return;

		document.sForm.mode.value = "sequence";
		document.sForm.submit();
}

</script>

<table border=0 cellpadding=0 cellspacing=0 width=1480 style="table-layout:fixed">
<col width=175></col>
<col width=5></col>
<col width=1300></col>
<tr>
	<td width=175 valign=top nowrap><? include ("menu.php"); ?></td>
	<td width=5 nowrap></td>
	<td valign=top>

	<table width="100%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:3px solid #EEEEEE" bgcolor="#ffffff">
		<tr>
			<td style="padding:10">
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=165></col>
				<col width=></col>
				<tr>
					<td height=29 align=center background="images/tab_menubg.gif">
					<FONT COLOR="#ffffff"><B>브랜드관 상품진열순서<B></FONT>
					</td>
					<td></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height=2 bgcolor=red></td></tr>
			<tr>
				<td bgcolor=#FBF5F7>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=10></col>
				<col width=></col>
				<col width=10></col>
				<tr>
					<td colspan=3 style="padding:15,15,5,15">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td style="padding-bottom:5"><img src="images/icon_boxdot.gif" border=0 align=absmiddle> <B>브랜드관 상품진열순서</B></td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 브랜드에 등록된 상품의 진열 순서를 변경할 수 있습니다.</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td><img src="images/tab_boxleft.gif" border=0></td>
					<td></td>
					<td><img src="images/tab_boxright.gif" border=0></td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- 처리할 본문 위치 시작 -->
			<tr><td height=0></td></tr>
			<tr>
				<td style="padding:15">
				
                <form name="sForm" method="post">
                <!-- <input type="hidden" name="listnum" value="<?=$listnum?>"> -->
                <input type="hidden" name="mode">

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td valign=top bgcolor=D4D4D4 style=padding:1>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td valign=top bgcolor=F0F0F0 style=padding:10>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr>
							<td>							
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<!-- <col width=60></col>
							<col width=></col> -->
							<tr>
								<td>&nbsp;<U>진열여부</U>&nbsp;
								<select name=disptype  style="font-size:8pt">
								<option value="">전체</option>
								<option value="Y" <?if($disptype=="Y")echo"selected";?>>진열</option>
								<option value="N" <?if($disptype=="N")echo"selected";?>>미진열</option>
								</select>
								&nbsp;&nbsp;&nbsp;
								<A HREF="javascript:SearchPrd()"><img src=images/btn_inquery03.gif border=0 align=absmiddle></A>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						<!-- </form> -->

						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<col width=150></col>
					<col width=></col>
					<col width=100></col>
					<tr>
						<td align=right valign=top>
						<select name="listnum" onchange="javascript:this.form.submit();">
							<option value="20" <?if($listnum==20)echo "selected";?>>20개씩 보기</option>
							<option value="40" <?if($listnum==40)echo "selected";?>>40개씩 보기</option>
							<option value="60" <?if($listnum==60)echo "selected";?>>60개씩 보기</option>
							<option value="80" <?if($listnum==80)echo "selected";?>>80개씩 보기</option>
							<option value="100" <?if($listnum==100)echo "selected";?>>100개씩 보기</option>
							<option value="200" <?if($listnum==200)echo "selected";?>>200개씩 보기</option>
							<option value="300" <?if($listnum==300)echo "selected";?>>300개씩 보기</option>
							<option value="400" <?if($listnum==400)echo "selected";?>>400개씩 보기</option>
							<option value="500" <?if($listnum==500)echo "selected";?>>500개씩 보기</option>
							<option value="100000" <?if($listnum==100000)echo "selected";?>>전체</option>
						</select>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=3></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td bgcolor=E7E7E7>


					<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed" id='spoitTable'>
					<colgroup>
                    <col width=40></col>
                    <col width=40></col>
                    <col width=></col>
                    <col width=70></col>
                    <col width=70></col>
                    <col width=70></col>
                    </colgroup>

					<!-- <form name=form2 method=post> -->

					<tr height=35 align=center bgcolor=F5F5F5>
						<td align=center><B>번호</B></td>
						<td align=center><B>이미지</B></td>
						<td align=center><B>상품명</B></td>
						<td align=center><B>판매가격</B></td>
						<td align=center><B>수량</B></td>
						<td align=center><B>상태</B></td>
					</tr>
<?php
					$colspan=12;
					$cnt=0;

					if($disptype) $qry.="AND display='".$disptype."' ";

                    $qry.="AND a.bridx = $bridx ";

                    $sql = "SELECT a.productcode, b.productname, b.sellprice, b.quantity, b.reserve, b.reservetype, b.addcode, b.display, b.vender, b.tinyimage, b.date, b.modifydate, a.start_no ";
                    $sql.= "FROM 	tblbrandproduct a ";
                    $sql.= "JOIN	tblproduct b ON a.productcode = b.productcode ";
                    $sql.= $qry." ";
                    // 프론트 브랜드 상품리스트 기준으로 변경 2016-06-23 jhjeong
                    $sql.= "ORDER BY a.start_no asc, b.regdate desc, b.modifydate desc  ";
                    if($listnum) $sql.= "Limit ".$listnum." OFFSET 0 ";
                    $result = pmysql_query($sql,get_db_conn());
                    $cnt = @pmysql_num_rows($result);
                    //echo "sql = ".$sql."<br>";

                    if($cnt>0)
                    {
                        $j=0;
                        while($row=pmysql_fetch_object($result)) {

                            $tinyimage = getProductImage($imagepath, $row->tinyimage );
?>

                    <!--  -->
                    <tr id = "spoit<?=++$idxx?>" class = 'spoitClass' id2 = '<?=$idxx?>'>
                        <td align=center width=40 nowrap><font class=small1 color=444444><?=$idxx?></font></td>
                        <TD><img src="<?=$tinyimage?>" style="width:25px" border="1"></td>
                        <td><?=$row->productname.($row->selfcode?"-".$row->selfcode:"").($row->addcode?"-".$row->addcode:"")?>&nbsp;/ <?=$row->productcode?></td>
                        <TD style="text-align:center; padding-right:20px">
                            <span class="font_orange"><?=number_format($row->sellprice)?></span>
                        </TD>
                        <TD >
                        <?
                        if ($row->quantity=="999999999") echo "무제한";
                        else if ($row->quantity == "0") echo "<span class=\"font_orange\"><b>품절</b></span>";
                        else echo $row->quantity;
                        ?>
                        </TD>
                        <TD  style="text-align:center;">
                        <?=($row->display=="Y"?"<font color=\"#0000FF\">판매중</font>":"<font color=\"#FF4C00\">보류중</font>")?>
                        </td>
                        <input type=hidden name=productcode[] value="<?=$row->productcode?>">
                        <input type=hidden name=sort[] value="<?=$idxx?>">
                    </tr>
<? 
                        }
                    } 
?>

					<!-- </form> -->
					</table>

					</td>
				</tr>
				<tr><td height=10></td></tr>
				<tr>
                    <TD colspan="<?=$colspan?>" align=center>* 순서변경은 변경을 원하는 상품을 선택 후 키보드 ↑(상)↓(하) 키로 이동해 주세요.</TD>

                </tr>
                <TR>
                    <TD align=center>
                        <a href="javascript:move_save();"><img src="images/btn_goods_order.gif" border="0"></a>
                    </TD>
                </TR>
				</table>
                </form>

				</td>
			</tr>
			<!-- 처리할 본문 위치 끝 -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>

<form name=etcform method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=prcodes>
<input type=hidden name=display>
</form>




</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<?php include("copyright.php"); ?>
