<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180713094108 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postalcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, validated TINYINT(1) DEFAULT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_basket (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, image_url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecommerce_config (id INT AUTO_INCREMENT NOT NULL, tva DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, description LONGTEXT NOT NULL, unit VARCHAR(100) NOT NULL, step INT NOT NULL, importance INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_basket');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE creation');
        $this->addSql('DROP TABLE ecommerce_config');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE type');
    }
}
