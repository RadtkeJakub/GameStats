<?php
    require_once 'champion.php';
    require_once 'All.php';
//    error_reporting(0);
//    ini_set('display_errors', 0);

    $champions = new All();
    $championsId = $champions -> getChampions();
    $items = $champions ->getItems();
    $pros = $champions ->getPros();
    foreach ($championsId as $championId)
    {
        echo $championId[0]." ".$championId[1]." ".$championId[2]." ".$championId[3]."<br />";
    }
    foreach ($items as $item)
    {
        echo $item[0]." ".$item[1]." ".$item[2]."<br />";
    }
    foreach ($pros as $pro)
    {
        echo $pro[0]." ".$pro[1]." ".$pro[2]."<br />";
    }
    unset($champions);

    $champion = new Champion(3);
    $runes = $champion -> getRunes();
    $spells = $champion -> getSummoners();
    $items = $champion -> getItems();
    $games = $champion -> getGames();
    $winratio = $champion ->getWinRatio();
    $name = $champion ->getName();
    $bestEnemies = $champion -> getBestEnemies();
    $bestTeam = $champion -> getBestTeamMatchups();
    $pros = $champion -> getPros();
    $worstEnemies = $champion -> getWorstEnemies();
    $worstTeam = $champion -> getWorstTeamMatchups();

    print_r($runes);
    echo "<br>";
    print_r($spells);
echo "<br>";
    print_r($items);
echo "<br>";
    print_r($games);
echo "<br>";
    print_r($winratio);
echo "<br>";
    print_r($name);
echo "<br>";
    print_r($bestEnemies);
echo "<br>";
    print_r($bestTeam);
echo "<br>";
    print_r($pros);
echo "<br>";
    print_r($worstEnemies);
echo "<br>";
    print_r($worstTeam);


