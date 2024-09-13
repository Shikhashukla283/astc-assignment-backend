<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request) {
        $supplier = Supplier::all();  // Correct method to retrieve all products
        return response()->json($supplier);
    }  
    public function store(Request $request) {
        $supplier = Supplier::create($request->all());
        return response()->json($supplier);
    }
    public function show($id)
    {
        // Retrieve the supplier by its ID
        $supplier = Supplier::find($id);
    
        // Check if supplier is found
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
    
        return response()->json($supplier);
    }
    
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
        ]);

        // Find the supplier by ID
        $supplier = Supplier::find($id);

        // Check if the supplier exists
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        // Update the supplier fields
        $supplier->name = $request->input('name');
        $supplier->contact_info = $request->input('contact_info');
        $supplier->save(); // Save the changes

        // Return the updated supplier
        return response()->json($supplier);
    }


    public function destroy($id)
{
    // Find the supplier by ID
    $supplier = Supplier::find($id);

    // Check if the supplier exists
    if (!$supplier) {
        return response()->json(['message' => 'Supplier not found'], 404);
    }
    $supplier->delete();
    return response()->json(['message' => 'Supplier deleted successfully']);
}

}
