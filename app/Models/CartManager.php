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

namespace App\Models;

use App\Models\Database\Entities\Bike;
use App\Models\Database\EntityManager;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\SmartObject;

/**
 * Cart manager
 */
final class CartManager {

	use SmartObject;

	/**
	 * @var SessionSection Date range
	 */
	private $dateRange;

	/**
	 * @var SessionSection Cart
	 */
	private $cart;

	/**
	 * @var EntityManager Entity manager
	 */
	private $manager;

	/**
	 * Constructor
	 * @param Session $session Session
	 * @param EntityManager $manager Entity manager
	 */
	public function __construct(Session $session, EntityManager $manager) {
		$this->cart = $session->getSection('cart');
		$this->dateRange = $session->getSection('dateRange');
		$this->manager = $manager;
	}

	public function add(int $id): void {
		$bike = $this->manager->getBikeRepository()->find($id);;
		$this->cart->offsetSet(strval($bike->getId()), $bike->getPrice());
	}

	public function getContent(): array {
		$cart = [];
		foreach ($this->cart as $id => $price) {
			$cart[$id] = $this->manager->getBikeRepository()->find($id);
		}
		return $cart;
	}

	public function getPrice(): int {
		$sum = 0;
		foreach ($this->cart as $price) {
			$sum += $price;
		}
		return $sum;
	}

	public function getDateRange(): array {
		return [
			'from' => $this->dateRange->offsetGet('from'),
			'to' => $this->dateRange->offsetGet('to'),
		];
	}

	public function setDateRange(?\DateTime $from = null, ?\DateTime $to = null): void {
		if ($from === null || $to === null) {
			return;
		}
		$this->dateRange->offsetSet('from', $from->format('Y-m-d'));
		$this->dateRange->offsetSet('to', $to->format('Y-m-d'));
	}

	public function remove(int $id): void {
		$this->cart->offsetUnset(strval($id));
	}

}
