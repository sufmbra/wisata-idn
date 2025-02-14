<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Proteksi pakai token
    }

    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
            'status' => 'in:draft,published,archived',
            'criteria' => 'in:perorangan,rombongan',
            'favorite' => 'boolean',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|integer|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|string',
            'status' => 'in:draft,published,archived',
            'criteria' => 'in:perorangan,rombongan',
            'favorite' => 'boolean',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
}
}
