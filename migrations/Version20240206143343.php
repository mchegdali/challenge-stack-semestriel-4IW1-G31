<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206143343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE invoice_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE invoice_status (id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C036F84F665648E9 ON invoice_status (color)');
        $this->addSql('ALTER TABLE invoice ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD invoice_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE invoice ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('COMMENT ON COLUMN invoice.created_at IS NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517446BF700BD FOREIGN KEY (status_id) REFERENCES invoice_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_906517446BF700BD ON invoice (status_id)');
        $this->addSql('ALTER TABLE invoice_item ADD tax_id UUID NOT NULL');
        $this->addSql('ALTER TABLE invoice_item ADD price_excluding_tax DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice_item ADD price_including_tax DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE invoice_item ADD tax_amount DOUBLE PRECISION NOT NULL');
        $this->addSql('COMMENT ON COLUMN invoice_item.tax_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE invoice_item ADD CONSTRAINT FK_1DDE477BB2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1DDE477BB2A824D8 ON invoice_item (tax_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517446BF700BD');
        $this->addSql('DROP SEQUENCE invoice_status_id_seq CASCADE');
        $this->addSql('DROP TABLE invoice_status');
        $this->addSql('ALTER TABLE invoice_item DROP CONSTRAINT FK_1DDE477BB2A824D8');
        $this->addSql('DROP INDEX IDX_1DDE477BB2A824D8');
        $this->addSql('ALTER TABLE invoice_item DROP tax_id');
        $this->addSql('ALTER TABLE invoice_item DROP price_excluding_tax');
        $this->addSql('ALTER TABLE invoice_item DROP price_including_tax');
        $this->addSql('ALTER TABLE invoice_item DROP tax_amount');
        $this->addSql('DROP INDEX IDX_906517446BF700BD');
        $this->addSql('ALTER TABLE invoice DROP status_id');
        $this->addSql('ALTER TABLE invoice DROP invoice_number');
        $this->addSql('ALTER TABLE invoice DROP updated_at');
        $this->addSql('ALTER TABLE invoice ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE invoice ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN invoice.created_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
