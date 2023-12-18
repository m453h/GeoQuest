<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218081912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_country_facts ADD content_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE tbl_country_facts ADD CONSTRAINT FK_25767C7A1A445520 FOREIGN KEY (content_type_id) REFERENCES cfg_content_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_25767C7A1A445520 ON tbl_country_facts (content_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tbl_country_facts DROP CONSTRAINT FK_25767C7A1A445520');
        $this->addSql('DROP INDEX IDX_25767C7A1A445520');
        $this->addSql('ALTER TABLE tbl_country_facts DROP content_type_id');
    }
}
