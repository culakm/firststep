<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Gate;

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
        //$pako = BlogPost::withCount(['comments','comments as new_comments' => function ($query) {$query->where('created_at', '>=', '2021-12-08 13:28:52');}])->get();
        
        //return view('posts.index', ['posts' => BlogPost::all()]);
        return view(
            'posts.index',
            ['posts' => BlogPost::withCount('comments')->get()]
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
        $validated = $request->validated();

        //$post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();
        $post = BlogPost::create($validated);

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

        $bp = BlogPost::FindOrFail($id);
        //$bp = BlogPost::with('comments')->findOrFail($id);
        //dd($bp);
        return view('posts.show', ['post' => $bp]);
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
