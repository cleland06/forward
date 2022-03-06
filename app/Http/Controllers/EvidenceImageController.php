<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvidenceImages;
use Intervention\Image\ImageManagerStatic as Image;
class EvidenceImageController extends Controller
{
    
    public function store(Request $request){
        //guardo en la bd
        if ($request->hasFile('file')){
            $image = $request->file('file');
            $name     = time().'.'.$image->extension();
            $url=public_path('/images/'.$name);
            Image::make($image->getRealPath())
                ->resize(200,100, function ($constraint){ 
                    $constraint->aspectRatio();
                })
                ->save($url,72);
        }
        $evidenceImage = new EvidenceImages();
        $evidenceImage->evidence_id = $request->id;
        $evidenceImage->url         = "/images/".$name;
        $evidenceImage->save();
        return response()->json(['success'=>$name]);
    }

    public function updateStatus(Request $request){
        
        $evidenceimages = EvidenceImages::find($request->id);
        
        $evidenceimages->status = $request->status;
        $evidenceimages->update();

        return ["result"=>["status"=>"Ok","data"=>["evidenceimages"=>EvidenceImages::where($request->evidence_id)]]];
    }
    public function destroy(Request $request){
        $evidenceimages = EvidenceImages::find($request->id);
        @unlink(public_path($evidenceimages->url));
        $evidenceimages->delete();

        return ["result"=>["status"=>"Ok","data"=>["evidenceimages"=>EvidenceImages::where($request->evidence_id)->get()]]];
    }

    public function getEvidence($id){
        $evidenceImage = EvidenceImages::where('evidence_id',$id)->get();
        return ["result"=>["status"=>"Ok","data"=>["evidenceimages"=>$evidenceImage]]];
    }
}
