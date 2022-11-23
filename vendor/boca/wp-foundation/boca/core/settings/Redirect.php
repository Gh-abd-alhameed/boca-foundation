<?php

namespace boca\core\settings;
use boca\core\settings\Request;
class Redirect
{
	public static function to(string $from = "", string $to = "", int $code = 302)
	{
		if (is_string($from)) {
			if (!empty($from)) {
				$pattern = "/" . str_replace("/", "\/", $from) . "$/";
				if (preg_match($pattern, Request::uri())) {
					header("Location: " . $to, true, $code);
				}
			}
		}
		return new Redirect();
	}
	public function back()
	{
		header("Location: " . Request::previous());
		return $this;
	}

	public function with(array $Key = [])
	{
		return $this;
	}

	public function url(string $url = "")
	{
		header("Location: $url");
		return $this;
	}

	public function status(int $code = 200, string $message = "")
	{
		header("HTTP/2.0 $code $message");
		return $this;
	}
}
