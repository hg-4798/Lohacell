<?php
include_once('outline/header_m.php');
$page_cate = '이벤트';
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<!-- 리뷰작성 팝업 -->
	<section class="pop_layer layer_photo_submit">
		<div class="inner">
			<h3 class="title">포토등록<button type="button" class="btn_close">닫기</button></h3>
			<div class="board_type_write">
				<dl>
					<dt>제목</dt>
					<dd>
						<input type="text" class="w100-per" placeholder="제목 입력(필수)">
					</dd>
				</dl>
				<dl>
					<dt>내용</dt>
					<dd>
						<textarea class="w100-per" rows="6" placeholder="주문번호를 입력해주세요.(필수)">주문번호를 입력해주세요.
주문번호: </textarea>
					</dd>
				</dl>
				<dl>
					<dt>이미지 첨부</dt>
					<dd>
						<div class="upload_img">
							<ul>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[0]" value="" class="vi-image"><input type="file" name="up_filename[0]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[1]" value="" class="vi-image"><input type="file" name="up_filename[1]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[2]" value="" class="vi-image"><input type="file" name="up_filename[2]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[3]" value="" class="vi-image"><input type="file" name="up_filename[3]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
							</ul>
						</div>
						<p class="mt-5">파일명: 한글, 영문, 숫자/파일 크기: 3mb 이하/파일 형식: GIF, JPG, JPEG</p>
					</dd>
				</dl>

				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-large">등록</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- //리뷰작성 팝업 -->

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>프로모션</span>
		</h2>
		<div class="breadcrumb">
			<?php include_once('promotion_menu.php'); ?>
		</div>
	</section><!-- //.page_local -->

	<section class="photo_type_view">
		<h4 class="title_area with_brand">
			<span class="brand">2월 신규 포토 이벤트</span>
			<span class="date">2017.01.14</span>
		</h4>

		<div><!-- [D] 에디터 영역 -->
			<img src="static/img/test/@event_view02.jpg" alt="이벤트 이미지">
		</div>

		<div class="btns mt-20">
			<ul>
				<li><a href="event.php" class="icon_list">목록</a></li>
				<li><a href="javascript:;" class="icon_like" title="선택 안됨">좋아요</a> <span class="count">23</span></li><!-- [D] 클릭시 좋아요 숫자+1, 재클릭시 좋아요 숫자-1 -->
				<li>
					<div class="wrap_bubble layer_sns_share on">
						<div class="btn_bubble"><button type="button" class="btn_sns_share">sns 공유</button></div>
						<div class="pop_bubble">
							<div class="inner">
								<button type="button" class="btn_pop_close">닫기</button>
								<div class="icon_container">
									<a href="javascript:;"><img src="static/img/icon/icon_sns_kas.png" alt="카카오스토리"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_face.png" alt="페이스북"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_twit.png" alt="트위터"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_band.png" alt="밴드"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_link.png" alt="url"></a>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div><!-- //.btns -->

		<div class="other_posting">
			<dl>
				<dt>PREV</dt>
				<dd><a href="#">비오는 날을 좋아하는 당신의 패션</a></dd>
			</dl>
			<dl>
				<dt>NEXT</dt>
				<dd><a href="#">햇볕 좋은 날을 좋아하는 당신의 패션</a></dd>
			</dl>
		</div><!-- //.other_posting -->

		<div class="photo_submit btn_area">
			<ul>
				<li><a href="javascript:;" class="btn_photo_submit btn-point h-input">포토등록</a></li>
			</ul>
			<p class="notice">※ 로그인 후 작성이 가능합니다.</p>
		</div><!-- //.photo_submit -->

		<div class="reply_list mt-20">
			<p class="count">댓글 21</p>
			<ul class="accordion_list">
				<li><!-- [D] 본인이 작성한 댓글 -->
					<div class="tit_area">
						<div class="info">
							<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
						</div>
						<p class="accordion_btn content">포토 이벤트 참여</p>
						<div class="btns">
							<a href="javascript:;" class="btn_photo_submit btn-line">수정</a>
							<a href="javascript:;" class="btn-basic">삭제</a>
						</div>
					</div>
					<div class="accordion_con">
						<p>저는 늘 신원몰 옷만 입어요. 제가 늘 착용하는 스탈이랍니다. 꼭 뽑아주세요. </p><br>
						<img src="static/img/test/@event_view04.jpg" alt="포토 이벤트 참여 이미지"><br><br>
						<img src="static/img/test/@event_view05.jpg" alt="포토 이벤트 참여 이미지">
					</div>
				</li>

				<li>
					<div class="tit_area">
						<div class="info">
							<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
						</div>
						<p class="accordion_btn content">포토 이벤트 참여</p>
					</div>
					<div class="accordion_con">
						<p>저는 늘 신원몰 옷만 입어요. 제가 늘 착용하는 스탈이랍니다. 꼭 뽑아주세요. </p><br>
						<img src="static/img/test/@event_view04.jpg" alt="포토 이벤트 참여 이미지">
					</div>
				</li>

				<li>
					<div class="tit_area">
						<div class="info">
							<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
						</div>
						<p class="accordion_btn content">옷이 너무 이뻐요~</p>
					</div>
					<div class="accordion_con">
						<p>저는 늘 신원몰 옷만 입어요. 제가 늘 착용하는 스탈이랍니다. 꼭 뽑아주세요. </p><br>
						<img src="static/img/test/@event_view05.jpg" alt="포토 이벤트 참여 이미지">
					</div>
				</li>
			</ul>
		</div><!-- //.reply_list -->
		
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
		</div>
	</section><!-- //.photo_type_view -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>