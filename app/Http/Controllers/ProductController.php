<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator; //panggil fungsi untuk validasi inputan
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request)
    {

        // validasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'type' => 'required|in:makanan,minuman,makeup',
            'expired_at' => 'required|date'
        ]);

        //kondisi inputan salah
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        //tampung inputan yang sudah benar
        $validated = $validator->validated();

        // masukan ke tabel product
        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'type' => $validated['type'],
            'expired_at' => $validated['expired_at']
        ]);

        // kondisi inputan benar
        return response()->json([
            'messages' => 'Data Berhasil Disimpan'
        ])->setStatusCode(201);
    }

    public function showAll()
    {
        $products = Product::all();

        return response()->json($products)->setStatusCode(200);
    }

    public function showById($id)
    {
        // mencari product berdasarkan Id
        $products = Product::where('id', $id)->first();

        //kondisi inputan benar
        if ($products) {

            //respon data ada
            return response()->json([
                'messages' => 'Data Product dengan ID: ' . $id,
                'data' => $products
            ])->setStatusCode(201);
        }

        return response()->json([
            'messages' => 'Data Product dengan ID: ' . $id . ' tidak ditemukan'
        ])->setStatusCode(404);
    }

    public function showByName($name)
    {
        // cari data berdasarkan nama product yang mirip
        $products = Product::where('name', 'like', '%' . $name . '%')->get();

        //kondisi product ada
        if ($products->count() > 0) {
            return response()->json([
                'messages' => 'Data Product dengan nama yang mirip: ' . $name,
                'data' => $products
            ])->setStatusCode(201);
        }

        return response()->json([
            'messages' => 'Data Product dengan nama yang mirip: ' . $name . ' tidak ditemukan'
        ])->setStatusCode(404);
    }

    public function update(Request $request, $id)
    {
        // validasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'type' => 'required|in:makanan,minuman,makeup',
            'expired_at' => 'required|date'
        ]);

        //kondisi inputan salah
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        $checkData = Product::find($id);

        if ($checkData) {

            // sunting inputan yang benar ke database (table product)
            Product::where('id', $id)->update([
                'name' => $payload['name'],
                'price' => $payload['price'],
                'type' => $payload['type'],
                'expired_at' => $payload['expired_at']
            ]);

            // kondisi inputan benar
            return response()->json([
                'messages' => 'Data Berhasil Diubah'
            ])->setStatusCode(201);
        }

        return response()->json([
            'messages' => 'Data Tidak Ditemukan'
        ])->setStatusCode(404);
    }

    public function destroy($id)
    {
        $checkData = Product::find($id);

        if ($checkData) {
            Product::destroy($id);

            return response()->json([
                'messages' => 'Data Berhasil Dihapus'
            ])->setStatusCode(200);
        }

        return response()->json([
            'messages' => 'Data Tidak Ditemukan'
        ])->setStatusCode(404);
    }
}
