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

namespace App\CoreModule\Forms;

use App\CoreModule\Presenters\SignPresenter;
use App\Models\Database\Entities\User;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\SmartObject;

/**
 * Sign up form factory
 */
final class SignUpFormFactory {

	use SmartObject;

	/**
	 * @var EntityManager Entity manager
	 */
	private $entityManager;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var Passwords Password manager
	 */
	private $passwordManager;

	/**
	 * @var SignPresenter Sign (in|out|up) presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param EntityManager $entityManager Entity manager
	 * @param Passwords $passwordManager Password manager
	 */
	public function __construct(FormFactory $factory, EntityManager $entityManager, Passwords $passwordManager) {
		$this->factory = $factory;
		$this->entityManager = $entityManager;
		$this->passwordManager = $passwordManager;
	}

	/**
	 * Creates sign up form
	 * @param SignPresenter $presenter Sign (in|out|up) presenter
	 * @return Form Sign in form
	 */
	public function create(SignPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('core.sign.up.form');
		$form = $this->factory->create();
		$form->addText('firstName', 'firstName');
		$form->addText('lastName', 'lastName');
		$form->addEmail('email', 'email')
			->setRequired('messages.email');
		$form->addPassword('password', 'password')
			->setRequired('messages.password');
		$form->addCheckbox('termsAgreement', 'termsAgreement');
		$form->addSubmit('send', 'send');
		$form->onSuccess[] = [$this, 'signUp'];
		return $form;
	}

	/**
	 * Signs up a new user
	 * @param Form $form Sign up form
	 */
	public function signUp(Form $form): void {
		$values = $form->getValues();
		$hash = $this->passwordManager->hash($values->password);
		$user = new User($values->firstName, $values->lastName, $values->email, $hash);
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		$this->presenter->flashSuccess('core.sign.up.messages.success');
		$this->presenter->redirect(':Core:Product:default');
	}

}