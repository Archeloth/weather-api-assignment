<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129194557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notice ADD COLUMN email_sent_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX email_city');
        $this->addSql('CREATE TEMPORARY TABLE __temp__notice AS SELECT id, email, city, temp_limit FROM notice');
        $this->addSql('DROP TABLE notice');
        $this->addSql('CREATE TABLE notice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, temp_limit INTEGER NOT NULL)');
        $this->addSql('INSERT INTO notice (id, email, city, temp_limit) SELECT id, email, city, temp_limit FROM __temp__notice');
        $this->addSql('DROP TABLE __temp__notice');
        $this->addSql('CREATE UNIQUE INDEX email_city ON notice (email, city)');
    }
}
