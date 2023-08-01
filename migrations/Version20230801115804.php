<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801115804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leave_request ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD sorted_date DATE NOT NULL, ADD returned_date DATE NOT NULL, ADD number_of_day_leaved INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leave_request DROP created_at, DROP sorted_date, DROP returned_date, DROP number_of_day_leaved');
    }
}
