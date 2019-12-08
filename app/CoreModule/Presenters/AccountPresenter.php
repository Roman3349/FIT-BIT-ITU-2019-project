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

namespace App\CoreModule\Presenters;

use App\CoreModule\Forms\AccountFormFactory;
use App\Models\Database\EntityManager;
use Nette\Application\UI\Form;

/**
 * Account presenter
 */
final class AccountPresenter extends BasePresenter {
    /**
     * @var AccountFormFactory Account form factory
     * @inject
     */
    public $formFactory;

    /**
     * @var EntityManager Entity manager
     */
    private $entityManager;

    /**
     * Constructor
     * @param EntityManager $manager Entity manager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Renders user editor
     * @param int $id User ID
     */
    public function renderEdit(int $id): void {
        $this->template->id = $id;
    }

    /**
     * Creates the account form
     * @return Form Account form
     */
    protected function createComponentAccountForm(): Form {
        return $this->formFactory->create($this);
    }
}