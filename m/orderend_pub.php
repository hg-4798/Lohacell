<?php
if(strlen($Dir)==0) $Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once(DOC_ROOT."/lib/shopdata.php");
include_once(DOC_ROOT."/lib/basket.class.php");
include_once(DOC_ROOT."/lib/delivery.class.php");

include('./include/top.php');
include('./include/gnb.php');
?>

<div id="page">
<!-- 내용 -->
<main id="content" class="subpage">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>주문/결제</span>
		</h2>
		<div class="page_step">
			<ul class="clear">
				<li><span class="icon_order_step01"></span>장바구니</li>
				<li><span class="icon_order_step02"></span>주문하기</li>
				<li class="on"><span class="icon_order_step03"></span>주문완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="orderpage">
		<div class="order-end-box">
			<h3 class="title">감사합니다!<br>고객님의 주문이 정상적으로 완료되었습니다.</h3>
			<em class="point-color">20180118213841381681A</em>
			<p>주문은 <a href="">마이페이지 &gt; 주문조회</a>를 이용해 주시기 바랍니다.<br>
			주문/배송 관련 문의사항은 <a href="">마이페이지 &gt; 1:1문의</a>를 이용해 주시기 바랍니다.</p>
		</div>

		<div class="title-section is-alone with-border"><h4 class="tit">결제방법</h4></div>
		<ul class="payment-result">
			<li class="type">무통장입금(가상계좌)</li>
			<li><em class="txt-toneA">국민은행</em> 39919011059152 / 게스홀딩스코리아 <span class="txt-toneC">(입금대기중)</span></li>
			<li>입금기한 : 2018-01-22 <span class="txt-toneC">|</span> 23:59:00</li>
			<!-- <li class="type">신용카드</li>
			<li><em class="emphasis-color">KB국민카드</em> <span class="txt-toneC">[무이자 10 개월]</span></li> -->
		</ul>

		<div class="title-section is-alone with-border"><h4 class="tit">결제정보</h4></div>
		<div class="price-sum-total is-confirm">
			<div class="inner">
				<dl>
					<dt>총 상품금액</dt>
					<dd><strong class="emphasis-color">541,600</strong> <span>원</span></dd>
				</dl>
				<dl>
					<dt>할인</dt>
					<dd class="discount-color">- 36,720 원</dd>
				</dl>
				<dl>
					<dt>쿠폰할인</dt>
					<dd class="discount-color">- 0 원</dd>
				</dl>
				<dl>
					<dt>마일리지 사용</dt>
					<dd class="discount-color">- 0 원</dd>
				</dl>
				<dl>
					<dt>포인트 사용</dt>
					<dd class="discount-color">- 0 원</dd>
				</dl>
				<dl>
					<dt>배송비</dt>
					<dd><span class="txt-toneA">+ 0</span> <span>원</span></dd>
				</dl>
				<dl class="total">
					<dt>총 결제금액</dt>
					<dd><strong class="emphasis-color">504,880</strong> <span>원</span><p class="mileage">(적립 마일리지 <b>6,900M</b>)</p></dd>
				</dl>
			</div>
		</div>

		<div class="box-sector no-line mt-10">
			<div class="sector-inner">
				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">주문/배송 조회</a></li>
						<li><a href="javascript:;" class="btn-point h-large">쇼핑계속하기</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section><!-- //.orderpage -->
</main>
<!-- //내용 -->

<? include('./include/bottom.php'); ?>
