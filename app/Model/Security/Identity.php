<?php declare(strict_types = 1);

namespace App\Model\Security;

use Nette\Security\SimpleIdentity as NetteIdentity;

class Identity extends NetteIdentity
{

	public function isAdmin(): bool
	{
		return in_array('admin', $this->getRoles(), true);
	}

}
