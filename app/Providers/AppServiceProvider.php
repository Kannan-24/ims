<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(MessageSent::class, function(MessageSent $event){
            try {
                $to = $event->message->getTo();
                $mailableClass = null;
                if(isset($event->data['__laravel_notification'])) {
                    $mailableClass = get_class($event->data['__laravel_notification']);
                } elseif(isset($event->data['notification'])) {
                    $mailableClass = get_class($event->data['notification']);
                } elseif(isset($event->data['mailable'])) {
                    $mailableClass = get_class($event->data['mailable']);
                }
                EmailLog::create([
                    'mailable' => $mailableClass,
                    'subject' => $event->message->getSubject(),
                    'to' => collect($to ?? [])->keys()->implode(','),
                    'success' => true,
                    'meta' => [ 'logged_at' => now()->toDateTimeString() ]
                ]);
            } catch(\Throwable $e) { /* swallow logging errors */ }
        });
    }
}
