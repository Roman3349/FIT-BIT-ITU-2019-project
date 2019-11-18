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

namespace App\Models\Database\Attributes;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;

trait TCreatedAt {

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime", nullable=FALSE)
	 */
	protected $createdAt;

	/**
	 * Returns created at time
	 * @return DateTime Created at time
	 */
	public function getCreatedAt(): DateTime {
		return $this->createdAt;
	}

	/**
	 * Sets created at time
	 * @ORM\PrePersist()
	 * @internal
	 */
	public function setCreatedAt(): void {
		$this->createdAt = new DateTime();
	}

}