<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250503105554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A28BE1523
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident CHANGE showtime_id showtime_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A28BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime DROP FOREIGN KEY FK_3248D9159E53FB9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3248D9159E53FB9 ON showtime
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime DROP incident_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A28BE1523
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident CHANGE showtime_id showtime_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A28BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime ADD incident_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime ADD CONSTRAINT FK_3248D9159E53FB9 FOREIGN KEY (incident_id) REFERENCES incident (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3248D9159E53FB9 ON showtime (incident_id)
        SQL);
    }
}
