<?php

/**
 * Routes Doc Comment
 * 
 * PHP Version 8.0.12
 * 
 * @category Class
 * @package  MyPackage
 * @author   VelkyMongol <velkymongo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hellbilling.com/
 */

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\UserController;
use App\Mail\CommentPostedMarkdown;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::view('/', 'home.index')->name('home.index');
Route::get('/', [HomeController::class,'home'])
    ->name('home.index')
    //->middleware('auth') //vyzaduje autentifikaciu
;

Route::get('/contact', [HomeController::class,'contact'])->name('home.contact');
Route::get('/secret', [HomeController::class,'secret'])
    ->name('home.secret')
    ->middleware('can:home.secret')    //pre post by to bolo ->middleware('can:update,post', ... )
;

Route::get('/single', AboutController::class);

Route::resource(
    'posts',
    PostController::class
);
// ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);



Route::get('/posts/tag/{tag_id}', [PostTagController::class,'index'])->name('posts.tags.index');


// toto vytvori route posts.comments.store co je akcia v PostCommentController
// ine akcie nepotrebujeme pretoze comment sa vzdy zobrazuje v ramci blog postu
// pozor, PostCommentController je aj api/v1/ a su aj nastavene api routy
Route::resource('posts.comments', PostCommentController::class)->only(['index','store']);
Route::resource('users.comments', UserCommentController::class)->only(['store']);
Route::resource('users', UserController::class)->only(['show', 'edit', 'update']);

//routa pre testovanie mailov
Route::get('mailable', function(){
    $comment = Comment::find(1);
    return new CommentPostedMarkdown($comment);
})
->name('mailable')
->middleware('can:mailable') ;

Auth::routes();


// Route::get(
//     '/posts', function () use ($posts) {
//         return view('posts.index', ['posts' => $posts]);
//     }
// )->name('posts.index');

// Route::get(
//     '/posts/{id?}', function ($id = 20) use ( $posts ) {
//         abort_if(!isset($posts[$id]), 404);
//         return view('posts.show', ['post' => $posts[$id]]);
//     }
// )->where(
//     ['id' => '[0-9]+']
// )->name(
//     'posts.show'
// );

// Route::prefix(
//     '/fun'
// )->name(
//     'fun.'
// )->group(
//     function () use ( $posts ) {

//         Route::get(
//             '/responses', function () use ( $posts ) {
//                 return response($posts, 201)
//                     ->header('Concent-Type', 'application/json')
//                     ->cookie('MY_COOKIE', 'Martin lala', 3600);
//             }
//         )->name(
//             'responses'
//         );

//         Route::get(
//             '/redirect', function () {
//                 return redirect('/contact');
//             }
//         )->name(
//             'redirect'
//         );

//         Route::get(
//             '/back', function () {
//                 return back();
//             }
//         )->name(
//             'back'
//         );

//         Route::get(
//             '/named-route', function () {
//                 return redirect()->route('posts.show', ['id' => 1]);
//             }
//         )->name(
//             'named-route'
//         );

//         Route::get(
//             '/away', function () {
//                 return redirect()->away('https://sme.sk');
//             }
//         )->name(
//             'away'
//         );

//         Route::get(
//             '/json', function () use ($posts) {
//                 return response()->json($posts);
//             }
//         )->name(
//             'json'
//         );

//         Route::get(
//             '/download', function () use ( $posts ) {
//                 return response()->download(
//                     public_path('/images/samurai.jpg', 'vystupne_meno_samuraia.jpg')
//                 );
//             }
//         )->name(
//             'responses'
//         );

//     }
// );