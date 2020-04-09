<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>FAQ</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="cs_faq sub_bdtop">
		
		<div class="divide-box-wrap four">
			<ul class="divide-box faq_cate">
				<li class="on"><a href="javascript:;">전체</a></li><!-- [D] sorting된 해당 카테고리에 .on 클래스 추가 -->
				<li><a href="javascript:;">상품관련</a></li>
				<li><a href="javascript:;">주문/결제</a></li>
				<li><a href="javascript:;">배송관련</a></li>
				<li><a href="javascript:;">취소/환불</a></li>
				<li><a href="javascript:;">반품/교환</a></li>
				<li><a href="javascript:;">회원혜택</a></li>
				<li><a href="javascript:;">기타</a></li>
			</ul>
		</div>

		<table class="th-top accordion_tbl mt-15">
			<colgroup>
				<col style="width:22.35%;">
				<col style="width:auto;">
			</colgroup>
			<thead>
				<tr>
					<th>구분</th>
					<th>제목</th>
				</tr>
			</thead>
			<tbody>
				<!-- 반복(리스트 5개씩 노출) -->
				<tr>
					<td><span class="brightest">상품관련</span></td>
					<td><a href="javascript:;" class="subject accordion_btn">A/S를 받고 싶은데 어떻게 해야 하나요?</a></td>
				</tr>
				<tr class="accordion_con">
					<td colspan="2" class="answer_area">
						<div class="ans">마이페이지 > 주문/배송조회 에서 상품이 배송중일 경우 [배송추적]을 통해 해당 상품을 배송하는 택배사의 사이트로 이동되어 상세한 배송정보를 확인하실 수 있습니다.</div>
					</td>
				</tr>
				<!-- //반복 -->

				<tr>
					<td><span class="brightest">상품관련</span></td>
					<td><a href="javascript:;" class="subject accordion_btn">상품의 사이즈를 알고 싶어요</a></td>
				</tr>
				<tr class="accordion_con">
					<td colspan="2" class="answer_area">
						<div class="ans">마이페이지 > 주문/배송조회 에서 상품이 배송중일 경우 [배송추적]을 통해 해당 상품을 배송하는 택배사의 사이트로 이동되어 상세한 배송정보를 확인하실 수 있습니다.</div>
					</td>
				</tr>

				<tr>
					<td><span class="brightest">주문/결제</span></td>
					<td><a href="javascript:;" class="subject accordion_btn">당일배송 상품과 택배로 받을 상품과 함께 구매할 수 없나요?</a></td>
				</tr>
				<tr class="accordion_con">
					<td colspan="2" class="answer_area">
						<div class="ans">마이페이지 > 주문/배송조회 에서 상품이 배송중일 경우 [배송추적]을 통해 해당 상품을 배송하는 택배사의 사이트로 이동되어 상세한 배송정보를 확인하실 수 있습니다.</div>
					</td>
				</tr>

				<tr>
					<td><span class="brightest">배송관련</span></td>
					<td><a href="javascript:;" class="subject accordion_btn">비회원도 주문이 가능한가요?</a></td>
				</tr>
				<tr class="accordion_con">
					<td colspan="2" class="answer_area">
						<div class="ans">마이페이지 > 주문/배송조회 에서 상품이 배송중일 경우 [배송추적]을 통해 해당 상품을 배송하는 택배사의 사이트로 이동되어 상세한 배송정보를 확인하실 수 있습니다.</div>
					</td>
				</tr>

				<tr>
					<td><span class="brightest">상품관련</span></td>
					<td><a href="javascript:;" class="subject accordion_btn">배송완료인데 택배를 받지 못했어요</a></td>
				</tr>
				<tr class="accordion_con">
					<td colspan="2" class="answer_area">
						<div class="ans">마이페이지 > 주문/배송조회 에서 상품이 배송중일 경우 [배송추적]을 통해 해당 상품을 배송하는 택배사의 사이트로 이동되어 상세한 배송정보를 확인하실 수 있습니다.</div>
					</td>
				</tr>

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

	</section><!-- //.cs_faq -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>