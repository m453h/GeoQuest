<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109035536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tbl_quiz_questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tbl_user_quizzes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tbl_quiz_questions (id INT NOT NULL, country_fact_id INT DEFAULT NULL, user_answer_id INT DEFAULT NULL, time_commpleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_correct BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B776EDEDC210447 ON tbl_quiz_questions (country_fact_id)');
        $this->addSql('CREATE INDEX IDX_1B776EDEAAD3C5E3 ON tbl_quiz_questions (user_answer_id)');
        $this->addSql('CREATE TABLE tbl_user_quizzes (id INT NOT NULL, quiz_type_id INT DEFAULT NULL, quiz_owner_id INT DEFAULT NULL, identifier VARCHAR(255) DEFAULT NULL, time_started TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, time_completed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, total_score DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3282FB29D7162133 ON tbl_user_quizzes (quiz_type_id)');
        $this->addSql('CREATE INDEX IDX_3282FB29A8F898BC ON tbl_user_quizzes (quiz_owner_id)');
        $this->addSql('ALTER TABLE tbl_quiz_questions ADD CONSTRAINT FK_1B776EDEDC210447 FOREIGN KEY (country_fact_id) REFERENCES tbl_country_facts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tbl_quiz_questions ADD CONSTRAINT FK_1B776EDEAAD3C5E3 FOREIGN KEY (user_answer_id) REFERENCES cfg_countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tbl_user_quizzes ADD CONSTRAINT FK_3282FB29D7162133 FOREIGN KEY (quiz_type_id) REFERENCES cfg_fact_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tbl_user_quizzes ADD CONSTRAINT FK_3282FB29A8F898BC FOREIGN KEY (quiz_owner_id) REFERENCES tbl_user_accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cfg_fact_types ALTER summary SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tbl_quiz_questions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tbl_user_quizzes_id_seq CASCADE');
        $this->addSql('ALTER TABLE tbl_quiz_questions DROP CONSTRAINT FK_1B776EDEDC210447');
        $this->addSql('ALTER TABLE tbl_quiz_questions DROP CONSTRAINT FK_1B776EDEAAD3C5E3');
        $this->addSql('ALTER TABLE tbl_user_quizzes DROP CONSTRAINT FK_3282FB29D7162133');
        $this->addSql('ALTER TABLE tbl_user_quizzes DROP CONSTRAINT FK_3282FB29A8F898BC');
        $this->addSql('DROP TABLE tbl_quiz_questions');
        $this->addSql('DROP TABLE tbl_user_quizzes');
        $this->addSql('ALTER TABLE cfg_fact_types ALTER summary DROP NOT NULL');
    }
}
