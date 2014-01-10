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
		}

		$this->cModel->addGenre($val->name, $path);

		$this->flashMessage("Žánr byl úspěšně přidán.");
		$this->redirect("this");
	}
}
