<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250502151814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A3256915B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3D03A11A3256915B ON incident
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD room VARCHAR(255) DEFAULT NULL, DROP relation_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD relation_id INT DEFAULT NULL, DROP room
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A3256915B FOREIGN KEY (relation_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3D03A11A3256915B ON incident (relation_id)
        SQL);
    }
}
