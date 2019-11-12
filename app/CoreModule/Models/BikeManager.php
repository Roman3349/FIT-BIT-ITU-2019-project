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
	 * @var Context Database context
	 */
	private $database;

	/**
	 * @var mixed[] Products
	 */
	private $products = [
		[
			'id' => 0,
			'name' => 'Ghost Kato 4.7',
			'img' => 'https://img.bike-pro.cz/images/SCH/MY18_KATO_4-7_AL_U_LOWBUDGED_ELECTRICBLUE_NIGHTBLACK_NEONYELLOW_18KA2029.png?vid=1&tid=30&r=A',
			'usage' => 'XC',
			'wheelSize' => '27,5"',
			'size' => '17"',
			'forkTravel' => 100,
			'shockTravel' => null,
			'speeds' => '3x9',
			'price' => 500,
		],
		[
			'id' => 1,
			'name' => 'Maxbike Kyoga 27.5"',
			'img' => 'https://img.bike-pro.cz/images/MB/2132.jpg?vid=1&tid=30&r=A',
			'usage' => 'XC/Trail',
			'wheelSize' => '27,5"',
			'size' => '17"',
			'forkTravel' => 120,
			'shockTravel' => null,
			'speeds' => '2x10',
			'price' => 600,
		],
		[
			'id' => 2,
			'name' => 'Ghost Kato FS 3 27,5"',
			'img' => 'https://img.bike-pro.cz/images/SCH/17AM1010_MY17_KATO_FS_3_AL_27-5_U_NIGHTBLACK_RIOTGREEN_NEONRED.jpg?vid=1&tid=30&r=A',
			'usage' => 'Trail',
			'wheelSize' => '27,5"',
			'size' => 'M',
			'forkTravel' => 130,
			'shockTravel' => 130,
			'speeds' => '3x10',
			'price' => 750,
		],
		[
			'id' => 3,
			'name' => 'Cannondale Jekyll 3',
			'img' => 'https://singlekras.rezervator.cz/files/items/images/img_7530d4192b569ab8664a114ce4049265.jpg?1552051310',
			'usage' => 'Trail/Enduro',
			'wheelSize' => '27,5"',
			'size' => 'M',
			'forkTravel' => 150,
			'shockTravel' => 150,
			'speeds' => '1x12',
			'price' => 900,
		],
	];

	/**
	 * Constructor
	 * @param Context $database Database context
	 */
	public function __construct(Context $database) {
		$this->database = $database;
	}

	public function list(): array {
		return $this->products;
	}

	public function get(int $id): array {
		return $this->products[$id];
	}

}