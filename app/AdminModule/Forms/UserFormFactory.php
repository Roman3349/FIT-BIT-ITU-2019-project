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

namespace App\AdminModule\Forms;

use App\AdminModule\Presenters\UserPresenter;
use App\CoreModule\Forms\FormFactory;
use App\Models\Database\Entities\User;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\SmartObject;

/**
 * User editor form factory
 */
final class UserFormFactory {

	use SmartObject;

	/**
	 * @var EntityManager Entity manager
	 */
	private $manager;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var int|null User ID
	 */
	private $id;

	/**
	 * @var Passwords Password manager
	 */
	private $passwords;

	/**
	 * @var UserPresenter User manager presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param EntityManager $manager Entity manager
	 * @param Passwords $passwords Password manager
	 */
	public function __construct(FormFactory $factory, EntityManager $manager, Passwords $passwords) {
		$this->factory = $factory;
		$this->manager = $manager;
		$this->passwords = $passwords;
	}

	public function create(UserPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->id = intval($this->presenter->getParameter('id'));
		$this->factory->setTranslationPrefix('admin.user');
		$form = $this->factory->create();
		$form->addText('firstName', 'firstName')
			->setRequired('messages.firstName');
		$form->addText('lastName', 'lastName')
			->setRequired('messages.lastName');
		$form->addEmail('email', 'email')
			->setRequired('messages.email');
		$form->addSelect('role', 'role')
			->setItems($this->getRoles())
			->setPrompt('messages.role')
			->setRequired('messages.role');
		$form->addSelect('state', 'state')
			->setItems($this->getStates())
			->setPrompt('messages.state')
			->setRequired('messages.state');
		$form->addProtection();
		if ($this->presenter->getAction() === 'edit') {
			$form->setDefaults($this->load($this->id));
			$form->addSubmit('save', 'save');
		} else {
			$form->addPassword('password', 'password')
				->setRequired('messages.password');
			$form->addSubmit('add', 'add');
		}
		$form->onSubmit[] = [$this, 'save'];
		return $form;
	}

	/**
	 * Returns all available user roles
	 * @return array<string,string> User roles
	 */
	private function getRoles(): array {
		$roles = [];
		foreach (User::ROLES as $role) {
			$roles[$role] = 'roles.' . $role;
		}
		return $roles;
	}

	/**
	 * Returns all available user states
	 * @return array<int,string> User states
	 */
	private function getStates(): array {
		return [
			User::STATE_ACTIVATED => 'states.activated',
			User::STATE_BLOCKED => 'states.blocked',
			User::STATE_FRESH => 'states.fresh'
		];
	}

	/**
	 * Loads data from the database
	 * @param int $id User ID
	 * @return array<string,mixed> Data for the form
	 */
	private function load(int $id): array {
		/**
		 * @var User User entity
		 */
		$user = $this->manager->getUserRepository()->find(intval($id));
		return [
			'firstName' => $user->getFirstName(),
			'lastName' => $user->getLastName(),
			'email' => $user->getEmail(),
			'role' => $user->getRole(),
			'state' => $user->getState(),
		];
	}

	/**
	 * Saves values from the form
	 * @param Form $form Manufacturer form
	 */
	public function save(Form $form): void {
		$values = $form->getValues();
		$repository = $this->manager->getUserRepository();
		/**
		 * @var User|null User entity
		 */
		$user = $repository->find($this->id);
		$translator = $this->presenter->translator;
		if ($user == null) {
			if ($repository->findOneByEmail($values->email) !== null) {
				$message = $translator->translate('messages.failureDuplicate', ['email' => $values->email]);
				$form['email']->addError($message);
				return;
			}
			$hash = $this->passwords->hash($values->password);
			$user = new User($values->firstName, $values->lastName, $values->email, $hash, $values->role, $values->state);
		} else {
			$user->setFirstName($values->firstName);
			$user->setLastName($values->lastName);
			$user->setEmail($values->email);
			$user->setRole($values->role);
			$user->setState($values->state);
		}
		$this->manager->persist($user);
		$this->manager->flush();
		if ($this->presenter->user->id === $user->getId()) {
			$this->presenter->user->logout();
		}
		$message = $translator->translate('admin.user.messages.successEdit', ['email' => $user->getEmail()]);
		$this->presenter->flashSuccess($message);
		$this->presenter->redirect('User:default');
	}

}