<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;


class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Pastikan Hanya User Yang Login Bisa Akses
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    $products = Product::when($request->keyword, function ($query) use ($request) {
        $query->where('name', 'like', "%{$request->keyword}%")
            ->orWhere('description', 'like', "%{$request->keyword}%");
    })->latest()->paginate(10);

    return view('pages.products.index', compact('products'));

    }

    /**
    * Menampilkan Form Tambah Produk
    */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('pages.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'criteria' => 'required|string',
            'favorite' => 'required|boolean',
            'status' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validatedData);

        // Simpan Gambar Kalau Ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs(
                'public/products',
                $product->id . '.' . $request->file('image')->extension()
            );
            $product->update(['image' => str_replace('public/', '', $imagePath)]);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    // Menampilkan Form Edit Produk
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('pages.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'criteria' => 'required|string',
            'favorite' => 'required|boolean',
            'status' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validatedData);

        // Kalau Ada Gambar Baru, Update Dan Hapus Gambar Lama
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $imagePath = $request->file('image')->storeAs(
                'public/products',
                $product->id . '.' . $request->file('image')->extension()
            );
            $product->update(['image' => str_replace('public/', '', $imagePath)]);
        }

        return redirect()->route('products.index')->with('success', 'Product Has Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus Gambar Kalau Ada
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product Has Successfully Deleted');

    }
}
