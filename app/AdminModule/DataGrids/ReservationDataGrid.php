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

use App\AdminModule\Presenters\ReservationPresenter;
use App\CoreModule\DataGrids\DataGridFactory;
use App\Models\Database\Entities\Reservation;
use App\Models\Database\EntityManager;
use Nette\SmartObject;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;

/**
 * Reservation manager data grid
 */
final class ReservationDataGrid {

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
	 * @var ReservationPresenter Reservation manager presenter
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
	 * Creates a new reservation manager data grid
	 * @param ReservationPresenter $presenter Reservation manager presenter
	 * @param string $name Component name
	 * @return DataGrid Reservation manager data grid
	 */
	public function create(ReservationPresenter $presenter, string $name): DataGrid {
		$this->presenter = $presenter;
		$prefix = 'admin.reservation.';
		$states = $this->listStates();
		$grid = $this->dataGridFactory->create($presenter, $name);
		$grid->setRememberState(false);
		$grid->setDataSource($this->manager->getReservationRepository()->createQueryBuilder('r'));
		$grid->addColumnNumber('id', 'admin.reservation.id')
			->setAlign('left')
			->setSortable();
		$grid->addColumnText('customer.fullName', $prefix . 'customer');
		$grid->addColumnDateTime('fromDate', $prefix . 'fromDate')
			->setSortable();
		$grid->addFilterDateRange('fromDate', 'fromDate');
		$grid->addColumnDateTime('toDate', $prefix . 'toDate')
			->setSortable();
		$grid->addFilterDateRange('toDate', 'toDate');
		$grid->addColumnStatus('state', $prefix . 'state')
			->addOption(Reservation::STATE_RESERVATION, $states[Reservation::STATE_RESERVATION])
			->setClass('btn btn-secondary')
			->endOption()
			->addOption(Reservation::STATE_CANCELLED, $states[Reservation::STATE_CANCELLED])
			->setClass('btn btn-dark')
			->endOption()
			->addOption(Reservation::STATE_ONGOING, $states[Reservation::STATE_ONGOING])
			->setClass('btn btn-primary')
			->endOption()
			->addOption(Reservation::STATE_RETURNED, $states[Reservation::STATE_RETURNED])
			->setClass('btn btn-success')
			->endOption()
			->addOption(Reservation::STATE_DELAYED, $states[Reservation::STATE_DELAYED])
			->setClass('btn btn-danger')
			->endOption()
			->setSortable()
			->onChange[] = [$this, 'changeState'];
		$grid->addFilterMultiSelect('state', 'state', $states);
		$grid->addColumnText('price', $prefix . 'price')
			->setSortable()
			->setFilterText();
		$grid->addAction('edit', 'admin.actions.edit')
			->setIcon('user-edit')
			->setClass('btn btn-xs btn-info');
		$grid->addAction('delete', 'admin.actions.delete')
			->setIcon('user-slash')
			->setClass('btn btn-xs btn-danger ajax')
			->setConfirmation(new StringConfirmation($prefix . 'messages.confirmDelete', 'id'));
		$grid->addToolbarButton('add', 'admin.actions.add')
			->setClass('btn btn-xs btn-success');
		return $grid;
	}

	/**
	 * Changes the reservation state
	 * @param string $id Reservation ID
	 * @param string $state Reservation state
	 */
	public function changeState(string $id, string $state): void {
		if ($state === '4') {
			return;
		}
		$reservation = $this->manager->getReservationRepository()->find(intval($id));
		$reservation->setState(intval($state));
		$this->manager->persist($reservation);
		$this->manager->flush();
		$this->presenter->flashSuccess('admin.reservation.messages.successChangeState');
		if ($this->presenter->isAjax()) {
			$this->presenter->redrawControl('flashes');
			$dataGrid = $this->presenter['reservationGrid'];
			$dataGrid->reloadTheWholeGrid();
		}
	}

	/**
	 * Returns all reservation states
	 * @return string[] Reservation states
	 */
	private function listStates(): array {
		$prefix = 'admin.reservation.';
		$states = [
			Reservation::STATE_RESERVATION => $prefix . 'states.reservation',
			Reservation::STATE_CANCELLED => $prefix . 'states.cancelled',
			Reservation::STATE_ONGOING => $prefix . 'states.ongoing',
			Reservation::STATE_RETURNED => $prefix . 'states.returned',
			Reservation::STATE_DELAYED => $prefix . 'states.delayed',
		];
		$array = [];
		foreach ($states as $state) {
			$array[] = $this->presenter->translator->translate($state);
		}
		return $array;
	}

}