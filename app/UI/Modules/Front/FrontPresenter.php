<?php declare(strict_types = 1);

namespace App\UI\Modules\Front;

use App\Model\Security\Identity;
use Nette\Application\UI\Presenter;
use Nette\Bridges\SecurityHttp\SessionStorage;

class FrontPresenter extends Presenter
{

	public function __construct(private SessionStorage $storage)
	{
		parent::__construct();
	}

	public function startup(): void
	{
		parent::startup();

		$this->getTemplate()->loggedIn = $this->isLogged();
	}

	protected function isLogged(): bool
	{
		return $this->storage->getState()[0];
	}

	protected function getIdentity(): Identity
	{
		$identity = $this->storage->getState()[1];
		if (!$identity instanceof Identity) {
			throw new \Exception('Wrong identity');
		}

		return $identity;
	}

}
