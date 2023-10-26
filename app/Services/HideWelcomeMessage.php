<?php

namespace App\Services;

class HideWelcomeMessage extends BaseService
{
    public function execute(): void
    {
        auth()->user()->welcome_message_displayed = false;
        auth()->user()->save();
    }
}
