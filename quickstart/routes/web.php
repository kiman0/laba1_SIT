<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Task;

use Illuminate\Support\Facades;

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

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', function () {
    if ((Auth::id())!=NULL)
    {

   // $tasks = Task::orderBy('created_at', 'asc')->where('us_id'==Auth::id())->get();

    $tasks = DB::table('tasks')->where('us_id', '=', Auth::id())->get();
    return view('tasks', [
        'tasks' => $tasks
    ]);
    }
    else
    {
        abort('404');
    };
});

Route::post('/task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Task;
    $task->name = $request->name;
    $task->us_id=Auth::id();
    $task->save();

    return redirect('/');
});

Route::delete('/task/{id}', function ($id) {
    Task::findOrFail($id)->delete();

    return redirect('/');
});

Route::put('/task/{id}', function (Request $request) {
  //  Task::findOrFail($id)->delete();

    $post = Task::find($request->id);
    $post->name = $request->name;
    $post->save();

    return redirect('/');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
