<?php

namespace TaskManager\Models;

use TaskManager\Services\Db;

abstract class ActiveRecordEntity
{
	protected $id;

	public function getId(): int
	{
		return $this->id;
	}

	public function __set($name, $value)
	{
		$camelCaseName = $this->underscoreToCamelCase($name);
		$this->$camelCaseName = $value;
	}

	private function underscoreToCamelCase(string $source): string
	{
		return lcfirst(str_replace('_', '', ucwords($source, '_')));
	}

	public static function findAll(): array
	{
		$db = Db::getInstance();
		return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
	}

	abstract protected static function getTableName(): string;

	public static function getById(int $id): ?self
	{
		$db = Db::getInstance();
		$entities = $db->query(
			'SELECT * FROM `' . static::getTableName() . '` WHERE `id` = :id;', 
			[':id' => $id], 
			static::class
		);

		return $entities ? $entities[0] : null;
	}

	public function save(): void
	{
		$mappedProperties = $this->mapPropertiesToDbFormat();
	    if ($this->id !== null) {
	        $this->update($mappedProperties);
	    } else {
	        $this->insert($mappedProperties);
	    }
	}

	private function update(array $mappedProperties): void
	{
		$paramsNames = [];
	    $params = [];
	    foreach ($mappedProperties as $columnName => $value) {
	        $paramName = ':' . $columnName;
	        $paramsNames[] = '`' . $columnName . '` = ' . $paramName;
	        $params[$paramName] = $value;
	    }

		$sql = 'UPDATE `' . static::getTableName() . '` SET ' . implode(', ', $paramsNames) . ' WHERE `id` = ' . $this->id . ';';
	    $db = Db::getInstance();
		$db->query($sql, $params, static::class);
	}

	private function insert(array $mappedProperties): void
	{
		$filteredProperties = array_filter($mappedProperties);
		$columns = [];
		$paramsNames = [];
	    $params = [];
	    foreach ($filteredProperties as $columnName => $value) {
	    	$columns[] = '`' . $columnName . '`';
	        $paramName = ':' . $columnName;
	        $paramsNames[] = $paramName;
	        $params[$paramName] = $value;
	    }
	    
		$sql = 'INSERT INTO `' . static::getTableName() . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $paramsNames) . ');';
	    $db = Db::getInstance();
		$db->query($sql, $params, static::class);
		$this->id = $db->getLastInsertId();
		$this->refresh();
	}

	private function refresh(): void
    {
        $objectFromDb = static::getById($this->id);
        $reflector = new \ReflectionObject($objectFromDb);
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $this->$propertyName = $property->getValue($objectFromDb);
        }
    }

    public function delete(): void
	{
		$sql = 'DELETE FROM `' . static::getTableName() . '` WHERE `id` = :id;';
	    $db = Db::getInstance();
		$db->query($sql, [':id' => $this->id], static::class);

		$this->id = null;
	}

	private function mapPropertiesToDbFormat(): array
	{
		$reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();
        
        $mappedProperties = [];
        foreach ($properties as $property) {
        	$propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }
        return $mappedProperties;
	}

	private function camelCaseToUnderscore(string $source): string
	{
		return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
	}

	public static function getPagesCount(int $itemsPerPage): int
	{
	    $db = Db::getInstance();
	    $result = $db->query('SELECT COUNT(*) AS `cnt` FROM `' . static::getTableName() . '`;');
	    return ceil($result[0]->cnt / $itemsPerPage);
	}

	public static function getPage(int $pageNum, int $itemsPerPage, array $sortParams): array
	{
    	if (empty($sortParams['sortType'])) {
	    	$sortType = ['id', 'DESC'];
	    } else {
	    	$sortType = [$sortParams['sortType'][0], $sortParams['sortType'][1]];
	    }

	    $db = $db = Db::getInstance();
	    return $db->query('SELECT * FROM `'. static::getTableName() . '` ORDER BY `' . $sortType[0] . '` ' . $sortType[1] . ' LIMIT ' . $itemsPerPage . ' OFFSET ' . ($pageNum - 1) * $itemsPerPage . ';', [], static::class);
	}
	
	public static function getSortParams(array $getRequest): array
	{
		return [
			'sortType' => static::getSortType($getRequest),
			'sortRequest' => static::getSortRequest($getRequest)
		];
	}

	private static function getSortType(array $sortData): array
	{
		if (!empty($sortData['sortType'])) {
			preg_match('/^(\w+)\-(\w+)$/', $sortData['sortType'], $matches);
			array_shift($matches);
			return $matches;
		}
		return [];
	}

	private static function getSortRequest(array $sortData): string
	{
		if (!empty($sortData)) {
			if (isset($sortData['sortType'])) {
				return '?sortType=' . $sortData['sortType'];
			}
		}
		return '';
	}
}