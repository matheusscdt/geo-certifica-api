<?php

namespace App\Jobs;

use App\Enums\RoutingKeyEnum;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $routingKey;
    public $data;

    protected function userService(): UserService
    {
        return app(UserService::class);
    }

    public function __construct($data, $routingKey)
    {
        $this->data = $data;
        $this->routingKey = $routingKey;
    }

    public function handle(): void
    {
        $routingKey = RoutingKeyEnum::from($this->routingKey);
//        $this->userService()->criarPelaQueue($this->data, $routingKey);
//        $this->userService()->editarPelaQueue($this->data, $routingKey);
    }
}
