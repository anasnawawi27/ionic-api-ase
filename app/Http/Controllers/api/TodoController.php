<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [
            'success' => false,
            'message' => 'Something is wrong!',
            'statusCode' => 400
        ];

        $todos = Todo::all();
        if($todos->count()){
            $output = [
                'success' => true,
                'rows' => $todos,
                'statusCode' => 200
            ];
        }
        return response()->json($output, $output['statusCode']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $output = [
            'success' => false,
            'statusCode' => 400,
            'message' => 'Something is wrong!',
        ];

        //validasi
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100|unique:todos',
            'description' => 'required|max:100'
        ], [
            'title.required' => 'Judul wajib diisi',
            'description.required' => 'Deskripsi wajib diisi'
        ]);

        if($validator->fails()){
            $output['message'] = $validator->errors();
        } else {

            //simpan
            $todo = new Todo();
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->save();

            $output['success'] = true;
            $output['statusCode'] = 201;
            $output['message'] = 'Data Todo Berhasil ditambahkan!';
        }

        //response
        return response()->json($output, $output['statusCode']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $output = [
            'success' => false,
            'statusCode' => 400,
            'message' => 'Something is wrong!',
        ];

        $todo = Todo::find($id);
        if($todo){
            $output = [
                'success' => true,
                'statusCode' => 200,
                'data' => $todo,
            ];
        }
        return response()->json($output, $output['statusCode']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $output = [
            'success' => false,
            'statusCode' => 400,
            'message' => 'Something is wrong!',
        ];

        if($id){

            //validasi
            $validator = Validator::make($request->all(), [
                'title' => ['required','max:100', Rule::unique('todos')->ignore($id)],
                'description' => 'required|max:100'
            ],
            [
                'title.required' => 'Judul wajib diisi',
                'description.required' => 'Deskripsi wajib diisi'
            ]);
    
            if($validator->fails()){
                $output['message'] = $validator->errors();
            } else {
    
                //simpan
                $todo = Todo::find($id);
                $todo->title = $request->title;
                $todo->description = $request->description;
                $todo->is_done = $request->is_done;
                $todo->save();
    
                $output['success'] = true;
                $output['statusCode'] = 200;
                $output['message'] = 'Data Todo Berhasil disimpan!';
            }
        }


        //response
        return response()->json($output, $output['statusCode']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $output = [
            'success' => false,
            'statusCode' => 400,
            'message' => 'Something is wrong!',
        ];

        if($id){
            $todo = Todo::destroy($id);
            if($todo){
                $output['success'] = true;
                $output['statusCode'] = 200;
                $output['message'] = 'Data Todo Berhasil dihapus!';
            } else {
                $output['success'] = false;
                $output['statusCode'] = 404;
                $output['message'] = 'Data Todo Gagal dihapus!';
            }
        }

        return response()->json($output, $output['statusCode']);
    }
}
