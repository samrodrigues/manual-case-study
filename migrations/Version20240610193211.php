<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610193211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Populates the tables with initial data.';
    }

    public function up(Schema $schema): void
    {
        // Insert data into the product table
        $this->addSql('INSERT INTO product (id, name) VALUES
            (1, "Sildenafil 50mg"),
            (2, "Sildenafil 100mg"),
            (3, "Tadalafil 10mg"),
            (4, "Tadalafil 20mg")');

        // Insert data into the questionnaire table
        $this->addSql('INSERT INTO questionnaire (id, name) VALUES (1, "Case Study Questionnaire")');

        // Insert data into the question table
        $this->addSql('INSERT INTO question (id, questionnaire_id, text) VALUES
            (1, 1, "Do you have difficulty getting or maintaining an erection?"),
            (2, 1, "Have you tried any of the following treatments before?"),
            (3, 1, "Was the Viagra or Sildenafil product you tried before effective?"),
            (4, 1, "Was the Cialis or Tadalafil product you tried before effective?"),
            (5, 1, "Which is your preferred treatment?"),
            (6, 1, "Do you have, or have you ever had, any heart or neurological conditions?"),
            (7, 1, "Do any of the listed medical conditions apply to you?"),
            (8, 1, "Are you taking any of the following drugs?")');

        // Insert data into the option table
        $this->addSql('INSERT INTO option (id, question_id, next_question_id, text) VALUES
            (1, 1, 2, "Yes"),
            (2, 1, 2, "No"),

            (3, 2, 3, "Viagra or Sildenafil"),
            (4, 2, 4, "Cialis or Tadalafil"),
            (5, 2, 5, "Both"),
            (6, 2, 6, "None of the above"),

            (7, 3, 6, "Yes"),
            (8, 3, 6, "No"),

            (9, 4, 6, "Yes"),
            (10, 4, 6, "No"),

            (11, 5, 6, "Viagra or Sildenafil"),
            (12, 5, 6, "Cialis or Tadalafil"),
            (13, 5, 6, "None of the above"),

            (14, 6, 7, "Yes"),
            (15, 6, 7, "No"),

            (16, 7, 8, "Significant liver problems (such as cirrhosis of the liver) or kidney problems"),
            (17, 7, 8, "Currently prescribed GTN, Isosorbide mononitrate, Isosorbide dinitrate, Nicorandil (nitrates) or Rectogesic ointment"),
            (18, 7, 8, "Abnormal blood pressure (lower than 90/50 mmHg or higher than 160/90 mmHg)"),
            (19, 7, 8, "Condition affecting your penis (such as Peyronie\'s Disease, previous injuries or an inability to retract your foreskin)"),
            (20, 7, 8, "I don\'t have any of these conditions"),

            (21, 8, NULL, "Alpha-blocker medication such as Alfuzosin, Doxazosin, Tamsulosin, Prazosin, Terazosin or over-the-counter Flomax"),
            (22, 8, NULL, "Riociguat or other guanylate cyclase stimulators (for lung problems)"),
            (23, 8, NULL, "Saquinavir, Ritonavir or Indinavir (for HIV)"),
            (24, 8, NULL, "Cimetidine (for heartburn)"),
            (25, 8, NULL, "I don\'t take any of these drugs")');

        // Insert data into the option_product table
        $this->addSql('INSERT INTO option_product (option_id, product_id, is_available) VALUES
            (2, 1, 0), -- No to question 1 excludes all products
            (2, 2, 0),
            (2, 3, 0),
            (2, 4, 0),

            (6, 1, 1), -- None of the above to question 2 includes Sildenafil 50mg or Tadalafil 10mg
            (6, 3, 1),

            (7, 1, 1), -- Yes to question includes Sildenafil 50mg and exlcludes 100mg
            (7, 2, 0),
            (7, 3, 0), -- Yes to question 3 excludes Tadalafil
            (7, 4, 0), -- Yes to question 3 excludes Tadalafil
            (8, 1, 0), -- No to question 3 excludes Sildenafil and Tadalafil 10mg, includes Tadalafil 20mg
            (8, 2, 0),
            (8, 3, 0),
            (8, 4, 1),

            (9, 1, 0), -- Yes to question 4 leaves only Tadalafil 10mg
            (9, 2, 0),
            (9, 3, 1),
            (9, 4, 0),
            (10, 1, 0), -- No to question 4 leaves only Sildenafil 100mg
            (10, 2, 1),
            (10, 3, 0),
            (10, 4, 0),

            (11, 2, 1), -- Viagra or Sildenafil to question 5 includes Sildenafil 100mg, excludes Sildenafil 50mg and Tadalafil
            (11, 1, 0),
            (11, 3, 0),
            (11, 4, 0),
            (12, 4, 1), -- Cialis or Tadalafil to question 5 includes Tadalafil 20mg, excludes Sildenafil
            (12, 1, 0),
            (12, 2, 0),
            (12, 3, 0),
            (13, 2, 1), -- None of the above to question 5 includes both Sildenafil 100mg and Tadalafil 20mg, excludes lower dosages
            (13, 4, 1),
            (13, 1, 0),
            (13, 3, 0),

            (14, 1, 0), -- Yes to question 6 excludes all products
            (14, 2, 0),
            (14, 3, 0),
            (14, 4, 0),

            (16, 1, 0), -- Specific medical conditions to question 7 exclude all products
            (16, 2, 0),
            (16, 3, 0),
            (16, 4, 0),
            (17, 1, 0),
            (17, 2, 0),
            (17, 3, 0),
            (17, 4, 0),
            (18, 1, 0),
            (18, 2, 0),
            (18, 3, 0),
            (18, 4, 0),
            (19, 1, 0),
            (19, 2, 0),
            (19, 3, 0),
            (19, 4, 0),

            (21, 1, 0), -- Specific drugs to question 8 exclude all products
            (21, 2, 0),
            (21, 3, 0),
            (21, 4, 0),
            (22, 1, 0),
            (22, 2, 0),
            (22, 3, 0),
            (22, 4, 0),
            (23, 1, 0),
            (23, 2, 0),
            (23, 3, 0),
            (23, 4, 0),
            (24, 1, 0),
            (24, 2, 0),
            (24, 3, 0),
            (24, 4, 0)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM option_product');
        $this->addSql('DELETE FROM option');
        $this->addSql('DELETE FROM question');
        $this->addSql('DELETE FROM product');
        $this->addSql('DELETE FROM questionnaire');
    }
}

