<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240114195049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quote_item ADD price_including_tax DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quote_item ADD tax_amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quote_item RENAME COLUMN price TO price_excludingtax');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE quote_item ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE quote_item DROP price_excludingtax');
        $this->addSql('ALTER TABLE quote_item DROP price_including_tax');
        $this->addSql('ALTER TABLE quote_item DROP tax_amount');
    }
}
