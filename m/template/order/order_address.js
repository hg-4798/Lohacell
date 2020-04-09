var OrderAddress = {
	open: function() {//배송지목록오픈
		UI.popup('/m/order/order.delivery.php', '배송지 목록');
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

				OrderAddress.callback();
			}
		});
	},
	post: function () {
		var me = this;
		new daum.Postcode({
			oncomplete: function (data) {
				$.each(data, function (i, e) {
					$('input[data-post="'+i+'"]').val(e);
				})
				$('#receiver_addr_detail').focus();

				OrderAddress.callback();
			}
		}).open();
	},
	callback: function() {
	}
};