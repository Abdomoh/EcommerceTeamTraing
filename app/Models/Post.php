<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    
protected $casts =[
    'created_at'=> 'date:Y-M-D',
    'updated_at'=> 'date:Y-M-D'
];
    protected $fillable = ['title', 'body', 'image', 'user_id'];

    protected $appends = [
        'title_ar', 'body_ar', 'slug', 'image_full_path',
    ];

    public function getTitleArAttribute()
    {
        $translation = Translation::where('model', 'Post')
            ->where('row_id', $this->attributes['id'])
            ->where('field', 'title')
            ->first();

        return $translation ? $translation->value : null;
    }

    public function getBodyArAttribute()
    {
        $translation = Translation::where('model', 'Post')
            ->where('row_id', $this->attributes['id'])
            ->where('field', 'body')
            ->first();

        return $translation ? $translation->value : null;
    }

    public function getSlugAttribute()
    {
        $slug = Slug::where('model', 'Post')
            ->where('row_id', $this->attributes['id'])
            ->first();

        return $slug ? $slug->value : null;
    }


    public function getImageFullPathAttribute()
    {
        return $this->image ? ('localhost:8000') . '/uploads/posts/' . $this->image : null;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
