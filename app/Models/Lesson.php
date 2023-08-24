<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;

    protected $casts = [
        'video'=>'json',
    ];

    // This function is used to set video. this get called when you submit data in the database
    public function setVideoAttribute($value){
        
        // this will stop functionality
        // dump($value);
        // exit();

        $newVideo = [];
        foreach($value as $k=>$v){
            $valueVideo = [];
            if(!empty($v['old_url'])){
                $valueVideo['url'] = $v['old_url'];
            }else{
                $valueVideo['url']=$v['url'];
            }

            if (!empty($v["old_thumbnail"])) {
                $valueVideo["thumbnail"] = $v["old_thumbnail"];
            } else {
                $valueVideo["thumbnail"] = $v["thumbnail"];
            }
            $valueVideo['name']=$v['name'];
            array_push($newVideo,$valueVideo);
        }

        //json_encode makes it json for the database
        //array_values get the values of the php associative array
        $this->attributes['video'] = json_encode(array_values($newVideo));
    }

    // This function is used to get the video.
    public function getVideoAttribute($value){
        //conver to associative array, its like this:
        /*
            "key"=>"value",
        */
        $result = json_decode($value, true);
        if(!empty($result)){
            foreach($result as $key => $value){
                $result[$key]['url'] = env('APP_URL')."uploads/".$value['url'];
                $result[$key]['thumbnail'] = env('APP_URL') . "uploads/" . $value['thumbnail'];
            }
            // dd($result);
        }
        return $result;
    }

    // This function is used to get the thumbnail.
    public function getThumbnailAttribute($value){
        return env('APP_URL')."uploads/".$value;
    }
}
