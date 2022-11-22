<?php

namespace App\Services;

use App\Foundations\Helpers\General;
use App\Models\User;
use App\Models\UserMeta;

class UserService
{   
    /**
     * Handle get all User with it's metas
     * 
     * @return array
     */
    public function getAll(): array
    {
        $users = (new User())->select()->toArray();

        // geting User meta for each recored
        foreach ($users as $index => $row) {
            $users[$index]['userMetas'] = $this->getMeta($row['id']);
        }

        return $users;
    }

    /**
     * Handle find a User by id
     * 
     * @param int $id
     * @return array
     */
    public function find(int $id): array
    {
        $user = (new User())->find($id);

        if($user) {
            $user['userMetas'] = $this->getMeta($id);
        }

        return $user;
    }

    /**
     * Handle create a User
     * 
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        
        $primaryUserFields = ['name', 'email', 'mobile', 'password', 'birth_date'];
        
        // models
        $userModel = new User();
        $userMetaModel = new UserMeta();

        [$year, $month, $day] = explode('/', $data['birth_date']);

        // create user
        $user_id = $userModel->insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => General::hash($data['password']),
            'birth_date' => General::jalaliToGregorian($year, $month, $day, '-'),
        ]);

        // insert user meta
        foreach ($data as $key => $value) {
            if (!in_array($key, $primaryUserFields)) {
                $userMetaModel->insert([
                    'user_id' => $user_id,
                    'key' => General::metaKeyGenerator($key),
                    'value' => $value
                ]);
            }
        }
        return ['message' => 'User created', 'user_id' => $user_id];
    }

    /**
     * Handle delete a User by id
     * 
     * @param int $id
     * @return array
     */
    public function delete(int $id): array
    {
        $user = new User();        
        $user->delete("WHERE id = :id", ['id' => $id]);

        return ['message' => 'deleted'];
    }

    /**
     * Handle user's metas
     * 
     * @return array
     */
    private function getMeta($id): array
    {
        $finalUserMetas = [];
        $userMetas = (new UserMeta())->find($id);
        
        foreach($userMetas as $userMeta) {
            $finalUserMetas = array_merge($finalUserMetas, [$userMeta['key'] => $userMeta['value']]);
        }
        
        return $finalUserMetas;
    }

}