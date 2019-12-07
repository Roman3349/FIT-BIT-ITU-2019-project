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

use App\CoreModule\Forms\CartFormFactory;
use Nette\Application\UI\Form;

/**
 * Cart presenter
 */
final class CartPresenter extends BasePresenter {

	/**
	 * @var CartFormFactory Cart form factory
	 * @inject
	 */
	public $formFactory;

	/**
	 * Deletes a bike from the cart
	 * @param int $id Bike ID
	 */
	public function actionDelete(int $id): void {
		$this->cartManager->remove($id);
		$this->redirect('default');
	}

	/**
	 * Renders the cart
	 */
	public function renderDefault(): void {
		$this->template->cartContent = $this->cartManager->getContent();
		$this->template->cartPrice = $this->cartManager->getPrice();
	}

	/**
	 * Creates the cart form
	 * @return Form Cart form
	 */
	protected function createComponentCartForm(): Form {
		return $this->formFactory->create($this);
	}

}