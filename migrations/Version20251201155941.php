<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251201155941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collect (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, jeu_video_id INT NOT NULL, prix_achat DOUBLE PRECISION DEFAULT NULL, date_achat DATE DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(30) NOT NULL, INDEX IDX_A40662F4FB88E14F (utilisateur_id), INDEX IDX_A40662F4695B6720 (jeu_video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editeur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, pays VARCHAR(100) DEFAULT NULL, site_web VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL, description LONGTEXT NOT NULL, actif TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeu_video (id INT AUTO_INCREMENT NOT NULL, editeur_id INT NOT NULL, genre_id INT NOT NULL, titre VARCHAR(255) NOT NULL, developpeur VARCHAR(255) NOT NULL, date_sortie DATE NOT NULL, prix NUMERIC(6, 2) DEFAULT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, INDEX IDX_4E22D9D43375BD21 (editeur_id), INDEX IDX_4E22D9D44296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, person VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, pseudo VARCHAR(30) NOT NULL, mail VARCHAR(255) NOT NULL, date_naissance DATE DEFAULT NULL, image_profil LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collect ADD CONSTRAINT FK_A40662F4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE collect ADD CONSTRAINT FK_A40662F4695B6720 FOREIGN KEY (jeu_video_id) REFERENCES jeu_video (id)');
        $this->addSql('ALTER TABLE jeu_video ADD CONSTRAINT FK_4E22D9D43375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id)');
        $this->addSql('ALTER TABLE jeu_video ADD CONSTRAINT FK_4E22D9D44296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collect DROP FOREIGN KEY FK_A40662F4FB88E14F');
        $this->addSql('ALTER TABLE collect DROP FOREIGN KEY FK_A40662F4695B6720');
        $this->addSql('ALTER TABLE jeu_video DROP FOREIGN KEY FK_4E22D9D43375BD21');
        $this->addSql('ALTER TABLE jeu_video DROP FOREIGN KEY FK_4E22D9D44296D31F');
        $this->addSql('DROP TABLE collect');
        $this->addSql('DROP TABLE editeur');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE jeu_video');
        $this->addSql('DROP TABLE utilisateur');
    }
}
