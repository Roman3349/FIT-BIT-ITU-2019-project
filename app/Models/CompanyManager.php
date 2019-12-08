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

namespace App\Models;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;

/**
 * Company manager
 */
final class CompanyManager {

	public function get(): \stdClass {
		return Json::decode(FileSystem::read(__DIR__ . '/../config/company.json'));
	}

	/**
	 * Returns the company address
	 * @return string Company address
	 */
	public function getAddress(): string {
		$address = $this->get()->address;
		return $address->street . PHP_EOL . $address->zip . ' ' . $address->city;
	}

	/**
	 * Returns the company e-mail
	 * @return string Company e-mail
	 */
	public function getEmail(): string {
		return $this->get()->email;
	}

	/**
	 * Returns the company latitude
	 * @return float Company latitude
	 */
	public function getLatitude(): float {
		return $this->get()->gps->latitude;
	}

	/**
	 * Returns the company longitude
	 * @return float Company longitude
	 */
	public function getLongitude(): float {
		return $this->get()->gps->longitude;
	}

	/**
	 * Returns the company logo
	 * @return string Company logo
	 */
	public function getLogo(): string {
		return $this->get()->logo;
	}

	/**
	 * Returns the company name
	 * @return string Company name
	 */
	public function getName(): string {
		return $this->get()->name;
	}

	/**
	 * Returns the company telephone number
	 * @return string Company telephone number
	 */
	public function getTelephone(): string {
		return $this->get()->telephone;
	}

}