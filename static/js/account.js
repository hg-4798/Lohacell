var Account = {
    init: function (toid) {
        var me = this;
    },
    acnt_chk: function () {
        var bank_code = $("#bank_code").val();
        var owner_nm = $("#depositor").val();
        var account_no = $("#account_num").val();

        if (!bank_code) {
            UI.alert("은행명을 선택해주세요.");
            return false;
        }

        if (!account_no) {
            UI.alert("계좌번호를 입력해주세요.");
            return false;
        }

        if (!owner_nm) {
            UI.alert("예금주를 입력해주세요.");
            return false;
        }

        $.ajax({
            cache: false,
            type: 'POST',
            url: '../third_party/pg/NHNKCP/hub_account.php',
            //contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
            data: "pay_method=ACCO&req_tx=pay&bank_code=" + bank_code + "&owner_nm=" + owner_nm + "&account_no=" +
            account_no,
            async: false,
            dataType: 'json',
            success: function (r) {
                if (r.success) {
                    UI.alert(r.msg);
                    $('.refund_account').prop('readonly', true);
                    $("#bank_code").attr("disabled", true);
                    $("#re_auth").removeClass('hide');
                    $("#confirm_account").addClass('hide');
                    $("#bank_code_val").val(bank_code);
                    $("#bank_checked").val("1");
                } else {
                    UI.error(r.msg);
                    //실패시 로그 파일 확인 필요 (paygate/A/payplus/log 폴더 확인)
                    $("#bank_checked").val("0");
                }
            },
            error: function (result) {
                UI.error("에러가 발생하였습니다.");
                $("#bank_checked").val("0");
            }
        });
    },
    acnt_del: function () {
        $("#re_auth").addClass('hide');
        $("#confirm_account").removeClass('hide');
        $('.refund_account').removeAttr('readonly');
        $("#bank_code").removeAttr("disabled");
        $(".refund_account").val("");
        $("#bank_checked").val("0");
    }
};