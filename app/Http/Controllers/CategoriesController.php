<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DataTables;
class CategoriesController extends Controller
{
    public function index(){
        //solamente la tabla 
        return view('categories.index');
        
    }

    public function dataTable(Request $request){
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $json ="{
                        url:'/categories/edit/".$row->id."',
                        btn:$(this),
                        msj_finished: 'Editar',
                        div_objet:'content'
                    }";
                    $json_delete = "{
                        url:'/categories/delete',
                        id:'".$row->id."',
                        btn:$(this),
                        msj_finished: 'Eliminar',
                        url_finished:'/categories/index',
                        div_objet:'content'
                    }";
                    $actionBtn = '<a href="javascript:void(0)" onClick="getView('.$json.')" class="edit btn btn-success btn-sm">Editar</a> <a href="#" onClick="deleteByID('.$json_delete.')" class="delete btn btn-danger btn-sm">Eliminar</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(){
        return view('categories.create');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'El nombre es Requerido'
        ]);

        $category = Category::create($validatedData);

        return ["result"=>["status"=>"Ok"]];
    }

    public function edit($id){
        //retorno la vista para editar
        $category = Category::findOrfail($id);
        //dd($id);
        return view('categories.edit',compact('category'));
    }

    public function update(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'El nombre es Requerido'
        ]);

        
        $category = Category::findOrfail($request->id);
        $category->update($validatedData);

        return ["result"=>["status"=>"Ok"]];
    }

    public function destroy(request $request){
        //dd($request);
        $category = Category::findOrfail($request->id);
        $category->delete();
        return ["result"=>["status"=>"Ok"]];
    }
}