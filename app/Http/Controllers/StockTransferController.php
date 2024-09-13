<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function transferStock(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        // Start the transaction
        DB::transaction(function () use ($validated) {
            $fromWarehouse = Warehouse::find($validated['from_warehouse_id']);
            $toWarehouse = Warehouse::find($validated['to_warehouse_id']);
            $product = Product::find($validated['product_id']);

            // Check if the product exists in the "from warehouse"
            $fromProductWarehouse = $fromWarehouse->products()->where('product_id', $product->id)->first();
            if (!$fromProductWarehouse) {
                throw new \Exception('Product not found in the from warehouse');
            }

            $fromStock = $fromProductWarehouse->pivot->quantity;

            if ($fromStock >= $validated['quantity']) {
                // Deduct stock from the fromWarehouse
                $fromWarehouse->products()->updateExistingPivot($product->id, [
                    'quantity' => DB::raw('quantity - ' . $validated['quantity'])
                ]);

                // Check if the product already exists in the toWarehouse
                $toProductWarehouse = $toWarehouse->products()->where('product_id', $product->id)->first();
                
                if ($toProductWarehouse) {
                    // Add stock to the existing product in the toWarehouse
                    $toWarehouse->products()->updateExistingPivot($product->id, [
                        'quantity' => DB::raw('quantity + ' . $validated['quantity'])
                    ]);
                } else {
                    // If the product does not exist in the toWarehouse, attach it
                    $toWarehouse->products()->attach($product->id, [
                        'quantity' => $validated['quantity']
                    ]);
                }

            } else {
                throw new \Exception('Not enough stock to transfer');
            }
        });

        return response()->json(['message' => 'Stock transferred successfully!']);
    }
}
