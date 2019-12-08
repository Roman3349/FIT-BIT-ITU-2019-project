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

namespace App\AdminModule\DataGrids;

use App\AdminModule\Presenters\UserPresenter;
use App\CoreModule\Datagrids\DataGridFactory;
use App\Models\Database\Entities\User;
use App\Models\Database\EntityManager;
use Nette\SmartObject;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridColumnStatusException;
use Ublaboo\DataGrid\Exception\DataGridException;

/**
 * Users data grid
 */
final class UserDataGrid {

	use SmartObject;

	/**
	 * @var DataGridFactory Data grid factory
	 */
	private $dataGridFactory;

	/**
	 * @var EntityManager Entity manager
	 */
	private $manager;

	/**
	 * @var UserPresenter User manager presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param DataGridFactory $dataGridFactory Generic data grid factory
	 * @param EntityManager $manager Entity manager
	 */
	public function __construct(DataGridFactory $dataGridFactory, EntityManager $manager) {
		$this->dataGridFactory = $dataGridFactory;
		$this->manager = $manager;
	}

	/**
	 * Creates the user data grid
	 * @param UserPresenter $presenter User presenter
	 * @param string $name Data grid's component name
	 * @return DataGrid User data grid
	 * @throws DataGridColumnStatusException
	 * @throws DataGridException
	 */
	public function create(UserPresenter $presenter, string $name): DataGrid {
		$this->presenter = $presenter;
		$grid = $this->dataGridFactory->create($presenter, $name);
		$grid->setDataSource($this->manager->getUserRepository()->createQueryBuilder('u'));
		$grid->addColumnNumber('id', 'admin.user.id')
			->setAlign('left')
			->setSortable();
		$grid->addColumnText('first_name', 'admin.user.firstName', 'firstName')
			->setSortable()
			->setFilterText();
		$grid->addColumnText('last_name', 'admin.user.lastName', 'lastName')
			->setSortable()
			->setFilterText();
		$grid->addColumnText('email', 'admin.user.email')
			->setSortable()
			->setFilterText();
		$grid->addColumnStatus('role', 'admin.user.role')
			->addOption('admin', 'admin.user.roles.admin')
			->endOption()
			->addOption('employee', 'admin.user.roles.employee')
			->endOption()
			->addOption('customer', 'admin.user.roles.customer')
			->endOption()
			->setSortable()
			->onChange[] = [$this, 'changeRole'];
		$grid->addFilterMultiSelect('role', 'admin.user.role', $this->getRoles());
		$grid->addColumnStatus('state', 'admin.user.state')
			->addOption(User::STATE_ACTIVATED, 'admin.user.states.activated')
			->setClass('btn-success')
			->endOption()
			->addOption(User::STATE_BLOCKED, 'admin.user.states.blocked')
			->setClass('btn-danger')
			->endOption()
			->addOption(User::STATE_FRESH, 'admin.user.states.fresh')
			->setClass('btn-primary')
			->endOption()
			->setSortable()
			->onChange[] = [$this, 'changeState'];
		$grid->addFilterMultiSelect('state', 'admin.user.state', $this->getStates());
		$grid->addAction('edit', 'admin.actions.edit')
			->setIcon('user-edit')
			->setClass('btn btn-xs btn-info');
		$grid->addAction('delete', 'admin.actions.delete')
			->setIcon('user-slash')
			->setClass('btn btn-xs btn-danger ajax')
			->setConfirmation(new StringConfirmation('admin.user.messages.confirmDelete', 'email'));
		$grid->addToolbarButton('add', 'admin.actions.add')
			->setClass('btn btn-success');
		return $grid;
	}

	/**
	 * Returns all available roles
	 * @return string[] Roles
	 */
	private function getRoles(): array {
		$roles = [
			'admin',
			'employee',
			'customer',
		];
		return array_map(function (string $role): string {
			$prefix = 'admin.user.roles.';
			return $this->presenter->translator->translate($prefix . $role);
		}, $roles);
	}

	/**
	 * Returns all available states
	 * @return string[] States
	 */
	private function getStates(): array {
		$states = [
			User::STATE_ACTIVATED => 'admin.user.states.activated',
			User::STATE_BLOCKED => 'admin.user.states.blocked',
			User::STATE_FRESH => 'admin.user.states.fresh'
		];
		return array_map(function (string $state): string {
			return $this->presenter->translator->translate($state);
		}, $states);
	}

	/**
	 * Changes the user's role
	 * @param string $id User ID
	 * @param string $role New user's role
	 */
	public function changeRole(string $id, string $role): void {
		$user = $this->manager->getUserRepository()->find($id);
		$user->setRole($role);
		$this->manager->persist($user);
		$this->manager->flush();
		$this->presenter->flashSuccess('admin.user.messages.successChangeRole');
		if ($this->presenter->isAjax()) {
			$this->presenter->redrawControl('flashes');
			$dataGrid = $this->presenter['userGrid'];
			$dataGrid->reloadTheWholeGrid();
		}
	}

	/**
	 * Changes the user's state
	 * @param string $id User ID
	 * @param string $state New user's state
	 */
	public function changeState(string $id, string $state): void {
		$user = $this->manager->getUserRepository()->find($id);
		$user->setState(intval($state));
		$this->manager->persist($user);
		$this->manager->flush();
		$this->presenter->flashSuccess('admin.user.messages.successChangeState');
		if ($this->presenter->isAjax()) {
			$this->presenter->redrawControl('flashes');
			$dataGrid = $this->presenter['userGrid'];
			$dataGrid->reloadTheWholeGrid();
		}
	}

}