<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Libraries\General;
use App\Models\Admin\Settings;
use App\Models\Admin\Products;
use App\Models\Admin\School;
use App\Models\Admin\ProductCategories;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SchoolController extends BaseController
{

    public function index(Request $request, $slug = null)
    {
        $title = 'All Schools';
        $schools = School::select(['id', 'name', 'logo'])->where('status', 1)->where('website_visible', 1);
        if($slug == 'junior')
        {
            $title = 'Junior Schools';
            $schools->where('schooltype', 'LIKE', 'Junior');
        }
        else if($slug == 'senior')
        {
            $title = 'Senior Schools';
            $schools->where('schooltype', 'LIKE', 'Senior');
        }
        else
        {
            $title = 'School Name By "'.$slug.'"';
            $schools->where('name', 'LIKE', $slug . '%');
        }
        $schools = $schools->orderBy('name', 'asc')->get();
        return view('frontend.school.index', [
            'schools' => $schools,
            'title' => $title
        ]);
    }

    public function schoolByName(Request $request)
    {
        return view('frontend.school.selection', [

        ]);
    }

    public function uniforms(Request $request, $slug)
    {
        $split = explode('-', $slug);
        $id  = $split[count($split)-1];
        $school = School::where('website_visible', 1)->where('id', $id)->where('status', 1)->find($id);
        if($school)
        {
            $categories = ProductCategories::select(['title', 'slug'])->where('status', 1)->orderBy('title', 'asc')->get();
            return view('frontend.school.uniforms', [
                'school' => $school,
                'schoolId' => $id,
                'categories' => $categories
            ]);
        }
        else
        {
            abort('404');
        }
    }
}