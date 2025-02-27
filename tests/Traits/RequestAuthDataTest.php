<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait RequestAuthDataTest
{
    protected function sendAuthRequest(string $method, string $route, array $data = [])
    {
        $token = $this->getTokenJwt();

        $header = [
            'Authorization' => 'Bearer ' . $token
        ];

        return $this->json($method, $route, $data, $header);
    }

    protected function sendRequest(string $method, string $route, array $data = [], bool $auth = true)
    {
        if ($auth) {
            $token = $this->getTokenJwt();
            $header = [
                'Authorization' => 'Bearer ' . $token
            ];
        }
        return $this->json($method, $route, $data, $header ?? []);
    }

    public function getTokenJwt()
    {
        $user = User::find(1);
        return Auth::fromUser($user);
    }
}
