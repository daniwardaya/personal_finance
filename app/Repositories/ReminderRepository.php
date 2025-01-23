<?php

namespace App\Repositories;

use App\Models\Reminder;

class ReminderRepository
{
    public function create(array $data): Reminder
    {
        return Reminder::create($data);
    }
}