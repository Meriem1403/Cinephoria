<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427084307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cinema (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, city VARCHAR(100) NOT NULL, adress LONGTEXT DEFAULT NULL, postal_code VARCHAR(20) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cinema_employee (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cinema_id INT NOT NULL, job_title VARCHAR(100) DEFAULT NULL, assigned_since DATE DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_D2CC01FCA76ED395 (user_id), INDEX IDX_D2CC01FCB4CB84B6 (cinema_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE incident (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, showtime_id INT DEFAULT NULL, relation_id INT DEFAULT NULL, seat_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, resolved_at DATETIME DEFAULT NULL, INDEX IDX_3D03A11AA76ED395 (user_id), INDEX IDX_3D03A11A28BE1523 (showtime_id), INDEX IDX_3D03A11A3256915B (relation_id), INDEX IDX_3D03A11AC1DAFE35 (seat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, duration INT NOT NULL, release_date DATE NOT NULL, language VARCHAR(5) NOT NULL, age_rating VARCHAR(5) DEFAULT NULL, genre VARCHAR(100) NOT NULL, poster_url LONGTEXT DEFAULT NULL, is_favorite TINYINT(1) NOT NULL, rating DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, showtime_id INT NOT NULL, reservation_date DATETIME NOT NULL, status VARCHAR(50) NOT NULL, total_price DOUBLE PRECISION NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C8495528BE1523 (showtime_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation_seats (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, seat_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, is_pmr TINYINT(1) DEFAULT NULL, is_valid TINYINT(1) NOT NULL, INDEX IDX_FC9D87F7B83297E7 (reservation_id), INDEX IDX_FC9D87F7C1DAFE35 (seat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, movie_id INT NOT NULL, rating DOUBLE PRECISION NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, is_approved TINYINT(1) NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C68F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_57698A6A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, cinema_id INT NOT NULL, name VARCHAR(100) NOT NULL, capacity INT NOT NULL, projection_equipment VARCHAR(100) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_729F519BB4CB84B6 (cinema_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE seat (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, row_label VARCHAR(5) NOT NULL, seat_number INT NOT NULL, is_pmr TINYINT(1) DEFAULT NULL, is_reserved TINYINT(1) DEFAULT NULL, is_broken TINYINT(1) DEFAULT NULL, INDEX IDX_3D5C366654177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE showtime (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, room_id INT NOT NULL, incident_id INT DEFAULT NULL, date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, language VARCHAR(5) NOT NULL, projection_type VARCHAR(20) DEFAULT NULL, status VARCHAR(50) NOT NULL, available_seats INT NOT NULL, pmr_seats INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, special_price TINYINT(1) NOT NULL, label VARCHAR(50) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_3248D918F93B6FC (movie_id), INDEX IDX_3248D9154177093 (room_id), INDEX IDX_3248D9159E53FB9 (incident_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, firt_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password LONGTEXT NOT NULL, birth_date DATE DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, adress LONGTEXT NOT NULL, postal_code VARCHAR(20) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, last_login DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cinema_employee ADD CONSTRAINT FK_D2CC01FCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cinema_employee ADD CONSTRAINT FK_D2CC01FCB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A28BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A3256915B FOREIGN KEY (relation_id) REFERENCES room (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11AC1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495528BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_seats ADD CONSTRAINT FK_FC9D87F7B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_seats ADD CONSTRAINT FK_FC9D87F7C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE room ADD CONSTRAINT FK_729F519BB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seat ADD CONSTRAINT FK_3D5C366654177093 FOREIGN KEY (room_id) REFERENCES room (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime ADD CONSTRAINT FK_3248D918F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime ADD CONSTRAINT FK_3248D9154177093 FOREIGN KEY (room_id) REFERENCES room (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime ADD CONSTRAINT FK_3248D9159E53FB9 FOREIGN KEY (incident_id) REFERENCES incident (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cinema_employee DROP FOREIGN KEY FK_D2CC01FCA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cinema_employee DROP FOREIGN KEY FK_D2CC01FCB4CB84B6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A28BE1523
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A3256915B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11AC1DAFE35
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495528BE1523
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_seats DROP FOREIGN KEY FK_FC9D87F7B83297E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_seats DROP FOREIGN KEY FK_FC9D87F7C1DAFE35
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP FOREIGN KEY FK_794381C68F93B6FC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE room DROP FOREIGN KEY FK_729F519BB4CB84B6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seat DROP FOREIGN KEY FK_3D5C366654177093
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime DROP FOREIGN KEY FK_3248D918F93B6FC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime DROP FOREIGN KEY FK_3248D9154177093
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE showtime DROP FOREIGN KEY FK_3248D9159E53FB9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cinema
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cinema_employee
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE incident
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE movie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation_seats
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE review
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE room
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE seat
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE showtime
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
