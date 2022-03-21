<?php

namespace Routes\ApiRoute\AuthRoute;

use JetBrains\PhpStorm\Pure;
use Packages\CurrentUserService\CurrentUserService;
use Packages\DBConnection\DBConnection;
use Packages\Encryptor\Encryptor;
use Packages\HttpDataManager\HttpData;
use Packages\Mailer\Mailer;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Route\Route;
use Packages\Route\RouteResponse;
use Packages\UserRepository\UserDTO\UserDTO;
use Packages\UserRepository\UserRepository;
use Packages\UserService\UserEntity\Role\Role;
use Packages\UserService\UserService;

class AuthRoute extends Route
{
    const RESPONSE_CODE_DUPLICATE_LOGIN = 409;
    const RESPONSE_CODE_EMAIL_EXISTS = 409;
    const RESPONSE_CODE_EMAIL_NOT_EXISTS = 409;
    const RESPONSE_CODE_WRONG_LOGIN_PASSWORD = 401;
    const RESPONSE_CODE_UNAUTHORIZED = 401;
    const RESPONSE_CODE_ACCESS_DENIED = 401;

    #[Pure] public function __construct(array $urls = [])
    {
        parent::__construct($urls);
    }

    protected function getSubRoutes(): array
    {
        return [];
    }

    protected function getMethods(): array
    {
        return [
            'login' => 'login',
            'register' => 'registerNewUser',
            'send_reset_password' => 'sendResetPassword',
            'reset_password' => 'resetUserPassword',
            'change_email' => 'changeUserEmail',
            'logout' => 'logout',
            'import_users' => 'importUsers',
        ];
    }

    public function login(HttpData $httpData): RouteResponse
    {
        $postData = $httpData->getPostData();

        if (!isset($postData['userData'])) {
            return $this->getResponseWrongData();
        }
        $userData = json_decode($postData['userData'], JSON_OBJECT_AS_ARRAY);

        if (
            !isset($userData['login']) || $userData['login'] === ''
            || !isset($userData['password'])  || $userData['password'] === ''
        ) {
            return $this->getResponseWrongData();
        }
        $login = $userData['login'];
        $password = $userData['password'];

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $userRepository = new UserRepository($connection, $queryBuilder);
        $userService = new UserService($userRepository);

        $user = $userService->getUserByLogin($login);
        if (!$user || !$user->verifyPassword($password)) {
            return new RouteResponse(['message' => 'wrong login or password'], self::RESPONSE_CODE_WRONG_LOGIN_PASSWORD);
        }

        $currentUserService = new CurrentUserService();
        $currentUserService->login($user);

        return new RouteResponse([
            'login' => $user->getLogin(),
            'role' => $user->getRole()->value,
        ], 200);
    }

    public function registerNewUser(HttpData $httpData): RouteResponse
    {
        $postData = $httpData->getPostData();

        if (!isset($postData['userData'])) {
            return $this->getResponseWrongData();
        }
        $userData = json_decode($postData['userData'], JSON_OBJECT_AS_ARRAY);
        if (
            !isset($userData['login']) || $userData['login'] === ''
            || !isset($userData['password'])  || $userData['password'] === ''
            || !isset($userData['name'])  || $userData['name'] === ''
            || !isset($userData['middleName'])  || $userData['middleName'] === ''
            || !isset($userData['lastName'])  || $userData['lastName'] === ''
        ) {
            return $this->getResponseWrongData();
        }
        $login = $userData['login'];
        $password = $userData['password'];
        $name = $userData['name'];
        $middleName = $userData['middleName'];
        $lastName = $userData['lastName'];

        $userDataDTO = [
            'login' => $login,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'first_name' => $name,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'role_id' => Role::USER->value,
        ];
        $userDTO = new UserDTO($userDataDTO);

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $userRepository = new UserRepository($connection, $queryBuilder);

        if ($userRepository->getByLogin($login)) {
            return new RouteResponse(['message' => 'login exists'], self::RESPONSE_CODE_DUPLICATE_LOGIN);
        }

        $userRepository->add($userDTO);
        return $this->getResponseOk();
    }

    public function sendResetPassword(HttpData $httpData): RouteResponse
    {
        $postData = $httpData->getPostData();

        if (!isset($postData['email']) || $postData['email'] === '') {
            return $this->getResponseWrongData();
        }
        $email = $_POST['email'];

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $userRepository = new UserRepository($connection, $queryBuilder);
        $userService = new UserService($userRepository);

        $user = $userService->getByEmail($email);
        if (!$user) {
            return new RouteResponse(['message' => 'email not exists'], self::RESPONSE_CODE_EMAIL_NOT_EXISTS);
        }

        $encryptor = new Encryptor();
        $password = $user->getPassword();
        $login = $user->getLogin();
        $token = urlencode($encryptor->encrypt(json_encode(['login' => $login, 'password' => $password])));
        $getParameter = "?token=$token";

        $mailer = new Mailer();
        $mailer->mail('resetPasswordConfirmTemplate', $email, ['getParameter' => $getParameter]);

        return $this->getResponseOk();
    }

    public function resetUserPassword(HttpData $httpData): RouteResponse
    {
        $postData = $httpData->getPostData();

        if (
            !isset($postData['password']) || $postData['password'] === ''
            && !isset($postData['token']) || $postData['token'] === ''
        ) {
            return $this->getResponseWrongData();
        }

        $encryptor = new Encryptor();
        $token = json_decode($encryptor->decrypt(urldecode($postData['token'])), JSON_OBJECT_AS_ARRAY);

        $newPassword = $postData['password'];
        $login = $token['login'];
        $oldHashedPassword = $token['password'];

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $userRepository = new UserRepository($connection, $queryBuilder);

        $user = $userRepository->getByLogin($login);
        if (!$user || $user->getPassword() !== $oldHashedPassword) {
            return new RouteResponse(['message' => 'access denied'], self::RESPONSE_CODE_ACCESS_DENIED);
        }

        $userRepository->updateByLogin($login, ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);

        return $this->getResponseOk();
    }

    public function changeUserEmail(HttpData $httpData): RouteResponse
    {
        $postData = $httpData->getPostData();

        if (!isset($postData['email']) || $postData['email'] === '') {
            return $this->getResponseWrongData();
        }
        $email = $_POST['email'];

        $currentUserService = new CurrentUserService();
        if (!$currentUserService->isAuthed()) {
            return new RouteResponse(['message' => 'not authorized'], self::RESPONSE_CODE_UNAUTHORIZED);
        }
        $login = $currentUserService->getLogin();

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $userRepository = new UserRepository($connection, $queryBuilder);

        if ($userRepository->getByEmail($email)) {
            return new RouteResponse(['message' => 'email exists'], self::RESPONSE_CODE_EMAIL_EXISTS);
        }

        $userRepository->updateByLogin($login, ['email' => $email]);

        return $this->getResponseOk();
    }

    public function logout(HttpData $httpData): RouteResponse
    {
        $currentUserService = new CurrentUserService();
        $currentUserService->logout();
        return new RouteResponse(['message' => 'ok'], 200);
    }
}