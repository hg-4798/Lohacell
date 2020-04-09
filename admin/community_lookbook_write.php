<?php
/*********************************************************************
// 파 일 명		: community_magazine_write.php
// 설     명		: 매거진 관리 생성, 수정
// 상세설명	    : 매거진 관리 생성, 수정
// 작 성 자		: 2016.09.20 - 김대엽
// 수 정 자		:
//
//
 *********************************************************************/
?>
<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

##################### 페이지 접근권한 check #####################
$PageCode = "co-6";
$MenuCode = "community";
if (!$_usersession->isAllowedTask($PageCode)) {
    include("AccessDeny.inc.php");
    exit;
}
#################################################################


if(!$product) $product = new PRODUCT();

include("header.php");

#---------------------------------------------------------------
# 넘어온 값들을 정리한다.
#---------------------------------------------------------------
//exdebug($_POST);
//exdebug($_GET);
//exdebug($_FILES);
//exit;

$mode = $_POST["mode"];
if(!$mode) $mode = $_GET["mode"];

#---------------------------------------------------------------
# DB를 처리한다
#---------------------------------------------------------------
$no = $_POST["no"];

if($mode=="delete") {
    $qry = "DELETE FROM tbllookbook WHERE no ='".$no."'";
    pmysql_query( $qry, get_db_conn() );
    //callNaver('lookbook', $no, 'del');
    echo "<html></head><body onload=\"alert('삭제가 완료되었습니다.');parent.goBackList();\"></body></html>";exit;

} else if($mode=="insert" || $mode=="modify") {				// DB를 수정한다.

    $title	            = pg_escape_string($_POST["title"]);
    //$content            = trim($_POST['content']);
    //$content            = str_replace("'", "''", $content);
    $img	        = pg_escape_string($_POST["img"]);
    $img_m	        = pg_escape_string($_POST["img_m"]);
    $content	        = htmlspecialchars($_POST["content"]);
    $display            = $_POST["display"];
    $relationProduct	 = $_POST["relationProduct"];



    $tag          = pg_escape_string($_POST["hash_tags"]);
    if (!$display) $display = "N";
    else $display = "Y";
//     exdebug($up_imagefile);
//     exit;

    $regdt = date("YmdHis");

    if($mode=="insert") {
        $sql = "INSERT INTO tbllookbook (
        title,
        content,
        img_file,
        img_m_file, 
        regdate,
        access, 
        img,
        img_m,
        tag,
        display,
		relation_product
        ) VALUES (
        '{$title}', 
        '{$content}', 
        '{$img}', 
        '{$img_m}', 
        '{$regdt}', 
        0,
        '{$img}', 
        '{$img_m}', 
        '{$tag}',
        '{$display}',
		'{$relationProduct}'
        ) ";
        pmysql_query($sql,get_db_conn());
//         exdebug($sql);
//         exit;

    }else if($mode=="modify") {
        $img_where="";
        $img_where[] = "title='{$title}' ";
        $img_where[] = "content ='{$content}' ";
        $img_where[] = "img ='{$img}' ";
        $img_where[] = "img_m ='{$img_m}' ";
        $img_where[] = "display = '{$display}' ";
        $img_where[] = "tag = '{$tag}' ";
        $img_where[] = "relation_product = '{$relationProduct}' ";
        $img_where[] = "img_file = '{$img}'";
        $img_where[] = "img_m_file = '{$img_m}'";

        $sql = "UPDATE tbllookbook SET ";
        $sql.= implode(", ",$img_where);
        $sql.= "WHERE no = '{$no}' ";
        //exdebug($sql);
        //exit;
        pmysql_query($sql,get_db_conn());
    }
    if(!pmysql_error()){
        if($mode=="insert") {
            $insetSeq_sql = "SELECT no FROM tbllookbook ORDER BY no DESC LIMIT 1";
            $insertSeq_result = pmysql_query($insetSeq_sql);
            $insertSeq_row = pmysql_fetch_object( $insertSeq_result );
            $insertSeq = $insertSeq_row->no;
            callNaver('lookbook', $insertSeq, 'reg');
            echo "<html></head><body onload=\"alert('등록이 완료되었습니다.');parent.goBackList();\"></body></html>";exit;
        }else if($mode=="modify") {
            callNaver('magazine', $no, 'reg');
            echo "<html></head><body onload=\"alert('수정이 완료되었습니다.');parent.goBackList();\"></body></html>";exit;
        }
    }else{
        exdebug($sql);
        alert_go('오류가 발생하였습니다.', $_SERVER['REQUEST_URI']);
    }
}

#---------------------------------------------------------------
# 넘어온 값들을 정리한다.
#---------------------------------------------------------------

# 수정할 배너 불러오기
if( $mode == 'modfiy_select' ){
    $no = $_POST['no'];
    if(!$no) $no = $_GET['no'];
    $mSelectSql = "SELECT * FROM tbllookbook WHERE no =".$no;
    $mSelectRes = pmysql_query( $mSelectSql, get_db_conn() );
    $mSelectRow = pmysql_fetch_array( $mSelectRes );
    $mSelect = $mSelectRow;
    pmysql_free_result( $mSelectRes );

    //exdebug($imagepath.$mSelect['img_rfile']);
    //$arrProductCodes = explode("||", $mSelect['productcodes']);

    //수정
    $qType = '1';
    $qType_text = '수정';
}

# 등록 mode
if( is_null( $qType ) ){
    $qType = '0';
    $qType_text = '등록';
}

?>

<style>
    /* sortable용 bshan 추가 */
    .highlight {
        border: 1px solid grey;
        font-weight: bold;
        font-size: 45px;
        background-color: #e9c05e;
    }
    html>body #check_relationProduct tr { height: 14px;}
</style>
    <script type="text/javascript" src="lib.js.php"></script>
    <script language="JavaScript">
        function CheckForm(mode, no) {

        	$.each(oEditors, function (i, e) {
				this.exec("UPDATE_CONTENTS_FIELD", []);
			})

            //관련상품 데이터 만들기 시작
            var relationproduct = LookBook.getProduct();
            var relationproduct_code = relationproduct.map(function () {return $(this).attr('data-productcode');}).get();
            var relationproduct_data = relationproduct_code.join();
            $('#relationProduct').val(relationproduct_data);
            //관련상품 데이터 만들기 끝

            if( mode == '0' ){

                if( document.form1.title.value == '' ){
                    alert('제목을 입력해야 합니다.');
                    return;
                }

                if( confirm('등록하시겠습니까?') ){
                    document.form1.mode.value="insert";
                    document.form1.target="processFrame";

                    document.form1.submit();
                } else {
                    return;
                }
            } else if ( mode == '1' ) {
                if( document.form1.title.value == '' ){
                    alert('제목을 입력해야 합니다.');
                    return;
                }

                if( confirm('수정하시겠습니까?') ){
                    document.form1.mode.value="modify";
                    document.form1.target="processFrame";

                    document.form1.submit();
                } else {
                    return;
                }
            } else if ( mode == '2' ) {
                document.form1.no.value=no;
                document.form1.mode.value="modfiy_select";
                document.form1.submit();
            } else if ( mode == '3' ) {
                if( confirm('삭제하시겠습니까?') ){
                    document.form1.no.value=no;
                    document.form1.mode.value="delete";
                    document.form1.target="processFrame";
                    document.form1.submit();
                } else {
                    return;
                }
            } else {
                alert('잘못된 입력입니다.');
                return;
            }
        }

        function goBackList(){
            location.href="community_lookbook_list.php";
        }


    </script>

    <div class="content-wrap">
		<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post enctype="multipart/form-data">
		    <input type=hidden name=tb value="lookbook">
		    <input type=hidden name=mode>
		    <input type=hidden name=no value="<?=$no?>">
		    <table cellpadding="0" cellspacing="0" width="100%">
		        <tr>
		            <td>
		                <!-- 페이지 타이틀 -->
		                <div class="title_depth3">LOOKBOOK <?=$qType_text?><span>LOOKBOOK <?=$qType_text?>할 수 있습니다.</span></div>

		            </td>
		        </tr>

		        <tr><td height=3></td></tr>
		        <tr>
		            <td>
		                <?#include("layer_prlistPop.php");?>
		                <div class="table_style01 m-t-20">
		                    <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		                        <col width=140></col>
		                        <col width=></col>
		                        <tr>
		                            <th><span>제목</span></th>
		                            <TD><INPUT maxLength=80 size=80 id='title' name='title' class="input w-100" value="<?=$mSelect['title']?>"></TD>
		                        </tr>

		                        <tr>
		                            <th>
		                                <span>관련상품</span>&nbsp;&nbsp;
		                                <button type="button" class="btn btn-primary btn-sm" onclick="LookBook.open()" >조회</button>
		                            </th>
		                            <td align="left">
		                                <div class="table_style02">
		                                    <table name="prList" style="width: 100%;">
		                                        <input type="hidden" name="relationProduct" id="relationProduct" value=""/>
		                                        <colgroup>
		                                            <th width=20>상품이미지</th>
		                                            <th width=50>상품명</th>
		                                            <th width=50>상품모델명</th>
		                                            <th width=50>판매상태</th>
		                                            <th width=50>정상가</th>
		                                            <th width=50>판매가</th>
		                                            <th width=50>할인율</th>
		                                            <th width=50>재고</th>
		                                            <th width=></th>
		                                        </colgroup>
                                                <tbody id="check_relationProduct">
		                                        <?
		                                        $relationProductArr = explode(",",$mSelect['relation_product']);
		                                        for($ii=0; $ii < count($relationProductArr); $ii++){
		                                            if($relationProductArr[$ii]!=""){
		                                                $relationProductArr_serialze .= "'".$relationProductArr[$ii]."',";

		                                                $insetSeq_sql = "select * from tblproduct where productcode = '".$relationProductArr[$ii]."'";
		                                                $insertSeq_result = pmysql_query($insetSeq_sql);
		                                                $insertSeq_row = pmysql_fetch_object( $insertSeq_result );
		                                                $tinyimage = $insertSeq_row->tinyimage;
		                                                $productname = $insertSeq_row->productname;
		                                                $productcode = $insertSeq_row->productcode;
                                                        $prodcode = $insertSeq_row->prodcode;
                                                        $soldout = $product->trans('soldout',$insertSeq_row->soldout);
                                                        $consumerprice = number_format($insertSeq_row->consumerprice);
                                                        $sellprice = number_format($insertSeq_row->sellprice);
                                                        $sale_rate = $insertSeq_row->sellprice_dc_rate;
                                                        $quantity = number_format($insertSeq_row->quantity);
		                                                ?>
                                                        <tr data-productcode="<?=$productcode?>">
                                                            <td><img alt="" class="thumbnail b-lazy" src="<?=getProductImage($Dir.DataDir.'shopimages/product/', $tinyimage );?>"></td>
                                                            <td class="ta_l"><?=$productname?></td>
                                                            <td><?=$prodcode?>
                                                                <div class="fc-null">(<?=$productcode?>)</div>
                                                            </td>
                                                            <td><?=$soldout?></td>
                                                            <td><?=$consumerprice?></td>
                                                            <td><?=$sellprice?></td>
                                                            <td><?=$sale_rate?>%</td>
                                                            <td><?=$quantity?></td>
                                                            <td><button type="button" class="btn_type1" onclick="LookBook.remove(this)">삭제</td>
                                                        </tr>
                                                                                        <?
                                                                                    }
                                                                                }
                                                                                pmysql_free_result( $mSelectRes );
                                                                                ?>
                                                </tbody>
                                            </table>
                                            <script>
                                                $( function() {
                                                    $( "#check_relationProduct" ).sortable({
                                                        placeholder: "highlight",
                                                        cursor: "move"
                                                    });
                                                    $( "#check_relationProduct" ).disableSelection();
                                                } );
                                            </script>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr id="ID_trImgPc">
                                                                    <th><span id="imgfile_pc">썸네일이미지(PC)</span></th>
                                                                    <td class="td_con1" style="position:relative">
                                                                        <input type=file name="up_imagefile[0]" id="up_imagefile" style="WIDTH: 400px"><br>
                                                                        <!--<input type=hidden name="v_up_imagefile[0]" value="<?/*=$mSelect['img_file']*/?>" >
                                                                        <?/*	if( is_file($imagepath.$mSelect['img_file']) ){ */?>
                                                                            <div style='margin-top:5px' >
                                                                                <img src='<?/*=$imagepath.$mSelect['img_file']*/?>' style='max-height: 200px;' />
                                                                            </div>
                                                                        --><?/*	} */?>
                                                                    </td>
                                                                </tr>
                                                                <tr id="ID_trImgMobile">
                                                                    <th><span id="imgfile_mobile">썸네일이미지(MOBILE)</span></th>
                                                                    <td class="td_con1" style="position:relative">
                                                                        <input type=file name="up_imagefile[1]" id="up_imagefile2" style="WIDTH: 400px"><br>
                                                                        <!--<input type=hidden name="v_up_imagefile[1]" value="<?/*=$mSelect['img_m_file']*/?>" >
                                                                        <?/*	if( is_file($imagepath.$mSelect['img_m_file']) ){ */?>
                                                                            <div style='margin-top:5px' >
                                                                                <img src='<?/*=$imagepath.$mSelect['img_m_file']*/?>' style='max-height: 200px;' />
                                                                            </div>
                                                                        --><?/*	} */?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th><span>LOOKBOOK 이미지(PC)<br></span><div style="text-align: center;">(이미지 사이즈: 1160 x 738)</div></th>
                                                                    <TD>
                                                                    	<button>파일업로드</button>
                                                                    </TD>
                                                                </tr>
                                                                <tr>
                                                                    <th><span>LOOKBOOK 이미지(mobile)<br></span></th>
                                                                    <TD>
                                                                    	<button>파일업로드</button>
                                                                    </TD>
                                                                </tr>
                                                                <tr>
                                                                    <th><span>내용</span></th>
                                                                    <TD><textarea wrap=off  id="ir1" style="WIDTH: 100%; HEIGHT: 300px" name="content"><?=$mSelect['content']?></textarea></TD>
                                                                </tr>
                                                                <tr>
                                                                    <th><span>태그</span></th>
                                                                    <TD>
                                                                    	<INPUT maxLength=80 size=80 id='hash_tags' name='hash_tags' class="input w-100" value="<?=$mSelect['tag']?>">
                                                                    	<div class="helper m-t-5"># 없이 ,(콤마)로 구분하여 작성하여 주십시오.</div>
                                                                    </TD>
                                                                </tr>
                                                                <tr>
                                                                    <th><span>노출</span></th>
                                                                    <TD><INPUT type='checkbox' id='display' name='display' value="1" <? if( $mSelect['display'] == 'Y' ) { echo "CHECKED"; } ?> > * 체크시 노출됩니다. </TD>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="table_style01 lookbooktable" style='padding-bottom:0px'></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan=8 align=center>
                                                        <?php
                                                        if( $qType == '0' ){
                                                            ?>
                                                            <a href="javascript:CheckForm('<?=$qType?>', '<?=$mSelect['idx']?>' );"><img src="img/btn/btn_input02.gif" alt="등록하기"></a>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a href="javascript:CheckForm('<?=$qType?>', '<?=$mSelect['no']?>' );"><img src="images/btn_edit2.gif" alt="수정하기"></a>
                                                            <a href="javascript:CheckForm('3', '<?=$mSelect['no']?>' );"><img src="images/botteon_del.gif" alt="삭제하기"></a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <a href="javascript:goBackList();"><img src="img/btn/btn_list.gif" alt="목록보기"></a>
                                                    </td>
                                                </tr>
                                                <tr><td height=20></td></tr>
                                                <tr>
                                                    <td>
                                                        <!-- 매뉴얼 -->
                                                        <div class="sub_manual_wrap">
                                                            <div class="title"><p>매뉴얼</p></div>

                                                            <dl>
                                                                <dt><span>MAGAZINE <?=$qType_text?></span></dt>
                                                                <dd>- MAGAZINE을 <?=$qType_text?>할 수 있습니다.
                                                                </dd>
                                                            </dl>

                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                       </div>

    <!--{* 상품목록 템플릿:s *}-->
    <textarea id="tpl_tr" style="display:none">
	<tr data-productcode="${productcode}">
		<td>${thumbnail}</td>
		<td class="ta_l">${name}</td>
		<td>${prodcode}
			<div class="fc-null">(${productcode})</div>
		</td>
		<td>${soldout}</td>
		<td>${price_consumer}</td>
		<td>${price_sell}</td>
		<td>${sale_rate}</td>
		<td>${stock}</td>
        <td><button type="button" class="btn_type1" onclick="LookBook.remove(this)">삭제</td>
		
	</tr>
    </textarea>
    <!--{* 상품목록 템플릿:E *}-->

    <form id="FrmProductSearch" name="FrmProductSearch" onsubmit="return false">
        <input type="hidden" name="mode" value="reg_desc">
        <input type="hidden" name="act" value="save">
        <input type="hidden" name="sort" value="reg_desc" /> <!-- 정렬기준 -->
        <input type="hidden" name="limit" value="20" /><!-- 페이지당노출개수 -->
    </form>

    <script type="text/javascript" src="/third_party/SE2/js/HuskyEZCreator.js" charset="utf-8"></script>
	<script type="text/javascript" src="/admin/static/js/template.js"></script>
    <script language="javascript">
        //룩북관련
        var LookBook = {
            init: function() {
            	var me = this;
            	me.createEditor('ir1');
            },
            createEditor: function (id) {
				nhn.husky.EZCreator.createInIFrame($.extend({}, se_option, {
					elPlaceHolder: id
				}));
			},
            open: function () { //상품추가모달열기
                var search = $('#FrmProductSearch').serialize();
                UI.modal('/admin/product_choice.php', '상품추가', {search: search}, 1100);
            },
            loadCallback: function() {
                //이동항목선택바인딩
                $('#check_relationProduct tr[data-productcode]').on('click.selected', function() {
                    $(this).siblings('tr').removeClass('selected');
                    $(this).addClass('selected');
                });
            },
            getProduct: function() {//기등록상품목록리턴
                return $('#check_relationProduct').find('tr:not(.empty)');
            },
            remove: function (ele) { //삭제(단순감춤)
                $(ele).closest('tr').remove();
            },
        };

        //상품선택 콜백
        var ChoiceCallback = function(tr_new){
            var tmpObj = TrimPath.parseDOMTemplate("tpl_tr");
            var target = $('#check_relationProduct');
            var tr = LookBook.getProduct();

            //기등록상품수
            var cnt = tr.length;

            //기등록상품 리스트
            var exist = tr.map(function () {return $(this).attr('data-productcode');}).get();

            var idx = cnt;
            $.each(tr_new, function(i,e) {
                var prcode = $(e).attr('data-productcode');
                if($.inArray(prcode, exist) != -1) return true;
                /*
                if(idx >= ProductOptRegister.max) {
                    alert('추천상품은 최대 '+ProductOptRegister.max+'개까지 등록가능합니다.');
                    return false;
                }
                */

                idx++;
                target.find('tr.empty').addClass('hide');
                var data = {idx:idx};
                $.each($(e).find('td'), function(ii,ee) {
                    var field = $(ee).data('field');
                    if(typeof field == 'undefined') return true;
                    data[field] = $(ee).html();
                });
                var html  = tmpObj.process(data);
                target.append(html);
            });
            LookBook.loadCallback();
        };

        $(document).ready( function() {
            LookBook.init();
        });
    </script>

    <iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>
<?=$onload?>
<?php
include("copyright.php");
