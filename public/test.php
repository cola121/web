<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/4/13
 * Time: 9:19
 */

//var_dump($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//var_dump($_SERVER['PHP_SELF']);
//var_dump($_SERVER['QUERY_STRING']);
//var_dump($_SERVER['HTTP_REFERER']);
//var_dump($_SERVER['SERVER_PORT']);
//var_dump($_SERVER['REMOTE_ADDR']);
//var_dump($_SERVER['REMOTE_HOST']);
//echo "<br>";
//$url = "http:://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//$path = parse_url($url);
//var_dump(pathinfo($path['path'],PATHINFO_DIRNAME));
var_dump($_GET['A']);

function test($str) {
    $pattern='/(.)\1/';
    $str = preg_replace($pattern,'',$str);
    if (preg_match($pattern, $str)) {
        return test($str);
    } else {
        return $str;
    }
}
$str='gaewwenngoeeojjgegop';
var_dump(test($str));

