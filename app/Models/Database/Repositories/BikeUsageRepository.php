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

namespace App\Models\Database\Repositories;

use App\Models\Database\Entities\BikeUsage;
use Doctrine\ORM\EntityRepository;

/**
 * Bike usage repository
 */
class BikeUsageRepository extends EntityRepository {

	/**
	 * Finds a bike usage by name
	 * @param string $name Bike usage name
	 * @return BikeUsage|null Bike usage
	 */
	public function findOneByName(string $name): ?BikeUsage {
		return $this->findOneBy(['name' => $name]);
	}

}