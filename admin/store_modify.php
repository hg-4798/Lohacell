<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


if(ord($_ShopInfo->getId())==0){
	alert_go('정상적인 경로로 접근하시기 바랍니다.','c');
}

//print_r($_POST);
//exit;

$type =     $_POST["type"];
$mode =     $_POST["mode"];
$sno =   $_POST["sno"];

$referer1 = '';


// 매장등록 시 브랜드 , 구분
$category = '<select name=sel_vender class="select">\n';
$category .= '<option value="">==== 전체 ====</option>\n';
while($ref2_data=pmysql_fetch_object($ref2_result)){
	$category .= '<option value="'.$ref2_data->vender.'" '.$selected[sel_vender][$ref2_data->vender].'>'.$ref2_data->brandname.'</option>\n';
}
$category .= '</select>';

$vender = '<select name="sel_category">\n';
foreach ($store_category as $k=>$v){
	$vender .= '<option value="'.$k.'">'.$v.'</option>\n';
}
$vender .= '</select>';

### 시간
$arr_hour = $arr_minute = array();
for ($i=0 ; $i < 24 ; $i++){
	if($i < 10){
		$i = '0'.$i;
	}
  $arr_hour[sprintf("%02d",$i)] = $i;
}
### 분
for ($i=0 ; $i < 60 ; $i++){
	if($i < 10){
		$i = '0'.$i;
	}
  $arr_minute[sprintf("%02d",$i)] = $i;
}

if ($mode=="insert") {
	
	list($chk_cnt)=pmysql_fetch("select count(*) as cnt from tblstore where store_code = '".$_POST['store_code']."'");
	if($chk_cnt > 0){
		echo "<script>alert('중복된 매장코드 입니다 다시입력 하세요.');opener.location.reload();window.close();</script>";
		exit;
	} else {
		//debug($_POST);exit;
		//$part_div = substr($_POST['store_code'],0,1);
		//$part_no = substr($_POST['store_code'],1,4);
		//$brandcd = substr($_POST['store_code'],5,6);
		
	    ## 매장 등록시..
	    //print_r($_POST);	0220-dereklee
	    $query = "  Insert into tblstore 
	                (name, address, phone, view, area_code, category, stime, etime, coordinate, store_code, regdt)
	                Values 
	                ('".$_POST['store_name']."', '".$_POST['address']."', '".$_POST['phone']."', '".$_POST['vw_flag']."', 
	                 '".$_POST['sel_area_code']."', '".$_POST['sel_category']."',  
	                 '".$_POST['shour'].":".$_POST['sminute']."', '".$_POST['ehour'].":".$_POST['eminute']."', 
	                 '".$_POST['coordinate']."', '".$_POST['store_code']."','".date("YmdHis")."')
	            ";
        //echo $query;exit;
	    pmysql_query($query,get_db_conn());
	
		echo "<script>alert('매장등록이 완료되었습니다.');opener.location.reload();window.close();</script>";
		exit;
	}
} else if ($mode=="modify" && !empty($sno)) {
    
	$display_yn = '';
	if($_POST['vw_flag'] == 0){
		$display_yn = 'Y';
	} else {
		$display_yn = 'N';
	}
	
	$Sync = new Sync();
	$arrayData = array(
			'name'    => $_POST['store_name'],				// 매장이름
			'address'    => $_POST['address'],					// 매장주소
			'stime'    => $_POST['shour'].":".$_POST['sminute'],	// 영업시간
			'etime'    => $_POST['ehour'].":".$_POST['eminute'],	// 영업시간
			'store_code'    => $_POST['store_code'],				// 매장코드
			'coordinate'    => $_POST['coordinate'],				// 매장좌표
			'vendor'    => $_POST['sel_vender'],					// 벤더
			'category'    => $_POST['sel_category'],				// 매장구분
			'area_code'    => $_POST['sel_area_code'],				// 지역
			'view'    => $display_yn,							// 노출여부
			'phone'    => 	$_POST['phone']						// 매장전화번호
	);
	
	/*
	 * $arrayData = array(
	 'name'    => $_POST['store_name'],				// 매장이름
	 'address'    => $_POST['address'],					// 매장주소
	 'stime'    => $_POST['shour'].":".$_POST['sminute'],	// 영업시간
	 'etime'    => $_POST['ehour'].":".$_POST['eminute'],	// 영업시간
	 'store_code'    => $_POST['store_code'],				// 매장코드
	 'coordinate'    => $_POST['coordinate'],				// 매장좌표
	 'vendor'    => $_POST['sel_vender'],					// 벤더
	 'category'    => $_POST['sel_category'],				// 매장구분
	 'area_code'    => $_POST['sel_area_code'],				// 지역
	 'view'    => $display_yn,							// 노출여부
	 'phone'    => 	$_POST['phone'],						// 매장전화번호
	 'owner_ph'    => 	$_POST['owner_ph']					// 담당자핸드폰
	 );
	 *
	 */
	
	$rtn = $Sync->StoreChange($arrayData);
	if( $rtn == "fail" ) {
		batchlog("[error] SyncCommerce API(StatusChange) failed ".json_encode_kr($arrayData));
	} else {
		## 매장 정보 수정하기..
		$query = "  Update tblstore Set
	                name = '".$_POST['store_name']."',
	                address = '".$_POST['address']."',
	                phone = '".$_POST['phone']."',
	                view = '".$_POST['vw_flag']."',
	                area_code = ".$_POST['sel_area_code'].",
	                category = '".$_POST['sel_category']."',
	                stime = '".$_POST['shour'].":".$_POST['sminute']."',
	                etime = '".$_POST['ehour'].":".$_POST['eminute']."',
	                coordinate = '".$_POST['coordinate']."',
	                store_code = '".$_POST['store_code']."'
	                Where sno = ".$sno."
	            ";

		pmysql_query($query,get_db_conn());
			
		echo "<script>alert('매장정보 수정이 완료되었습니다.');opener.location.reload();window.close();</script>";
		exit;
	}
    
} else {
    if(!$sno) {
        ## 매장 추가
        $selected[shour]['10'] = "selected";
        $selected[sminute]['30'] = "selected";
        $selected[ehour]['20'] = "selected";
        $selected[eminute]['00'] = "selected";
    } else {
        ## 매장정보 가져오기
        $query = "Select * from tblstore where sno = ".$sno."";
        $data = pmysql_fetch($query);
        //print_r($data);

        // 매장등록 시 브랜드 , 구분
//         $tem_ref_qry = "SELECT  a.vender,a.id,a.com_name,a.delflag, b.bridx, b.brandname
// 		            FROM    tblvenderinfo a
// 		            JOIN    tblproductbrand b on a.vender = b.vender AND b.vender = '".$data[vendor]."'";
		# 0220 -dereklee
        $tem_ref_qry = "SELECT  a.vender,a.id,a.com_name,a.delflag, b.bridx, b.brandname
		            FROM    tblvenderinfo a
		            JOIN    tblproductbrand b on a.vender = b.vender ";
        
        $tem_ref2_result=pmysql_query($tem_ref_qry);
        
        $category = '<select name=sel_vender class="select">\n';
        while($tem_ref2_data=pmysql_fetch_object($tem_ref2_result)){
        	if($data[vendor] == $tem_ref2_data->vender){
        		$category .= '<option value="'.$tem_ref2_data->vender.'" '.$selected[sel_vender][$tem_ref2_data->vender].' selected>'.$tem_ref2_data->brandname.'</option>\n';
        	} else {
	        	$category .= '<option value="'.$tem_ref2_data->vender.'" '.$selected[sel_vender][$tem_ref2_data->vender].'>'.$tem_ref2_data->brandname.'</option>\n';
        	}
        }
        $category .= '</select>';
        
        # 0220 -dereklee
        $vender = '<select name="sel_category">\n';
        foreach ($store_category as $k => $v){
        	if($data[category] == $k) {
		        $vender .= '<option value="'.$k.'" selected>'.$v.'</option>\n';
        	} else {
		        $vender .= '<option value="'.$k.'">'.$v.'</option>\n';
        	}
        }
        $vender .= '</select>';
        
        $tmp_stime = explode(":", $data[stime]);
        $data[shour] = $tmp_stime[0];
        $data[sminute] = $tmp_stime[1];
        $tmp_etime = explode(":", $data[etime]);
        $data[ehour] = $tmp_etime[0];
        $data[eminute] = $tmp_etime[1];
        $regdt = substr($data[regdt], 0, 4)."-".substr($data[regdt], 4, 2)."-".substr($data[regdt], 6, 2)." ".substr($data[regdt], 8, 2).":".substr($data[regdt], 10, 2).":".substr($data[regdt], 12, 2);

        $selected[sel_vender][$data[vendor]] = "selected";
        $selected[sel_area_code][$data[area_code]] = "selected";
        $selected[sel_category][$data[category]] = "selected";	
        $selected[vw_flag][$data[view]] = "selected";	
        $selected[shour][$data[shour]] = "selected";
        $selected[sminute][$data[sminute]] = "selected";
        $selected[ehour][$data[ehour]] = "selected";
        $selected[eminute][$data[eminute]] = "selected";
    }
}

?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
<title>매장관리</title>
<link rel="stylesheet" href="/admin/static/css/admin.css" type="text/css">
<link rel="stylesheet" href="/admin/static/css/style.css" type="text/css">
<script src="../js/jquery.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
//dereklee

function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 40;
	var oHeight = document.all.table_body.clientHeight + 160;
	window.resizeTo(oWidth,oHeight);
}

function CheckForm() {
	//alert(document.form1.sno.value);

	var store_name = document.form1.store_name.value;
    var addr = document.form1.address.value;
    var store_code_chk = document.form1.store_code_chk.value;
	var ph = document.form1.phone.value;
	var regExp = /^\d{2,3}-\d{3,4}-\d{4}$/;
	var sno = document.form1.sno.value;

	var store_code = document.form1.store_code.value;
	var regExp2 = /^[A-Z]\d{4}[A-Z]$/;

	// dereklee 
    if (store_name=="")  {
        alert("매장명을 입력해주세요.");
        $("input[name='store_name']").focus();
        return false;
    }else if (store_code_chk != '1' && sno ==''){
        alert("매장코드 중복확인 해주세요.");
        return false;
    }else if ( !regExp.test(ph) ) {
		alert("잘못된 매장전화 번호입니다. 숫자, - 를 포함한 숫자만 입력하세요.");
		return false;
	} else if (addr == ''){
		alert("주소는 필수 입력입니다.");
	    return false;
	}  else {
        if(document.form1.sno.value == "") {
            document.form1.mode.value = "insert";
        }else{
            document.form1.mode.value = "modify";
        }
        document.form1.submit();
    }
	/*
	else if (cj_deli_code == ''){
		alert("cj배송번호는 필수 입력입니다.");
	    return false
	}else if ( !regExp2.test(store_code) ) {
		alert("잘못된 매장코드 번호입니다. ex:A1234B 형태로 입력하세요.");
		return false
	}
	*/

	
}
$(document).ready(function(){

    $("#address").click(function (e) {  // keypresss, keyup, keydown 종류가 있음
        DaumPostcode();//실행할 이벤트
    });
    $("#store_code").change(function (e) {  // keypresss, keyup, keydown 종류가 있음
       $('#store_code_chk').val('0');
    });



    var sno = "<?=$sno?>";
    if(sno !=""){
        addressMap();
    }

});
//-->
</SCRIPT>
</head>
<link rel="styleSheet" href="/css/admin.css" type="text/css">
<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
<link rel="stylesheet" href="style.css" type="text/css">

<div class="pop_top_title"><p>매장 <?=( $sno == "" ? '등록' : '수정' )?></p></div>
<!-- <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" > -->
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="PageResize();" style="overflow-x:hidden; ">
<!-- <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" > -->
<TABLE WIDTH="790" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
<input type=hidden name=mode value="<?=$mode?>">
<input type=hidden name=sno value="<?=$sno?>">
<input type=hidden name=store_code_chk id=store_code_chk value="0">


<TR>
	<TD style="padding:5pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
        <div class="table_style01">
		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TR>
			<th><span>매장명</span></th>
			<TD class="td_con1"><input type=text name=store_name value="<?=$data['name']?>" style="width:100%;" class="input"></TD>
		</TR>
		<TR>
			<th><span>매장 코드</span></th>
			<TD class="td_con1">
			<? if(strlen($sno) == 0 ) {?>
				<input type=text name=store_code id="store_code" value="<?=$data['store_code']?>" style="width:50%;" class="input" >
                <input type="button" class="btn-basic h-small" value="중복확인" onclick="ValidStoreCode('1','');return false;">
			<? } else { ?>
				<input type=text name=store_code id="store_code" value="<?=$data['store_code']?>" style="width:50%;" class="input" readonly> 
			<? } ?>	
			</TD>
		</TR>
        <TR>
			<th><span>매장 구분</span></th>
			<TD class="td_con1">
				<!--  
                <select name="sel_category">
                <? foreach ($store_category as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[sel_category][$k]?>><?=$v?><? } ?>
                </select>
				-->
				<? echo $vender;?>
            </TD>
		</TR>
        <TR>
			<th><span>지역 선택</span></th>
			<TD class="td_con1">
                <select name="sel_area_code">
                <? foreach ($store_area as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[sel_area_code][$k]?>><?=$v?><? } ?>
                </select>
            </TD>
		</TR>
        <TR>
			<th><span>매장전화번호</span></th>
			<TD class="td_con1"><input type=text name=phone id="phone" value="<?=$data['phone']?>" style="width:100%;" class="input" maxlength=13></TD>
		</TR>
        <TR>
			<th><span>영업시간</span></th>
			<TD class="td_con1">
                <select name="shour">
                <? foreach ($arr_hour as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[shour][$k]?>><?=$v?><? } ?>
                </select> 시
                <select name="sminute">
                <? foreach ($arr_minute as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[sminute][$k]?>><?=$v?><? } ?>
                </select> 분
              ~
                <select name="ehour">
                <? foreach ($arr_hour as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[ehour][$k]?>><?=$v?><? } ?>
                </select> 시
                <select name="eminute">
                <? foreach ($arr_minute as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[eminute][$k]?>><?=$v?><? } ?>
                </select> 분
            </TD>
		</TR>
        <TR>
			<th><span>노출여부</span></th>
			<TD class="td_con1">
                <select name="vw_flag">
                <? foreach ($store_vwflag as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[vw_flag][$k]?>><?=$v?><? } ?>
                </select>
            </TD>
		</TR>
       <!--
		<TR>
			<th><span>담당자 핸드폰</span></th>
			<TD class="td_con1">
			    <?=$data['owner_ph']?>&nbsp;&nbsp;&nbsp;&nbsp;* 알림톡 발송 정보로 싱크커머스의 "점주 핸드폰"과 연동됩니다.
            </TD>
		</TR>
		-->
		<!-- ////////// end - dereklee -->
        <TR>
            <th><span>주소</span></th>
             <TD class="td_con1">
                <input type=text name=address id="address" value="<?=$data['address']?>" style="width:475px;" class="input" readonly="readonly">
                 <input type=hidden name=coordinate id="coordinate" value="<?=$data['coordinate']?>" style="width:98%;" class="input">
                 <input type="button" class="btn-basic h-small" value="검색" onclick="DaumPostcode();">
             </TD>
        </TR>
        <TR>
            <th><span>지도</span></th>
            <TD class="td_con1"><div id="map" style="height:400px;"></div></TD>
        </TR>
<?
if($_POST['sno']) {
?>
        <TR>
			<th><span>등록일</span></th>
			<TD class="td_con1"><?=$regdt?></TD>
		</TR>
<?
}
?>
		</TABLE>
        </div>
		</td>
	</tr>
	</table>
	</TD>
</TR>
<TR>
	<TD align=center>
        <input type="button" class="btn-point h-small" value="<?=($sno=="")? "등록":"수정";?>" onclick="CheckForm();">
        <input type="button" class="btn-basic h-small" value="닫기" onclick="javascript:window.close();">
    </TD>
</TR>
</form>
</TABLE>


<!--<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>-->
<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
<script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=052ced9432ff069991ac27a825f7dc9b&libraries=services"></script>
<script>
    var mapContainer = document.getElementById('map'), // 지도를 표시할 div
        mapOption = {
            center: new daum.maps.LatLng(37.50344605760512, 127.03599581836751), // 지도의 중심좌표
            level: 5 // 지도의 확대 레벨
        };

    //지도를 미리 생성
    var map = new daum.maps.Map(mapContainer, mapOption);
    //주소-좌표 변환 객체를 생성
    var geocoder = new daum.maps.services.Geocoder();
    //마커를 미리 생성
    var marker = new daum.maps.Marker({
        position: new daum.maps.LatLng(37.50344605760512, 127.03599581836751),
        map: map
    });
    daum.maps.event.addListener(map, 'click', function(mouseEvent) {

        // 클릭한 위도, 경도 정보를 가져옵니다
        var latlng = mouseEvent.latLng;

        // 마커 위치를 클릭한 위치로 옮깁니다
        marker.setPosition(latlng);

        $("input[name='coordinate']").val(latlng.getLat()+'|'+latlng.getLng());
    });


    function DaumPostcode() {

        new daum.Postcode({
            oncomplete: function(data) {
                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 주소 정보를 해당 필드에 넣는다.
                document.getElementById("address").value = fullAddr;
                // 주소로 상세 정보를 검색
                geocoder.addressSearch(data.address, function(results, status) {
                    // 정상적으로 검색이 완료됐으면
                    if (status === daum.maps.services.Status.OK) {
                         var result = results[0]; //첫번째 결과의 값을 활용

                        // 해당 주소에 대한 좌표를 받아서
                        var coords = new daum.maps.LatLng(result.y, result.x);
                        $("input[name='coordinate']").val(result.y+'|'+result.x);
                        // 지도를 보여준다.
                        mapContainer.style.display = "block";
                        map.relayout();
                        // 지도 중심을 변경한다.
                        map.setCenter(coords);
                        // 마커를 결과값으로 받은 위치로 옮긴다.
                        marker.setPosition(coords)
                    }
                });
            }
        }).open();
    }


 function addressMap() {
        var coordinate = $('#coordinate').val().split('|');

        if(coordinate !="") {
            var markerPosition = new daum.maps.LatLng(coordinate[0], coordinate[1]);
            var mapContainer = document.getElementById('map'), // 지도를 표시할 div
                mapOption = {
                    center: new daum.maps.LatLng(coordinate[0], coordinate[1]), // 지도의 중심좌표
                    level: 3, // 지도의 확대 레벨
                    marker: marker // 이미지 지도에 표시할 마커
                };

            // 지도를 생성합니다
            var map = new daum.maps.Map(mapContainer, mapOption);
            var marker = new daum.maps.Marker({
                map: map,
                position: markerPosition
            });
            daum.maps.event.addListener(map, 'click', function(mouseEvent) {

                // 클릭한 위도, 경도 정보를 가져옵니다
                var latlng = mouseEvent.latLng;

                // 마커 위치를 클릭한 위치로 옮깁니다
                marker.setPosition(latlng);

                $("input[name='coordinate']").val(latlng.getLat()+'|'+latlng.getLng());
            });


        }else {
             // 주소 정보를 해당 필드에 넣는다.
            addr= $('#address').val();
            // 주소로 상세 정보를 검색
            geocoder.addressSearch(addr, function (results, status) {
             // 정상적으로 검색이 완료됐으면
             if (status === daum.maps.services.Status.OK) {
                var result = results[0]; //첫번째 결과의 값을 활용
                 // 해당 주소에 대한 좌표를 받아서
                 var coords = new daum.maps.LatLng(result.y, result.x);
                 $("input[name='coordinate']").val(result.y + '|' + result.x);
            }
            var markerPosition = new daum.maps.LatLng(result.y, result.x);
            var mapContainer = document.getElementById('map'), // 지도를 표시할 div
                 mapOption = {
                     center: new daum.maps.LatLng(result.y, result.x), // 지도의 중심좌표
                     level: 3, // 지도의 확대 레벨
                     marker: marker // 이미지 지도에 표시할 마커
                 };

                        // 지도를 생성합니다
                        var map = new daum.maps.Map(mapContainer, mapOption);
                        var marker = new daum.maps.Marker({
                            map: map,
                            position: markerPosition
                        });
                daum.maps.event.addListener(map, 'click', function(mouseEvent) {

                    // 클릭한 위도, 경도 정보를 가져옵니다
                    var latlng = mouseEvent.latLng;

                    // 마커 위치를 클릭한 위치로 옮깁니다
                    marker.setPosition(latlng);

                    $("input[name='coordinate']").val(latlng.getLat()+'|'+latlng.getLng());
                });
             });

        }
    }



    function ValidStoreCode(jointype, type) { //매장코드 유효성 체크

        var val	= $("input[name=store_code]").val();
        if (val == '') {
            alert("매장코드를 입력해 주세요.");
            $("input[name=store_code]").focus();
            return;
        }  else {
            $.ajax({
                type: "GET",
                url: "/admin/proc/store_code.proc.php",
                data: "store_code=" + val + "&mode=store_code",
                dataType:"json",
                success: function(data) {
                    if(data.code=='0'){
                        alert(data.msg);
                        $("input[name=store_code]").focus();
                        return;
                    }else{
                        $("#store_code_chk").val('1');
                        alert('사용 가능한 매장코드 입니다.');
                        return;
                    }
                },
                error: function(result) {
                    alert("에러가 발생하였습니다.");
                    $("input[name=store_code]").focus();
                }
            });

            }
    }
</script>


</body>
</html>
