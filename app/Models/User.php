<?php

namespace App\Models;

use App\Configs\Database;

class User extends Database
{
    protected $table = 'users';

    protected $fields = ['id','name','email', 'mobile', 'birth_date'];

    public function find(int $id)
    {
        return $this->select('WHERE id = :id', ['id' => $id])->toArray()[0];
    }
}