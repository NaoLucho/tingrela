<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180720160013 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_basket ADD commande_id INT DEFAULT NULL, ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande_basket ADD CONSTRAINT FK_2012ABAE82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_basket ADD CONSTRAINT FK_2012ABAE4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_2012ABAE82EA2E54 ON commande_basket (commande_id)');
        $this->addSql('CREATE INDEX IDX_2012ABAE4584665A ON commande_basket (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_basket DROP FOREIGN KEY FK_2012ABAE82EA2E54');
        $this->addSql('ALTER TABLE commande_basket DROP FOREIGN KEY FK_2012ABAE4584665A');
        $this->addSql('DROP INDEX IDX_2012ABAE82EA2E54 ON commande_basket');
        $this->addSql('DROP INDEX IDX_2012ABAE4584665A ON commande_basket');
        $this->addSql('ALTER TABLE commande_basket DROP commande_id, DROP product_id');
    }
}
