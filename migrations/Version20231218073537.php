<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218073537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE country_fact_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE tbl_country_facts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tbl_country_facts (id INT NOT NULL, fact_type_id INT NOT NULL, country_id INT NOT NULL, content VARCHAR(255) NOT NULL, content_type VARCHAR(255) NOT NULL, is_user_created BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25767C7A6E59D952 ON tbl_country_facts (fact_type_id)');
        $this->addSql('CREATE INDEX IDX_25767C7AF92F3E70 ON tbl_country_facts (country_id)');
        $this->addSql('ALTER TABLE tbl_country_facts ADD CONSTRAINT FK_25767C7A6E59D952 FOREIGN KEY (fact_type_id) REFERENCES cfg_fact_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tbl_country_facts ADD CONSTRAINT FK_25767C7AF92F3E70 FOREIGN KEY (country_id) REFERENCES cfg_countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE country_fact DROP CONSTRAINT fk_e897d0716e59d952');
        $this->addSql('ALTER TABLE country_fact DROP CONSTRAINT fk_e897d071f92f3e70');
        $this->addSql('DROP TABLE country_fact');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tbl_country_facts_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE country_fact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE country_fact (id INT NOT NULL, fact_type_id INT NOT NULL, country_id INT NOT NULL, content VARCHAR(255) NOT NULL, content_type VARCHAR(255) NOT NULL, is_user_created BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e897d071f92f3e70 ON country_fact (country_id)');
        $this->addSql('CREATE INDEX idx_e897d0716e59d952 ON country_fact (fact_type_id)');
        $this->addSql('ALTER TABLE country_fact ADD CONSTRAINT fk_e897d0716e59d952 FOREIGN KEY (fact_type_id) REFERENCES cfg_fact_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE country_fact ADD CONSTRAINT fk_e897d071f92f3e70 FOREIGN KEY (country_id) REFERENCES cfg_countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tbl_country_facts DROP CONSTRAINT FK_25767C7A6E59D952');
        $this->addSql('ALTER TABLE tbl_country_facts DROP CONSTRAINT FK_25767C7AF92F3E70');
        $this->addSql('DROP TABLE tbl_country_facts');
    }
}
