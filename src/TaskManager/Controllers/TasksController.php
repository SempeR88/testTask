<?php

namespace TaskManager\Controllers;

use TaskManager\Exceptions\InvalidArgumentException;
use TaskManager\Exceptions\UnauthorizedException;
use TaskManager\Exceptions\NotFoundException;
use TaskManager\Models\Tasks\Task;
use TaskManager\Models\Users\User;
use TaskManager\Services\Constants;

class TasksController extends AbstractController
{
    public function edit(int $taskId): void
    {
    	$task = Task::getById($taskId);

    	if ($task === null) {
	        throw new NotFoundException();
	    }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)) {
            try {
                $task->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('tasks/edit.php', [
                    'task' => $task,
                    'statusOptions' => Constants::getStatusOptions(),
                    'error' => $e->getMessage()
                ]);
                return;
            }

            header('Location: /');
            exit();
        }

        $this->view->renderHtml('tasks/edit.php', [
            'task' => $task, 
            'statusOptions' => Constants::getStatusOptions()
        ]);
    }

    public function add(): void
    {
        if (!empty($_POST)) {
            try {
                $task = Task::createFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('tasks/add.php', ['error' => $e->getMessage()]);
                return;
            }

            header('Location: /');
            exit();
        }

        $this->view->renderHtml('tasks/add.php');
    }

    public function delete(int $taskId): void
    {
    	$task = Task::getById($taskId);

    	if ($task === null) {
	        throw new NotFoundException();
	    }

	    $task->delete();

        header('Location: /');
        exit();
    }
}