<?php declare(strict_types = 1);

namespace App\Domain\Article;

use App\Domain\User\User;
use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Article\ArticleRepository")
 * @ORM\Table(name="`article`")
 * @ORM\HasLifecycleCallbacks
 */
class Article extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	/** @ORM\Column(type="string", length=255, nullable=FALSE) */
	private string $text;

	/** @ORM\ManyToOne(targetEntity="App\Domain\User\User", inversedBy="articles") */
	private User $user;

	public function __construct(string $name, string $text, User $user)
	{
		$this->name = $name;
		$this->text = $text;
		$this->user = $user;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getText(): string
	{
		return $this->text;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	/**
	 * Doctrine annotation
	 *
	 * @ORM\PrePersist
	 * @internal
	 */
	public function setCreatedAt(): void
	{
		$this->createdAt = php_sapi_name() === 'cli' ? $this->randomCreatedAt() : new \DateTime();
	}

	public function randomCreatedAt(): \DateTime
	{
		$year = mt_rand(1970, 2024);
		$month = mt_rand(1, 12);
		$day = mt_rand(1, 28);
		$hour = mt_rand(0, 23);
		$minute = mt_rand(0, 59);
		$second = mt_rand(0, 59);

		// phpcs:disable Squiz.Strings.DoubleQuoteUsage.ContainsVar
		return new \DateTime("$year-$month-$day $hour:$minute:$second");
		// phpcs:enable Squiz.Strings.DoubleQuoteUsage.ContainsVar
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setText(string $text): void
	{
		$this->text = $text;
	}

}
