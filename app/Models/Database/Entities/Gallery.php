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
 * Gallery entity
 * @ORM\Entity(repositoryClass="App\Models\Database\Repositories\GalleryRepository")
 * @ORM\Table(name="`galleries`")
 * @ORM\HasLifecycleCallbacks()
 */
class Gallery {

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/**
	 * @var string Gallery name
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE)
	 */
	private $name;
	/**
	 * @var string Picture URL
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE)
	 */
	private $url;

	/**
	 * Constructor
	 * @param string $name Gallery name
	 * @param string $url Picture URL
	 */
	public function __construct(string $name, string $url) {
		$this->name = $name;
		$this->url = $url;
	}

	/**
	 * Returns the gallery name
	 * @return string Gallery name
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns the picture URL
	 * @return string Picture URL
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * Sets the gallery name
	 * @param string $name Gallery name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}

	/**
	 * Sets the picture URL
	 * @param string $url Picture URL
	 */
	public function setUrl(string $url): void {
		$this->url = $url;
	}

}