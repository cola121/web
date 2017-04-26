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

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->input('name');
        var_dump($this->test($name));
        $testArr = DB::select('select * from t_note where note_id = ?', [1]);
        var_dump($testArr);
        return view('wap.index');
    }

    public function test($name)
    {
        return response()->json([
            'name' => $name
        ]);
    }
}