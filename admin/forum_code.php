<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/category_forum.class.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "co-8";
$MenuCode = "community";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################
$catelist =new CATEGORYLIST_FORUM();

?>

<?php include("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<link rel="stylesheet" type="text/css" href="DynamicTree.css">
<script src="DynamicTree.js"></script>
<script src="DynamicTreeSorting.js"></script>
<script language="JavaScript">

$(document).ready(function(){
	category='<?=$_GET[category]?>';
	
	if(category!=''){
		var len = category.length / 3;
		var el = "cate" + len + "[]";
		var obj = document.getElementsByName(el);
		for (i=0;i<obj.length;i++){
			if (obj[i].value==category){
				openTree(obj[i].parentNode, '1');
				break;
			}
		}
	}
})

function CodeDelete2(_code) {
	if(_code.length==12 && _code!="000000000000") {
		document.form1.code.value=_code;
		document.form1.mode.value="delete";
		document.form1.action="forum_code.process.php";
		document.form1.target="HiddenFrame";
		document.form1.submit();
	}else{
		alert('삭제하실 카테고리를 선택해주세요.');
	}
}

</script>
<STYLE type=text/css>
	#contentDiv {WIDTH: 220;HEIGHT: 320;}
</STYLE>

<!-- 라인맵 -->
<div class="admin_linemap"><div class="line"><p>현재위치 : 상품관리 &gt;포럼 &gt; <span>포럼 카테고리 관리</span></p></div></div>

<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed" >
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">

	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
		<col width=240 id="menu_width"></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top">
			<?php include("menu_community.php"); ?>
			</td>
			<td></td>
			<td valign="top">
			<form name=form1 action="forum_code_indb.php" method=post>
			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			
			<input type=hidden name=mode value="<?=$mode?>">
			<input type=hidden name=code>
			<input type=hidden name=cate>
			<input type=hidden name=codes>
			<input type=hidden name=parentcode>
			<tr>
				<td>				
					<div class="product_setup_wrap"><!-- 카테고리관리 -->
					<table width="100%" cellspacing=0 cellpadding=0 border=0>
						<tr>
							<td valign="top">

							<!-- 카테고리 트리 -->

							<div class="title_depth3">
								포럼 카테고리관리
								<!--div class="btn_function"><a href="#"><img src="/admin/img/btn/btn_cate_reg.gif" alt="등록" /></a><a href="#"><img src="/admin/img/btn/btn_cate_del.gif" alt="삭제" /></a></div-->
							</div>

							<div class="cate_tree_wrap">
									<table cellpadding="0" cellspacing="0" width="100%" height="800">
									<tr>
										<td width="100%" height="100%" valign="top">

										<table cellpadding="0" cellspacing="0" width="100%" height="100%">
											<tr>
												<td width="100%" height="100%" align=center valign=top style="padding-left:5px;padding-right:5px;">

												<DIV class=MsgrScroller id=contentDiv style="width:99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
													<DIV id=bodyList>

														<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor=FFFFFF>
															<tr>
																<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;"><a href="javascript:openTree(this,'2');">최상위 카테고리</span></td>
															</tr>
															<tr>
																<!-- 카테고리 목록 -->
																<td id="code_list" nowrap valign=top>
																	<div class="DynamicTree">
																		<div class="wrap" id="tree">
																			<? echo $catelist->getCateTree();?>
																		</div>
																	</div>
																</td>
																<!-- 카테고리 목록 끝 -->
															</tr>
														</table>

													</DIV>
												</DIV>

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
						<td align=left style="padding-left:30px;width:82%;">
							<DIV style="width:100%;height:100%;bgcolor:#FFFFFF;">
								<IFRAME name="PropertyFrame" src="forum_code.property.php" width=100% height=1000 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
							</div>
							<!-- 매뉴얼 -->
							<div class="sub_manual_wrap">
								<div class="title"><p>매뉴얼</p></div>
								<dl>
									<dt><span>카테고리생성시 주의사항</span></dt>
									<dd>
										  - 카테고리명은 최대 한글 50자, 영문 100자 이내로 제한되어 있으며, 특수문자는 삼가해 주세요.  <br />
										  - 최상위카테고리 생성시 하위카테고리 유무를 확인 후 등록해 주세요. <br />
									</dd>
								</dl>
							</div>

						</td>
						<!-- 설정영역 -->

						</tr>
					</table>
					</div><!-- 카테고리관리 -->

					<!-- 페이지 타이틀 -->
					
				</td>
			</tr>
			
			<IFRAME name="HiddenFrame" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
			
			<tr><td height="20"></td></tr>
			
<?/*?>			
			<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=mode value="<?=$mode?>">
			<input type=hidden name=code>
			<input type=hidden name=codes>
			<input type=hidden name=parentcode>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" height="910">
				<tr>
					<td valign="top">
					<DIV onmouseover="divAction(this,2);" id="cateidx" style="position:absolute;z-index:0;width:242px;bgcolor:#FFFFFF; "> <!-------------------->
					<table cellpadding="0" cellspacing="0" width="100%" height="870">
					<tr>
						<td width="100%" height="100%" valign="top" background="images/category_boxbg.gif">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td bgcolor="#FFFFFF"><IMG SRC="images/product_totoacategory_title.gif" ALT=""></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box1.gif" border="0"></td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding-top:4pt; padding-bottom:6pt;">
							<table align="center" cellpadding="0" cellspacing="0" width="230">
							<tr>
								<td width="24"><button title="전체 트리확장" id="btn_treeall" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="AllOpen();"><IMG SRC="images/category_btn1.gif" border="0"></button></td>
								<td width="24"><button title="선택된 카테고리속성 보기" id="btn_property" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="ViewProperty();"><IMG SRC="images/category_btn2.gif" border="0"></button></td>
								<td width="24"><button title="선택된 카테고리에 하위카테고리 추가" id="btn_codeadd" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="NewCode();"><IMG SRC="images/category_btn3.gif" border="0"></button></td>
								<td width="24"><button title="선택된 카테고리 삭제" id="btn_codedel" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="CodeDelete();"><IMG SRC="images/category_btn4.gif" border="0"></button></td>
								<td width="24"><button title="선택된 카테고리 위로 이동" id="btn_moveup" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="CodeMove('up');"><IMG SRC="images/category_btn5.gif" border="0"></button></td>
								<td width="24"><button title="선택된 카테고리 아래로 이동" id="btn_movedown" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="CodeMove('down');"><IMG SRC="images/category_btn6.gif" border="0"></button></td>
								<td width="24"><button title="이동된 카테고리 저장" id="btn_movesave" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="MoveSave();"><IMG SRC="images/category_btn7.gif" border="0"></button></td>
								<td width="24"><button title="이동된 카테고리 되돌리기" id="btn_movecancel" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="MoveCancel();"><IMG SRC="images/category_btn8.gif" border="0"></button></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box2.gif" border="0"></td>
						</tr>
						<tr>
							<td width="100%" height="100%" align=center valign=top style="padding-left:5px;padding-right:5px;">
							<DIV class=MsgrScroller id=contentDiv style="width:99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
							<DIV id=bodyList>
							<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor=FFFFFF>
							<tr>
								<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('out');">최상위 카테고리</span></td>
							</tr>
							<tr>
								<!-- 상품카테고리 목록 -->
								<td id="code_list" nowrap valign=top></td>
								<!-- 상품카테고리 목록 끝 -->
							</tr>
							</table>
							</DIV>
							</DIV>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><IMG SRC="images/category_boxdown.gif" border="0"></td>
					</tr>
					</table>
					</div>
					</td>
					<td style="padding-left:84px;"></td>
					<td width="100%" valign="top" height="100%" onmouseover="divAction(document.getElementById('cateidx'),0);"><DIV style="position:relative;z-index:1;width:100%;height:100%;bgcolor:#FFFFFF;"><IFRAME name="PropertyFrame" src="forum_code.property.php" width=100% height=840 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></div></td>
				</tr>
				<IFRAME name="HiddenFrame" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
				</table>
				</td>
			</tr>
			</form>
<?*/?>			
			<tr><td height=20></td></tr>
			<tr><td height="50"></td></tr>
			</table>
			</form>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<script type="text/javascript">
hiddenLeft();
var tree = new DynamicTree("tree");
//tree.category = '<?=$_GET[category]?>';
tree.init();
tree.Sorting();


function openTree(obj, chkable)
{
	var code='';
	var cate='';
	var mode='';
	var chkcate=''
	if(chkable!='2'){
		mode='modify';
		tree.sorting.ready(obj);


		var addZero='';
		chkcate = obj.getElementsByTagName('input')[0].value;
		if(chkcate.length<12){
			
			for(var i=0;i<(12-chkcate.length);i++){
				
				addZero=addZero+'0';
			}
		}
		
		code=chkcate+addZero;
	}

	document.form1.cate.value=chkcate;
	document.form1.code.value=code;
	document.form1.mode.value=mode;
	document.form1.action="forum_code.property.php";
	document.form1.target="PropertyFrame";
	document.form1.submit();

	//PropertyFrame.location.href = "forum_code.property.php?mode=modify&code="+cate;
		
}

</script>

<?php
include("copyright.php");