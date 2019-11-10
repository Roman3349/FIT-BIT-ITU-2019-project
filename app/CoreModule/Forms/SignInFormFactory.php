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
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\SmartObject;

/**
 * Sign in form factory
 */
final class SignInFormFactory {

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
	 * @var User User entity
	 */
	private $user;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param User $user User entity
	 */
	public function __construct(FormFactory $factory, User $user) {
		$this->factory = $factory;
		$this->user = $user;
	}

	/**
	 * Creates sign in form
	 * @param SignPresenter $presenter Sign (in|out|up) presenter
	 * @return Form Sign in form
	 */
	public function create(SignPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('core.sign.in.form');
		$form = $this->factory->create();
		$form->addEmail('email', 'email')
			->setRequired('messages.email');
		$form->addPassword('password', 'password')
			->setRequired('messages.password');
		$form->addCheckbox('remember', 'remember');
		$form->addSubmit('send', 'send');
		$form->onSuccess[] = [$this, 'signIn'];
		return $form;
	}

	/**
	 * Signs user in
	 * @param Form $form Sign in form
	 */
	public function signIn(Form $form): void {
		$values = $form->getValues();
		try {
			$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
			$this->user->login($values->email, $values->password);
			$this->presenter->flashSuccess('core.sign.in.form.messages.success');
			if ($this->presenter->backlink === null) {
				$this->presenter->redirect(':Core:Product:default');
			} else {
				$this->presenter->restoreRequest($this->presenter->backlink);
			}
		} catch (AuthenticationException $e) {
			$this->presenter->flashError('core.sign.in.form.messages.failure');
		}
	}



}