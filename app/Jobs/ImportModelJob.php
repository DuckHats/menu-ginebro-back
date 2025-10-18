<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportModelJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected string $modelClass;

    protected array $data;

    public function __construct(string $modelClass, array $data)
    {
        $this->modelClass = $modelClass;
        $this->data = $data;
    }

    public function handle(): void
    {
        $model = new $this->modelClass;
        $model->importRow($this->data);
    }
}
