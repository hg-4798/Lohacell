<!-- start container -->
<div id="container" >



<input type=hidden name=tempkey>
<input type=hidden name=ordercode>
<input type=hidden name=type>
<input type=hidden name=ordercodeid value="<?=$ordercodeid?>">
<input type=hidden name=ordername value="<?=$ordername?>">


<?
$subTop_flag = 3;
//include ($Dir.MainDir."sub_top.php");
?>



<!-- 반품 신청 dimm layer -->
<div class="layer-dimm-wrap layer-refund">
    <div class="dimm-bg"></div>
    <div class="layer-inner">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">

			<h4 class="table-title">반품신청</h4>
			<table class="th-top util">
				<colgroup>
					<col style="width:110px"><col style="width:100px"><col style="width:auto"><col style="width:100px"><col style="width:100px"><col style="width:100px"><col style="width:110px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">상품금액</th>
						<th scope="col">배송비</th>
						<th scope="col">할인금액</th>
						<th scope="col">결제금액</th>
					</tr>
				</thead>
				<tfoot class="refund-money">
					<tr>
						<td class="ment" colspan="7">[확인사항] 할인금액을 제외한 나머지 금액으로 취소됩니다.</td>
					</tr>
					<tr>
						<td class="last" colspan="7">환불 예정금액 <strong>100,000</strong><span>(환금예정 마일리지 1,000M)</span></td>
					</tr>
				</tfoot>
				<tbody>
					<tr class="wish-item-tr">
						<td>2016-02-01</td>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><del>200,000</del><br><strong>200,000</strong></td>
						<td>3,000</td>
						<td>3,000</td>
						<td><strong>100,00</strong></td>
					</tr>
				</tbody>
			</table>

			<h4 class="table-title mt-50">환불방법 확인 및 기타사항</h4>
			<table class="th-left util">
				<caption>환불방법 선택</caption>
				<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
				<tr>
					<th scope="row">반품사유</th>
					<td class="reason">
						<input type="radio" name="reason-type" id="refund-type1" class="radio-def">
						<label for="refund-type1">단순변심(색상/사이즈)</label>
						<input type="radio" name="reason-type" id="refund-type2" class="radio-def">
						<label for="refund-type2">상품불량/파손</label>
						<input type="radio" name="reason-type" id="refund-type3" class="radio-def">
						<label for="refund-type3">배송누락/오배송</label>
						<input type="radio" name="reason-type" id="refund-type4" class="radio-def">
						<label for="refund-type4">기타</label>
					</td>
				</tr>
				<tr>
					<th scope="row">반품 배송비</th>
					<td>
						<p><strong>5,000원</strong>(반송배송비 2,500원 + 추가배송비 2,500원)</p>
						<p>박스안에 반품배송비를 반드시 동봉해주세요!</p>
					</td>
				</tr>
				<tr>
					<th scope="row">환불방법</th>
					<td>계좌입금(휴대폰결제/무통장입금의 경우는 계좌입금만 가능)</td>
				</tr>
				<tr class="account-info">
					<th scope="row">환불계좌정보</th>
					<td>
						<div class="select small">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value">은행선택</button>
							<ul class="a_list">
								<li><a href="#1">국민은행</a></li>
								<li><a href="#2">우체국</a></li>
							</ul>
						</div>
						<label for="account-name">예금주</label>
						<input type="text" id="account-name" class="input-def w100">
						<label for="account-number">계좌번호</label>
						<input type="text" id="account-number" class="input-def w200">
						<button class="btn-dib-line ">계좌 실명인증</button>
					</td>
				</tr>
				<tr>
					<th scope="row">파일첨부</th>
					<td class="imageAdd">
						<input type="file" id="add-image">
						<label for="add-image">파일선택</label>
						<div class="txt-box">선택된 파일 없음</div>
					</td>
				</tr>
			</table>

			<h4 class="table-title mt-50">택배기사가 방문해야 하는 주소 <span>택배사에 별도로 신청하지 않아도 <strong>2~3일 내 택배 기사가 방문하여 상품을 회수</strong>해 갈 예정입니다.</span></h4>
			<table class="th-left util">
				<caption>환불방법 선택</caption>
				<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
				<tr>
					<th scope="row">배송지 선택</th>
					<td>
						<input type="radio" name="address-type" id="address-same" class="radio-def">
						<label for="address-same">받으신 주소와 동일</label>
						<input type="radio" name="address-type" id="address-new" class="radio-def">
						<label for="address-new">새로운 수거지</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="name">이름</label></th>
					<td><input type="text" name="" id="name" class="input-def w200" title="이름 입력자리"></td>
				</tr>
				<tr>
					<th scope="row"><label for="phone-num">휴대전화</label></th>
					<td>
						<div class="select small">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value">010</button>
							<ul class="a_list">
								<li><a href="#1">010</a></li>
								<li><a href="#2">011</a></li>
							</ul>
						</div>
						<span class="txt-lh">-</span>
						<input type="text" name="" id="phone-num" class="input-def w70" title="전화번호 가운데 입력자리">
						<span class="txt-lh">-</span>
						<input type="text" name="" class="input-def w70" title="전화번호 마지막 입력자리">
					</td>
				</tr>
				<tr>
					<th scope="row">주소</th>
					<td>
						<ul>
							<li>
								<input type="text" name="" id="phone-num" class="input-def w70" title="전화번호 가운데 입력자리">
								<span class="txt-lh">-</span>
								<input type="text" name="" class="input-def w70" title="전화번호 마지막 입력자리">
								<button class="btn-dib-line ">우편번호</button>
							</li>
							<li class="mt-5">
								<input type="text" class="input-def w400" title="주소 입력자리">
								<input type="text" class="input-def w200" title="주소 상세 입력자리">
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="delivery-message">배송 요청사항</label></th>
					<td><input type="text"  id="delivery-message" class="input-def w400"></td>
				</tr>
			</table>
			<div class="mypage-content-wrap">
				<dl class="attention">
					<dt>반품신청 이용안내</dt>
					<dd>해당 상품을 반품하려는 사유를 꼭 선택해 주세요.</dd>
					<dd>상품이 손상/훼손 되었거나 이미 사용하셨다면 반품이 불가능합니다.</dd>
					<dd>반품 사유가 단순변심, 구매자 사유일 경우반품 배송비를 상품과 함께 박스에 동봉해 주세요.</dd>
					<dd>배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다.</dd>
					<dd>반품 사유가 상품불량/파손, 배송누락/오배송 등 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다.</dd>
					<dd>상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다.</dd>
				</dl>
			</div>
			<div class="btn-place mt-30">
				<button class="btn-dib-function" type="submit"><span>신청</span></button>
				<button class="btn-dib-function line" type="button"><span>취소</span></button>
			</div>


        </div><!-- //.layer-content -->
    </div>
</div>

<!-- 교환신청 신청 dimm layer -->
<div class="layer-dimm-wrap layer-refund change">
    <div class="dimm-bg"></div>
    <div class="layer-inner">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">

			<h4 class="table-title">교환신청</h4>
			<table class="th-top util">
				<colgroup>
					<col style="width:110px"><col style="width:100px"><col style="width:auto"><col style="width:100px"><col style="width:100px"><col style="width:100px"><col style="width:110px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">상품금액</th>
						<th scope="col">배송비</th>
						<th scope="col">할인금액</th>
						<th scope="col">결제금액</th>
					</tr>
				</thead>
				<tbody>
					<tr class="wish-item-tr">
						<td>2016-02-01</td>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><del>200,000</del><br><strong>200,000</strong></td>
						<td>3,000</td>
						<td>3,000</td>
						<td><strong>100,00</strong></td>
					</tr>
				</tbody>
			</table>

			<h4 class="table-title mt-50">교환 사유 및 정보</h4>
			<table class="th-left util">
				<caption>교환사유 선택</caption>
				<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
				<tr>
					<th scope="row">교환 사유</th>
					<td class="reason">
						<input type="radio" name="reason-type" id="change-type1" class="radio-def">
						<label for="change-type1">단순변심(색상/사이즈)</label>
						<input type="radio" name="reason-type" id="change-type2" class="radio-def">
						<label for="change-type2">상품불량/파손</label>
						<input type="radio" name="reason-type" id="change-type3" class="radio-def">
						<label for="change-type3">배송누락/오배송</label>
						<input type="radio" name="reason-type" id="change-type4" class="radio-def">
						<label for="change-type4">기타</label>
					</td>
				</tr>
				<tr>
					<th scope="row">교환 배송비</th>
					<td>
						<p><strong>5,000원</strong></p>
						<p>박스안에 반품배송비를 반드시 동봉해주세요!</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="exchange-info">교환 상품 정보</label></th>
					<td><textarea id="exchange-info" cols="30" rows="10" class="textarea-def" placeholder="교환은 같은 옵션만 가능합니다."></textarea></td>
				</tr>
				<tr>
					<th scope="row">파일첨부</th>
					<td class="imageAdd">
						<input type="file" id="add-image">
						<label for="add-image">파일선택</label>
						<div class="txt-box">선택된 파일 없음</div>
					</td>
				</tr>
			</table>

			<h4 class="table-title mt-50">상품수거지 주소(택배기사가 방문해야 하는 주소) <span>택배사에 별도로 신청하지 않아도 <strong>2~3일 내 택배 기사가 방문하여 상품을 회수</strong>해 갈 예정입니다.</span></h4>
			<table class="th-left util">
				<caption>환불방법 선택</caption>
				<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
				<tr>
					<th scope="row">배송지 선택</th>
					<td>
						<input type="radio" name="address-type" id="address-same2" class="radio-def">
						<label for="address-same2">받으신 주소와 동일</label>
						<input type="radio" name="address-type" id="address-new2" class="radio-def">
						<label for="address-new2">새로운 수거지</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="name2">이름</label></th>
					<td><input type="text" name="" id="name2" class="input-def w200" title="이름 입력자리"></td>
				</tr>
				<tr>
					<th scope="row"><label for="phone-num2">휴대전화</label></th>
					<td>
						<div class="select small">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value">010</button>
							<ul class="a_list">
								<li><a href="#1">010</a></li>
								<li><a href="#2">011</a></li>
							</ul>
						</div>
						<span class="txt-lh">-</span>
						<input type="text" name="" id="phone-num2" class="input-def w70" title="전화번호 가운데 입력자리">
						<span class="txt-lh">-</span>
						<input type="text" name="" class="input-def w70" title="전화번호 마지막 입력자리">
					</td>
				</tr>
				<tr>
					<th scope="row">주소</th>
					<td>
						<ul>
							<li>
								<input type="text" name="" id="" class="input-def w70" title="전화번호 가운데 입력자리">
								<span class="txt-lh">-</span>
								<input type="text" name="" class="input-def w70" title="전화번호 마지막 입력자리">
								<button class="btn-dib-line ">우편번호</button>
							</li>
							<li class="mt-5">
								<input type="text" class="input-def w400" title="주소 입력자리">
								<input type="text" class="input-def w200" title="주소 상세 입력자리">
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="delivery-message2">배송 요청사항</label></th>
					<td><input type="text"  id="delivery-message2" class="input-def w400"></td>
				</tr>
			</table>

			<h4 class="table-title mt-50">교환상품 받으실 주소<span>아래 주소로 교환상품을 배송해 드립니다.</span></h4>
			<table class="th-left util">
				<caption>교환상품 받을 주소 입력</caption>
				<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
				<tr>
					<th scope="row">배송지 선택</th>
					<td>
						<input type="radio" name="address-type" id="address-same3" class="radio-def">
						<label for="address-same3">받으신 주소와 동일</label>
						<input type="radio" name="address-type" id="address-new3" class="radio-def">
						<label for="address-new3">새로운 수거지</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="name3">이름</label></th>
					<td><input type="text" name="" id="name3" class="input-def w200" title="이름 입력자리"></td>
				</tr>
				<tr>
					<th scope="row"><label for="phone-num3">휴대전화</label></th>
					<td>
						<div class="select small">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value">010</button>
							<ul class="a_list">
								<li><a href="#1">010</a></li>
								<li><a href="#2">011</a></li>
							</ul>
						</div>
						<span class="txt-lh">-</span>
						<input type="text" name="" id="phone-num3" class="input-def w70" title="전화번호 가운데 입력자리">
						<span class="txt-lh">-</span>
						<input type="text" name="" class="input-def w70" title="전화번호 마지막 입력자리">
					</td>
				</tr>
				<tr>
					<th scope="row">주소</th>
					<td>
						<ul>
							<li>
								<input type="text" name="" id="" class="input-def w70" title="전화번호 가운데 입력자리">
								<span class="txt-lh">-</span>
								<input type="text" name="" class="input-def w70" title="전화번호 마지막 입력자리">
								<button class="btn-dib-line ">우편번호</button>
							</li>
							<li class="mt-5">
								<input type="text" class="input-def w400" title="주소 입력자리">
								<input type="text" class="input-def w200" title="주소 상세 입력자리">
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="delivery-message3">배송 요청사항</label></th>
					<td><input type="text"  id="delivery-message3" class="input-def w400"></td>
				</tr>
			</table>
			<div class="mypage-content-wrap">
				<dl class="attention">
					<dt>교환신청 이용안내</dt>
					<dd>교환을 원하는 상품 정보(디자인/사이즈등)를 상세히 기입해 주세요.</dd>
					<dd>교환은 같은 옵션상품만 가능합니다. 다른 옵션의 상품으로 교환을 원하실 경우, 반품 후 재 구매를 해주세요.</dd>
					<dd>상품이 손상/훼손 되었거나 이미 사용하셨다면 교환이 불가능합니다.</dd>
					<dd>교환 사유가 구매자 사유일 경우 왕복 교환 배송비를 상품과 함께 박스에 동봉해 주세요.</dd>
					<dd>교환 왕복 배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다.</dd>
					<dd>교환 사유가 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다.</dd>
					<dd>상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다.</dd>
				</dl>
			</div>
			<div class="btn-place mt-30">
				<button class="btn-dib-function" type="submit"><span>신청</span></button>
				<button class="btn-dib-function line" type="button"><span>취소</span></button>
			</div>


        </div><!-- //.layer-content -->
    </div>
</div>

	<div class="containerBody sub-page">

		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li><a href="mypage.php">MY PAGE</a></li>
				<li class="on"><a>주문배송조회</a></li>
			</ul>
		</div>

	<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
			<!---->
		</div><!-- //LNB -->

		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">

			<div class="my-order-detail">
			<p><img src="../static/img/common/mypage_order_flow.jpg" alt=""></p>

			<div class="order-no">주문 No. <?=$ordercode?> <span>(<? echo " ".substr($ordercode,0,4)."-".substr($ordercode,4,2)."-".substr($ordercode,6,2).""; ?> 00:00:00)</span></div>
			
			<table class="th-top util">
				<colgroup>
					<col style="width:100px"><col style="width:auto"><col style="width:100px"><col style="width:100px"><col style="width:100px"><col style="width:110px"><col style="width:140px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">상품금액</th>
						<th scope="col">배송비</th>
						<th scope="col">주문금액</th>
						<th scope="col">진행단계</th>
						<th scope="col">주문관리</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><del>200,000</del><br><strong>200,000</strong></td>
						<td rowspan="2">3,000</td>
						<td rowspan="2"><strong>203,000</strong></td>
						<td rowspan="2">주문접수</td>
						<td><button class="btn-dib-line " type="button"><span>주문취소</span></button></td>
					</tr>
					<tr>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><del>200,000</del><br><strong>200,000</strong></td>
						<td><button class="btn-dib-line " type="button"><span>주문취소</span></button></td>
					</tr>
					<tr>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><del>200,000</del><br><strong>200,000</strong></td>
						<td>3,000</td>
						<td><strong>203,000</strong></td>
						<td>결제완료</td>
						<td><button class="btn-dib-line " type="button"><span>주문취소</span></button></td>
					</tr>
					<tr>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><del>200,000</del><br><strong>200,000</strong></td>
						<td>3,000</td>
						<td><strong>203,000</strong></td>
						<td>배송중<br><button class="btn-dib-line " type="button"><span>배송추적</span></button></td>
						<td></td>
					</tr>
					<tr class="last-line">
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><del>200,000</del><br><strong>200,000</strong></td>
						<td>3,000</td>
						<td><strong>203,000</strong></td>
						<td>배송중<br><button class="btn-dib-line "><span>배송추적</span></button></td>
						<td>
							<button class="btn-dib-line " type="button"><span>리뷰쓰기</span></button>
							<button class="btn-dib-line " type="button"><span>구매확정</span></button>
							<button class="btn-dib-line  btn-change" type="button"><span>교환접수</span></button>
							<button class="btn-dib-line  btn-refund" type="button"><span>반품접수</span></button>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="total-price-sum">
				<ul>
					<li><span>총 주문금액</span>0</li>
					<li class="plus"><span>배송비</span>0</li>
					<li class="minus"><span>총 할인금액</span>0</li>
					<li class="total"><span>총 결제금액</span><strong>0</strong></li>
				</ul>
			</div>
			<div class="btn-total-cancle"><button class="btn-dib-line " type="button"><span>전체주문취소</span></button></div>

			<div class="my-half-table">
				<div class="inner-left">
					<p class="title">주문자 정보</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">주문자</th>
							<td>홍길동</td>
						</tr>
						<tr>
							<th scope="row">휴대전화</th>
							<td>010-1234-5678</td>
						</tr>
						<tr>
							<th scope="row">전화번호</th>
							<td>010-1234-5555</td>
						</tr>
						<tr>
							<th scope="row">이메일</th>
							<td>hong123@naver.com</td>
						</tr>
					</table>
				</div>
				<div class="inner-right">
					<p class="title">배송지 정보</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">받는사람</th>
							<td>홍길동</td>
						</tr>
						<tr>
							<th scope="row">휴대전화</th>
							<td>010-1234-5678</td>
						</tr>
						<tr>
							<th scope="row">전화번호</th>
							<td>010-1234-5555</td>
						</tr>
						<tr>
							<th scope="row">주소</th>
							<td>[12357] 서울시 강남구 논현동 000번지 5층 거기</td>
						</tr>
						<tr>
							<th scope="row">배송 요청사항</th>
							<td>배송전 전화주세요</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="my-half-table">
				<div class="inner-left">
					<p class="title">결제금액 할인</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">총 주문금액</th>
							<td>400,000</td>
						</tr>
						<tr>
							<th scope="row">배송비</th>
							<td>0</td>
						</tr>
						<tr>
							<th scope="row">상품할인</th>
							<td>0</td>
						</tr>
						<tr>
							<th scope="row">쿠폰할인</th>
							<td>10,000(회원가입 즉시 할인 쿠폰)</td>
						</tr>
						<tr>
							<th scope="row">마일리지 할인</th>
							<td>0</td>
						</tr>
						<tr>
							<th scope="row">결제금액</th>
							<td>390,000</td>
						</tr>
					</table>
				</div>
				<div class="inner-right">
					<p class="title">결제정보</p>
					<table class="th-left">
						<colgroup><col style="width:110px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">결제방법</th>
							<td>무통장입금(가상계좌)</td>
						</tr>
						<tr>
							<th scope="row">입금은행</th>
							<td>국민은행</td>
						</tr>
						<tr>
							<th scope="row">입금계좌</th>
							<td>1234-424-45315313</td>
						</tr>
						<tr>
							<th scope="row">입금자 명</th>
							<td>홍길동</td>
						</tr>
						<tr>
							<th scope="row">승인일자</th>
							<td>2016-02-01 00:00:11</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="btn-place">
				<a href="#" class="btn-dib-function"><span>목록보기</span></a>
				<a href="#" class="btn-dib-function line"><span>쇼핑하기</span></a>
			</div>

			<!-- 주문상품 -->
			<div class="table_wrap mt_20 hide">
				<h3>주문정보</h3>
				<table class="th_top">
					<colgroup>
						<col width="85" >
						<col width="*px" >
						<col width="145" >
						<col width="100" >
						<col width="100" >
						<col width="100" >
						<col width="100" >
					</colgroup>
					<tr>
						<th class="bg_none" colspan=2>상품정보</th>
						<th class="bg_none">판매금액</th>
						<th class="bg_none">수량</th>
						<th class="bg_none">총 주문금액</th>
						<th class="bg_none">진행상태</th>
						<th class="bg_none">요청사항</th>
					</tr>

<?php
		$tmpPrice = 0;
		foreach( $orProduct as $prKey=>$prVal ) { // 상품
			
			$tmpPrice = 0;
			$tmpQuantity = 0;
			$tmpProductPrice = 0;
			$tmpOptionPrice = 0;
			$tmpCouponPrice = 0;
			$tmpDeliPrice = 0;
			$deli_company = "";
			$deli_number = "";

			if(strlen($prVal->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$prVal->tinyimage)){
				$file = $Dir.DataDir.'shopimages/product/'.$prVal->tinyimage;
			} else {
				$file = $Dir."/images/common/noimage.gif";
			}
?>
					<tr>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prKey?>">
								<img src="<?=$file?>" border="0" class='img-size-mypage' >
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prKey?>"><?=$prVal->productname?></a>
<?php
			foreach( $orOption[$prKey] as $optVal ){ //옵션
				$optStr = '';
				$optVal->opt1_name = str_replace("::"," ",$optVal->opt1_name);
				$optVal->opt2_name = str_replace("::"," ",$optVal->opt2_name);
				if( $optVal->option_type == '0') {
					$tmpProductPrice = $optVal->price;		
					if($deli_company=="") $deli_company = $optVal->deli_com;
					if($deli_number=="") $deli_number = $optVal->deli_num;	
				}

				// 상품 + 필수옵션
				if( $optVal->option_type == '0' && strlen( $optVal->opt1_name ) > 0 ){
					$optStr = '옵션 : '.$optVal->opt1_name;
					if(strlen( $optVal->opt2_name ) > 0 ){
						$optStr .= ' / '.$optVal->opt2_name;
					}
					$optStr .= "&nbsp;( ".$optVal->option_quantity." 개 ".number_format( $optVal->option_price * $optVal->option_quantity )." 원 )";
					$tmpQuantity += $optVal->option_quantity;
				// 추가옵션
				} else if( $optVal->option_type == '1' && strlen( $optVal->opt1_name ) > 0 ){
					$optStr = '옵션 : '.$optVal->opt1_name;
					$optStr .= "&nbsp;( ".$optVal->option_quantity." 개 ".number_format( $optVal->option_price * $optVal->option_quantity )." 원 )";
				// 상품
				} else {
					$tmpQuantity += $optVal->option_quantity;
				}	
				$tmpPrice				+= ( ($optVal->price + $optVal->option_price) * $optVal->option_quantity );
				$tmpCouponPrice	+= $optVal->coupon_price;
				$tmpDeliPrice			+= $optVal->deli_price;
?>
							<div class="opt">
								<?=$optStr?>
							</div>
<?php
			}
?>
						</td>
						<td><?=number_format( $tmpProductPrice )?>원</td>
						<td><?=$tmpQuantity?>개</td>
						<td><strong><?=number_format( $tmpPrice )?>원</strong></td>
						<td>
<?php		if( $_ord->order_conf == '0' ){
				if ($optVal->deli_gbn=="C") echo "주문취소";
				else if ($optVal->deli_gbn=="D") echo "취소요청";
				else if ($optVal->deli_gbn=="E") echo "환불대기";
				else if ($optVal->deli_gbn=="X") echo "배송준비";
				else if ($optVal->deli_gbn=="Y") {
					if ($optVal->receive_ok == '1') echo "<span style='color:#ff7200;'>배송완료</span>";
					else echo "배송중";
				} else if ($optVal->deli_gbn=="N") {
					if (strlen($_ord->bank_date)<12 && strstr("BOQ", $_ord->paymethod[0])) echo "주문접수";
					else if ($_ord->pay_admin_proc=="C" && $_ord->pay_flag=="0000") echo "결제취소";
					else if (strlen($_ord->bank_date)>=12 || $_ord->pay_flag=="0000") echo "결제완료";
					else echo "결제확인중";
				} else if ($optVal->deli_gbn=="S") {
					echo "배송준비";
				} else if ($optVal->deli_gbn=="R") {
					echo "반송처리";
				} else if ($optVal->deli_gbn=="H") {
					echo "배송완료 [정산보류]";
				}
			} else {
				echo "구매확정";
			}


			if ($optVal->deli_date != "") {
				if(dateDiff(date("YmdHis"),$optVal->deli_date) < 11){
					$opt_redelivery_type = $optVal->redelivery_type; 
				}
			} else {
				$opt_redelivery_type	="";
			}
							
			if(($optVal->deli_gbn=="Y" && $opt_redelivery_type == "Y") || ($_ord->deli_gbn=="Y" && $redaliveryArray->redelivery_type == "Y")){
				echo "<br>";
				echo "<font color='red'>[반송신청]</font>";
			}
?>
						</td>
						<td>
							
							<? 
								if( $optVal->deli_gbn=="Y" && $_ord->order_conf == '0' ){ // 배송중일 경우
							?>
								<?if($optVal->deli_gbn=="Y" && $optVal->receive_ok == '0' ){?>
									<a href="javascript:;" class="btn_mypage_s deli_ok" ordercode = "<?=$ordercode?>" productcode = "<?=$prKey?>">수령확인</a>
								<?}?>
								<!-- 반송신청 위치 -->
								<?if($redaliveryArray->redelivery_type != "Y" && $opt_redelivery_type != "Y"){?><a href="javascript:;" class="btn_mypage_s CLS_redelivery" ordercode = "<?=$ordercode?>" productcode = "<?=$prKey?>">반송신청</a><?}?>
								<?if($optVal->deli_gbn=="Y" && $optVal->receive_ok == '0'){?><a href="javascript:;" class="btn_mypage_s CLS_delivery_tracking" urls = "<?=$delicomlist[$deli_company]->deli_url.$deli_number?>">배송추적</a><?}?>								
							<?
								} else if( $optVal->deli_gbn=="Y" && $_ord->order_conf == '1') { //구매확정일 경우
							?>
								<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prKey?>" class="btn_mypage_s">구매평</a>
							<?
								}else{
									echo "-";
								}
							?>
							
						</td>
					</tr>
<?php
		} 
?>												
				
					<tfoot>
						<tr>
							<td colspan=7>
								<div class="result_box">
									<span class="total">
										<span class="txt">총 주문금액</span>
										<strong class="number"><?=number_format($_ord->price)?> 원</strong>
									</span>
									<img class="icon" src="../img/icon/cart_list_icon_minus.gif" alt="-" >
									<span class="total">
										<span class="txt">쿠폰 할인금액</span>
										<strong class="number"><?=number_format($_ord->dc_price)?> 원</strong>
									</span>
									<img class="icon" src="../img/icon/cart_list_icon_minus.gif" alt="-" >
									<span class="total">
										<span class="txt">적립금 할인금액</span>
										<strong class="number"><?=number_format($_ord->reserve)?> 원</strong>
									</span>
									<img class="icon" src="../img/icon/cart_list_icon_plus.gif" alt="+" >
									<span class="total">
										<span class="txt">총 배송비</span>
										<strong class="number"><?=number_format($_ord->deli_price)?> 원</strong>
									</span>
									<img class="icon" src="../img/icon/cart_list_icon_equals.gif" alt="=" >
									<span class="total_payment">
										<span class="txt">총 결제 금액</span>
										<strong class="number"><?=number_format($_ord->price-$_ord->dc_price-$_ord->reserve+$_ord->deli_price)?><span>원</span></strong>
									</span>
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div><!-- //주문상품 -->

<?php
	if(strstr("VCPM", $_ord->paymethod[0])) {
		$arpm=array("V"=>"실시간계좌이체","C"=>"신용카드","P"=>"매매보호 - 신용카드", "M"=>"핸드폰");
		$subject = "결제일자";
		$o_year = substr($ordercode, 0, 4);
		$o_month = substr($ordercode, 4, 2);
		$o_day = substr($ordercode, 6, 2);
		$o_hour = substr($ordercode, 8, 2);
		$o_min = substr($ordercode, 10, 2);
		$o_sec = substr($ordercode, 12, 2);

		$msg = $o_year."-".$o_month."-".$o_day." ".$o_hour.":".$o_min.":".$o_sec;
	} else if (strstr("BOQ", $_ord->paymethod[0])) {
		$arpm=array("B"=>"무통장입금","O"=>"가상계좌","Q"=>"매매보호 - 가상계좌");
		if(strstr("B", $_ord->paymethod[0])){
			$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>입금자 : ".$_ord->bank_sender."</div> <div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>계좌 : ".$_ord->pay_data."</div>";
		}else{
			if($_ord->pay_flag=="0000"){
				$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>(입금확인후 배송이 됩니다.)</div>";
			}
			if(strstr("O", $_ord->paymethod[0])){
				$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>가상계좌 : ".$_ord->pay_data."</div> ".$msg;
			}else if(strstr("Q", $_ord->paymethod[0])){
				$msg = "<div style = 'font-family:dotum; font-size:12px; color: #9b9b9b;'>매매보호 - 가상계좌 : ".$_ord->pay_data."</div> ".$msg;
			}
		}
		$subject = "추가정보";
	}
?>
			<!-- 결제정보 -->
			<div class="table_wrap mt_20 hide">
				<h3>결제정보</h3>
				<table class="th_left" summary="결제정보">
					<colgroup>
						<col style="width:121px" ><col style="width:auto" ><col style="width:121px" ><col style="width:auto" >
					</colgroup>
					<tbody>
						<tr>
							<th scope="col">총 결제금액</th>
							<td class="text_only red"><b><?=number_format($_ord->price-$_ord->dc_price-$_ord->reserve+$_ord->deli_price)?>원</b></td>
							<th scope="col">결제방법</th>
							<td class="text_only"><?=$arpm[$_ord->paymethod[0]]?></td>
						</tr>
						<tr>
							<th scope="col"><?=$subject?></th>
							<td class="text_only" colspan=3><?=$msg?></td>
						</tr>
					</tbody>
				</table>
			</div><!-- //결제정보 -->

			<!-- 배송정보 -->
			<div class="table_wrap mt_20 hide">
				<h3>배송정보</h3>
				<table class="th_left" summary="배송정보">
					<colgroup>
						<col style="width:121px" ><col style="width:auto" ><col style="width:121px" ><col style="width:auto" >
					</colgroup>
					<tbody>						
						<tr>
							<th scope="col">받는분</th>
							<td class="text_only" colspan=3><?=$_ord->receiver_name?></td>
						</tr>
						<tr>
							<th scope="col">전화번호</th>
							<td class="text_only" ><?=$_ord->receiver_tel1?></td>
							<th scope="col">핸드폰번호</th>
							<td class="text_only" ><?=$_ord->receiver_tel2?></td>
						</tr>
						<tr>
							<th scope="col">주소</th>
							<td class="text_only"  colspan=3><?if($_ord->deli_type == "2"){ echo "해당 주문은 고객 [직접수령] 입니다"; } else { echo $_ord->receiver_addr; }?></td>
						</tr>
						<tr>
							<th scope="col">요청사항</th>
							<td class="text_only"  colspan=3>
								<?if($_ord->order_msg2){?>
									<?=$_ord->order_msg2?>
								<?}else{?>
									-
								<?}?>
							</td>
						</tr>
<?php
			if( strlen( trim( $_ord->overseas_code ) ) > 0 ) {
?>
						<tr>
							<th scope="col">통관번호</th>
							<td colspan=3 ><?=$_ord->overseas_code?></td>
						</tr>
<?php
			}
?>
					</tbody>
				</table>
			</div><!-- //배송정보 -->


<?php
	#주문취소
	list($flagCancelData) = pmysql_fetch("SELECT count(idx) FROM tblorderproductcancel WHERE ordercode = '".$_ord->ordercode."' group by ordercode");
	if($flagCancelData){
?>
			<!-- 취소상품 -->
			<div class="table_wrap mt_20">
				<h3>취소상품</h3>
				<table class="th_top">
					<colgroup>
						<col width="85" >
						<col width="*px" >
						<col width="145" >
						<col width="100" >
						<col width="100" >
						<col width="100" >
						<col width="100" >
					</colgroup>
					<tr>
						<th class="bg_none" colspan=2>상품정보</th>
						<th class="bg_none">판매금액</th>
						<th class="bg_none">수량</th>
						<th class="bg_none">총 주문금액</th>
						<th class="bg_none">진행상태</th>
						<th class="bg_none">요청사항</th>
					</tr>

<?php
		$tmpCancelPrice = 0;
		foreach( $corProduct as $prKey=>$prVal ) { // 상품
			
			$tmpPrice = 0;
			$tmpQuantity = 0;
			$tmpProductPrice = 0;
			$tmpOptionPrice = 0;
			$tmpCouponPrice = 0;
			$tmpDeliPrice = 0;
			$deli_company = "";
			$deli_number = "";

			if(strlen($prVal->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$prVal->tinyimage)){
				$file = $Dir.DataDir.'shopimages/product/'.$prVal->tinyimage;
			} else {
				$file = $Dir."/images/common/noimage.gif";
			}
?>
					<tr>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prKey?>">
								<img src="<?=$file?>" border="0" class='img-size-mypage' >
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prKey?>"><?=$prVal->productname?></a>
<?php
			foreach( $corOption[$prKey] as $optVal ){ //옵션
				$optStr = '';
				$optVal->opt1_name = str_replace("::"," ",$optVal->opt1_name);
				$optVal->opt2_name = str_replace("::"," ",$optVal->opt2_name);
				if( $optVal->option_type == '0') {
					$tmpProductPrice = $optVal->price;		
					if($deli_company=="") $deli_company = $optVal->deli_com;
					if($deli_number=="") $deli_number = $optVal->deli_num;	
				}

				// 상품 + 필수옵션
				if( $optVal->option_type == '0' && strlen( $optVal->opt1_name ) > 0 ){
					$optStr = '옵션 : '.$optVal->opt1_name;
					if(strlen( $optVal->opt2_name ) > 0 ){
						$optStr .= ' / '.$optVal->opt2_name;
					}
					$optStr .= "&nbsp;( ".$optVal->option_quantity." 개 ".number_format( $optVal->option_price * $optVal->option_quantity )." 원 )";
					$tmpQuantity += $optVal->option_quantity;
				// 추가옵션
				} else if( $optVal->option_type == '1' && strlen( $optVal->opt1_name ) > 0 ){
					$optStr = '옵션 : '.$optVal->opt1_name;
					$optStr .= "&nbsp;( ".$optVal->option_quantity." 개 ".number_format( $optVal->option_price * $optVal->option_quantity )." 원 )";
				// 상품
				} else {
					$tmpQuantity += $optVal->option_quantity;
				}	
				$tmpPrice				+= ( ($optVal->price + $optVal->option_price) * $optVal->option_quantity );
				$tmpCouponPrice	+= $optVal->coupon_price;
				$tmpDeliPrice			+= $optVal->deli_price;
?>
							<div class="opt">
								<?=$optStr?>
							</div>
<?php
			}
?>
						</td>
						<td><?=number_format( $tmpProductPrice )?>원</td>
						<td><?=$tmpQuantity?>개</td>
						<td><strong><?=number_format( $tmpPrice )?>원</strong></td>
						<td>주문취소</td>
						<td> - </td>
					</tr>
<?php
		$tmpCancelPrice += $tmpPrice;
		} 
?>
				</table>
			</div><!-- //취소상품 -->
			
			<!-- 환불정보 -->
			<div class="table_wrap mt_20">
				<h3>환불정보</h3>
				<table class="th_left" summary="결제정보">
					<colgroup>
						<col style="width:121px" ><col style="width:auto" ><col style="width:121px" ><col style="width:auto" >
					</colgroup>
					<tbody>
						<tr>
							<th scope="col">환불금액</th>
							<td class="text_only red"><?=number_format($tmpCancelPrice)?>원</td>
							<th scope="col">수수료</th>
							<td class="text_only"><?=number_format($c_refund)?>원</td>
						</tr>
						<tr>
							<th scope="col">총 환불금액</th>
							<td class="text_only red" colspan=3><b><?=number_format($tmpCancelPrice+$c_refund)?>원</b></td>
						</tr>
					</tbody>
				</table>
			</div><!-- //환불정보 -->
<?php
	}
?>
			<div class="ta_c mt_40 hide"><a href="javascript:history.go(-1)" class="btn_D">뒤로</a></div>
			

		</div><!-- //.my-order-detail -->
		</div><!-- 내용 -->

	</div>
<style type="text/css">
	.layer {display:none; position:fixed; _position:absolute; top:0; left:0; width:100%; height:100%; z-index:100;}
	.layer .bg {position:absolute; top:0; left:0; width:100%; height:100%; background:#000; opacity:.5; filter:alpha(opacity=50);}
	.layer .pop-layer {display:block;}

	.pop-layer {display:none; position: absolute; top: 50%; left: 50%; width: 410px; height:auto;  background-color:#fff; border: 2px solid #000; z-index: 10;}	
	.pop-layer .pop-container {padding: 20px 25px;}
	.pop-layer p.ctxt {color: #666; line-height: 25px;}
</style>
<div class="layer" style="display: none;">
	<div class="bg"></div>
	<div id="layer1" class="pop-layer">
		<div class="pop-container">
			<div class="pop-conts">
				<!--content //-->
				<h3 class="title mb_20">반송 신청</h3>
				<table class="th_left" cellpadding="0" cellspacing="0" border="0" width="100%">
					<colgroup>
						<col style="width:120px"><col style="width:auto">
					</colgroup>
					<tbody>
					<tr>
						<th>반송사유</th>
						<td>
							<textarea name="redelivery_reason_content" id="redelivery_reason_content" rows="10" style="width: 98%;resize: none;"></textarea>
							<input type="hidden" id="redelivery_ordercode" >
							<input type="hidden" id="redelivery_productcode" >
							<input type="hidden" id="redelivery_obj" >
						</td>
					</tr>
					</tbody>
				</table>
				<div class="ta_c mt_20">
					<a href="javascript:;" id="redelivery_reson_on" class="btn_D on">반송신청</a>
					<a href="javascript:;" id="redelivery_reson_close" class="btn_D cbtn">취소</a>
				</div>
				<!--// content-->
			</div>
		</div>
	</div>
</div>

