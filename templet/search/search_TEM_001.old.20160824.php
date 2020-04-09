    
<!-- start container -->
<div id="container">
	<!-- start contents -->
	<div class="contents">
	
	<div class="title">
			<h2><img src="<?=$Dir?>image/list/title_search.gif" alt="제품검색" /></h2>
			<div class="path">
				<ul>
					<li class="home">홈&nbsp;&gt;&nbsp;</li>
					<li>제품검색</li>
				</ul>
			</div>
		</div>
	
<div class="search_box">
<div class="search_result"> 
검색어 [<font class="search_word"> <?=$search?> </font>] 
검색결과 총 <font class="search_ea"><?=$t_count?></font>개의 제품이 있습니다.
</div>
				

<div class="research">
<ul>
	<li><img src="<?=$Dir?>image/list/search.gif" alt="검색" /></li>
	<li>상품가격 :</li>
	<li><input type=text name=minprice value="<?=$minprice?>"> ~ <input type=text name=maxprice value="<?=$maxprice?>"></li>
	<li> / </li>
	<li>검색어 :</li>
	<li>
<select name=s_check>
<option value="all" <?if($s_check=="all")echo"selected";?>>통합검색</option>
<option value="keyword" <?if($s_check=="keyword")echo"selected";?>>상품명/키워드</option>
<option value="code" <?if($s_check=="code")echo"selected";?>>상품코드</option>
<option value="selfcode" <?if($s_check=="selfcode")echo"selected";?>>진열코드</option>
<option value="production" <?if($s_check=="production")echo"selected";?>>제조사</option>
<option value="model" <?if($s_check=="model")echo"selected";?>>모델명</option>
<option value="content" <?if($s_check=="content")echo"selected";?>>상세설명</option>
</select>
<input type=text name=search value="<?=$search?>" size=25>

</li>
<li><a href="javascript:CheckForm();" class="btn_search01"><img src="<?=$Dir?>image/list/bt_search.gif" alt="검색" /></a></li>
</ul>



</div>



</div>






<div class="itemlist_header">
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
<select name=listnum onchange="ChangeListnum(this.value)">
			<option value="20"<?if($listnum==20)echo" selected";?>>20개씩 보기
			<option value="40"<?if($listnum==40)echo" selected";?>>40개씩 보기
			<option value="80"<?if($listnum==80)echo" selected";?>>80개씩 보기
			<option value="120"<?if($listnum==120)echo" selected";?>>120개씩 보기
			</select>
</span>
</div>

</div>


<div class="itemlist">
<ul>
<?php
		$tag_0_count = 2; //전체상품 태그 출력 갯수
		//번호, 사진, 상품명, 제조사, 가격
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",CASE WHEN a.reservetype='N' THEN CAST(a.reserve AS FLOAT)*1 ELSE CAST(a.reserve AS FLOAT)*a.sellprice*0.01 END AS reservesort ";
		}
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, a.option1, a.option2, a.option_quantity, ";
		$sql.= "a.maximage, a.date, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= $addsortsql;
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="dcprice") $sql.= "ORDER BY case when consumerprice>0 then  100 - cast((cast(sellprice as float)/cast(consumerprice as float))*100 as integer) else 0 end desc ";
		elseif($tmp_sort[0]=="best" || $tmp_sort[0]=="order" ){
			$bestsql="select COALESCE(sum(cnt),0) sumcnt, a.productcode from tblproduct a left join tblcounterproduct b on (a.productcode=b.productcode) where a.productname like '%".$search."%' and a.productcode not like '035%' group by a.productcode order by sumcnt desc limit 100";
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
		
		
		if(count($casewhen)>0) $sql.= "ORDER BY case a.productcode when ".implode(" when ",$casewhen)." end ";	
		}else{ $sql.= "ORDER BY a.productname ";}
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		$i=0;
		while($row=pmysql_fetch_object($result)) {
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

			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
			if($i=="5"){
				$i=0;
?>
			</ul><ul>
<?			}?>
				<li>
				<p class="itemlist_img"><A HREF="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort?>">
					<img src="<?=$Dir.DataDir."shopimages/product/".urlencode($row->maximage)?>" width=<?=$_data->primg_minisize?> height=<?=$_data->primg_minisize?>></A>
					<p class="preview_btn" alt="<?=$row->productcode?>">상품미리보기</p></p>
				<p class="itemlist_info"><A HREF="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query?>"><?=viewproductname($row->productname,$row->etctype,$row->selfcode)?></A></p>
				<p class="itemlist_price">
					<?if($row->consumerprice){
						echo "<strike>".number_format($row->consumerprice)."</strike> → <font class='sale_price' id='idx_price'>";
					}?>
					<?
					echo dickerview_tem001($row->etctype,number_format($row->sellprice)." 원");
					if ($_data->ETCTYPE["MAINSOLD"]=="Y" &&  ($row->quantity=="0" || (count($check_optin)=='0' && $check_optea))) echo soldout();
					if($row->consumerprice) echo "</font>";
					?>
				</p>
				</li>

<?php	$i++;}?>
				
</ul>






</div>
	<div class="paging">
		
		<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
		
	</div>


	</div><!-- //end contents -->
</div><!-- //end container -->

