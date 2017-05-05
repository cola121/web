<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/4/12
 * Time: 9:57
 */
namespace App\Http\Controllers\Wap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Library\Classes\getTop250Movie;
use App\Models\Note;
use App\Models\Movie;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $num = rand(1,10);
        $movies = Movie::where('id', '>', 47+$num*15)
                    ->take(15)
                    ->get();

        //$movie['name'] = $movies->c_name;
        $i = 0;
        foreach ($movies as $row) {
            $movie[$i]['id'] = $row->id;
            $movie[$i]['name'] = $row->c_name;
            $movie[$i]['image'] = $row->image;
            $movie[$i]['director'] = $row->director;
            $movie[$i]['star'] = $row->star;
            $movie[$i]['quote'] = $row->quote;
            $movie[$i]['year'] = $row->m_year;
            $movie[$i]['tag'] = $row->tag;
            $points = explode('.', $row->points);
            $movie[$i]['pointsa'] = intval($points[0]);
            $movie[$i]['pointsb'] = count($points) > 1 ? intval($points[1]) : 0;
            $i++;
        }
        //$movie[]['image'] = $movies->image;

        $result['data'] = $movie;

        return response()->json([
            'data' => $movie
        ]);


//        $movies = getTop250Movie::getAllMovieInfo();
//        foreach ($movies as $row) {
//            $movie = new Movie();
//            $movie->c_name = $row['c_name'];
//            $movie->e_name = $row['e_name'];
//            $movie->other_name = $row['other_name'];
//            $movie->summary = $row['summary'];
//            $movie->tag = $row['tags'];
//            $movie->director = $row['director'];
//            $movie->star = $row['stars'];
//            $movie->writer = $row['writer'];
//            $movie->image = $row['img'];
//            $movie->points = $row['points'];
//            $movie->quote = $row['quote'];
//            $movie->m_language = $row['language'];
//            $movie->city = $row['city'];
//            $movie->m_year = $row['year'] ? $row['year'] : '';
//            $movie->m_long = $row['long'];
//            $movie->created_at = time();
//            $movie->updated_at = time();
//            $movie->save();
//        }
//        echo 'done';
    }

    public function getMovieInfo(Request $request, $id)
    {

        $movie = Movie::find(intval($id));

        return response()->json([
            'data' => $movie
        ]);

    }
}