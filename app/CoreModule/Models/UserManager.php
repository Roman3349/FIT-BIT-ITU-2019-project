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

use Nette\Database\Context;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;

/**
 * User manager
 */
final class UserManager {

	/**
	 * @var Context Database context
	 */
	private $database;

	/**
	 * @var Passwords Passwords service
	 */
	private $passwords;

	/**
	 * Constructor
	 * @param Context $database Database context
	 * @param Passwords $passwords Passwords service
	 */
	public function __construct(Context $database, Passwords $passwords) {
		$this->database = $database;
		$this->passwords = $passwords;
	}

	/**
	 * Logs in the user
	 * @param string $email E-mail
	 * @param string $password Password
	 * @return IIdentity Identity emtity
	 * @throws AuthenticationException
	 */
	public function login(string $email, string $password): IIdentity {
		$table = $this->database->table('users');
		$row = $table->where('email', $email)->fetch();
		if ($row === null) {
			throw new AuthenticationException('User not found.');
		}
		if (!$this->passwords->verify($password, $row->hash)) {
			throw new AuthenticationException('Invalid password.');
		}
		$data = [
			'firstName' => $row->firstName,
			'lastName' => $row->lastName,
			'name' => $row->firstName . ' ' . $row->lastName,
			'email' => $row->email,
		];
		return new Identity($row['id'], $row['role'], $data);
	}

	/**
	 * Register a new user
	 * @param string $firstName First name
	 * @param string $lastName Last name
	 * @param string $email E-mail
	 * @param string $password Password
	 * @param int $role User role
	 * @throws \Exception
	 */
	public function register(string $firstName, string $lastName, string $email, string $password, int $role): void {
		$table = $this->database->table('users');
		$row = $table->where('email', $email)->fetch();
		if ($row !== null) {
			throw new \Exception();
		}
		$data = [
			'firstName' => $firstName,
			'lastName' => $lastName,
			'email' => $email,
			'hash' => $this->passwords->hash($password),
			'role' => $role,
		];
		$table->insert($data);
	}

}