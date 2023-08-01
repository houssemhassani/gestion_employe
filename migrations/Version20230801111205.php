<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801111205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE leave_request (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, INDEX IDX_7DC8F7781B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pay_roll (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pay_roll_user (pay_roll_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_392D4A694DE1AAED (pay_roll_id), INDEX IDX_392D4A69A76ED395 (user_id), PRIMARY KEY(pay_roll_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leave_request ADD CONSTRAINT FK_7DC8F7781B65292 FOREIGN KEY (employe_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE pay_roll_user ADD CONSTRAINT FK_392D4A694DE1AAED FOREIGN KEY (pay_roll_id) REFERENCES pay_roll (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pay_roll_user ADD CONSTRAINT FK_392D4A69A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD salary DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leave_request DROP FOREIGN KEY FK_7DC8F7781B65292');
        $this->addSql('ALTER TABLE pay_roll_user DROP FOREIGN KEY FK_392D4A694DE1AAED');
        $this->addSql('ALTER TABLE pay_roll_user DROP FOREIGN KEY FK_392D4A69A76ED395');
        $this->addSql('DROP TABLE leave_request');
        $this->addSql('DROP TABLE pay_roll');
        $this->addSql('DROP TABLE pay_roll_user');
        $this->addSql('ALTER TABLE `user` DROP salary');
    }
}
