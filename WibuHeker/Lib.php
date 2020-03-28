<?php
/**
 * @WibuHeker | Library Class
 */
 define("OS", strtolower(PHP_OS));
 class Library {

     public static function inStr($s, $as)
     {
        $s = strtoupper($s);
        if(!is_array($as)) $as=array($as);
        for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i]))!==false) return true;
        return false;
    }
    public static function Save($filename, $content) {
        $f = fopen($filename, 'a+');
        fwrite($f, $content . "\n");
        fclose($f);
    }
    public static function getString($str, $O01, $O02)
    {
        if(strpos($str, $O01) === FALSE) return FALSE;
        if(strpos($str, $O02) === FALSE) return FALSE;
        $start = strpos($str, $O01) + strlen($O01);
        $end = strpos($str, $O02, $start);
        $return = substr($str, $start, $end - $start);
        return $return;
    }
    public static function setColor()
    {
        return array(
            "BL" => (OS == "linux" ? "\e[0;34m" : ""),
            "WH" => (OS == "linux" ? "\e[0m" : ""),
            "YL" => (OS == "linux" ? "\e[1;33m" : ""),
            "RD" => (OS == "linux" ? "\e[0;31m" : ""),
            "GRN" => (OS == "linux" ? "\e[32;4m" : ""),
            "BLD" => (OS == "linux" ? "\e[1m" : "")
        );
    }
 }
