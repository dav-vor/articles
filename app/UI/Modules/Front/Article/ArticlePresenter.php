<?php declare(strict_types = 1);

namespace App\UI\Modules\Front\Article;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleFacade;
use App\Mail\SendEmail;
use App\UI\Modules\Front\FrontPresenter;
use Nette\Application\UI\Form;
use Nette\Bridges\SecurityHttp\SessionStorage;
use Nette\Utils\ArrayHash;

class ArticlePresenter extends FrontPresenter
{

	public function __construct(
		private SessionStorage $storage,
		private ArticleFacade $articleFacade,
		private SendEmail $sendEmail,
	)
	{
		parent::__construct($this->storage);
	}

	public function actionEdit(?int $id = null): void
	{
		if ($id !== null) {
			$this->fillData($id);
		} else {
			$this->getTemplate()->images = [];
			$this->getTemplate()->articleForm = $this['articleForm'];
		}
	}

	public function saveArticleSucceeded(Form $form, ArrayHash $values): void
	{
		$values['user_id'] = $this->getIdentity()->getId();
		$article = isset($values->id) ?
			$this->articleFacade->updateArticle(intval($values['id']), (array) $values) :
			$this->articleFacade->createArticle((array) $values);

		$this->articleFacade->saveImages($article->getId(), $values->images);

		if (!$this->getIdentity()->isAdmin()) {
			$this->sendEmail($values, $article->getId());
		}

		$this->redirect('Article:edit', ['id' => $article->getId()]);
	}

	public function sendEmail(ArrayHash $values, int $id): void
	{
		$this->sendEmail->sendAdminsMail('Nový článek ' . $values['name'], 'Byl vytvořen nový článek ' . $this->link('Article:', ['id' => $id]));
	}

	protected function createComponentArticleForm(): Form
	{
		$form = new Form();
		$form->addHidden('id');
		$form->addText('name', 'Jméno')->setRequired();
		$form->addText('text', 'Text');
		$form->addMultiUpload('images', 'Obrázek k článku:')
			->addRule(Form::IMAGE, 'JPEG, PNG nebo GIF.')
			->setOption('label', 'Text');

		$form->addSubmit('submit', 'Uložit');

		$form->onSuccess[] = [$this, 'saveArticleSucceeded'];

		return $form;
	}

	protected function fillData(int $id): void
	{
		$article = $this->articleFacade->find($id);
		$this->checkOwnership($article);

		$this->setArticleData($article);
		$this->getTemplate()->id = $id;
		$this->getTemplate()->images = $this->articleFacade->getImages($id);
	}

	protected function setArticleData(Article $article): void
	{
		$form = $this['articleForm'];

		$defaultFormData = [];
		$defaultFormData['id'] = $article->getId();
		$defaultFormData['name'] = $article->getName();
		$defaultFormData['text'] = $article->getText();

		$form->setDefaults($defaultFormData);
		$this->getTemplate()->articleForm = $form;
	}

	protected function checkOwnership(Article $article): void
	{
		if (!$this->isLogged()) {
			throw new \Exception('Uživatel není přihlášený.');
		}

		if ($this->getIdentity()->getId() !== $article->getUser()->getId() && !$this->getIdentity()->isAdmin()) {
			throw new \Exception('Přihlášený uživatel nemá přístup k danému článku.');
		}
	}

}
