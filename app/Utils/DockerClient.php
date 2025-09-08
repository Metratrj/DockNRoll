<?php

namespace App\Utils;

class DockerClient
{
    private string $pipePath;

    function __construct(string $pipePath = "\\\\.\\pipe\\docker_engine")
    {
        $this->pipePath = $pipePath;
    }

    private function sendRequest(string $method, string $endpoint, ?array $body = null)
    {
        $bodyJson = $body ? json_encode($body) : '';
        $contentLength = $bodyJson ? strlen($bodyJson) : 0;

        $request = "$method $endpoint HTTP/1.1\r\n";
        $request .= "Host: localhost\r\n";
        $request .= "Content-Type: application/json\r\n";
        if ($contentLength > 0) {
            $request .= "Content-Length: $contentLength\r\n";
        }
        $request .= "\r\n";
        if ($bodyJson) {
            $request .= $bodyJson;
        }

        // Named Pipe öffnen
        $fp = fopen($this->pipePath, 'c+b');
        if (!$fp) {
            throw new \RuntimeException("Kann Docker Named Pipe nicht öffnen: {$this->pipePath}");
        }

        fwrite($fp, $request);
        fflush($fp);

        // Antwort einlesen
        $response = '';
        while (!feof($fp)) {
            $response .= fread($fp, 8192);
        }

        fclose($fp);

        // HTTP Header abtrennen
        $parts = preg_split("/\r\n\r\n/", $response, 2);
        $body = $parts[1] ?? '';

        return json_decode($body, true) ?: [];

    }
    public function getVersion(): array {
        return $this->sendRequest('GET', '/version');
    }

    public function getInfo(): array {
        return $this->sendRequest('GET', '/info');
    }

    public function listContainers(bool $all = true): array {
        return $this->sendRequest('GET', '/containers/json?all=' . ($all ? '1' : '0'));
    }
}