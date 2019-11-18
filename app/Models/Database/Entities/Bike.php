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
 * Bike entity
 * @ORM\Entity(repositoryClass="App\Models\Database\Repositories\BikeRepository")
 * @ORM\Table(name="`bikes`")
 * @ORM\HasLifecycleCallbacks()
 */
class Bike {

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/**
	 * @var Manufacturer Manufacturer
	 * @ORM\ManyToOne(targetEntity="Manufacturer")
	 * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
	 */
	private $manufacturer;

	/**
	 * @var string Bike model name
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=FALSE)
	 */
	private $name;

	/**
	 * @var string Bike picture
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=FALSE)
	 */
	private $picture;

	/**
	 * @var string Frame material
	 * @ORM\Column(type="string", length=15, nullable=FALSE, unique=FALSE)
	 */
	private $frameMaterial;

	/**
	 * @var string Frame size
	 * @ORM\Column(type="string", length=15, nullable=FALSE, unique=FALSE)
	 */
	private $frameSize;

	/**
	 * @var string Wheel size
	 * @ORM\Column(type="string", length=15, nullable=FALSE, unique=FALSE)
	 */
	private $wheelSize;

	/**
	 * @var int Fork travel
	 * @ORM\Column(type="integer", length=10, nullable=TRUE, unique=FALSE)
	 */
	private $forkTravel;

	/**
	 * @var int Shock travel
	 * @ORM\Column(type="integer", length=10, nullable=TRUE, unique=FALSE)
	 */
	private $shockTravel;

	/**
	 * @var string Speeds
	 * @ORM\Column(type="string", length=15, nullable=FALSE, unique=FALSE)
	 */
	private $speeds;

	/**
	 * @var int Bike rent price
	 * @ORM\Column(type="integer", length=10, nullable=FALSE, unique=FALSE)
	 */
	private $price;

	/**
	 * Constructor
	 * @param Manufacturer $manufacturer Manufacturer
	 * @param string $name Model name
	 * @param string $picture Picture
	 * @param string $frameMaterial Frame material
	 * @param string $frameSize Frame size
	 * @param string $wheelSize Wheel size
	 * @param int $forkTravel Fork travel
	 * @param int $shockTravel Shock travel
	 * @param string $speeds Speeds
	 * @param int $price Bike rent price
	 */
	public function __construct(Manufacturer $manufacturer, string $name, string $picture, string $frameMaterial, string $frameSize, string $wheelSize, int $forkTravel, int $shockTravel, string $speeds, int $price) {
		$this->manufacturer = $manufacturer;
		$this->name = $name;
		$this->picture = $picture;
		$this->frameMaterial = $frameMaterial;
		$this->frameSize = $frameSize;
		$this->wheelSize = $wheelSize;
		$this->forkTravel = $forkTravel;
		$this->shockTravel = $shockTravel;
		$this->speeds = $speeds;
		$this->price = $price;
	}

}