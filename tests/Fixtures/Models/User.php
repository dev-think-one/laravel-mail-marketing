<?php

namespace MailMarketing\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MailMarketing\Models\InMailMarketing;
use MailMarketing\Tests\Fixtures\Factories\UserFactory;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasFactory;
    use InMailMarketing;

    protected $table = 'users';

    protected $guarded = [];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
