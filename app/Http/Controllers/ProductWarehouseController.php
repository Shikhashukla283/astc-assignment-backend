<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductWarehouseController extends Controller
{
    public function index()
    {
        // Retrieve all products along with their associated warehouses and stock
        $products = Product::with(['warehouses' => function ($query) {
            $query->withPivot('quantity');  // Include the stock (quantity) in the pivot table
        }])->get();

        // Format the response to include product, warehouse, and stock information
        $response = $products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'warehouses' => $product->warehouses->map(function ($warehouse) {
                    return [
                        'warehouse_id' => $warehouse->id,
                        'warehouse_name' => $warehouse->name,
                        'quantity' => $warehouse->pivot->quantity,  // Stock in the warehouse
                    ];
                }),
            ];
        });

        return response()->json($response);
    }

    // Show the stock for a specific product in a warehouse
    public function show($productId, $warehouseId)
    {
        $product = Product::find($productId);
        $warehouse = Warehouse::find($warehouseId);

        if (!$product || !$warehouse) {
            return response()->json(['message' => 'Product or Warehouse not found'], 404);
        }

        $stock = $warehouse->products()->where('product_id', $productId)->first();

        if ($stock) {
            return response()->json([
                'product' => $product->name,
                'warehouse' => $warehouse->name,
                'quantity' => $stock->pivot->quantity,
            ]);
        }

        return response()->json(['message' => 'Product not found in warehouse'], 404);
    }

    // Add or update stock in a warehouse
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Find the product and warehouse by their IDs
        $product = Product::find($request->product_id);
        $warehouse = Warehouse::find($request->warehouse_id);
    
        // Check if the warehouse and product were retrieved correctly
        if (!$product || !$warehouse) {
            return response()->json(['message' => 'Product or Warehouse not found'], 404);
        }
    
        // Check if the product already exists in the warehouse
        $existingStock = $warehouse->products()->where('product_id', $request->product_id)->first();
    
        if ($existingStock) {
            // Update the quantity if the product is already in the warehouse
            $warehouse->products()->updateExistingPivot($product->id, [
                'quantity' => DB::raw('quantity + ' . $request->quantity),
            ]);
        } else {
            // Attach the product to the warehouse with the given quantity
            $warehouse->products()->attach($product->id, ['quantity' => $request->quantity]);
        }
    
        return response()->json(['message' => 'Stock added/updated successfully']);
    }
    
    // Update stock quantity in a warehouse
    public function update(Request $request, $productId, $warehouseId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $warehouse = Warehouse::find($warehouseId);
        $product = Product::find($productId);

        if (!$warehouse || !$product) {
            return response()->json(['message' => 'Product or Warehouse not found'], 404);
        }

        $warehouse->products()->updateExistingPivot($product->id, ['quantity' => $request->quantity]);

        return response()->json(['message' => 'Stock quantity updated successfully']);
    }

    // Delete stock entry from a warehouse
    public function destroy($productId, $warehouseId)
    {
        $product = Product::find($productId);
        $warehouse = Warehouse::find($warehouseId);

        if (!$product || !$warehouse) {
            return response()->json(['message' => 'Product or Warehouse not found'], 404);
        }

        $warehouse->products()->detach($productId);

        return response()->json(['message' => 'Stock entry deleted successfully']);
    }
}
