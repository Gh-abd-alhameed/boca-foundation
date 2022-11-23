<?php

namespace boca\core\settings;

class Response
{
    public function json($value)
    {
        echo json_encode($value);
        return $this;
    }

    public function status(int $code = 200, string $message = "")
    {
        header("HTTP/2.0 $code $message");
        return $this;
    }

    public function withHeaders(array $headers)
    {
        if (!is_string($headers)) {
            foreach ($headers as $key => $value) {
                header("$key: $value");
            }
        }
        unset($headers);
        return $this;
    }
}