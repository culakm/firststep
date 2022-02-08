<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Http\Requests\StorePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Events\BlogPostPosted;
use App\Facades\CounterFacade;

class PostController extends Controller
{

    // private $posts = [
    //     1 => [
    //         'title' => 'Intro to Laravel',
    //         'content' => 'This is a short intro to Laravel',
    //         'is_new' => 1,
    //         'has_comments' => true
    //     ],
    //     2 => [
    //         'title' => 'Intro to PHP',
    //         'content' => 'This is a short intro to PHP',
    //         'is_new' => 2
    //     ],
    //     20 => [
    //         'title' => 'Som defaultna 20',
    //         'content' => 'Som defaultna 20 a nie som nova',
    //         'is_new' => 20,
    //         'has_comments' => true
    //     ],
    //     201 => [
    //         'title' => 'Som defaultna 201',
    //         'content' => 'Som defaultna 20 a nie som nova',
    //         'is_new' => 20,
    //         'has_comments' => true
    //     ]
    // ];

    public function __construct()
    {
        $this->middleware('auth')
         ->only(['create','store','edit','update','destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // nejake navratove veci 
        //$pako = BlogPost::withCount(['comments','comments as new_comments' => function ($query) {$query->where('created_at', '>=', '2021-12-08 13:28:52');}])->get();
        //return view('posts.index', ['posts' => BlogPost::all()]);

        //$posts_most_commented = BlogPost::mostCommented()->take(5)->get(); 

        return view(
            'posts.index',
            [
                //'posts' => BlogPost::withCount('comments')
                // //->orderBy('created_at','desc') // toto je priame zoradovanie, ak toto prebije to global scope
                //->get()

                // bez priameho radenia, pouzije sa global scope definovane v modeli ak je tam  vo funkcii boot 
                //static::addGlobalScope(new LatestScope);
                //'posts' => BlogPost::withCount('comments')->get(), 

                //'posts' => BlogPost::Latest()->withCount('comments')->with('user')->with('tags')->get(), //zoradovanie pomocou lokalnej scope funkcie kontrolera
                'posts' => BlogPost::latestWithRelations()->get(), //latestWithRelations je definovane v BlogPost modeli pretoze sa to este opakuje niekde inde
                
                // tieto data doda app/Http/ViewComposers/ActivityComposer.php
                // 'posts_most_commented' => $posts_most_commented,
                // 'users_most_active' => $users_most_active,
                // 'users_most_active_month' => $users_most_active_month,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        // vytvori validovane data z requestu
        $validated = $request->validated();
        // prida usera z requestu
        $validated['user_id'] = $request->user()->id; 
        //$post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();
        $post = BlogPost::create($validated);

        // file handling
        // prisiel nam z formularoveho prvku 'thumbnail' file?
        if ($request->hasFile('thumbnail')){
            // ulozime file do defaultneho disku v podadresari 'thumbnails' a dostaneme jeho cestu/meno
            $path = $request->file('thumbnail')->store('thumbnails');
            // cez BlogPost ulozime
            $post->image()->save(
                // vytvorime do TB image cestu k suboru, blog_post_id je urobene automaticky cez relationship
                Image::make(['path' => $path])
            );
        }

        // posle mail vsetkym adminom ked je pridany novy blogpost
        // generuje event
        event(new BlogPostPosted($post));

        $request->session()->flash('status', 'BlogPost was created');

        return redirect()->route('posts.show', ['post' => $post->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //abort_if(!isset($this->posts[$id]), 404);
        //return view('posts.show', ['post' => $this->posts[$id]]);
        // $pako = BlogPost::FindOrFail($id);
        // $pako = BlogPost::with('comments')->whereKey($id)->get()->first();
        //dd($pako);
        // return view('posts.show', ['post' => BlogPost::FindOrFail($id)]);
        //return view('posts.show', ['post' => BlogPost::with('comments')->whereKey($id)->get()->first()]);

        $bp = Cache::tags(['blog_post'])->remember("blog_post_{$id}", 60, function() use($id){
            // return BlogPost::with('comments')
            // ->with('user')
            // ->with('tags')
            // ->with('comments.user')
            // ->FindOrFail($id);
            return BlogPost::with(['comments', 'user', 'tags', 'comments.user'])
            ->FindOrFail($id);
        });

        // Vselijake cachovane cisla pre pocitanie pristupov. Service
        // toto volanie je nahradene dependecy injection v kostruktore, potom je to volane ako $this->counter
        //$counter = resolve(Counter::class);

        return view('posts.show', [
            'post' => $bp,
            'counter' => CounterFacade::increment("blog-post-{$id}", ['blog-post']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::FindOrFail($id);
        // Check if blogpost was created by loged user
        $this->authorize('update', $post);
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::FindOrFail($id);

        // Check if blogpost was created by loged user
        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can't update this blog post!");
        // }
        $this->authorize('update', $post);
        
        $validated = $request->validated();
        $post->fill($validated);

        // file handling
        if ($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnails');

            if($post->image) {
                // vymazeme stary obrazok
                Storage::delete($post->image->path);
                $post->image->path = $path;
                // uloz cestu k novemu obrazku
                $post->image->save();
            } else {
                // uloz prvy obrazok
                // $post->image()->save(
                //     Image::create(['path' => $path])
                // );

                // uloz prvy obrazok s polymorfizmom
                $post->image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

        // vsetky zmeny v poste sa ulozia
        $post->save();

        $request->session()->flash('status', 'BlogPost was updated');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::FindOrFail($id);
        // Check if blogpost was created by loged user
        $this->authorize('delete', $post);
        
        $post->delete();

        session()->flash('status', 'BlogPost was deleted');

        return redirect()->route('posts.index');

    }
}
