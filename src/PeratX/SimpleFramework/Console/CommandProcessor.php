<?php

/**
 * SimpleFramework
 * The fast, light-weighted, easy-to-extend php framework.
 *
 * Some classes are based on project PocketMine-MP.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PeratX
 */

namespace PeratX\SimpleFramework\Console;

use PeratX\SimpleFramework\Console\Command\ClearCommand;
use PeratX\SimpleFramework\Console\Command\Command;
use PeratX\SimpleFramework\Console\Command\HelpCommand;
use PeratX\SimpleFramework\Console\Command\ModulesCommand;
use PeratX\SimpleFramework\Console\Command\PackModuleCommand;
use PeratX\SimpleFramework\Console\Command\PackSFCommand;
use PeratX\SimpleFramework\Console\Command\StopCommand;
use PeratX\SimpleFramework\Console\Command\UnpackModuleCommand;
use PeratX\SimpleFramework\Console\Command\VersionCommand;

class CommandProcessor{
	/** @var Command[] */
	private $registeredCommands;

	public function __construct(){
		$this->registerCommands();
	}

	public function getCommands(){
		return $this->registeredCommands;
	}

	public function registerCommands(){
		$this->register(new HelpCommand(), "help");
		$this->register(new VersionCommand(), "version");
		$this->register(new StopCommand(), "stop");
		$this->register(new ModulesCommand(), "modules");
		$this->register(new ClearCommand(), "clear");

		$this->register(new PackModuleCommand(), "pm");
		$this->register(new PackSFCommand(), "psf");
		$this->register(new UnpackModuleCommand(), "um");
	}

	public function register(Command $command, string $name){
		$this->registeredCommands[$name] = $command;
	}

	public function unregister(string $name) : bool{
		if(isset($this->registeredCommands[strtolower($name)])){
			unset($this->registeredCommands[strtolower($name)]);
			return true;
		}
		return false;
	}

	public function dispatchCommand(string $commandLine){
		$args = explode(" ", $commandLine);
		$command = strtolower(array_shift($args));
		if(isset($this->registeredCommands[$command])){
			if(!$this->registeredCommands[$command]->execute($command, $args)){
				Logger::info(TextFormat::GOLD . "Usage: " . TextFormat::WHITE . $this->registeredCommands[$command]->getUsage());
			}
		}else{
			Logger::info(TextFormat::RED . "Command '$command' not found. Type 'help' for help");
		}
	}
}

