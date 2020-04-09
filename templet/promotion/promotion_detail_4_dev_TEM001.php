<?php
    $sql    = "SELECT * FROM tblpromo WHERE idx = '{$idx}' AND end_date > current_date";
    $row    = pmysql_fetch_object(pmysql_query($sql));

    if ( !$row ) {
        // =================================================================================
        // 출석체크 이벤트가 기간을 지난 경우
        // 현재날짜 기준으로 등록된 출석체크 이벤트를 조회
        // =================================================================================
        $sql  = "SELECT * FROM tblpromo ";
        $sql .= "WHERE event_type = '4' AND hidden = 1 AND display_type in ('A', 'P') AND current_date between start_date and end_date ";
        $sql .= "LIMIT 1";
        $row  = pmysql_fetch_object(pmysql_query($sql));

        if ( !$row ) {
            // =================================================================================
            // 아직 이번달 출석체크 이벤트가 없는 경우
            // =================================================================================
            echo '
                <script type="text/javascript">
                    alert("진행중인 출석체크 이벤트가 없습니다.");
                    window.history.back();
                </script>
            ';
            exit;
        }

        $idx = $row->idx;
    }

/*
    출석체크에 사용할 달력을 그리는 함수
*/
function draw_calendar_dev($mem_id, $idx, $year, $month, $weekly_icon, $weekend_icon, $isMobile) {
    
    // 출석체크한 내용 조회
    $arrDays = array();
    if ( $mem_id != "" ) { 
        $sql  = "SELECT * FROM tblattendancerecord WHERE id = '{$mem_id}' AND promo_idx = {$idx} ";
        $sql .= "ORDER BY idx asc ";
        $result = pmysql_query($sql);

        // 출석체크한 날짜들로 배열 인덱스를 지정한다.
        while ($row = pmysql_fetch_array($result) ) {
            $arrDays[$row['day']] = "";
        }
    }

    $today = ltrim(date("d"), "0");                              // 오늘 일자 
    $running_day = date('w',mktime(0,0,0,$month,1,$year));      // 해당월의 첫날의 요일번호(0:일요일 ~ 6:토요일)
    $days_in_month = date('t',mktime(0,0,0,$month,1,$year));    // 해당월의 마지막 날

    $calendar = '';

    if ( $isMobile ) {

/*
        // 달력 앞 부분을 채운다. (빈 공간)
        $calendar = "<tr>";

        for($x = 0; $x < $running_day; $x++):
            $addDayClass = "";
            if ( $x == 6 ) {
                $addDayClass = "sat";   // 토요일
            } else {
                $addDayClass = "sun";   // 일요일
            }

            $calendar.= "<td class='{$addDayClass}'></td>";
        endfor;

        // 일별로 생성
        for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
            $addDayClass = "";
            if ( $running_day == 6 ) {
                $addDayClass = "sat";   // 토요일
            } else {
                $addDayClass = "sun";   // 일요일
            }

            $calendar.= "<td class=\"{$addDayClass}\">";

            if ( $running_day >= 1 && $running_day <= 5 ) {
                // 주중
                $iconUrl = '/data/shopimages/timesale/' . $weekly_icon;

                if ( $list_day == $today ) {
                    // 오늘 날짜인 경우

                    if ( isset($arrDays[$list_day]) ) {
                        // 출석체크를 한 날인경우
                        $pos = -108;
                        $addClass = "ok";
                    } else {
                        $pos = 0;
                        $addClass = "today";
                    }

                } elseif ( isset($arrDays[$list_day]) ) {
                    // 출석체크를 한 날인경우
                    $pos = -108;
                    $addClass = "ok";
                } else {
                    $pos = -216;
                    $addClass = "";
                }

                $calendar.= '<button class="check ' . $addClass . '" type="button" style="background:url(\'' . $iconUrl . '\') ' . $pos . 'px 0 no-repeat"></button><span class="day">' . $list_day;
            } else {
                // 주말
                $iconUrl = '/data/shopimages/timesale/' . $weekend_icon;

                if ( $list_day == $today ) {
                    // 오늘 날짜인 경우

                    if ( isset($arrDays[$list_day]) ) {
                        // 출석체크를 한 날인경우
                        $pos = -260;
                        $addClass = "ok";
                    } else {
                        $pos = -130;
                        $addClass = "today";
                    }
                } elseif ( isset($arrDays[$list_day]) ) {
                    // 출석체크를 한 날인경우
                    $pos = -260;
                    $addClass = "ok";
                } else {
                    $pos = 0;
                    $addClass = "";
                }

                $calendar.= '<button class="check-holiday ' . $addClass . '" type="button" style="background:url(\'' . $iconUrl . '\') ' . $pos . 'px 0 no-repeat"></button><span class="day ' . $addDayClass . '">' . $list_day;
            }

            $calendar .= '</td>';

            $running_day++;

            // 일요일은 7이 아니라 0으로 셋팅
            if ( $running_day == 7 ) { $running_day = 0; }
        }

        // 나머지 남은 공간을 채운다.
        for ( $list_day = 0; $list_day < 7 - $running_day; $list_day++ ) {
            $calendar.= '<li></li>';
        }
*/

    } else {

        // 달력 앞 부분을 채운다. (빈 공간)
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<li></li>';
        endfor;

        // 일별로 생성
        for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
            $calendar.= '<li>';

            if ( $running_day >= 1 && $running_day <= 5 ) {
                // 주중
                $iconUrl = '/data/shopimages/timesale/' . $weekly_icon;

                if ( $list_day == $today ) {
                    // 오늘 날짜인 경우

                    if ( isset($arrDays[$list_day]) ) {
                        // 출석체크를 한 날인경우
                        $pos = -108;
                        $addClass = "ok";
                    } else {
                        $pos = 0;
                        $addClass = "today";
                    }

                } elseif ( isset($arrDays[$list_day]) ) {
                    // 출석체크를 한 날인경우
                    $pos = -108;
                    $addClass = "ok";
                } else {
                    $pos = -216;
                    $addClass = "";
                }

                $calendar.= '<button class="check ' . $addClass . '" type="button" style="background:url(\'' . $iconUrl . '\') ' . $pos . 'px 0 no-repeat"></button><span class="day">' . $list_day;
            } else {
                // 주말
                $iconUrl = '/data/shopimages/timesale/' . $weekend_icon;

                if ( $list_day == $today ) {
                    // 오늘 날짜인 경우

                    if ( isset($arrDays[$list_day]) ) {
                        // 출석체크를 한 날인경우
                        $pos = -260;
                        $addClass = "ok";
                    } else {
                        $pos = -130;
                        $addClass = "today";
                    }
                } elseif ( isset($arrDays[$list_day]) ) {
                    // 출석체크를 한 날인경우
                    $pos = -260;
                    $addClass = "ok";
                } else {
                    $pos = 0;
                    $addClass = "";
                }

                if ( $running_day == 6 ) {
                    $addDayClass = "sat";   // 토요일
                } else {
                    $addDayClass = "sun";   // 일요일
                }

                $calendar.= '<button class="check-holiday ' . $addClass . '" type="button" style="background:url(\'' . $iconUrl . '\') ' . $pos . 'px 0 no-repeat"></button><span class="day ' . $addDayClass . '">' . $list_day;
            }

            $calendar .= '</li>';

            $running_day++;

            // 일요일은 7이 아니라 0으로 셋팅
            if ( $running_day == 7 ) { $running_day = 0; }
        }

        // 나머지 남은 공간을 채운다.
        for ( $list_day = 0; $list_day < 7 - $running_day; $list_day++ ) {
            $calendar.= '<li></li>';
        }

    }

    return $calendar;
}

    $arrDate    = explode("-", $row->start_date);
                        
    $year                   = $arrDate[0];                              // 해당년
    $month                  = trim($arrDate[1], "0");                   // 해당월
    $weekly_icon            = $row->attendance_weekly_icon;             // 주중 아이콘
    $weekend_icon           = $row->attendance_weekend_icon;            // 주말 아이콘
    $weekly_mobile_icon     = $row->attendance_weekly_mobile_icon;      // 주중 아이콘(모바일)
    $weekend_mobile_icon    = $row->attendance_weekend_mobile_icon;     // 주말 아이콘(모바일)

    // 쿠폰 정보를 조회하기 위한 사전작업
    $arrCouponCode = array();
    if ( $row->attendance_weekly_reward_coupon ) { array_push($arrCouponCode, "'{$row->attendance_weekly_reward_coupon}'"); }
    if ( $row->attendance_weekend_reward_coupon ) { array_push($arrCouponCode, "'{$row->attendance_weekend_reward_coupon}'"); }
    if ( $row->attendance_complete_reward_coupon ) { array_push($arrCouponCode, "'{$row->attendance_complete_reward_coupon}'"); }

    $whereCouponCode = implode(",", $arrCouponCode);

    // 쿠폰 정보를 배열에 저장
    $arrCouponName = array();
    $arrCouponMiniPrice = array();
    if ( count($arrCouponCode) > 0 ) {
        $sql    = "SELECT * FROM tblcouponinfo WHERE coupon_code in ( {$whereCouponCode} ) ";
        $result = pmysql_query($sql);
        
        while ($coupon_row = pmysql_fetch_array($result)) {
            $arrCouponName[$coupon_row['coupon_code']] = $coupon_row['coupon_name'];   
            $arrCouponMiniPrice[$coupon_row['coupon_code']] = $coupon_row['mini_price'];   
        }
    }

    $calendarHtml = draw_calendar_dev($_ShopInfo->getMemid(), $idx, $year, $month, $weekly_icon, $weekend_icon, $isMobile);

    // =================================================================================
    // 하단 멘트
    // =================================================================================
    $bottomComment = '<p>- 출석은 1일 1회만 가능합니다. (PC or 모바일)</p>';

    if ( $row->attendance_weekly_reward === "0" ) { 
        $bottomComment .= '<p>- 평일 출석체크는 마일리지 ' . number_format($row->attendance_weekly_reward_point) . 'M 적립, ';
    } else { 
        $bottomComment .= '<p>- 평일 출석체크는 \'' . $arrCouponName[$row->attendance_weekly_reward_coupon] . '\' 쿠폰 지급 ,';
    } 

    if ( $row->attendance_weekend_reward === "0" ) { 
        $bottomComment .= '주말(토,일)은 마일리지 ' . number_format($row->attendance_weekend_reward_point) . 'M 적립됩니다.</p>';
    } else { 
        $bottomComment .= '주말(토,일)은 \'' . $arrCouponName[$row->attendance_weekend_reward_coupon] . '\' 쿠폰이 지급됩니다.</p>';
    } 

    if ( $row->attendance_complete_reward === "0" ) { 
        $bottomComment .= '<p>- 한달간 모두 출석체크 해주신 경우 마일리지 ' . number_format($row->attendance_complete_reward_point) . ' 적립됩니다. </p>';
    } else {
        $bottomComment .= '<p>- 한달간 모두 출석체크 해주신 경우 \'' . $arrCouponName[$row->attendance_complete_reward_coupon] . '\' 쿠폰이 발행됩니다.</p>';
    } 

    $bottomComment .= '<p>- 주말쿠폰은 주말(토,일)에만 사용이 가능한 쿠폰입니다.</p>';

    if ( $row->attendance_complete_reward === "1" ) {
        $bottomComment .= '<p>- \'' . $arrCouponName[$row->attendance_complete_reward_coupon] . '\' 쿠폰은 ' . number_format($arrCouponMiniPrice[$row->attendance_complete_reward_coupon]) . '원 이상 상품 구매시 사용가능합니다.</p>';
    }

    if ( $isMobile ) {
        include($Dir.TempletDir."promotion/mobile/promotion_detail_4_TEM001.php");
    } else {
?>

<div id="contents">
        <div class="containerBody sub-page">
            
            <div class="breadcrumb">
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="/front/promotion.php">PROMOTION</a></li>
                    <li class="on"><a href="#">출석체크</a></li>
                </ul>
            </div><!-- //.breadcrumb -->

            <div class="attend-event-wrap">
                <div class="inner">
                    <p class="year"><?=$year?></p>
                    <h4 class="title"><?=$month?>월 출석체크</h4>
                    <ul class="day-name">
                        <li>sunday</li>
                        <li>monday</li>
                        <li>tuesday</li>
                        <li>wendsday</li>
                        <li>thursday</li>
                        <li>friday</li>
                        <li>saturday</li>
                    </ul>
                    <ul class="calendar">
                        <?=$calendarHtml?>
                    </ul>
                </div>
                <table class="attention">
                    <colgroup><col style="width:177px"><col style="width:auto"><col style="width:360px"></colgroup>
                    <tr>
                        <th>유의사항</th>
                        <td>
                            <?=$bottomComment?>
                        </td>
                        <td class="benefit">
                            <a href="/front/mypage_reserve.php">나의 마일리지</a>
                            <a href="/front/mypage_coupon.php">나의 사용가능 쿠폰</a>
                        </td>
                    </tr>
                </table>
            </div><!-- //.attend-event-wrap -->

        </div><!-- //공통 container -->
    </div><!-- //contents -->

<?  } ?>

<script type="text/javascript">

    $(document).ready(function() {
        var chk_func = function() {
            if ( $(this).hasClass('today') ) {
                setAttendance('<?=$idx?>', '<?=$_SERVER['REQUEST_URI']?>');
                // 출석체크를 해야 하는 경우
            }
        };

        $(".check-holiday").on("click", chk_func);
        $(".check").on("click", chk_func);

        // 모바일 출석체크 클릭 이벤트
        $(".area").on("click", chk_func);

        // 모바일 배경이미지 변경
        $("article.promo-attendance td .area").css("background-image", "url(/data/shopimages/timesale/<?=$weekly_mobile_icon?>)");
        $("article.promo-attendance td.sat .area").css("background-image", "url(/data/shopimages/timesale/<?=$weekend_mobile_icon?>)");
        $("article.promo-attendance td.sun .area").css("background-image", "url(/data/shopimages/timesale/<?=$weekend_mobile_icon?>)");
    });

</script>

