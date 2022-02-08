<?php

namespace App\Http\Controllers;

use App\Facades\CounterFacade;
use App\Http\Requests\UpdateUser;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
            'counter' => CounterFacade::increment("user_{$user->id}")
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {

        // ulozime avatara
        if ($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('avatars');

            // obrazok pre usera uz existuje, ta userovi len updatnem cestu
            if ($user->image) {
                $user->image->path = $path;
                $user->image->save();
                
            } else {

                //dd(Image::make(['path' => $path]));

                $user->image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

        //ulozime meno a locale
        $user->name = $request->get('name');
        $user->locale = $request->get('locale');
        $user->save();

        // po ulozeni to presmeruje a zobrazi flash
        // v dvoch krokoch
        // $request->session()->flash('status', 'User was updated');
        // return redirect()->route('users.show', ['user' => $user->id]);

        // naraz
        return redirect()
            // presmerovanie na show
            //->route('users.show', ['user' => $user->id])
            // presmerovanie na seba, update
            ->back()
            ->withStatus('Profile was updated');

        //return view('users.update', ['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
