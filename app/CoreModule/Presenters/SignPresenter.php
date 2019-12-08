<?php

/*
 * Copyright (C) 2019 Roman Ondráček <xondra58@stud.fit.vutbr.cz>, Karel Fiedler <xfiedl04@stud.fit.vutbr.cz>
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

namespace App\CoreModule\Presenters;

use App\CoreModule\Forms\SignInFormFactory;
use App\CoreModule\Forms\SignUpFormFactory;
use App\CoreModule\Forms\SignResetFormFactory;
use App\CoreModule\Traits\TPresenterFlashMessage;
use Nette\Application\UI\Form;

/**
 * Sign in/up/out presenter
 */
final class SignPresenter extends BasePresenter {

	use TPresenterFlashMessage;

	/**
	 * @var string Back link
	 * @persistent
	 */
	public $backlink;

	/**
	 * @var SignInFormFactory Sign in form factory
	 * @inject
	 */
	public $signInFactory;

	/**
	 * @var SignUpFormFactory Sign up form factory
	 * @inject
	 */
	public $signUpFactory;

    /**
     * @var SignResetFormFactory Sign up form factory
     * @inject
     */
    public $signResetFactory;

	/**
	 * Signs user out
	 */
	public function actionOut(): void {
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Core:Sign:in');
		} else {
			$this->getUser()->logout();
			$this->redirect(':Core:Sign:in');
		}
	}

	/**
	 * Creates the sign in form
	 * @return Form Sign in form
	 */
	protected function createComponentSignInForm(): Form {
		return $this->signInFactory->create($this);
	}

	/**
	 * Creates the sign up form
	 * @return Form Sign up form
	 */
	protected function createComponentSignUpForm(): Form {
		return $this->signUpFactory->create($this);
	}

    /**
     * Creates the reset password form
     * @return Form Reset password form
     */
    protected function createComponentSignResetForm(): Form {
        return $this->signResetFactory->create($this);
    }
}