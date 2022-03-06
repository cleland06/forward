<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Evidence extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'category_id',
        'description',
        'user_id',
    ];

    //relacion con la categoria
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    

    //relacion con las imagenes
    public function images()
    {
        return $this->hasMany('App\Models\EvidenceImages', 'evidence_id', 'id');
    }

    public function getDateAttribute()
{
    return \Carbon\Carbon::create($this->created_at)->format('d-m-Y');
}
}
