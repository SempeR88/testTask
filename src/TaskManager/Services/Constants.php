<?php

namespace TaskManager\Services;

class Constants
{
	const SORT_OPTIONS = [
		['author_name', 'ASC', 'Имя по возрастанию'],
		['author_name', 'DESC', 'Имя по убыванию'],
		['author_email', 'ASC', 'Email по возрастанию'],
		['author_email', 'DESC', 'Email по убыванию'],
		['status', 'ASC', 'Сначала *В работе*'],
		['status', 'DESC', 'Сначала *Выполнено*']
	];

	const STATUS_OPTIONS = [
		['in_work', 'В работе'],
		['completed', 'Выполнено']
	];

	public static function getSortOptions()
	{
		return self::SORT_OPTIONS;
	}

	public static function getStatusOptions()
	{
		return self::STATUS_OPTIONS;
	}
}