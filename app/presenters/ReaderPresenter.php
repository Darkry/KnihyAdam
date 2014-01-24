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

	public function createComponentEditReaderForm() {
		$formCtenari = new Form();

		$formCtenari->addText("name", "Jméno: ")->setRequired("Prosím, vyplňte jméno čtenáře.")
										 ->setAttribute('placeholder', 'Jméno čtenáře...');
		$formCtenari->addText("email", "E-mail: ")->setRequired("Prosím, vyplňte příjmení čtenáře.")
										 ->setAttribute('placeholder', 'E-mail čtenáře...');

		$id = $this->getParameter("id");
		$data = $this->cModel->getUser($id);
		$formCtenari->setDefaults(array('name' => $data->jmeno, 'email' => $data->email));

		$formCtenari->addHidden("id", $id);
		$formCtenari->addSubmit("submit", "Upravit údaje");

		$formCtenari->onSuccess[] = callback($this, "editReaderFormSubmitted");

		return $formCtenari;
	}
	public function editReaderFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->editReader($val->name, $val->email, $val->id);
		$this->flashMessage("Údaje byly úspěšně změněny.", "success");
		$this->redirect("this");
	}

	public function renderDetail($id) {
		$user = $this->cModel->getUser($id);
		$this->template->jmeno = $user->jmeno;
		$this->template->email = $user->email;
		$this->template->id = $user->id;}

	public function handleDeleteReader($delId) {
		if($this->cModel->getReaderBooksCount($delId) > 0) {
			$this->flashMessage("Čtenář nemohl být smazán, protože má momentálně půjčené nějaké knihy.", "error");
			$this->redirect("this");
		}
		else {
			$this->cModel->deleteReader($delId);
			$this->flashMessage("Čtenář byl úspěšně smazán z naší databáze.", "success");
			$this->redirect("default");
		}
	}

}
