<?php

namespace TaskManager\Models\Users;

use TaskManager\Exceptions\InvalidArgumentException;
use TaskManager\Models\ActiveRecordEntity;
use TaskManager\Models\Users\User;
use TaskManager\Services\Db;

class User extends ActiveRecordEntity
{
	protected $login;         // логин пользователя
	protected $passwordHash;  // хэш пароля пользователя
	protected $authToken;     // токен авторизации

	public function getLogin(): string
	{
		return $this->nloginame;
	}

	public function getPasswordHash(): string
	{
		return $this->passwordHash;
	}

	public function getAuthToken(): string
	{
		return $this->authToken;
	}

	protected static function getTableName(): string
	{
		return 'users';
	}

	public static function findOneByColumn(string $columnName, $value): ?self
	{
	    $db = Db::getInstance();
	    $result = $db->query(
	        'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value LIMIT 1;',
	        [':value' => $value],
	        static::class
	    );
	    if ($result === []) {
	        return null;
	    }
	    return $result[0];
	}

	public static function login(array $loginData): User
	{
	    if (empty($loginData['login'])) {
	        throw new InvalidArgumentException('Не передан логин');
	    }

	    if (empty($loginData['password'])) {
	        throw new InvalidArgumentException('Не передан пароль');
	    }

	    $user = User::findOneByColumn('login', $loginData['login']);
	    if ($user === null) {
	        throw new InvalidArgumentException('Нет пользователя с таким логином');
	    }

	    if (!password_verify($loginData['password'], $user->getPasswordHash())) {
	        throw new InvalidArgumentException('Неправильный пароль');
	    }

	    $user->refreshAuthToken();
	    $user->save();

	    return $user;
	}

	private function refreshAuthToken()
	{
	    $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
	}
}