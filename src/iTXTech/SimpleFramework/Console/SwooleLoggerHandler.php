<?php

/*
 *
 * SimpleFramework
 *
 * Copyright (C) 2016-2020 iTX Technologies
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace iTXTech\SimpleFramework\Console;

use Swoole\Channel;
use Swoole\Process;

abstract class SwooleLoggerHandler implements LoggerHandler{
	/** @var Process */
	private static $proc;
	/** @var Channel */
	private static $channel;

	public static function shutdown(){
		if(self::$proc instanceof Process){
			self::$proc->close();
		}
	}

	public static function init(){
		if(!self::$channel instanceof Channel){
			$channel = new Channel(1024 * 1024 * 32);
			self::$channel = $channel;
		}else{
			$channel = self::$channel;
		}
		self::$proc = new Process(function(Process $process) use ($channel){
			go(function() use ($channel){
				while(true){
					while(($line = $channel->pop()) !== false){
						echo $line . PHP_EOL;
					}
					\co::sleep(0.01);
				}
			});
		});
		self::$proc->start();
	}

	public static function println(string $message){
		$cleanMessage = TextFormat::clean($message);

		if(!Terminal::hasFormattingCodes()){
			self::$channel->push($cleanMessage);
		}else{
			self::$channel->push($message);
		}
	}
}
