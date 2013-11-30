<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

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
		$formCtenari = new Form();

		$formCtenari->addText("name", "Jméno: ")->setRequired("Prosím, vyplňte Vaše jméno.")
										 ->setAttribute('placeholder', 'Vaše jméno...');
		$formCtenari->addText("email", "E-mail: ")->setRequired("Prosím, vyplňte Váš e-mail.")
										   ->addRule(Form::EMAIL, "Zadaná e-mailová adresa není platná.")
										   ->setAttribute('placeholder', 'Váš e-mail...');


		$formCtenari->addSubmit("submit", "Přidat uživatele");

		$formCtenari->onSuccess[] = callback($this, "addUserFormSubmitted");

		return $formCtenari;
	}

	public function createComponentSearchForm() {
		$formSearch = new Form();

		$formSearch->addText("search")->setRequired("Prosím, vyplňte hledané slovo.")
									  ->setAttribute('placeholder', 'Hledat...');

		$formSearch->addImage("submit", "../images/search.gif");

		//$formSearch->onSuccess[] = callback($this, "searchFormSubmitted");

		return $formSearch;
	}

	public function addUserFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->addUser($val->name, $val->email);
		$this->flashMessage("Uživatel byl úspěšně přidán.", "success");
		$this->redirect("default");
	}

}
