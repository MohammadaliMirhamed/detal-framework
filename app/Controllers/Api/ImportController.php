<?php

namespace App\Controllers\Api;


use App\Controllers\BaseController;
use App\Configs\Request;
use App\Configs\Response;
use App\Configs\Validation;
use App\Configs\Storage;
use App\Services\ImportService;
use App\Foundations\Helpers\General;
use App\Foundations\Providers\FileReader\JsonFileReader;
use App\Foundations\Providers\FileReader\CsvFileReader;

class ImportController extends BaseController
{

    /**
     * file extension
     * 
     * @var array $fileExt
     */
    public array $fileExt;

    /**
     * Construct of class
     */
    public function __construct()
    {
        $this->fileExt = [
            'users' => ['json', 'csv']
        ];
    }

    /**
     * Handle importing users to system
     * 
     * @return Response
     */
    public function users(Request $request, Validation $validation, ImportService $importService): Response
    {
        $file = $request->file('users_file');

        // validation
        $validation->name('users_file')->file($file)->ext($this->fileExt['users'])->required();

        if (!$validation->isSuccess()) {
            $response = [
                'body' => ['errors' => $validation->getErrors()],
                'code' => Response::HTTP_BAD_REQUEST
            ];
        } else {

            //upload the file get file path to pass it to car service
            $uploadedFilePath = Storage::save(General::getStoragePath(), $file);

            // file reader options by ext
            $fileReaders = [
                'json' => new JsonFileReader($uploadedFilePath),
                'csv' =>  new CsvFileReader($uploadedFilePath),
            ];
            
            //handle the uploaded file
            $response = [
                'body' => $importService->users($fileReaders[strtolower(pathinfo($file['name'], PATHINFO_EXTENSION))]),
                'code' => Response::HTTP_OK
            ];
        }

        return $this->response($response['body'], $response['code']);
    }
}