<?php declare(strict_types = 1);

namespace App\Domain\Article;

use App\Model\Database\Repository\AbstractRepository;

/**
 * @method Article|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Article|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Article[] findAll()
 * @method Article[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<Article>
 */
class ArticleRepository extends AbstractRepository
{

}
