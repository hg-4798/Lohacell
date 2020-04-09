if(typeof $.datepicker != 'undefined') {
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

var ADMIN = {
	crm:function(id) {
		document.crmview.id.value = id;
		document.crmview.search_keyword.value = id;
		window.open("about:blank","crm_view","scrollbars=yes,width=100,height=100,resizable=yes");
		document.crmview.target="crm_view";
		document.crmview.submit();
	},
	crm_memo:function(id) {
		document.crmview.menu.value = 'mem_memo';
		document.crmview.id.value = id;
		document.crmview.search_keyword.value = id;
		window.open("about:blank","crm_view","scrollbars=yes,width=100,height=100,resizable=yes");
		document.crmview.target="crm_view";
		document.crmview.submit();
	},
	order: function(order_num) {
		window.open('/admin/order/order_view.php?orn='+order_num, 'order_view', 'width=1200, height=1000');
	},
	product: function(pr_type, productcode) {
		var href = "/admin/product/product_input.php?prtype=" + pr_type;
		if (productcode) {
			href += "&productcode=" + productcode;
			window.open(href, "register", "width=1500,height=700,scrollbars=yes,status=no");
		} else {
			document.location.href = href;
		}
	},
	admin_memo: function(order_num) {
		UI.modal('/admin/order/memo.inc.php', '메모', {order_num:order_num});
	},
	cancel: function(order_num) { //취소접수
		window.open('/admin/order/cancel.php?orn='+order_num, 'order_cancel', 'width=1000, height=800');
	},
	refund: function(order_num) { //취소(환불)접수
		window.open('/admin/order/refund.php?orn='+order_num, 'order_refund', 'width=1000, height=800');
	},
	exchange:function(order_num) { //교환접수
		window.open('/admin/order/exchange.php?orn='+order_num, 'order_exchange', 'width=1000, height=800');
	},
	exchangeCS:function(cs_idx) {
		window.open('/admin/cscenter/cs_exchange.pop.php?csidx='+cs_idx, 'cs_exchange', 'width=1000, height=800');
	},
	return: function(order_num, return_idx) { //반품접수
		var uri = '/admin/order/return.php?orn='+order_num;
		if(return_idx) uri+='&idx='+return_idx;
		window.open(uri, 'order_refund', 'width=1000, height=800');
	},
	returnCS:function(cs_idx) {
		window.open('/admin/cscenter/cs_return.pop.php?csidx='+cs_idx, 'cs_return', 'width=1100, height=800');
	},
	changeStatus: function(status, order_num, callback) { //주문상태변경
		var msg;
		switch(status) {
			case 1:
				msg = "입금대기 상태로 변경하시겠습니까?";
				break;
			case 2:
				msg = "결제완료 상태로 변경하시겠습니까?";
				break;
			case 3:
				msg = "배송준비중 상태로 변경하시겠습니까?";
				break;
			case 4:
				msg = "배송중 상태로 변경하시겠습니까?";
				break;
			case 5:
				msg = "배송완료 상태로 변경하시겠습니까?";
				break;
			case 6:
				msg = "구매확정 상태로 변경하시겠습니까?";
				break;
		}
		
		UI.confirm(msg, function() {

			$.ajax({
				url:'/admin/proc/order.proc.php',
				type:'POST',
				data:{
					mode:'change',
					act:'order_status',
					field:'order_num',
					order_num:order_num,
					order_status:status
				},
				dataType:'json',
				type:'POST',
				success: function(r) {
					if(r.success) {
						UI.alert(r.msg, function() {
							if($.isFunction(callback)) {
								callback();
							}
							else document.location.reload();
						});
					}
					else {
						UI.error(r.msg);
					}
				}

			})
		});
	}
}