<?php declare(strict_types = 1);

namespace App\Mail;

use App\Domain\User\User;
use App\Domain\User\UserFacade;
use Nette\Mail\Mailer;
use Nette\Mail\Message;

class SendEmail
{

	public function __construct(private Mailer $mailer, private UserFacade $userFacade)
	{
	}

	public function sendMail(string $from, string $to, string $subject, string $body): void
	{
		$mail = new Message();
		$mail->setFrom($from)
			->addTo($to)
			->setSubject($subject)
			->setBody($body);

		//$this->mailer->send($mail);//not working on local
	}

	public function sendAdminsMail(string $subject, string $body): void
	{
		$admins = $this->userFacade->findBy(['role' => User::ROLE_ADMIN]);
		foreach ($admins as $admin) {
			$this->sendMail('me@email.cz', $admin->getEmail(), $subject, $body);
		}
	}

}
