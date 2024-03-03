<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303005739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD quote_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN invoice.quote_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90651744DB805178 ON invoice (quote_id)');
        $this->addSql('ALTER TABLE invoice_status DROP border_color');
        $this->addSql('ALTER TABLE invoice_status DROP text_color');
        $this->addSql('ALTER TABLE invoice_status DROP bg_color');
        $this->addSql('ALTER TABLE quote_status DROP bg_color');
        $this->addSql('ALTER TABLE quote_status DROP text_color');
        $this->addSql('ALTER TABLE quote_status DROP border_color');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice_status ADD border_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status ADD text_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status ADD bg_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE quote_status ADD bg_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE quote_status ADD text_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE quote_status ADD border_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744DB805178');
        $this->addSql('DROP INDEX IDX_90651744DB805178');
        $this->addSql('ALTER TABLE invoice DROP quote_id');
    }
}
