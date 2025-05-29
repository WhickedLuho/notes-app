<?php
namespace App\Core;

class Request {
    public array $get;
    public array $post;
    public array $server;
    public array $params;

    public function __construct(array $params = []) {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->params = $params;
    }

    public function input(string $key, $default = null) {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array {
        return array_merge($this->get, $this->post);
    }
}
