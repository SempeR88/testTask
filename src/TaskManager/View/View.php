<?php

namespace TaskManager\View;

class View
{
	private $templatesPath;   // путь до папки с шаблонами
    private $extraVars = [];  // дополнительные переменные (например, User)

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    public function setVar(string $name, $value): void
    {
        $this->extraVars[$name] = $value;
    }

    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code);

        extract($this->extraVars);
        extract($vars);

        ob_start();
	    include $this->templatesPath . '/' . $templateName;
	    $buffer = ob_get_contents();
	    ob_end_clean();

	    echo $buffer;
    }
}