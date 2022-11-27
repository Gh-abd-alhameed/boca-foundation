<?php


namespace boca\core\settings;

class Hooks
{
	public static $hook;
	public static $actions;
	public static $filters;

	public static function Init(string $hook, \Closure $callback)
	{
		self::$hook = $hook;
		$callback();
		if (self::$actions):
			self::runAction();
		endif;
		if (self::$filters):
			self::runFilter();
		endif;
	}

	public static function filter(\Closure $callback)
	{
		self::$filters[] = ["hook" => self::$hook, "callback" => $callback];
	}

	public static function action(\Closure $callback)
	{
		self::$actions[] = ["hook" => self::$hook, "callback" => $callback];
	}

	public static function runAction()
	{
		foreach (self::$actions as $key => $value) {
			add_action($value["hook"], $value["callback"]);
		}
	}

	public static function runFilter()
	{
		foreach (self::$filters as $key => $value) {
			add_filter($value["hook"], $value["callback"]);
		}
	}
}

