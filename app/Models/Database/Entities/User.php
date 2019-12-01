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
use Nette\Security\Identity;

/**
 * User entity
 * @ORM\Entity(repositoryClass="App\Models\Database\Repositories\UserRepository")
 * @ORM\Table(name="`users`")
 * @ORM\HasLifecycleCallbacks()
 */
class User {

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	public const ROLE_ADMIN = 'admin';
	public const ROLE_EMPLOYEE = 'employee';
	public const ROLE_CUSTOMER = 'customer';

	public const ROLES = [
		self::ROLE_ADMIN,
		self::ROLE_EMPLOYEE,
		self::ROLE_CUSTOMER,
	];

	public const STATE_ACTIVATED = 1;
	public const STATE_BLOCKED = 2;
	public const STATE_FRESH = 3;

	public const STATES = [
		self::STATE_ACTIVATED,
		self::STATE_BLOCKED,
		self::STATE_FRESH,
	];

	/**
	 * @var string First name
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=FALSE)
	 */
	private $firstName;

	/**
	 * @var string Last name
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=FALSE)
	 */
	private $lastName;

	/**
	 * @var string E-mail
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE)
	 */
	private $email;

	/**
	 * @var string Password hash
	 * @ORM\Column(type="string", length=255, nullable=FALSE, unique=FALSE)
	 */
	private $hash;

	/**
	 * @var string User role
	 * @ORM\Column(type="string", length=15, nullable=FALSE, unique=FALSE)
	 */
	private $role;

	/**
	 * @var int User state
	 * @ORM\Column(type="integer", length=10, nullable=FALSE, unique=FALSE)
	 */
	private $state;

	/**
	 * Constructor
	 * @param string $firstName First name
	 * @param string $lastName Last name
	 * @param string $email E-mail
	 * @param string $hash Password hash
	 */
	public function __construct(string $firstName, string $lastName, string $email, string $hash) {
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->hash = $hash;
		$this->role = self::ROLE_CUSTOMER;
		$this->state = self::STATE_FRESH;
	}

	/**
	 * Activates the user
	 */
	public function activate(): void {
		$this->state = self::STATE_ACTIVATED;
	}

	/**
	 * Blocks the user
	 */
	public function block(): void {
		$this->state = self::STATE_BLOCKED;
	}

	/**
	 * Returns e-mail address
	 * @return string E-mail
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * Returns the first name
	 * @return string First name
	 */
	public function getFirstName(): string {
		return $this->firstName;
	}

	/**
	 * Return the last name
	 * @return string Last name
	 */
	public function getLastName(): string {
		return $this->lastName;
	}

	/**
	 * Returns the full name
	 * @return string Full name
	 */
	public function getFullName(): string {
		return $this->firstName . ' ' . $this->lastName;
	}

	/**
	 * Returns the password hash
	 * @return string Password hash
	 */
	public function getHash(): string {
		return $this->hash;
	}

	/**
	 * Returns the user's role
	 * @return string User role
	 */
	public function getRole(): string {
		return $this->role;
	}

	/**
	 * Returns the user's account state
	 * @return int User's account state
	 */
	public function getState(): int {
		return $this->state;
	}

	/**
	 * Sets the user's role
	 * @param string $role User's role
	 */
	public function setRole(string $role): void {
		$this->role = $role;
	}

	/**
	 * Sets the user's state
	 * @param int $state User's state
	 */
	public function setState(int $state): void {
		$this->state = $state;
	}

	/**
	 * Returns user's identity
	 * @return Identity User identity
	 */
	public function toIdentity(): Identity {
		return new Identity($this->getId(), [$this->role], [
			'email' => $this->email,
			'name' => $this->firstName . ' ' . $this->lastName,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'state' => $this->state,
		]);
	}

}