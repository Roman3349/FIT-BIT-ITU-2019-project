<?php

/*
 * Copyright (C) 2019 Roman Ondráček <xondra58@stud.fit.vutbr.cz>
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
 */

declare(strict_types = 1);

namespace App;

use Nette\Configurator;
use Nette\Utils\Finder;
use SplFileInfo;

/**
 * Bootstrap
 */
class Bootstrap {

	/**
	 * Config directory
	 */
	private const CONFIG_DIR = __DIR__ . '/config/';

	/**
	 * Boots the web app
	 * @return Configurator DI container configurator
	 */
	public static function boot(): Configurator {
		$configurator = new Configurator;

		//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
		$configurator->enableTracy(__DIR__ . '/../log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig(self::CONFIG_DIR . 'common.neon');

		if (file_exists(self::CONFIG_DIR . 'local.neon')) {
			$configurator->addConfig(self::CONFIG_DIR . 'local.neon');
		}

		/**
		 * @var SplFileInfo $file File info object
		 */
		foreach (Finder::findFiles('*Module/config/config.neon')->from(__DIR__) as $file) {
			$configurator->addConfig($file->getRealPath());
		}

		return $configurator;
	}
}
