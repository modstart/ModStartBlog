<?php


namespace Module\Blog\Model;


use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog';


    public static function published()
    {
        return self::where([
            'isPublished' => true,
        ]);
    }

    public static function unPublished()
    {
        return self::where([
            'isPublished' => false,
        ]);
    }
}
