<?php
include_once('outline/header_m.php');
$page_cate = '이벤트';
?>

<!-- 내용 -->
<main id="content" class="subpage">
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
			<span class="brand">2월 룰렛 이벤트</span>
			<span class="date">2017.01.14</span>
		</h4>

		<div class="rlt_event">
			<h5><span><strong>2월 룰렛</strong> 이벤트!</span></h5>
				
			<div class="wrap_wheel">
				<div class="spin_wheel">
					<canvas id="canvas" class="roulette" width="560" height="560">
						<p align="center">Sorry, your browser doesn't support canvas. Please try another.</p>
					</canvas>
					<div class="spin_button">
						<a href="javascript:;" class="spin_btn1 clickable" onClick="startSpin();">START 클릭!</a>
						<a href="javascript:;" class="spin_btn2" onClick="alert('이미 응모하셨습니다.');">START 클릭!</a>
					</div>
				</div>
			</div>

			<div class="notice">
				<p class="tit">꼭! 알아두세요.</p>
				<ul>
					<li>회원 가입 후 참여가 가능합니다.</li>
					<li>쿠폰은 발급일로부터 30일간 사용이 가능합니다.</li>
					<li>1개의 아이디당 1번만 응모가 가능합니다.</li>
					<li>발급된 쿠폰 및 포인트는 온라인 구매시 사용이 가능합니다.</li>
				</ul>
			</div>
		</div><!-- //.rlt_event -->

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

		<div class="reply_write">
			<textarea placeholder="※ 로그인 후 작성이 가능합니다."></textarea>
			<div class="clear">
				<span class="txt_count"><span class="point-color">0</span>/300</span>
				<a href="javascript:;" class="btn-point">등록</a>
			</div>
		</div><!-- //.reply_write -->

		<div class="reply_list">
			<p class="count">댓글 21</p>
			<ul>
				<li><!-- [D] 본인이 작성한 댓글 -->
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<p class="content">궁금하고 또 궁금해서 물어보는데요. 매거진안에 나오는 옷 어디 브랜드인가요?</p>
					<div class="btns">
						<a href="javascript:;" class="btn-line">수정</a>
						<a href="javascript:;" class="btn-basic">삭제</a>
					</div>
				</li>

				<li><!-- [D] 수정 클릭시 작성폼으로 변경 (텍스트 수정 가능) -->
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<div class="reply_write">
						<textarea>궁금하고 또 궁금해서 물어보는데요. 매거진안에 나오는 옷 어디 브랜드인가요?</textarea>
						<div class="clear">
							<span class="txt_count"><span class="point-color">0</span>/300</span>
							<a href="javascript:;" class="btn-line">수정</a>
						</div>
					</div>
				</li>

				<li>
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<p class="content">궁금하고 또 궁금해서 물어보는데요. 매거진은 일주일에 한번씩 업데이트 되나요, 멋집니다.</p>
				</li>

				<li>
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<p class="content">옷이 너무 이뻐요~</p>
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

<script type="text/javascript" src="static/js/TweenMax.min.js"></script>
<script type="text/javascript" src="static/js/Winwheel.js"></script>
<script type="text/javascript">
// Create new wheel object specifying the parameters at creation time.
var theWheel = new Winwheel({
	'numSegments'       : 8,         // Specify number of segments.
	'drawMode'          : 'image',   // drawMode must be set to image.
	'segments'     :                // Define segments.
	[
	   {'text' : 'E-POINT 600P'},
	   {'text' : '1만원 할인 쿠폰'},
	   {'text' : 'E-POINT 500P'},
	   {'text' : '영화예매권'},
	   {'text' : 'E-POINT 500P'},
	   {'text' : '1만원 할인 쿠폰'},
	   {'text' : 'E-POINT 500P'},
	   {'text' : '영화예매권'}
	],
	'animation' :                   // Specify the animation to use.
	{
		'type'     : 'spinToStop',
		'duration' : 2,     // Duration in seconds.
		'spins'    : 4,     // Number of complete spins.
		'callbackFinished' : 'alertPrize()'
	}
});

// Create new image object in memory.
var loadedImg = new Image();

// Create callback to execute once the image has finished loading.
loadedImg.onload = function()
{
	theWheel.wheelImage = loadedImg;    // Make wheelImage equal the loaded image object.
	theWheel.draw();                    // Also call draw function to render the wheel.
}

// Set the image source, once complete this will trigger the onLoad callback (above).
loadedImg.src = "static/img/common/roulette_wheel.png";



// Vars used by the code in this page to do power controls.
var wheelPower    = 0;
var wheelSpinning = false;

// -------------------------------------------------------
// Function to handle the onClick on the power buttons.
// -------------------------------------------------------
function powerSelected(powerLevel)
{
	// Ensure that power can't be changed while wheel is spinning.
	if (wheelSpinning == false)
	{
		// Reset all to grey incase this is not the first time the user has selected the power.
		document.getElementById('pw1').className = "";
		document.getElementById('pw2').className = "";
		document.getElementById('pw3').className = "";

		// Now light up all cells below-and-including the one selected by changing the class.
		if (powerLevel >= 1)
		{
			document.getElementById('pw1').className = "pw1";
		}

		if (powerLevel >= 2)
		{
			document.getElementById('pw2').className = "pw2";
		}

		if (powerLevel >= 3)
		{
			document.getElementById('pw3').className = "pw3";
		}

		// Set wheelPower var used when spin button is clicked.
		wheelPower = powerLevel;

		// Light up the spin button by changing it's source image and adding a clickable class to it.
		//document.getElementById('spin_button').src = "roulette_btn.png";
		//document.getElementById('spin_button').className = "clickable";
	}
}

// -------------------------------------------------------
// Click handler for spin button.
// -------------------------------------------------------
function startSpin()
{
	// Ensure that spinning can't be clicked again while already running.
	if (wheelSpinning == false)
	{
		// Based on the power level selected adjust the number of spins for the wheel, the more times is has
		// to rotate with the duration of the animation the quicker the wheel spins.
		if (wheelPower == 1)
		{
			theWheel.animation.spins = 2;
		}
		else if (wheelPower == 2)
		{
			theWheel.animation.spins = 5;
		}
		else if (wheelPower == 3)
		{
			theWheel.animation.spins = 8;
		}

		// Disable the spin button so can't click again while wheel is spinning.
		//document.getElementById('spin_button').src       = "roulette_btn.png";
		//document.getElementById('spin_button').className = "disabled";

		// Begin the spin animation by calling startAnimation on the wheel object.
		theWheel.startAnimation();

		// Set to true so that power can't be changed and spin button re-enabled during
		// the current animation. The user will have to reset before spinning again.
		wheelSpinning = true;
	}
}

// -------------------------------------------------------
// Function for reset button.
// -------------------------------------------------------
function resetWheel()
{
	theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
	theWheel.rotationAngle = 0;     // Re-set the wheel angle to 0 degrees.
	theWheel.draw();                // Call draw to render changes to the wheel.

	document.getElementById('pw1').className = "";  // Remove all colours from the power level indicators.
	document.getElementById('pw2').className = "";
	document.getElementById('pw3').className = "";

	wheelSpinning = false;          // Reset to false to power buttons and spin can be clicked again.
}

// -------------------------------------------------------
// Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters.
// -------------------------------------------------------
function alertPrize()
{
	// Get the segment indicated by the pointer on the wheel background which is at 0 degrees.
	var winningSegment = theWheel.getIndicatedSegment();

	// Do basic alert of the segment text. You would probably want to do something more interesting with this information.
	alert(winningSegment.text + "에 당첨되었습니다.");

	$('.spin_button').addClass('disabled');
}
</script>

<?php
include_once('outline/footer_m.php');
?>