
<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use App\Models\User;
use App\Configs\Env;
use App\Foundations\Helpers\General;
use Faker\Factory;
use GuzzleHttp\Promise\Create;

class UserControllerTest extends TestCase
{

    protected $client;

    protected $faker;

    public function setup() :void
    {
        (new Env(realpath(".") . '/.env'))->load();

        $this->client = new Client(['base_uri' => getenv('APPLICATION_URL')]);

        $this->faker = Factory::create();
    }

    public function testShowAllUsers()
    {       
        $response = $this->client->request('GET', '/api/users');
                        
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateUser()
    {
        
        $firstCountOfUser = (new User())->rowsCount();

        $response = $this->CreateNewUser();
    
        $secondCountOfUser = (new User())->rowsCount();
        $result = json_decode($response->getBody(), true);

        $this->assertEquals(($firstCountOfUser + 1), $secondCountOfUser);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($result['data']['message'], 'User created');
    }

    public function testDeleteUser()
    {
        $response = $this->CreateNewUser();

        $userId = (new User())->select()->first()->id;

        if (!$userId) {
            $response = $this->CreateNewUser();
            $result = json_decode($response->getBody(), true);
            $userId = $result['data']['user_id'];
        }

        $response = $this->client->request('DELETE', '/api/users/' . $userId);
                        
        $result = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($result['data'], ['message' => 'deleted']);
    }

    private function CreateNewUser() {
        $response = $this->client->request(
            'POST',
            '/api/users',
            [
                'multipart' => [
                    [
                        'name'     => 'name',
                        'contents' => $this->faker->name()
                    ],
                    [
                        'name'     => 'email',
                        'contents' => $this->faker->email()
                    ],
                    [
                        'name'     => 'mobile',
                        'contents' => "0912345" . rand(100, 999)
                    ],
                    [
                        'name'     => 'password',
                        'contents' => General::hash(rand(10000000, 99999999))
                    ],
                    [
                        'name'     => 'birth_date',
                        'contents' => rand(1340,1390) .'/'. rand(1,12) .'/'. rand(1,30)
                    ],
                ]
            ]
        );

        return $response;
    }
}