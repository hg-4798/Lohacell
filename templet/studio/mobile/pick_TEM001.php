<?php
$sql="select * from tblpick where hidden = 1 order by sort asc, no desc";
$result =pmysql_query($sql);
$img_path = "/data/shopimages/pick/";
?>
			
	<div class="sub-title">
		<h2>SNS 핫이슈 아이템을 만나보세요</h2>
		<a class="btn-prev" href="javascript:history.go(-1);"><img src="./static/img/btn/btn_page_prev.png" alt="이전 페이지"></a>
	</div>
	
	<div class="promotion-area">
	<?while( $row = pmysql_fetch_array($result) ){?>
		<ul class="pick-list">
			<li>
				<?php if($row['samechk_link']){?>
				<a href="<?= str_replace("/front/","/m/",$row['link'])?>">
				<?php } else {?>	
				<a href="<?= $row['link_m']?>">
				<?php }?>
				<?php if($row['samechk_img']){?>
					<div class="thumb"><img src="<?= $img_path.$row['img']?>" alt="PICK 이미지"></div>
				<?php } else {?>				
					<div class="thumb"><img src="<?= $img_path.$row['img_m']?>" alt="PICK 이미지"></div>
				<?php }?>
					<div class="caption">
						<p class="tit"><?= $row['title'] ?></p>
						<p class="comment"><?= $row['subtitle'] ?></p>
					</div>
				</a>
			</li>
		</ul>
		<?php }?>
	</div>
