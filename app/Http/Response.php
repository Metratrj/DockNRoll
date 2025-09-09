<?php

namespace App\Http;

class Response
{
    private string $body = "";
    private array $headers = [];
    private int $status = 0;

    public function setStatus(int $status): self {
        $this->status = $status;
        return $this;
    }

    public function setBody(string $body): self {
        $this->body = $body;
        return $this;
    }

    public function setHeader(string $header, string $value): self {
        $this->headers[$header] = $value;
        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function getBody(): string {
        return $this->body;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function json(array $data): void
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setBody(json_encode($data));
        $this->send();
    }

    public function send(): void {
        if ($this->status) {
            http_response_code($this->status);
        }
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        echo $this->body;
    }
}