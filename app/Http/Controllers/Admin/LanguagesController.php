<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    //

    public function index()
    {
      $languages=Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index',compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');

    }


    public function store(LanguageRequest $request)
    {
        //validation request
        //validation in LanguageRequest

        //store Data
        try{
        Language::create($request->except(['_token']));
        return redirect()->route('admin.languages')->with(['success'=>'تم حفظ اللغة بنجاح']);
        }catch(\Exception $ex)
        {
            return redirect()->route('admin.languages')->with(['erorr'=>'هناك خطأ ما يرجى المحاولة فيما بعد ']);

        }
    }

    public function edit($id)
    {

        $language=Language::select()->find($id);
        if (!$language)
        {
            return redirect()->route('admin.languages')->with(['error'=>'هذة اللغة غير موجوودة ']);
        }
        return view('admin.languages.edit',compact('language'));
    }

    public function update($id ,LanguageRequest $request)
    {
        //validation outed of the box  LanguageRequest

        try{
                $language=Language::find($id);
                if (!$language) //cheked mogoda wla la2
                {
                    return redirect()->route('admin.languages.edit',$id)->with(['error'=>'هذة اللغة غير موجوودة ']);
                }
                if (!$request->has('active')){
                $request->request->add(['active' => 0]);
                }
                //updeted
                $language->update($request->except(['_token']));
                return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);

    }catch(\Exception $ex)
    {
        return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);

    }


    }


    public function delete($id)
    {
    try{
        $language=Language::find($id);
                if (!$language) //cheked mogoda wla la2
                {
                    return redirect()->route('admin.languages')->with(['error'=>'هذة اللغة غير موجوودة ']);
                }

             //updeted
             $language->delete();
             return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح']);


    }catch(\Exception $ex)
    {
        return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);

    }




    }
}
