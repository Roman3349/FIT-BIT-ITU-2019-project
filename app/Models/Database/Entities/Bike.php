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
use stdClass;

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
	 * @var BikeUsage Bike usage
	 * @ORM\ManyToOne(targetEntity="BikeUsage")
	 * @ORM\JoinColumn(name="usage_id", referencedColumnName="id")
	 */
	private $usage;

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
	 * @param BikeUsage $usage Bike usage
	 * @param string $picture Picture
	 * @param string $frameMaterial Frame material
	 * @param string $frameSize Frame size
	 * @param string $wheelSize Wheel size
	 * @param int $forkTravel Fork travel
	 * @param int $shockTravel Shock travel
	 * @param string $speeds Speeds
	 * @param int $price Bike rent price
	 */
	public function __construct(Manufacturer $manufacturer, string $name, BikeUsage $usage, string $picture, string $frameMaterial, string $frameSize, string $wheelSize, int $forkTravel, int $shockTravel, string $speeds, int $price) {
		$this->manufacturer = $manufacturer;
		$this->name = $name;
		$this->usage = $usage;
		$this->picture = $picture;
		$this->frameMaterial = $frameMaterial;
		$this->frameSize = $frameSize;
		$this->wheelSize = $wheelSize;
		$this->forkTravel = $forkTravel;
		$this->shockTravel = $shockTravel;
		$this->speeds = $speeds;
		$this->price = $price;
	}

	/**
	 * Returns the bike manufacturer
	 * @return Manufacturer Bike manufacturer
	 */
	public function getManufacturer(): Manufacturer {
		return $this->manufacturer;
	}

	/**
	 * Returns the bike manufacturer name
	 * @return string Bike manufacturer name
	 */
	public function getManufacturerName(): string {
		return $this->manufacturer->getName();
	}

	/**
	 * Returns the bike name
	 * @return string Bike name
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns the full bike name
	 * @return string Full bike name
	 */
	public function getFullName(): string {
		return $this->getManufacturerName() . ' ' . $this->getName();
	}

	/**
	 * Returns the bike usage
	 * @return BikeUsage Bike usage
	 */
	public function getUsage(): BikeUsage {
		return $this->usage;
	}

	/**
	 * Returns the bike usage
	 * @return string Bike usage
	 */
	public function getUsageName(): string {
		return $this->usage->getName();
	}

	/**
	 * Returns the bike picture
	 * @return string Bike picture
	 */
	public function getPicture(): string {
		return $this->picture;
	}

	/**
	 * Returns the bike frame material
	 * @return string Bike frame material
	 */
	public function getFrameMaterial(): string {
		return $this->frameMaterial;
	}

	/**
	 * Returns the bike frame size
	 * @return string Bike frame size
	 */
	public function getFrameSize(): string {
		return $this->frameSize;
	}

	/**
	 * Returns the bike wheel size
	 * @return string Bike wheel size
	 */
	public function getWheelSize(): string {
		return $this->wheelSize;
	}

	/**
	 * Returns the bike fork travel
	 * @return string Bike fork travel
	 */
	public function getForkTravel(): string {
		return strval($this->forkTravel) ?? '-';
	}

	/**
	 * Returns the bike rear shock travel
	 * @return string Bike rear shock travel
	 */
	public function getShockTravel(): string {
		return strval($this->shockTravel) ?? '-';
	}

	/**
	 * Return the bike speeds
	 * @return string Bike speeds
	 */
	public function getSpeeds(): string {
		return $this->speeds;
	}

	/**
	 * Returns the bike rental price
	 * @return int Bike rental price
	 */
	public function getPrice(): int {
		return $this->price;
	}

	/**
	 * Sets the bike manufacturer
	 * @param Manufacturer $manufacturer Bike manufacturer
	 */
	public function setManufacturer(Manufacturer $manufacturer): void {
		$this->manufacturer = $manufacturer;
	}

	/**
	 * Sets the bike name
	 * @param string $name Bike name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}

	/**
	 * Sets the bike usage
	 * @param BikeUsage $usage Bike usage
	 */
	public function setUsage(BikeUsage $usage): void {
		$this->usage = $usage;
	}

	/**
	 * Sets the bike picture
	 * @param string $picture Bike picture
	 */
	public function setPicture(string $picture): void {
		$this->picture = $picture;
	}

	/**
	 * Sets the bike frame material
	 * @param string $material Bike frame material
	 */
	public function setFrameMaterial(string $material): void {
		$this->frameMaterial = $material;
	}

	/**
	 * Sets the bike frame size
	 * @param string $size Bike frame size
	 */
	public function setFrameSize(string $size): void {
		$this->frameSize = $size;
	}

	/**
	 * Sets the bike wheel size
	 * @param string $size Bike wheel size
	 */
	public function setWheelSize(string $size): void {
		$this->wheelSize = $size;
	}

	/**
	 * Sets the bike fork travel
	 * @param int|null $travel Bike fork travel
	 */
	public function setForkTravel(?int $travel): void {
		$this->shockTravel = $travel;
	}

	/**
	 * Sets the bike rear shock travel
	 * @param int|null $travel Bike rear shock travel
	 */
	public function setShockTravel(?int $travel): void {

	}

	/**
	 * Sets the bike speeds
	 * @param string $speeds Bike speeds
	 */
	public function setSpeeds(string $speeds): void {
		$this->speeds = $speeds;
	}

	/**
	 * Sets the bike rental price
	 * @param int $price Bike rental price
	 */
	public function setPrice(int $price): void {
		$this->price = $price;
	}

}
