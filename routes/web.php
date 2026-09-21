<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminLevelController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Admin\GameBuilderController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\RoomController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\InfoGalleryController;

// Import your actual models
use App\Models\PhishingEmail;
use App\Models\LevelTwoQuestion;
use App\Models\LevelThreeScenario;
use App\Models\LevelFourScenario;
use App\Models\LevelFiveQuestion;

/*
|--------------------------------------------------------------------------
| Public Routes (Guests Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // 1. The Main Game Wrapper (This holds the music)
    Route::get('/', function () { return view('game-wrapper'); })->name('home');

    // 2. The actual login/register forms
    Route::get('/login-frame', function () { return view('auth.login'); })->name('login');
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    
    // Form Submissions
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Logged In Agents & Admins Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Logout Action
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 🗂️ AGENT INTEL GALLERY ROUTES
    Route::get('/agent-gallery', [AgentController::class, 'intelGallery'])->name('agent.gallery');
    Route::get('/agent-gallery/{id}', [AgentController::class, 'intelGalleryShow'])->name('agent.gallery.show');

    // ==========================================
    // AGENT PROFILE ROUTES
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // ==========================================
    // AGENT DASHBOARD ROUTE
    // ==========================================
    Route::get('/dashboard', function () { 
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id()]);
        
        $roomsCompleted = 0;
        if ($progress->level_1_completed) $roomsCompleted++;
        if ($progress->level_2_completed) $roomsCompleted++;
        if ($progress->level_3_completed) $roomsCompleted++;
        if ($progress->level_4_completed) $roomsCompleted++;
        if ($progress->level_5_completed) $roomsCompleted++;
        
        $userScore = $progress->total_score; 

        $allProgress = \App\Models\GameProgress::with('user')
            ->orderByDesc('total_score')
            ->get();

        $userRank = $allProgress->search(function($p) {
            return $p->user_id == auth()->id();
        }) + 1;

        $leaderboard = $allProgress->take(10);

        return view('dashboard', compact('progress', 'roomsCompleted', 'userScore', 'userRank', 'leaderboard'));
    })->name('dashboard');

    // ==========================================
    // AGENT GAMEPLAY ROUTES
    // ==========================================
    Route::get('/mission', [AgentController::class, 'missionHub'])->name('agent.mission');
    Route::get('/mission/certificate', [AgentController::class, 'viewCertificate'])->name('agent.certificate');

    // Level 1
    Route::get('/mission/level1', [AgentController::class, 'playLevelOne'])->name('agent.level1');
    Route::get('/mission/level1/complete', [AgentController::class, 'completeLevelOne'])->name('agent.level1.complete');

    // Level 2
    Route::get('/mission/level2', [AgentController::class, 'playLevelTwo'])->name('agent.level2');
    Route::get('/mission/level2/complete', [AgentController::class, 'completeLevelTwo'])->name('agent.level2.complete');

    // Level 3
    Route::get('/mission/level3', [AgentController::class, 'playLevelThree'])->name('agent.level3');
    Route::get('/mission/level3/complete', [AgentController::class, 'completeLevelThree'])->name('agent.level3.complete');

    // Level 4
    Route::get('/mission/level4', [AgentController::class, 'playLevelFour'])->name('agent.level4');
    Route::get('/mission/level4/complete', [AgentController::class, 'completeLevelFour'])->name('agent.level4.complete');

    // Level 5
    Route::get('/mission/level5', [AgentController::class, 'playLevelFive'])->name('agent.level5');
    Route::get('/mission/level5/complete', [AgentController::class, 'completeLevelFive'])->name('agent.level5.complete');

    Route::get('/agent/arcade/{id}/certificate', [AgentController::class, 'arcadeCertificate'])->name('agent.arcade.certificate');

    // ==========================================
    // ADMIN / DIRECTOR ROUTES
    // ==========================================
    Route::get('/admin/levels', function () { return view('admin-levels'); })->name('admin.levels');
    
    // Admin Level Configuration
    Route::get('/admin/level1', [AdminLevelController::class, 'showLevelOne'])->name('admin.level1');
    Route::post('/admin/level1/store', [AdminLevelController::class, 'storeLevelOne'])->name('admin.level1.store');
    Route::delete('/admin/level1/destroy/{id}', [AdminLevelController::class, 'destroyLevelOne'])->name('admin.level1.destroy');

    Route::get('/admin/level2', [AdminLevelController::class, 'showLevelTwo'])->name('admin.level2');
    Route::post('/admin/level2/store', [AdminLevelController::class, 'storeLevelTwo'])->name('admin.level2.store');
    Route::post('/admin/level2/update/{id}', [AdminLevelController::class, 'updateLevelTwo'])->name('admin.level2.update');
    Route::delete('/admin/level2/destroy/{id}', [AdminLevelController::class, 'destroyLevelTwo'])->name('admin.level2.destroy');

    Route::get('/admin/level3', [AdminLevelController::class, 'showLevelThree'])->name('admin.level3');
    Route::post('/admin/level3/store', [AdminLevelController::class, 'storeLevelThree'])->name('admin.level3.store');
    Route::post('/admin/level3/update/{id}', [AdminLevelController::class, 'updateLevelThree'])->name('admin.level3.update');
    Route::delete('/admin/level3/destroy/{id}', [AdminLevelController::class, 'destroyLevelThree'])->name('admin.level3.destroy');

    Route::get('/admin/level4', [AdminLevelController::class, 'showLevelFour'])->name('admin.level4');
    Route::post('/admin/level4/store', [AdminLevelController::class, 'storeLevelFour'])->name('admin.level4.store');
    Route::post('/admin/level4/update/{id}', [AdminLevelController::class, 'updateLevelFour'])->name('admin.level4.update');
    Route::delete('/admin/level4/destroy/{id}', [AdminLevelController::class, 'destroyLevelFour'])->name('admin.level4.destroy');

    Route::get('/admin/level5', [AdminLevelController::class, 'showLevelFive'])->name('admin.level5');
    Route::post('/admin/level5/store', [AdminLevelController::class, 'storeLevelFive'])->name('admin.level5.store');
    Route::post('/admin/level5/update/{id}', [AdminLevelController::class, 'updateLevelFive'])->name('admin.level5.update');
    Route::delete('/admin/level5/destroy/{id}', [AdminLevelController::class, 'destroyLevelFive'])->name('admin.level5.destroy');
    Route::post('/admin/level5/settings', [AdminLevelController::class, 'updateLevelFiveSettings'])->name('admin.level5.settings');
    
    // Level 6 Arcade Admin Routes
    Route::get('/admin/level6', [AdminLevelController::class, 'showLevelSix'])->name('admin.level6');
    Route::post('/admin/level6/store', [AdminLevelController::class, 'storeLevelSix'])->name('admin.level6.store');
    Route::post('/admin/level6/update/{id}', [AdminLevelController::class, 'updateLevelSix'])->name('admin.level6.update');
    Route::delete('/admin/level6/destroy/{id}', [AdminLevelController::class, 'destroyLevelSix'])->name('admin.level6.destroy');
    
    // 🔥 S.H.I.E.L.D CUSTOM BRANCHING MODULE ROUTES 🔥
    Route::get('/branching-hub', [AgentController::class, 'branchingHub'])->name('agent.branching-hub');
    Route::get('/play/room/{room}', [AgentController::class, 'playRoom'])->name('agent.play.room');
    Route::post('/play/room/{room}', [AgentController::class, 'submitRoomAnswer'])->name('agent.play.room.submit');
    Route::get('/play/room/{room}/q/{question}', [AgentController::class, 'playRoomQuestion'])->name('agent.play.room.question');
    Route::get('/play/room/{room}/complete', [AgentController::class, 'completeRoom'])->name('agent.play.room.complete');

    // Arcade Gameplay Routes
    Route::get('/mission/arcade', [AgentController::class, 'arcadeHub'])->name('agent.arcade');
    Route::get('/mission/arcade/play/{id}', [AgentController::class, 'playMiniGame'])->name('agent.arcade.play');
    Route::post('/mission/arcade/complete/{id}', [AgentController::class, 'completeMiniGame'])->name('agent.arcade.complete');
});

// Password Reset Routes
Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->middleware('guest')->name('password.request');
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => 'Recovery link transmitted to your comms.'])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        }
    );

   return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', 'Authorization Key successfully updated. You may now log in.')
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.reset.submit');

// ==========================================
// SCENARIO BUILDER ROUTES
// ==========================================
// 🔥 DIKEMASKINI: /builder kini menghala ke AdminController (Green/Gold Dashboard) 🔥
Route::get('/builder', [AdminController::class, 'index'])->name('admin.builder.index');
Route::get('/builder/modules', [GameBuilderController::class, 'modules'])->name('admin.builder.modules');

// ⚠️ IMPORTANT: 'create' must be ABOVE '{room}'
Route::get('/builder/room/create', [GameBuilderController::class, 'createRoom'])->name('admin.builder.room.create');
Route::post('/builder/room', [GameBuilderController::class, 'storeRoom'])->name('admin.builder.room.store');

// Room Specific Routes
Route::get('/builder/room/{room}', [GameBuilderController::class, 'buildRoom'])->name('admin.builder.show'); 
Route::get('/builder/room/{room}/edit', [GameBuilderController::class, 'editRoom'])->name('admin.builder.room.edit');
Route::put('/builder/room/{room}', [GameBuilderController::class, 'updateRoom'])->name('admin.builder.room.update');
Route::delete('/builder/room/{room}', [GameBuilderController::class, 'destroyRoom'])->name('admin.builder.room.destroy');
Route::get('/builder/room/{room}/map', [GameBuilderController::class, 'visualMap'])->name('admin.builder.map');

// Node / Question Routes
Route::post('/builder/room/{room}/node', [GameBuilderController::class, 'storeNode'])->name('admin.builder.node.store');
Route::get('/builder/room/{room}/node/create', [GameBuilderController::class, 'createNode'])->name('admin.builder.node.create');
Route::put('/builder/node/{question}', [GameBuilderController::class, 'updateNode'])->name('admin.builder.node.update');
Route::delete('/builder/node/{question}', [GameBuilderController::class, 'destroyNode'])->name('admin.builder.node.destroy');

// Choice Routes
Route::get('/builder/node/{question}/choice/create', [GameBuilderController::class, 'createChoice'])->name('admin.builder.choice.create');
Route::post('/builder/node/{question}/choice', [GameBuilderController::class, 'storeChoice'])->name('admin.builder.choice.store');
Route::post('/builder/choice/{option}/link', [GameBuilderController::class, 'linkChoice'])->name('admin.builder.choice.link');
Route::delete('/builder/choice/{option}', [GameBuilderController::class, 'destroyChoice'])->name('admin.builder.choice.destroy');

Route::get('/builder/node/{question}/edit', [GameBuilderController::class, 'editNode'])->name('admin.builder.node.edit');
Route::put('/builder/node/{question}', [GameBuilderController::class, 'updateNode'])->name('admin.builder.node.update');
Route::delete('/builder/node/{question}', [GameBuilderController::class, 'destroyNode'])->name('admin.builder.node.destroy');

Route::get('/builder/export-hub', function () {
    return view('admin.builder.export_hub'); 
})->name('builder.export.hub');


// ==========================================
// 🔥 LALUAN KAWALAN ADMIN (TELAH DISATUKAN) 🔥
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // 🔥 DIKEMASKINI: /admin kini menghala ke GameBuilderController (Purple Dashboard) 🔥
    Route::get('/', [GameBuilderController::class, 'index'])->name('dashboard');

    // 🗂️ LALUAN PENGURUSAN GALERI INTEL 🗂️
    Route::get('/gallery', [InfoGalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [InfoGalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery/{id}', [InfoGalleryController::class, 'destroy'])->name('gallery.destroy');

    Route::get('/gallery/{id}/edit', [InfoGalleryController::class, 'edit'])->name('gallery.edit');
    Route::patch('/gallery/{id}', [InfoGalleryController::class, 'update'])->name('gallery.update');
    
    // Kawalan Pengguna (Menggunakan JSON Response sepenuhnya)
    Route::patch('/users/{user}/reset', [AdminController::class, 'resetScore'])->name('users.reset');
    Route::patch('/users/{user}/toggle-suspend', [AdminController::class, 'toggleSuspend'])->name('users.toggle-suspend');
    Route::delete('/users/{user}/delete', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // 2 Laluan yang dipindahkan dari AdminDashboardController lama
    Route::get('/analytics/{room?}', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/export-log-lama', [AdminController::class, 'exportLogs'])->name('export');

    Route::get('rooms/{room}/visual-map', [RoomController::class, 'visualMap'])->name('rooms.visual-map');
    Route::get('rooms/{room}/visual-map-fullscreen', [RoomController::class, 'visualMapFullscreen'])->name('rooms.visualMapFullscreen');

    // 💡 LALUAN HAB EKSPORT BAHARU 💡
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index'); 
        Route::get('/summary', [ExportController::class, 'summary'])->name('summary'); 
        Route::get('/detailed', [ExportController::class, 'detailed'])->name('detailed'); 
        Route::get('/charts', [ExportController::class, 'charts'])->name('charts'); 
    });

    // Laluan Sumber (CRUD)
    Route::resource('rooms', RoomController::class);
    Route::resource('rooms.questions', QuestionController::class);
    Route::resource('questions.options', OptionController::class);
});

Route::post('/log-room-failure', [\App\Http\Controllers\AgentController::class, 'logFailure'])->name('agent.log_failure');

/*
|--------------------------------------------------------------------------
| Director / Admin Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->group(function () {
    
    // 1. Admin Level Configurations
    Route::get('/admin/levels', function () { return view('admin-levels'); })->name('admin.levels');
    // ... Move ALL your /admin/level1 through /admin/level6 routes here ...

    // 2. Scenario Builder Routes
    Route::get('/builder', [AdminController::class, 'index'])->name('admin.builder.index');
    Route::get('/builder/modules', [GameBuilderController::class, 'modules'])->name('admin.builder.modules');
    // ... Move ALL your /builder/room and /builder/node routes here ...
    Route::get('/builder/export-hub', function () { return view('admin.builder.export_hub'); })->name('builder.export.hub');

    // 3. Admin Control Prefix Group (Gallery, Users, Analytics, Export)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [GameBuilderController::class, 'index'])->name('dashboard');
        
        Route::get('/gallery', [InfoGalleryController::class, 'index'])->name('gallery.index');
        // ... Move ALL your gallery, users reset/suspend, analytics, and export routes here ...
        
        Route::resource('rooms', RoomController::class);
        Route::resource('rooms.questions', QuestionController::class);
        Route::resource('questions.options', OptionController::class);
    });

});