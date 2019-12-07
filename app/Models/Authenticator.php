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

use App\Models\Database\EntityManager;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\SmartObject;

/**
 * Authenticator
 */
final class Authenticator implements IAuthenticator {

	use SmartObject;

	/**
	 * @var EntityManager Entity manager
	 */
	private $entityManager;

	/**
	 * @var Passwords Password manager
	 */
	private $passwordManager;

	/**
	 * Constructor
	 * @param EntityManager $manager Entity manager
	 * @param Passwords $passwords Password manager
	 */
	public function __construct(EntityManager $manager, Passwords $passwords) {
		$this->entityManager = $manager;
		$this->passwordManager = $passwords;
	}

	/**
	 * Performs an authentication
	 * @param string[] $credentials Authentication credentials
	 * @return IIdentity Nette Identity
	 * @throws AuthenticationException
	 */
	function authenticate(array $credentials): IIdentity {
		[$email, $password] = $credentials;

		$user = $this->entityManager->getUserRepository()->findOneByEmail($email);
		if ($user === null) {
			throw new AuthenticationException('The email is incorrect.');
		} elseif (!$this->passwordManager->verify($password, $user->getHash())) {
			throw new AuthenticationException('The password is incorrect.');
		}
		$this->entityManager->flush();
		return $user->toIdentity();
	}
}