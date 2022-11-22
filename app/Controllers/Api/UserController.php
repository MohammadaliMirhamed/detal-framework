<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Configs\Request;
use App\Configs\Response;
use App\Configs\Validation;
use App\Services\UserService;

class UserController extends BaseController
{
    /**
     * Return all Users
     * 
     * @return Response 
     */
    public function all(UserService $userService): Response
    {
        
        $response = [
            'body' => $userService->getAll(),
            'code' => Response::HTTP_OK
        ];

        return $this->response($response['body'], $response['code']);
    }

    /**
     * Find a User by id
     * 
     * @return Response
     */
    public function find(UserService $userService, $id): Response
    {
        $response = [
            'body' => $userService->find($id),
            'code' => Response::HTTP_OK
        ];

        return $this->response($response['body'], $response['code']);
    }

    /**
     * Create a User
     * 
     * @return Response
     */
    public function create(UserService $userService, Request $request,Validation $validation): Response
    {
        // validations
        $validation->name('name')->value($request->get('name'))->required();
        $validation->name('email')->value($request->get('email'))->isDuplicate('users:email')->isEmail()->required();
        $validation->name('mobile')->value($request->get('mobile'))->isDuplicate('users:mobile')->pattern('tel')->required();
        $validation->name('password')->value($request->get('password'))->required();
        $validation->name('birth_date')->value($request->get('birth_date'))->required();

        if (!$validation->isSuccess()) {
            $response = [
                'body' => ['errors' => $validation->getErrors()],
                'code' => Response::HTTP_BAD_REQUEST
            ];
        } else {
            $response = [
                'body' => $userService->create($request->all()),
                'code' => Response::HTTP_OK
            ];
        }
        
        return $this->response($response['body'], $response['code']);
    }

    /**
     * Delete User by id
     * 
     * @return Response
     */
    public function delete(UserService $userService, Validation $validation, $id): Response
    {
        // validation
        $validation->name('user_id')->value($id)->isInt()->exist('users:id')->required();

        if (!$validation->isSuccess()) {
            $response = [
                'body' => ['errors' => $validation->getErrors()],
                'code' => Response::HTTP_BAD_REQUEST
            ];
        } else {
            $response = [
                'body' => $userService->delete($id),
                'code' => Response::HTTP_OK
            ];
        }
        
        return $this->response($response['body'], $response['code']);
    }
}