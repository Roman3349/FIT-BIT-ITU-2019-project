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

use App\AdminModule\Presenters\BikePresenter;
use App\CoreModule\DataGrids\DataGridFactory;
use App\Models\Database\EntityManager;
use Nette\SmartObject;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridException;

/**
 * Bike data grid
 */
final class BikeDataGrid {

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
	 * @var BikePresenter Bike manager presenter
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
	 * Creates a bike manager data grid
	 * @param BikePresenter $presenter Bike manager presenter
	 * @param string $name Component name
	 * @return DataGrid Bike data grid
	 * @throws DataGridException
	 */
	public function create(BikePresenter $presenter, string $name): DataGrid {
		$this->presenter = $presenter;
		$grid = $this->dataGridFactory->create($presenter, $name);
		$grid->setDataSource($this->manager->getBikeRepository()->createQueryBuilder('b'));
		$grid->addColumnNumber('id', 'admin.bike.id')
			->setAlign('left')
			->setSortable();
		$grid->addColumnText('manufacturerName', 'admin.bike.manufacturer');
		$grid->addColumnText('name', 'admin.bike.name')
			->setSortable()
			->setFilterText();
		$grid->addColumnText('wheelSize',  'admin.bike.wheelSize')
			->setSortable()
			->setFilterText();
		$grid->addColumnText('frameSize', 'admin.bike.frameSize')
			->setSortable()
			->setFilterText();
		$grid->addColumnText('price', 'admin.bike.price')
			->setSortable()
			->setFilterText();
		$grid->addAction('edit', 'admin.actions.edit')
			->setIcon('edit')
			->setClass('btn btn-xs btn-info');
		$grid->addAction('delete', 'admin.actions.delete')
			->setIcon('trash-alt')
			->setClass('btn btn-xs btn-danger ajax')
			->setConfirmation(new StringConfirmation('admin.bike.messages.confirmDelete', 'fullName'));
		$grid->addToolbarButton('add', 'admin.actions.add')
			->setClass('btn btn-success');
		return $grid;
	}

}