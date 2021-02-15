<?php

namespace TaskManager\Models\Tasks;

use TaskManager\Exceptions\InvalidArgumentException;
use TaskManager\Models\ActiveRecordEntity;
use TaskManager\Models\Users\User;
use TaskManager\Models\Tasks\Task;
use TaskManager\Services\Constants;

class Task extends ActiveRecordEntity
{
	protected $authorName;   // имя автора задания
	protected $authorEmail;  // email автора задания
	protected $text;         // текст задания
	protected $status;       // статус задания
	protected $edited;       // отредактировано

	public function getName(): string
	{
		return $this->authorName;
	}

	public function setName(string $authorName): void
	{
		$this->authorName = $authorName;
	}

	public function getEmail(): string
	{
		return $this->authorEmail;
	}

	public function setEmail(string $authorEmail): void
	{
		$this->authorEmail = $authorEmail;
	}

	public function getText(): string
	{
		return $this->text;
	}

	public function setText(string $text): void
	{
		$this->text = $text;
	}

	public function getStatus(): string
	{
		return $this->status;
	}

	public function setStatus(string $status): void
	{
		$this->status = $status;
	}

	public function getEdited(): bool
	{
		return $this->edited;
	}

	public function setEdited(bool $editByAdmin): void
	{
		$this->edited = $editByAdmin;
	}

	protected static function getTableName(): string
	{
		return 'tasks';
	}

	public static function createFromArray(array $fields): Task
	{
	    if (empty($fields['name'])) {
	        throw new InvalidArgumentException('Не передано имя автора');
	    }

	    if (!preg_match('/^[a-zA-Z0-9а-яёЁА-Я]+$/u', $fields['name'])) {
            throw new InvalidArgumentException('Имя может состоять только из символов русского или латинского алфавита и цифр');
        }

	    if (empty($fields['email'])) {
	        throw new InvalidArgumentException('Не передан email автора');
	    }

	    if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
	        throw new InvalidArgumentException('Email некорректен');
	    }

	    if (empty($fields['text'])) {
	        throw new InvalidArgumentException('Не передан текст задания');
	    }

	    $newText = filter_var($fields['text'], FILTER_SANITIZE_SPECIAL_CHARS);

	    if (mb_strlen($newText) == 0 || mb_strlen($newText) > 255) {
	        throw new InvalidArgumentException('Длина текста задания должна быть от 1 до 255 символов');
	    }

	    $task = new Task();
	    $task->setName($fields['name']);
	    $task->setEmail($fields['email']);
	    $task->setText($newText);
	    $task->save();

	    setcookie('message', 'Новое задание добавлено', time() + 5, '/', '', false, true);
	    return $task;
	}

	public function updateFromArray(array $fields): Task
	{
	    $newText = filter_var($fields['text'], FILTER_SANITIZE_SPECIAL_CHARS);

	    if (mb_strlen($newText) == 0 || mb_strlen($newText) > 255) {
	        throw new InvalidArgumentException('Длина текста задания должна быть от 1 до 255 символов');
	    }

	    foreach(Constants::getStatusOptions() as $option) {
	    	if ($fields['status'] == $option[0]) {
	    		$newStatus = $option[1];
	    		break;
	    	}
	    }

	    $this->setName($fields['name']);
	    $this->setEmail($fields['email']);
	    $this->setText($newText);
	    $this->setStatus($newStatus);
	    $this->setEdited(true);
	    $this->save();

	    setcookie('message', 'Задание отредактировано', time() + 5, '/', '', false, true);
	    return $this;
	}

	public static function getSuccessMessage(array $successData): string
	{
		if (!empty($successData['message'])) {
			return $successData['message'];
		}
		return '';
	}
}