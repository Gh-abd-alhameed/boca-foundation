<?php

namespace boca\core\settings;

class Response
{
    public function json($value)
    {
    	return json_encode($value);
    }

    public function status(int $code = 200, string $message = "")
    {
        header("HTTP/1.1 $code $message");
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