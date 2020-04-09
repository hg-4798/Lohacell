$(document).ready(function () {
	// 공통 탭
	$("[data-ui=TabMenu]").each(function () {
		var $ui = $(this);
		var $menu = $ui.find("[data-content=menu]");
		var $content = $ui.find("[data-content=content]");
		$menu.on("click", function (_e) {
			//_e.preventDefault();
			var index = $menu.index(this);
			$menu.removeClass("active").removeAttr("title").eq(index).addClass("active").attr("title", "선택됨");
			$content.removeClass("active").eq(index).addClass("active");
		});
	});

	//상품상세 팝업 탭
	$('ul.tab li').click(function () {
		$(this).siblings().removeClass('selected');
		$(this).addClass('selected');
	});

	//layers popup
	function popupLayer(obj) {
		var self = $(obj);
		var target = $($(obj).attr("href"));
		var dimmH = $('html,body').height();
		var targetH = $($(obj).attr("href")).height();
		$('body').css('overflow-y', 'hidden');
		target.wrap('<div class="popup-dim">');
		target.attr("tabindex", "0").stop().fadeIn(500).focus();
		target.css({
			'margin-top': '-' + Math.ceil(target.height() / 2) + 'px',
			'margin-left': '-' + target.width() / 2 + 'px',
			'z-index': '1500'
		});

		if (targetH > dimmH) {
			target.parent().addClass('layer-scroll');
		}

		target.find(".layer-close button, .btn-cancel").click(function () {
			$('body').css('overflow-y', 'auto');
			target.css({
				'z-index': '10'
			});
			$('.popup-dim').children('.layers[id*="popup-"], .layers[id*="pop-view-"]').unwrap();
			target.stop().fadeOut(300);
			self.focus();
		});

	}
	$('a[href*="#popup-"]').on('click', function (e) {
		e.preventDefault();
		popupLayer(this);
	});
});

swal.setDefaults({
	confirmButtonText:'확인',
	cancelButtonText:'취소',
	focusConfirm: false,
	animation:false,
	confirmButtonColor:'#212121'
});


var oEditors = [];
var se_option = {
	oAppRef: oEditors,
	elPlaceHolder: "contents",
	sSkinURI: "/third_party/SE2/SmartEditor2Skin.html",
	fCreator: "createSEditor2",
	htParams: {
		SE2M_FontName: {
			htMainFont: {
				'id': 'NanumGothic',
				'name': '나눔고딕',
				'size': '9',
				'url': '',
				'cssUrl': ''
			} // 기본 글꼴 설정
		}
	},
	fOnAppLoad: function () {
		//console.log(oEditors);
		//oEditors.getById['contents'].setDefaultFont("NanumGothic", 9);
	}
}
var UI = {
	alert: function (msg, callback) {
		swal({
			type: 'info',
			title: '',
			html: msg
		}).then(function() {
			if($.isFunction(callback)) callback();
		});
	},
	warning: function (msg) {
		swal({
			type: 'warning',
			title: '',
			html: msg,
		});
		return false;

	},
	error: function (msg) {
		swal({
			type: 'error',
			title: '',
			html: msg,
		});
	},
	confirm: function(msg, callback) {
		swal({
			type: 'question',
			title: '',
			showCancelButton:true,
			html: msg
		}).then(function(result) {
			console.log(result)
			if(result.value) {
				if($.isFunction(callback)) callback();
			}
		});
	},
	memo: function(callback ) {
		swal({
			title: "메모",
			input: 'textarea',
			showCancelButton: true,
			confirmButtonText: "등록"
		}).then(function(result) {
			if(result.value=='') {
				UI.error('입력한 내용이 없습니다.');
			}else {
				if($.isFunction(callback)) callback(result.value);
			}
		});
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
	reverseCheck: function() {
		$('[data-reverse]').each(function(i,e) {
			var el_name = $(this).data('reverse');
			$('[name="'+el_name+'"]').on('click', function() {
				if(this.checked) {
					if(this.value == 'all') {
						$('[name="'+el_name+'"][value!="all"]').removeAttr("checked");
					}
					else {
						$('[name="'+el_name+'"][value="all"]').removeAttr("checked");
					}
				}
				else {
					if(this.value == 'all') {
						return false;
					}
					else {
						var checked = $('[name="'+el_name+'"][value!="all"]:checked').length;
						if(checked < 1) $('[name="'+el_name+'"][value="all"]').prop('checked',true);
					}
				}
			});
		});
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
	getChecked: function (el_name) {
		var checked = $('input:checkbox[name="' + el_name + '"]:checked');
		return checked;
	},
	popup: function (url, title, w, h) {
		var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
		var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

		var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

		var left = ((width / 2) - (w / 2)) + dualScreenLeft;
		var top = ((height / 2) - (h / 2)) + dualScreenTop;
		var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

		// Puts focus on the newWindow
		if (window.focus) {
			newWindow.focus();
		}
	},
	modal: function (url, title, param, w) {
		var modal = $('.modal');
		this.url = url;
		this.param = param;

		modal.find('button.close').off('click.close').on('click.close', UI.modalClose);
		modal.find('button.max').off('click.max').on('click.max', UI.modalMax);
		modal.find('button.min').off('click.min').on('click.min', UI.modalMin);
		modal.find('.modal-title').html(title);

		//가로사이즈체크
		var width = w || 500;
		modal.attr('data-width', width);
		modal.find('.modal-content').css('width', width);
		modal.find('.modal-content').css('margin-left', width / 2 * -1);


		$('.modal-body').load(url, param, function () {
			modal.css('display', 'block');
			UI.modalResize();
			UI.modalCenter();
		});

		$(window).off('resize.modal').on('resize.modal', UI.modalResize);

		$('body').css({'overflow-y':'hidden'});
		modal.find('.modal-content').draggable({
			handle: ".modal-header"
		});

		//$('.modal-content').resizable({handles :'e'});

		
	},
	modalReload: function () {
		$('.modal-body').load(this.url, this.param);
	},
	modalCenter: function () {
		var mc = $('.modal').find('.modal-content');
		var h = mc.height();
		var top = ($(window).height() - h) / 2;
		mc.css('top', top);

	},
	modalPreload: function () {
		//this.preload = true;
	},
	modalClose: function () {
		$('.modal').css('display', 'none');
		$('body').css({'overflow-y':'auto'});
		UI.modalRestore();
	},
	modalMax: function () {
		if ($('.modal-header button.max i').hasClass('fa-window-restore')) {
			UI.modalRestore();
		} else {
			$('.modal-content').css({
				width: '100%',
				height: '100%',
				top: 0,
				left: 0,
				marginLeft: 0,
				'max-height': ''
			});
			$('.modal-body').css({
				'max-height': $('.modal-content').height() - 37
			});
			$('.modal-header button.max i').removeClass('fa-window-maximize').addClass('fa-window-restore');
		}

	},
	modalResize: function () {
		var maxheight = $(window).height() - 40;
		$('.modal').attr('data-maxheight', maxheight);
		$('.modal').find('.modal-content').css('max-height', maxheight);
		$('.modal').find('.modal-body').css('max-height', maxheight - 37);
	},
	modalMin: function () {

		if ($('.modal-header button.min i').hasClass('fa-window-restore')) {
			UI.modalRestore();
		} else {
			$('.modal-body').css('display', 'none');
			$('.modal-content').css({
				width: 'auto',
				height: 'auto'
			});
			$('.modal-header button.max').css('display', 'none');
			$('.modal-header button.min i').removeClass('fa-window-minimize').addClass('fa-window-restore');
		}
	},
	modalRestore: function () {
		var width = $('.modal').data('width');
		var maxheight = $('.modal').data('maxheight');
		var margin_left = width / 2 * (-1);
		$('.modal-body').css({
			'display': '',
			'max-height': maxheight - 37
		});
		$('.modal-header button.max').css('display', '');
		$('.modal-header button.min').css('display', '');
		$('.modal-content').css({
			width: width + 'px',
			height: 'auto',
			top: '20px',
			left: '50%',
			marginLeft: margin_left + 'px',
			'max-height': maxheight
		});
		$('.modal-header button.max i').removeClass('fa-window-restore').addClass('fa-window-maximize');
		$('.modal-header button.min i').removeClass('fa-window-restore').addClass('fa-window-minimize');
	},
	top: function (t) {
		var top = t || 0;
		$('html,body').scrollTop(top);
	},
	open: function (url, title, w, h) {
		var left = (screen.width / 2) - (w / 2);
		var top = (screen.height / 2) - (h / 2);
		return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
	},
	href: function (href) {
		if (href) document.location.href = href;
		else document.location.reload();
	},
	loading: function (id) {
		var el = $('#' + id);
		if (el.hasClass('loading-list')) {
			//setTimeout(function(){el.removeClass('loading-list');},1000);
			el.removeClass('loading-list');
		} else {
			el.addClass('loading-list');
		}
	},
	ing: function (msg) {
		var dimm = $('.dimm-loading');
		if (dimm.is(':visible')) dimm.css('display', 'none');
		else {
			if (msg) dimm.find('.comment').html(msg);
			dimm.css('display', 'block');
		}
	},
	toInt: function(str) {
		if(!str) return 0;
		return parseInt(str.replace(/,/g,""));
	}
};

var UTIL = {
	period: function (now, sub) { //now:2018-04-05, gap:7_days, 14_days,, 1_months, 
		//dependence moment.js

		var end = moment(now).format('YYYY-MM-DD');
		var start = end;
		switch (sub) {
			case 'today':
				break;
			case 'all':
				end = '';
				start = '';
				break;
			default:
				var sub_arr = sub.split('_');
				start = moment(now).subtract(sub_arr[0], sub_arr[1]).format('YYYY-MM-DD');
				break;
		}

		return {
			start: start,
			end: end
		};
	}
};


function on_menu_sub(depth_2, depth_3) {
	$('.lnb_sub_menu').find('dd').css('display', 'none');
	var on = $('.lnb_sub_menu').eq(depth_2).find('dd');
	on.css('display', 'block');
	on.find('li').eq(depth_3).find('a').addClass('this');
}