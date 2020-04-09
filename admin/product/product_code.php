<?php // hspark
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/category.class.php");
include("../access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################
$catelist =new CATEGORYLIST();

?>

<?php include("../header.php"); ?>

<script type="text/javascript" src="/admin/lib.js.php"></script>
<link rel="stylesheet" type="text/css" href="/admin/static/plugins/DynamicTree/DynamicTree.css">
<script src="/admin/static/plugins/DynamicTree/DynamicTree.js"></script>
<script src="/admin/static/plugins/DynamicTree/DynamicTreeSorting.js"></script>
<script language="JavaScript">
	$(document).ready(function () {
		category = '<?=$_GET[category]?>';

		if (category != '') {
			var len = category.length / 3;
			var el = "cate" + len + "[]";
			var obj = document.getElementsByName(el);
			for (i = 0; i < obj.length; i++) {
				if (obj[i].value == category) {
					openTree(obj[i].parentNode, '1');
					break;
				}
			}
		}
	})

	function CodeDelete2(_code) {
		if (_code.length == 12 && _code != "000000000000") {
			document.form1.code.value = _code;
			document.form1.mode.value = "delete";
			document.form1.action = "product_code.process.php";
			document.form1.target = "HiddenFrame";
			document.form1.submit();
		} else {
			alert('삭제하실 카테고리를 선택해주세요.');
		}
	}
</script>
<STYLE type=text/css>
	#contentDiv {
		WIDTH: 220;
		HEIGHT: 320;
	}
</STYLE>

<!-- 라인맵 -->
<div class="content-wrap">
	<form name=form1 action="product_code_indb.php" method=post>
		<input type="hidden" name="mode" value="<?=$mode?>">
		<input type="hidden" name="code">
		<input type="hidden" name="cate">
		<input type="hidden" name="codes">
		<input type="hidden" name="parentcode">
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr>
				<td>
					<div class="product_setup_wrap">
						<!-- 카테고리관리 -->
						<table width="100%" cellspacing=0 cellpadding=0 border="0" >
							<tr>
								<td width="242" valign="top">

									<!-- 카테고리 트리 -->

									<div class="title_depth3">
										카테고리관리
										<!--div class="btn_function"><a href="#"><img src="/admin/img/btn/btn_cate_reg.gif" alt="등록" /></a><a href="#"><img src="/admin/img/btn/btn_cate_del.gif" alt="삭제" /></a></div-->
									</div>

									<div class="cate_tree_wrap" >
										<table cellpadding="0" cellspacing="0" width="100%" height="800">
											<tr>
												<td width="100%" height="100%" valign="top">

													<table cellpadding="0" cellspacing="0" width="100%" height="100%">
														<tr>
															<td width="100%" height="100%" align="center" valign=top style="padding-left:5px;padding-right:5px;">

																<div class=MsgrScroller id=contentDiv style="width:99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false"
																    style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
																	<div id=bodyList>

																		<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor="FFFFFF">
																			<tr>
																				<td height=18>
																					<IMG SRC="/admin/images/directory_root.gif" border=0 align=absmiddle>
																					<span id="code_top" style="cursor:default;">
																						<a href="javascript:openTree(this,'2');">최상위 카테고리</span>
																				</td>
																			</tr>
																			<tr>
																				<!-- 상품카테고리 목록 -->
																				<td id="code_list" nowrap valign=top>
																					<div class="DynamicTree">
																						<div class="wrap" id="tree">
																							<? echo $catelist->getCateTree();?>
																						</div>
																					</div>
																				</td>
																				<!-- 상품카테고리 목록 끝 -->
																			</tr>
																		</table>

																	</div>
																</div>

															</td>
														</tr>
													</table>

												</td>
											</tr>
										</table>

									</div>

								</td>
								<!-- 카테고리 트리 -->

								<!-- 설정영역 -->
								<td align="left" style="padding-left:20px;padding-top:20px">
									<div style="width:100%;height:100%;background-color:#FFFFFF;">
										<iframe name="PropertyFrame" src="product_code.property.php" width=100% height=1000 frameborder=0 scrolling="no"
										    marginheight="0" marginwidth="0" style="background-color:#fff"></iframe>
									</div>
									<!-- 매뉴얼 -->
									<div class="sub_manual_wrap">
										<div class="title">
											<p>매뉴얼</p>
										</div>
										<ul class="help_list">
											<li>카테고리명은 최대 한글 50자, 영문 100자 이내로 제한되어 있으며, 특수문자는 삼가해 주세요.</li>
											<li>최상위카테고리 생성시 하위카테고리 유무를 확인 후 등록해 주세요. </li>
										</ul>
									</div>
								</td>
								<!-- 설정영역 -->
							</tr>
						</table>
					</div>
					<!-- 카테고리관리 -->

					<!-- 페이지 타이틀 -->

				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
		</table>
	</form>
</div>

<iframe name="HiddenFrame" src="about:blank" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></iframe>
<script type="text/javascript">
	var tree = new DynamicTree("tree");
	tree.init();
	tree.Sorting();

	function openTree(obj, chkable) {
		var code = '';
		var cate = '';
		var mode = '';
		var chkcate = ''
		if (chkable != '2') {
			mode = 'modify';
			tree.sorting.ready(obj);


			var addZero = '';
			chkcate = obj.getElementsByTagName('input')[0].value;
			if (chkcate.length < 12) {

				for (var i = 0; i < (12 - chkcate.length); i++) {

					addZero = addZero + '0';
				}
			}
			code = chkcate + addZero;
		}

		document.form1.cate.value = chkcate;
		document.form1.code.value = code;
		document.form1.mode.value = mode;
		document.form1.action = "product_code.property.php";
		document.form1.target = "PropertyFrame";
		document.form1.submit();
	}
</script>

<?php
include("../copyright.php");