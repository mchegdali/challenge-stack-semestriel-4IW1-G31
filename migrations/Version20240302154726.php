<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302154726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tax DROP CONSTRAINT fk_8e81ba76979b1ad6');
        $this->addSql('DROP INDEX idx_8e81ba76979b1ad6');
        $this->addSql('ALTER TABLE tax DROP company_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tax ADD company_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN tax.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE tax ADD CONSTRAINT fk_8e81ba76979b1ad6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8e81ba76979b1ad6 ON tax (company_id)');
    }
}
