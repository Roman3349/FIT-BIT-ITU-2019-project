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

namespace App\CoreModule\Forms;

use App\CoreModule\Presenters\CartPresenter;
use App\Models\CartManager;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Cart form factory
 */
final class CartFormFactory {

	use SmartObject;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var CartManager Cart manager
	 */
	private $manager;

	/**
	 * @var CartPresenter Cart presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param CartManager $manager Cart manager
	 */
	public function __construct(FormFactory $factory, CartManager $manager) {
		$this->factory = $factory;
		$this->manager = $manager;
	}

	/**
	 * Creates a new cart form
	 * @param CartPresenter $presenter Cart presenter
	 * @return Form Cart form
	 */
	public function create(CartPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('core.cart');
		$form = $this->factory->create();
		$form->addText('from', 'fromDate')
			->setHtmlType('date');
		$form->addText('to', 'toDate')
			->setHtmlType('date');
		if (!$this->presenter->user->isLoggedIn()) {
			$form->addText('firstName', 'firstName');
			$form->addText('lastName', 'lastName');
			$form->addEmail('email', 'email');
		}
		$form->setDefaults($this->manager->getDateRange());
		$form->addSubmit('reserve', 'reserve');
		return $form;
	}
}