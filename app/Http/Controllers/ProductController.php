<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller{
    public function index(){
        $products = Product::all()->toArray();
        return response()->json(['status' => 'success', 'data' => $products], 200);
    }

    public function show($id){
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required|string|max:255',
            'image' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $product = Product::create([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'description' => $request->get('description'),
            'image' => $request->get('image'),
        ]);
        if($product){
            return response()->json(['status' => 'success', 'message' => 'Product created successfully'], 201);
        }else {
            return response()->json(['status' => 'success', 'message' => 'Product not created'], 400);
        }
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required|string|max:255',
            'image' => 'string',
        ]);
        
        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $product->name = $request->get('name');
        $product->price = $request->get('price');
        $product->description = $request->get('description');
        $product->stock = $request->get('stock');
        if($request->get('image')){
            $product->image = $request->get('image');
        }
        $product->save();
        if($product){
            return response()->json(['status' => 'success', 'message' => 'Product updated successfully'], 201);
        }else {
            return response()->json(['status' => 'success', 'message' => 'Product not updated'], 400);
        }
    }

    public function destroy($id){
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
        $product->delete();
        return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
    }
}