<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Post;
use App\Models\PostView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $posts = Post::query()
        ->where('activo','=',1)
        ->whereDate('publicarse_en','<',Carbon::now())
        ->orderBy('publicarse_en','desc')
        ->paginate(10);

        

        
        return view('home', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Request $request)
    {
        if(!$post->activo || $post->publicarse_en > Carbon::now()){
            throw new NotFoundHttpException();
        }

        $next = Post::query()
        ->where('activo', true)
        ->whereDate('publicarse_en','<=',Carbon::now())
        ->whereDate('publicarse_en','<',$post->publicarse_en)
        ->orderBy('publicarse_en','desc')
        ->limit(1)
        ->first();

        $prev = Post::query()
        ->where('activo', true)
        ->whereDate('publicarse_en','<=',Carbon::now())
        ->whereDate('publicarse_en','>',$post->publicarse_en)
        ->orderBy('publicarse_en','asc')
        ->limit(1)
        ->first();

        $user = $request->user();
        
        PostView::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'id_post' => $post->id,
           'id_usuario' => $user?->id //? indica que si no esta definido sera null
        ]);
        
        return view('post.vista', compact('post', 'prev', 'next'));
    }

    public function porCategoria(Categoria $categoria){
        $posts = Post::query()
        ->join('categoria_post', 'posts.id', '=', 'categoria_post.id_post')
        ->where('categoria_post.id_categoria', '=', $categoria->id)
        ->where('activo', '=', true)
        ->whereDate('publicarse_en', '<=', Carbon::now())
        ->orderBy('publicarse_en', 'desc')
        ->paginate(10);

        return view('post.index', compact('posts','categoria'));
    }

    
}
