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

use App\AdminModule\Presenters\BikePresenter;
use App\CoreModule\Forms\FormFactory;
use App\Models\Database\Entities\Bike;
use App\Models\Database\EntityManager;
use Contributte\Translation\Wrappers\NotTranslate;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Utils\Html;

/**
 * Bike manager form factory
 */
final class BikeFormFactory {

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
	 * @var BikePresenter Bike manager presenter
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
	 * Creates a bike manager form
	 * @param BikePresenter $presenter Bike manager presenter
	 * @return Form Manufacturer form
	 */
	public function create(BikePresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('admin.bike');
		$form = $this->factory->create();
		$form->addSelect('manufacturer', 'manufacturer')
			->setItems($this->listManufacturers())
			->setPrompt('messages.manufacturer')
			->setRequired('messages.manufacturer');
		$form->addText('name', 'name')
			->setRequired('messages.name');
		$form->addSelect('usage', 'usage')
			->setItems($this->listUsages())
			->setPrompt('messages.usage')
			->setRequired('messages.usage');
		$form->addText('picture', 'picture')
			->setRequired('messages.picture');
		$form->addSelect('frameMaterial', 'frameMaterial')
			->setItems($this->listFrameMaterials())
			->setPrompt('messages.frameMaterial')
			->setRequired('messages.frameMaterial');
		$form->addText('frameSize', 'frameSize')
			->setRequired('messages.frameSize');
		$form->addText('wheelSize', 'wheelSize')
			->setRequired('messages.wheelSize');
		$form->addInteger('forkTravel', 'forkTravel')
			->setDefaultValue(0);
		$form->addInteger('shockTravel', 'shockTravel')
			->setDefaultValue(0);
		$form->addText('speeds', 'speeds')
			->setRequired('messages.speeds');
		$form->addInteger('price', 'price')
			->setRequired('messages.price');
		$form->addProtection();
		$id = $this->presenter->getParameter('id');
		if ($this->presenter->getAction() === 'edit') {
			$form->setDefaults($this->load(intval($id)));
			$form->addSubmit('save', 'save');
		} else {
			$form->addSubmit('add', 'add');
		}
		$form->onSubmit[] = [$this, 'save'];
		return $form;
	}

	/**
	 * Lists all available frame materials
	 * @return array<string,string> Available frame materials
	 */
	public function listFrameMaterials(): array {
		return [
			'Al' => 'frameMaterials.Al',
			'Carbon' => 'frameMaterials.Carbon',
		];
	}

	/**
	 * Lists all available manufacturers
	 * @return array<string,string> Available manufacturers
	 */
	private function listManufacturers(): array {
		$array = [];
		$manufacturers = $this->manager->getManufacturerRepository()
			->createQueryBuilder('m')->select('m.name')
			->getQuery()->getArrayResult();
		foreach ($manufacturers as $key => $value) {
			$array[$value['name']] = new NotTranslate($value['name']);
		}
		return $array;
	}

	/**
	 * Lists all available bike usages
	 * @return array<string,string> Available bike usages
	 */
	public function listUsages(): array {
		$array = [];
		$usages = $this->manager->getBikeUsageRepository()
			->createQueryBuilder('bu')->select('bu.name')
			->getQuery()->getArrayResult();
		foreach ($usages as $key => $value) {
			$array[$value['name']] = new NotTranslate($value['name']);
		}
		return $array;
	}

	private function load(int $id): array {
		/**
		 * @var Bike|null $bike Bike entity
		 */
		$bike = $this->manager->getBikeRepository()->find($id);
		return [
			'manufacturer' => $bike->getManufacturerName(),
			'name' => $bike->getName(),
			'usage' => $bike->getUsageName(),
			'picture' => $bike->getPicture(),
			'frameMaterial' => $bike->getFrameMaterial(),
			'frameSize' => $bike->getFrameSize(),
			'wheelSize' => $bike->getWheelSize(),
			'forkTravel' => $bike->getForkTravel(),
			'shockTravel' => $bike->getShockTravel(),
			'speeds' => $bike->getSpeeds(),
			'price' => $bike->getPrice(),
		];
	}

	/**
	 * Saves values from the form
	 * @param Form $form Manufacturer form
	 */
	public function save(Form $form): void {
		$values = $form->getValues();
		$id = $this->presenter->getParameter('id');
		$repository = $this->manager->getBikeRepository();
		$bike = $repository->find(intval($id));
		$translator = $this->presenter->translator;
		$manufacturer = $this->manager->getManufacturerRepository()
			->findOneByName($values->manufacturer);
		$usage = $this->manager->getBikeUsageRepository()
			->findOneByName($values->usage);
		if ($bike == null) {
			$bike = new Bike($manufacturer, $values->name, $usage, $values->picture, $values->frameMaterial, $values->frameSize, $values->wheelSize, $values->forkTravel, $values->shockTravel, $values->speeds, $values->price);
		} else {
			$bike->setManufacturer($manufacturer);
			$bike->setName($values->name);
			$bike->setUsage($usage);
			$bike->setPicture($values->picture);
			$bike->setFrameMaterial($values->frameMaterial);
			$bike->setFrameSize($values->frameSize);
			$bike->setWheelSize($values->wheelSize);
			$bike->setForkTravel($values->forkTravel);
			$bike->setShockTravel($values->shockTravel);
			$bike->setSpeeds($values->speeds);
			$bike->setPrice($values->price);
		}
		$this->manager->persist($bike);
		$this->manager->flush();
		$message = $translator->translate('admin.bike.messages.successEdit', ['name' => $bike->getFullName()]);
		$this->presenter->flashSuccess($message);
		$this->presenter->redirect('Bike:default');
	}

}
