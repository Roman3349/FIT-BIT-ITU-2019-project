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

use App\AdminModule\DataGrids\ReservationDataGrid;
use App\AdminModule\Forms\ReservationFormFactory;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;
use Ublaboo\DataGrid\DataGrid;

/**
 * Reservation presenter
 */
final class ReservationPresenter extends BasePresenter {

	/**
	 * @var ReservationFormFactory Reservation editor form factory
	 * @inject
	 */
	public $formFactory;

	/**
	 * @var ReservationDataGrid Reservation manager data grid
	 * @inject
	 */
	public $dataGrid;

	/**
	 * @var EntityManager Entity manager
	 */
	private $manager;

	/**
	 * Constructor
	 * @param EntityManager $manager Entity manager
	 */
	public function __construct(EntityManager $manager) {
		$this->manager = $manager;
	}

	/**
	 * Renders reservation editor
	 * @param int $id Reservation ID
	 */
	public function renderEdit(int $id): void {
	}

	/**
	 * Creates a new reservation editor form
	 * @return Form Reservation editor form
	 */
	protected function createComponentReservationForm(): Form {
		return $this->formFactory->create($this);
	}

	/**
	 * Creates a new reservation manager data grid
	 * @param string $name Component name
	 * @return DataGrid Reservation manager data grid
	 */
	protected function createComponentReservationGrid(string $name): DataGrid {
		return $this->dataGrid->create($this, $name);
	}

}