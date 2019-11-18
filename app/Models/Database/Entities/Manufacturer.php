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

namespace App\Models\Database\Entities;

use App\Models\Database\Attributes\TCreatedAt;
use App\Models\Database\Attributes\TId;
use App\Models\Database\Attributes\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * Manufacturer entity
 * @ORM\Entity(repositoryClass="App\Models\Database\Repositories\ManufacturerRepository")
 * @ORM\Table(name="`manufacturers`")
 * @ORM\HasLifecycleCallbacks()
 */
class Manufacturer {

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/**
	 * @var string Manufacturer name
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE)
	 */
	private $name;

	/**
	 * Constructor
	 * @param string $name Manufacturer name
	 */
	public function __construct(string $name) {
		$this->name = $name;
	}

	/**
	 * Returns the manufacturer name
	 * @return string Manufacturer name
	 */
	public function getName(): string {
		return $this->name;
	}

}