<?php declare(strict_types = 1);

namespace Database\Fixtures;

use App\Domain\Article\Article;
use App\Domain\User\User;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends AbstractFixture
{

	public function getOrder(): int
	{
		return 2;
	}

	public function load(ObjectManager $manager): void
	{
		foreach ($this->getArticles() as $article) {
			$randomUserId = rand(1, 3);

			$user = $manager->getRepository(User::class)->find($randomUserId);

			$entity = new Article(
				$article['name'],
				$article['text'],
				$user
			);
			$entity->randomCreatedAt();
			$manager->persist($entity);
		}

		$manager->flush();
	}

	/**
	 * @return mixed[]
	 */
	protected function getArticles(): iterable
	{
		yield ['name' => 'Průzkum podmořské fauny v Karibiku', 'text' => 'Nepoznané druhy a jejich ekologie'];
		yield ['name' => 'Umění mindfulness', 'text' => 'Jak se soustředit ve světě plném rozptýlení'];
		yield ['name' => 'Budoucnost vesmírné turistiky', 'text' => 'Cesty k Marsu a mimo naši sluneční soustavu'];
		yield ['name' => 'Inovace ve vzdělávání', 'text' => 'Virtuální třídy a interaktivní učební prostředí'];
		yield ['name' => 'Rozbor politických trendů', 'text' => 'Vliv sociálních médií na volby a veřejné mínění'];
		yield ['name' => 'Technologie pro zdravotnictví', 'text' => 'Chytré implantáty a nanorobotika v léčbě nemocí'];
		yield ['name' => 'Kulturní odraz pandemie', 'text' => 'Změny v uměleckých a kulturních projevech po celém světě'];
		yield ['name' => 'Ekonomika a životní prostředí', 'text' => 'Nové přístupy k udržitelnému rozvoji a ochraně biodiverzity'];
		yield ['name' => 'Sportovní inovace', 'text' => 'Pokrok v biomechanice a výživě pro maximální výkon'];
		yield ['name' => 'Digitální bezpečnost ve 21. století', 'text' => 'Výzvy a řešení v době kybernetických hrozeb'];
	}

}
