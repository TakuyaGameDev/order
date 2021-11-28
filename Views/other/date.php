<?php

    // 曜日
    $week = array( "日", "月", "火", "水", "木", "金", "土","日", "月", "火", "水", "木", "金", "土", );
    // 今日
    $today = date("m/d")." (".$week[date("w")].") ";
    // 未来
    // 6日後
    $ago[6] = date('m/d', strtotime('+6 day'))." (".$week[date("w")+6].") ";
    // 5日後
    $ago[5] = date('m/d', strtotime('+5 day'))." (".$week[date("w")+5].") ";
    // 4日後
    $ago[4] = date('m/d', strtotime('+4 day'))." (".$week[date("w")+4].") ";
    // 3日後
    $ago[3] = date('m/d', strtotime('+3 day'))." (".$week[date("w")+3].") ";
    // 2日後
    $ago[2] = date('m/d', strtotime('+2 day'))." (".$week[date("w")+2].") ";
    // 1日後
    $ago[1] = date('m/d', strtotime('+1 day'))." (".$week[date("w")+1].") ";

    // 過去
    // 7日前
    $before[7] = date('m/d', strtotime('-7 day'))." (".$week[date("w")].") ";
    // 6日前
    $before[6] = date('m/d', strtotime('-6 day'))." (".$week[date("w")+1].") ";
    // 5日前
    $before[5] = date('m/d', strtotime('-5 day'))." (".$week[date("w")+2].") ";
    // 4日前
    $before[4] = date('m/d', strtotime('-4 day'))." (".$week[date("w")+3].") ";
    // 3日前
    $before[3] = date('m/d', strtotime('-3 day'))." (".$week[date("w")+4].") ";
    // 2日前
    $before[2] = date('m/d', strtotime('-2 day'))." (".$week[date("w")+5].") ";
    // 1日前
    $before[1] = date('m/d', strtotime('-1 day'))." (".$week[date("w")+6].") ";

