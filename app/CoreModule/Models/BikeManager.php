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

namespace App\CoreModule\Models;

use Nette\Database\Context;

/**
 * Bike manager
 */
final class BikeManager {

	/**
	 * Database name table
	 */
	private const TABLE = 'bikes';

	/**
	 * @var Context Database context
	 */
	private $database;

	/**
	 * Constructor
	 * @param Context $database Database context
	 */
	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * Lists all bikes
	 * @return mixed[] Bikes
	 */
	public function list(): array {
		return $this->database->table(self::TABLE)
			->fetchAll();
	}

	/**
	 * Returns a bike
	 * @param int $id Bike ID
	 * @return mixed[] Bike
	 */
	public function get(int $id): array {
		return $this->database->table(self::TABLE)
			->where('id', $id)
			->fetch()
			->toArray();
	}

}