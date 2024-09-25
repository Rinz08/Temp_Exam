<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $perPage = $request->input('per_page', 10); 

        $query = Product::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        }

        return response()->json(['message' => 'Product not found'], 404);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'date_time' => 'required|date',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->category = $request->category;
        $product->description = $request->description;
        $product->date_time = $request->date_time;

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $filename = $image->store('images', 'public');
                $images[] = $filename;
            }
            $product->image_name = json_encode($images);
        }

        $product->save();

        return response()->json(['message' => 'Product updated successfully']);
    }
}
