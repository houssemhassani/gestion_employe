<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801111918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pay_roll_user DROP FOREIGN KEY FK_392D4A69A76ED395');
        $this->addSql('ALTER TABLE pay_roll_user DROP FOREIGN KEY FK_392D4A694DE1AAED');
        $this->addSql('DROP TABLE pay_roll_user');
        $this->addSql('ALTER TABLE pay_roll ADD employee_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD number_of_days_present DOUBLE PRECISION NOT NULL, ADD prime_evaluation_total DOUBLE PRECISION NOT NULL, ADD total_salary DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE pay_roll ADD CONSTRAINT FK_7D2CE0698C03F15C FOREIGN KEY (employee_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7D2CE0698C03F15C ON pay_roll (employee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pay_roll_user (pay_roll_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_392D4A694DE1AAED (pay_roll_id), INDEX IDX_392D4A69A76ED395 (user_id), PRIMARY KEY(pay_roll_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pay_roll_user ADD CONSTRAINT FK_392D4A69A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pay_roll_user ADD CONSTRAINT FK_392D4A694DE1AAED FOREIGN KEY (pay_roll_id) REFERENCES pay_roll (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pay_roll DROP FOREIGN KEY FK_7D2CE0698C03F15C');
        $this->addSql('DROP INDEX IDX_7D2CE0698C03F15C ON pay_roll');
        $this->addSql('ALTER TABLE pay_roll DROP employee_id, DROP created_at, DROP number_of_days_present, DROP prime_evaluation_total, DROP total_salary');
    }
}
