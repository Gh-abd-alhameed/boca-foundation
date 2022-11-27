<?php

namespace boca\core\settings;

use boca\core\settings\Hooks;

class Route
{

	public static $namespace;
	public static $route;
	public static $READABLE = \WP_REST_Server::READABLE; // GET
	public static $EDITABLE = \WP_REST_Server::EDITABLE; // POST, PUT, PATCH
	public static $DELETABLE = \WP_REST_Server::DELETABLE; // DELETE
	public static $ALLMETHODS = \WP_REST_Server::ALLMETHODS; // ANY

	public static function Init(string $namespace, $callback)
	{
		self::$namespace = $namespace;
		$callback();
		self::run();
	}

	public static function post(string $route, $callback)
	{
		 self::RouteHandle($route, "POST", $callback);
	}

	public static function any(string $route, $callback)
	{
		 self::RouteHandle($route, self::$ALLMETHODS, $callback);
	}

	public static function delete(string $route, $callback)
	{
		 self::RouteHandle($route, "DELETE", $callback);
	}

	public static function get($route, $callback)
	{
		 self::RouteHandle($route, "GET", $callback);
	}

	public static function RouteHandle($routename, $methods, $callback)
	{

		if (is_array($callback)) {
			${$callback[1]} = new $callback[0];
			$function = $callback[1];
			self::$route[] = ["namespace" => self::$namespace, "route" => $routename, "method" => $methods, "callback" => ${$callback[1]}->{$function}];
		} else {
			self::$route[] = ["namespace" => self::$namespace, "route" => $routename, "method" => $methods, "callback" => $callback];
		}
	}

	public static function run()
	{
		Hooks::Init("rest_api_init", function () {
			Hooks::action(function () {
				foreach (self::$route as $key => $value) {
					register_rest_route($value["namespace"], $value["route"], array(
						'methods' => $value["method"],
						'callback' => $value["callback"]
					));
				}
			});
		});
	}
}
