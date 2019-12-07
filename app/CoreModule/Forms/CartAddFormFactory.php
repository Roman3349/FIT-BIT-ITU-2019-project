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

use App\CoreModule\Presenters\ProductPresenter;
use App\Models\CartManager;
use App\Models\Database\EntityManager;
use Contributte\Translation\Wrappers\Message;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Add to cart form factory
 */
final class CartAddFormFactory {

	use SmartObject;

	/**
	 * @var EntityManager Entity manager
	 */
	private $entityManager;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var CartManager Cart manager
	 */
	private $manager;

	/**
	 * @var ProductPresenter Product presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param CartManager $manager Cart manager
	 * @param EntityManager $entityManager Entity manager
	 */
	public function __construct(FormFactory $factory, CartManager $manager, EntityManager $entityManager) {
		$this->factory = $factory;
		$this->manager = $manager;
		$this->entityManager = $entityManager;
	}

	/**
	 * Creates a new add to cart form
	 * @param ProductPresenter $presenter Product presenter
	 * @return Form Add to cart form
	 */
	public function create(ProductPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('core.cart');
		$form = $this->factory->create();
		if ($this->presenter->getAction() === 'show') {
			$form->addText('from', 'fromDate')
				->setHtmlType('date');
			$form->addText('to', 'toDate')
				->setHtmlType('date');
		}
		$form->addHidden('id');
		$form->addSubmit('rent', 'rent');
		$form->onSubmit[] = [$this, 'add'];
		return $form;
	}

	/**
	 * Adds the product to the cart
	 * @param Form $form Add to cart from
	 */
	public function add(Form $form): void {
		$values = $form->getValues();
		$id = intval($values->id);
		$this->manager->add($id);
		$bike = $this->entityManager->getBikeRepository()->find($id);
		$message = $this->presenter->translator->translate('core.cart.messages.add', ['name' => $bike->getFullName()]);
		$this->presenter->flashSuccess($message);
	}

}