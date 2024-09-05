<?php
class App
{
    protected $controller = 'TaskController';
    public $method = "index";
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        if (file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        if (isset($url[4]) && method_exists($this->controller, $url[4])) {
            $this->method = $url[4];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        if (isset($uri)) {
            return explode('/', filter_var(rtrim($uri, '/'), FILTER_SANITIZE_URL));
        }
    }
}
