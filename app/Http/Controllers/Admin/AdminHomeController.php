<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use Illuminate\Support\Facades\Storage;

class AdminHomeController extends Controller
{
    public function index()
    {
        if(request('category')){
            $data = Category::with('product')->where('category', request('category'))->firstOrFail()->product;
        } else {
            $data = Product::where('isDeleted', 0)->paginate(3);
        }
        return view('admin.home', compact('data'));
    }

    public function create()
    {
        $data = Category::all();
        return view('admin.create', compact('data'));
    }

    public function store(Request $request)
    {
        // validating the request
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:1',
            'description' => ['required', function($attribute, $value, $fail){
                                if($value == '<p><br></p>'){
                                    $fail('The :attribute field is empty.');
                                }
                            }],
            'stock' => 'required|numeric|min:1',
            'image' => 'required|image'
        ]);

        // mass assignment to the product table
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'user_id' => auth()->user()->id,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        //naming and storing image if any
        if($request->image){
            $imageName = $product->id.'ASC.'.$request->image->getClientOriginalExtension();
            $path = Storage::disk('product_image')->putFileAs('', $request->image, $imageName);
            // $path = $request->image->storeAs('', $imageName, 'product_image');
            $product->update(['image' => $path]);
        }

        //assigning product category if any
        if($request->category){
            $product->category()->attach(json_decode($request->category));
        }
        
        //returning next route
        return route('admin.home');
    }

    public function edit(Product $product)
    {
        $productCategory = $product->category;
        $category = Category::all();
        return compact('product', 'category', 'productCategory');
    }

    public function update(Request $request, Product $product)
    {
        //validating update form
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:1',
            'description' => ['required', function($attribute, $value, $fail){
                                if($value == '<p><br></p>'){
                                    $fail('The :attribute field is empty.');
                                }
                            }],
            'stock' => 'required|numeric|min:1'
        ]);

        //updating data
        $product->update($validated);

        //updating category if any
        if($request->category){
            $product->category()->sync(json_decode($request->category));
        }
        //updating image if any
        if($request->image){
            $path = Storage::disk('product_image')->putFileAs('', $request->image, $product->image);
            $product->update(['image' => $path]);
        }

        return route('admin.home');
    }

    public function destroy(Product $product)
    {
        Storage::disk('product_image')->delete($product->image);
        $product->delete();
        return route('admin.home');
    }
}
