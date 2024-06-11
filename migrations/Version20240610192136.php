<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610192136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE option (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_id INTEGER NOT NULL, next_question_id INTEGER DEFAULT NULL, text VARCHAR(255) NOT NULL, CONSTRAINT FK_5A8600B01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5A8600B01CF5F25E FOREIGN KEY (next_question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5A8600B01E27F6BF ON option (question_id)');
        $this->addSql('CREATE INDEX IDX_5A8600B01CF5F25E ON option (next_question_id)');
        $this->addSql('CREATE TABLE option_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, option_id INTEGER NOT NULL, product_id INTEGER NOT NULL, is_available BOOLEAN NOT NULL, CONSTRAINT FK_CBBE13D8A7C41D6F FOREIGN KEY (option_id) REFERENCES option (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CBBE13D84584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CBBE13D8A7C41D6F ON option_product (option_id)');
        $this->addSql('CREATE INDEX IDX_CBBE13D84584665A ON option_product (product_id)');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, questionnaire_id INTEGER NOT NULL, text VARCHAR(255) NOT NULL, CONSTRAINT FK_B6F7494ECE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B6F7494ECE07E8FF ON question (questionnaire_id)');
        $this->addSql('CREATE TABLE question_response (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_id INTEGER NOT NULL, option_id INTEGER NOT NULL, questionnaire_submission_id INTEGER NOT NULL, CONSTRAINT FK_5D73BBF71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5D73BBF7A7C41D6F FOREIGN KEY (option_id) REFERENCES option (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5D73BBF7127FDF7F FOREIGN KEY (questionnaire_submission_id) REFERENCES questionnaire_submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5D73BBF71E27F6BF ON question_response (question_id)');
        $this->addSql('CREATE INDEX IDX_5D73BBF7A7C41D6F ON question_response (option_id)');
        $this->addSql('CREATE INDEX IDX_5D73BBF7127FDF7F ON question_response (questionnaire_submission_id)');
        $this->addSql('CREATE TABLE questionnaire (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE questionnaire_submission (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, respondent_id INTEGER NOT NULL, questionnaire_id INTEGER NOT NULL, CONSTRAINT FK_2CF49A6ACE80CD19 FOREIGN KEY (respondent_id) REFERENCES respondent (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2CF49A6ACE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2CF49A6ACE80CD19 ON questionnaire_submission (respondent_id)');
        $this->addSql('CREATE INDEX IDX_2CF49A6ACE07E8FF ON questionnaire_submission (questionnaire_id)');
        $this->addSql('CREATE TABLE respondent (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE option');
        $this->addSql('DROP TABLE option_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_response');
        $this->addSql('DROP TABLE questionnaire');
        $this->addSql('DROP TABLE questionnaire_submission');
        $this->addSql('DROP TABLE respondent');
    }
}
