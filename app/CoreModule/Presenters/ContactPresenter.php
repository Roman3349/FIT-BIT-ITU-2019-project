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

use MapyCZ\Controls\MapControl;
use MapyCZ\Exceptions\MapException;
use MapyCZ\Factories\IMapControlFactory;
use MapyCZ\MapControls\Marker;

/**
 * Contact presenter
 */
final class ContactPresenter extends BasePresenter {

	/**
	 * @var IMapControlFactory
	 * @inject
	 */
	public $mapControlFactory;

	/**
	 * Creates the map
	 * @param string $name Component name
	 * @return MapControl Map
	 * @throws MapException
	 */
	protected function createComponentMap(string $name): MapControl {
		$map = $this->mapControlFactory->create();
		$latitude = $this->companyManager->getLatitude();
		$longitude = $this->companyManager->getLongitude();
		$map->settings = [
			'mapId' => 'map',
			'width' => 32,
			'height' => 32,
			'units' => 'rem',
			'mapType' => 1,
			'center' => [
				'latitude' => $latitude,
				'longitude' => $longitude
			],
			'defaultZoom' => 17,
			'controls' => true
		];
		$map->addMarker(new Marker($latitude, $longitude, $this->companyManager->getName()));
		$this->addComponent($map, $name);
		return $map;
	}

}