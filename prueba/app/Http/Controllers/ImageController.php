<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Image;
use Illuminate\Http\Response;
use App\Models\Comment;
use App\Models\Like;

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

    public function delete($id){
            $user = \Auth::user();
            $image = Image::find($id);
            $comments = Comment::where('image_id', $id)->get();
            $likes = Like::where('image_id', $id)->get();

            if($user && $image && $image->user->id == $user->id){
                //eliminar comentarios
                if($comments && count($comments) >=1){
                    foreach($comments as $comment){
                        $comment->delete();
                    }
                }
                //eliminar likes
            if($user && $image->user->id == $user->id){
                //eliminar comentarios
                if($likes && count($likes) >=1){
                    foreach($likes as $like){
                        $like->delete();
                    }
                }
            }

            //eliminar ficheros de imagen

            Storage::disk('images')->delete($image->image_path);

            //eliminar imagen
            $image->delete();
            $message = array('message' => 'La imagen se ha borrado con éxito');
        }else{
            $message = array('message' => 'La imagen no se ha podido borrar');
        }

        return redirect()->route('home')->with($message);
    }

    public function edit($id){
            $user = \Auth::user();
            $image = Image::find($id);

            if($user && $image && $image->user->id == $user->id){
                return view('images.edit', [
                    'image' => $image
                ]);

        }else{
            return redirect()->route('home');
        }
    }

    public function update(Request $request){
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'image',
        ]);

        //Recoger datos
        $image_id = $request->input('image_id');
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //Conseguir objeto image
        $image = Image::find($image_id);
        $image->description = $description;

        //Subir fichero
        if($image_path){
            $image_path_name = time().$image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        //Actualizar registro
        $image->update();

        return redirect()->route('image.detail', ['id' => $image_id])
                        ->with(['message' => 'Imagen actualizada con éxito']);
    }

}