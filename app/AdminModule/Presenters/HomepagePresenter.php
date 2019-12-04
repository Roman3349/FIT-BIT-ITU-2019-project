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

use App\Models\Database\EntityManager;

/**
 * Homepage presenter
 */
final class HomepagePresenter extends BasePresenter {

	/**
	 * @var EntityManager Entity manager
	 */
	private $entityManager;

	/**
	 * Constructor
	 * @param EntityManager $entityManager Entity manager
	 */
	public function __construct(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * Renders dashboard
	 */
	public function renderDefault(): void {
		$this->template->reservationCount = $this->entityManager->getReservationRepository()->count([]);
		$this->template->userCount = $this->entityManager->getUserRepository()->count([]);
	}
}