<?php
namespace MatMuh;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class Helper
{
    public static function fileExtension($file)
    {
        $str = explode(".", $file);
		return strtolower($str[sizeof($str)-1]);
    }

    public static function fixString($string, $time = 0, $extra = "")
    {
        $new = mb_strtolower($string,"utf-8");

    	$new = str_replace("ğ", "g", $new);
    	$new = str_replace("ı", "i", $new);
    	$new = str_replace("ş", "s", $new);
    	$new = str_replace("ç", "c", $new);
    	$new = str_replace("ö", "o", $new);
    	$new = str_replace("ü", "u", $new);

    	$new = preg_replace("/[^a-zA-Z0-9s.]/", "_", $new);

    	if($time)
    		$new .= '_' . time();

    	if($extra)
    		$new .= '_' . $extra;

    	return $new;
    }

    public static function generatePassword($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
}