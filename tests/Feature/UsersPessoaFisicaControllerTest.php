<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\RequestAuthDataTest;

class UsersPessoaFisicaControllerTest extends TestCase
{
    use RefreshDatabase, RequestAuthDataTest;

    public function test_deve_criar_user_pessoa_fisica()
    {
        $data = $this->getDataStore();
        $response = $this->store($data);
        $response->assertStatus(201);

        $content = $response->getOriginalContent();
        $this->assertNotNull($content);
        $this->assertEqualsIgnoringCase($data['email'], $content->email);
        $this->assertEqualsIgnoringCase(true, $content->ativo);
    }

    private function store(array $data): TestResponse
    {
        $router = route('users-pessoa-fisica.store');
        return $this->sendRequest('POST', $router, $data, false);
    }

    public function test_deve_alterar_user_pessoa_fisica()
    {
        $dataStore = $this->getDataStore();
        $responseStore = $this->store($dataStore);

        $dataUpdate = $this->getDataUpdate();
        $response = $this->update($responseStore->getOriginalContent()->id, $dataUpdate);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $this->assertNotNull($content);
        $this->assertEqualsIgnoringCase($dataUpdate['email'], $content->email);
        $this->assertEqualsIgnoringCase(true, $content->ativo);
    }

    private function update(int $id, array $data): TestResponse
    {
        $router = route('users-pessoa-fisica.update', $id);
        return $this->sendRequest('PUT', $router, $data);
    }

    private function getDataStore(): array
    {
        return [
            'nome' => 'Carlos Augusto Cavalcanti de Barros',
            'email' => 'ccavalcanti@grupoimagetech.com.br',
            'cpf' => '99103788172',
            'data_nascimento' => '1983-02-23',
            'password' => '123456',
        ];
    }

    private function getDataUpdate(): array
    {
        return [
            'nome' => 'Carlos Augusto Cavalcanti de Barros',
            'email' => 'ccavalcanti@grupoimagetech.com.br',
            'data_nascimento' => '1983-02-23',
            'password' => '123456',
        ];
    }
}
