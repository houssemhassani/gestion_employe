<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230817090548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salary_advance DROP FOREIGN KEY FK_7085813F3879DF36');
        $this->addSql('DROP INDEX IDX_7085813F3879DF36 ON salary_advance');
        $this->addSql('ALTER TABLE salary_advance DROP attendance_record_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salary_advance ADD attendance_record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE salary_advance ADD CONSTRAINT FK_7085813F3879DF36 FOREIGN KEY (attendance_record_id) REFERENCES attendance_record (id)');
        $this->addSql('CREATE INDEX IDX_7085813F3879DF36 ON salary_advance (attendance_record_id)');
    }
}
