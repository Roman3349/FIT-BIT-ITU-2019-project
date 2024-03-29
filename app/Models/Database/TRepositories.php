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

namespace App\Models\Database;

use App\Models\Database\Entities\Bike;
use App\Models\Database\Entities\BikeUsage;
use App\Models\Database\Entities\Gallery;
use App\Models\Database\Entities\Manufacturer;
use App\Models\Database\Entities\Reservation;
use App\Models\Database\Entities\User;
use App\Models\Database\Repositories\BikeRepository;
use App\Models\Database\Repositories\BikeUsageRepository;
use App\Models\Database\Repositories\GalleryRepository;
use App\Models\Database\Repositories\ManufacturerRepository;
use App\Models\Database\Repositories\ReservationRepository;
use App\Models\Database\Repositories\UserRepository;

/**
 * @mixin EntityManager
 */
trait TRepositories {

	/**
	 * Returns the bike repository
	 * @return BikeRepository Bike repository
	 */
	public function getBikeRepository(): BikeRepository {
		return $this->getRepository(Bike::class);
	}

	/**
	 * Returns the bike usage repository
	 * @return BikeUsageRepository Bike usage repository
	 */
	public function getBikeUsageRepository(): BikeUsageRepository {
		return $this->getRepository(BikeUsage::class);
	}

	/**
	 * Returns the gallery repository
	 * @return GalleryRepository Gallery repository
	 */
	public function getGalleryRepository(): GalleryRepository {
		return $this->getRepository(Gallery::class);
	}

	/**
	 * Returns the manufacturer repository
	 * @return ManufacturerRepository Manufacturer repository
	 */
	public function getManufacturerRepository(): ManufacturerRepository {
		return $this->getRepository(Manufacturer::class);
	}

	/**
	 * Returns the reservation repository
	 * @return ReservationRepository Reservation repository
	 */
	public function getReservationRepository(): ReservationRepository {
		return $this->getRepository(Reservation::class);
	}

	/**
	 * Returns the user repository
	 * @return UserRepository User repository
	 */
	public function getUserRepository(): UserRepository {
		return $this->getRepository(User::class);
	}

}