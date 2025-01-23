<?php

namespace App\Services;

use App\Repositories\ReminderRepository;

class ReminderService
{
    protected $repository;

    public function __construct(ReminderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createReminder(array $data)
    {
        // Business logic could go here, like sending notifications
        return $this->repository->create($data);
    }
}