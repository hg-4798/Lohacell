"use strict"

// 슬라이드 배너 (반복)
$(function() {
	
	$("[data-ui=SlideLoop]").each(function() {
		
		var $ui = $(this);
		var $list = $ui.find("[data-element=list]");
		var $content = $list.find("[data-element=contents]");
		var $menu = $ui.find("[data-element=menu]");
		var $arrow = $ui.find("[data-element=prev], [data-element=next]");
		var $pageCurrent = $ui.find("[data-element=current]");
		var $pageTotal = $ui.find("[data-element=total]");

		var contentTotal = $content.length;
		var pageNum = 0;
		var isChange = false;
		var timer;
		var timerInterval;
		var timerDuration = 4;

		content_sort();

		// 메뉴
		$menu.on("click", function(_e) {

			_e.preventDefault();
			if (!isChange && pageNum != $menu.index(this)) {
				var oldNum = pageNum;
				pageNum = $menu.index(this);
				var direction = (pageNum > oldNum) ? "next" : "prev";
				content_change({ oldNum:oldNum, direction:direction });
			}

		});

		function menu_change() {

			$menu.removeClass("on").removeAttr("title")
				.eq(pageNum).addClass("on").attr("title", "선택됨");

		}

		// 화살표
		$arrow.on("click", function(_e) {

			_e.preventDefault();
			if (!isChange) {
				var oldNum = pageNum;
				var direction = $(this).attr("data-element");
				if (direction == "prev") pageNum = (pageNum == 0) ? contentTotal - 1 : pageNum - 1;
				else pageNum = (pageNum == contentTotal - 1) ? 0 : pageNum + 1;
				content_change({ oldNum:oldNum, direction:direction });
			}

		});

		// 내용 변경 { oldNum:이전 번호, direction:이동 방향, time:이동 시간, ease:이동 효과 }
		function content_change(_obj) {
			
			isChange = true;
			var option = $.extend({ oldNum:null, direction:"next", time:0.4, ease:Cubic.easeInOut }, _obj);
			var target = (option.direction == "next") ? -$content.outerWidth() : $content.outerWidth();

			$content.show();
			
			if (option.oldNum != null) {
				var $old = $content.eq(option.oldNum);
				TweenMax.to($old, option.time, { left:target, ease:option.ease });
			}
			var $current = $content.eq(pageNum);
			$current.css({ left:-target });
			TweenMax.to($current, option.time, { left:0, ease:option.ease, onStart:menu_change, onComplete:content_tween_complete });

		}

		function content_tween_complete() {
			
			content_sort();
			isChange = false;

		}
		
		// 내용 재정렬
		function content_sort() {
			
			$content.show().not($content.eq(pageNum)).hide().css({ left:$content.outerWidth() });
			$pageTotal.text(contentTotal);
			$pageCurrent.text(pageNum + 1);

		}
		
		// 자동롤링
		$ui.on("focusin focusout mouseenter mouseleave", function(_e) {
			
			timer.stop();
			switch(_e.type) {
				case "focusout":
				case "mouseleave":
					timer.start();
				break;
			}

		});

		timer = {
			start:function() {
				timerInterval = setInterval(function() {
				
					var oldNum = pageNum;
					pageNum = (pageNum == contentTotal - 1) ? 0 : pageNum + 1;
					content_change({ oldNum:oldNum, direction:"next" });

				}, timerDuration * 1000);
			},
			stop:function() {
				clearInterval(timerInterval);
			},
			reset:function() {
				timer.stop();
				timer.start();
			}
		}
		timer.start();

	});

});