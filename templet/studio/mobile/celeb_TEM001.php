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
$sql="select * from tblceleb where hidden = 1 order by sort asc, no desc";
$result =pmysql_query($sql);


$img_path = "/data/shopimages/celeb/";
$img_pr = "/data/shopimages/product/";
/*
 p.s 배너 링크는 삽입 안하였습니다. 배너 링크 삽입하여 마무리 하세요.
 */
?>

<?php include ($Dir.TempletDir."studio/mobile/navi_TEM001.php"); ?>

<div class="studio-star-list">
	<!-- celeb-area -->
	<div class="celeb-area">
		<!--<p><img src="../static/img/banner/bnn-celeb-top.jpg" alt="JayJun with THE STAR SNS 핫이슈 셀럽착용 아이템을 만나보세요." /></p>-->
		<ul class="celeb-list">
		<?while( $row = pmysql_fetch_array($result) ){
			$arrProductCodes = explode("||", $row['productcodes']);
			?>
			<li>
				<div class="celeb-description">
				<?php if($row['samechk_link']){?>
				<a href="<?= str_replace("/front/","/m/",$row['link'])?>">
				<?php } else {?>
					<a href="<?=$row['link_m']?>">
					<?php }?>
				<?php if($row['samechk_img']){?>								
					<img src="<?=$img_path.$row['img']?>" alt="" />
					<?php } else {?>
					<img src="<?=$img_path.$row['img_m']?>" alt="" />
					<?php }?>
					</a>
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
				<li style="cursor:pointer;" onclick="location.href='/m/productdetail.php?productcode=<?=$pr_data['productcode']?>';">
						<img src="<?=$img_pr.$pr_data['tinyimage']?>" class="celeb-figure" alt="" />
						<em><?=$pr_data['brandname']?></em>
						<span class="celeb-sale"><?=$pr_data['mdcomment']?></span>
						<span class="celeb-sale"><?=$pr_data['productname']?></span>
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
			<!--
			<li>
				<div class="celeb-description">
					<img src="../static/img/test/@celeb-image2.jpg" alt="" />
					<strong>바다의 여성스러움이 돋보이는<br />원피스 알고싶어요</strong>
					<span>플리츠 디테일이 여성스러움을 돋보이게 하는 원피스 입니다.<br />화이트 컬러와 우아하고 여성스러운 실루엣이 유니크한 아이템입니다.</span>
				</div>
				<ul class="celeb-purchasing">
					<li>
						<img src="../static/img/test/@celeb-image-small2.jpg" alt="" />
						<em>SCULPTOR</em>
						<span class="celeb-sale">[SPECIAL SALE 10%]<br />CAMP CREW DENIM SHORTS [DENIM]</span>
						<span><del>62,000</del><i>53,010</i></span>
					</li>
					<li>
						<img src="../static/img/test/@celeb-image-small2.jpg" alt="" />
						<em>SCULPTOR</em>
						<span class="celeb-sale">[SPECIAL SALE 10%]<br />CAMP CREW DENIM SHORTS [DENIM]</span>
						<span><del>62,000</del><i>53,010</i></span>
					</li>
					<li>
						<img src="../static/img/test/@celeb-image-small2.jpg" alt="" />
						<em>SCULPTOR</em>
						<span class="celeb-sale">[SPECIAL SALE 10%]<br />CAMP CREW DENIM SHORTS [DENIM]</span>
						<span><del>62,000</del><i>53,010</i></span>
					</li>
				</ul>
			</li>
			-->
		</ul>
	</div>
	<!-- /celeb-area -->
</div>

<!-- 프레스 리스트 -->
<?/*?>
<div class="studio-star-list">
	<!-- celeb-area -->
	<div class="celeb-area">
		<p><img src="../static/img/banner/bnn-celeb-top.jpg" alt="JayJun with THE STAR SNS 핫이슈 셀럽착용 아이템을 만나보세요." /></p>
		<ul class="celeb-list">
		<?foreach($celeb as $key=> $val ){?>	
			<li>
				<div class="celeb-description">
					<a href=""><img src="<?=$img_path.$val['banner_img_m']?>" alt="" /></a>
					<strong><?=$val['banner_name']?></strong>
					<span><?=$val['banner_subname']?></span>
				</div>
				<ul class="celeb-purchasing">
				<?foreach($celeb_pr[$key] as $key2=>$val2 ){?>
					<li>
						<a href="/m/productdetail.php?productcode=<?=$val2['productcode']?>">
							<img src="<?=$img_pr.$val2['tinyimage']?>" class="celeb-figure" alt="" />
						</a>
						<em><?=$val2['brandname']?></em>
						<span class="celeb-sale"><?=$val2['mdcomment']?><br /><?=$val2['productname']?></span>
						<span><del><?=number_format( $val2['consumerprice'] )?></del><i><?=number_format( $val2['sellprice'] )?></i></span>
					</li>
				<?}?>
				</ul>
			</li>
		<?}?>
			<!--
			<li>
				<div class="celeb-description">
					<img src="../static/img/test/@celeb-image2.jpg" alt="" />
					<strong>바다의 여성스러움이 돋보이는<br />원피스 알고싶어요</strong>
					<span>플리츠 디테일이 여성스러움을 돋보이게 하는 원피스 입니다.<br />화이트 컬러와 우아하고 여성스러운 실루엣이 유니크한 아이템입니다.</span>
				</div>
				<ul class="celeb-purchasing">
					<li>
						<img src="../static/img/test/@celeb-image-small2.jpg" alt="" />
						<em>SCULPTOR</em>
						<span class="celeb-sale">[SPECIAL SALE 10%]<br />CAMP CREW DENIM SHORTS [DENIM]</span>
						<span><del>62,000</del><i>53,010</i></span>
					</li>
					<li>
						<img src="../static/img/test/@celeb-image-small2.jpg" alt="" />
						<em>SCULPTOR</em>
						<span class="celeb-sale">[SPECIAL SALE 10%]<br />CAMP CREW DENIM SHORTS [DENIM]</span>
						<span><del>62,000</del><i>53,010</i></span>
					</li>
					<li>
						<img src="../static/img/test/@celeb-image-small2.jpg" alt="" />
						<em>SCULPTOR</em>
						<span class="celeb-sale">[SPECIAL SALE 10%]<br />CAMP CREW DENIM SHORTS [DENIM]</span>
						<span><del>62,000</del><i>53,010</i></span>
					</li>
				</ul>
			</li>
			-->
		</ul>
	</div>
	<!-- /celeb-area -->
</div>
<?*/?>