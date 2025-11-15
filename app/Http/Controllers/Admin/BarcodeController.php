<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Milon\Barcode\DNS1D;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
use Exception;

class BarcodeController extends Controller
{
    public function index()
    {
        try {
            return view('admin.barcode.index');
        } catch (Exception $e) {
            Log::error('Failed to load barcode index page: ' . $e);
            return redirect()->back()->with('error', 'Could not load the page.');
        }
    }

    // AJAX method to search for products
    public function search(Request $request)
    {
        try {
            $term = $request->get('term');
            $products = Product::where('name', 'LIKE', "%{$term}%")
                               ->orWhere('sku', 'LIKE', "%{$term}%")
                               ->limit(10)
                               ->get(['id', 'name', 'sku', 'selling_price']);
            return response()->json($products);
        } catch (Exception $e) {
            Log::error('Failed to search products for barcode generation: ' . $e);
            return response()->json(['error' => 'Failed to search for products.'], 500);
        }
    }

      // This method now ONLY generates HTML for previewing and printing
    public function print(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'paper_size' => 'required|string',
        ]);

        try {
            $productsData = [];
            $barcodeGenerator = new DNS1D();

            foreach ($request->products as $productData) {
                $product = Product::find($productData['id']);
                if ($product) {
                    for ($i = 0; $i < $productData['qty']; $i++) {
                        $productsData[] = [
                            'name' => $product->name,
                            'price' => $product->offer_price ?? $product->selling_price,
                            'code' => $product->sku,
                            'barcode_html' => $barcodeGenerator->getBarcodeHTML($product->sku, 'C128', 1, 25)
                        ];
                    }
                }
            }
            
            $options = [
                'show_store_name' => $request->boolean('show_store_name'),
                'show_product_name' => $request->boolean('show_product_name'),
                'show_price' => $request->boolean('show_price'),
                'show_border' => $request->boolean('show_border'),
                'paper_size' => $request->paper_size,
                // Add custom dimensions to the options array
                'paper_width' => $request->paper_width,
                'paper_height' => $request->paper_height,
            ];

            $html = view('admin.barcode.print_preview', [
                'products' => $productsData,
                'options' => $options,
            ])->render();

            return response($html);
        } catch (Exception $e) {
            Log::error('Failed to generate barcode print preview: ' . $e);
            return response('An error occurred while generating the print preview. Please check the logs.', 500);
        }
    }
}