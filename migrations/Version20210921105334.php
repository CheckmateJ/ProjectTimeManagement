<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921105334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_time DROP FOREIGN KEY FK_D38B9CCEA76ED395');
        $this->addSql('DROP INDEX IDX_5605505DA76ED395 ON project_time');
        $this->addSql('ALTER TABLE project_time DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_time ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_time ADD CONSTRAINT FK_D38B9CCEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5605505DA76ED395 ON project_time (user_id)');
    }
}
