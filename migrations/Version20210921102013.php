<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921102013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_time ADD CONSTRAINT FK_5605505DE70990B3 FOREIGN KEY (project_name_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_5605505DE70990B3 ON project_time (project_name_id)');
        $this->addSql('ALTER TABLE project_time RENAME INDEX idx_d38b9ccea76ed395 TO IDX_5605505DA76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_time DROP FOREIGN KEY FK_5605505DE70990B3');
        $this->addSql('DROP INDEX IDX_5605505DE70990B3 ON project_time');
        $this->addSql('ALTER TABLE project_time RENAME INDEX idx_5605505da76ed395 TO IDX_D38B9CCEA76ED395');
    }
}
