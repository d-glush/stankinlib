<pre>
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\UserRepository\UserDTO\UserDTO;
use Packages\UserRepository\UserRepository;
use Packages\UserService\UserEntity\UserEntity;

$userDTO = new UserDTO([
    'id' => null,
    'password' => 2,
    'login' => 'login',
    'first_name' => 'firstname',
    'last_name' => 'lastname',
    'middle_name' => 'middleware',
    'registration_date' => null,//'2022-03-13 21:09:12',
    'last_auth_date' => null,
    'role_id' => 2,
]);

$userEntity = new UserEntity($userDTO);

$connection = new DBConnection();
$queryBuilder = new QueryBuilder();
$userRepository = new UserRepository($connection, $queryBuilder);

$userRepository->addUser($userDTO);

