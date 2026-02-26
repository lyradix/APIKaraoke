<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226132047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE singer (id INT AUTO_INCREMENT NOT NULL, nickname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE singer_song (singer_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_58B4FF1F271FD47C (singer_id), INDEX IDX_58B4FF1FA0BDB2F3 (song_id), PRIMARY KEY (singer_id, song_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE song (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE singer_song ADD CONSTRAINT FK_58B4FF1F271FD47C FOREIGN KEY (singer_id) REFERENCES singer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE singer_song ADD CONSTRAINT FK_58B4FF1FA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE singer_song DROP FOREIGN KEY FK_58B4FF1F271FD47C');
        $this->addSql('ALTER TABLE singer_song DROP FOREIGN KEY FK_58B4FF1FA0BDB2F3');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE singer');
        $this->addSql('DROP TABLE singer_song');
        $this->addSql('DROP TABLE song');
    }
}
