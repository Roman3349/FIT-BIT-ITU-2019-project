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

namespace App\AdminModule\Presenters;

use App\AdminModule\DataGrids\GalleryDataGrid;
use App\AdminModule\Forms\GalleryFormFactory;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;
use Ublaboo\DataGrid\DataGrid;

/**
 * Gallery presenter
 */
final class GalleryPresenter extends BasePresenter {

	/**
	 * @var GalleryDataGrid Gallery data grid factory
	 * @inject
	 */
	public $dataGrid;

	/**
	 * @var GalleryFormFactory Gallery form factory
	 * @inject
	 */
	public $formFactory;

	/**
	 * @var EntityManager Entity manager
	 */
	private $manager;

	/**
	 * Constructor
	 * @param EntityManager $manager Entity manager
	 */
	public function __construct(EntityManager $manager) {
		$this->manager = $manager;
	}

	/**
	 * Deletes a gallery
	 * @param int $id Gallery ID
	 */
	public function actionDelete(int $id): void {
		$gallery = $this->manager->getGalleryRepository()->find($id);
		if ($gallery === null) {
			return;
		}
		$this->manager->remove($gallery);
		$this->manager->flush();
		$message = $this->translator->translate('admin.gallery.messages.successDelete', ['name' => $gallery->getName()]);
		$this->flashSuccess($message);
		$this->redirect('Gallery:default');
		$this->setView('default');
	}

	/**
	 * Renders the gallery edit form
	 * @param int $id Gallery ID
	 */
	public function renderEdit(int $id) {
		$this->template->id = $id;
	}

	/**
	 * Creates a new gallery data grid
	 * @param string $name Component name
	 * @return DataGrid Gallery data grid
	 */
	protected function createComponentGalleryGrid(string $name): DataGrid {
		return $this->dataGrid->create($this, $name);
	}

	/**
	 * Creates a new gallery form
	 * @return Form Gallery form
	 */
	protected function createComponentGalleryForm(): Form {
		return $this->formFactory->create($this);
	}

}