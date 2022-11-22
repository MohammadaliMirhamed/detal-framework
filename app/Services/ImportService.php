<?php

namespace App\Services;

use App\Foundations\Contracts\ReaderInterface;
use App\Foundations\Helpers\General;
use App\Models\User;
use App\Models\UserMeta;


class ImportService
{
    /**
     * Handle importing users from files
     * 
     * @return array
     */
    public function users(ReaderInterface $reader): array
    {
        // parse uploaded file
        $parsedFileEntry = $reader->parse();

        // statistic of the import procedure
        $statistic = [
            'imported' => 0,
            'updated' => 0,
            'total' => count($parsedFileEntry)
        ];
        
        $userEntryKeys = ['Name', 'Mobile Number', 'Email Address', 'Birth Date'];

        // iterate imported file rows
        foreach ($parsedFileEntry as $rows) {
            
            $row = [
                'user' => [],
                'meta' => [],
            ];

            // iterate each element of row for detect the right table for them
            foreach ($rows as $key => $value) {
                $correctArrayObject = in_array(trim($key), $userEntryKeys) ? 'user' : 'meta';
                $row[$correctArrayObject] = array_merge($row[$correctArrayObject], [trim($key) => $value]);                
            }

            $userModel = new User();
            $userMetaModel = new UserMeta();

            // prepare where clue params by consider the email , mobile feilds
            $whereCluseParams = [
                'column' => isset($row['user']['Mobile Number']) ? 'mobile' :  'email',
                'value' => $row['user']['Mobile Number'] ?? $row['user']['Email Address']
            ];

            // if the brith data has been set then convert it for saving in db
            if (isset($row['user']['Birth Date'])) {
                $date = explode('-', $row['user']['Birth Date']);
                $date = General::jalaliToGregorian($date[0], $date[1], $date[2], '-');
            }
           
            // if user does exist in db
            if (
                $user = $userModel->select(
                    "WHERE {$whereCluseParams['column']} = :{$whereCluseParams['column']}",
                    [$whereCluseParams['column'] => $whereCluseParams['value']])
                ->first()
            ) {
                // insert new user's meta
                foreach ($row['meta'] as $key => $value) {
                    if ($userMetaModel->rowsCount('WHERE `key` = :key', ['key' => General::metaKeyGenerator($key)]) == 0) {
                        $userMetaModel->insert([
                            'user_id' => $user->id,
                            'key' => General::metaKeyGenerator($key),
                            'value' => $value,
                        ]);
                    }
                }
                
                $statistic['updated'] += 1;

            } else {
                
                // insert new user
                $userId = $userModel->insert([
                    'name'  => $row['user']['Name'] ?? '',
                    'email'  => $row['user']['Email Address'] ?? null,
                    'mobile'  => $row['user']['Mobile Number'] ?? null,
                    'birth_date'  => $date ?? ''
                ]);

                
                // insert new user's meta
                foreach ($row['meta'] as $key => $value) {
                    $userMetaModel->insert([
                        'user_id' => $userId,
                        'key' => General::metaKeyGenerator($key),
                        'value' => $value,
                    ]);
                }

                $statistic['imported'] += 1;
            }
        }
        
        return ['statistic' => $statistic];
    }
}