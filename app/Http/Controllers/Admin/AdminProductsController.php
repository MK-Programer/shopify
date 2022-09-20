<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminProductsController extends Controller
{
    // display all products
    public function index(){
        $products = Product::paginate(3);
        return view('admin.displayProducts', ['products'=>$products]);
    }

    // display edit product form
    public function displayEditProductForm($id){
        $product = Product::find($id);
        return view('admin.editProductForm', ['product'=>$product]);
    }

    // display edit product image form 
    public function displayEditProductImageForm($id){
        $product = Product::find($id);
        return view('admin.editProductImageForm', ['product'=>$product]);
    }

    // update product image
    public function updateProductImage(Request $request, $id){
        // check if it is an image and check its size
        Validator::make($request->all(), ['image'=>'required|image|mimes:jpg,jpeg|max:5000'])->validate();
        if($request->hasFile('image')){
            $product = Product::find($id);
            $exists = Storage::disk('local')->exists('public/images/products/'.$product->image);
            // delete the old image if exists then add the new one
            if($exists){
                Storage::delete('public/images/products/'.$product->image);
            } 
            // upload the new one
            // return the extension of the file
           $ext = $request->file('image')->getClientOriginalExtension();
            // add the old image name as the new image one
            $request->image->storeAs('public/images/products/', $product->image);
            // $arrayToUpdate = array('image'=>$product->image);
            // DB::table('products')->where('id', $id)->update($arrayToUpdate);
            return redirect()->route('adminDisplayProducts');
        }else{
            return 'No Image was selected';
        }
    }

    public function updateProductForm(Request $request, $id){
        $name = $request->input('name');
        $description = $request->input('description');
        $type = $request->input('type');
        $price = str_replace('$', '',$request->input('price'));
        $arrayToUpdate = array('name'=>$name, 'description'=>$description, 'type'=>$type, 'price'=>$price);
        DB::table('products')->where('id', $id)->update($arrayToUpdate);
        return redirect()->route('adminDisplayProducts');
    }

    public function displayAddProduct(){
        return view('admin.addProductForm');
    }

    // send product to db
    public function addProduct(Request $request){
        $name = $request->input('name'); // white skirt
        $description = $request->input('description');
        $type = $request->input('type');
        $price = str_replace('$', '',$request->input('price'));
        // check if it is an image and check its size
        Validator::make($request->all(), ['image'=>'required|image|mimes:jpg,jpeg,png|max:5000'])->validate();
        $ext = $request->file('image')->getClientOriginalExtension();
        $stringImageReFormat = str_replace(' ', '', $name);
        $imageName = $stringImageReFormat.".".$ext; 
        // encode the image first
        $imageEncoded = File::get($request->image);
        Storage::disk('local')->put('public/images/products/'.$imageName, $imageEncoded);
        $newProductArray = array('name'=>$name, 'description'=>$description, 'image'=>$imageName,'type'=>$type, 'price'=>$price);
        $created = DB::table('products')->insert($newProductArray);
        if($created){
            return redirect()->route('adminDisplayProducts');
        }else{
            return 'Product was not created';
        }
    }

    public function deleteProduct($id){
        $product = Product::find($id);
        // delete this product image from the server
        $exists = Storage::disk('local')->exists('public/images/products/'.$product->image);
        if($exists){
            Storage::delete('public/images/products/'.$product->image);
        } 
        Product::destroy($id); // delete the record from the db
        return redirect()->route('adminDisplayProducts'); 
    }
    
}
