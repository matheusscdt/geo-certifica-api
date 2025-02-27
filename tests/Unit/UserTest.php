<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
    }

    public function test_fillable_attribute()
    {
        $fillable = [
            'pessoa_id',
            'email',
            'password',
            'ativo'
        ];

        $this->assertEquals($fillable, $this->user->getFillable());
    }

    public function test_casts_attribute()
    {
        $casts = [
            'id' => 'int',
            'pessoa_id' => 'int',
            'ativo' => 'bool',
            'deleted_at' => 'datetime'
        ];

        $this->assertEquals($casts, $this->user->getCasts());
    }
}
