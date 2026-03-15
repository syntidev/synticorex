<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\NewTicketNotification;

class SupportTicketObserver
{
    public function created(SupportTicket $ticket): void
    {
        $adminUser = User::where('is_admin', true)->first();

        if ($adminUser !== null) {
            $adminUser->notify(new NewTicketNotification($ticket));
        }
    }
}
