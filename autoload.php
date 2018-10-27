<?php

/*
 *
 * SimpleFramework
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTXTech
 * @link https://itxtech.org
 *
 */

const PHAR_FILENAME = "SimpleFramework.phar";

$workingDir = __DIR__ . DIRECTORY_SEPARATOR;
if(file_exists($workingDir . PHAR_FILENAME)){
	define("iTXTech\SimpleFramework\PATH", "phar://" . $workingDir . PHAR_FILENAME . DIRECTORY_SEPARATOR);
}else{
	define("iTXTech\SimpleFramework\PATH", $workingDir);
}
require_once(\iTXTech\SimpleFramework\PATH . "src/iTXTech/SimpleFramework/Util/ClassLoader.php");

$classLoader = new \ClassLoader();
$classLoader->addPath(\iTXTech\SimpleFramework\PATH . "src");
$classLoader->register(true);

abstract class Initializer{
	public static function setSingleThread(bool $bool = false){
		@define('iTXTech\SimpleFramework\SINGLE_THREAD', $bool);
		if(!$bool){
			\iTXTech\SimpleFramework\ThreadManager::init();
		}
	}

	public static function initTerminal(bool $formattingCodes = true){
		\iTXTech\SimpleFramework\Console\Terminal::$formattingCodes = $formattingCodes;
		\iTXTech\SimpleFramework\Console\Terminal::init();
	}
}