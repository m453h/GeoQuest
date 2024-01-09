<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109043326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_quiz_questions ADD quiz_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_quiz_questions ADD CONSTRAINT FK_1B776EDE853CD175 FOREIGN KEY (quiz_id) REFERENCES tbl_user_quizzes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1B776EDE853CD175 ON tbl_quiz_questions (quiz_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tbl_quiz_questions DROP CONSTRAINT FK_1B776EDE853CD175');
        $this->addSql('DROP INDEX IDX_1B776EDE853CD175');
        $this->addSql('ALTER TABLE tbl_quiz_questions DROP quiz_id');
    }
}
