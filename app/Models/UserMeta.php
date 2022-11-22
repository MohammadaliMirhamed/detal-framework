<?php

namespace App\Models;

use App\Configs\Database;

class UserMeta extends Database
{
    protected $table = "user_meta";

    protected $fields = [];

    public function find(int $id)
    {
        return $this->select('WHERE user_id = :userId', ['userId' => $id])->toArray();
    }
}