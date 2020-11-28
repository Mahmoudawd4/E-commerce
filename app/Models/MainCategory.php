<?php

namespace App\Models;

use App\Models\SubCategory;
use App\Observers\MainCategoryObserver;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    //

    protected $table='main_categories';
    protected $fillable = [
        'id ', 'translation_lang','translation_of','name', 'slug','image','active','created_at','updated_at'
    ];


    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'translation_lang', 'name', 'slug', 'image', 'active', 'translation_of');
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }


    //image path define
    public function getImageAttribute($val)
    {
        return ($val !== null) ? asset('public/assets/' . $val) : "";
    }

    public function scopeDefaultCategory($query){
        return  $query -> where('translation_of',0);
    }


    // get all translation categories
    public function categories()
    {
        return $this->hasMany(self::class, 'translation_of');
    }


    // public  function subCategories(){
    //     return $this -> hasMany(SubCategory::class,'category_id','id');
    // }


    public function vendors()
    {
        return $this->hasMany('App\Models\Vendor','category_id','id');
    }


    //tareket rabt modell bell observe
    protected static function boot()
    {
        parent::boot();
        MainCategory::observe(MainCategoryObserver::class);
    }


public  function subCategories(){
        return $this -> hasMany(SubCategory::class,'category_id','id');
    }

}
