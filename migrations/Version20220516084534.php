<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516084534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add another table infon in user';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD phone_number VARCHAR(255) NOT NULL, ADD parc_place VARCHAR(255) NOT NULL, ADD language_use VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP phone_number, DROP parc_place, DROP language_use');
    }
}
