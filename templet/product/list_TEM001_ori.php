<?
include_once dirname(__FILE__)."/../../lib/product.class.php";
if(!$product) $product = new PRODUCT();

?>    

<!-- 메인 컨텐츠 -->
<div class="main_wrap">
		
	<!-- 라인맵&타이틀 -->
	<?php
		$sql = "SELECT * FROM tblproductcode where code_a='{$code_a}' AND type='L'";
		$result_title = pmysql_query($sql);
		$result_row = pmysql_fetch_array($result_title);
		$title_a = $result_row['code_name'];
		pmysql_free_result($result_row);
		
		if($code_b!="000"){
			$sql = "SELECT * FROM tblproductcode where code_a='{$code_a}' AND code_b='{$code_b}' AND code_c='000'";
			$result_title = pmysql_query($sql);
			$result_row = pmysql_fetch_array($result_title);
			$title_b = $result_row['code_name'];
			pmysql_free_result($result_row);
		}
	?>
	<div class="category_item_sort">
		<h3>
			<?=$title_a?>
			<?php 	if($title_b){	?>
			<span class="depth_two"> > <?=$title_b?> </span>
			<?php	}	?>
			<span class="total">(159)</span></h3>
		<div class="line_map">

			홈 > 
			<div class="select_type open ta_l" style="width:150px; z-index:70">
				<span class="ctrl"><span class="arrow"></span></span>
				<button type="button" class="myValue">WOMEN'S SHOE</button>
				<ul class="aList">
				<?
				$a_sql = "SELECT * FROM tblproductcode WHERE group_code!='NO' ";
				$a_sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') and code_b='000' ORDER BY cate_sort ";
				$a_result=pmysql_query($a_sql);
				while($a_data=pmysql_fetch_object($a_result)){
					$a_checked[$code_a]="selected";
				?>
					<li><a href="#1">Link_1</a></li>
				<?
				}
				?>
				</ul>
			</div>
			>
			<div class="select_type open ta_l" style="width:150px; z-index:70">
				<span class="ctrl"><span class="arrow"></span></span>
				<button type="button" class="myValue">WOMEN'S SHOE</button>
				<ul class="aList">
					<li><a href="#1">Link_1</a></li>
					<li><a href="#2">Link_2</a></li>
					<li><a href="#3">Link_3</a></li>
				</ul>
			</div>

		</div>
	</div><!-- //라인맵&타이틀 -->

	<!-- 조회 -->
	<div class="category_sort">
		<div class="item_sort">
			<p>아이템별</p>
			<ul class="item">
			<?php
				$sql = "SELECT * FROM tblproductitem ORDER BY itemname";
				$result = pmysql_query($sql);
				
				#none class를 넣기 위한 작업
				$inone = 1;//none class를 넣기 위한 변수
				$row_num = pmysql_num_rows($result);
				$tot_line = ceil($row_num/6);
				$none[$tot_line]='class="none"';
				#//none class를 넣기 위한 작업
				
				$iu=0;//ul태그를 만들기 위한 변수
				while($i_row = pmysql_fetch_array($result)){
					
					//중간에 ul 태그 만들기
					if($iu==6){
						$inone++;
						$iu=0;
			?>
			</ul>
			<ul class="item">
			<?php
					}//ul 태그 만들기 끝 
			?>
				<li <?=$none[$inone]?>><a href="javascript:searchItemCate(<?=$i_row['itidx']?>)"><?=$i_row['itemname']?></a></li>
			<?php
					$iu++;
				}
				pmysql_free_result($result);
			?>
			</ul>
		</div>
		<div class="brand_sort">
			<p>브랜드별</p>
			<ul class="brand">
			<?php
				$checked_str[$brand]="checked";
				$sql = "SELECT * FROM tblproductbrand ORDER BY brandname";
				$result = pmysql_query($sql);
				$ich = 0;
				while($b_row = pmysql_fetch_array($result)){
			?>
				<li>
					<input type="radio" name="brand" id="" <?=$checked_str[$b_row['bridx']]?> onclick="searchBrand(<?=$b_row['bridx']?>)"/> 
					<?=$b_row['brandname']?>
				</li>
			<?php
					$ich++;
				}
				pmysql_free_result($result);
			?>
			</ul>
		</div>
	</div><!-- //조회 -->

	<!-- 베스트아이템 --> 
	<?php
		#####베스트 아이템에는 판매순 상품을 진열한다.
		//판매순 상품 쿼리
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, a.option1, a.option2, a.option_quantity, ";
		if($_cdata->sort=="date2") $sql.="CASE WHEN a.quantity<=0 THEN '11111111111111' ELSE a.date END as date, ";
		$sql.= "a.maximage, a.tinyimage, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= $addsortsql;
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}

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
		
		$sql.= "limit 5";
		
		$result=pmysql_query($sql,get_db_conn());
		$num_rows = pmysql_num_rows($result);
		if($num_rows){
	
	?>
	<div class="main_best_item_wrap">
		<div class="container">
			<div class="title">
				<h3>BEST ITEMS</h3>
			</div>
		</div>
		<div class="goods_list_four_wrap">
			<div class="four_arrow">
				<a href="#" class="best_w_left best_slider_btn" data-target="next">왼쪽</a>
				<a href="#" class="best_w_right best_slider_btn" data-target="prev">오른쪽</a>
			</div>
			<div class="container">
				<div id="productListBest">
					<ul class="four">
					

					<?php
						$cnti=1;
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
							
							//상품 이미지		
							if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)) {
								$imgsrc = $Dir.DataDir."shopimages/product/".urlencode($row->tinyimage);
							}else{
								$imgsrc = $Dir."images/no_img.gif";
							}
					?>

						<li>
							<div class="number"><?=$cnti++?></div>
							<a href="<?=$Dir.FrontDir."productdetail_test.php?productcode=".$row->productcode?>">
								<img src="<?=$imgsrc?>" alt="" />
							</a>
							<div class="goods_info">
								<?=viewproductname($row->productname,$row->etctype,$row->selfcode)?><br />
								<?php	if($row->consumerprice){	?>
								<span class="original"><?=number_format($row->consumerprice)?></span>
								<?php	}	?>
								<span class="off"><?=number_format($row->sellprice)?>원</span>
							</div>
						</li>
					<?php
						}
					?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
		}
	?>
	<!-- //베스트아이템 -->

	<!-- best review & focus on -->
	<div class="review_focus_wrap">
		<!-- REVIEW -->
		<?php

			$where = "AND a.productcode like '".$likecode."%' ";
			
			$sql="SELECT b.minimage, a.id,a.name,a.reserve,a.display,a.content,a.date,a.productcode,a.upfile,b.productname,b.tinyimage,b.selfcode,
			b.assembleuse, a.best_type, a.marks FROM tblproductreview a, tblproduct b WHERE a.productcode = b.productcode {$where} 
			ORDER BY a.date DESC, marks limit 1";
			
			$res=pmysql_query($sql);
			$row_review=pmysql_fetch_array($res);

			$upfile = ($row_review['upfile'])?"board/reviewbbs/".$row_review['upfile']:"product/".$row_review['tinyimage'];
		?>
		<div class="list_best_review">
			<h3>best review</h3>
			<div class="best">
				<a href="#"><img src="<?=$Dir.DataDir."shopimages/".$upfile?>" alt="" /></a>
				<div class="list_best_info">
					<ul class="review_info">
						<li class="subject"><a href="#"><?=$row_review['productname']?></a></li>
						<li class="price">
						<?php	if($row_review['consumerprice']){	?>
							<span><?=number_format($row_review['consumerprice'])?> </span>
						<?php	}	?>
							<br />
						<?php	if($row_review['sellprice']){	?>
							<?=number_format($row_review['sellprice'])?> 원
						<?php	}	?>
						</li>
					</ul>
					<p class="review_info">
						<span class="star">
						<?php	if($row_review['marks']){	?>
							<?=review_mark($row_review['marks'])?>
						<?php	}	?>
						</span>
						<a href="#">
						<?php	if($row_review['content']){	?>
							<?=$row_review['content']?>
						<?php	}	?>
						</a>
					</p>
				</div>
			</div>
		</div><!-- //REVIEW -->

		<!-- FOCUS ON -->
		<div class="focus_on">
			<h3>focus on</h3>
			<div class="focus_on_bg">
				<ul class="focus_on_list">
				<?php
				$specail_disp_goods = specail_disp_goods_sub($likecode);	//	FOCUS ON 진열상품
				if($specail_disp_goods[4]){
					$ifocus=0;
					foreach($specail_disp_goods[4] as $k=>$v){
						if($v['productcode']&&$ifocus<4){
				?>
					<li>
						<ul class="focus_on_list_goods">
							<li class="goods"><a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$v['productcode']?>"><img src="<?=$Dir.DataDir."shopimages/product/".$v['tinyimage']?>" alt="" style="width:180px;height: 180px;"/></a></li>
							<li class="goods_info">
								<span class="name"><a href="#"><?=$v['productname']?></a></span>
								<span class="price">
								<?
									if($v['consumerprice']){
								?>
									<em><?=number_format($v['consumerprice'])?></em>
								<?php
									}
								?>
									<?=number_format($v['sellprice'])?>원</span>
								<div class="point">
									<?php	if($v['reserve']){	//적립금?>
									<img src="../img/icon/icon_p.gif" alt="" /> <?=number_format($v['reserve'])?>
									<?php	}	
											if($v['quantity']){
									?>
									<span class="dc_per"><?=$v['quantity']?>%</span>
									<?php	}	?>
								</div>
							</li>
						</ul>
					</li>
				<?php
						}
						$ifocus++;
					}
				}
				?>
				</ul>
			</div>
		</div><!-- //FOCUS ON -->

	</div>
	<!-- //best review & focus on -->

	<script type="text/javascript">
	$(function(){
		$('ul.tap_goods>li').mouseenter(function(){
		$(this).find('ul.goods_quick_icon').css('display','block');
		});
		$('ul.tap_goods>li').mouseleave(function(){
		$(this).find('ul.goods_quick_icon').css('display','none');
		});
	});
	</script>

	<!-- 탭메뉴 -->
	<?php
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
	$paging = new Tem001_saveheels_Paging($sql,10,$listnum,'GoPage',true);
	$t_count = $paging->t_count;
	$gotopage = $paging->gotopage;
	?>

	<div class="goods_list_wrap">
		<div class="goods_list_title">
			<h3><?=$_cdata->code_name?> <span> (Total <em><?=$t_count?></em>)</span></h3>
			<ul class="sort">
				<li><a href="javascript:ChangeSort('opendate')" <?=$sort_current["sort_current"][opendate]?>>신규등록순▼</a></li>
				<li><a href="javascript:ChangeSort('best')" <?=$sort_current["sort_current"][best]?>>인기판매순</a></li>
				<li><a href="javascript:ChangeSort('price_desc')" <?=$sort_current["sort_current"][price_desc]?>>높은가격순</a></li>
				<li><a href="javascript:ChangeSort('price')" <?=$sort_current["sort_current"][price]?>>낮은가격순</a></li>
				<li>
					<select id="listnum" name="listnum" onchange="javascript:ChangeSort('<?=$sort?>','listnum');">
						<option value="20" <?=$selected["listnum"][20]?>>20개씩 보기</option>
						<option value="40" <?=$selected["listnum"][40]?>>40개씩 보기</option>
						<option value="80" <?=$selected["listnum"][80]?>>80개씩 보기</option>
						<option value="120" <?=$selected["listnum"][120]?>>120개씩 보기</option>
					</select>
				</li>
			</ul>
		</div>
		<ul class="tap_goods">

<?

		//번호, 사진, 상품명, 제조사, 가격
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",CASE WHEN a.reservetype='N' THEN CAST(a.reserve AS FLOAT)*1 ELSE CAST(a.reserve AS FLOAT)*a.sellprice*0.01 END AS reservesort ";
		}
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, a.option1, a.option2, a.option_quantity, ";
		if($_cdata->sort=="date2") $sql.="CASE WHEN a.quantity<=0 THEN '11111111111111' ELSE a.date END as date, ";
		$sql.= "a.maximage, a.tinyimage, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
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
		
		//exdebug($sql);
		//exit;

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
			
			if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)) {
				$imgsrc = $Dir.DataDir."shopimages/product/".urlencode($row->tinyimage);
			}else{
				$imgsrc = $Dir."images/no_img.gif";
			}
			
			#장바구니를 위한 것들
			//장바구니를 위한 옵션
			
			if($row->option1){
				$opt1arr = explode(",",$row->option1);
				$opt1 = $opt1arr[1];
			}
			if($row->option2){
				$opt2arr = explode(",",$row->option2);
				$opt2 = $opt2arr[1];
			}
			
			//상품의 카테고리 코드
			$prd_cate_code = substr($row->productcode,0,12);
			
			$sell_price = ($row->sellprice)?$row->sellprice:"0";
			$consumerprice = ($row->consumerprice)?$row->consumerprice:"0";
			$option_reserve = ($row->option_reserve)?$row->option_reserve:"0";
			
?>

			<li>
				<ul class="goods_quick_icon">
					<li><a href="#" class="detail">자세히보기</a></li>
					<li><a href="javascript:basket('<?=$row->productcode?>')" class="cart">장바구니 담기</a></li>
				</ul>
				<a href="<?=$Dir.FrontDir."productdetail_test.php?productcode=".$row->productcode?>"><img src="<?=$imgsrc?>" alt="" /></a>
				<div class="goods_info">
					<?=viewproductname($row->productname,$row->etctype,$row->selfcode)?><br />
					
					<?php	if($row->consumerprice){	?>
					<span class="original"><?=number_format($row->consumerprice)?></span>
					<?php	}	?>
					
					<span class="off"><?=number_format($row->sellprice)?>원</span>
					<p>
					<?php	
						if($row->reserve){	
					?>
						<img src="../img/icon/icon_p.gif" alt="" /> <?=number_format($row->reserve)?>
					<?php	
						}
						if($row->quantity){
					?>
						<span class="dc_per"><?=number_format($row->quantity)?></span>
					<?php		
						}
					?>
					</p>
				</div>
				<form name="productbasket<?=$row->productcode?>" id="productbasket<?=$row->productcode?>">
					<input type="hidden" name="price" value="<?=number_format($sell_price)?>">
					<input type="hidden" name="sprice" value="<?=number_format($sell_price)?>">
					<input type="hidden" name="consumer" value="<?=$consumerprice?>">
					<input type="hidden" name="o_reserve" value="<?=$option_reserve?>">
					<input type="hidden" name="quantity" value="1">
					<input type="hidden" name="option1" value="<?=$opt1?>">
					<input type="hidden" name="option2" value="<?=$opt2?>">
					<input type="hidden" name="code" value="<?=$prd_cate_code?>">
					<input type="hidden" name="productcode" value="<?=$row->productcode?>">
					<input type="hidden" name="opts" value="">
				</form>
			</li>
<?php
		}
	}
?>
		</ul>
		<div class="page page_margin">
			<ul>
				<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
			</ul>
		</div>
	</div>
	<!-- //탭메뉴 -->

</div><!-- //메인 컨텐츠 -->