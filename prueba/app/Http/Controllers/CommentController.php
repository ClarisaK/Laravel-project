<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }

    public function save(Request $request) {
        //validacion
        $validate = $this->validate($request, [
            'image_id' => 'int|required',
            'content' => 'string|required'
        ]);

        //datos
        $user = \Auth::user();
        $image_id = $request->input('image_id');
        $content = $request->input('content');

        //asigno valores al objeto
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;

        //guardar en la base de datos
        $comment->save();

        //redireccion
        return redirect()->route('images.detail', ['id' => $image_id])
        ->with(['message' => 'El comentario se ha publicado correctamente']);

    }

    public function delete($id) {
        //conseguir datos del usuario logueado
        $user = \Auth::user();

        //conseguir objeto del comentario
        $comment = Comment::find($id);

        //comprobar si es el dueño del comentario o publicacion
        if($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)){
            $comment->delete();
            return redirect()->route('images.detail', ['id' => $comment->image->id])
        ->with(['message' => 'El comentario se ha eliminado correctamente']);
        }else{
            return redirect()->route('images.detail', ['id' => $comment->image->id])
        ->with(['message' => 'El comentario no se ha eliminado']);
        }
    }
}
