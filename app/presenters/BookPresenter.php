<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

class BookPresenter extends BasePresenter
{

	private $authorModel;
	private $genreModel;
	private $bookModel;

	public function startup() {
		parent::startup();
		$this->authorModel = $this->getService("autorRepository");
		$this->bookModel = $this->getService("knihaRepository");
		$this->genreModel = $this->getService("zanrRepository");
	}

	public function renderDefault() {
		$books = $this->bookModel->getAllBooks();
		$this->template->knihy = $books;

	}

	public function createComponentAddBookForm() {
		$form = new Form();

		$form->addText("name", "Název: ")->setRequired("Vyplňte, prosím, název knihy.");
		$form->addText("number", "Počet kopií: ")->setRequired("Prosíme, vyplňte počet kopií knihy, které knihovna vlastní.")
												 ->addRule(Form::FLOAT, "Hodnota počtu výtisků musí být číslo.")
												 ->setType("number");

		$authorList = $this->authorModel->getAllAuthors();
		$authors = array();
		foreach($authorList as $author) {
			$authors[$author->id] = $author->jmeno . " " . $author->prijmeni;
		}
		$form->addSelect("author", "Autor: ", $authors)->setPrompt("Vyberte autora")->setRequired("Prosíme, vyberte autora knihy.");


		$genreList = $this->genreModel->getAllGenres();
		$genres = array();
		foreach($genreList as $genre) {
			$genres[$genre->id] = $genre->nazev;
		}
		$form->addSelect("genre", "Žánr:: ", $genres)->setPrompt("Vyberte žánr")->setRequired("Prosíme, vyberte žánr knihy.");

		$form->addSubmit("submit", "Přidat knihu");

		$form->onSuccess[] = callback($this, "addBookFormSubmitted");

		return $form;
	}

	public function addBookFormSubmitted(Form $form) {

		$val = $form->getValues();
		$this->bookModel->addBook($val->name, $val->author, $val->genre, $val->number);
		$this->flashMessage("Kniha byla úspěšně přidána.");
		$this->redirect("this");
	}

	public function renderDetail($id) {
		$b = $this->bookModel->getBook($id);
		$this->template->nazev = $b->nazev;
		$this->template->vytiskuCelkem = $b->celkemVytisku;
		$this->template->volneVytisky = $b->volnychVytisku;
	}

	public function createComponentEditBookForm() {
		$form = new Form();

		$form->addText("name", "Název: ")->setRequired("Vyplňte, prosím, název knihy.");
		$authorList = $this->authorModel->getAllAuthors();
		$authors = array();
		foreach($authorList as $author) {
			$authors[$author->id] = $author->jmeno . " " . $author->prijmeni;
		}
		$form->addSelect("author", "Autor: ", $authors)->setRequired("Prosíme, vyberte autora knihy.");


		$genreList = $this->genreModel->getAllGenres();
		$genres = array();
		foreach($genreList as $genre) {
			$genres[$genre->id] = $genre->nazev;
		}
		$form->addSelect("genre", "Žánr:: ", $genres)->setRequired("Prosíme, vyberte žánr knihy.");

		$id = $this->getParameter("id");
		$data = $this->bookModel->getBook($id);
		$form->setDefaults(array('name' => $data->nazev, 'author' => $data->autor_id, 'genre' => $data->zanr_id));

		$form->addHidden("id", $id);
		$form->addSubmit("submit", "Upravit knihu");

		$form->onSuccess[] = callback($this, "editBookFormSubmitted");
		return $form;
	}

	public function editBookFormSubmitted(Form $form) {
		$val = $form->getValues();
		$this->bookModel->editBook($val->id, $val->name, $val->author, $val->genre);
		$this->flashMessage("Kniha byla úspěšně upravena.");
		$this->redirect("this");
	}

	public function createComponentAddNewCopies() {
		$form = new Form();

		$form->addText("copies", "Přidat X kopií: ")->setType("number")->addRule(Form::FLOAT, "Počet kopií musí být číslo.")
													->setRequired("Musíte vyplnit počet kopií k přidání.");

		$form->addSubmit("submit", "Přidat nové výtisky");
		$form->onSuccess[] = callback($this, "addNewCopiesSubmitted");
		return $form;
	}

	public function addNewCopiesSubmitted(Form $form) {
		$val = $form->getValues();

		$freeCopies = $this->bookModel->getFreeCopiesCount($this->getParameter("id"));
		if($val->copies*(-1) > $freeCopies) {
			$this->flashMessage("Nemůžete odebrat více výtisků než je momentálně nevypůjčených.");
		}
		else {
			$this->bookModel->increaseCopiesNumber($val->copies, $this->getParameter("id"));
			$this->flashMessage("Počet výtisků byl úspěšně navýšen.");
		}
		$this->redirect("this");
	}
}
