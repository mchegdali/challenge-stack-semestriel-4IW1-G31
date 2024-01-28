<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240128221737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE payment_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment (id UUID NOT NULL, customer_id UUID NOT NULL, status_id INT NOT NULL, tax_id UUID NOT NULL, invoice_id UUID NOT NULL, amount_without_tax DOUBLE PRECISION NOT NULL, amount_with_tax DOUBLE PRECISION NOT NULL, tax_amount DOUBLE PRECISION NOT NULL, expected_date DATE NOT NULL, payment_date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D28840D9395C3F3 ON payment (customer_id)');
        $this->addSql('CREATE INDEX IDX_6D28840D6BF700BD ON payment (status_id)');
        $this->addSql('CREATE INDEX IDX_6D28840DB2A824D8 ON payment (tax_id)');
        $this->addSql('CREATE INDEX IDX_6D28840D2989F1FD ON payment (invoice_id)');
        $this->addSql('COMMENT ON COLUMN payment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.tax_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.expected_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.payment_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE payment_status (id INT NOT NULL, name VARCHAR(255) NOT NULL, border_color VARCHAR(255) NOT NULL, bg_color VARCHAR(255) NOT NULL, text_color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D6BF700BD FOREIGN KEY (status_id) REFERENCES payment_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DB2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE invoice ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('COMMENT ON COLUMN invoice.created_at IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE payment_status_id_seq CASCADE');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D9395C3F3');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D6BF700BD');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840DB2A824D8');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D2989F1FD');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_status');
        $this->addSql('ALTER TABLE invoice DROP updated_at');
        $this->addSql('ALTER TABLE invoice ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE invoice ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN invoice.created_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
