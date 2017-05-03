<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/5/3
 * Time: 14:37
 */
namespace App\Library\Classes;


class getDouBanMovieInfo
{
    public $reqUrl;
    public $fullData;
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
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $reqUrl);
        $data = curl_exec($curl);
        curl_close($curl);

        $this->fullData = $data;
        $pattern = '/<div id="info">(.*)<\/div>/isU';

        if (preg_match_all($pattern, $data, $res)) {
            $this->resData = $res[1][0];
        }

    }


    public function getMovieInfos()
    {
        $movieInfo = array();
        $movieInfo['director'] = $this->getMovieDirector();
        $movieInfo['writer'] = $this->getMovieWriter();
        $movieInfo['stars'] = $this->getMovieStarts();
        $movieInfo['tags'] = $this->getMovieTags();
        $movieInfo['city'] = $this->getMovieCitys();
        $movieInfo['language'] = $this->getMovieLanguages();
        $movieInfo['summary'] = $this->getMovieSummary();
        $movieInfo['long'] = $this->getMovieInfoByParttern($this->longParttern);
        $movieInfo['year'] = $this->getMovieYear();

        return $movieInfo;
    }

    public function getMovieSummary()
    {
        $pattern = '/<span property="v:summary".*>(.*)<\/span>/isU';

        if (preg_match_all($pattern, $this->fullData, $res)) {

            return $res[1][0];
        }

        return "";
    }

    public function getMovieDirector()
    {
        $pattern = '/<a href=".*" rel="v:directedBy">(.*)<\/a>/isU';

        if (preg_match_all($pattern, $this->resData, $res)) {
            return $res[1][0];
        }

        return "";
    }

    public function getMovieWriter()
    {
        $writerArr = array();
        $writers = array();
        $pattern = '/<span class=\'pl\'>编剧<\/span>\: <span class=\'attrs\'>(.*)<\/span>/isU';
        if (preg_match_all($pattern, $this->resData, $res)) {
            $writers = explode(' / ',$res[1][0]);
        }

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

        return "";
    }

    public function getMovieTags()
    {
        $pattern = '/<span property="v:genre">(.*)<\/span>/isU';
        if (preg_match_all($pattern, $this->resData, $res)) {
            return implode(',', $res[1]);
        }

        return "";
    }

    public function getMovieCitys()
    {
        $pattern = '/<span class="pl">制片国家\/地区:<\/span>(.*)<br\/>/isU';
        if (preg_match_all($pattern, $this->resData, $res)) {
            $citys = str_replace(" ", "", $res[1][0]);
            $cityArr = explode('/', $citys);
            return implode(',', $cityArr);
        }

        return "";
    }

    public function getMovieLanguages()
    {
        $pattern = '/<span class="pl">语言:<\/span>(.*)<br\/>/isU';
        if (preg_match_all($pattern, $this->resData, $res)) {
            $citys = str_replace(" ", "", $res[1][0]);
            $cityArr = explode('/', $citys);
            return implode(',', $cityArr);
        }

        return "";
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
        return "";
    }

}