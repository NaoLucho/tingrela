<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725091356 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande ADD customer_id INT DEFAULT NULL, DROP firstname, DROP lastname, DROP phone, DROP email, DROP address, DROP postalcode, DROP city');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D9395C3F3 ON commande (customer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D9395C3F3');
        $this->addSql('DROP INDEX IDX_6EEAA67D9395C3F3 ON commande');
        $this->addSql('ALTER TABLE commande ADD firstname VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD lastname VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD phone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD postalcode VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP customer_id');
    }
}
