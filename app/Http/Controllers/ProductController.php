<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::all();  // Correct method to retrieve all products
        return response()->json($products);
    }
    
    public function store(Request $request) {
        $products = Product::create($request->all());
        return response()->json($products);
    }
    
    public function show($id)
    {
        // Retrieve the products by its ID
        $products = Product::find($id);
    
        // Check if products is found
        if (!$products) {
            return response()->json(['message' => 'products not found'], 404);
        }
    
        return response()->json($products);
    }
    
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'quantity' => 'required|integer|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        // Find the products by ID
        $products = Product::find($id);

        // Check if the products exists
        if (!$products) {
            return response()->json(['message' => 'products not found'], 404);
        }

        // Update the products fields
        $products->name = $request->input('name');
        $products->sku = $request->input('sku');
        $products->quantity = $request->input('quantity');
        $products->supplier_id = $request->input('supplier_id');
        $products->save(); // Save the changes

        // Return the updated products
        return response()->json($products);
    }


    public function destroy($id)
{
    $products = Product::find($id);
    if (!$products) {
        return response()->json(['message' => 'products not found'], 404);
    }
    $products->delete();
    return response()->json(['message' => 'products deleted successfully']);
}

// public function transferStock(Request $request) {
//     $product = Product::find($request->product_id);
//     $fromWarehouse = Warehouse::find($request->from_warehouse_id);
//     $toWarehouse = Warehouse::find($request->to_warehouse_id);

//     if ($product && $fromWarehouse && $toWarehouse) {
//         $product->warehouses()->detach($fromWarehouse);
//         $product->warehouses()->attach($toWarehouse);
//         // Update stock levels accordingly
//     }
// }


}
