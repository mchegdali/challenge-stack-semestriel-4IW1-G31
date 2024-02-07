<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207135501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE payment_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment_method (id INT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840d9395c3f3');
        $this->addSql('DROP INDEX idx_6d28840d9395c3f3');
        $this->addSql('ALTER TABLE payment ADD due_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE payment ADD paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE payment DROP customer_id');
        $this->addSql('ALTER TABLE payment DROP expected_date');
        $this->addSql('ALTER TABLE payment DROP payment_date');
        $this->addSql('COMMENT ON COLUMN payment.due_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.paid_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE payment_method_id_seq CASCADE');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('ALTER TABLE payment ADD customer_id UUID NOT NULL');
        $this->addSql('ALTER TABLE payment ADD expected_date DATE NOT NULL');
        $this->addSql('ALTER TABLE payment ADD payment_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE payment DROP due_at');
        $this->addSql('ALTER TABLE payment DROP paid_at');
        $this->addSql('COMMENT ON COLUMN payment.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.expected_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.payment_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840d9395c3f3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6d28840d9395c3f3 ON payment (customer_id)');
    }
}
