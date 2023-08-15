<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801110323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendance_record (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, month VARCHAR(150) NOT NULL, year INT NOT NULL, INDEX IDX_311E8495A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendance_record ADD CONSTRAINT FK_311E8495A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE attendance ADD attendance_record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D913879DF36 FOREIGN KEY (attendance_record_id) REFERENCES attendance_record (id)');
        $this->addSql('CREATE INDEX IDX_6DE30D913879DF36 ON attendance (attendance_record_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D913879DF36');
        $this->addSql('ALTER TABLE attendance_record DROP FOREIGN KEY FK_311E8495A76ED395');
        $this->addSql('DROP TABLE attendance_record');
        $this->addSql('DROP INDEX IDX_6DE30D913879DF36 ON attendance');
        $this->addSql('ALTER TABLE attendance DROP attendance_record_id');
    }
}
