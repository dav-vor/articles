<?php declare(strict_types = 1);

namespace App\Domain\Article;

use App\Model\Database\Query\AbstractQuery;
use Doctrine\ORM\QueryBuilder;

class ArticleQuery extends AbstractQuery
{

	public static function ofEmail(string $email): self
	{
		$self = new self();
		$self->ons[] = function (QueryBuilder $qb) use ($email): QueryBuilder {
			$qb->andWhere('u.email = :email')
				->setParameter('email', $email);

			return $qb;
		};

		return $self;
	}

	public function setup(): void
	{
		$this->ons[] = function (QueryBuilder $qb): QueryBuilder {
			$qb->select('u')
				->from(Article::class, 'u');

			return $qb;
		};
	}

}
