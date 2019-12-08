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

namespace App\AdminModule\Presenters;

use App\CoreModule\Presenters\BasePresenter as CorePresenter;
use App\CoreModule\Traits\TPresenterFlashMessage;
use App\Models\Database\Entities\User;
use Nette\Security\IUserStorage;

/**
 * Administration base presenter
 */
abstract class BasePresenter extends CorePresenter {

	use TPresenterFlashMessage;

	/**
	 * Checks requirements
	 * @param mixed $element Element
	 */
	public function checkRequirements($element): void {
		if (!$this->user->isLoggedIn()) {
			if ($this->user->getLogoutReason() === IUserStorage::INACTIVITY) {
				$this->flashInfo('core.sign.out.inactivity');
			}
			$this->redirect(':Core:Sign:In', ['backlink' => $this->storeRequest()]);
		}
		if ($this->user->isInRole(User::ROLE_CUSTOMER)) {
			$this->redirect(':Core:Product:default');
		}
		parent::checkRequirements($element);
	}

}