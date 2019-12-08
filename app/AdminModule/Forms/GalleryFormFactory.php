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
use App\AdminModule\Presenters\GalleryPresenter;
use App\CoreModule\Forms\FormFactory;
use App\Models\CompanyManager;
use App\Models\Database\Entities\Gallery;
use App\Models\Database\EntityManager;
use MapyCZ\Form\GPSPicker;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\SmartObject;
use Nette\Utils\Image;
use Ramsey\Uuid\Uuid;

/**
 * Company manager form
 */
final class GalleryFormFactory {

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
	 * @var GalleryPresenter Gallery manager presenter
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
	 * Creates a gallery form
	 * @param GalleryPresenter $presenter Gallery manager presenter
	 * @return Form Gallery form
	 */
	public function create(GalleryPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('admin.gallery');
		$form = $this->factory->create();
		$form->addText('name', 'name')
			->setRequired('messages.name');
		$form->addUpload('image', 'image')
			->addRule(Form::IMAGE, 'message.imageFormat')
			->setRequired('message.image');
		$form->addProtection();
		$id = $this->presenter->getParameter('id');
		if (isset($id)) {
			/**
			 * @var Gallery Gallery entity
			 */
			$gallery = $this->manager->getGalleryRepository()->find(intval($id));
			$form->setDefaults(['name' => $gallery->getName()]);
			$form->addSubmit('save', 'save');
		} else {
			$form->addSubmit('add', 'add');
		}
		$form->onSubmit[] = [$this, 'save'];
		return $form;
	}

	/**
	 * Saves values from the form
	 * @param Form $form Gallery form
	 */
	public function save(Form $form): void {
		$values = $form->getValues();
		$id = $this->presenter->getParameter('id');
		$repository = $this->manager->getGalleryRepository();
		/**
		 * @var Gallery $gallery Gallery
		 */
		$gallery = $repository->find(intval($id));
		$translator = $this->presenter->translator;
		$url = '/img/gallery/' . Uuid::uuid4()->toString() . '.png';
		/**
		 * @var FileUpload $image
		 */
		$image = $values->image;
		$image->move(__DIR__ . '/../../../www/' . $url);
		$picture = $image->toImage();
		$picture->resize(1600, 900, Image::FILL);
		$picture->save(__DIR__ . '/../../../www/' . $url);
		if ($gallery == null) {
			if ($repository->findOneBy(['name' => $values->name]) !== null) {
				$message = $translator->translate('messages.failureDuplicate', ['name' => $values->name]);
				$form['name']->addError($message);
				return;
			}
			$gallery = new Gallery($values->name, $url);
		} else {
			$gallery->setName($values->name);
			$gallery->setUrl($url);
		}
		$this->manager->persist($gallery);
		$this->manager->flush();
		$message = $translator->translate('admin.gallery.messages.successEdit', ['name' => $values->name]);
		$this->presenter->flashSuccess($message);
		$this->presenter->redirect('Gallery:default');
	}

}