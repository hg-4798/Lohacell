<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>공지사항</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="cs_notice sub_bdtop">

		<div class="board_search">
			<div class="input_addr">
				<input type="text" name="">
				<div class="btn_addr"><a href="javascript:;" class="btn-point h-input">검색</a></div>
			</div>
		</div><!-- //.board_search -->

		<table class="th-top">
			<colgroup>
				<col style="width:auto;">
				<col style="width:22.35%;">
			</colgroup>
			<thead>
				<tr>
					<th>제목</th>
					<th>등록일</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><a href="#" class="subject">A/S를 받고 싶은데 어떻게 해야 하나요?</a></td>
					<td><span class="brightest">2017.01.26</span></td>
				</tr>
				<tr>
					<td><a href="#" class="subject">설 연휴 배송 및 O2O주문 안내</a></td>
					<td><span class="brightest">2017.01.26</span></td>
				</tr>
				<tr>
					<td><a href="#" class="subject">☆새해 첫 할인쿠폰 이벤트☆☆새해 첫 할인쿠폰 이벤트☆☆새해 첫 할인쿠폰 이벤트☆☆새해 첫 할인쿠폰 이벤트☆</a></td>
					<td><span class="brightest">2017.01.26</span></td>
				</tr>
				<tr>
					<td><a href="#" class="subject">12월 30일 고객센터 상담 업무 운영</a></td>
					<td><span class="brightest">2017.01.26</span></td>
				</tr>
				<tr>
					<td><a href="#" class="subject">12월 신용카드 무이자 할부 안내 </a></td>
					<td><span class="brightest">2017.01.26</span></td>
				</tr>
				<!-- [D] 게시물이 없는 경우 -->
				<tr>
					<td colspan="2" class="none">검색결과가 없습니다.</td>
				</tr>
				<tr>
					<td colspan="2" class="none">등록된 게시물이 없습니다.</td>
				</tr>
				<!-- //[D] 게시물이 없는 경우 -->
			</tbody>
		</table><!-- //.th-top -->

		<div class="list-paginate mt-15">
			<a href="#" class="prev-all disabled">처음</a><!-- [D] 버튼 비활성인 경우 .disabled 클래스 추가 -->
			<a href="#" class="prev disabled">이전</a>
			<a href="#" class="on">1</a>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
			<a href="#">6</a>
			<a href="#" class="next">다음</a>
			<a href="#" class="next-all">끝</a>
		</div><!-- //.list-paginate -->

	</section><!-- //.cs_notice -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>