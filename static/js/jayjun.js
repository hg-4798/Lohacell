if (typeof $.datepicker != 'undefined') {
	$.datepicker.setDefaults({
		dateFormat: 'yy-mm-dd',
		prevText: '이전 달',
		nextText: '다음 달',
		monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		dayNames: ['일', '월', '화', '수', '목', '금', '토'],
		dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
		dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
		showMonthAfterYear: true,
		yearSuffix: '년'
	});
}

if (typeof swal != 'undefined') {
	swal.setDefaults({
		confirmButtonText: '확인',
		cancelButtonText: '취소',
		focusConfirm: false,
		animation: false,
		confirmButtonColor: '#212121'
	});
}


//공유
var Share = {
	naverblog: function (tit) {
		var url = encodeURI(encodeURIComponent(window.location.href));
		var title = encodeURI(tit);
		var shareURL = "http://share.naver.com/web/shareView.nhn?url=" + url + "&title=" + title;
		window.open(shareURL, '_blank');
	},
	facebook: function () {
		window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(location.href), 'facebook-share-dialog', 'width=626,height=436');
	},
	kakaolink: function (content) {
		Kakao.Link.sendDefault({
			objectType: 'feed',
			content: content,
			buttonTitle: 'iKNOWiONE'
		});
	},
	urlCopy: function () {
		var clipboard = new ClipboardJS('.icon-share-url', {
			text: function () {
				var href = window.location.href;
				return href;
			}
		});

		UI.alert('공유할 주소가 복사되었습니다.');

	}
}


var UI = {
	month: function (term) {
		var date_e = moment().format('YYYY-MM-DD')
		var date_s = moment().subtract(term, 'month').format('YYYY-MM-DD');
		$('#date_e').val(date_e);
		$('#date_s').val(date_s);
	},
	modal: function (url, title, param) {
		$('#jayjun_modal').fadeIn(300);
		$('#jayjun_modal .layer-title').text(title);

		$('#jayjun_modal .layer-body').load(url, param, function () {
			UI.modalResize();
			$("#jayjun_modal .js-scroll").each(function () {
				$(this).mCustomScrollbar({
					theme: "dark-3",
					callbacks: {
						onInit: function () {
							UI.modalResize();
						}
					}
				});
			});
		});

		$('#jayjun_modal .btn-close').on('click', function () {
			$('.layer-dimm-wrap').hide();
			$('#jayjun_modal .layer-body').css('height', 'auto').empty();
		});
	},
	modalResize: function () {
		var body = $('.layer-inner .layer-body');
		var h = body.outerHeight();
		if (h % 2 == 1) body.css('height', h + 1); //크롬 팝업깨짐 방지처리
	},
	popup: function (url, title, param) {
		$('#jayjun_popup').show();
		$('#jayjun_popup .title').html(title + '<button type="button" class="btn_close">닫기</button>');
		$('#jayjun_popup .pop-body').load(url, param, function () {});

		$('#jayjun_popup .btn_close').on('click', function () {
			$('.pop_layer').hide();
			$('#jayjun_popup .pop-body').css('height', 'auto').empty();

			var osc = -$('#page').position().top;
			$('body').removeClass('lyr_open');
			$(window).scrollTop(osc);
		});
	},
	toInt: function (str) {
		return parseInt(str.replace(/,/g, ""));
	},
	numberFormat: function (number) {
		var num = parseFloat(number);
		if (isNaN(num)) return "0";
		else {
			var reg = /(^[+-]?\d+)(\d{3})/;
			var n = (num + '');
			while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
			return n;
		}
	},
	confirm: function (msg, callback) {
		if (typeof swal == 'undefined') {
			msg = msg.replace("<br>", "\n");
			if (!confirm(msg)) return false;
			if ($.isFunction(callback)) callback();
		} else {
			swal({
				type: 'question',
				title: '',
				showCancelButton: true,
				html: msg
			}).then(function (result) {
				if (result.value) {
					if ($.isFunction(callback)) callback();
				}
			});
		}

	},
	alert: function (msg, callback) {
		if (typeof swal == 'undefined') {
			msg = msg.replace("<br>", "\n");
			alert(msg);
			if ($.isFunction(callback)) callback();
		} else {
			swal({
				type: 'info',
				title: '',
				html: msg
			}).then(function () {
				if ($.isFunction(callback)) callback();
			});

		}

	},
	warning: function (msg) {
		if (typeof swal == 'undefined') {
			msg = msg.replace("<br>", "\n");
			alert(msg);
		} else {
			swal({
				type: 'warning',
				title: '',
				html: msg,
			});
			return false;
		}


	},
	error: function (msg) {
		if (typeof swal == 'undefined') {
			msg = msg.replace("<br>", "\n");
			alert(msg);
		} else {
			swal({
				type: 'error',
				title: '',
				html: msg,
			});
			return false;
		}

	},
	getChecked: function (el_name) {
		var checked = $('input:checkbox[name="' + el_name + '"]:checked');
		return checked;
	},
	open: function (url) {
		if (!url) return false;
		window.open(url);
	},
	pushHistory: function (url, data, title) {
		if (typeof (history.pushState) == 'function') {
			history.pushState(data, title, url);
		}

	},
	setDate: function () {

	},
	toggleCheck: function () {
		$('[data-all]').on('click', function (event) {
			var target_id = $(this).data('all');
			var target = $('[name="' + target_id + '"], [data-group="' + target_id + '"]');
			if (this.checked) {
				target.prop('checked', true);
			} else {
				target.prop('checked', false);
			}
		});
	},
}

var UTIL = {
	cookieSet: function (cookieName, cookieValue, cookieExp) {
		var date = new Date();
		date.setTime(date.getTime() + cookieExp * 24 * 60 * 60 * 1000);
		document.cookie = cookieName + '=' + cookieValue + ';expires=' + date.toUTCString() + ';path=/';
	},
	cookieRemove: function (cookieName) {
		var expireDate = new Date();
		expireDate.setDate(expireDate.getDate() - 1);
		document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString() + "; path=/";
	},
	href: function (href) {
		document.location.href = href;
	}
}


var PRODUCT = {
	init: function () {
		this.like();

	},
	like: function (vp) {
		$('[data-hottcode]').off('click.like').on('click.like', function (evt) {
			evt.preventDefault();
			//로그인검증
			if (!JAYJUN.memid) {
				JAYJUN.login();
				return false;
			}

			var e = $(this);
			var icon = $(this).find('.icon-like, .icon_like');
			var hottcode = e.attr('data-hottcode');
			var section = e.data('section');
			var act = (icon.hasClass('on')) ? 'release' : 'like';

			//
			if (vp == '/front') var cnt_target = e.find('span:eq(1)');
			else var cnt_target = e.find('.like_count');

			var cnt = parseInt(cnt_target.text());



			//실행전 먼저 좋아요 처리를 한다(클릭 이후 처리하면 안예쁨)
			var target = $('[data-hottcode="' + hottcode + '"]'); //화면의 해당 상품코드전체

			target.find('.icon-like, .icon_like').toggleClass('on');
			cnt = (act == 'like') ? cnt + 1 : cnt - 1;
			if (vp == '/front') target.find('span:eq(1)').text(cnt);
			else target.find('.like_count').text(cnt);


			//좋아요처리
			$.ajax({
				url: '/proc/widget.proc.php',
				data: {
					mode: 'like',
					act: act,
					section: section,
					hott_code: hottcode
				},
				dataType: 'json',
				type: 'POST',
				success: function (r) {
					if (r.success) {
						$("#bottom_like_total").html(r.msg);
						//FLOATING.like();
					} else {
						if (r.msg == 'login') {
							JAYJUN.login();
						} else {
							alert(r.msg);
						}

					}

					//카운트
				}
			})
		});
	}
}

//제이준 커스텀
var JAYJUN = {
	init: function (vp) {
		this.viewport = vp;
		PRODUCT.like(vp); //좋아요처리
	},
	setVp: function (vp) {
		if ((vp == 'PC' && this.viewport == '/front') || (vp == 'MOBILE' && this.viewport == '/m')) {
			UTIL.cookieRemove('VP'); //지정뷰포트와 노출뷰포트가 같은경우 쿠키 삭제
		} else UTIL.cookieSet('VP', vp, 1);
		document.location.href = '/';
		return false;
	},
	login: function () {
		UI.confirm("로그인 후 이용이 가능합니다.<br>로그인 화면으로 이동하시겠습니까?", function () {
			var href = JAYJUN.viewport + '/login.php';
			href += '?chUrl=' + encodeURIComponent($(location).attr('pathname') + $(location).attr('search'));
			document.location.href = href;
		});
	},
	logout: function () {
		location.href = "/proc/logout.php";
        UTIL.cookieSet('exe_banner_close','N'); //로그아웃시 임직원 배너 노출여부 초기화
	}
}

var ORDER = {
	cancel: function (order_num) {
		UI.alert("고객센터를 통해 신청해주세요.");
	},
	refund: function () {
		UI.alert("고객센터를 통해 신청해주세요.");
	},
	exchange: function () {
		UI.alert("고객센터를 통해 신청해주세요.");
	},
	deliveryTraker: function (url) {

		window.open(url, 'delivery', 'width=500, height=500');
	}
}