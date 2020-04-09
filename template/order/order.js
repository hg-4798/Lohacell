var Order = {
	init: function (toid) {
		var me = this;
		me.toid = toid;
		var option = $.extend({}, validation_option, {
			validateNonVisibleFields: false,
			onValidationComplete: function (form, status) {
				if (status) me.pay();
			}
		});
		$("#FrmOrder").validationEngine('attach', option);


		me.setUI();

		$('[data-paymethod]').on('click', function () {
			var paymethod = $(this).data("paymethod");

			$('#pay_method').val(paymethod);
			if (paymethod == 'card') $('#bank_div').addClass('hide');
			else $('#bank_div').removeClass('hide');

		});

		//숫자체크
		$('[data-filter="numeric"], [data-filter="price"]').on('keydown.row input.row', function (evt) {
			var v = $(this).val();
			v = v.replace(/[^0-9]/g, '');

			var filter = $(this).data('filter');
			if (filter == 'price') {
				v = v.replace(/^0+(?!$)/, ''); //전화번호 입력 부분에서 0으로 시작하게 입력하면 사라지기때문에 가격에서만 처리되게 분기 안으로 옮김 20100114 bshan
				v = UI.numberFormat(v);
			}
			$(this).val(v);
		});

		//배송지정보
		$('[name="delivery_choice"]').on('click', function (e) {
			switch (this.value) {
				default:
				case 'reset':
					$('#section_receiver').find('input, select').each(function () {
						if (/receiver_memo|receiver_memo_etc/.exec(this.name)) { //리셋제외
							return true;
						}
						$(this).val('');
					});
					break;
				case 'copy': //주문고객과 동일
					$('[data-match]').each(function () {
						var field_id = $(this).data('match');
						$('#' + field_id).val(this.value);
					});
					me.calcDelivery();
					break;
				case 'member': //회원정보와 동일
				case 'lastest': //최근배송지
					me.getAddress({
						mode: 'address',
						act: this.value
					});
					break;
			}

		
		});

		$('[name="use_point"], [name="use_mileage"]').on('blur', function() {
			
			var maximum = $(this).data('maximum');
			var cut = $(this).data('cut');
			var with_coupon = $(this).data('with-coupon'); //쿠폰동시사용여부

			var v = parseInt(this.value.replace(/,/g,""));
			if(v > maximum) v = maximum;

			$(this).val(UI.numberFormat(v));

			if(this.name == 'use_point') {
				if(v > 0) $('[name="use_mileage"]').val('0').attr('disabled', true);
				else $('[name="use_mileage"]').removeAttr('disabled');
			}
			else {
				if(v > 0) $('[name="use_point"]').val('0').attr('disabled', true);
				else $('[name="use_point"]').removeAttr('disabled');
			}

			if(v > 0 && with_coupon == 'N') {
				$('button[id^="btn_coupon"]').attr('disabled',true);
			}
			else {
				$('button[id^="btn_coupon"][data-disabled!="Y"]').removeAttr('disabled');
			}

			me.calc();
		});

		$('#buyer_email_domain').on('change', function(i,e){
			if(this.value == 'etc') $('#buyer_email_etc').val('');
			else  $('#buyer_email_etc').val(this.value);
		});
	},
	calcDelivery: function() {
		//지역배송비체크
		$.ajax({
			url:'/proc/order.proc.php',
			dataType:'json',
			type:'POST',
			data:{
				mode:'address',
				act:'fee',
				zipcode:$('#order_address').val()
			},
			success: function(r) {
				if(r.success) {
					//console.log(r.data.fee);
					var mag = "지역별 추가 배송비 부과지역입니다.";
					var frm = document.FrmOrder;
					if(frm.coupon_delivery_discount.value > 0) {
						mag+="<br>무료배송쿠폰이 초기화됩니다.";
					}
					UI.alert(mag, function() {
						$('#pay_delivery').val(r.data.fee);
						if(frm.coupon_delivery.value > 0) $('#btn_coupon_delivery').trigger('click');
						$('#btn_coupon_delivery').attr('disabled',true);
						Order.calc();
					});
				}
				else {
					var fee = $('#pay_delivery_origin').val();
					$('#pay_delivery').val(fee);
					$('#btn_coupon_delivery').removeAttr('disabled');
					Order.calc();
				}
			
			}
		});
	},
	calc: function() { //결제금액계산
		var frm = document.FrmOrder;

		//총상품금액
		var total =  UI.toInt(frm.sum_end.value);
		var total_end = UI.toInt(frm.pay_total.value); //배송비포함금액

		//- 상품쿠폰할인금액
		var sub_coupon_product =  UI.toInt(frm.coupon_product_discount.value);
		$('[data-match="coupon_product_discount"]').text(UI.numberFormat(sub_coupon_product));
		if(sub_coupon_product>0) {
			$('#btn_coupon_product').removeClass('btn-basic').addClass('btn-line').text('적용취소');
			$('#btn_coupon_basket').attr("disabled", true);
		}

		// - 장바구니쿠폰 할인금액
		var sub_coupon_basket =  UI.toInt(frm.coupon_basket_discount.value);
		$('[data-match="coupon_basket_discount"]').text(UI.numberFormat(sub_coupon_basket));
		if(sub_coupon_basket>0) {
			$('#btn_coupon_basket').removeClass('btn-basic').addClass('btn-line').text('적용취소');
			$('#btn_coupon_product').attr("disabled", true);
		}


		var sub_coupon = parseInt(sub_coupon_product)+parseInt(sub_coupon_basket);
		$('#sub_discount').text(UI.numberFormat(sub_coupon));

		//전체쿠폰사용액
		var sub_coupon_all = sub_coupon+UI.toInt(frm.coupon_delivery_discount.value);

		var after_coupon = total - sub_coupon; //쿠폰사용후 결제액 
		
		//- 마일리지사용금액
		var sub_mileage = 0;
		if(typeof frm.use_mileage != 'undefined') {
			var cfg_mileage = $(frm.use_mileage).data();
			if(cfg_mileage.withCoupon == 'N' && sub_coupon_all > 0) { //쿠폰동시사용 불가이며 쿠폰이 적용되어있는경우
				frm.use_mileage.value = 0;
				frm.use_mileage.disabled = true;
			}

			sub_mileage =  UI.toInt(frm.use_mileage.value);
			if(sub_mileage > 0) { //마일리지 사용하는경우
				after_coupon = after_coupon*cfg_mileage.rate*0.01;
				if(sub_mileage > after_coupon) sub_mileage = after_coupon;
				sub_mileage = Math.floor(sub_mileage/cfg_mileage.cut)*cfg_mileage.cut;//절사
				frm.use_mileage.value = UI.numberFormat(sub_mileage);
			}
			
			$('#use_mileage').text(UI.numberFormat(sub_mileage)); //사용마일리지 표시
		}
		
		//- 포인트사용금액
		var sub_point = 0;
		if(typeof frm.use_point != 'undefined') {
			var cfg_point = $(frm.use_point).data();
			
			if(cfg_point.withCoupon == 'N' && sub_coupon_all > 0) {
				frm.use_point.value = 0;
				frm.use_point.disabled = true;
			}

			sub_point =  UI.toInt(frm.use_point.value);
			if(sub_point > 0) {
				after_coupon = after_coupon*cfg_point.rate*0.01;
				if(sub_point > after_coupon) sub_point = after_coupon;
				sub_point = Math.floor(sub_point/cfg_point.cut)*cfg_point.cut;//절사
				frm.use_point.value = UI.numberFormat(sub_point);
			}
			
			$('#use_point').text(UI.numberFormat(sub_point)); //사용포인트 표시
		}

		//+배송비
		var add_delivery =  UI.toInt(frm.pay_delivery.value);
		//-배송비쿠폰 할인금액
		var sub_delivery = UI.toInt(frm.coupon_delivery_discount.value);
		$('[data-match="coupon_delivery_discount"]').text(UI.numberFormat(sub_delivery));
	
		if(sub_delivery>0) {
			$('#btn_coupon_delivery').removeClass('btn-basic').addClass('btn-line').text('적용취소');
			add_delivery-=sub_delivery;
		}
		$('#add_delivery').text(UI.numberFormat(add_delivery));

		//= 총결제금액
		var pay_pg = total-sub_coupon-sub_mileage-sub_point+add_delivery;
		$('#pay_pg').text(UI.numberFormat(pay_pg));
		$('#pay_total').val(pay_pg);
		
	},
	getAddress: function (param) {
		$.ajax({
			url: '/proc/order.proc.php',
			data: param,
			dataType: 'json',
			type: 'POST',
			success: function (r) {
				$.each(r.data.address, function (i, v) {
					$('[name="' + i + '"]').val(v);
				});

				me.calcDelivery();
			}
		});
	},
	setUI: function () {
		$('[data-cond]').each(function (i, e) {
			var cond = $(e).data('cond');
			$('[name="' + cond + '"]').on('click, change', function () {
				$(e).addClass('hide');
				$(e).filter('[data-cond-value~="' + this.value + '"]').removeClass('hide');
			});
		});
	},
	post: function () {
		var me = this;
		new daum.Postcode({
			oncomplete: function (data) {
				$.each(data, function (i, e) {
					$('input[data-post="' + i + '"]').val(e);
				});

				me.calcDelivery();
			}
		}).open();
	},
	openCoupon: function (e) {
		var frm = document.FrmOrder;
		
		if($(e).hasClass('btn-line')) { //선택된상태인경우 리셋처리
			switch (e.id) {
				case 'btn_coupon_product': //상품쿠폰 리셋
					$.ajax({
						url:'/proc/order.proc.php',
						data:{
							mode:'coupon',
							act:'reset',
							toid: Order.toid
						},
						dataType:'json',
						type:'POST',
						success: function(r) {
							if(r.success) {
								frm.coupon_product_discount.value = 0;
								$(e).removeClass("btn-line").addClass("btn-basic").text('쿠폰적용');
								$('#btn_coupon_basket[data-count!=0]').removeAttr("disabled");
								Order.calc();
							}
						}
					});
					break;
				case 'btn_coupon_basket': //장바구니 쿠폰 리셋
					frm.coupon_basket_discount.value = 0;
					frm.coupon_basket.value = '';
					$(e).removeClass("btn-line").addClass("btn-basic").text('쿠폰적용');
					
					$('#btn_coupon_product').removeAttr("disabled");
					Order.calc();
					break;
				case 'btn_coupon_delivery': //무료배송쿠폰 리셋
					frm.coupon_delivery_discount.value = 0;
					frm.coupon_delivery.value = '';
					$(e).removeClass("btn-line").addClass("btn-basic").text('쿠폰적용');
					Order.calc();
					break;
			}
			frm.use_mileage.disabled = false;
			frm.use_point.disabled = false;
		}
		else {
			UI.modal('/front/order/order.coupon.php', '쿠폰적용 목록', {
				toid: Order.toid,
				id:e.id
			});
		}
		
	},
	openDelivery: function () {
		UI.modal('/front/order/order.delivery.php', '배송지 목록');
	},
	openBenefit: function () { //카드사혜택
		UI.modal('/front/order/order.benefit.php', '카드사 혜택');
	},
	pay: function () {
		if ($('#pay_method').val() !='card' && $('#bank_checked').val()<1) {
			UI.warning('환불계좌를 등록해주세요.');
			return false;
		}
		var formdata = $('#FrmOrder').serialize();
		$.ajax({
			url: '/proc/order.proc.php',
			data: formdata,
			dataType: 'json',
			type: 'POST',
			success: function (r) {
				if (r.success) {
					$('#order_form').load(r.data.return_url, {
						order_num: r.data.order_num
					});
				} else {
					UI.error(r.msg);
				}
			}
		});
	}
};

