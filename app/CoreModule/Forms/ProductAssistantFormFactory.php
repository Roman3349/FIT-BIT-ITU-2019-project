<?php

/*
 * Copyright (C) 2019 Roman Ondráček <xondra58@stud.fit.vutbr.cz>, Karel Fiedler <xfiedl04@stud.fit.vutbr.cz>
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
use App\Models\Database\EntityManager;
use Contributte\Forms\Rendering\Bootstrap4VerticalRenderer;
use Contributte\Translation\Wrappers\NotTranslate;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Product assistant form factory
 */
final class ProductAssistantFormFactory {

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
	 * @var ProductPresenter Product presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param EntityManager $entityManager Entity manager
	 */
	public function __construct(FormFactory $factory, EntityManager $entityManager) {
		$this->factory = $factory;
		$this->entityManager = $entityManager;
	}

	/**
	 * Creates a new product assistant form
	 * @param ProductPresenter $presenter Product presenter
	 * @return Form Product assistant form
	 */
	public function create(ProductPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('core.assistant');
		$form = $this->factory->create();
		$form->addText('height', 'height')
			->setHtmlType('number');
		$form->addText('weight', 'weight')
			->setHtmlType('number');
		$form->addSubmit('filter', 'filter')
			->setHtmlAttribute('class', 'btn btn-primary col-md-12 my-3 p-4');
        return $form;
	}
}