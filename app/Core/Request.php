<?php
namespace App\Core;

class Request
{
    protected array $get;
    protected array $post;
    protected array $server;
    protected array $files;

    public function __construct()
    {
        $this->get    = $_GET;
        $this->post   = $_POST;
        $this->server = $_SERVER;
        $this->files  = $_FILES;
    }

    // Request methodok
    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isAjax(): bool
    {
        return strtolower($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    // Inputok lekérése
    public function input(string $key, $default = null, string $type = 'string', string $from = 'any')
    {
        $value = match($from) {
            'post' => $this->post[$key] ?? $default,
            'get' => $this->get[$key] ?? $default,
            default => $this->post[$key] ?? $this->get[$key] ?? $default,
        };

        return $this->sanitize($value, $type);
    }

    public function has(string $key, string $from = 'any'): bool
    {
        return match($from) {
            'post' => isset($this->post[$key]),
            'get' => isset($this->get[$key]),
            default => isset($this->post[$key]) || isset($this->get[$key]),
        };
    }

    public function all(array $filters = []): array
    {
        return $this->sanitizeArray(array_merge($this->get, $this->post), $filters);
    }

    public function get(array $filters = []): array
    {
        return $this->sanitizeArray($this->get, $filters);
    }

    public function post(array $filters = []): array
    {
        return $this->sanitizeArray($this->post, $filters);
    }

    // Input szűrés
    protected function sanitize($value, string $type = 'string')
    {
        if (is_array($value)) {
            return $this->sanitizeArray($value);
        }

        $value = (string)$value;

        return match ($type) {
            'int' => (int)$value,
            'float' => (float)$value,
            'email' => filter_var($value, FILTER_SANITIZE_EMAIL),
            'url' => filter_var($value, FILTER_SANITIZE_URL),
            'path' => preg_replace('/[^a-zA-Z0-9\-_\/]/', '', $value),
            'filename' => preg_replace('/[^a-zA-Z0-9\-_\.]/', '', basename($value)),
            'raw' => $value,
            default => htmlspecialchars(strip_tags($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
        };
    }

    protected function sanitizeArray(array $data, array $filters = []): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            $filter = $filters[$key] ?? 'string';
            $clean[$key] = $this->sanitize($value, $filter);
        }
        return $clean;
    }

    // Fájlkezelés
    public function file(string $key): ?array
    {
        if (!isset($this->files[$key])) return null;

        return [
            'name' => $this->sanitize($this->files[$key]['name'], 'filename'),
            'type' => $this->sanitize($this->files[$key]['type'], 'string'),
            'tmp_name' => $this->files[$key]['tmp_name'],
            'error' => (int)$this->files[$key]['error'],
            'size' => (int)$this->files[$key]['size'],
        ];
    }

    // CSRF védelem
    public function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Szerverváltozók
    public function server(string $key, $default = null, string $type = 'string')
    {
        return $this->sanitize($this->server[$key] ?? $default, $type);
    }

    public function getBearerToken(): ?string
    {
        $header = $this->server('HTTP_AUTHORIZATION', '', 'raw');
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $this->sanitize($matches[1], 'string');
        }
        return null;
    }
}
