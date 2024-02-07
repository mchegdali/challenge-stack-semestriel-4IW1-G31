<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207143159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD payment_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN invoice.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517444C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_906517444C3A3BB ON invoice (payment_id)');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840d2989f1fd');
        $this->addSql('DROP INDEX idx_6d28840d2989f1fd');
        $this->addSql('ALTER TABLE payment DROP invoice_id');
        $this->addSql('ALTER TABLE payment DROP amount_without_tax');
        $this->addSql('ALTER TABLE payment DROP amount_with_tax');
        $this->addSql('ALTER TABLE payment DROP tax_amount');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment ADD invoice_id UUID NOT NULL');
        $this->addSql('ALTER TABLE payment ADD amount_without_tax DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE payment ADD amount_with_tax DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE payment ADD tax_amount DOUBLE PRECISION NOT NULL');
        $this->addSql('COMMENT ON COLUMN payment.invoice_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840d2989f1fd FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6d28840d2989f1fd ON payment (invoice_id)');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517444C3A3BB');
        $this->addSql('DROP INDEX UNIQ_906517444C3A3BB');
        $this->addSql('ALTER TABLE invoice DROP payment_id');
    }
}
