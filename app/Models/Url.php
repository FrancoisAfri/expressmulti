<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
         'title', 'original_url', 'shortener_url','expires','signature','uuid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public static function getlink($id){
        $query = Url::where(['title' => $id])->first();

        if ($query == null) {
            abort(404);
        } else {
            return $query->uuid;
        }
    }


    public static function getExpired($id){
        $query = Url::where(['title' => $id])->first();

        if ($query == null) {
            abort(404);
        } else {
            return $query->expires;
        }
    }


    public static function deleteLink($id){
        return Url::where(['title' => $id])->delete();
    }
}
