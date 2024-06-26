<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506163914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restriction ADD restricted_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE restriction ADD CONSTRAINT FK_7A999BCE4577F9EA FOREIGN KEY (restricted_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7A999BCE4577F9EA ON restriction (restricted_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restriction DROP FOREIGN KEY FK_7A999BCE4577F9EA');
        $this->addSql('DROP INDEX IDX_7A999BCE4577F9EA ON restriction');
        $this->addSql('ALTER TABLE restriction DROP restricted_user_id');
    }
}
