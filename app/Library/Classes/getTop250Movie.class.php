<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/5/3
 * Time: 14:40
 */

namespace App\Library\Classes;

use App\Library\Classes\getDouBanMovieInfo;

class getTop250Movie
{

    public static function getData($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        $resData = curl_exec($curl);
        curl_close($curl);
        $pattern = '/<li>(.*)<\/li>/isU';

        preg_match_all($pattern, $resData, $arr);

        return $arr[0];
    }

    public static function getMovies($str)
    {
        $movies = array();
        $movies['name'] = self::getMoveName($str);
        $movies['otherName'] = self::getOtherName($str);
        $movies['director'] = self::getMoveDirector($str);
        $movies['img'] = self::getMovieImg($str);
        $movies['points'] = self::getMoviePoints($str);
        $movies['quote'] = self::getMovieQuote($str);

        return $movies;
    }

    public static function getAllMovieInfo()
    {
        $moviesArr = array();

        $reqUrl = 'https://movie.douban.com/top250?start=225&filter=';
        $resData = self::getData($reqUrl);

        foreach ($resData as $row) {
            if (self::getMoveName($row)) {
                $movieLink = self::getInfoUrl($row);
                $info = new getDouBanMovieInfo($movieLink[0]);
                $movies = $info->getMovieInfos();
                $name= self::getMoveName($row);
                $movies['c_name'] = $name[0];
                if (isset($name[1])) {
                    $movies['e_name'] = str_replace('&nbsp;/&nbsp;', '', $name[1]);
                } else {
                    $movies['e_name'] = '';
                }

                $movies['other_name'] = self::getOtherName($row);
                $movies['img'] = self::getMovieImg($row);
                $movies['points'] = self::getMoviePoints($row);
                $movies['quote'] = self::getMovieQuote($row);
                $moviesArr[] = $movies;
            }
        }

        return $moviesArr;
    }

    public static function getInfoUrl($str)
    {
        $otherTitle = '/<a href="(.*)">/isU';
        if (preg_match_all($otherTitle,$str,$res)) {
            return $res[1];
        }
        return false;
    }

    public static function getMoveName($str)
    {
        $title = '/<span class=\"title\">(.*)<\/span>/isU';
        if (preg_match_all($title,$str,$res)) {
            return $res[1];
        }
        return false;
    }

    public static function getOtherName($str)
    {
        $otherTitle = '/<span class=\"other\">(.*)<\/span>/isU';
        if (preg_match_all($otherTitle,$str,$res)) {
            $result = str_replace(' ', '', str_replace('&nbsp;/&nbsp;', '', $res[1][0]));
            $nameArr = explode('/', $result);;
            return implode(',', $nameArr);
        }
        return false;
    }

    public static function getMoveDirector($str)
    {
        $actor = '/<p class=\"\">(.*)<\/p>/isU';
        if (preg_match_all($actor,$str,$res)) {
            $t1 = mb_strpos($res[1][0],'导演:')+3;
            $t2 = mb_strpos($res[1][0],'主');
            $s = mb_substr($res[1][0],$t1,$t2-$t1);
            return $s;
        }
        return false;
    }

    public static function getMovieImg($str)
    {
        $img = '/<img alt=".*?" src="(.*?)" class="">/is';
        if (preg_match_all($img,$str,$res)) {
            return $res[1][0];
        }
        return false;

    }

    public static function getMoviePoints($str)
    {
        $point = '/<span class=\"rating_num\".*>(.*)<\/span>/isU';
        if (preg_match_all($point,$str,$res)) {
            return $res[1][0];
        }

        return false;
    }

    public static function getMovieQuote($str)
    {
        $quote = '/<span class=\"inq\">(.*)<\/span>/isU';
        if (preg_match_all($quote,$str,$res)) {
            return $res[1][0];
        }
        return false;

    }

}