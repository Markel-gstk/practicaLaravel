<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Idea;

class ApiController extends Controller
{
    
    private $IdeaController; 
    
    public function __construct(IdeaController $IdeaController){
        $this->IdeaController = $IdeaController;
    }
    /**
     * Display a listing of the resource.
     */
    public function getIdea(Request $request){
    
        return $this->IdeaController->index($request, true);
    }

    public function getUser()
    {
        $usuario = User::all();

        return response()->json($usuario);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeIdea(Request $request)
    {
        return $this->IdeaController->store($request, true);
    }

    public function storeUser(Request $request)
    {
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);

        $usuario->save();

        return response()->json([
            "message"=>"Ususario creado con exito",
            "data" => $usuario
        ], 201);

    }
    
    public function showIdea($id)
    {
        $idea = $this->IdeaController->show(Idea::findOrFail($id), true);
    
        if(!$idea){
            return response()->json(['error'=>'Error, idea no encontrada'],404);
        }else{
            return response()->json($idea, 200);
        } 
    }

    public function showUser($id)
    {
        $usuario = User::find($id,['*']);
        if(!$usuario){
            return response()->json(['error'=> 'Error, usuario no encontrado'],404);
        }else{
            return response()->json($usuario, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateIdea(Request $request,  $id)
    {
        return $this->IdeaController->update($request, Idea::findOrFail($id), true);
    }
    public function updateUser(Request $request, $id)
    {
        $usuario = User::find($id,['*']);

        if(!$usuario) {
            return response()->json(['error'=> 'Usuario no encontrado'],404);
        }

        if($request->has('name')){$usuario->name = $request->name;}
        
        if($request->has('email')){$usuario->email = $request->email;}
     
        /** @var \App\Models\User $usuario */
        $usuario->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyIdea($id)
    {
        return $this->IdeaController->delete(Idea::findOrFail($id), true);
    }

     public function destroyUser($id)
    {
        $usuario = User::find($id,['*']);

        if(!$usuario){
            return response()->json(['error'=> 'Error, usuario no encontrado'],404);
        }
        /** @var \Illuminate\Database\Eloquent\Model $usuario */
        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente'],204);
    }
}
