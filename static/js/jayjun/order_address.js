var OrderAddress = {
	open: function() {//배송지목록오픈
		UI.modal('/front/order/order.delivery.php', '배송지 목록');
	},
	setAddress: function(mode) {
		switch (mode) {
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
				break;
			case 'member': //회원정보와 동일
			case 'lastest': //최근배송지
				OrderAddress.getAddress({
					mode: 'address',
					act: mode
				});
				break;
		}
	},
	getAddress: function(param) {
		$.ajax({
			url:'/proc/order.proc.php',
			data:param,
			dataType:'json',
			type:'POST',
			success: function(r) {
				$('#pop_buyer_name2').focus();
				$.each(r.data.address, function(i,v){
					$('[name="'+i+'"]').val(v);
				});

			}
		})
	},
	post: function () {
		var me = this;
		new daum.Postcode({
			oncomplete: function (data) {
				$.each(data, function (i, e) {
					$('input[data-post="' + i + '"]').val(e);
				});
				$('#receiver_addr_detail').focus();
			}
		}).open();
	}
};