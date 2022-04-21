<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Categories::all();

        return view('categories.create')->with(compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $save_category = Categories::create([
            'category_name'=>$request->category_name,
            'parent_id'=>$request->parent_id,
            'visible'=>$request->category_visible
        ]);

        if ($save_category)
        {
            return "success";
        }else{
            return "error";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Categories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $all_categories = Categories::all();
        $category = Categories::where('id', $id)->first();
        $option_category = Categories::where('id', $category->parent_id)->first();

        //return json_encode($category);
        return view('categories.edit')->with(compact('category', 'all_categories', 'option_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categories $categories, $id)
    {
        $update_category = Categories::where('id', $id)->update([
            'category_name' => $request->edit_category_name,
            'parent_id' => $request->edit_parent_category_id,
            'visible' => $request->edit_category_visible
        ]);

        if($update_category)
        {
            return "success";
        }else{
            return "error";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $catId = $request->catId;
 
        $deleteCategory = Categories::where('id', $catId)->delete();

        if(!empty($deleteCategory)){
            return "success";
        }else{
            return "error";
        }
    }
}
