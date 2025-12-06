<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251206114755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_it ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE post_it ADD CONSTRAINT FK_563E13487E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_563E13487E3C61F9 ON post_it (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_it DROP CONSTRAINT FK_563E13487E3C61F9');
        $this->addSql('DROP INDEX IDX_563E13487E3C61F9');
        $this->addSql('ALTER TABLE post_it DROP owner_id');
    }
}
