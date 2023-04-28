<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Post;
use App\Models\PostView;
use App\Models\UpvoteDownvote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(): View
    {
        //latest
        $latestPost = Post::where('activo', '=', 1)
            ->whereDate('publicarse_en', '<', Carbon::now())
            ->orderBy('publicarse_en', 'desc')
            ->limit(1)
            ->first();

        //3 most popular based on upvotes
        //con esto arreglaba lo del group by, pero en vez de hacerlo asi
        //lo arregle en el group by para evitar problemas al subirlo al servidor
        // DB::statement("SET SQL_MODE=''");
        $popular3Posts = Post::query()
            ->leftJoin('upvote_downvotes', 'posts.id', '=', 'upvote_downvotes.id_post')
            ->select('posts.*', DB::raw('COUNT(upvote_downvotes.id) as upvote_count'))
            ->where(function ($query) {
                $query->where('upvote_downvotes.is_upvoted', '=', 1);
            })
            ->where('activo', '=', 1)
            ->whereDate('publicarse_en', '<', Carbon::now())
            ->orderByDesc('upvote_count')
            ->groupBy([
                'posts.id',
                'posts.titulo',
                'posts.descripcion',
                'posts.miniatura',
                'posts.post',
                'posts.activo',
                'posts.publicarse_en',
                'posts.id_usuario',
                'posts.created_at',
                'posts.updated_at',
                'posts.meta_title',
                'posts.meta_description'
            ])
            ->limit(6)
            ->get();

        //if authorized, show recomended posts based on upvotes
        $user = Auth()->user();

        if ($user) {
            $leftJoin = "(SELECT cp.id_categoria, cp.id_post FROM upvote_downvotes
            JOIN categoria_post cp ON upvote_downvotes.id_post =
            cp.id_post WHERE upvote_downvotes.is_upvoted = 1 AND upvote_downvotes.id_user = ?) as t";
            $recommendedPosts = Post::query()
                ->leftJoin('categoria_post as cp', 'posts.id', '=', 'cp.id_post')
                ->leftJoin(DB::raw($leftJoin), function ($join) {
                    $join->on('t.id_categoria', '=', 'cp.id_categoria')
                        ->on('t.id_post', '<>', 'cp.id_post');
                })
                ->select('posts.*')
                ->where('posts.id', '<>', DB::raw('t.id_post'))
                ->setBindings([$user->id])
                ->limit(3)
                ->get();
            //else based on views
        } else {
            $recommendedPosts = Post::query()
                ->leftJoin('post_views', 'posts.id', '=', 'post_views.id_post')
                ->select('posts.*', DB::raw('COUNT(post_views.id) as views_count'))
                // ->where(function($query){
                //     $query->where('upvote_downvotes.is_upvoted', '=', 1);
                // })
                ->where('activo', '=', 1)
                ->whereDate('publicarse_en', '<', Carbon::now())
                ->orderByDesc('views_count')
                ->groupBy([
                    'posts.id',
                    'posts.titulo',
                    'posts.descripcion',
                    'posts.miniatura',
                    'posts.post',
                    'posts.activo',
                    'posts.publicarse_en',
                    'posts.id_usuario',
                    'posts.created_at',
                    'posts.updated_at',
                    'posts.meta_title',
                    'posts.meta_description'
                ])
                ->limit(6)
                ->limit(3)
                ->get();
        }
        //recent categories with their recent posts
        $categories = Categoria::query()
        // ->with(['posts' => function($query){
        //     $query->orderByDesc('publicarse_en')->limit(3);
        // }])
        ->select('categorias.*')
        ->selectRaw('MAX(posts.publicarse_en) as max_date')
        ->leftJoin('categoria_post', 'categorias.id', '=', 'categoria_post.id_categoria')
        ->leftJoin('posts', 'posts.id', '=', 'categoria_post.id_post')
        ->orderByDesc('max_date')
        ->groupBy([
            'categorias.id',
            'categorias.titulo',
            'categorias.descripcion',
            'categorias.created_at',
            'categorias.updated_at',
        ])
        ->limit(5)
        ->get();
        
        return view('home', compact('latestPost', 'popular3Posts', 'recommendedPosts', 'categories'));
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
        if (!$post->activo || $post->publicarse_en > Carbon::now()) {
            throw new NotFoundHttpException();
        }

        $next = Post::query()
            ->where('activo', true)
            ->whereDate('publicarse_en', '<=', Carbon::now())
            ->whereDate('publicarse_en', '<', $post->publicarse_en)
            ->orderBy('publicarse_en', 'desc')
            ->limit(1)
            ->first();

        $prev = Post::query()
            ->where('activo', true)
            ->whereDate('publicarse_en', '<=', Carbon::now())
            ->whereDate('publicarse_en', '>', $post->publicarse_en)
            ->orderBy('publicarse_en', 'asc')
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

    public function porCategoria(Categoria $categoria)
    {
        $posts = Post::query()
            ->join('categoria_post', 'posts.id', '=', 'categoria_post.id_post')
            ->where('categoria_post.id_categoria', '=', $categoria->id)
            ->where('activo', '=', true)
            ->whereDate('publicarse_en', '<=', Carbon::now())
            ->orderBy('publicarse_en', 'desc')
            ->paginate(10);

        return view('post.index', compact('posts', 'categoria'));
    }

    public function search(Request $request)
    {
        $squery = $request->get('query');
        
        $posts = Post::query()
            ->where('activo', '=', true)
            ->whereDate('publicarse_en', '<=', Carbon::now())
            ->where(function($query) use($squery){
                $query->where('titulo', 'like', "%$squery%")
                ->orWhere('post', 'like', "%$squery%");
            })
            ->orderBy('publicarse_en', 'desc')
            ->paginate(10);

        return view('post.search', compact('posts'));
    }
}
