<?php

namespace App\Models;

use App\Models\MainCategory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table='sub_categories';
    protected $fillable = [
        'id ', 'translation_lang','parent_id','translation_of','name', 'slug','image','active','created_at','updated_at'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id','parent_id','translation_lang', 'name', 'slug', 'photo', 'active', 'translation_of');
    }

    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    //get main category of subcategory
    public  function mainCategory(){
        return $this ->belongsTo(MainCategory::class,'category_id','id');
    }
}
