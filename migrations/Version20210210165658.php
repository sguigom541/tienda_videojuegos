<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210165658 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalle_pedido (id INT AUTO_INCREMENT NOT NULL, pedido_id INT NOT NULL, videojuego_id INT NOT NULL, cantidad_compra INT NOT NULL, total DOUBLE PRECISION NOT NULL, precio_videojuego DOUBLE PRECISION NOT NULL, INDEX IDX_A834F5694854653A (pedido_id), INDEX IDX_A834F56982925A85 (videojuego_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedido (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha_pedido DATETIME NOT NULL, total_compra DOUBLE PRECISION NOT NULL, INDEX IDX_C4EC16CEDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalle_pedido ADD CONSTRAINT FK_A834F5694854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id)');
        $this->addSql('ALTER TABLE detalle_pedido ADD CONSTRAINT FK_A834F56982925A85 FOREIGN KEY (videojuego_id) REFERENCES videojuego (id)');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CEDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE videojuego ADD stock INT NOT NULL, ADD descripcion LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_pedido DROP FOREIGN KEY FK_A834F5694854653A');
        $this->addSql('DROP TABLE detalle_pedido');
        $this->addSql('DROP TABLE pedido');
        $this->addSql('ALTER TABLE videojuego DROP stock, DROP descripcion');
    }
}
