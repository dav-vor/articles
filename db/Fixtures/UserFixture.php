<?php declare(strict_types = 1);

namespace Database\Fixtures;

use App\Domain\User\User;
use Doctrine\Persistence\ObjectManager;
use Nette\Security\Passwords;

class UserFixture extends AbstractFixture
{

	public function getOrder(): int
	{
		return 1;
	}

	public function load(ObjectManager $manager): void
	{
		foreach ($this->getUsers() as $user) {
			$entity = new User(
				$user['name'],
				$user['surname'],
				$user['email'],
				$user['username'],
				$this->container->getByType(Passwords::class)->hash($user['password'])
			);

			$entity->setRole($user['role']);

			$manager->persist($entity);
		}

		$manager->flush();
	}

	/**
	 * @return mixed[]
	 */
	protected function getUsers(): iterable
	{
		yield ['email' => 'admin@admin.cz', 'name' => 'Admin', 'surname' => 'Admin', 'username' => 'admin', 'role' => User::ROLE_ADMIN, 'password' => 'admin'];
		yield ['email' => 'alice@example.com', 'name' => 'Alice', 'surname' => 'Smith', 'username' => 'alice', 'role' => User::ROLE_REDACTOR, 'password' => 'alice'];
		yield ['email' => 'Bob@example.com', 'name' => 'Bob', 'surname' => 'Johnson', 'username' => 'bob', 'role' => User::ROLE_REDACTOR, 'password' => 'bob'];
	}

}
