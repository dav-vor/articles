<?php declare(strict_types = 1);

namespace App\Domain\User;

use App\Model\Database\EntityManagerDecorator;

class UserFacade
{

	public function __construct(private EntityManagerDecorator $em)
	{
	}

	/**
	 * @return User[]
	 */
	public function findAll(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
	}

	/**
	 * @psalm-param array<string, mixed> $criteria
	 * @psalm-param array<string, string>|null $orderBy
	 * @return User[]
	 */
	public function findBy(array $criteria = [], array $orderBy = ['id' => 'ASC'], int $limit = 10, int $offset = 0): array
	{
		return $this->em->getRepository(User::class)->findBy($criteria, $orderBy, $limit, $offset);
	}

	/**
	 * @psalm-param array<string, mixed> $criteria
	 */
	public function findOneBy(array $criteria): User
	{
		$user = $this->em->getRepository(User::class)->findOneBy($criteria);
		if ($user === null) {
			throw new \Exception('User not found.');
		}

		return $user;
	}

}
