<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index(Request $request) {
        $warehouse = Warehouse::all();  // Correct method to retrieve all products
        return response()->json($warehouse);
    }  
    public function store(Request $request) {
        $warehouse = Warehouse::create($request->all());
        return response()->json($warehouse);
    }
    public function show($id)
    {
        // Retrieve the warehouse by its ID
        $warehouse = Warehouse::with('productsDetail')->find($id);
    
        // Check if warehouse is found
        if (!$warehouse) {
            return response()->json(['message' => 'Warehouse not found'], 404);
        }
    
        return response()->json($warehouse);
    }
    
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        // Find the warehouse by ID
        $warehouse = Warehouse::find($id);

        // Check if the warehouse exists
        if (!$warehouse) {
            return response()->json(['message' => 'warehouse not found'], 404);
        }

        // Update the warehouse fields
        $warehouse->name = $request->input('name');
        $warehouse->location = $request->input('location');
        $warehouse->save(); // Save the changes

        // Return the updated warehouse
        return response()->json($warehouse);
    }


    public function destroy($id)
{
    // Find the warehouse by ID
    $warehouse = Warehouse::find($id);

    // Check if the warehouse exists
    if (!$warehouse) {
        return response()->json(['message' => 'Warehouse not found'], 404);
    }
    $warehouse->delete();
    return response()->json(['message' => 'Warehouse deleted successfully']);
}
    }
    

