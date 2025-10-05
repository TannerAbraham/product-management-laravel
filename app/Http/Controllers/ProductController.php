<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * ==========================================================================
 * ProductController
 * ==========================================================================
 * 
 * Handles CRUD operations for products using JSON file storage
 * instead of a database. This controller supports:
 *  - Listing all products
 *  - Creating new products
 *  - Updating existing products
 *  - Deleting products
 * 
 * @author  Tanner Abraham
 * @version 1.0.0
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * ----------------------------------------------------------------------
     * @var string $dataFile
     * The JSON file used to persist product data.
     * Stored in: storage/app/products.json
     * ----------------------------------------------------------------------
     */
    private $dataFile = 'products.json';

    /**
     * ----------------------------------------------------------------------
     * Display the product management view.
     * Route: GET /
     * View: resources/views/products/index.blade.php
     * ----------------------------------------------------------------------
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * ----------------------------------------------------------------------
     * Retrieve all products as a JSON response.
     * Route: GET /products
     * 
     * @return \Illuminate\Http\JsonResponse
     * ----------------------------------------------------------------------
     */
    public function list()
    {
        $products = $this->getProducts();

        return response()->json($products);
    }

    /**
     * ----------------------------------------------------------------------
     * Store a newly created product in the JSON file.
     * Route: POST /products
     * 
     * Validates incoming data, creates a new product entry, and 
     * saves it to storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * ----------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        try {
            // --------------------------
            // Validate user input
            // --------------------------
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0'
            ]);

            // --------------------------
            // Load existing products
            // --------------------------
            $products = $this->getProducts();

            // --------------------------
            // Create new product record
            // --------------------------
            $newProduct = [
                'id' => uniqid(), // unique identifier
                'product_name' => $validated['product_name'],
                'quantity' => (int) $validated['quantity'],
                'price' => (float) $validated['price'],
                'datetime' => Carbon::now()->toIso8601String(),
                'total_value' => (int) $validated['quantity'] * (float) $validated['price'],
            ];

            // Append new product to list
            $products[] = $newProduct;

            // Persist changes to file
            $this->saveProducts($products);

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully',
                'product' => $newProduct
            ]);
        } 
        // --------------------------
        // Validation exception handling
        // --------------------------
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } 
        // --------------------------
        // General exception handling
        // --------------------------
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ----------------------------------------------------------------------
     * Update an existing product by ID.
     * Route: PUT /products/{id}
     * 
     * Validates input, updates the matching record, and saves to file.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     * ----------------------------------------------------------------------
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate updated data
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0'
            ]);

            $products = $this->getProducts();
            $updated = false;

            // Update the matching product
            foreach ($products as &$product) {
                if ($product['id'] === $id) {
                    $product['product_name'] = $validated['product_name'];
                    $product['quantity'] = (int) $validated['quantity'];
                    $product['price'] = (float) $validated['price'];
                    $product['total_value'] = $product['quantity'] * $product['price'];
                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                $this->saveProducts($products);

                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } 
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ----------------------------------------------------------------------
     * Delete a product by ID.
     * Route: DELETE /products/{id}
     * 
     * Removes the product from the JSON file.
     * 
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     * ----------------------------------------------------------------------
     */
    public function destroy($id)
    {
        $products = $this->getProducts();

        // Filter out the deleted product
        $filtered = array_filter($products, function ($product) use ($id) {
            return $product['id'] !== $id;
        });

        // Save the updated list
        $this->saveProducts(array_values($filtered));

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * ----------------------------------------------------------------------
     * Retrieve all products from the JSON storage file.
     * 
     * @return array
     * ----------------------------------------------------------------------
     */
    private function getProducts()
    {
        // If file doesn't exist, return empty list
        if (!Storage::exists($this->dataFile)) {
            return [];
        }

        // Decode JSON contents
        $json = Storage::get($this->dataFile);
        $products = json_decode($json, true) ?: [];

        // Sort by newest first (descending by datetime)
        usort($products, function ($a, $b) {
            return strtotime($b['datetime']) - strtotime($a['datetime']);
        });

        return $products;
    }

    /**
     * ----------------------------------------------------------------------
     * Save an array of products back to the JSON file.
     * 
     * @param  array  $products
     * @return void
     * ----------------------------------------------------------------------
     */
    private function saveProducts($products)
    {
        $json = json_encode($products, JSON_PRETTY_PRINT);
        Storage::put($this->dataFile, $json);
    }
}
