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

use App\AdminModule\DataGrids\UserDataGrid;
use App\CoreModule\Models\UserManager;
use Contributte\Translation\Wrappers\Message;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridColumnStatusException;
use Ublaboo\DataGrid\Exception\DataGridException;

/**
 * User manager presenter
 */
final class UserPresenter extends BasePresenter {

	/**
	 * @var UserDataGrid User data grid factory
	 * @inject
	 */
	public $dataGrid;

	/**
	 * @var UserManager User manager
	 */
	private $manager;

	/**
	 * Constructor
	 * @param UserManager $manager User manager
	 */
	public function __construct(UserManager $manager) {
		$this->manager = $manager;
	}

	/**
	 * Deletes an user
	 * @param int $id User ID
	 */
	public function actionDelete(int $id): void {
		$user = $this->manager->get($id);
		$this->manager->delete($id);
		if ($this->user->id === $id) {
			$this->user->logout(true);
		}
		$message = $this->translator->translate('admin.user.messages.successDelete', ['email' => $user['email']]);
		$this->flashSuccess($message);
		$this->redirect('User:default');
		$this->setView('default');
	}

	/**
	 * Creates the data grid
	 * @param string $name Component name
	 * @return DataGrid Datagrid with users
	 * @throws DataGridColumnStatusException
	 * @throws DataGridException
	 */
	protected function createComponentUserGrid(string $name): DataGrid {
		return $this->dataGrid->create($this, $name);
	}

}