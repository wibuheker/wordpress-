<?php
/*
 * @Wibuheker | Curl Class
 * 
 */
class Curl {
    public static $URL = null;
    public static $ch;
    public static function MakeRequests()
    {
        self::$ch = curl_init();
        curl_setopt (self::$ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt (self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt (self::$ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    public static function SetHeaders($header)
    {
        curl_setopt (self::$ch, CURLOPT_HTTPHEADER, $header);
    }
    public static function setTimeout($timeout)
    {
        curl_setopt (self::$ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt (self::$ch, CURLOPT_CONNECTTIMEOUT,$timeout);
    }
    public static function Cookies($file_path)
    {
        $fp = fopen($file_path, 'wb');
        fclose($fp);
        curl_setopt (self::$ch, CURLOPT_COOKIEJAR, $file_path);
        curl_setopt (self::$ch, CURLOPT_COOKIEFILE, $file_path);
    }
    public static function Follow()
    {
        curl_setopt (self::$ch, CURLOPT_FOLLOWLOCATION, 1);
    }
    public static function Post($data)
    {
        curl_setopt (self::$ch, CURLOPT_URL, self::$URL);
        curl_setopt(self::$ch, CURLOPT_POST, 1);	
	curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $data);
    }
    public static function Get()
    {
        curl_setopt (self::$ch, CURLOPT_URL, self::$URL);
        curl_setopt (self::$ch, CURLOPT_POST, 0);
    }
    public static function StatusCode()
    {
        return curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
    }
    public static function Header()
    {
        $header = curl_getinfo(self::$ch, CURLINFO_HEADER_SIZE);
        $head = substr(self::Body(), 0, $header);
        return $head;
    }
    public static function Body()
    {
        $data = curl_exec (self::$ch);
        return $data;
    }
}
