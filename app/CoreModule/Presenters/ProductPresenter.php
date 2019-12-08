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

use App\CoreModule\Forms\CartAddFormFactory;
use App\CoreModule\Forms\ProductFilterFormFactory;
use App\CoreModule\Forms\ProductAssistantFormFactory;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;

/**
 * Product presenter
 */
final class ProductPresenter extends BasePresenter {

	/**
	 * @var CartAddFormFactory Add to cart form factory
	 * @inject
	 */
	public $cartFormFactory;

	/**
	 * @var ProductFilterFormFactory Product filter form factory
	 * @inject
	 */
	public $filterFactory;

    /**
     * @var ProductAssistantFormFactory Product assistent form factory
     * @inject
     */
    public $assistantFactory;

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
	 * Returns bikes
	 * @param array|null $filters Filters
	 * @return Bike[] Bikes
	 */
	public function getBikes(?array $filters): array {
		$bikeRepository = $this->manager->getBikeRepository();
		if ($filters === null) {
			return $bikeRepository->findAll();
		}
		$filters = array_filter($filters, function($value) {
			return !is_null($value) && $value !== [];
		});
		return $bikeRepository->findBy($filters);
	}

	/**
	 * Renders a list of bikes
	 */
	public function renderDefault(): void {
		if (!isset($this->template->products)) {
			$this->template->products = $this->getBikes(null);
		}
	}

	/**
	 * Renders the bike detail
	 * @param int $id Bike ID
	 */
	public function renderShow(int $id): void {
		$this->template->product = $this->manager->getBikeRepository()->find($id);
	}

	/**
	 * Creates a new add to cart form
	 * @return Form Add to cart form
	 */
	protected function createComponentCartAddForm(): Form {
		return $this->cartFormFactory->create($this);
	}

	/**
	 * Creates the filter form
	 * @return Form Filter form
	 */
	protected function createComponentFilterForm(): Form {
		return $this->filterFactory->create($this);
	}

    /**
     * Creates the assistant form
     * @return Form Assistant form
     */
    protected function createComponentAssistantForm(): Form {
        return $this->assistantFactory->create($this);
    }
}
