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

        $movies = Movie::all();
        foreach ($movies as $row) {
            echo '<li><p>'.$row['c_name'].'</p>
                <img src="'.$row['image'].'" width="30">
                </li>';
        }





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

    public function test($name)
    {
        return response()->json([
            'name' => $name
        ]);
    }
}