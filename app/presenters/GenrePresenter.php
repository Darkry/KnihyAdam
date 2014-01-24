<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

class GenrePresenter extends BasePresenter
{

	private $cModel;

	public function startup() {
		parent::startup();
		$this->cModel = $this->getService("zanrRepository");
	}

	public function renderDefault() {
		$this->template->zanry = $this->cModel->getAllGenres();
	}

	public function createComponentAddGenreForm() {
		$form = new Form();

		$form->addText("name", "Název: ")->setRequired("Musíte vyplnit název žánru.")->addRule(Form::MAX_LENGTH, "Název je příliš dlouhý.", 49);
		$form->addUpload("icon", "Ikona žánru")->addRule(FORM::IMAGE, "Nahrávaný soubor musí být obrázek.");

		$form->addSubmit("submit", "Přidat žánr");

		$form->onSuccess[] = callback($this, "addGenreFormSubmitted");

		return $form;
	}

	public function addGenreFormSubmitted(Form $form) {

		$val = $form->getValues();

		$file = $val->icon;

		$path = "";
		if ($file->isOk() && $file->isImage()) {
			$img = $file->toImage();
			$fileName = uniqid() . $file->getName();

			$img->resize(NULL,40);
			$img->save(WWW_DIR . "/upload/".$fileName);

			$path = "upload/".$fileName;

			$this->cModel->addGenre($val->name, $path);

			$this->flashMessage("Žánr byl úspěšně přidán.");
		} else {
			$this->flashMessage("Nastala chyba při nahrávání obrázku.");			
		}

		$this->redirect("this");
	}

	public function renderDetail($id) {
		$zanr = $this->cModel->getGenre($id);
		$this->template->nazev = $zanr->nazev;
		$this->template->ikona = $zanr->ikona;
		$this->template->id = $zanr->id;
		$this->template->knihy = $this->cModel->getAllGenreBooks($id);
	}

	public function createComponentEditGenreForm() {
		$formZanry = new Form();

		$formZanry->addText("name", "Název: ")->setRequired("Prosím, vyplňte jméno autora.")
										 ->setAttribute('placeholder', 'Název žánru...');

		$id = $this->getParameter("id");
		$data = $this->cModel->getGenre($id);
		$formZanry->setDefaults(array('name' => $data->nazev));

		$formZanry->addHidden("id", $id);
		$formZanry->addSubmit("submit", "Upravit údaje");

		$formZanry->onSuccess[] = callback($this, "editGenreFormSubmitted");

		return $formZanry;
	}
	public function editGenreFormSubmitted(Form $form) {
		$val = $form->getValues();

		$this->cModel->editGenre($val->name, $val->id);
		$this->flashMessage("Údaje byly úspěšně změněny.", "success");
		$this->redirect("this");
	}

	public function handleDeleteGenre($delId) {
		if($this->cModel->getGenreBooksCount($delId) > 0) {
			$this->flashMessage("Žánr nemohl být smazán, protože v naší databázi jsou od něj uloženy nějaké knihy.", "error");
			$this->redirect("this");
		}
		else {
			$this->cModel->deleteGenre($delId);
			$this->flashMessage("Žánr byl úspěšně smazán z naší databáze.", "success");
			$this->redirect("default");
		}
	}
}
