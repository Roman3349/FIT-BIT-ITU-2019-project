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

namespace App\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\Helpers;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\Responses\CallbackResponse;
use Nette\Application\Responses\ForwardResponse;
use Nette\Http;
use Nette\SmartObject;
use Tracy\ILogger;

/**
 * Error presenter
 */
final class ErrorPresenter implements IPresenter {

	use SmartObject;

	/**
	 * @var ILogger
	 */
	private $logger;

	/**
	 * Constructor
	 * @param ILogger $logger Logger
	 */
	public function __construct(ILogger $logger) {
		$this->logger = $logger;
	}

	/**
	 * Runs Error presenter
	 * @param Request $request HTTP(S) request
	 * @return ForwardResponse|CallbackResponse
	 */
	public function run(Request $request): IResponse {
		$exception = $request->getParameter('exception');

		if ($exception instanceof BadRequestException) {
			[$module, , $sep] = Helpers::splitName($request->getPresenterName());
			return new ForwardResponse($request->setPresenterName($module . $sep . 'Error4xx'));
		}

		$this->logger->log($exception, ILogger::EXCEPTION);
		return new CallbackResponse(function (Http\IRequest $httpRequest, Http\IResponse $httpResponse): void {
			if (preg_match('#^text/html(?:;|$)#', (string) $httpResponse->getHeader('Content-Type'))) {
				require __DIR__ . '/templates/Error/500.phtml';
			}
		});
	}
}
