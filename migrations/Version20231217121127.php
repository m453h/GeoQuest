<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217121127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE country_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE region_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sub_region_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fact_type_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE cfg_countries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cfg_fact_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cfg_regions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cfg_sub_regions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cfg_countries (id INT NOT NULL, sub_region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F1BD98878A2B47EB ON cfg_countries (sub_region_id)');
        $this->addSql('CREATE TABLE cfg_fact_types (id INT NOT NULL, description VARCHAR(255) NOT NULL, api_field VARCHAR(255) NOT NULL, question_prompt TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cfg_regions (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cfg_sub_regions (id INT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7BD8657298260155 ON cfg_sub_regions (region_id)');
        $this->addSql('ALTER TABLE cfg_countries ADD CONSTRAINT FK_F1BD98878A2B47EB FOREIGN KEY (sub_region_id) REFERENCES cfg_sub_regions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cfg_sub_regions ADD CONSTRAINT FK_7BD8657298260155 FOREIGN KEY (region_id) REFERENCES cfg_regions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE country DROP CONSTRAINT fk_5373c9668a2b47eb');
        $this->addSql('ALTER TABLE sub_region DROP CONSTRAINT fk_bd33be3a98260155');
        $this->addSql('DROP TABLE fact_type');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE sub_region');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cfg_countries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cfg_fact_types_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cfg_regions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cfg_sub_regions_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE region_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sub_region_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fact_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE fact_type (id INT NOT NULL, description VARCHAR(255) NOT NULL, api_field VARCHAR(255) NOT NULL, question_prompt TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE region (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, sub_region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5373c9668a2b47eb ON country (sub_region_id)');
        $this->addSql('CREATE TABLE sub_region (id INT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_bd33be3a98260155 ON sub_region (region_id)');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT fk_5373c9668a2b47eb FOREIGN KEY (sub_region_id) REFERENCES sub_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sub_region ADD CONSTRAINT fk_bd33be3a98260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cfg_countries DROP CONSTRAINT FK_F1BD98878A2B47EB');
        $this->addSql('ALTER TABLE cfg_sub_regions DROP CONSTRAINT FK_7BD8657298260155');
        $this->addSql('DROP TABLE cfg_countries');
        $this->addSql('DROP TABLE cfg_fact_types');
        $this->addSql('DROP TABLE cfg_regions');
        $this->addSql('DROP TABLE cfg_sub_regions');
    }
}
