<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207101151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840d6bf700bd');
        $this->addSql('DROP SEQUENCE payment_status_id_seq CASCADE');
        $this->addSql('DROP TABLE payment_status');
        $this->addSql('DROP INDEX uniq_c036f84f665648e9');
        $this->addSql('ALTER TABLE invoice_status ADD border_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status ADD text_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status ADD bg_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status RENAME COLUMN color TO display_name');
        $this->addSql('DROP INDEX idx_6d28840d6bf700bd');
        $this->addSql('ALTER TABLE payment DROP status_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE payment_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment_status (id INT NOT NULL, name VARCHAR(255) NOT NULL, border_color VARCHAR(255) NOT NULL, bg_color VARCHAR(255) NOT NULL, text_color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE invoice_status ADD color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_status DROP display_name');
        $this->addSql('ALTER TABLE invoice_status DROP border_color');
        $this->addSql('ALTER TABLE invoice_status DROP text_color');
        $this->addSql('ALTER TABLE invoice_status DROP bg_color');
        $this->addSql('CREATE UNIQUE INDEX uniq_c036f84f665648e9 ON invoice_status (color)');
        $this->addSql('ALTER TABLE payment ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840d6bf700bd FOREIGN KEY (status_id) REFERENCES payment_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6d28840d6bf700bd ON payment (status_id)');
    }
}
