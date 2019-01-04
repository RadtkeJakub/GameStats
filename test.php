<?php
    require_once 'champion.php';
//    error_reporting(0);
//    ini_set('display_errors', 0);

    $champion = new Champion();
    $champion -> setId(1);
    $champion ->getName();
    $champion ->getWinRatio();
    $champion ->getGames();
    unset($champion);