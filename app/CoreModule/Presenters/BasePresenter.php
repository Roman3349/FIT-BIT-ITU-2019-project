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

use App\Models\CartManager;
use App\Models\CompanyManager;
use Contributte\Translation\LocalesResolvers\Session;
use Nette\Application\UI\Presenter;
use Nette\Localization\ITranslator;

/**
 * Core base presenter
 */
abstract class BasePresenter extends Presenter {

	/**
	 * @var string Locale
	 * @persistent
	 */
	public $locale;

	/**
	 * @var CartManager Cart manager
	 */
	protected $cartManager;

	/**
	 * @var CompanyManager Company manager
	 */
	protected $companyManager;

	/**
	 * @var ITranslator
	 * @inject
	 */
	public $translator;

	/**
	 * @var Session
	 * @inject
	 */
	public $translatorSessionResolver;

	/**
	 * Injects Cart manager
	 * @param CartManager $cartManager Cart manager
	 */
	public function injectCartManager(CartManager $cartManager) {
		$this->cartManager = $cartManager;
	}

	/**
	 * Injects Company manager
	 * @param CompanyManager $companyManager Company manager
	 */
	public function injectCompanyManager(CompanyManager $companyManager) {
		$this->companyManager = $companyManager;
	}

	/**
	 * Before render
	 */
	protected function beforeRender() {
		$this->template->company = $this->companyManager;
		parent::beforeRender();
	}

}