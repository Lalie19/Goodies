<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220318143033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achats (id INT AUTO_INCREMENT NOT NULL, goodies_id INT NOT NULL, commande_id INT NOT NULL, nombres INT NOT NULL, quantity INT NOT NULL, INDEX IDX_9920924EBBFA5614 (goodies_id), INDEX IDX_9920924E82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, post_code VARCHAR(255) NOT NULL, town VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, stripe_sucess_key VARCHAR(255) DEFAULT NULL, paid TINYINT(1) NOT NULL, pi_stripe VARCHAR(255) DEFAULT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achats ADD CONSTRAINT FK_9920924EBBFA5614 FOREIGN KEY (goodies_id) REFERENCES goodies (id)');
        $this->addSql('ALTER TABLE achats ADD CONSTRAINT FK_9920924E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achats DROP FOREIGN KEY FK_9920924E82EA2E54');
        $this->addSql('DROP TABLE achats');
        $this->addSql('DROP TABLE commande');
    }
}
