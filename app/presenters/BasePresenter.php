<?php
use \Nette\Application\UI\Form;
use \Nette\Utils\Html;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	public function createComponentSearchForm() {
		$formSearch = new Form();

		$formSearch->addText("search")->setRequired("Prosím, vyplňte hledané slovo.")
									  ->setAttribute('placeholder', 'Hledat...');

		$formSearch->addImage("submit", $this->template->basePath."/images/search.gif");

		//$formSearch->onSuccess[] = callback($this, "searchFormSubmitted");

		return $formSearch;
	}

}
