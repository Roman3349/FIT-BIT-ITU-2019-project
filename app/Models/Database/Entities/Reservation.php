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
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation entity
 * @ORM\Entity(repositoryClass="App\Models\Database\Repositories\ReservationRepository")
 * @ORM\Table(name="`reservations`")
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation {

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/**
	 * @var User User
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $customer;

	/**
	 * @var ?User User
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
	 */
	private $createdBy;

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime", nullable=FALSE)
	 */
	private $fromDate;

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime", nullable=FALSE)
	 */
	private $toDate;

	/**
	 * @ORM\ManyToMany(targetEntity="Bike")
	 * @ORM\JoinColumn(name="bikes", referencedColumnName="id")
	 */
	private $bikes;

	/**
	 * @var int Reservation state
	 * @ORM\Column(type="integer", length=10, nullable=FALSE, unique=FALSE)
	 */
	private $state;

	/**
	 * @var int Rental price
	 * @ORM\Column(type="integer", length=10, nullable=FALSE, unique=FALSE)
	 */
	private $price;

	/**
	 * New reservation
	 */
	public const STATE_RESERVATION = 0;

	/**
	 * Cancelled reservation
	 */
	public const STATE_CANCELLED = 1;

	/**
	 * Ongoing rental
	 */
	public const STATE_ONGOING = 2;

	/**
	 * Returned rental
	 */
	public const STATE_RETURNED = 3;

	/**
	 * Delayed return rental
	 */
	public const STATE_DELAYED = 4;

	/**
	 * Reservation states
	 */
	public const STATES = [
		self::STATE_RESERVATION,
		self::STATE_CANCELLED,
		self::STATE_ONGOING,
		self::STATE_RETURNED,
		self::STATE_DELAYED,
	];

	/**
	 * Constructor
	 * @param User $user Customer
	 * @param User $createdBy Created by
	 * @param DateTime $fromDate From date
	 * @param DateTime $toDate To date
	 * @param Bike[] $bikes Bikes
	 * @param int $state State
	 */
	public function __construct(User $user, User $createdBy, DateTime $fromDate, DateTime $toDate, array $bikes, int $state) {
		$this->customer = $user;
		$this->createdBy = $createdBy;
		$this->fromDate = $fromDate;
		$this->toDate = $toDate;
		$this->bikes = new ArrayCollection($bikes);
		$this->state = $state;
		$this->price = 0;
		foreach ($bikes as $bike) {
			$this->price += $bike->getPrice();
		}
		$this->price *= $this->fromDate->diff($this->toDate)->d;
	}

	/**
	 * Returns the customer
	 * @return User Customer
	 */
	public function getCustomer(): User {
		return $this->customer;
	}

	/**
	 * Returns the reservation creator
	 * @return User Reservation creator
	 */
	public function getCreatedBy(): User {
		return $this->createdBy;
	}

	/**
	 * Returns the reservation start date
	 * @return DateTime Reservation start date
	 */
	public function getFromDate(): DateTime {
		return $this->fromDate;
	}

	/**
	 * Returns the reservation end date
	 * @return DateTime Reservation end date
	 */
	public function getToDate(): DateTime {
		return $this->toDate;
	}

	/**
	 * Returns the reserved bikes
	 * @return Collection Reserved bikes
	 */
	public function getBikes(): Collection {
		return $this->bikes;
	}

	/**
	 * Returns the reservation state
	 * @return int Reservation state
	 */
	public function getState(): int {
		if ($this->state == self::STATE_ONGOING &&
			$this->fromDate->diff(new DateTime(), true)->s <= 0) {
			return self::STATE_DELAYED;
		}
		return $this->state;
	}

	/**
	 * Returns the reservation rental price
	 * @return int Rental price
	 */
	public function getPrice(): int {
		return $this->price;
	}

	/**
	 * Sets the reservation customer
	 * @param User $customer Customer
	 */
	public function setCustomer(User $customer): void {
		$this->customer = $customer;
	}

	/**
	 * Sets the reservation creator
	 * @param User $creator Reservation creator
	 */
	public function setCreatedBy(User $creator): void {
		$this->createdBy = $creator;
	}

	/**
	 * Sets the reservation start date
	 * @param DateTime $date Reservation start date
	 */
	public function setFromDate(DateTime $date): void {
		$this->fromDate = $date;
	}

	/**
	 * Sets the reservation end date
	 * @param DateTime $date Reservation end date
	 */
	public function setToDate(DateTime $date): void {
		$this->toDate = $date;
	}

	/**
	 * Sets the reserved bikes
	 * @param ArrayCollection $bikes Reserved bikes
	 */
	public function setBikes(ArrayCollection $bikes): void {
		$this->bikes = $bikes;
	}

	/**
	 * Sets the reservation state
	 * @param int $state Reservation state
	 */
	public function setState(int $state): void {
		$this->state = $state;
	}

	/**
	 * Sets the rental price
	 * @param int $price Rental price
	 */
	public function setPrice(int $price): void {
		$this->price = $price;
	}

}