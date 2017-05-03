<?php
/**
 * Created by PhpStorm.
 * User: hankele
 * Date: 2017/4/26
 * Time: 13:52
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

    protected $table = 'note';

    public $timestamps = false;


    /*
     * 保存新笔记
     *
     */
    public function saveNewNote($noteInfoArr)
    {
        $this->title = $noteInfoArr['title'];
        $this->note_content = $noteInfoArr['content'];
        $this->save_time = time();
        $this->save();
    }
}