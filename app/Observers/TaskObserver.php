<?php

namespace App\Observers;

use App\Models\Default\Task;
use Illuminate\Http\Request;

class TaskObserver
{
    public function creating(Request $request)
    {
        dd($request);
        var_dump($request);
    }
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    public function deleting(Request $request)
    {
        dd($request);
        var_dump($request);
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        dd($task);
        var_dump($task);
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
