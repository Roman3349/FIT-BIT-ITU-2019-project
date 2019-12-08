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

use Nette\Security\Passwords;
use App\CoreModule\Presenters\AccountPresenter;
use App\Models\Database\EntityManager;
use App\Models\Database\Entities\User;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Account form factory
 */
final class AccountFormFactory {

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
	 * @var AccountPresenter Account presenter
	 */
	private $presenter;

    /**
     * @var Passwords Password manager
     */
    private $passwords;

    /**
     * @var int|null User ID
     */
    private $id;

	/**
	 * Constructor
	 * @param FormFactory $factory Generic form factory
	 * @param EntityManager $entityManager Entity manager
	 */
	public function __construct(FormFactory $factory, EntityManager $entityManager, Passwords $passwords) {
		$this->factory = $factory;
		$this->entityManager = $entityManager;
        $this->passwords = $passwords;
    }

	/**
	 * Creates a new account form (edit profile)
	 * @param AccountPresenter $presenter Account presenter
	 * @return Form Account form
	 */
	public function create(AccountPresenter $presenter): Form {
        $this->presenter = $presenter;
        $form = $this->factory->create();
        if ($this->presenter->user->isLoggedIn()) {
            $customer = $this->entityManager->getUserRepository()->find($this->presenter->getUser()->getId());
            $this->factory->setTranslationPrefix('core.account');
            $form->addText('firstName', 'firstName')
                ->setRequired('messages.firstName');
            $form->addText('lastName', 'lastName')
                ->setRequired('messages.lastName');
            $form->addEmail('email', 'email')
                ->setRequired('messages.email');
            $form->addPassword('password', 'password');
            $form->addProtection();
            $form->setDefaults($this->load($customer->getId()));
            $form->addSubmit('save', 'save');
            $form->onSubmit[] = [$this, 'save'];
        }
        return $form;
	}

    /**
     * Loads data from the database
     * @param int $id User ID
     * @return array<string,mixed> Data for the form
     */
    private function load(int $id): array {
        /**
         * @var User User entity
         */
        $user = $this->entityManager->getUserRepository()->find(intval($id));
        return [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
        ];
    }

    /**
     * Saves values from the form
     * @param Form $form Manufacturer form
     */
    public function save(Form $form): void {
        $values = $form->getValues();
        /**
         * @var User|null User entity
         */
        $user = $this->entityManager->getUserRepository()->find($this->presenter->getUser()->getId());
        $translator = $this->presenter->translator;
        if ($user != null) {
            $user->setFirstName($values->firstName);
            $user->setLastName($values->lastName);
            $user->setEmail($values->email);
            if ($values->password != null) {
                $hash = $this->passwords->hash($values->password);
                $user->setPassword($hash);
            }
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->presenter->user->logout();
        $message = $translator->translate('core.account.messages.successEdit', ['email' => $user->getEmail()]);
        $this->presenter->flashSuccess($message);
        $this->presenter->redirect('Sign:in');
    }
}