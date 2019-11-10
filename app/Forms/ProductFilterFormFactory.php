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

namespace App\Forms;

use App\Presenters\ProductPresenter;
use Contributte\Forms\Rendering\Bootstrap4VerticalRenderer;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Product filter form factory
 */
final class ProductFilterFormFactory {

	use SmartObject;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var ProductPresenter Product presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 */
	public function __construct(FormFactory $factory) {
		$this->factory = $factory;
	}

	public function create(ProductPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('core.product');
		$form = $this->factory->create();
		$renderer = new Bootstrap4VerticalRenderer();
		$form->setRenderer($renderer);
		$form->addText('fromDate', 'fromDate');
		$form->addText('toDate', 'toDate');
		$form->addSelect('usageType', 'usageType', ['XC', 'Trail']);
		$form->addInteger('height', 'height');
		$form->addSelect('wheelSize', 'wheelSize', ['26"', '27,5"']);
		$form->addSelect('frameSize', 'frameSize', ['17"', '19"']);
		$form->addSubmit('filter', 'filter');
		return $form;
	}

}