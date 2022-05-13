<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513131226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE direct_message (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, receiver_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, has_been_seen TINYINT(1) NOT NULL, INDEX IDX_1416AF93B03A8386 (created_by_id), INDEX IDX_1416AF93CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93B03A8386 FOREIGN KEY (created_by_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE direct_message');
    }
}
