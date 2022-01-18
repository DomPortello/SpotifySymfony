<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118092855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, artist_id INT NOT NULL, title VARCHAR(255) NOT NULL, small_cover VARCHAR(255) DEFAULT NULL, medium_cover VARCHAR(255) DEFAULT NULL, big_cover VARCHAR(255) DEFAULT NULL, xl_cover VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, nb_tracks INT NOT NULL, duration INT DEFAULT NULL, release_at DATETIME DEFAULT NULL, available TINYINT(1) NOT NULL, lyrics VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_39986E43B7970CF8 (artist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE album_genre (album_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_F5E879DE1137ABCF (album_id), INDEX IDX_F5E879DE4296D31F (genre_id), PRIMARY KEY(album_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, small_picture VARCHAR(255) DEFAULT NULL, medium_picture VARCHAR(255) DEFAULT NULL, big_picture VARCHAR(255) DEFAULT NULL, xl_picture VARCHAR(255) DEFAULT NULL, nb_album INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, small_picture VARCHAR(255) DEFAULT NULL, medium_picture VARCHAR(255) DEFAULT NULL, big_picture VARCHAR(255) DEFAULT NULL, xl_picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, ended_at DATETIME NOT NULL, status VARCHAR(10) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_line (id INT AUTO_INCREMENT NOT NULL, order_entity_id INT NOT NULL, track_id INT DEFAULT NULL, album_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_9CE58EE13DA206A5 (order_entity_id), INDEX IDX_9CE58EE15ED23C43 (track_id), INDEX IDX_9CE58EE11137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE track (id INT AUTO_INCREMENT NOT NULL, album_id INT NOT NULL, artist_id INT NOT NULL, title VARCHAR(255) NOT NULL, duration INT DEFAULT NULL, position INT DEFAULT NULL, rank INT DEFAULT NULL, release_at DATETIME DEFAULT NULL, lyrics VARCHAR(255) DEFAULT NULL, preview VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D6E3F8A61137ABCF (album_id), INDEX IDX_D6E3F8A6B7970CF8 (artist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nick_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(20) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE album_genre ADD CONSTRAINT FK_F5E879DE1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_genre ADD CONSTRAINT FK_F5E879DE4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE13DA206A5 FOREIGN KEY (order_entity_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE15ED23C43 FOREIGN KEY (track_id) REFERENCES track (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE11137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A61137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_genre DROP FOREIGN KEY FK_F5E879DE1137ABCF');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE11137ABCF');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A61137ABCF');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43B7970CF8');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A6B7970CF8');
        $this->addSql('ALTER TABLE album_genre DROP FOREIGN KEY FK_F5E879DE4296D31F');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE13DA206A5');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE15ED23C43');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE album_genre');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_line');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE user');
    }
}
