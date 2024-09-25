<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Fetch query parameters for filtering
        $search = $request->input('search');
        $category = $request->input('category');
        $perPage = $request->input('per_page', 10); 

        // Query the products
        $query = Product::query();

        // Apply search by name or description
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }

        // Paginate the results
        $products = $query->paginate($perPage);

        // Return the paginated results as JSON
        return response()->json($products);
    }

    public function destroy($id)
    {
        // Find and delete the product
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        }

        return response()->json(['message' => 'Product not found'], 404);
    }
}
