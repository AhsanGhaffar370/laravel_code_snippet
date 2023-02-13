<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable= [
        'title',
        'image',
        'category_id',
        'description',
        'status',
    ];
    
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }
    
}
