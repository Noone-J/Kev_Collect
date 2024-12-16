<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241215151510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, le_user_id INT DEFAULT NULL, le_statut_id INT DEFAULT NULL, date DATETIME NOT NULL, prix_commande INT NOT NULL, INDEX IDX_6EEAA67D88A1A5E2 (le_user_id), INDEX IDX_6EEAA67D2F382CF3 (le_statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_commande (id INT AUTO_INCREMENT NOT NULL, la_commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix_quantite INT NOT NULL, INDEX IDX_98344FA63743EDD (la_commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_statut (id INT AUTO_INCREMENT NOT NULL, date_changement DATETIME NOT NULL, modifie_par VARCHAR(255) NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, libeller VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_unitaire INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE raillon (id INT AUTO_INCREMENT NOT NULL, type_raillon VARCHAR(255) NOT NULL, emplacement VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour_client (id INT AUTO_INCREMENT NOT NULL, libeller VARCHAR(255) DEFAULT NULL, notation INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, le_historique_statut_id INT DEFAULT NULL, libeller VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E564F0BF25A4AD6F (le_historique_statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, le_produit_id INT DEFAULT NULL, quantite_stock INT NOT NULL, INDEX IDX_4B3656602C340150 (le_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num INT DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D88A1A5E2 FOREIGN KEY (le_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2F382CF3 FOREIGN KEY (le_statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA63743EDD FOREIGN KEY (la_commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BF25A4AD6F FOREIGN KEY (le_historique_statut_id) REFERENCES historique_statut (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656602C340150 FOREIGN KEY (le_produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D88A1A5E2');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D2F382CF3');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA63743EDD');
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BF25A4AD6F');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656602C340150');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE detail_commande');
        $this->addSql('DROP TABLE historique_statut');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE raillon');
        $this->addSql('DROP TABLE retour_client');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
