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
use App\Http\Controllers\PostController;
use App\Http\Controllers\PasswordResetController;


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

# Login Controller
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

# Register Controller
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/check-username-availability/{username}', [RegisterController::class, 'checkUsernameAvailability']);
Route::get('/resend-verification-email', [RegisterController::class, 'showVerificationForm'])->name('resend-verification-email');
Route::post('/resend-verification-email', [RegisterController::class, 'resendVerification']);
Route::middleware(['verify.token.auth'])->get('/verify-account', [RegisterController::class, 'verifyAccount'])->name('verify-account');

# Forgot Password Controller
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::middleware(['reset.token.auth'])->get('/reset-password', [PasswordResetController::class, 'showNewPasswordForm'])->name('reset-password');

# FAQ Controller
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::middleware(['auth', 'admin'])->group(function () {

    # Admin Controller
    Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin-panel');
    Route::post('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('update-user');
    Route::post('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
    Route::post('/admin/update-society/{id}', [AdminController::class, 'updateSociety'])->name('update-society');
    Route::post('/admin/accept-society/{id}', [AdminController::class, 'acceptSociety'])->name('accept-society');
    Route::post('/admin/deny-society/{id}', [AdminController::class, 'denySociety'])->name('deny-society');
    Route::post('/admin/accept-society-claim/{societyId}', [AdminController::class, 'acceptSocietyClaim'])->name('accept-society-claim');
    Route::post('/admin/deny-society-claim/{id}', [AdminController::class, 'denySocietyClaim'])->name('deny-society-claim');
    Route::post('/admin/delete-society/{id}', [AdminController::class, 'deleteSociety'])->name('delete-society');
    Route::post('/admin/ban-user/{id}', [AdminController::class, 'banUser'])->name('ban-user');
    Route::post('/admin/unban-user/{id}', [AdminController::class, 'unbanUser'])->name('unban-user');
});

Route::middleware('auth')->group(function () {

    # Society Controller
    Route::get('/societies', [SocietyController::class, 'index'])->name('societies');                                                                                   
    Route::get('/societies/{id}', [SocietyController::class, 'viewSocietyInfo'])->name('view-society');
    Route::post('/create-society', [SocietyController::class, 'createSociety'])->name('create-society');
    Route::post('/join-society/{societyId}', [SocietyController::class, 'joinSociety'])->name('join-society');
    Route::post('/leave-society/{societyId}', [SocietyController::class, 'leaveSociety'])->name('leave-society');
    Route::post('/edit-society/{societyId}', [SocietyController::class, 'editSociety'])->name('edit-society');
    Route::post('/claim-society/{societyId}', [SocietyController::class, 'claimSociety'])->name('claim-society');
    Route::post('/delete-society/{societyId}', [SocietyController::class, 'deleteSociety'])->name('delete-society');
    Route::post('/promote-to-moderator/{societyId}', [SocietyController::class, 'promoteToModerator'])->name('promote-to-moderator');
    Route::post('/demote-moderator/{societyId}', [SocietyController::class, 'demoteModerator'])->name('demote-moderator');

    # Post Controller
    Route::get('/posts/{postId}', [PostController::class, 'show'])->name('post.show');
    Route::get('/societies/{societyId}/posts/{postId}', [PostController::class, 'viewPost'])->name('view-post');
    Route::post('/create-post/{societyId}', [PostController::class, 'createPost'])->name('create-post');
    Route::post('/delete-post/{postId}', [PostController::class, 'deletePost'])->name('delete-post');
    Route::post('/report-post/{postId}', [PostController::class, 'reportPost'])->name('report-post');
    Route::post('/pin-post/{postId}', [PostController::class, 'pinPost'])->name('pin-post');
    Route::post('/like-post/{postId}', [PostController::class, 'likePost'])->name('like-post');
    Route::post('/dislike-post/{postId}', [PostController::class, 'dislikePost'])->name('dislike-post');
    Route::post('/bookmark/{postId}', [PostController::class, 'bookmarkPost'])->name('bookmark.post');
    Route::delete('/unbookmark/{postId}', [PostController::class, 'unbookmarkPost'])->name('unbookmark.post');
    Route::get('/check-bookmark/{postId}', [PostController::class, 'checkBookmark'])->name('check-bookmark');

    #Comment Controller
    Route::get('/societies/{societyId}/posts/{postId}/comments/{commentId}', [CommentController::class, 'viewComment'])->name('view-comment');
    Route::post('save-comment/{commentId}', [CommentController::class, 'saveComment'])->name('save-comment');
    Route::delete('/unsave-comment/{commentId}', [CommentController::class, 'unsaveComment'])->name('unsave-comment');
    Route::post('report-comment/{postId}/{commentId}', [CommentController::class, 'reportComment'])->name('report-comment');
    Route::post('/post/{postId}/comment', [CommentController::class, 'addComment'])->name('add-comment');
    Route::delete('/delete-comment/{commentId}', [CommentController::class, 'deleteComment'])->name('delete-comment');
    Route::post('/societies/{societyId}/posts/{postId}/comments/{commentId}/respond', [CommentController::class, 'respondToComment'])->name('respond-to-comment');
    Route::post('/like-comment/{commentId}', [CommentController::class, 'likeComment'])->name('like-comment');
    Route::post('/dislike-comment/{commentId}', [CommentController::class, 'dislikeComment'])->name('dislike-comment');

    #Account Controller
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/change-email', [AccountController::class, 'changeEmail'])->name('change-email');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change-password');
    Route::post('/delete-account', [AccountController::class, 'deleteAccount'])->name('delete-account');

    #Profile Controller
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile-update', [ProfileController::class, 'updateProfile'])->name('profile-update');
    Route::delete('/delete-message/{id}', [ProfileController::class, 'deleteMessage'])->name('delete-message');

    #User Controller
    Route::get('/view-students', [UserController::class, 'index'])->name('view-students');  
    Route::get('/student/{id}', [UserController::class, 'showProfile'])->middleware(['auth', 'checkBlockedUser'])->name('user.profile');
    Route::post('/send-message/{id}', [UserController::class, 'sendMessage'])->name('send-message');
    Route::post('/delete-message/{id}', [UserController::class, 'deleteMessage'])->name('delete-message');
    Route::post('/mark-message/{id}', [UserController::class, 'markMessage'])->name('mark-message');
    Route::post('/unmark-message/{id}', [UserController::class, 'unmarkMessage'])->name('unmark-message');
    Route::post('/block-user', [UserController::class, 'blockUser'])->name('block-user');
    Route::post('/unblock-user', [UserController::class, 'unblockUser'])->name('unblock-user');

    #Discovery Controller
    Route::get('/discovery', [DiscoveryController::class, 'index'])->name('discovery');

    #Friend Controller
    Route::post('/send-friend-request', [FriendController::class, 'sendFriendRequest'])->name('sendFriendRequest');
    Route::post('/cancel-friend-request', [FriendController::class, 'cancelFriendRequest'])->name('cancelFriendRequest');
    Route::post('/accept-friend-request', [FriendController::class, 'acceptFriendRequest'])->name('acceptFriendRequest');
    Route::post('/deny-friend-request', [FriendController::class, 'denyFriendRequest'])->name('denyFriendRequest');
    Route::post('/delete-pending-request', [FriendController::class, 'deletePendingRequest'])->name('delete-pending-request');
    Route::post('/remove-friend', [FriendController::class, 'removeFriend'])->name('removeFriend');
});


