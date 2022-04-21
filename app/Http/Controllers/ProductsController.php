<?php

namespace App\Http\Controllers;

use App\Categories;
use App\ProductImages;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
//    function __construct()
//    {
//        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
//        $this->middleware('permission:product-create', ['only' => ['create','store']]);
//        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::leftjoin('categories as c', 'products.category_id', '=', 'c.id')->where('products.visible', '!=', '-1')
        ->select('products.*', 'c.category_name')
        ->with('productImages')->get();
        //dd($products);
        return view('products.index')->with(compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::where('parent_id', 0)->where('visible', '=', 1)->get();

        return view('products.create')->with(compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_array = $request->except('_token');

        $save_product = Products::create($data_array);

        if ($save_product)
        {
            $last_id = $save_product->id;

            $path = "uploads/product_images/";

            if(!is_dir($path))
            {
                mkdir($path, 0770, true);
            }

            if($request->hasFile('image'))
            {
                $names = [];
                foreach ($request->file('image') as $key => $image)
                {
                    $destinationPath = 'uploads/product_images/';
                    $filename = time().'-'.$image->getClientOriginalName();
                    $image->move($destinationPath, $filename);
                    $home_image = ProductImages::create(['image_path' => $filename, 'category_id' => $request->sub_category_id, 'product_id' => $last_id]);
                    /*if ($key == $request->default){
                        $home_image->update(['is_titled' => 1]);
                    }*/
                }
            }

            return redirect('/products/create')->with('success', 'Product saved successfully!');
        }else{
            return redirect('/products/create')->with('error', 'Error while saving record.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $product_images = ProductImages::where('product_id',$id)->get();
        $sub_category = Categories::where('id', $product->sub_category_id)->first();
        //dd($product_images);
        $categories = Categories::where('parent_id', 0)->where('visible', '=', 1)->get();

        return view('products.edit',compact('product','categories', 'product_images','sub_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $long_description = serialize($request->long_description);
        $short_description = serialize($request->short_description);

        $update_product = Products::where('id', $id)->update([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'regular_price' => $request->regular_price,
            'discounted_price' => $request->discounted_price,
            'quantity' => $request->quantity,
            'sku' => $request->sku,
            'publish_date' => $request->publish_date,
            'short_description' => $short_description,
            'long_description' => $long_description,
            'visible' => $request->visible
        ]);

        if ($update_product)
        {
            $product_id = $id;

            $path = "uploads/product_images/";

            if(!is_dir($path))
            {
                mkdir($path, 0770, true);
            }

            if($request->hasFile('image')) {
                $names = [];
                foreach ($request->file('image') as $key => $image) {
                    $destinationPath = 'uploads/product_images/';
                    $filename = time().'-'.$image->getClientOriginalName();
                    $image->move($destinationPath, $filename);
                    $home_image = ProductImages::create(['image_path' => $filename, 'category_id' => $request->sub_category_id, 'product_id' => $id]);
                    /*if ($key == $request->default){
                        $home_image->update(['is_titled' => 1]);
                    }*/
                }
            }

            return redirect()->back()->with('success', 'Product updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Error while updating record.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id)
    {
        $delete_product = Products::where('id', $product_id)->update([
            'visible' => '-1'
        ]);
        if($delete_product)
        {
            return 'success';
        }else{
            return 'error';
        }
    }

    public function getSubCategories($category_id)
    {
        $sub_categories = Categories::where('parent_id', $category_id)->where('visible', '=', 1)->pluck("category_name","id");
        return response()->json($sub_categories);
    }

    public function removeImage($image_id)
    {
        $image_name = ProductImages::where('id', $image_id)->first()['image_path'];
        $filePath = public_path('uploads/product_images/'.$image_name);

        if(File::exists($filePath)) {
            File::delete($filePath);

            ProductImages::where('id', $image_id)->delete();
            return 'success';
        }else{
            ProductImages::where('id', $image_id)->delete();
            return 'error';
        }
    }
}
