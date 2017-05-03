<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/4/13
 * Time: 9:19
 */

//$movies = getTop250Movie::getAllMovieInfo();
$movie = new getDouBanMovieInfo('https://movie.douban.com/subject/1292720/');

echo "<pre>";
print_r($movie->getMovieYear());

class getTop250Movie
{

    public static function getData($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
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
        $movies = array();
        for ($i = 0; $i <= 50; $i+=25) {
            $reqUrl = 'https://movie.douban.com/top250?start='.$i.'&filter=';
            $resData = self::getData($reqUrl);
            $m = 0;
            foreach ($resData as $row) {
                if (self::getMoveName($row)) {
                    $movieLink = self::getInfoUrl($row);
                    $info = new getDouBanMovieInfo($movieLink[0]);
                    $movies[$m] = $info->getMovieInfos();
                    $name= self::getMoveName($row);
                    $movies[$m]['c_name'] = $name[0];
                    $movies[$m]['e_name'] = str_replace('&nbsp;/&nbsp;', '', $name[1]);
                    $movies[$m]['other_name'] = self::getOtherName($row);
                    $movies[$m]['img'] = self::getMovieImg($row);
                    $movies[$m]['points'] = self::getMoviePoints($row);
                    $movies[$m]['quote'] = self::getMovieQuote($row);
                    $m++;
                }
            }
        }
        return $movies;
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

class getDouBanMovieInfo
{
    public $reqUrl;
    public $fullData;
    public $resData;

    public $cityParttern = '/<span class="pl">制片国家\/地区:<\/span>(.*)<br\/>/isU';
    public $languageParttern = '/<span class="pl">语言:<\/span>(.*)<br\/>/isU';
    public $longParttern = '/<span property="v:runtime" content="(.*)">/isU';
    public $OthNamePatternb = '/<span class="pl">又名:<\/span>(.*)<br\/>/isU';
    public $yearPattern = '/<span property="v:initialReleaseDate" content="(.*)">/isU';

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

        $this->fullData = $data;
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
        $movieInfo['summary'] = $this->getMovieSummary();
        $movieInfo['long'] = $this->getMovieInfoByParttern($this->longParttern);

        return $movieInfo;
    }

    public function getMovieSummary()
    {
        $pattern = '/<span property="v:summary".*>(.*)<\/span>/isU';

        if (preg_match_all($pattern, $this->fullData, $res)) {

            return $res[1][0];
        }

        return false;
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

    public function getMovieYear()
    {
        $pattern = '/<span property="v:initialReleaseDate" content=\".*\">(.*)<\/span>/isU';

        if (preg_match_all($pattern, $this->resData, $res)) {
            $year = substr($res[1][0], 0, 10);
            return $year;
        }
    }

    public function getMovieInfoByParttern($pattern)
    {
        if (preg_match_all($pattern, $this->resData, $res)) {
            return $res[1][0];
        }
        return false;
    }

}