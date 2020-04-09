
<!-- 상세페이지 -->
<div class="main_wrap">
	<div class="container">

	<div class="bd_box_wrap">
		<div class="bd_box">
			<table cellpadding="0" cellspacing="0" width="100%">
				<colgroup>
					<col width="50" /><col width="30" /><col width="" /><col width="100" />
				</colgroup>
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text1.gif" border="0"></td>
					<td></td>
					<td>

					<table cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_icon1.gif" border="0"></td>
						<td width="100%"><select name=code_a style="width:183px" onchange="SearchChangeCate(this,1)" style="font-size:11px;">
							<option value="">--- 1차 카테고리 선택 ---</option>
							</select>
							<select name=code_b style="width:183px" onchange="SearchChangeCate(this,2)" style="font-size:11px;">
							<option value="">--- 2차 카테고리 선택 ---</option>
							</select></td>
					</tr>
					<TR>
						<TD></td>
						<td><select name=code_c style="width:183px;" onchange="SearchChangeCate(this,3)" style="font-size:11px;">
							<option value="">--- 3차 카테고리 선택 ---</option>
							</select>
							<select name=code_d style="width:183px" style="font-size:11px;">
							<option value="">--- 4차 카테고리 선택 ---</option>
							</select></td>
					</tr>
					<tr>
						<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_icon2.gif" border="0"></td>
						<td width="100%"><input type=text name=minprice value="<?=$minprice?>" style="WIDTH: 175px" onkeyup="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> <b><span style="font-size:13pt;">~</span></b> <input type=text name=maxprice value="<?=$maxprice?>" style="WIDTH: 175px" onkeyup="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
					</tr>
					<tr>
						<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_icon3.gif" border="0"></td>
						<td width="100%"><select name=s_check style="width:90px;" style="font-size:11px;">
							<option value="all" <?if($s_check=="all")echo"selected";?>>통합검색</option>
							<option value="keyword" <?if($s_check=="keyword")echo"selected";?>>상품명/키워드</option>
							<option value="code" <?if($s_check=="code")echo"selected";?>>상품코드</option>
							<option value="selfcode" <?if($s_check=="selfcode")echo"selected";?>>진열코드</option>
							<option value="production" <?if($s_check=="production")echo"selected";?>>제조사</option>
							<option value="model" <?if($s_check=="model")echo"selected";?>>모델명</option>
							<option value="content" <?if($s_check=="content")echo"selected";?>>상세설명</option>
							</select> <input type=text name=search value="<?=$search?>" style="WIDTH: 277px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
					</tr>
					</table>

					</td>
					<td><a href="javascript:CheckForm();" class="btn_search01">검색하기</a></td>
				</tr>
				</table>
				<script>SearchCodeInit("<?=$code_a?>","<?=$code_b?>","<?=$code_c?>","<?=$code_d?>");</script>
		</div>
	</div>

	<div class="main_product_list">
		<h1 class="sub_name">총 검색상품 : <?=$t_count?>건 
		<p>
			<select name=listnum onchange="ChangeListnum(this.value)" style="font-size:11px;">
			<option value="20"<?if($listnum==20)echo" selected";?> style="color:#444444;">20개씩 정렬
			<option value="40"<?if($listnum==40)echo" selected";?> style="color:#444444;">40개씩 정렬
			<option value="60"<?if($listnum==60)echo" selected";?> style="color:#444444;">60개씩 정렬
			<option value="100"<?if($listnum==100)echo" selected";?> style="color:#444444;">100개씩 정렬
			</select>
		</p>
		</h1>
		<div class="list">
			<ul>
<?php
		$tag_0_count = 2; //전체상품 태그 출력 갯수
		//번호, 사진, 상품명, 제조사, 가격
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",CASE WHEN a.reservetype='N' THEN CAST(a.reserve AS FLOAT)*1 ELSE CAST(a.reserve AS FLOAT)*a.sellprice*0.01 END AS reservesort ";
		}
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
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
		else $sql.= "ORDER BY a.productname ";
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		$i=0;
		while($row=pmysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
			if (strlen($row->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->maximage)) { 
?>

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
				</li>
<?php		} else { ?>
				<li><img src="<?=$Dir?>images/no_img.gif" width=240 height=240></li>
<?php		}
				
		}?>
				
			</ul>
		</div>
	</div>



	<div class="page_wrap">
		<div class="page">
		<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
		</div>
	</div>
	

	</div>
</div>