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

use App\Models\Database\EntityManager;
use DateInterval;
use DateTime;
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

	/**
	 * Adds the bike into the cart
	 * @param int $id Bike ID
	 */
	public function add(int $id): void {
		$bike = $this->manager->getBikeRepository()->find($id);;
		$this->cart->offsetSet(strval($bike->getId()), $bike->getPrice());
	}

	/**
	 * Clears the cart
	 */
	public function clear(): void {
		$this->cart->remove();
	}

	/**
	 * Returns the cart content
	 * @return array<string,string> Cart content
	 */
	public function getContent(): array {
		$cart = [];
		foreach ($this->cart as $id => $price) {
			$cart[$id] = $this->manager->getBikeRepository()->find($id);
		}
		return $cart;
	}

	/**
	 * Returns the cart price
	 * @return int Cart price
	 */
	public function getPrice(): int {
		$sum = 0;
		foreach ($this->cart as $price) {
			$sum += $price;
		}
		return $sum;
	}

	/**
	 * Returns the date range
	 * @return array<string,string> Date range
	 */
	public function getDateRange(): array {
		$today = new DateTime();
		$array = [];
		if ($this->dateRange->offsetExists('from')) {
			$array['from'] = $this->dateRange->offsetGet('from');
		} else {
			$array['from'] = $today->format('Y-m-d');
		}
		if ($this->dateRange->offsetExists('to')) {
			$array['to'] = $this->dateRange->offsetGet('to');
		} else {
			$array['to'] = (new DateTime($array['from']))->add(new DateInterval('P1D'))->format('Y-m-d');
		}
		return $array;
	}

	/**
	 * Sets the date range
	 * @param DateTime|null $from From date
	 * @param DateTime|null $to To date
	 */
	public function setDateRange(?DateTime $from = null, ?DateTime $to = null): void {
		if ($from === null || $to === null) {
			return;
		}
		$this->dateRange->offsetSet('from', $from->format('Y-m-d'));
		$this->dateRange->offsetSet('to', $to->format('Y-m-d'));
	}

	/**
	 * Removes the bike from the cart
	 * @param int $id Bike ID
	 */
	public function remove(int $id): void {
		$this->cart->offsetUnset(strval($id));
	}

}
