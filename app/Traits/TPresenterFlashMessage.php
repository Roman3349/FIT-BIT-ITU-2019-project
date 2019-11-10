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

namespace App\Traits;

use App\Presenters\BasePresenter;
use stdClass;

/**
 * Trait for flash messages in presenters
 * @mixin BasePresenter
 */

trait TPresenterFlashMessage {

	/**
	 * Saves the info flash message to template, that can be displayed after redirect or AJAX request
	 * @param string $message Message
	 * @return stdClass Flash message object
	 */
	public function flashInfo(string $message): stdClass {
		return $this->flashMessage($message, 'info');
	}

	/**
	 * Saves the flash message to template, that can be displayed after redirect or AJAX request
	 * @param string $message Message
	 * @param string $type Message's type
	 * @return stdClass Flash message object
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @internal
	 */
	public function flashMessage($message, string $type = 'info'): stdClass {
		if ($this->isAjax()) {
			$this->redrawControl('flashes');
		}
		return parent::flashMessage($message, $type);
	}

	/**
	 * Saves the success flash message to template, that can be displayed after redirect or AJAX request
	 * @param string $message Message
	 * @return stdClass Flash message object
	 */
	public function flashSuccess(string $message): stdClass {
		return $this->flashMessage($message, 'success');
	}

	/**
	 * Saves the warning flash message to template, that can be displayed after redirect or AJAX request
	 * @param string $message Message
	 * @return stdClass Flash message object
	 */
	public function flashWarning(string $message): stdClass {
		return $this->flashMessage($message, 'warning');
	}

	/**
	 * Saves the error flash message to template, that can be displayed after redirect or AJAX request
	 * @param string $message Message
	 * @return stdClass Flash message object
	 */
	public function flashError(string $message): stdClass {
		return $this->flashMessage($message, 'danger');
	}

}