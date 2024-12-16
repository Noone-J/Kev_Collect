<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216014829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_commande ADD le_produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA62C340150 FOREIGN KEY (le_produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_98344FA62C340150 ON detail_commande (le_produit_id)');
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BF25A4AD6F');
        $this->addSql('DROP INDEX UNIQ_E564F0BF25A4AD6F ON statut');
        $this->addSql('ALTER TABLE statut DROP le_historique_statut_id, CHANGE libeller libelle VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA62C340150');
        $this->addSql('DROP INDEX IDX_98344FA62C340150 ON detail_commande');
        $this->addSql('ALTER TABLE detail_commande DROP le_produit_id');
        $this->addSql('ALTER TABLE statut ADD le_historique_statut_id INT DEFAULT NULL, CHANGE libelle libeller VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BF25A4AD6F FOREIGN KEY (le_historique_statut_id) REFERENCES historique_statut (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E564F0BF25A4AD6F ON statut (le_historique_statut_id)');
    }
}
