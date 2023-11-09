<?php


namespace Module\Blog\Model;


use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $table = 'blog_comment';

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blogId', 'id');
    }
}
