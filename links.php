<?php
include 'All.php';

$xml = new SimpleXMLElement('<pages/>');
$all = new All();
$champions = $all -> getChampions();

foreach ($champions as $champion) {
    $link = $xml->addChild('link');
    $link ->addChild('title', "$champion[1] ");
    $link ->addChild('url', "asd ");
}

Header('Content-type: text/xml');
print($xml->asXML());