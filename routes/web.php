<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\UniqueQueueJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

Route::get('/dispatch-job', function () {
    $data = ['order_id' => 123, 'amount' => 100];
    $uniqueKey = 'job:' . md5(json_encode($data));

    if (!Cache::has($uniqueKey)) {
        Cache::put($uniqueKey, true, 600);  // Lock for 10 minutes
        UniqueQueueJob::dispatch($data, $uniqueKey);

        Log::info('Unique job dispatched!', ['key' => $uniqueKey]);
        return 'Job dispatched!';
    } else {
        Log::warning('Job already exists!', ['key' => $uniqueKey]);
        return 'Job already queued!';
    }
});
