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

namespace App\AdminModule\Forms;

use App\AdminModule\Presenters\ManufacturerPresenter;
use App\CoreModule\Forms\FormFactory;
use App\Models\Database\Entities\Manufacturer;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Manufacturer form factory
 */
final class ManufacturerFormFactory {

	use SmartObject;

	/**
	 * @var EntityManager Entity manager
	 */
	private $manager;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var ManufacturerPresenter Manufacturer manager presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param EntityManager $manager Entity manager
	 */
	public function __construct(FormFactory $factory, EntityManager $manager) {
		$this->factory = $factory;
		$this->manager = $manager;
	}

	/**
	 * Creates a manufacturer form
	 * @param ManufacturerPresenter $presenter Manufacturer manager presenter
	 * @return Form Manufacturer form
	 */
	public function create(ManufacturerPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('admin.manufacturer');
		$form = $this->factory->create();
		$form->addText('name', 'name')
			->setRequired('messages.name');
		$form->addProtection();
		$id = $this->presenter->getParameter('id');
		if (isset($id)) {
			/**
			 * @var Manufacturer Manufacturer entity
			 */
			$manufacturer = $this->manager->getManufacturerRepository()->find(intval($id));
			$form->setDefaults(['name' => $manufacturer->getName()]);
			$form->addSubmit('save', 'save');
		} else {
			$form->addSubmit('add', 'add');
		}
		$form->onSubmit[] = [$this, 'save'];
		return $form;
	}

	/**
	 * Saves values from the form
	 * @param Form $form Manufacturer form
	 */
	public function save(Form $form): void {
		$values = $form->getValues();
		$id = $this->presenter->getParameter('id');
		$repository = $this->manager->getManufacturerRepository();
		$manufacturer = $repository->find(intval($id));
		$translator = $this->presenter->translator;
		if ($manufacturer == null) {
			if ($repository->findOneBy(['name' => $values->name]) !== null) {
				$message = $translator->translate('messages.failureDuplicate', ['name' => $values->name]);
				$form['name']->addError($message);
				return;
			}
			$manufacturer = new Manufacturer($values->name);
		} else {
			$manufacturer->setName($values->name);
		}
		$this->manager->persist($manufacturer);
		$this->manager->flush();
		$message = $translator->translate('admin.manufacturer.messages.successEdit', ['name' => $values->name]);
		$this->presenter->flashSuccess($message);
		$this->presenter->redirect('Manufacturer:default');
	}

}