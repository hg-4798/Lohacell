<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
?>

<?php include($Dir.MainDir.$_data->menu_type.".php") ?>

<div id="contents">
	<div class="inner_width">
		<ul class="breadcrumb">
			<li>
				<a href="/">HOME</a>
			</li>
			<li>
				<a href="">BRAND</a>
			</li>
			<li class="now">
				<a href="">매장정보</a>
			</li>
		</ul>
		<div class="sub_tit_area">
			<h2 class="title">매장정보</h2>
		</div>

		

	</div>
</div>

<div id="create_openwin" style="display:none"></div>

<?php  include ($Dir."lib/bottom.php") ?>
<?=$onload?>
</BODY>
</HTML>

