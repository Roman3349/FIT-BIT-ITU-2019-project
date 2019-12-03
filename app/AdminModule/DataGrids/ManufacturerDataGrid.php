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

use App\AdminModule\Presenters\ManufacturerPresenter;
use App\CoreModule\DataGrids\DataGridFactory;
use App\Models\Database\EntityManager;
use Nette\SmartObject;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridException;

/**
 * Manufacturer data grid
 */
final class ManufacturerDataGrid {

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
	 * @var ManufacturerPresenter Manufacturer manager presenter
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
	 * Creates a manufacturer data grid
	 * @param ManufacturerPresenter $presenter Manufacturer manager presenter
	 * @param string $name Component name
	 * @return DataGrid
	 * @throws DataGridException
	 */
	public function create(ManufacturerPresenter $presenter, string $name): DataGrid {
		$this->presenter = $presenter;
		$grid = $this->dataGridFactory->create($presenter, $name);
		$grid->setDataSource($this->manager->getManufacturerRepository()->createQueryBuilder('m'));
		$grid->addColumnNumber('id', 'admin.manufacturer.id')
			->setAlign('left')
			->setSortable();
		$grid->addColumnText('name', 'admin.manufacturer.name')
			->setSortable()
			->setFilterText();
		$grid->addAction('edit', 'admin.actions.edit')
			->setIcon('edit')
			->setClass('btn btn-xs btn-info');
		$grid->addAction('delete', 'admin.actions.delete')
			->setIcon('trash-alt')
			->setClass('btn btn-xs btn-danger ajax')
			->setConfirmation(new StringConfirmation('admin.manufacturer.messages.confirmDelete', 'name'));
		$grid->addToolbarButton('add', 'admin.actions.add')
			->setClass('btn btn-xs btn-success');
		return $grid;
	}

}