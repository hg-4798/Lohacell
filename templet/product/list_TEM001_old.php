<?
include_once dirname(__FILE__)."/../../lib/product.class.php";
if(!$product) $product = new PRODUCT();
?>    
<!-- start container -->
<div id="container">
	<!-- start contents -->
	<div class="contents">
	
	<div class="category_depth">

		<div class="path">
			<ul>
				<li class="home">홈&nbsp;&gt;&nbsp;</li>
				<li>
	<select id="code_a" name="code_a" class="categoryselect" onchange="javascript:cate_change('a');">
	<?
	$a_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
	$a_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_b='000' ORDER BY cate_sort ";
	$a_result=pmysql_query($a_sql);
	while($a_data=pmysql_fetch_object($a_result)){
		$a_checked[$code_a]="selected";
	?>
		<option value="<?=$a_data->code_a?>" <?=$a_checked[$a_data->code_a]?>><?=$a_data->code_name?></option>
	<?	
	}
	?>
	<!--
      <option value="1" selected>베이스오일</option>
      <option value="2">아로마오일,워터류</option>
      <option value="3">화장품원료</option>
      <option value="4">비누원료</option>
      <option value="5">분말,허브</option>
      <option value="6">용기</option>
      <option value="7">예쁜포장</option>
      <option value="8">만들기도구</option>
      <option value="9">키트세트</option>
	  -->
    </select>
				
				</li>
				
	<?
	$b_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
	$b_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_a='".$code_a."' and code_b!='000' and code_c='000' ORDER BY cate_sort ";
	$b_result=pmysql_query($b_sql);
	$b_num=pmysql_num_rows($b_result);
	
	if($b_num){
		
	?>
				<li>&nbsp; > &nbsp;</li>
				<li>
				
	
	<select id="code_b" name="code_b" class="categoryselect" onchange="javascript:cate_change('b');">
	
	
	<option value="">2차 카테고리</option>
	<?
	while($b_data=pmysql_fetch_object($b_result)){
		$b_checked[$code_b]="selected";
	?>
		<option value="<?=$b_data->code_b?>"<?=$b_checked[$b_data->code_b]?>><?=$b_data->code_name?></option>
	<?	
	}
	?>
    <!--
	  <option value="1" selected>The Fresh 비정제</option>
      <option value="2">유기농</option>
      <option value="3">정제</option>
      <option value="4">비누용</option>
      <option value="5">버터류</option>
	 -->
    </select>
				
				</li>
	<?}
		
	$c_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
	$c_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_a='".$code_a."' and code_b='".$code_b."' and code_c!='000' and code_d='000' and code_b!='000' ORDER BY cate_sort ";
	$c_result=pmysql_query($c_sql);
	$c_num=pmysql_num_rows($c_result);
	
	if($c_num){
		
	?>
				<li>&nbsp; > &nbsp;</li>
				<li>
				
	
	<select id="code_c" name="code_c" class="categoryselect" onchange="javascript:cate_change('c');">
	
	
	<option value="">3차 카테고리</option>
	<?
	while($c_data=pmysql_fetch_object($c_result)){
		$c_checked[$code_c]="selected";
	?>
		<option value="<?=$c_data->code_c?>"<?=$c_checked[$c_data->code_c]?>><?=$c_data->code_name?></option>
	<?	
	}
	?>
    <!--
	  <option value="1" selected>The Fresh 비정제</option>
      <option value="2">유기농</option>
      <option value="3">정제</option>
      <option value="4">비누용</option>
      <option value="5">버터류</option>
	 -->
    </select>
				
				</li>
	<?}
	
	
	$d_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
	$d_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_a='".$code_a."' and code_b='".$code_b."' and code_c='".$code_c."' and code_d!='000' ORDER BY cate_sort ";
	$d_result=pmysql_query($d_sql);
									
	$d_num=pmysql_num_rows($d_result);
	
	if($d_num){
		
	?>
				<li>&nbsp; > &nbsp;</li>
				<li>
				
	
	<select id="code_d" name="code_d" class="categoryselect" onchange="javascript:cate_change('d');">
	
	
	<option value="">4차 카테고리</option>
	<?
	while($d_data=pmysql_fetch_object($d_result)){
		$d_checked[$code_d]="selected";
	?>
		<option value="<?=$d_data->code_d?>"<?=$d_checked[$d_data->code_d]?>><?=$d_data->code_name?></option>
	<?	
	}
	?>
    <!--
	  <option value="1" selected>The Fresh 비정제</option>
      <option value="2">유기농</option>
      <option value="3">정제</option>
      <option value="4">비누용</option>
      <option value="5">버터류</option>
	 -->
    </select>
				
				</li>
	<?}?>
			</ul>

		</div>
	</div>
	
	
<div class="category_header">
<ul>
				<li class="category_title"><img src="<?=$Dir?>image/list/cat_title<?=substr($code,1,2)?>.gif"></li>
				<li class="category_sublist">
                <ul>
				
<?
if($_data->ETCTYPE["CODEYES"]!="N") {
	if(strlen($likecode)==3) {			//1차분류 (1차에 속한 모든 2차,3차분류를 보여준다) - 3차가 있는지 검사
		//1차가 최종분류일 경우엔 아무것도 보여주지 않는다.
		if($_cdata->type!="LX" && $_cdata->type!="TX") {	//하위분류가 있을 경우에만
			$sql = "SELECT COUNT(*) as cnt FROM tblproductcode ";
			$sql.= "WHERE code_a='".$code_a."' AND code_b!='000' AND code_c!='000' AND group_code!='NO' ";
			$result=pmysql_query($sql,get_db_conn());
			$row=pmysql_fetch_object($result);
			$cnt=$row->cnt;

			if($cnt>0) {
				$disp_1 = true;
				$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
				$sql.= "WHERE code_a='".$code_a."' AND code_b!='000' AND code_c='000' AND code_d='000' AND group_code!='NO' ";
				$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
				$sql.= "ORDER BY cate_sort ";				
			} else {
				$disp_4 = true;			
				$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
				$sql.= "WHERE code_a='".$code_a."' AND code_b!='000' AND code_c='000' AND code_d='000' AND group_code!='NO' ";
				$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
				$sql.= "ORDER BY cate_sort ";				
			}
		}
	} elseif(strlen($likecode)==6) {	//2차분류 (2차에 속한 모든 3차,4차분류를 보여준다) - 4차가 있는지 검사
		//2차가 최종분류일 경우엔 1차에 속한 2차를 보여준다
		if($_cdata->type!="LMX" && $_cdata->type!="TMX") {	//하위분류가 있을 경우에만
			$disp_3 = true;
		} else {
			$disp_4 = true;			
			$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
			$sql.= "WHERE code_a='".$code_a."' AND code_b!='000' AND code_c='000' AND code_d='000' AND group_code!='NO' ";
			$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
			$sql.= "ORDER BY cate_sort ";
		}
	} elseif(strlen($likecode)==9) {	//3차분류 (2차에 속한 모든 3차, 4차분류를 보여준다) - 4차가 있는지 검사
		//3차가 최종분류일 경우엔 2차에 속한 3차를 보여준다
		if($_cdata->type!="LMX" && $_cdata->type!="TMX") {	//하위분류가 있을 경우에만
			$disp_3 = true;
		} else {
			$disp_4 = true;
			$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
            $sql.= "WHERE code_a='".$code_a."' AND code_b='".$code_b."' AND code_c!='000' AND code_d='000' AND group_code!='NO' ";
            $sql.= "AND (type='LM' OR type='TM' OR type='LMX' OR type='TMX') ";
            $sql.= "ORDER BY cate_sort ";
		}
	} elseif(strlen($likecode)==12) {	//4차분류 (3차에 속한 모든 4차분류만 보여준다)
		$disp_4 = true;
		$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
		$sql.= "WHERE code_a='".$code_a."' AND code_b='".$code_b."' AND code_c='".$code_c."' AND code_d!='000' AND group_code!='NO' ";
		$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
		$sql.= "ORDER BY cate_sort ";		
	}
	
	if($disp_3) {
			$sql = "SELECT COUNT(*) as cnt FROM tblproductcode WHERE code_a='".$code_a."' AND code_b='".$code_b."' AND code_c!='000' AND code_d!='000' AND group_code!='NO' ";
			$result=pmysql_query($sql,get_db_conn());
			$row=pmysql_fetch_object($result);
			$cnt=$row->cnt;

			if($cnt>0) {
				$disp_1 = true;
				$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
				$sql.= "WHERE code_a='".$code_a."' AND code_b='".$code_b."' AND code_c!='000' AND code_d='000' AND group_code!='NO' ";
				$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
				$sql.= "ORDER BY cate_sort ";
			} else {				
				$disp_4 = true;
				$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
				$sql.= "WHERE code_a='".$code_a."' AND code_b='".$code_b."' AND code_c!='000' AND code_d='000' AND group_code!='NO' ";
				$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
				$sql.= "ORDER BY cate_sort ";				
			}
	}
	if($disp_1) {
		
		$result=pmysql_query($sql,get_db_conn());
		$i=0;
		while($row=pmysql_fetch_object($result)) {
			//if($i>0) $category_list.="<tr><td height=1 colspan=2 bgcolor=FFFFFF></td></tr>\n";
			echo "<li>";
			echo "<a href=\"".$Dir.FrontDir."productlist.php?code=".$row->code_a.$row->code_b.$row->code_c.$row->code_d."\">";
			if($code==$row->code_a.$row->code_b.$row->code_c.$row->code_d) {
				echo "<font class=\"current\">".$row->code_name."</font>";
			} else {
				echo "".$row->code_name."";
			}
			echo "</a>";
			echo "</li>";
			if(!strstr($row->type,"X")) {
				if($row->code_c==='000') {
					$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
					$sql.= "WHERE code_a='".$row->code_a."' AND code_b='".$row->code_b."' AND code_c!='000' AND code_d='000' AND group_code!='NO' ";
					$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
					$sql.= "ORDER BY cate_sort ";
				} else {
					$sql = "SELECT code_a,code_b,code_c,code_d,code_name,type FROM tblproductcode ";
					$sql.= "WHERE code_a='".$row->code_a."' AND code_b='".$row->code_b."' AND code_c='".$row->code_c."' AND code_d!='000' AND group_code!='NO' ";
					$sql.= "AND type IN ('LM','TM','LMX','TMX') ";
					$sql.= "ORDER BY cate_sort ";
				}				
				
				$result2=pmysql_query($sql,get_db_conn());
				$_=array();
				while($row=pmysql_fetch_object($result)) {
					echo "<li>";
					echo "<a href=\"".$Dir.FrontDir."productlist.php?code=".$row->code_a.$row->code_b.$row->code_c.$row->code_d."\">";
					if($code==$row->code_a.$row->code_b.$row->code_c.$row->code_d) {
						echo "<font class=\"current\">".$row->code_name."</font>";
					} else {
						echo "".$row->code_name."";
					}
					echo "</a>";
					echo "</li>";
				}
				
			}
			$i++;
		}
		
	}
	
	if($disp_4) {
		
		$result=pmysql_query($sql,get_db_conn());
		while($row=pmysql_fetch_object($result)) {
			echo "<li>";
			echo "<a href=\"".$Dir.FrontDir."productlist.php?code=".$row->code_a.$row->code_b.$row->code_c.$row->code_d."\">";
			if($code==$row->code_a.$row->code_b.$row->code_c.$row->code_d) {
				echo "<font class=\"current\">".$row->code_name."</font>";
			} else {
				echo "".$row->code_name."";
			}
			echo "</a>";
			echo "</li>";
			
		}
	}
}
?>

               </ul>
				</li>
</ul>
</div>

<?
$codenum=str_pad($code, 12, "0", STR_PAD_RIGHT);

$sql = "SELECT special_list FROM tblspecialcode ";
$sql.= "WHERE code = '{$codenum}' AND special='2' ";
$result = pmysql_query($sql,get_db_conn());
if($row = pmysql_fetch_object($result)){
	$sp_prcode=str_replace(',','\',\'',$row->special_list);
}	

$sql = "SELECT option_price, productcode,productname,production,sellprice,consumerprice, ";
$sql.= "buyprice,quantity,reserve,reservetype,addcode,display,vender,maximage,selfcode,assembleuse ";
$sql.= "FROM tblproduct ";
$sql.= "WHERE productcode IN ('{$sp_prcode}') ORDER BY FIELD(productcode,'".$sp_prcode."') limit 5";
$result = pmysql_query($sql,get_db_conn());
$i=1;
$ii=0;
while($row=pmysql_fetch_object($result)) {
	$cate_best[$ii]["maximage"]=$row->maximage;
	$cate_best[$ii]["productname"]=$row->productname;
	$cate_best[$ii]["sellprice"]=$row->sellprice;
	$cate_best[$ii]["consumerprice"]=$row->consumerprice;
	$cate_best[$ii]["productcode"]=$row->productcode;
	$cate_best[$ii]["cateno"]=$i;
$i++;
$ii++;		
}

if(count($cate_best)){
	
?>

<div class="category_best">
<h2><img src="<?=$Dir?>image/list/category_best.gif"></h2>

<div class="category_bestlist">

<ul class="category_bestitem1">
				<li class="category_bestitem1_img"><a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$cate_best[0]["productcode"]?>"><img src="<?=$Dir.DataDir?>shopimages/product/<?=urlencode($cate_best[0]["maximage"])?>"></a></li>
				<li class="category_bestitem1_info">
				<p><img src="<?=$Dir?>image/list/category_best1.gif"></p>
				<p class="category_bestitem1_name"><a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$cate_best[0]["productcode"]?>"><?=viewproductname($cate_best[0]["productname"],$row->etctype,$row->selfcode)?></a></p>
				<p class="category_bestitem1_price">
					<?if($cate_best[0]["consumerprice"]){
						echo number_format($cate_best[0]["consumerprice"])." → ";
					}?>
					<font class="sale_price"><?=number_format($cate_best[0]["sellprice"])?>원</font>
					
				</p>
				</li>
</ul>

<ul class="category_bestitem">
<?
	foreach($cate_best as $v=>$k){
		if($k[cateno]=="1"){
			continue;
		}
	
?>
				<li>
				<p class="bestlist_img"><a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$cate_best[$v]["productcode"]?>"><img src="<?=$Dir.DataDir?>shopimages/product/<?=urlencode($cate_best[$v]["maximage"])?>"></a></p>
				<p class="bestlist_info">
				<span class="bestlist_info_label"><img src="<?=$Dir?>image/list/category_best<?=$k[cateno]?>.gif"></span>
				<span>
				<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$cate_best[$v]["productcode"]?>"><?=viewproductname(mb_strimwidth($cate_best[$v]["productname"],0,18,'...','euc_kr'),$row->etctype,$row->selfcode)?></a><br>
					<?if($cate_best[$v]["consumerprice"]){
						echo number_format($cate_best[$v]["consumerprice"])." → ";
					}?>

				<font class="bestlist_price"><?=number_format($cate_best[$v]["sellprice"])?>원</font>
				</span>
				</p>
				</li>
<?}?>
			
</ul>


<div class="clearboth"></div>
</div>
</div>

<?}

//	<!-- 상품목록 시작 -->
if($_cdata->islist=="Y"){

$sql = "SELECT COUNT(*) as t_count FROM tblproduct AS a ";
$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
$sql.= $qry." ";
$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
if(strlen($not_qry)>0) {
	$sql.= $not_qry." ";
}
//$listnum
$paging = new Tem001_Paging($sql,10,$listnum,'GoPage',true);
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;
?>

		
<div class="itemlist_header">
	<div class="itemlist_subtitle">
		<span class="itemlist_subtitle_title"><?=$_cdata->code_name?></span>
		<span>현재 카테고리에 <font class="number_ea"><?=$t_count?></font>개의 제품이 있습니다.</span>
	</div>
	<div class="itemlist_sort_warp">
	<span class="itemlist_sort">
		<a href="javascript:ChangeSort('order')"><font <?=$sort_current["sort_current"][order]?>>누적 판매량순</font></a><font class="sort_line">|</font>
		<a href="javascript:ChangeSort('opendate')"><font <?=$sort_current["sort_current"][opendate]?>>신상품순</font></a><font class="sort_line">|</font>
		<a href="javascript:ChangeSort('best')"><font <?=$sort_current["sort_current"][best]?>>인기상품순</font></a><font class="sort_line">|</font>
		<a href="javascript:ChangeSort('price_desc')"><font <?=$sort_current["sort_current"][price_desc]?>>높은가격순</font></a><font class="sort_line">|</font>
		<a href="javascript:ChangeSort('price')"><font <?=$sort_current["sort_current"][price]?>>낮은가격순</font></a><font class="sort_line">|</font>
		<a href="javascript:ChangeSort('dcprice')"><font <?=$sort_current["sort_current"][dcprice]?>>할인율순</font></a>
	</span>
	<span class="itemlist_viewNo">
		<select id="listnum" name="listnum" onchange="javascript:ChangeSort('<?=$sort?>','listnum');">
			<option value="20" <?=$selected["listnum"][20]?>>20개씩 보기</option>
			<option value="40" <?=$selected["listnum"][40]?>>40개씩 보기</option>
			<option value="80" <?=$selected["listnum"][80]?>>80개씩 보기</option>
			<option value="120" <?=$selected["listnum"][120]?>>120개씩 보기</option>
		</select>
	</span>

	</div>
</div>

<div class="itemlist">
<ul>
<?

		//번호, 사진, 상품명, 제조사, 가격
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",CASE WHEN a.reservetype='N' THEN CAST(a.reserve AS FLOAT)*1 ELSE CAST(a.reserve AS FLOAT)*a.sellprice*0.01 END AS reservesort ";
		}
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, a.option1, a.option2, a.option_quantity, ";
		if($_cdata->sort=="date2") $sql.="CASE WHEN a.quantity<=0 THEN '11111111111111' ELSE a.date END as date, ";
		$sql.= "a.maximage, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= $addsortsql;
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
		
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="opendate") $sql.= "ORDER BY opendate DESC, date desc ";
		elseif($tmp_sort[0]=="dcprice") $sql.= "ORDER BY case when consumerprice>0 then  100 - cast((cast(sellprice as float)/cast(consumerprice as float))*100 as integer) else 0 end desc ";
		elseif($tmp_sort[0]=="order" ){
			$bestsql="select COALESCE(sum(cnt),0) sumcnt, a.productcode from tblproduct a left join tblcounterproduct b on (a.productcode=b.productcode) where a.productcode like '".$likecode."%' group by a.productcode order by sumcnt desc";
			$bestresult=pmysql_query($bestsql);
			
			$count=0;
			$lk=1;
			$casewhen="";
			while($bestrow=pmysql_fetch_object($bestresult)){
				$productcode[$count]=$bestrow->productcode;
				$casewhen[]=" '".$bestrow->productcode."' then ".$lk;
				$count++;
				$lk++;
			}
			
			$sql.= "ORDER BY a.start_no desc ";
			if(count($casewhen)>0) $sql.= " ,case a.productcode when ".implode(" when ",$casewhen)." end ";	
			//$prlist = implode("','",$productcode);
			//$sql.="ORDER BY FIELD(a.productcode,'{$prlist}') ";
		}else if($tmp_sort[0]=="best"){

			$sql.= "ORDER BY a.start_no desc ";
			if(count($lk_casewhen)>0) $sql.= " ,case a.productcode when ".implode(" when ",$lk_casewhen)." end ";
		}else {
			if(strlen($_cdata->sort)==0 || $_cdata->sort=="date" || $_cdata->sort=="date2") {
				if(strstr($_cdata->type,"T") && strlen($t_prcode)>0) {
					$sql.= "ORDER BY FIELD(a.productcode,'".$t_prcode."'),date DESC ";
				} else {
					$sql.= "ORDER BY opendate DESC ";
					//$sql.= "ORDER BY a.start_no desc,case a.productcode when ".implode(" when ",$lk_casewhen)." end ";
				}
			} elseif($_cdata->sort=="productname") {
				$sql.= "ORDER BY a.start_no desc,a.productname ";
			} elseif($_cdata->sort=="production") {
				$sql.= "ORDER BY a.start_no desc,a.production ";
			} elseif($_cdata->sort=="price") {
				$sql.= "ORDER BY a.start_no desc,a.sellprice ";
			}
		}
		
		$sql = $paging->getSql($sql);

		$result=pmysql_query($sql,get_db_conn());
		
		$i=0;
		while($row=pmysql_fetch_object($result)) {
//			echo $row->productcode;
			$dc_data = $product->getProductDcRate($row->productcode);
//			var $dc_data = PRODUCT::getProductDcRate($row->productcode);
			$temp = $row->option1;
			$tok = explode(",",$temp);
			$goods_count=count($tok);
			
			$check_optea='0';
			if($goods_count>"1"){
				$check_optea="1";	
			}
			
			$optioncnt = explode(",",ltrim($row->option_quantity,','));
			$check_optout=array();
			$check_optin=array();
			for($gi=1;$gi<$goods_count;$gi++) {
				
				if(strlen($row->option2)==0 && $optioncnt[$gi-1]=="0"){ $check_optout[]='1';}
				else{  $check_optin[]='1';}
			}
			
			//타임세일 가격변경
			$timesale_data=$timesale->getPdtData($row->productcode);
			$time_sale_now='';
			if($timesale_data['s_price']>0){
				$time_sale_now='Y';
				$row->sellprice = $timesale_data['s_price'];
			}
			//타임세일 가격변경
			
			$number = ($t_count-($setup['list_num'] * ($gotopage-1))-$i);
			
			if($i=="5"){
				$i=0;
?>
			</ul><ul>
<?			}?>


			<li>
<?php		if (strlen($row->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->maximage)) { ?>
				<p class="itemlist_img"><a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode?>"><img src="<?=$Dir.DataDir."shopimages/product/".urlencode($row->maximage)?>"></a></p>			
<?php		} else { ?>
				<p class="itemlist_img"><a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode?>"><img src="<?=$Dir?>images/no_img.gif" border="0" align="center"></a></p>			
<?php		} ?>
				
				<p class="itemlist_info"><a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode?>"><?=viewproductname($row->productname,$row->etctype,$row->selfcode)?></a></p>
				<p class="itemlist_price">
				<?
				if($row->consumerprice) echo "<strike>".dickerview_tem001($row->etctype,number_format($row->consumerprice)." 원</strike> → <font class='sale_price' id='idx_price'>");
				echo dickerview_tem001($row->etctype,number_format($row->sellprice)." 원");
				if($row->consumerprice) echo "</font>";
//				if($dc_data[price]) echo " → <font class='sale_price' id='idx_price'>".number_format(getProductSalePrice($row->sellprice,$dc_data[price]))."</font> 원";
				if ($_data->ETCTYPE["MAINSOLD"]=="Y"  && ($row->quantity=="0" || (count($check_optin)=='0' && $check_optea))) echo soldout();
				?>
				
				</p>
			</li>
				
<?

		$i++;}
}		
?>
				
</ul>
</div>


	<div class="paging">
		
			<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
		
	</div><!-- paging 끝 -->

	</div><!-- //end contents -->
</div><!-- //end container -->

		































<?/*?>

<!-- 상세페이지 -->
<div class="main_wrap">
	<div class="container">

		<!-- 주소복사 -->
		<p class="local_copy mt_20"><span><?=$codenavi?></span><A HREF="javascript:ClipCopy('http://<?=$_ShopInfo->getShopurl2()?>?<?=$_SERVER["QUERY_STRING"]?>')" class="btn_small">주소복사 ></a></p>
		<!-- #주소복사 -->
		<?if($_data->ETCTYPE["CODEYES"]!="N") {?>
		<!-- 카테고리 선택 -->
		<div class="category_select_wrap">
			<div class="category_select">
					<span>상품</span> > 

					<select name=code_a id="code_a" style="width:183px" onchange="javascript:cate_change('a');" style="font-size:11px;">
					<option value="">1차 카테고리</option>
					<?
					$a_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
					$a_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_b='000' ORDER BY cate_sort ";
					$a_result=pmysql_query($a_sql);
					while($a_data=pmysql_fetch_object($a_result)){
						$a_checked[$code_a]="selected";
					?>
					<option value="<?=$a_data->code_a?>" <?=$a_checked[$a_data->code_a]?>><?=$a_data->code_name?></option>
					<?	
					}
					?>
					</select> >
					<select name=code_b id="code_b" style="width:183px" onchange="javascript:cate_change('b');" style="font-size:11px;">
					<option value="">2차 카테고리</option>
					<?
					$b_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
					$b_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_a='".$code_a."' and code_b!='000' and code_c='000' ORDER BY cate_sort ";
					$b_result=pmysql_query($b_sql);
					while($b_data=pmysql_fetch_object($b_result)){
						$b_checked[$code_b]="selected";
					?>
					<option value="<?=$b_data->code_b?>"<?=$b_checked[$b_data->code_b]?>><?=$b_data->code_name?></option>
					<?	
					}
					?>
					</select> >
					<select name=code_c id="code_c" style="width:183px;" onchange="javascript:cate_change('c');" style="font-size:11px;">
					<option value="">3차 카테고리</option>
					<?
					$c_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
					$c_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_a='".$code_a."' and code_b='".$code_b."' and code_c!='000' and code_d='000' and code_b!='000' ORDER BY cate_sort ";
					$c_result=pmysql_query($c_sql);
					while($c_data=pmysql_fetch_object($c_result)){
						$c_checked[$code_c]="selected";
					?>
					<option value="<?=$c_data->code_c?>"<?=$c_checked[$c_data->code_c]?>><?=$c_data->code_name?></option>
					<?	
					}
					?>
					</select> >
					<select name=code_d id="code_d" style="width:183px" onchange="javascript:cate_change('d');" style="font-size:11px;">
					<option value="">4차 카테고리</option>
					<?
					$d_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
					$d_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_a='".$code_a."' and code_b='".$code_b."' and code_c='".$code_c."' and code_d!='000' ORDER BY cate_sort ";
					$d_result=pmysql_query($d_sql);
					while($d_data=pmysql_fetch_object($d_result)){
						$d_checked[$code_d]="selected";
					?>
					<option value="<?=$d_data->code_d?>"<?=$d_checked[$d_data->code_d]?>><?=$d_data->code_name?></option>
					<?	
					}
					?>
					</select>
					
				<?if($category_list){?>
				<div class="category_list">
					<h2><?=$_cdata->code_name?></h2>
					<?=$category_list?>
				</div>
				<?}?>
			</div>
		</div>
		<!-- #카테고리 선택 -->
		<?}?>
		<!-- 카테고리별 리스트 -->
		<div class="main_product_list">


<?

//<!-- 신규/인기/추천 시작 -->

$special_show_cnt=0;

$arrspecialcnt=explode(",",$_cdata->special_cnt);
foreach($arrspecialcnt as $specialitem) {
	$arr = explode(':',$specialitem);
	$arr2 = explode('X',$arr[1]);
	$special[$arr[0]]['cols'] = $arr2[0];
	$special[$arr[0]]['rows'] = $arr2[1];
	$special[$arr[0]]['type'] = $arr2[2];
	$special[$arr[0]]['num'] = $special[$arr[0]]['cols'] * $special[$arr[0]]['rows'];
}


$rows = array();
$id = array('','N','B','H');
$title = array('','CATEGORY NEW','CATEGORY BEST','CATEGORY HOT');
foreach(explode(',',$_cdata->special) as $idx) {
	$sql = "SELECT special_list FROM tblspecialcode ";
	$sql.= "WHERE code='".$code."' AND special='{$idx}' ";
	$result=pmysql_query($sql,get_db_conn());
	$sp_prcode="";
	
	if($row=pmysql_fetch_object($result)) {
		$sp_prcode=str_replace(',','\',\'',$row->special_list);
	}
	if(strlen($sp_prcode)>0) {
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, ";
		$sql.= "a.maximage, a.date, a.etctype, a.reserve, a.reservetype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "WHERE a.productcode IN ('".$sp_prcode."') AND a.display='Y' ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
		$sql.= "ORDER BY FIELD(a.productcode,'".$sp_prcode."') ";
		$sql.= "LIMIT ".$special[$idx]['num'];
		$result=pmysql_query($sql,get_db_conn());
		while($row=pmysql_fetch_object($result)) {

			//타임세일 가격변경
			$timesale_data=$timesale->getPdtData($row->productcode);
			$time_sale_now='';
			if($timesale_data['s_price']>0){
				$time_sale_now='Y';
				$row->sellprice = $timesale_data['s_price'];
			}
			//타임세일 가격변경

			$rows[$idx][] = $row;
		}
	}
}

foreach(explode(',',$_cdata->special) as $idx) {
?>
			<h2><?=$title[$idx]?></h2>
			<div class="list">
				<ul>
				<?
				foreach($rows[$idx] as $row) {
					if (strlen($row->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->maximage)) { 
				?>
						<li>
						<div class="img">
						<A HREF="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query?>"><img src="<?=$Dir.DataDir?>shopimages/product/<?=urlencode($row->maximage)?>" width=<?=$_data->primg_minisize?> height=<?=$_data->primg_minisize?>></a>
						<?if(!$_data->ETCTYPE["QUICKTOOLS"]){?>
						<div class="quick_menu">
						<img src="<?=$Dir?>images/common/icon_RPLout01.gif" onclick="PrdtQuickCls.quickView('<?=$row->productcode?>');">
						<img src="<?=$Dir?>images/common/icon_RPLout02.gif" onclick="PrdtQuickCls.quickFun('<?=$row->productcode?>','1');">
						<img src="<?=$Dir?>images/common/icon_RPLout03.gif" onclick="PrdtQuickCls.quickFun('<?=$row->productcode?>','2');">
						<img src="<?=$Dir?>images/common/icon_RPLout04.gif" onclick="PrdtQuickCls.quickFun('<?=$row->productcode?>','3');">	
						</div>
						<?}?>
						</div>
						<p><A HREF="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query?>"><?=viewproductname($row->productname,$row->etctype,$row->selfcode)?></A>
						<br />
						<?if($row->consumerprice!=0){?>
								<strike><?=number_format($row->consumerprice)?></strike>
						<?}
						echo dickerview_tem001($row->etctype,number_format($row->sellprice)." won");
						if ($_data->ETCTYPE["MAINSOLD"]=="Y" && $row->quantity=="0") echo soldout();
						?>
						</p>
						</li>
<?php				} else { ?>
						<li><img src="<?=$Dir?>images/no_img.gif" border="0" align="center" width=<?=$_data->primg_minisize?> height=<?=$_data->primg_minisize?>></li>
<?php				} ?>
					
					
					
				<?}?>
				
				</ul>
			</div>
<?}

//<!-- 신규/인기/추천 끝 -->
//	<!-- 상품목록 시작 -->
if($_cdata->islist=="Y"){

$sql = "SELECT COUNT(*) as t_count FROM tblproduct AS a ";
$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
$sql.= $qry." ";
$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
if(strlen($not_qry)>0) {
	$sql.= $not_qry." ";
}
$paging = new Tem001_Paging($sql,10,$listnum,'GoPage',true);
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;
?>
			<h1 class="sub_name">
				<?=$_cdata->code_name?>
				<p><a href="#">총 <?=$t_count?>개의 상품이 있습니다.</a></p>
			</h1>
			<div class="list">
				<ul>
<?

		//번호, 사진, 상품명, 제조사, 가격
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",CASE WHEN a.reservetype='N' THEN CAST(a.reserve AS FLOAT)*1 ELSE CAST(a.reserve AS FLOAT)*a.sellprice*0.01 END AS reservesort ";
		}
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
		if($_cdata->sort=="date2") $sql.="CASE WHEN a.quantity<=0 THEN '11111111111111' ELSE a.date END as date, ";
		$sql.= "a.maximage, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= $addsortsql;
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
		else {
			if(strlen($_cdata->sort)==0 || $_cdata->sort=="date" || $_cdata->sort=="date2") {
				if(strstr($_cdata->type,"T") && strlen($t_prcode)>0) {
					$sql.= "ORDER BY FIELD(a.productcode,'".$t_prcode."'),date DESC ";
				} else {
					$sql.= "ORDER BY date DESC ";
				}
			} elseif($_cdata->sort=="productname") {
				$sql.= "ORDER BY a.productname ";
			} elseif($_cdata->sort=="production") {
				$sql.= "ORDER BY a.production ";
			} elseif($_cdata->sort=="price") {
				$sql.= "ORDER BY a.sellprice ";
			}
		}
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		$i=0;
		while($row=pmysql_fetch_object($result)) {
			//타임세일 가격변경
			$timesale_data=$timesale->getPdtData($row->productcode);
			$time_sale_now='';
			if($timesale_data['s_price']>0){
				$time_sale_now='Y';
				$row->sellprice = $timesale_data['s_price'];
			}
			//타임세일 가격변경
			
			$number = ($t_count-($setup['list_num'] * ($gotopage-1))-$i);
			if (strlen($row->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->maximage)) { ?>
				<li>
					<div class="img">
					<A HREF="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort?>">
					<img src="<?=$Dir.DataDir."shopimages/product/".urlencode($row->maximage)?>" width=<?=$_data->primg_minisize?> height=<?=$_data->primg_minisize?>></A>
					<?if(!$_data->ETCTYPE["QUICKTOOLS"]){?>
					<div class="quick_menu">
					<img src="<?=$Dir?>images/common/icon_RPLout01.gif" onclick="PrdtQuickCls.quickView('<?=$row->productcode?>');">
					<img src="<?=$Dir?>images/common/icon_RPLout02.gif" onclick="PrdtQuickCls.quickFun('<?=$row->productcode?>','1');">
					<img src="<?=$Dir?>images/common/icon_RPLout03.gif" onclick="PrdtQuickCls.quickFun('<?=$row->productcode?>','2');">
					<img src="<?=$Dir?>images/common/icon_RPLout04.gif" onclick="PrdtQuickCls.quickFun('<?=$row->productcode?>','3');">	
					</div>
					<?}?>
					</div>
					
					<p><A HREF="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query?>"><?=viewproductname($row->productname,$row->etctype,$row->selfcode)?></A>
					<br />
					<?if($row->consumerprice!=0){?>
							<strike><?=number_format($row->consumerprice)?></strike>
					<?}
					echo dickerview_tem001($row->etctype,number_format($row->sellprice)." won");
					if ($_data->ETCTYPE["MAINSOLD"]=="Y" && $row->quantity=="0") echo soldout();
					?>
					
				</p>
<?php		} else { ?>
				<li><img src="<?=$Dir?>images/no_img.gif" width=<?=$_data->primg_minisize?> height=<?=$_data->primg_minisize?>></li>
<?php		}

		}?>
				
			</div>
		</div>
		<!-- # 카테고리별 리스트 -->

		<!-- 페이징 -->
		<div class="page_wrap">
			<div class="page">
			<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
			<!--
			<a href="#" class="pre"><<</a><a href="#" class="pre"><</a><a href="#" class="select">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#" class="pre">></a><a href="#" class="pre">>></a></div>
			-->
		</div>
		<!-- #페이징 -->

	</div>
</div>
<?php }?>

<?*/?>