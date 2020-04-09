<?php
    $idx = $_GET['idx'];
    $num = $_GET['num'];
    $gotopage = $_GET['gotopage'];
    $keyword = trim($_GET['keyword']);              // 검색어
    $view_mode = trim($_GET['view_mode']) ?: "M";   // M : 이미지 형태, L : 리스트 형태
    $view_type = trim($_GET['view_type']) ?: "A";   // A : 전체, R : 진행중 이벤트, E : 종료된 이벤트, W : 당첨자 발표
    $mode = trim($_GET['mode']);

    $sql = "SELECT *, current_date - publication_date as diff_date FROM tblpromo WHERE idx = '{$idx}' ";

    if ( pmysql_num_rows(pmysql_query($sql)) === 0 ) {
        echo "<script type='text/javascript'>alert('존재하지 않는 내용입니다.'); history.go(-1);</script>";
    }

    $row = pmysql_fetch_object(pmysql_query($sql));
    $event_type         = $row->event_type;
    $winner_list_html   = $row->winner_list_content;    // 당첨자 발표 내용이 있는 경우
    $diff_date          = $row->diff_date;              // 발표일과 오늘날짜의 차이
    $start_date         = $row->start_date;
    $end_date           = $row->end_date;               
    $today              = date("Y-m-d");

    if ( $winner_list_html != "" ) {
        // 당첨자 발표가 있는 경우
        $navi_title = "당첨자 발표";
    } else {
        if ( $today >= $start_date && $today <= $end_date ) {
            // 진행중 이벤트
            $navi_title = "진행중 프로모션";
        } else {
            // 종료된 이벤트
            $navi_title = "지난 프로모션";
        }
    }

    if ( $winner_list_html != "" ) {
        // 당첨자 발표가 있는 경우
        include($Dir.TempletDir."promotion/promotion_detail_winner_list_TEM001.php"); 
    } elseif ( $event_type == "3" && !empty($num) ) {
        // 포토이벤트 상세페이지
        include($Dir.TempletDir."promotion/promotion_detail_{$event_type}_view_TEM001.php"); 
    } else {
        include($Dir.TempletDir."promotion/promotion_detail_{$event_type}_dev_TEM001.php"); 
    }

    include($Dir.TempletDir."promotion/promotion_detail_bottom_TEM001.php");
?>

