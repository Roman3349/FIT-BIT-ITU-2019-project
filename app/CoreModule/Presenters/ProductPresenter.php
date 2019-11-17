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

namespace App\CoreModule\Presenters;

use App\CoreModule\Forms\ProductFilterFormFactory;
use App\CoreModule\Models\BikeManager;
use App\CoreModule\Models\CompanyManager;
use Nette\Application\UI\Form;

/**
 * Product presenter
 */
final class ProductPresenter extends BasePresenter {

	/**
	 * @var ProductFilterFormFactory Product filter form factory
	 * @inject
	 */
	public $filterFactory;

	/**
	 * @var BikeManager Bike manager
	 */
	private $manager;

	/**
	 * Constructor
	 * @param BikeManager $manager Bike manager
	 */
	public function __construct(BikeManager $manager) {
		$this->manager = $manager;
	}

	public function renderDefault(): void {
		$this->template->products = $this->manager->list();
		\Tracy\Debugger::barDump($this->template->products);
	}

	public function renderShow(int $id): void {
		$this->template->product = $this->manager->get($id);
	}

	/**
	 * Creates the filter form
	 * @return Form Filter form
	 */
	protected function createComponentFilterForm(): Form {
		return $this->filterFactory->create($this);
	}
}
