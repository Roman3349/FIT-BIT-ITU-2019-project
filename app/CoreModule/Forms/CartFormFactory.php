<?php

/*
 * Copyright (C) 2019 Roman Ondráček <xondra58@stud.fit.vutbr.cz>, Karel Fiedler <xfiedl04@stud.fit.vutbr.cz>
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

use App\CoreModule\Presenters\CartPresenter;
use App\Models\CartManager;
use App\Models\Database\Entities\Reservation;
use App\Models\Database\Entities\User;
use App\Models\Database\EntityManager;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Utils\Html;

/**
 * Cart form factory
 */
final class CartFormFactory {

	use SmartObject;

	/**
	 * @var EntityManager Entity manager
	 */
	private $entityManager;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var CartManager Cart manager
	 */
	private $manager;

	/**
	 * @var CartPresenter Cart presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param CartManager $manager Cart manager
	 * @param EntityManager $entityManager Entity manager
	 */
	public function __construct(FormFactory $factory, CartManager $manager, EntityManager $entityManager) {
		$this->factory = $factory;
		$this->manager = $manager;
		$this->entityManager = $entityManager;
	}

	/**
	 * Creates a new cart form
	 * @param CartPresenter $presenter Cart presenter
	 * @return Form Cart form
	 */
	public function create(CartPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('core.cart');
		$form = $this->factory->create();
		$form->addText('from', 'fromDate')
			->setHtmlType('date');
		$form->addText('to', 'toDate')
			->setHtmlType('date');
		if (!$this->presenter->user->isLoggedIn()) {
			$form->addText('firstName', 'firstName')
                ->setRequired('messages.firstName');
            $form->addText('lastName', 'lastName')
                ->setRequired('messages.lastName');
            $form->addEmail('email', 'email')
                ->setRequired('messages.email');
        }
		$form->setDefaults($this->manager->getDateRange());
		$translator = $this->presenter->translator;
		$termsAgreement = Html::el('p')->setHtml(
			$translator->translate('core.cart.termsAgreement') . ' ' .
			Html::el('a')->href('/terms/')
				->setText($translator->translate('core.cart.terms'))
		);
		$form->addCheckbox('termsAgreement', $termsAgreement)
			->setRequired('messages.terms');
		$form->addSubmit('reserve', 'reserve')->setHtmlAttribute('class', 'btn btn-primary float-right');;
		$form->onSubmit[] = [$this, 'reserve'];
		return $form;
	}

	/**
	 * Creates a new reservation
	 * @param Form $form Cart form
	 */
	public function reserve(Form $form): void {
		$values = $form->getValues();
		if ($this->presenter->user->isLoggedIn()) {
			$creator = $this->entityManager->getUserRepository()->find($this->presenter->getUser()->getId());
		} else {
			$creator = $this->entityManager->getUserRepository()->findOneByEmail($values->email);
			if ($creator === null) {
				$creator = new User($values->firstName, $values->lastName, $values->email, '', User::ROLE_CUSTOMER, User::STATE_BLOCKED);
				$this->entityManager->persist($creator);
				$this->entityManager->flush();
			}
		}
		$bikes = [];
		foreach ($this->manager->getContent() as $bikeId => $bikePrice) {
			$bikes[] = $this->entityManager->getBikeRepository()->find(intval($bikeId));
		}
		$bikes = new ArrayCollection($bikes);
		$fromDate = new DateTime($values->from);
		$toDate = new DateTime($values->to);
		$reservation = new Reservation($creator, $creator, $fromDate, $toDate, $bikes, Reservation::STATE_RESERVATION);
		$this->entityManager->persist($reservation);
		$this->entityManager->flush();
		$this->manager->clear();
		$message = $this->presenter->translator->translate('core.cart.messages.success', ['id' => $reservation->getId()]);
		$this->presenter->flashSuccess($message);
	}
}