<? /* include "_header.html"; 파일없음 */ ?>
<style>
div.cart_wrap table.list_table td div.updatebtn{width:31px;display:inline-block;float:right;margin-left:-15px;}
</style>
<!-- start container -->

<script>
$(function(){
	//중간 상품 레이어
	var middle_goods = $('ul.main_middle_goods li div.goods_img');
	var middle_goods_layer = $('div.layer_goods_info');

	middle_goods.mouseenter(function(e){
		//middle_goods_layer.show();
		$(this).parent().find(middle_goods_layer).show();
		//$(this).find(middle_goods_layer).show();
	});
	middle_goods_layer.mouseleave(function(){
		//middle_goods_layer.hide();
	});	
 
})
</script>

<div id="container">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<!-- 메인 컨텐츠 -->
			<div class="main_wrap">


				<div class="cart_wrap">
					 
					<!-- 담은 상품 -->
					 
					<!-- // 담은 상품 -->
<!-- // 담은 상품 --><!-- // 담은 상품 --><!-- // 담은 상품 --><!-- // 담은 상품 --><!-- // 담은 상품 --><!-- // 담은 상품 --><!-- // 담은 상품 --><!-- // 담은 상품 -->
					<?
						$sqlVcount = "
							SELECT a.* ,c.sellprice as group_sellprice,c.consumerprice as group_consumerprice 
							FROM tblproduct a
							LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode 
							LEFT OUTER JOIN (SELECT * FROM tblmembergroup_price WHERE group_code = '{$_ShopInfo->memgroup}' ) c 
							ON a.productcode = c.productcode
							WHERE a.display = 'Y' 
							order by a.vcnt desc 
							limit 4 offset 0 ";
						$resultVcount=pmysql_query($sqlVcount,get_db_conn());
					?>

<!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- -->
		<div class="middle_content">
			<ul class="main_middle_goods">
<?php
			$mainproduct_sql = "
				SELECT 
				a.icon 
				,b.productcode 
				,b.productname 
				,b.sellprice 
				,b.consumerprice 
				,b.maximage 
				,d.sellprice as group_sellprice
				,d.consumerprice as group_consumerprice
				FROM tblmainlist a 
				JOIN tblproduct b ON a.pridx = b.pridx 
				LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode 
				LEFT OUTER JOIN (SELECT * FROM tblmembergroup_price WHERE group_code = '{$_ShopInfo->memgroup}' ) d 
				ON b.productcode = d.productcode 
				ORDER BY a.sort ASC 
				OFFSET 0 LIMIT 3 
			";
			$mainproduct_res = pmysql_query($mainproduct_sql,get_db_conn());
			while($mainproduct_row = pmysql_fetch_array($mainproduct_res)){	
				if ($mainproduct_row[group_sellprice]) {
					$mainproduct_row[sellprice] = $mainproduct_row[group_sellprice];
					}
				if ($mainproduct_row[group_consumerprice]) {
					$mainproduct_row[consumerprice] = $mainproduct_row[group_consumerprice];
				}
?>
				<li>
					<div class="goods_img">
						<div class="layer_goods_info">
							<span class="subject"><?=$mainproduct_row[productname]?></span>
							<span class="price_oiginal"><?=number_format(exchageRate($mainproduct_row[consumerprice]))?>원</span>
							<span class="price"><?=number_format(exchageRate($mainproduct_row[sellprice]))?>원</span>
							<p class="btn">
								<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$mainproduct_row[productcode]?>" class="btn_C">자세히보기</a><a  class="btn_C  busket" alt="<?=$mainproduct_row[productcode]?>"  href="javascript:CheckForm('','<?=$mainproduct_row[productcode]?>');">장바구니</a>
							</p>
						</div>					
						<img src="<?=$Dir.DataDir?>shopimages/product/<?=$mainproduct_row[maximage]?>" alt="" />
					</div>
				</li>
<?php		}	?>
			</ul>
			 
		</div>				<!-- //div.middle_content -->
<!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- --><!-- -->
<!-- -->

<div class="middle_content">
	<ul class="main_middle_goods">
				<h3>추천!<span>다른 고객님들이 많이 구입하신 상품들</span></h3>
						 
							<?
								while($row=pmysql_fetch_object($resultVcount)) {
									if ($row->group_sellprice) {
									$row->sellprice = $row->group_sellprice;
									}
									if ($row->group_consumerprice) {
										$row->consumerprice = $row->group_consumerprice;
									}
									$sellPrice = 0;
									$optcode = substr($row->option1,5,4);
									$miniq = 1;
									if (ord($row->etctype)) {
										$etctemp = explode("",$row->etctype);
										for ($i=0;$i<count($etctemp);$i++) {
											if (strpos($etctemp[$i],"MINIQ=")===0) $miniq=substr($etctemp[$i],6);
										}
									}

									if(strlen($dicker=dickerview($row->etctype,number_format($row->sellprice),1))>0){
										$sellPrice = $dicker;
									} else if(strlen($optcode)==0 && strlen($row->option_price)>0) {
										$sellPrice = $row->sellprice;
									} else if(strlen($optcode)>0) {
										$sellPrice = $row->sellprice;
									} else if(strlen($row->option_price)==0) {
										if($row->assembleuse=="Y") {
											$sellPrice = ($miniq>1?$miniq*$row->sellprice:$row->sellprice);
										} else {
											$sellPrice = $row->sellprice;
										}
									}
							?>
					<li>
							<div class="goods_img"  alt="<?=$row->productcode?>" >
								<div class="layer_goods_info">
									<span class="subject"><?=$row->productname?></span>
									<span class="price_oiginal"><?=number_format(exchageRate($row->consumerprice))?>원</span>
									<span class="price"><?=number_format(exchageRate($row->sellprice))?>원</span>
									<p class="btn">
										<a  href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row->productcode?>" class="btn_C">자세히보기</a>
										<a  class="btn_C  busket" 
											alt="<?=$row->productcode?>"  href="javascript:CheckForm('','<?=$row->productcode?>');">장바구니</a>
									</p>
								</div>					
								<?if(is_file($Dir."img/icon/".$row->icon)){?>
										<img style="max-width:240px;"  src="<?=$Dir?>img/icon/<?=$row->icon?>" alt="" />
									<?}?>
									 
									<?if(is_file($Dir.DataDir."shopimages/product/".$row->maximage)){?>
										<img style="max-width:240px;" src="<?=$Dir.DataDir?>shopimages/product/<?=$row->maximage?>" alt="" />
									<?}else{?>
										<img style="max-width:240px; width:240px;" src="<?=$Dir?>images/no_img.gif" alt="" />
									<?}?>
							</div>						 
				</li>
<?}?>

	</ul>			 
</div>				<!-- //div.middle_content -->
		
		
		<!-- 추천 -->
					 
					<!-- // 추천 -->

					<!-- 무이자 할부혜택 -->
			 
					<!-- // 무이자 할부혜택 -->
				</div>

			</div>
			<!-- //메인 컨텐츠 -->
		</td>
	</tr>
	</table>
</div>
 