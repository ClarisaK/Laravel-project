<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Image;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }

    public function create(){
        return view("images.create");
    }

    public function save(Request $request) {
        // Validación
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'required|image',
        ]);
    
        // Recoger datos
        $description = $request->description;
        $user = \Auth::user();
    
        // Subir fichero
        $image_path_name = time() .'_'. $request->file('image_path')->getClientOriginalName();
    
        // Guardar la imagen en el sistema de archivos 'public'
        Storage::disk('public')->put($image_path_name, File::get($request->file('image_path')));
    
        // Crear una nueva instancia de tu modelo Image
        $imageModel = new Image();
        
        // Asignar valores al modelo
        $imageModel->user_id = $user->id;
        $imageModel->description = $description;
        $imageModel->image_path = $image_path_name;
    
        // Guardar el modelo Image en la base de datos
        $imageModel->save();
    
        return redirect()->route('home')->with('message', 'La foto ha sido subida con éxito');
    }

    public function getImage($filename){
        $file = Storage::disk('images')->get($filename);
        return new Response($file, 200);
    }

    public function detail($id){
        $image = Image::find($id);
        return view('images.detail', ['image'=> $image]);
    }
}
