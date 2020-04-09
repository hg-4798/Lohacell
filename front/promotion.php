<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


include "include/top.php";
include "include/gnb.php";

if($_REQUEST[ptype]=="event"){
    $event_no = "127";
}else{
    $event_no = "128";
}
$sql_rollimg = "select * from tblmainbannerimg where banner_no='".$event_no."' order by banner_sort";


$keyword = trim($_GET['keyword']);              // 검색어
$view_mode = trim($_GET['view_mode']) ?: "M";   // M : 이미지 형태, L : 리스트 형태
$view_type = trim($_GET['view_type']) ?: "R";   // A : 전체, R : 진행중 이벤트, E : 종료된 이벤트, W : 당첨자 발표
$view_type_val = trim($_GET['view_type_val']);          // 빈값 : 전체, C : CATEGORY VIEW, B : BRAND VIEW
$view_type_code = trim($_GET['view_type_code']);        // 카테고리 코드 or 브랜드 코드
?>
<script type="text/javascript" src="/js/json_adapter/json_adapter.js"></script>
<script type="text/javascript">

    var req = JSON.parse('<?=json_encode($_REQUEST)?>');
    var ses = JSON.parse('<?=json_encode($_SESSION)?>');

    var db = new JsonAdapter();
    var util = new UtilAdapter();
    var eventList = new EventList(req);

    var pArr 	= new Array(); //상품배열
    var poArr 	= new Array(); //상품옵션배열

    var total_cnt = 1;
    var currpage = 1;	//현재페이지
    var roundpage = 9;  //한페이지조회컨텐츠수
    var currgrp = 1;	//페이징그룹
    var roundgrp = 5; 	//페이징길이수

    $(document).ready( function() {

        if(req.currpage){
            currpage = req.currpage;
        }


        var page_type = req.ptype
        var event_type = req.event_type;
        if(!event_type) event_type = 1;

        var page_text = '';
        if(page_type=='event'){

            event_type = 2; //이벤트
            page_text = '이벤트';
            $('#menu_event').show();

        }else if(page_type=='special'){

            event_type = 1;
            page_text = '기획전';
            $('#menu_special').show();
        }


        $('#page_title').html(page_text);




        //페이징처리
        var total_cnt = 1;
        var roundpage = 9;  //한페이지조회컨텐츠수
        var currgrp = 1;	//페이징그룹
        var roundgrp = 10; 	//페이징길이수

        var ing_currpage = 1;
        var old_currpage = 1;
        if(req.currpage){
            if(req.chkPage) {
                switch (req.chkPage) {
                    case "1" :
                        ing_currpage = req.currpage, old_currpage = 1;
                        break;
                    case "2" :
                        ing_currpage = 1, old_currpage = req.currpage;
                        break;
                }
            }else {
                ing_currpage = req.currpage;
            }

        }

        //게시판전체카운트조회
        if(event_type=='2'){
            param = "'2','3'";  //2댓글3포토
        }
        if(event_type=='1'){
            param = "'0','1'";  //0타임세일1일반기획전
        }
        var data = db.getDBFunc({sp_name: 'event_list_cnt', sp_param : param});
        total_cnt = data.data[0].total_cnt;

        //페이징ui생성
        util.getPaging(total_cnt, ing_currpage, roundpage, roundgrp);
        var rows = eventList.setPaging(util.getPaging(total_cnt, ing_currpage, roundpage, roundgrp), ing_currpage);
        $('#paging_area').html(rows);

        //내용조회
        eventList.getEventList(event_type, ing_currpage, roundpage);


        /* 종료된이벤트*/
        //이벤트게시판전체카운트조회
        if(event_type=='2') param = "'2','3'";  //2댓글3포토
        var data = db.getDBFunc({sp_name: 'event_list_cnt_old', sp_param : param});
        total_cnt = data.data[0].total_cnt;

        //페이징ui생성
        util.getPaging(total_cnt, old_currpage, roundpage, roundgrp);
        var rows = eventList.setPaging(util.getPaging(total_cnt, old_currpage, roundpage, roundgrp), old_currpage);
        $('#paging_area_old').html(rows);

        //내용조회
        eventList.getEventListOld(event_type, old_currpage, roundpage);

        if(req.chkPage){
            var chkPage = req.chkPage;
            DivControl(2,chkPage);
            if(chkPage==2) {
                $('.active2').attr('class', 'active');
                $('.active1').removeAttr('active');
            }else{
                $('.active1').attr('class', 'active');
                $('.active2').removeAttr('active');
            }
        }else{
            $('.active1').attr('class', 'active');
            $('.active2').removeAttr('active');
        }
    });

    var chkPage = '1';
    function DivControl(event_type,div){
        req['chkPage'] = div;
        if(event_type=='2'){
            if(div==1){
                chkPage = '1';
                $('#nowEventArea').show();
                $('#oldEventArea').hide();
                $('#boardEventArea').hide();
            }
            if(div==2){
                chkPage = '2';
                $('#nowEventArea').hide();
                $('#oldEventArea').show();
                $('#boardEventArea').hide();
            }
            if(div==3){
                chkPage = '3';
                $('#nowEventArea').hide();
                $('#oldEventArea').hide();
                $('#boardEventArea').show();
            }

        }
    }

    //-----------------------------------
    //	이벤트
    //-----------------------------------
    function EventList(req){

        this.currpage = 0;
        this.roundpage = 0;


        /* 이벤트리스트 조회*/
        this.getEventList = function (event_type, currpage, roundpage){

            var param = '';
            if(event_type=='2'){
                param = "'2','3'";  //2댓글3포토
            }
            if(event_type=='1'){
                param = "'0','1'";  //0타임세일1일반기획전
            }


            var paging = [currpage,roundpage];
            var data = db.getDBFunc({sp_name: 'event_list', sp_param : param, sp_paging:paging});
            var list = data.data;

            if(data.data){

                var rows = '';
                for(var i = 0 ; i < list.length ; i++){

                    var start_date = list[i].start_date.replace(/-/gi, " .");
                    var end_date = list[i].end_date.replace(/-/gi, " .");

                    rows += ' 	<li><a href="promotion_detail.php?idx='+list[i].idx+'&event_type='+list[i].event_type+'&display_seq='+list[i].display_seq+'&chkPage=1'+'&view_mode='+list[i].thumb_img+'&view_type=R">';
                    rows += '		<figure>';
                    rows += '			<div class="thumb-img"><img src="../data/shopimages/timesale/'+list[i].thumb_img+'" alt="이벤트 이미지"></div>';
                    rows += '			<figcaption>';
                    rows += '				<p class="ellipsis subject">'+list[i].title+'</p>';
                    rows += '				<p class="date">'+start_date+'~'+ end_date+'</p>';
                    rows += '			</figcaption>';
                    rows += '		</figure>';
                    rows += '	</a></li>';

                }

                $('#getEventList').html(rows);

            }

        };

        /* 종료된이벤트리스트 조회*/
        this.getEventListOld = function (event_type, currpage, roundpage){

            var param = '';
            if(event_type=='2'){
                param = "'2','3'";  //2댓글3포토
            }
            if(event_type=='1'){
                param = "'0','1'";  //0타임세일1일반기획전
            }

            var paging = [currpage,roundpage];
            var data = db.getDBFunc({sp_name: 'event_list_old', sp_param : param, sp_paging:paging});
            var list = data.data;

            if(data.data){

                var rows = '';
                for(var i = 0 ; i < list.length ; i++){

                    var start_date = list[i].start_date.replace(/-/gi, " .");
                    var end_date = list[i].end_date.replace(/-/gi, " .");

                    rows += ' 	<li><a href="promotion_detail.php?idx='+list[i].idx+'&event_type='+list[i].event_type+'&display_seq='+list[i].display_seq+'&chkPage=2'+'&view_mode='+list[i].thumb_img+'&view_type=R">';
                    rows += '		<figure>';
                    rows += '			<div class="thumb-img"><img src="../data/shopimages/timesale/'+list[i].thumb_img+'" alt="이벤트 이미지"></div>';
                    rows += '			<figcaption>';
                    rows += '				<p class="ellipsis subject">'+list[i].title+'</p>';
                    rows += '				<p class="date">'+start_date+'~'+ end_date+'</p>';
                    rows += '			</figcaption>';
                    rows += '		</figure>';
                    rows += '	</a></li>';

                }

                $('#getEventList_old').html(rows);

            }

        };

        /* 페이징 화면세팅 (디자인공통) */
        this.setPaging = function (pageArr, currpage){

            //console.log(pageArr);
            var rows  = '';

            if(pageArr.before_currpage==0 && pageArr.currgrp==1){
                rows += '<a href="javascript://" class="prev-all" ></a>';
                rows += '<a href="javascript://" class="prev"  ></a>';

            }else{
                rows += '<a href="javascript://" class="prev-all on" onclick="eventList.goPage('+pageArr.beforeG_currpage+');"></a>';
                rows += '<a href="javascript://" class="prev on"  onclick="eventList.goPage('+pageArr.before_currpage+')";></a>';

            }

            for(var i = 0 ; i < pageArr.pageIndex.length ; i++){

                var on = '';
                if((pageArr.pageIndex[i]) == currpage){
                    on = 'on';
                }
                rows += '<a href="javascript://" onclick="eventList.goPage('+pageArr.pageIndex[i]+')"  class="number '+on+'">'+pageArr.pageIndex[i]+'</a>';

            }

            if(pageArr.after_currpage==0){
                rows += '<a href="javascript://"  class="next" );"></a>';
                rows += '<a href="javascript://"  class="next-all" )";></a>';

            }else{
                rows += '<a href="javascript://"  class="next on" onclick="eventList.goPage('+pageArr.after_currpage+');"></a>';
                rows += '<a href="javascript://"  class="next-all on" onclick="eventList.goPage('+pageArr.afterG_currpage+')";></a>';
            }

            return rows;

        };

        this.goPage = function (currpage){

            util.goPage(currpage, req); //parameter를 가져는 util입니다.
        };
    }


    /* 페이징화면이동 */
    function goPage(currpage){

        util.goPage(currpage, req);

    }


</script>



<div id="contents">
    <div class="promotion-page">

        <article class="promotion-wrap">
            <header><h2 class="promotion-title" id="page_title"></h2></header>
            <div class="promotionList-slider-wrap">
                <ul class="promotionList-slider">
                    <?
                    $result=pmysql_query($sql_rollimg,get_db_conn());
                    $i = 1;
                    while($row = pmysql_fetch_object($result)) {
                        ?>
                        <li><a href="<?=$row->banner_link?>"><img src="/data/shopimages/mainbanner/<?=$row->banner_img?>" alt=""></a></li>
                        <?
                    }
                    pmysql_free_result($result);
                    ?>

                </ul>
            </div>
            <div class="promotion-list-wrap ta-c mt-25" data-ui="TabMenu">

                <div class="tabs" id="menu_event" style="display: none;">
                    <button data-content="menu" class="active1" onclick="DivControl(2,1);"><span>진행중 이벤트</span></button>
                    <button data-content="menu" class="active2" onclick="DivControl(2,2);"><span>종료된 이벤트</span></button>
                    <!-- <button data-content="menu" onclick="DivControl(2,3);"><span>당첨자발표</span></button> -->
                </div>
                <div class="tabs" id="menu_special" style="display: none;">
                    <button data-content="menu" class="active1" onclick="DivControl(2,1);"><span>진행중 기획전</span></button>
                    <button data-content="menu" class="active2" onclick="DivControl(2,2);"><span>종료된 기획전</span></button>
                </div>
                <div id="nowEventArea">
                    <!--이벤트리스트-->
                    <ul class="list-photoType clear" id="getEventList">
                        <li>컨텐츠가 없습니다.</li>
                    </ul>
                    <div class="list-paginate mt-45" id="paging_area">
                        <li>등컨텐츠가 없습니다.</li>
                    </div>
                </div>
                <div id="oldEventArea" style="display: none;">
                    <!--이벤트리스트-->
                    <ul class="list-photoType clear" id="getEventList_old">
                        <li>컨텐츠가 없습니다</li>
                    </ul>
                    <div class="list-paginate mt-45" id="paging_area_old">
                        <li>컨텐츠가 없습니다</li>
                    </div>
                </div>

                <div id="boardEventArea" style="display: none;">
                    <div class="ta-r">
                        <form>
                            <fieldset>
                                <legend>당첨자 발표 검색</legend>
                                <input type="text" class="w250" placeholder="검색어를 입력해주세요." title="당첨자 검색어 입력자리">
                                <button class="btn-point ml-5 w70" type="submit"><span>검색</span></button>
                            </fieldset>
                        </form>
                    </div>
                    <table class="th-top mt-10">
                        <caption>이벤트 당첨자 목록</caption>
                        <colgroup>
                            <col style="width:100px">
                            <col style="width:auto">
                            <col style="width:250px">
                            <col style="width:150px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">이벤트명</th>
                            <th scope="col">이벤트 기간</th>
                            <th scope="col">발표일</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="txt-toneA">25</td>
                            <td class="subject"><a href="">설 연휴 이벤트 당첨자 발표</a></td>
                            <td class="txt-toneA">2017.01.14 ~ 2017.02.28</td>
                            <td class="txt-toneA">2017.03.02</td>
                        </tr>
                        <tr>
                            <td class="txt-toneA">24</td>
                            <td class="subject"><a href="">설 연휴 이벤트 당첨자 발표</a></td>
                            <td class="txt-toneA">2017.01.14 ~ 2017.02.28</td>
                            <td class="txt-toneA">2017.03.02</td>
                        </tr>
                        <tr>
                            <td class="txt-toneA">23</td>
                            <td class="subject"><a href="">설 연휴 이벤트 당첨자 발표</a></td>
                            <td class="txt-toneA">2017.01.14 ~ 2017.02.28</td>
                            <td class="txt-toneA">2017.03.02</td>
                        </tr>
                        <tr>
                            <td class="txt-toneA">22</td>
                            <td class="subject"><a href="">설 연휴 이벤트 당첨자 발표</a></td>
                            <td class="txt-toneA">2017.01.14 ~ 2017.02.28</td>
                            <td class="txt-toneA">2017.03.02</td>
                        </tr>
                        <tr>
                            <td class="txt-toneA">21</td>
                            <td class="subject"><a href="">설 연휴 이벤트 당첨자 발표</a></td>
                            <td class="txt-toneA">2017.01.14 ~ 2017.02.28</td>
                            <td class="txt-toneA">2017.03.02</td>
                        </tr>
                        <tr>
                            <td class="txt-toneA">20</td>
                            <td class="subject"><a href="">설 연휴 이벤트 당첨자 발표</a></td>
                            <td class="txt-toneA">2017.01.14 ~ 2017.02.28</td>
                            <td class="txt-toneA">2017.03.02</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="list-paginate mt-20">
                        <a href="#" class="prev-all"></a>
                        <a href="#" class="prev"></a>
                        <a href="#" class="number on">1</a>
                        <a href="#" class="number">2</a>
                        <a href="#" class="number">3</a>
                        <a href="#" class="number">4</a>
                        <a href="#" class="number">5</a>
                        <a href="#" class="number">6</a>
                        <a href="#" class="number">7</a>
                        <a href="#" class="number">8</a>
                        <a href="#" class="number">9</a>
                        <a href="#" class="number">10</a>
                        <a href="#" class="next on"></a>
                        <a href="#" class="next-all on"></a>
                    </div>
                </div>



            </div>

        </article>

    </div>
</div><!-- //#contents -->



<?//php include($Dir.TempletDir."promotion/promotion_TEM001.php"); ?>

<div id="create_openwin" style="display:none"></div>

<?
include "include/bottom.php";
?>
</BODY>
</HTML>