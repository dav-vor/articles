<?php declare(strict_types = 1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224000000 extends AbstractMigration
{

	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

		$this->addSql('CREATE SEQUENCE "article_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE TABLE "article" (id INT NOT NULL, name VARCHAR(255) NOT NULL, user_id INT not null, text TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id), FOREIGN KEY (user_id) REFERENCES "user"(id))');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

		$this->addSql('DROP SEQUENCE "article_id_seq" CASCADE');
		$this->addSql('DROP TABLE "article"');
	}

}
