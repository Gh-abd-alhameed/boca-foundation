<?php

namespace boca\core\settings;

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
		if (is_string($callback)) {
			return self::StringHandle($callback);
		}
		self::RouteHandle($route, self::$EDITABLE, $callback);
	}

	public static function any(string $route, $callback)
	{
		if (is_string($callback)) {
			return self::StringHandle($callback);
		}
		self::RouteHandle($route, self::$ALLMETHODS, $callback);
	}

	public static function delete(string $route, $callback)
	{
		if (is_string($callback)) {
			return self::StringHandle($callback);
		}
		self::RouteHandle($route, self::$DELETABLE, $callback);
	}

	public static function get($route, $callback)
	{
		if (is_string($callback)) {
			return self::StringHandle($callback);
		}
		self::RouteHandle($route, self::$READABLE, $callback);
	}

	public static function RouteHandle($routename, $methods, $callback)
	{
		self::$route[] = ["route" => $routename, "method" => $methods, "callback" => $callback];
	}

	public static function run()
	{
		add_action('rest_api_init', function () {
			foreach (self::$route as $key => $value) {
				register_rest_route(self::$namespace, $value["route"], array(
					'methods' => $value["method"],
					'callback' => $value["callback"],
				));
			}
		});
	}
}