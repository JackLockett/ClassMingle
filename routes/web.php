<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/check-username-availability/{username}', [RegisterController::class, 'checkUsernameAvailability']);

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin-panel');
    Route::post('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('update-user');
    Route::post('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
    Route::post('/admin/update-society/{id}', [AdminController::class, 'updateSociety'])->name('update-society');
    Route::post('/admin/accept-society/{id}', [AdminController::class, 'acceptSociety'])->name('accept-society');
    Route::post('/admin/deny-society/{id}', [AdminController::class, 'denySociety'])->name('deny-society');
    Route::post('/admin/delete-society/{id}', [AdminController::class, 'deleteSociety'])->name('delete-society');
});

Route::middleware('auth')->group(function () {
    Route::get('/societies', [SocietyController::class, 'index'])->name('societies');  
    Route::get('/societies/{id}', [SocietyController::class, 'viewSocietyInfo'])->name('view-society');
    Route::post('/promote-to-moderator/{societyId}', [SocietyController::class, 'promoteToModerator'])->name('promote-to-moderator');
    Route::post('/demote-moderator/{societyId}', [SocietyController::class, 'demoteModerator'])->name('demote-moderator');
    Route::post('/create-society', [SocietyController::class, 'createSociety'])->name('create-society');
    Route::post('/create-post/{societyId}', [SocietyController::class, 'createPost'])->name('create-post');
    Route::post('/delete-post/{postId}', [SocietyController::class, 'deletePost'])->name('delete-post');
    Route::post('/pin-post/{postId}', [SocietyController::class, 'pinPost'])->name('pin-post');
    Route::post('/post/{postId}/comment', [SocietyController::class, 'addComment'])->name('add-comment');
    Route::get('/posts/{postId}', [SocietyController::class, 'show'])->name('post.show');
    Route::get('/societies/{societyId}/posts/{postId}', [SocietyController::class, 'viewPost'])->name('view-post');
    Route::post('/join-society/{societyId}', [SocietyController::class, 'joinSociety'])->name('join-society');
    Route::post('/leave-society/{societyId}', [SocietyController::class, 'leaveSociety'])->name('leave-society');
    Route::post('/edit-society/{societyId}', [SocietyController::class, 'editSociety'])->name('edit-society');
    Route::post('/delete-society/{societyId}', [SocietyController::class, 'deleteSociety'])->name('delete-society');
    Route::post('/bookmark/{postId}', [SocietyController::class, 'bookmarkPost'])->name('bookmark.post');
    Route::delete('/unbookmark/{postId}', [SocietyController::class, 'unbookmarkPost'])->name('unbookmark.post');
    Route::get('/check-bookmark/{postId}', [SocietyController::class, 'checkBookmark'])->name('check-bookmark');
    Route::post('save-comment/{commentId}', [SocietyController::class, 'saveComment'])->name('save-comment');
    Route::delete('/unsave-comment/{commentId}', [SocietyController::class, 'unsaveComment'])->name('unsave-comment');
    Route::delete('/delete-comment/{commentId}', [SocietyController::class, 'deleteComment'])->name('delete-comment');

    Route::get('/societies/{societyId}/posts/{postId}/comments/{commentId}', [CommentController::class, 'viewComment'])->name('view-comment');
    Route::post('/societies/{societyId}/posts/{postId}/comments/{commentId}/respond', [CommentController::class, 'respondToComment'])->name('respond-to-comment');

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/change-email', [AccountController::class, 'changeEmail'])->name('change-email');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change-password');
    Route::post('/delete-account', [AccountController::class, 'deleteAccount'])->name('delete-account');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile-update', [ProfileController::class, 'updateProfile'])->name('profile-update');

    Route::get('/view-students', [UserController::class, 'index'])->name('view-students');  
    Route::get('/student/{id}', [UserController::class, 'showProfile'])->name('user.profile');
    Route::post('/send-message/{id}', [UserController::class, 'sendMessage'])->name('send-message');
    Route::post('/delete-message/{id}', [UserController::class, 'deleteMessage'])->name('delete-message');

    Route::get('/discovery', [DiscoveryController::class, 'index'])->name('discovery');

    Route::post('/send-friend-request', [FriendController::class, 'sendFriendRequest'])->name('sendFriendRequest');
    Route::post('/cancel-friend-request', [FriendController::class, 'cancelFriendRequest'])->name('cancelFriendRequest');
    Route::post('/accept-friend-request', [FriendController::class, 'acceptFriendRequest'])->name('acceptFriendRequest');
    Route::post('/deny-friend-request', [FriendController::class, 'denyFriendRequest'])->name('denyFriendRequest');
    Route::post('/delete-pending-request', [FriendController::class, 'deletePendingRequest'])->name('delete-pending-request');
    Route::post('/remove-friend', [FriendController::class, 'removeFriend'])->name('removeFriend');
    Route::delete('/delete-message/{id}', [ProfileController::class, 'deleteMessage'])->name('delete-message');
});


