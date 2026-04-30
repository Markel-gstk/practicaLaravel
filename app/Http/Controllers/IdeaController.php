<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\User;
use phpDocumentor\Reflection\Types\Boolean;

class IdeaController extends Controller
{

    private array $validationRules = [
        'title' => 'required|string|max:100',
        'description' => 'required|string|max:300' 
    ];

    
    private array $errorMessages = [
        'title.required' => 'El campo titulo es obligatorio',
        'description.required' => 'El campo descripcion es obligatorio',
        'titulo.string' => 'El campo titulo es debe ser de tipo string',
        'description.string' => 'El campo descripcion debe ser de tipo string',
        'string' => 'Este campo debe ser de tipo string',
        'title.max' => 'El campo :atribute no de debe ser mayor que 100',
        'description.max' => 'El campo :atribute no de debe ser mayor que 300'
    ];



    public function index(Request $request, $api=false)
    {

        
        
        if(!$api){
            $ideas = Idea::myIdeas($request->filtro)->theBest($request->filtro)->get();
            return view('idea.index', ['ideas'=> $ideas]);
        }else{
            $ideas = Idea::all();
            return response()->json($ideas);
        }
    }

    public function create() : View
    {

        

        return view('idea.create_or_edit');
    }

    public function store(Request $request, $api=false)
    {
        $validated=$request->validate($this->validationRules, $this->errorMessages);
        Log::info("Mensaje de error guardado correctamente en la variable");

        
        
        if(!$api){
            $nuevaIdea = Idea::create([
            
                'user_id' => $request->user()->id,   
                'title' => $validated['title'],
                'description' => $validated['description'], 
                    
            ]);
        }else{
            $nuevaIdea = Idea::create([
            
                'user_id' => '2',   
                'title' => $validated['title'],
                'description' => $validated['description'], 
                    
            ]);
        }


        session()->flash('message', 'Idea creada correctamente!');
        
        if(!$api){
            return redirect()->route('idea.index');
        }else{
            return response() -> json([
                'user_id' => '2',
                'title'=> $validated['title'],
                'description'=> $validated['description'],

            ], 201);
        }
    }

    public function edit(Idea $idea): View
    {  

        //session()->flash('message', 'Idea editada correctamente!');
        $this->authorize( 'update', $idea);
        return view('idea.create_or_edit')->with('idea', $idea);

    }

    public function update(Request $request, Idea $idea, $api=false)
    {
           
        $validated=$request->validate($this->validationRules, $this->errorMessages);

        if(!$api){
            
            $this->authorize( 'update', $idea);
            $idea->update($validated);


            session()->flash('message', 'Idea editada correctamente!');

            return redirect(route('idea.index'));

        }else{

            $idea->update($validated);
            return response()->json([
                'message' => 'Idea actualizada correctamente',
                'idea' => $idea
            ], 200);
        
        }    
    }

    public function show(Idea $idea, $api=false)
    {

        $liked = Auth::check() ? Auth::user()->iLikeIt($idea->id) : false;

       
        if(!$api){
            return view('idea.show', [
                'idea'=> $idea,
                'liked'=> $liked
            ]);
        }else{
            return $idea = Idea::find($idea->id, ['*']);
        }


    }

    public function delete(Idea $idea, $api=false)
    {
        if(!$api){$this->authorize( 'delete', $idea);}

        /** @var \Illuminate\Database\Eloquent\Model $idea */
        $idea->delete();

        /*session()->flash('message', 'Idea eliminada correctamente!');

        return redirect()->route('idea.index');*/
        
        return response()->json([
            'success' => true,
            'id' => $idea->id,
            'message' => 'Idea eliminada correctamente!'
        ]);

        
    }

    public function synchronizeLikes(Request $request, Idea $idea)
    {   
        Log::info("Controlador iniciado correctamente");
        $this->authorize('updateLikes', $idea);
    
        $request->user()->ideasUsers()->toggle([$idea->id]);
        
        $idea->update(['likes'=>$idea->usersIdeas()->count()]);

        //return redirect()->route('idea.show', $idea);

        return response()->json([
            'likes'=>$idea->usersIdeas()->count(),
            'liked'=>$idea->usersIdeas()->where('user_id', $request->user()->id)->exists(),
        ]);
    }

}
