<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

class ReaderPresenter extends BasePresenter
{

	private $cModel;

	public function startup() {
		parent::startup();
		$this->cModel = $this->getService("ctenarRepository");
	}

	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!DEFAULT

	public function renderDefault() {
		$users = $this->cModel->getAllUsers();
		$this->template->ctenari = $users;
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

	public function addUserFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->addUser($val->name, $val->email);
		$this->flashMessage("Uživatel byl úspěšně přidán.", "success");
		$this->redirect("default");
	}

	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!DETAIL

	public function renderDetail($id) {
		$user = $this->cModel->getUser($id);
		$this->template->jmeno = $user->jmeno;
		$this->template->email = $user->email;
	}

}
