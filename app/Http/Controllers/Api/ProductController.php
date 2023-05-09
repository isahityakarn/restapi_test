<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\ProductFile;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::with('productFile')->get();
        $data_array = [];
        $page = isset($request->page) ? $request->page : '';
        $page_size = isset($request->page_size) ? $request->page_size : '';
        $pagination = [];
        if ($page  && $page_size) {
            $offset = ($page - 1) * $page_size;
            for ($i = 0; $i < $page_size; $i++) {
                if (isset($product[$offset])) {
                    $data_array[] = $product[$offset];
                }
                $offset++;
            }
            $pagination['total_pages'] = ceil(count($product) / $page_size);
            $pagination['current_page'] = (int) $page;
            $pagination['total_records'] = count($product);
        } else {
            $data_array = $product;
        }
        return prepareResult(true, $product, [], 'product Data successfully Show', 200, $pagination);
    }
    public function store(ProductRequest $request)
    {

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->save();
            if ($request->product_file) {
                foreach ($request->product_file as $item) {
                    $product_file = new ProductFile();
                    $product_file->product_id = $product->id;
                    $product_file->file_name = $item["file_name"];
                    $product_file->save();
                }
            }
            DB::commit();
            $product->getSaveData();
            return prepareResult(true, $product, [], 'Product Add successfully', 200, []);
        } catch (\Throwable $th) {
            DB::rollback();
            return prepareResult(false, $th, [], '! Opps Error in Product InsertingData', 401, []);
        }
    }
    public function edit($id)
    {

        $product = Product::with('productFile')->find($id);
        $product->getSaveData();
        return prepareResult(true, $product, [], 'Product Add successfully', 200, []);
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->save();
            if ($request->product_file) {
                foreach ($request->product_file as $item) {
                    $product_file = ProductFile::find($item['id']);
                    $product_file->file_name = $item["file_name"];
                    $product_file->save();
                }
            }
            DB::commit();
            $product->getSaveData();
            return prepareResult(true, $product, [], 'Product Update successfully', 200, []);
        } catch (\Throwable $th) {
            DB::rollback();
            return prepareResult(false, $th, [], '! Opps Error in Product InsertingData', 401, []);
        }
    }
}
