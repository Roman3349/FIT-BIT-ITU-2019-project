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

namespace App\Forms;

use App\Models\UserManager;
use App\Presenters\SignPresenter;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Sign up form factory
 */
final class SignUpFormFactory {

	use SmartObject;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var SignPresenter Sign (in|out|up) presenter
	 */
	private $presenter;

	/**
	 * @var UserManager User manager
	 */
	private $userManager;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param UserManager $userManager User manager
	 */
	public function __construct(FormFactory $factory, UserManager $userManager) {
		$this->factory = $factory;
		$this->userManager = $userManager;
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
		$this->userManager->register($values->firstName, $values->lastName, $values->email, $values->password, 0);
		$this->presenter->flashSuccess('core.sign.up.messages.success');
		$this->presenter->redirect(':Homepage:default');
	}

}