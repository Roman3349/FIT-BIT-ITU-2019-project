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

namespace App\CoreModule\DataGrids;

use App\CoreModule\Presenters\BasePresenter;
use Nette\Localization\ITranslator;
use Nette\SmartObject;
use Ublaboo\DataGrid\DataGrid;

/**
 * Generic data grid factory
 */
class DataGridFactory {

	use SmartObject;

	/**
	 * @var ITranslator Translator
	 */
	private $translator;

	/**
	 * Constructor
	 * @param ITranslator $translator Translator
	 */
	public function __construct(ITranslator $translator) {
		$this->translator = $translator;
	}

	/**
	 * Creates a data grid and set the translator
	 * @param BasePresenter $presenter Base presenter
	 * @param string $name Data grid's component name
	 * @return DataGrid Data grid
	 */
	public function create(BasePresenter $presenter, string $name): DataGrid {
		DataGrid::$iconPrefix = 'fas fa-';
		$grid = new DataGrid($presenter, $name);
		$grid->setTranslator($this->translator);
		$grid->setPagination(false);
		return $grid;
	}

}