<?php

use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\NotificationController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

Route::name('feed.')->prefix('feed')->group(function() {
    Route::get('/', [FeedController::class, 'get'])->name('get');
});

Route::name('post.')->prefix('posts')->group(function() {

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/{post}/like', [PostController::class, 'like'])->name('like');
        Route::put('/{post}/unlike', [PostController::class, 'unlike'])->name('unlike');
        Route::post('/create', [PostController::class, 'create'])->name('create');
    });

    Route::get('/{post}', [PostController::class, 'get'])->name('get');
    Route::get('/{post}/comments', [PostController::class, 'getComments'])->name('comments');
});

Route::name('user.')->prefix('users')->group(function() {
    Route::get('/search', [UserController::class, 'search'])->name('search');

    Route::get('/{user}', [UserController::class, 'get'])->name('get');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'get'])->name('get');

        Route::put('/{user}/profile', [UserController::class, 'updateProfile'])->name('update');
        Route::put('/{user}/follow', [UserController::class, 'follow'])->name('follow');
        Route::put('/{user}/unfollow', [UserController::class, 'unfollow'])->name('unfollow');
    });
});

Route::name('notification.')->prefix('notifications')->group(function() {

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [NotificationController::class, 'get'])->name('get');
        Route::put('/{id}', [NotificationController::class, 'markAsRead'])->whereUuid('id')->name('markAsRead');
    });
});

Route::name('project.')->prefix('projects')->group(function() {
    Route::get('/search', [ProjectController::class, 'search'])->name('search');
    Route::get('/{project}', [ProjectController::class, 'get'])->name('get');
    Route::get('/{project}/posts', [ProjectController::class, 'posts'])->name('posts');

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/{project}/follow', [ProjectController::class, 'follow'])->name('follow');
        Route::put('/{project}/unfollow', [ProjectController::class, 'unfollow'])->name('unfollow');
    });
});

Route::name('comments.')->prefix('comments')->group(function() {
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/', [CommentController::class, 'store'])->name('create');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('delete');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');

    });
   Route::get('/{comment}', [CommentController::class, 'get'])->name('get');
});

Route::name('badge.')->prefix('badges')->group(function() {
    Route::get('/search', [TechnologyController::class, 'search'])->name('search');
});
