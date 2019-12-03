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

use App\AdminModule\DataGrids\BikeDataGrid;
use App\AdminModule\Forms\BikeFormFactory;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridColumnStatusException;
use Ublaboo\DataGrid\Exception\DataGridException;

/**
 * Bike manager presenter
 */
final class BikePresenter extends BasePresenter {

	/**
	 * @var BikeFormFactory Bike manager form factory
	 * @inject
	 */
	public $formFactory;

	/**
	 * @var BikeDataGrid Bike data grid factory
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
	 * Renders bike editor page
	 * @param int $id Bike ID
	 */
	public function renderEdit(int $id): void {
	}

	/**
	 * Creates a new bike editor form
	 * @return Form Bike editor form
	 */
	protected function createComponentBikeForm(): Form {
		return $this->formFactory->create($this);
	}

	/**
	 * Creates a new bike data grid
	 * @param string $name Component name
	 * @return DataGrid Bike data grid
	 * @throws DataGridColumnStatusException
	 * @throws DataGridException
	 */
	protected function createComponentBikeGrid(string $name): DataGrid {
		return $this->dataGrid->create($this, $name);
	}

}