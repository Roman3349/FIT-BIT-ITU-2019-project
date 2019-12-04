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

namespace App\AdminModule\Forms;

use App\AdminModule\Presenters\ReservationPresenter;
use App\CoreModule\Forms\FormFactory;
use App\Models\Database\Entities\Bike;
use App\Models\Database\Entities\Reservation;
use App\Models\Database\Entities\User;
use App\Models\Database\EntityManager;
use Contributte\Translation\Wrappers\NotTranslate;
use Doctrine\Common\Collections\ArrayCollection;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\SmartObject;
use Nette\Utils\DateTime;

/**
 * Reservation editor form factory
 */
final class ReservationFormFactory {

	use SmartObject;

	/**
	 * @var EntityManager Entity manager
	 */
	private $manager;

	/**
	 * @var FormFactory Generic form factory
	 */
	private $factory;

	/**
	 * @var ReservationPresenter Reservation manager presenter
	 */
	private $presenter;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param EntityManager $manager Entity manager
	 */
	public function __construct(FormFactory $factory, EntityManager $manager) {
		$this->factory = $factory;
		$this->manager = $manager;
	}

	/**
	 * Creates a new reservation editor form
	 * @param ReservationPresenter $presenter Reservation manager presenter
	 * @return Form Reservation editor form
	 */
	public function create(ReservationPresenter $presenter): Form {
		$this->presenter = $presenter;
		$this->factory->setTranslationPrefix('admin.reservation');
		$form = $this->factory->create();
		$form->addGroup();
		$form->addSelect('customer', 'customer')
			->setItems($this->listUsers())
			->setPrompt('messages.customer')
			->setRequired('messages.customer');
		$form->addText('fromDate', 'fromDate')
			->setRequired('messages.fromDate')
			->setHtmlType('date');
		$form->addText('toDate', 'toDate')
			->setRequired('messages.toDate')
			->setHtmlType('date');
		$form->addGroup('bikes.title');
		$bikes = $form->addMultiplier('bikes', [$this, 'createBikeMultiplier'], 1);
		$bikes->addCreateButton('bikes.add')
			->addClass('btn btn-success');
		$bikes->addRemoveButton('bikes.remove')
			->addClass('btn btn-danger');
		$form->addGroup();
		$form->addSelect('state', 'state')
			->setItems($this->listStates())
			->setPrompt('messages.state')
			->setRequired('messages.state');
		$form->addProtection();
		$form->onSubmit[] = [$this, 'save'];
		if ($this->presenter->getAction() === 'edit') {
			$id = $this->presenter->getParameter('id');
			$form->setDefaults($this->load(intval($id)));
			$form->addSubmit('edit', 'edit');
		} else {
			$form->addSubmit('add', 'add');
		}
		return $form;
	}

	/**
	 * Creates bike form multiplier
	 * @param Container $container Container for bikes
	 */
	public function createBikeMultiplier(Container $container): void {
		$container->addSelect('bike', 'bikes.bike')
			->setItems($this->listBikes())
			->setPrompt('messages.bike')
			->setRequired('messages.bike');
	}

	/**
	 * Lists all available bikes
	 * @return array<int,string> Available bikes
	 */
	public function listBikes(): array {
		$array = [];
		/**
		 * @var Bike[] $bikes Bikes
		 */
		$bikes = $this->manager->getBikeRepository()->findAll();
		$unit = $this->presenter->translator->translate('core.product.priceUnit');
		foreach ($bikes as $bike) {
			$array[$bike->getId()] = new NotTranslate(sprintf('%s (%s, %s, %s, %s %s)' ,
				$bike->getFullName(), $bike->getUsageName(),
				$bike->getWheelSize(), $bike->getFrameSize(),
				$bike->getPrice(), $unit
			));
		}
		return $array;
	}

	/**
	 * Lists all available users
	 * @return array<int,string> Available users
	 */
	private function listUsers(): array {
		$array = [];
		/**
		 * @var User[] $users Users
		 */
		$users = $this->manager->getUserRepository()
			->createQueryBuilder('u')
			->where('u.state = :state')
			->setParameter('state', User::STATE_ACTIVATED)
			->getQuery()->execute();
		foreach ($users as $user) {
			$name = $user->getFullName() . ' (' . $user->getEmail() . ')';
			$array[$user->getId()] = new NotTranslate($name);
		}
		return $array;
	}

	/**
	 * Lists all available states
	 * @return array<int,string> Available states
	 */
	private function listStates(): array {
		return [
			Reservation::STATE_RESERVATION => 'states.reservation',
			Reservation::STATE_CANCELLED => 'states.cancelled',
			Reservation::STATE_ONGOING => 'states.ongoing',
			Reservation::STATE_RETURNED => 'states.returned',
		];
	}

	/**
	 * Loads data for the form
	 * @param int $id Reservation ID
	 * @return array<int, mixed> Data for the form
	 */
	private function load(int $id): array {
		/**
		 * @var Reservation|null $reservation Reservation
		 */
		$reservation = $this->manager->getReservationRepository()->find($id);
		if ($reservation === null) {
			return [];
		}
		$data = [
			'customer' => $reservation->getCustomer()->getId(),
			'fromDate' => $reservation->getFromDate()->format('Y-m-d'),
			'toDate' => $reservation->getToDate()->format('Y-m-d'),
			'state' => $reservation->getState(),
		];
		foreach ($reservation->getBikes() as $bike) {
			$data['bikes'][] = ['bike' => $bike->getId()];
		}
		return $data;
	}

	/**
	 * Saves the values from the form
	 * @param Form $form Reservation editor form
	 */
	public function save(Form $form): void {
		$values = $form->getValues();
		$userRepository = $this->manager->getUserRepository();
		$customer = $userRepository->find($values->customer);
		$creator = $userRepository->find($this->presenter->getUser()->getId());
		$bikes = [];
		foreach ($values->bikes as $bike) {
			$bikes[] = $this->manager->getBikeRepository()->find($bike->bike);
		}
		$bikes = new ArrayCollection($bikes);
		$fromDate = new DateTime($values->fromDate);
		$toDate = new DateTime($values->toDate);
		$repository = $this->manager->getReservationRepository();
		$id = $this->presenter->getParameter('id');
		/**
		 * @var Reservation|null $reservation Reservation
		 */
		$reservation = $repository->find(intval($id));
		if ($reservation === null && $id === null) {
			$reservation = new Reservation($customer, $creator, $fromDate, $toDate, $bikes, $values->state);
		} else {
			$reservation->setCustomer($customer);
			$reservation->setCreatedBy($creator);
			$reservation->setFromDate($fromDate);
			$reservation->setToDate($toDate);
			$reservation->setBikes($bikes);
			$reservation->setState($values->state);
		}
		$this->manager->persist($reservation);
		$this->manager->flush();
		$message = $this->presenter->translator->translate('admin.reservation.messages.successEdit', ['id' => $reservation->getId()]);
		$this->presenter->flashSuccess($message);
		$this->presenter->redirect('Reservation:default');
	}

}
