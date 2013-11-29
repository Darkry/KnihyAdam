<?php
use \Nette\Application\UI\Form;

class HomepagePresenter extends BasePresenter
{

	private $cModel;

	public function startup() {
		parent::startup();
		$this->cModel = $this->getService("ctenarRepository");
	}

	public function renderDefault() {
		
	}

	public function createComponentAddUserForm() {
		$form = new Form();

		$form->addText("name", "Jméno: ")->setRequired("Prosím vyplňte Vaše jméno.");
		$form->addText("email", "E-mail: ")->setRequired("Prosím, vyplňte Váš e-mail.")
										   ->addRule(Form::EMAIL, "Zadaná e-mailová adresa není platná.");


		$form->addSubmit("submit", "Přidat uživatele");

		$form->onSuccess[] = callback($this, "addUserFormSubmitted");

		return $form;
	}

	public function addUserFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->addUser($val->name, $val->email);
		$this->flashMessage("Uživatel byl úspěšně přidán.", "success");
		$this->redirect("default");
	}

}
