<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
class UsersController extends Controller
{
    public function index(){
        //solamente la tabla 
        return view('users.index');
        
    }

    public function dataTable(Request $request){
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $json ="{
                        url:'/users/edit/".$row->id."',
                        btn:$(this),
                        msj_finished: 'Editar',
                        div_objet:'content'
                    }";
                    $json_delete = "{
                        url:'/users/delete',
                        id:'".$row->id."',
                        btn:$(this),
                        msj_finished: 'Eliminar',
                        url_finished:'/users/index',
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
        return view('users.create');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users'
        ], [
            'name.required' => 'El nombre es Requerido',
            'password.required' => 'La contraseña es requerida'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);

        return ["result"=>["status"=>"Ok"]];
    }

    public function edit($id){
        //retorno la vista para editar
        $user = User::findOrfail($id);
        //dd($id);
        return view('users.edit',compact('user'));
    }

    public function update(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id
        ], [
            'name.required' => 'El nombre es Requerido'
        ]);

        ($request->password != "") ? $validatedData['password'] = bcrypt($request->password): null ;
        if($request->password == "") unset($validatedData['password']);
        $user = User::findOrfail($request->id);
        $user->update($validatedData);

        return ["result"=>["status"=>"Ok"]];
    }

    public function destroy(request $request){
        //dd($request);
        $user = User::findOrfail($request->id);
        $user->delete();
        return ["result"=>["status"=>"Ok"]];
    }
}
