<?php

use App\Models\Receiver;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    return redirect('/admin');
});

Route::get('track/{hash}', function(Request $request, $hash) {
    $receiver = Receiver::query()
        ->with(['campaign'])
        ->firstWhere('hash', $hash);
    if($receiver) {
        $data = [
            'last_opened_at' => now(),
        ];
        if(empty($receiver->first_opened_at)) {
            $data['first_opened_at'] = now();
        }
        $receiver->update($data);
        Visit::create([
            'receiver_id' => $receiver->getKey(),
            'campaign_id' => $receiver->campaign?->getKey(),
            'customer_id' => $receiver->campaign?->customer_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
    return response('', 200, [
        'Content-Type' => 'image/jpg',
        'Cache-Control' => 'no-store',
    ]);
})->name('tracking');

Route::get('unsuscribe/{hash}', function(Request $request, $hash){
    /** @var Receiver $receiver */
    $receiver = Receiver::query()
        ->with(['campaign'])
        ->firstWhere('hash', $hash);
    if($receiver) {
        $receiver->contact->update([
            'unsubscribed_at' => now(),
        ]);
    }
    return response(__('admin.unsubscribe_message'), 200);
})->name('unsubscribe');
