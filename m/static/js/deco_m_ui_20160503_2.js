	// 페이지/위젯/탑/상세툴바
	$$page.each(function() {
		
		var $ui = $(this);
		var $widget = $(".js-widget");
		var $top = $(".js-btn-top");
		var $toolMenu = $$toolbar.find(".menu");// 메뉴툴바
		var $toolBuy = $$toolbar.find(".js-tool-buy");// 상세툴바

		var scrollTotal;
		var scrollTop;
		
		$$toolbar.data("isShow", true);
		TweenMax.set($top, { autoAlpha:0 });
		
		$(window).on("scroll", page_scroll);
		page_scroll(null);
		
		function page_scroll(_e) {
			
			if (_e) {
				scrollTotal = this.scrollHeight - this.clientHeight;
				scrollTop = this.scrollTop;
			}
			
			// top버튼 위치
			var toolbarH = $$toolbar.outerHeight();
			if ( $(window).scrollTop() > 100) $top.addClass("on");
			else $top.removeClass("on");
			var tempBottom = (scrollTop > scrollTotal - 35) ? toolbarH + (scrollTop - (scrollTotal - 35)) : toolbarH;
			$top.css({ bottom:tempBottom });
			
			// 위젯버튼 위치
			//$widget.css({ bottom:$$toolbar.outerHeight() + 6 });
			
			// 숨기기/보이기
			TweenMax.killDelayedCallsTo(page_scroll_end);
			TweenMax.delayedCall(0.1, page_scroll_end); //속도 조절 1 -> 0.1
			if (_e && $$toolbar.data("isShow")) {
				$$toolbar.data("isShow", false);
				if( $(".goods-detail-buy").length == 0 ) {
					TweenMax.to($toolBuy, 0.3, { autoAlpha:0, height:0, ease:Cubic.easeOut, onUpdate:page_scroll_update });
					//TweenMax.to($widget, 0.3, { autoAlpha:0, ease:Cubic.easeOut });
					//TweenMax.to($top, 0.3, { autoAlpha:0, ease:Cubic.easeOut });
				} 
			}

			// 상세 툴바
			if ($toolBuy[0]) {

				var $detailBuy = $(".goods-detail-buy");

                if ( $detailBuy.length > 0 ) {
					// 상품 상세에서는 무조건 나오게 변경
                   // var limitY = $detailBuy.position().top + $detailBuy.outerHeight();
					var targetY = $('.js-goods-detail-content').offset();
                    //if (scrollTop > limitY) { // 변경
					//if( $(window).height() > targetY.top + 25 ) { // 변경
					if( $(window).scrollTop() > targetY.top - $(window).height() ) {
                        $toolBuy.show();
                        //$toolMenu.hide();
                    } else {
                        $toolBuy.fadeOut().trigger("close");;
                        //$toolMenu.show();
                    }
					//$toolBuy.show();
                    $toolMenu.hide();
                    var childH = $$toolbar.children().filter(":visible").outerHeight();
                    $$footer.css({ paddingBottom:childH });
                    $(".js-font").css({ bottom:childH + 10 });
                }
			}
			
		}
		
		function page_scroll_end() {
			if (!$$toolbar.data("isShow")) {
				$$toolbar.data("isShow", true);
				var childH = $$toolbar.children().filter(":visible").outerHeight();
				
				if( $.type( childH ) == 'number' ){
					TweenMax.to($$toolbar, 0.4, { autoAlpha:1, height:childH, ease:Sine.easeInOut, onUpdate:page_scroll_update });
					//TweenMax.to($widget, 0.4, { autoAlpha:1, ease:Sine.easeOut });
					TweenMax.to($top, 0.4, { autoAlpha:1, ease:Sine.easeOut });
				} else {
					//TweenMax.to($$toolbar, 0.1, { autoAlpha:0, height:0, ease:Sine.easeInOut, onUpdate:page_scroll_update });
					$$toolbar.css( 'opacity', 0 ).css( 'height', 0 );
				}
			}
			
		}
		
		function page_scroll_update() {
			
			page_scroll();
			window_resize();
			
		}
		
		// 위젯 메뉴
		$widget.each(function() {
			
			// 20160227 시작 - 닫기/속도 수정
			var $ui = $(this);
			var $toggle = $ui.find(".js-widget-toggle");
			var $toggleCross = $toggle.find(".js-cross");
			var $content = $ui.find(".js-widget-content");
			var $close = $ui.next(".js-layer-dim");
			
			TweenMax.set($content, { autoAlpha:0, scale:0.3 });
			$toggle.on("click", function(_e) {
				
				$ui.toggleClass("on");
				if ($ui.hasClass("on")) {
					$$page.css({ overflow:"hidden" });
					TweenMax.to(this, 0.3, { rotation:-144, ease:Sine.easeInOut });
					TweenMax.to($toggleCross, 0.3, { marginTop:2, rotation:9, ease:Sine.easeInOut });
					TweenMax.to($content, 0.3, { autoAlpha:1, scale:1, ease:Back.easeOut });
				} else {
					widget_close();
				}
				
			});
			
			$close.on("click", function(_e) {
				
				$ui.removeClass("on");
				widget_close();
				
			});
			
			function widget_close() {
				
				$$page.css({ overflow:"auto" });
				TweenMax.to($toggle, 0.3, { rotation:0, ease:Sine.easeOut });
				TweenMax.to($toggleCross, 0.3, { marginTop:1, rotation:0, ease:Sine.easeOut });
				TweenMax.to($content, 0.3, { autoAlpha:0, scale:0.3, ease:Sine.easeOut });
				
			}
			// 20160227 끝 - 닫기/속도 수정
			
		});
		
		// 툴바 메뉴 (상세)
		$toolBuy.each(function() {
			
			var $ui = $(this);
			var $open = $toolBuy.find(".offbox .btn-shoppingbag, .offbox .btn-buy");
			var $close = $ui.find(".js-btn-close");
			var $content = $ui.find(".js-onbox");
			
			$open.on("click", function(_e) {
				
				_e.preventDefault();
				$$toolbar.addClass("on");
				$content.show();
				TweenMax.to($content, 0.5, { bottom:0, ease:Cubic.easeInOut });
				
			});
			
			$close.on("click", function(_e) {
				
				TweenMax.to($content, 0.5, { bottom:"-100%", ease:Sine.easeOut,
					onComplete:function() {
						$content.hide();
						$$toolbar.removeClass("on");
					}
				});
				
			});
			
			$ui.on({
				"close":function() {
					$close.trigger("click");
				}
			});
			
		});
		
	});
