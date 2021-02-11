<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210170158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE videojuego ADD plataforma_id INT NOT NULL, ADD categoria_id INT NOT NULL');
        $this->addSql('ALTER TABLE videojuego ADD CONSTRAINT FK_AA5E6DFAEB90E430 FOREIGN KEY (plataforma_id) REFERENCES plataforma (id)');
        $this->addSql('ALTER TABLE videojuego ADD CONSTRAINT FK_AA5E6DFA3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_AA5E6DFAEB90E430 ON videojuego (plataforma_id)');
        $this->addSql('CREATE INDEX IDX_AA5E6DFA3397707A ON videojuego (categoria_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE videojuego DROP FOREIGN KEY FK_AA5E6DFAEB90E430');
        $this->addSql('ALTER TABLE videojuego DROP FOREIGN KEY FK_AA5E6DFA3397707A');
        $this->addSql('DROP INDEX IDX_AA5E6DFAEB90E430 ON videojuego');
        $this->addSql('DROP INDEX IDX_AA5E6DFA3397707A ON videojuego');
        $this->addSql('ALTER TABLE videojuego DROP plataforma_id, DROP categoria_id');
    }
}
