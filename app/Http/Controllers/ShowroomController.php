<?php

namespace App\Http\Controllers;

use App\Models\Showroom;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShowroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $showroom = Showroom::all();

       try {
         if($showroom = null){
            return response()->json([
                'message'=>'data tidak',
                'data'=> $showroom
            ],200);
        }

        return response()->json([
            'message' => 'Data berhasil di ambil',
            'data' => $showroom
        ], 200);
       }catch(\Exception $e){
        return response()->json([
            'status'=> 'error',
            'message'=>'Terjadi masalah pada server',
            'error'=> $e->GetMessage()
        ],500);
       }
    }

    public function store(Request $request)
    {
        try {
            $isBulk = array_is_list($request->all()); //untuk input banyak data
            if ($isBulk) {
                $validate = $request->validate([
                    '*.name' => 'required|string|max:255|unique:showrooms,name',
                    '*.price' => 'required|integer|min:0',
                    '*.stock' => 'required|integer|min:0',
                    '*.description' => 'required|string|max:255'
                ]);

                $showroom = [];

                foreach ($validate as $item) {
                    $showroom[] = Showroom::create($item);
                }
            } else {
                $validate = $request->validate([
                    'name' => 'required|string|max:255|unique:showrooms,name',
                    'price' => 'required|integer|min:0',
                    'stock' => 'required|integer|min:0',
                    'description' => 'required|string|max:255'
                ]);
                $showroom = Showroom::create($validate);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'data showroo, berhasil di buat',
                'data' => $showroom
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak berhasil di buat',
                'data' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $showroom = Showroom::find($id);

        if (!$showroom) {
            return response()->json([
                'message' => 'data tidak ada',
                'data' => $showroom
            ], 200);
        }
    }


    public function update(Request $request, string
    $id)
    {
        $showroom = Showroom::find($id);

        if (!$showroom) {
            return response()->json([
                'message' => 'Product tidak di temukan',

            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'description' => 'sometimes|required|string|max:255'
        ]);

        $showroom->update($validated);

        return response()->json([
            'message' => 'Product berhasil di buat',
            'data' => $showroom
        ], 200);
    }

    public function destroy(showroom $showroom, string $id)
    {
        try {
            $showroom->find($id);

            if (!$showroom) {
                return response()->json([
                    'message' => 'data tidak di temukan'
                ], 404);
            }

            $showroom->delete();

            return response()->json([
                'message' => 'data berhasil di hapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'tejadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }

        // $showroom->find($id);

        // if(!$showroom){
        //     return response()->json([
        //         'message'=>'data tidak di temukan',

        //     ],404);
        // }

        // $showroom->delete();

        // return response()->json([
        //     'message'=> 'data telah di hapus'
        // ],200);
    }
}
