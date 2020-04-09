<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="promotion-page">

		<article class="promotion-wrap">
			<header><h2 class="promotion-title">이벤트</h2></header>
			<div class="roulette-view">
				<div class="bulletin-info mb-10">
					<ul class="title">
						<li>2월 룰렛 이벤트</li>
						<li class="txt-toneC">2017.02.01~2017.02.28</li>
					</ul>
					<ul class="share-like clear">
						<li><a href="javascript:history.back();"><i class="icon-list">리스트 이동</i></a></li>
						<li><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></li> <!-- [D] 좋아요 i 태그에 .on 추가 -->
						<li>
							<div class="sns">
								<i class="icon-share">공유하기</i>
								<div class="links">
									<a href="#"><i class="icon-kas">카카오 스토리</i></a>
									<a href="#"><i class="icon-facebook-dark">페이스북</i></a>
									<a href="#"><i class="icon-twitter">트위터</i></a>
									<a href="#"><i class="icon-band">밴드</i></a>
									<a href="#"><i class="icon-link">링크</i></a>
								</div>
							</div>
						</li>
					</ul>
				</div><!-- //.bulletin-info -->

				<div class="roulette-wrap">
					<h3><span><strong>2월 룰렛</strong> 이벤트!</span></h3>
					<div class="wrap-wheel">
						<div class="spin-wheel">
							<canvas id="canvas" class="roulette" width="700" height="700">
								<p class="ta-c">Sorry, your browser doesn't support canvas. Please try another.</p>
							</canvas>
							<div class="spin-button">
								<a href="javascript:;" class="spin-btn1 clickable" onclick="startSpin();">START 클릭!</a>
								<a href="javascript:;" class="spin-btn2" onclick="alert('이미 응모하셨습니다.');">START 클릭!</a>
							</div>
						</div>
					</div>
					<div class="notice clear">
						<p class="tit">꼭! 알아두세요.</p>
						<ul>
							<li>회원 가입 후 참여가 가능합니다.</li>
							<li>쿠폰은 발급일로부터 30일간 사용이 가능합니다.</li>
							<li>1개의 아이디당 1번만 응모가 가능합니다.</li>
							<li>발급된 쿠폰 및 포인트는 온라인 구매시 사용이 가능합니다.</li>
						</ul>
					</div>
				</div><!-- //.roulette-wrap -->

				<div class="prev-next clear">
					<div class="prev clear"><span class="mr-20">PREV</span><a >이전글이 없습니다.</a></div>
					<div class="next clear"><span class="ml-20">NEXT</span><a href="#">햇볕 좋은 날을 좋아하는 당신의 패션</a></div>
				</div><!-- //.prev-next -->
				<section class="reply-list-wrap mt-80">
					<header><h2>댓글 입력과 댓글 리스트 출력</h2></header>
					<div class="reply-count clear">
						<div class="fl-l">댓글 <strong class="fz-16">235</strong></div>
						<div class="byte "><span class="point-color">0</span> / 300</div>
					</div>
					<div class="reply-reg-box">
						<div class="box">
							<form>
								<fieldset>
									<legend>댓글 입력 창</legend>
									<textarea placeholder="※ 로그인 후 작성이 가능합니다."></textarea>
									<button class="btn-point" type="submit"><span>등록</span></button>
								</fieldset>
							</form>
						</div>
					</div>
					<ul class="reply-list">
						<li>
							<div class="reply">
								<div class="btn">
									<button class="btn-basic h-small" type="button"><span>수정</span></button>
									<button class="btn-line h-small" type="button"><span>삭제</span></button>
								</div>
								<p class="name"><strong>박길동</strong><span class="pl-5">(2017.02.20 16:33)</span></p>
								<div class="comment editor-output">
									<p>다들 말한것처럼 크기는 사진처럼 넉넉해보이진 않아요.. 일반 에코백 사이즈입니다.</p>
									<p>그래도 이쁘고 짱짱하고 튼튼하고 조으다</p>
									<p>컬러 이뿌당</p>
								</div>
							</div><!-- //.reply -->
						</li>
						<li>
							<div class="reply">
								<div class="btn hide"> <!-- [D] 버튼 출력발생시 .hide 클래스 삭제 -->
									<button class="btn-basic h-small" type="button"><span>수정</span></button>
									<button class="btn-line h-small" type="button"><span>삭제</span></button>
								</div>
								<p class="name"><strong>홍길동</strong><span class="pl-5">(2017.02.20 16:33)</span></p>
								<div class="comment editor-output">
									<p>다들 말한것처럼 크기는 사진처럼 넉넉해보이진 않아요.. 일반 에코백 사이즈입니다.</p>
									<p>그래도 이쁘고 짱짱하고 튼튼하고 조으다</p>
									<p>컬러 이뿌당</p>
								</div>
							</div><!-- //.reply -->
						</li>
						<li>
							<div class="reply">
								<div class="btn hide"> <!-- [D] 버튼 출력발생시 .hide 클래스 삭제 -->
									<button class="btn-basic h-small" type="button"><span>수정</span></button>
									<button class="btn-line h-small" type="button"><span>삭제</span></button>
								</div>
								<p class="name"><strong>홍길동</strong><span class="pl-5">(2017.02.20 16:33)</span></p>
								<div class="comment editor-output">
									<p>다들 말한것처럼 크기는 사진처럼 넉넉해보이진 않아요.. 일반 에코백 사이즈입니다.</p>
									<p>그래도 이쁘고 짱짱하고 튼튼하고 조으다</p>
									<p>컬러 이뿌당</p>
								</div>
							</div><!-- //.reply -->
						</li>
					</ul><!-- //.reply-list -->
					<div class="list-paginate mt-20">
						<a href="#" class="prev-all"></a>
						<a href="#" class="prev"></a>
						<a href="#" class="number on">1</a>
						<a href="#" class="number">2</a>
						<a href="#" class="number">3</a>
						<a href="#" class="number">4</a>
						<a href="#" class="number">5</a>
						<a href="#" class="number">6</a>
						<a href="#" class="number">7</a>
						<a href="#" class="number">8</a>
						<a href="#" class="number">9</a>
						<a href="#" class="number">10</a>
						<a href="#" class="next on"></a>
						<a href="#" class="next-all on"></a>
					</div><!-- //.list-paginate -->
				</section>
			</div><!-- //.roulette-view -->
		</article>

	</div>
</div><!-- //#contents -->

<script type="text/javascript" src="../static/js/TweenMax.min.js"></script>
<script type="text/javascript" src="../static/js/Winwheel.js"></script>
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
loadedImg.src = "../static/img/common/roulette_wheel.png";



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
		//document.getElementById('spin-button').src = "roulette_btn.png";
		//document.getElementById('spin-button').className = "clickable";
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
		//document.getElementById('spin-button').src       = "roulette_btn.png";
		//document.getElementById('spin-button').className = "disabled";

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
	
	$('.spin-button').addClass('disabled');
}
</script>

<?php include_once('../outline/footer.php') ?>