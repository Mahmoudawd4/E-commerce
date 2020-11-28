<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;

class MainCategoriesController extends Controller
{

    public function index()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)
            ->selection()
            ->get();

        return view('admin.maincategories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }

    public function store(MainCategoryRequest $request)
    {
        //validation

        try {
            //return $request;
        //cllect le array ely el2sm bta3o  categry ...bmsek category
            $main_categories = collect($request->category);
        //filter main category 2la 7sb language ely rag3a
            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });
        //basheal raqm 1 mn array
            $default_category = array_values($filter->all()) [0];


            $filePath = "";
            //low fe uploude file image
            if ($request->has('image')) { //folder ,path file mn request
                $filePath = uploadImage('maincategories', $request->image);
            }



            //mn hen low fe ay expextion error in line  DB::beginTransaction() &&  DB::commi() btrga3 tany mn 2lol le8ayt mtsave
            DB::beginTransaction();
            //code here transaction in DB




            //store data insertGetId function bdall create
            //هيضيف الداتا اول مرة من لغى الdefult
            //insert wy get id ma3a
            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'image' => $filePath
            ]);
            //translation_of علشان اضيف الداتا تانى للى
            // لو اللغة مش عربى يعنى مش defult
            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });


            //low fee data in  categories
            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                //علشان ال performance
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'image' => $filePath
                    ];
                }
                //store in data base
                MainCategory::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function edit($id)
    {

       //get specific categories and its translations
       $mainCategory = MainCategory::with('categories')
       ->selection()
       ->find($id);

        if(! $mainCategory)
        {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
        return view('admin.maincategories.edit' ,compact('mainCategory'));
    }

    public function update($id ,MainCategoryRequest $request)
    {


        try {
            $main_category = MainCategory::find($id);

            if (!$main_category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            // update date
            //brga3 elarray le 0 array
            $category = array_values($request->category) [0];



            //low el active mesh gayaa khaly el active be 0 else active be 1
            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            MainCategory::where('id', $id)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);

            // save image

            if ($request->has('image')) {
                $filePath = uploadImage('maincategories', $request->image);
                MainCategory::where('id', $id)
                    ->update([
                        'image' => $filePath,
                    ]);
            }


            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function delete($id)
    {

        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            $vendors = $maincategory->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->route('admin.maincategories')->with(['error' => 'لأ يمكن حذف هذا القسم  ']);
            }

            //htakta3 localhost/ecomerce/public ay ma ba3d public
            $image = Str::after($maincategory->image, 'public/');

            $image = base_path('public/' . $image);
            unlink($image); //delete from folder

            $maincategory->categories()->delete();
            $maincategory->delete(); //delete culomn
            return redirect()->route('admin.maincategories')->with(['success' => 'تم حذف القسم بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function changeStatus($id)
    {

            try {
                $maincategory = MainCategory::find($id);
                if (!$maincategory)
                    return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

               $status =  $maincategory ->active == 0 ? 1 : 0;

               $maincategory -> update(['active' =>$status ]);

                return redirect()->route('admin.maincategories')->with(['success' => ' تم تغيير الحالة بنجاح ']);

            } catch (\Exception $ex) {
                return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
            }

    }


}
