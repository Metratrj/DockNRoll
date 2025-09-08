<?php

use App\Controllers\ContainerController;

require __DIR__ . '/../vendor/autoload.php';

function normalizeData($data) {
    // OpenAPI- oder JsonSerializable-Objekte in Array umwandeln
    if ($data instanceof JsonSerializable) {
        return normalizeData($data->jsonSerialize());
    }

    // stdClass oder andere Objekte
    if (is_object($data)) {
        return normalizeData(get_object_vars($data));
    }

    // Arrays rekursiv verarbeiten
    if (is_array($data)) {
        $normalized = [];
        foreach ($data as $k => $v) {
            $normalized[$k] = normalizeData($v);
        }
        return $normalized;
    }

    // Skalare oder null unverändert zurück
    return $data;
}

function renderNestedList($data): string {
    $data = normalizeData($data);

    if (is_scalar($data) || $data === null) {
        return htmlspecialchars((string)$data, ENT_QUOTES);
    }

    if (is_array($data)) {
        if (empty($data)) {
            return ''; // leere Arrays nicht anzeigen
        }
        $html = "<ul>";
        foreach ($data as $key => $value) {
            $html .= "<li><strong>" . htmlspecialchars((string)$key) . ":</strong> ";
            $html .= renderNestedList($value);
            $html .= "</li>";
        }
        $html .= "</ul>";
        return $html;
    }

    return '';
}

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
echo $requestUri;
if ($requestUri === '/containers') {
    (new ContainerController())->index();
    exit;
}



// Aktionen
if ($requestMethod === 'POST' && preg_match('@^/containers/([^/]+)/start$@', $requestUri, $m)) {
    (new ContainerController())->start($m[1]);
    exit;
}
if ($requestMethod === 'POST' && preg_match('@^/containers/([^/]+)/stop$@', $requestUri, $m)) {
    (new ContainerController())->stop($m[1]);
    exit;
}
if ($requestMethod === 'POST' && preg_match('@^/containers/([^/]+)/restart$@', $requestUri, $m)) {
    (new ContainerController())->restart($m[1]);
    exit;
}
if ($requestMethod === 'POST' && preg_match('@^/containers/([^/]+)/delete$@', $requestUri, $m)) {
    (new ContainerController())->remove($m[1]);
    exit;
}

http_response_code(404);
echo "404 Not Found";