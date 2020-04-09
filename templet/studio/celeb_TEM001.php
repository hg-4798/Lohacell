<?
/*
$sql= "select * from tblmainbannerimg where banner_no = '120' and banner_hidden ='1' order by banner_sort asc ";
$result =pmysql_query($sql);

$celeb = "";

while( $row = pmysql_fetch_array($result) ){
	$celeb[ $row['no'] ] = $row; 
}

$pr_sql = "
	select a.no, b.productcode, c.productname,c.sellprice,c.consumerprice,c.tinyimage,c.mdcomment,d.brandname 
	from tblmainbannerimg a
	left join tblmainbannerimg_product b on b.tblmainbannerimg_no = a.no
	left join tblproduct c on c.productcode = b.productcode
	left join tblproductbrand d on d.bridx = c.brand 
	where a.banner_no = '120' and banner_hidden ='1' ";

$pr_result = pmysql_query($pr_sql);

$celeb_pr = "";
while( $row = pmysql_fetch_array( $pr_result ) ){
	$celeb_pr[$row['no'] ][] = $row;
}
*/

$sql="select * from tblceleb where hidden = 1 order by sort asc, no desc ";
$result =pmysql_query($sql);


$img_path = "/data/shopimages/celeb/";
$img_pr = "/data/shopimages/product/";
/*
p.s 배너 링크는 삽입 안하였습니다. 배너 링크 삽입하여 마무리 하세요.
*/
?>

<div id="contents">
	<div class="containerBody sub-page">
		<div class="promotion-wrap">

			<?// include ($Dir.TempletDir."studio/navi_TEM001.php"); ?>
			<div class="breadcrumb studio-top">
				<ul>
					<li><a href="#">HOME</a></li>
					<li class="on"><a href="/front/celeb.php">CELEB</a></li>
				</ul>
			</div><!-- //.breadcrumb -->

			<div class="star-press-list-wrap">
				<!-- celeb-area -->
				<div class="celeb-area-wrap">
					<div class="celeb-area">
						<p style="text-align:center;"><img src="../static/img/banner/pc_celeb.jpg" alt="JayJun with THE STAR SNS 핫이슈 셀럽착용 아이템을 만나보세요." /></p>
						<ul class="celeb-list">
						<?while( $row = pmysql_fetch_array($result) ){
							$arrProductCodes = explode("||", $row['productcodes']);
							?>
							<li>
								<div class="celeb-description">
									<a href="<?=$row['link']?>"><img src="<?=$img_path.$row['img']?>" class="celeb-figure" alt="" /></a>
									<strong><?=$row['title']?></strong>
									<span><?=$row['subtitle']?></span>
								</div>
								<ul class="celeb-purchasing">
								<?foreach($arrProductCodes as $prcode ){
									$pr_sql = "
										select * from 
										tblproduct p
										left join tblproductbrand pb on pb.bridx = p.brand 
										where p.productcode='".$prcode."' ";

									$pr_result = pmysql_query($pr_sql);
									$pr_data = pmysql_fetch_array($pr_result);
								?>	
									<?//debug($val2);?>
									<li style="cursor:pointer;" onclick="location.href='/front/productdetail.php?productcode=<?=$pr_data['productcode']?>';">
										<img src="<?=$img_pr.$pr_data['tinyimage']?>" class="celeb-figure" alt="" />
										<em><?=$pr_data['brandname']?></em>
										<span class="celeb-sale"><div class="ellipsis"><?=$pr_data['mdcomment']?></div><div class="ellipsis"><?=$pr_data['productname']?></div></span>
										<?php if($pr_data['consumerprice'] == $pr_data['sellprice']) {?>
										<span><i><?=number_format( $pr_data['sellprice'] )?></i></span>
										<?php } else {?>										
										<span><del><?=number_format( $pr_data['consumerprice'] )?></del><i><?=number_format( $pr_data['sellprice'] )?></i></span>
										<?php }?>
									</li>
								<?}?>
								</ul>
							</li>
						<?}?>
						</ul>
					</div>
				</div>
				<!-- /celeb-area -->
				<!-- <div class="btn-more-wrap"><button class="btn-more">더보기</button></div> -->
			</div><!-- //.star-press-list-wrap -->
		
		</div><!-- //.promotion-wrap -->

	</div><!-- //.containerBody -->
</div><!-- //contents -->

<?/*?>
<div id="contents">
	<div class="containerBody sub-page">
		<div class="promotion-wrap">

			<? include ($Dir.TempletDir."studio/navi_TEM001.php"); ?>
			<div class="star-press-list-wrap">
				<!-- celeb-area -->
				<div class="celeb-area">
					<p><img src="../static/img/banner/bnn-celeb-top.jpg" alt="JayJun with THE STAR SNS 핫이슈 셀럽착용 아이템을 만나보세요." /></p>
					<ul class="celeb-list">
					<?foreach($celeb as $key=> $val ){?>
						<li>
							<div class="celeb-description">
								<a href=""><img src="<?=$img_path.$val['banner_img']?>" class="celeb-figure" alt="" /></a>
								<strong><?=$val['banner_name']?></strong>
								<span><?=$val['banner_subname']?></span>
							</div>
							<ul class="celeb-purchasing">
							<?foreach($celeb_pr[$key] as $key2=>$val2 ){?>	
								<?//debug($val2);?>
								<li>
									<a href="/front/productdetail.php?productcode=<?=$val2['productcode']?>">
										<img src="<?=$img_pr.$val2['tinyimage']?>" class="celeb-figure" alt="" />
									</a>
									<em><?=$val2['brandname']?></em>
									<span class="celeb-sale"><div class="ellipsis"><?=$val2['mdcomment']?></div><div class="ellipsis"><?=$val2['productname']?></div></span>
									<span><del><?=number_format( $val2['consumerprice'] )?></del><i><?=number_format( $val2['sellprice'] )?></i></span>
								</li>
							<?}?>
							</ul>
						</li>
					<?}?>
					</ul>
				</div>
				<!-- /celeb-area -->
				<!-- <div class="btn-more-wrap"><button class="btn-more">더보기</button></div> -->
			</div><!-- //.star-press-list-wrap -->
		
		</div><!-- //.promotion-wrap -->

	</div><!-- //.containerBody -->
</div><!-- //contents -->
<?*/?>