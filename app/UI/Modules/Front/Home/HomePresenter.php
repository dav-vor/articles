<?php declare(strict_types = 1);

namespace App\UI\Modules\Front\Home;

use App\Domain\Article\ArticleFacade;
use App\Domain\User\User;
use App\UI\Modules\Front\FrontPresenter;
use Nette\Bridges\SecurityHttp\SessionStorage;

class HomePresenter extends FrontPresenter
{

	public function __construct(
		private SessionStorage $storage,
		private ArticleFacade $articleFacade
	)
	{
		parent::__construct($this->storage);
	}

	public function actionDefault(): void
	{
		if ($this->isLogged()) {
			$this->getData();
		}
	}

	public function handleTable(string $orderBy, int $page): void
	{
		if ($this->isLogged()) {
			$this->getData($page, $orderBy);
			$this->redrawControl('table');
		}
	}

	public function getData(int $page = 1, string $orderBy = 'DESC'): void
	{
		$identity = $this->getIdentity();
		$criteria = in_array(User::ROLE_ADMIN, $identity->getRoles(), true) ? [] : ['user' => $identity->getId()];

		$this->getTemplate()->sort = $orderBy === 'ASC' ? 'DESC' : 'ASC';
		$this->getTemplate()->totalPages = $this->articleFacade->getTotalPages($criteria);
		$this->getTemplate()->data = $this->articleFacade->findBy($criteria, ['createdAt' => $orderBy], page: $page);
		$this->getTemplate()->page = $page;
	}

}
