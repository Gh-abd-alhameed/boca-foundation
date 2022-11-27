<?php

namespace app\http\controller;

class TranslationsController
{
	public function Show()
	{

		return json_encode(["status"=>200 , "err"=>"" , "msg"=>"" , "data"=>[]]);
	}
}