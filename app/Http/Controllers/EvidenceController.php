<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Evidence;
use App\Models\Category;
class EvidenceController extends Controller
{
    

    
    public function landing(){
        $evidence = Evidence::whereHas('images', function ( $query) {
            $query->where('status', 'Activo');
        })->with('images')->get();
        return ["result"=>["status"=>"Ok","data"=>["evidence"=>$evidence]]];
    }

    public function index(){
        //solamente la tabla 
        return view('evidence.index');
        
    }

    public function dataTable(Request $request){
        if ($request->ajax()) {
            $data = Evidence::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $json ="{
                        url:'/evidence/edit/".$row->id."',
                        btn:$(this),
                        msj_finished: 'Editar',
                        div_objet:'content'
                    }";
                    $json_delete = "{
                        url:'/evidence/delete',
                        id:'".$row->id."',
                        btn:$(this),
                        msj_finished: 'Eliminar',
                        url_finished:'/evidence/index',
                        div_objet:'content'
                    }";
                    $actionBtn = '<a href="javascript:void(0)" onClick="getView('.$json.')" class="edit btn btn-success btn-sm">Editar</a> <a href="#" onClick="deleteByID('.$json_delete.')" class="delete btn btn-danger btn-sm">Eliminar</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action','description'])
                ->make(true);
        }
    }

    public function create(){
        $categories = Category::all();
        return view('evidence.create',compact('categories'));
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'El titulo es Requerido',
            'category_id.required' => 'La categoría es Requerida'
        ]);
        //dd(\Auth::user()->id);
        $validatedData['user_id'] = \Auth::user()->id;
        $evidence = Evidence::create($validatedData);

        return ["result"=>["status"=>"Ok","data"=>["evidence"=>$evidence]]];
    }
    
    public function show($id){
        $evidence = Evidence::where('id',$id)->with('images')->with('category')->with('user')->first();
        //dd($evidence);
        return view('evidence.landing_modal',compact('evidence'));
        return ["result"=>["status"=>"Ok","data"=>["evidence"=>$evidence]]];
    }

    public function edit($id){
        //retorno la vista para editar
        $evidence = Evidence::findOrfail($id);
        $categories = Category::all();
        //dd($id);
        return view('evidence.edit',compact('evidence','categories'));
    }

    public function update(Request $request){
        $validatedData = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
        ], [
            'title.required' => 'El titulo es Requerido',
            'category_id.required' => 'La categoría es Requerida'
        ]);
        

        //aqui debo de renducir el tamaño de las imagines y guardarlas 
        $evidence = Evidence::findOrfail($request->id);
        $evidence->update($validatedData);

        return ["result"=>["status"=>"Ok"]];
    }

    public function destroy(request $request){
        //dd($request);
        $evidence = Evidence::findOrfail($request->id);
        $evidence->delete();
        return ["result"=>["status"=>"Ok"]];
    }


    public function saveImages($request,$file,$name,$url = 'images/evidence/'){
        $files = $request->file($file);
        //dd($files);
        $newProspectImages = new ProspectImage();
        $newProspectImages->prospect_id = $this->prospect->id;
        $newProspectImages->image_name = $name;
        $destinationPath = $url; // upload path
        $profilefile = date('YmdHisu') . $name . "." . $files->getClientOriginalExtension();
        $files->move($destinationPath, $profilefile);
        $insert['file'] = "$profilefile";
        $newProspectImages->route = $insert['file'];
        $newProspectImages->save();
    }
}
