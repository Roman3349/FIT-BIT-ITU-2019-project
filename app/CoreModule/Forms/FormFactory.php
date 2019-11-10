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

namespace App\CoreModule\Forms;

use Contributte\Forms\IApplicationFormFactory;
use Contributte\Forms\Rendering\Bootstrap4HorizontalRenderer;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\SmartObject;

/**
 * Form factory
 */
class FormFactory implements IApplicationFormFactory {

	use SmartObject;

	/**
	 * @var string|null Translated message prefix
	 */
	private $translationPrefix = null;

	/**
	 * @var ITranslator Translator
	 */
	private $translator;

	/**
	 * Constructor
	 * @param ITranslator $translator Translator
	 */
	public function __construct(ITranslator $translator) {
		$this->translator = $translator;
	}

	/**
	 * Creates a new form
	 * @return Form Form
	 */
	public function create(): Form {
		$form = new Form();
		$renderer = new Bootstrap4HorizontalRenderer();
		if ($this->translationPrefix === null) {
			$form->setTranslator($this->translator);
		} else {
			$translator = $this->translator->createPrefixedTranslator($this->translationPrefix);
			$form->setTranslator($translator);
		}

		$form->setRenderer($renderer);
		return $form;
	}

	/**
	 * Sets the translated message prefix
	 * @param string|null $translationPrefix Translated message prefix
	 */
	public function setTranslationPrefix(string $translationPrefix): void {
		$this->translationPrefix = $translationPrefix;
	}
}