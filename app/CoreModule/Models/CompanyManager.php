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

/**
 * Company manager
 */
final class CompanyManager {

	/**+
	 * @var string Company logo
	 */
	private $logo = 'Logo půjčovny';

	/**
	 * @var string Company name
	 */
	private $name = 'Jméno půjčovny';

	/**
	 * @var string Company address
	 */
	private $address = 'Ulice č. popisné' . PHP_EOL . 'PSČ Město';

	/**
	 * @var string Company telephone
	 */
	private $telephone = '+420 123 456 789';

	/**
	 * @var string Company e-mail
	 */
	private $email = 'info@example.com';

	/**
	 * @var float Company latitude
	 */
	private $latitude = 49.4949264;

	/**
	 * @var float Company longitude
	 */
	private $longitude = 16.6814939;

	/**
	 * Returns the company address
	 * @return string Company address
	 */
	public function getAddress(): string {
		return $this->address;
	}

	/**
	 * Returns the company e-mail
	 * @return string Company e-mail
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * Returns the company latitude
	 * @return float Company latitude
	 */
	public function getLatitude(): float {
		return $this->latitude;
	}

	/**
	 * Returns the company longitude
	 * @return float Company longitude
	 */
	public function getLongitude(): float {
		return $this->longitude;
	}

	/**
	 * Returns the company logo
	 * @return string Company logo
	 */
	public function getLogo(): string {
		return $this->logo;
	}

	/**
	 * Returns the company name
	 * @return string Company name
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns the company telephone number
	 * @return string Company telephone number
	 */
	public function getTelephone(): string {
		return $this->telephone;
	}

}