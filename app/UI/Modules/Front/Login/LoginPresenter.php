<?php declare(strict_types = 1);

namespace App\UI\Modules\Front\Login;

use App\Domain\User\UserFacade;
use App\Model\Security\Identity;
use App\UI\Modules\Front\FrontPresenter;
use Nette\Application\UI\Form;
use Nette\Bridges\SecurityHttp\SessionStorage;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;

class LoginPresenter extends FrontPresenter
{

	public function __construct(
		private SessionStorage $storage,
		private UserFacade $userFacade,
		private Passwords $passwords
	)
	{
		parent::__construct($this->storage);
	}

	public function actionLogout(): void
	{
		$this->storage->clearAuthentication(true);
		$this->redirect('Login:');
	}

	public function actionDefault(): void
	{
		$this->getTemplate()->loginForm = $this['loginForm'];
	}

	public function loginFormSucceeded(Form $form, ArrayHash $values): void
	{
		$user = $this->userFacade->findOneBy(['username' => $values->name]);

		$identity = new Identity($user->getId(), $user->getRole());
		$this->storage->saveAuthentication($identity);
		$this->redirect('Home:');
	}

	public function loginFormValidate(Form $form, ArrayHash $values): void
	{
		try {
			$user = $this->userFacade->findOneBy(['username' => $values->name]);
			if (!$this->passwords->verify($values->password, $user->getPasswordHash())) {
				throw new \Exception('Špatné heslo');
			}
		} catch ( \Throwable $exception) {
			$form->addError($exception->getMessage());
		}
	}

	protected function createComponentLoginForm(): Form
	{
		$form = new Form();
		$form->addText('name', 'Jméno')
			->setRequired();
		$form->addText('password', 'Heslo')
			->setRequired();

		$form->addSubmit('login', 'Login');

		$form->onValidate[] = [$this, 'loginFormValidate'];
		$form->onSuccess[] = [$this, 'loginFormSucceeded'];

		return $form;
	}

}
