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

use App\AdminModule\Presenters\CompanyPresenter;
use App\CoreModule\Forms\FormFactory;
use App\Models\CompanyManager;
use MapyCZ\Form\GPSPicker;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Company manager form
 */
final class CompanyFormFactory {

	use SmartObject;

	/**
	 * @var CompanyManager Company manager
	 */
	private $manager;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var CompanyPresenter Company manager presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param CompanyManager $manager Company manager
	 */
	public function __construct(FormFactory $factory, CompanyManager $manager) {
		$this->factory = $factory;
		$this->manager = $manager;
	}

	public function create(CompanyPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('admin.company');
		$form = $this->factory->create();
		$form->addGroup();
		$form->addText('name', 'name')
			->setRequired('messages.name');
		$form->addGroup('address.title');
		$address = $form->addContainer('address');
		$address->addText('street', 'address.street')
			->setRequired('messages.address.street');
		$address->addText('zip', 'address.zip')
			->setRequired('messages.address.zip');
		$address->addText('city', 'address.city')
			->setRequired('messages.address.city');
		$form['gps'] = new GPSPicker('gps', null);
		$form['gps']->setSettings([
				'mapId' => 'gps',
				'width' => 47.5,
				'height' => 24,
				'mapType' => 1,
				'units' =>  'em',
				'center' => [
					'latitude' => 50,
					'longitude' => 15,
				],
				'defaultZoom' => 12,
				'controls' => true,
			])
			->setRequired('messages.gps');
		$form->addGroup('openingHours.title');
		$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
		$openingHours = $form->addContainer('openingHours');
		foreach ($days as $day) {
			$openingHours->addText($day, 'openingHours.' . $day);
		}
		$form->addGroup('contacts');
		$form->addEmail('email', 'email')
			->setRequired('messages.email');
		$form->addText('telephone', 'telephone')
			->setHtmlType('tel')
			->setRequired('messages.telephone');
		$form->setDefaults($this->manager->get());
		$form->addProtection();
		$form->addSubmit('save', 'save');
		$form->onSubmit[] = [$this, 'save'];
		return $form;
	}

	public function save(Form $form): void {
		$values = $form->getValues();
		$this->manager->set($values);
		$this->presenter->flashSuccess('admin.company.messages.success');
		$this->presenter->redirect('Homepage:default');
	}

}