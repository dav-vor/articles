<?php declare(strict_types = 1);

namespace App\Domain\Article;

use App\Domain\User\User;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Utils\Image;
use Nette\Http\FileUpload;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

class ArticleFacade
{

	public const LIMIT = 10;

	public function __construct(private EntityManagerDecorator $em)
	{
	}

	/**
	 * @return Article[]
	 */
	public function findAll(int $limit = self::LIMIT, int $page = 1): array
	{
		return $this->findBy([], ['id' => 'ASC'], self::LIMIT, ($page - 1) * $limit);
	}

	/**
	 * @psalm-param array<string, mixed> $criteria
	 * @psalm-param array<string, string>|null $orderBy
	 * @return Article[]
	 */
	public function findBy(array $criteria = [], array $orderBy = ['id' => 'ASC'], int $limit = self::LIMIT, int $page = 1): array
	{
		return $this->em->getRepository(Article::class)->findBy($criteria, $orderBy, $limit, ($page - 1) * $limit);
	}

	public function find(int $id): Article
	{
		$article = $this->em->getRepository(Article::class)->find($id);

		if ($article === null) {
			throw new \Exception('Article not found for ID: ' . $id);
		}

		return $article;
	}

	/**
	 * @return Image[]
	 */
	public function getImages(int $id): array
	{
		$directory = sprintf('uploads/articles/%d', $id);
		$imageFilenames = [];

		if (is_dir($directory)) {
			$finder = Finder::findFiles('*')->from($directory);

			foreach ($finder as $file) {
				$image = Image::fromFile($directory . '/' . $file->getFilename());

				$imageFilenames[] = $image;
			}
		}

		return $imageFilenames;
	}

	/**
	 * @param FileUpload[] $images
	 */
	public function saveImages(int $id, array $images): void
	{
		$path = 'uploads/articles/' . $id;
		FileSystem::createDir($path);
		foreach ($images as $file) {
			$image = Image::fromFile($file->getTemporaryFile());
			$image->save($path . '/' . $file->name);
		}
	}

	/**
	 * @param array<string,mixed> $data
	 */
	public function createArticle(array $data): Article
	{
		$user = $this->em->getRepository(User::class)->find($data['user_id']);

		if ($user === null) {
			throw new \Exception('User not found for ID: ' . $data['user_id']);
		}

		$article = new Article(
			$data['name'],
			$data['text'],
			$user
		);

		$this->em->persist($article);
		$this->em->flush();

		return $article;
	}

	/**
	 * @param array<string,mixed> $data
	 */
	public function updateArticle(int $id, array $data): Article
	{
		$article = $this->find($id);

		$article->setName($data['name']);
		$article->setText($data['text']);

		$this->em->persist($article);
		$this->em->flush();

		return $article;
	}

	/**
	 * @psalm-param array<string, mixed> $criteria
	 * @psalm-param array<string, string>|null $orderBy
	 */
	public function getTotalPages(array $criteria, int $limit = self::LIMIT): int
	{
		$total = count($this->em->getRepository(Article::class)->findBy($criteria));

		return (int) ceil($total / $limit);
	}

}
