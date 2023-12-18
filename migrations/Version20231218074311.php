<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218074311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cfg_fact_types ADD content_type_id INT');
        $this->addSql('ALTER TABLE cfg_fact_types ADD CONSTRAINT FK_8BF50C6C1A445520 FOREIGN KEY (content_type_id) REFERENCES content_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8BF50C6C1A445520 ON cfg_fact_types (content_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cfg_fact_types DROP CONSTRAINT FK_8BF50C6C1A445520');
        $this->addSql('DROP INDEX IDX_8BF50C6C1A445520');
        $this->addSql('ALTER TABLE cfg_fact_types DROP content_type_id');
    }
}
