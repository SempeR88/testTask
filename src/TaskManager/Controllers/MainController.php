<?php

namespace TaskManager\Controllers;

use TaskManager\Models\Tasks\Task;
use TaskManager\Services\Constants;

class MainController extends AbstractController
{
	public function main()
    {
        $this->page(1);
    }

    public function page(int $pageNum)
	{
	    $this->view->renderHtml('main/main.php', [
	        'tasks' => Task::getPage($pageNum, 3, Task::getSortParams($_GET)),
	        'pagesCount' => Task::getPagesCount(3),
	        'currentPageNum' => $pageNum,
	        'sortOptions' => Constants::getSortOptions(),
	        'sortParams' => Task::getSortParams($_GET),
	        'message' => Task::getSuccessMessage($_COOKIE)
	    ]);
	}
}