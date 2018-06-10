<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->get('/topics', function (Request $request) {
    $topics = \App\Topic::where('name','like','%'.$request->query('q').'%')
        ->get(['id','name']);
    return $topics;
});

Route::middleware('auth:api')->post('/question/follower', function (Request $request) {
    $user = Auth::guard('api')->user();
    $followed = $user->followed($request->get('question'));
    if ($followed) {
        return response()->json(['followed' => true]);
    }
    return response()->json(['followed' => false]);
});

Route::middleware('auth:api')->post('/question/follow', function (Request $request) {
    $user = Auth::guard('api')->user();
    $question = \App\Question::find($request->get('question'));
    $followed = $user->followThis($question->id);
    if (count($followed['detached']) > 0) {
        $question->decrement('followers_count');
        return response()->json(['followed' => false,'test' => $followed]);
    }
    $question->increment('followers_count');
    return response()->json(['followed' => true,'test' => $followed]);
});

Route::get('/user/followers/{id}','FollowersController@index');
Route::post('/user/follow','FollowersController@follow');
