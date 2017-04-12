<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/4/12
 * Time: 9:57
 */
namespace App\Http\Controllers\Wap;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('wap.index');
    }
}