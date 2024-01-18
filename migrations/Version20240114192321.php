<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240114192321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quote_item ADD tax_id UUID NOT NULL');
        $this->addSql('ALTER TABLE quote_item ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('COMMENT ON COLUMN quote_item.tax_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE quote_item ADD CONSTRAINT FK_8DFC7A94B2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8DFC7A94B2A824D8 ON quote_item (tax_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE quote_item DROP CONSTRAINT FK_8DFC7A94B2A824D8');
        $this->addSql('DROP INDEX IDX_8DFC7A94B2A824D8');
        $this->addSql('ALTER TABLE quote_item DROP tax_id');
        $this->addSql('ALTER TABLE quote_item DROP price');
    }
}
