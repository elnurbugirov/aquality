<?php

namespace App\Http\Controllers;

use App\Http\Resources\Photo;
use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class ProductController extends Controller
{


    public function index()
    {
        $product = Product::find(1);
        $images = $product->images;
        return response()->json($images);
    }

    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return response()->json($product);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'price' => 'int|max:6|',
            'quantity' => 'int|min:6|',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        $product = new Product();
        $product->user_id = $request->user_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        $image = new Image();
        $image->product_id = $request->product_id;
        $base64_image = $request->image;

        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $data = base64_decode($data);
            $name = md5(microtime()) . '.png';
            Storage::disk('public')->put($name, $data);
            $image->image = $name;
        }
        $product->save();
        $product->images()->save($image);
        return response()->json($product);
    }
    public function update(Request $request, $id)
    {

        $product = Product::findOrFail(1);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $id . ' cannot be found'
            ], 400);
        }

        $inp = $request->all();
        $updated = $product->fill($inp)->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Sorry, product could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Sorry, product with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($product->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Product could not be deleted'
            ], 500);
        }
    }

}
