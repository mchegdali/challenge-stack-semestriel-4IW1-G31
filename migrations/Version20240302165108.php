<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302165108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('ALTER TABLE quote_status ADD bg_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE quote_status ADD text_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE quote_status ADD border_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status ADD border_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status ADD text_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status ADD bg_color VARCHAR(255) NOT NULL');
    }
}
