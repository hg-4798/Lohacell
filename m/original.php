<?php
$Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once($Dir."lib/shopdata.php");

include('./include/top.php');
include('./include/gnb.php');
?>

<div id="page">
	<section class="page_local is-line">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>정품인증 안내</span>
		</h2>
	</section>
	
	<div class="original_wrp">
		<h3>i KNOW iONE의<br>홀로그램<br><strong>정품 인증 방법</strong></h3>
		<div class="img"><span class="holo"><img src="/jayjun/m/static/img/content/hologram_img01.png" alt="홀로그램"><p>&lt; 홀로그램 실사 &gt;</p></span><span class="goods"><img src="/jayjun/m/static/img/content/hologram_img02.jpg" alt="홀로그램"></span></div>
		<div class="about">홀로그램이란 각도에 따라 무늬와 색상이 변하도록 얇은 특수 필름으로 제작된 정품인증 솔루션입니다.<br>i KNOW iONE은 소비자들이 안심하고 제품을 사용할 수 있도록 모든 제품에 홀로그램 스티커를 부착하여 안전성을 강화하고 있습니다. 빛의 각도에 따라 나타나는 아이노아이원 로고와 제이준 로고로 정품 여부를 확인하세요.</div>
		<div class="check">
			<p class="tit">홀로그램 확인 방법</p>
			<ul class="list">
				<li>
					<img src="/jayjun/m/static/img/content/hologram_check01.jpg" alt="정면일 때">
					<p class="txt">정면일 때<br>(두 개의 로고가 함께 노출)</p>
				</li>
				<li>
					<img src="/jayjun/m/static/img/content/hologram_check02.jpg" alt="각도 1">
					<p class="txt">각도 1<br>(한 개의 로고만 노출)</p>
				</li>
				<li>
					<img src="/jayjun/m/static/img/content/hologram_check03.jpg" alt="각도 2">
					<p class="txt">각도 2<br>(다른 한 개의 로고만 노출)</p>
				</li>
			</ul>
		</div>
	</div>
	
</div>

<?php
include('./include/bottom.php');
?>