<?php
defined("ABSPATH") or die('');
use boca\core\settings\Hooks;

$array = array (
	"post_image_url"  =>[  // id meta box
		"type"=>[],
		"title"=> "image",
		"screen"=>array("post"),
		"context"=>"side",
		"priority"=>"default",
		"callback"=>array(
			"option_id"=>"",
		)
	]
);


Hooks::Init("add_meta_boxes" , function (){
	Hooks::action(function (){
		add_meta_box('post_image_url', 'image', function ($post){

		},"post", 'side', 'default');
	});
});
