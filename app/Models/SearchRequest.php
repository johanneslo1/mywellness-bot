<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Support\Carbon|null $last_check_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property array                           $params
 * @property string                          $email
 * @property boolean                         $active
 */
class SearchRequest extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $casts = [
        'params' => 'array',
        'telegram_token' => 'encrypted',
        'email' => 'encrypted',
        'last_check_at' => 'datetime',
    ];



    public function routeNotificationForMail(): string
    {
        return $this->email;
    }



    public function routeNotificationForTelegram()
    {
        return 510005475;
    }

    // use NotificationChannels\Telegram\TelegramUpdates;
    //
    //// Response is an array of updates.
    //$updates = TelegramUpdates::create()
    //    // (Optional). Get's the latest update. NOTE: All previous updates will be forgotten using this method.
    //    // ->latest()
    //
    //    // (Optional). Limit to 2 updates (By default, updates starting with the earliest unconfirmed update are returned).
    //    ->limit(2)
    //
    //    // (Optional). Add more params to the request.
    //    ->options([
    //        'timeout' => 0,
    //    ])
    //    ->get();
    //
    //if($updates['ok']) {
    //    // Chat ID
    //    $chatId = $updates['result'][0]['message']['chat']['id'];
    //
    //dd($chatId);
    //}
    public function free_time_slots()
    {
        return $this->hasMany(FreeTimeSlot::class);
    }
}
