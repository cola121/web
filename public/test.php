<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/4/13
 * Time: 9:19
 */
var_dump(base64_decode('%E7%A7%91%E5%B9%BB'));
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
//var_dump($_GET['A']);
//
//function test($str) {
//    $pattern='/(.)\1/';
//    $str = preg_replace($pattern,'',$str);
//    if (preg_match($pattern, $str)) {
//        return test($str);
//    } else {
//        return $str;
//    }
//}
//$str='gaewwenngoeeojjgegop';
//var_dump(test($str));

$reqUrl = 'https://movie.douban.com/subject/1292720/';
////$reqUrl = 'https://movie.douban.com/subject/1295644/';
$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 100);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
curl_setopt($curl, CURLOPT_URL, $reqUrl);
$wxData = curl_exec($curl);
curl_close($curl);
//$pattern = '/<span property="v:summary" class="">(.*)<\/span>/isU';
$pattern = '/<div id="info">(.*)<\/div>/isU';
//$pattern = '/<a.*?<span class="title">([^<]+)/s';
preg_match_all($pattern,$wxData,$arr);
////返回匹配到的数组
//echo "<pre>";
//print_r($arr);exit;
//
//$movie = new getDouBanMovieInfo($reqUrl);
////echo"<pre>";
////print_r($movie->getMovieInfos());exit;
////$patternb = '/<a href=".*" rel="v:directedBy">(.*)<\/a>/isU';
////$patternb = '/<a href=".*" rel="v:starring">(.*)<\/a>/isU';
////$patternb = '/<span property="v:genre">(.*)<\/span>/isU';
////$patternb = '/<span class="pl">制片国家\/地区:<\/span>(.*)<br\/>/isU';
////$patternb = '/<span class="pl">语言:<\/span>(.*)<br\/>/isU';
////$patternb = '/<span class="pl">片长:<\/span>(.*)<br\/>/isU';
////$patternb = '/<span class="pl">又名:<\/span>(.*)<br\/>/isU';
$patternb = '/<span property="v:initialReleaseDate" content=\".*\">(.*)<\/span>/isU';
preg_match_all($patternb,$arr[1][0],$arrb);
echo "<pre>";
print_r($arrb);exit;
//
//
//
//for ($i=20;$i<=44;$i++) {
//    //$patternb = '/<span class=\"title\">(.*)<\/span>/isU';
//    //$patternb = '/<span class=\"other\">(.*)<\/span>/isU';
//   // $patternb = '/<img alt=".*?" src="(.*?)" class="">/is';
//    //$patternb = '/<span class=\"inq\">(.*)<\/span>/isU';
//    //$patternb = '/<span class=\"rating_num\".*>(.*)<\/span>/isU';
//    $patternb = '/<p class=\"\">(.*)<\/p>/isU';
//    if (preg_match_all($patternb,$arr[0][$i],$title)) {
//        $t1 = mb_strpos($title[1][0],'导演:')+3;
//        $t2 = mb_strpos($title[1][0],'主');
//        echo $s = mb_substr($title[1][0],$t1,$t2-$t1);
////        echo "<pre>";
////        print_r($title[1]);
//    }
////    $t1 = mb_strpos($str,'提');
////    $t2 = mb_strpos($str,'串');
////    echo $s = mb_substr($str,$t1,$t2-$t1);
//
//}

class getTop250Movie
{
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
            return $res[1];
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

class getDouBanMovieInfo
{
    public $reqUrl;
    public $resData;

    public $cityParttern = '/<span class="pl">制片国家\/地区:<\/span>(.*)<br\/>/isU';
    public $languageParttern = '/<span class="pl">语言:<\/span>(.*)<br\/>/isU';
    public $longParttern = '/<span property="v:runtime" content="(.*)">/isU';
    public $OthNamePatternb = '/<span class="pl">又名:<\/span>(.*)<br\/>/isU';

    public function __construct($reqUrl)
    {
        $this->reqUrl = $reqUrl;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $reqUrl);
        $data = curl_exec($curl);
        curl_close($curl);

        $pattern = '/<div id="info">(.*)<\/div>/isU';
        preg_match_all($pattern, $data, $res);
        $this->resData = $res[1][0];
    }


    public function getMovieInfos()
    {
        $movieInfo = array();
        $movieInfo['director'] = $this->getMovieDirector();
        $movieInfo['writer'] = $this->getMovieWriter();
        $movieInfo['starts'] = $this->getMovieStarts();
        $movieInfo['tags'] = $this->getMovieTags();
        $movieInfo['city'] = $this->getMovieCitys();
        $movieInfo['language'] = $this->getMovieLanguages();
        $movieInfo['long'] = $this->getMovieInfoByParttern($this->longParttern);

        return $movieInfo;
    }

    public function getMovieDirector()
    {
        $pattern = '/<a href=".*" rel="v:directedBy">(.*)<\/a>/isU';

        if (preg_match_all($pattern, $this->resData, $res)) {
            return $res[1][0];
        }

        return false;
    }

    public function getMovieWriter()
    {
        $writerArr = array();
        $pattern = '/<span class=\'pl\'>编剧<\/span>\: <span class=\'attrs\'>(.*)<\/span>/isU';
        preg_match_all($pattern, $this->resData, $res);
        $writers = explode(' / ',$res[1][0]);

        foreach ($writers as $row) {
            preg_match('/<a href=".*">(.*)<\/a>/', $row, $result);
            $writerArr[] = $result[1];
        }

        return implode(',', $writerArr);
    }

    public function getMovieStarts()
    {
        $pattern = '/<a href=".*" rel="v:starring">(.*)<\/a>/isU';

        if (preg_match_all($pattern, $this->resData, $res)) {
            return implode(',', $res[1]);
        }

         return false;
    }

    public function getMovieTags()
    {
        $pattern = '/<span property="v:genre">(.*)<\/span>/isU';
        if (preg_match_all($pattern, $this->resData, $res)) {
            return implode(',', $res[1]);
        }

        return false;
    }

    public function getMovieCitys()
    {
        $pattern = '/<span class="pl">制片国家\/地区:<\/span>(.*)<br\/>/isU';
        if (preg_match_all($pattern, $this->resData, $res)) {
            $citys = str_replace(" ", "", $res[1][0]);
            $cityArr = explode('/', $citys);
            return implode(',', $cityArr);
        }

        return false;
    }

    public function getMovieLanguages()
    {
        $pattern = '/<span class="pl">语言:<\/span>(.*)<br\/>/isU';
        if (preg_match_all($pattern, $this->resData, $res)) {
            $citys = str_replace(" ", "", $res[1][0]);
            $cityArr = explode('/', $citys);
            return implode(',', $cityArr);
        }

        return false;
    }

    public function getMovieInfoByParttern($pattern)
    {
        if (preg_match_all($pattern, $this->resData, $res)) {
            return $res[1][0];
        }
        return false;
    }

//$patternb = '/<a href=".*" rel="v:directedBy">(.*)<\/a>/isU';
//$patternb = '/<a href=".*" rel="v:starring">(.*)<\/a>/isU';
//$patternb = '/<span property="v:genre">(.*)<\/span>/isU';

//$patternb = '/<span property="v:initialReleaseDate" content=\".*\">(.*)<\/span>/isU';
//$patternb = '/<span class=\'pl\'>编剧<\/span>\: <span class=\'attrs\'><a href=".*">(.*)<\/a>/isU';


  //  preg_match_all($patternb,$arr[1][0],$arrb);
}