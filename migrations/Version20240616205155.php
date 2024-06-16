<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240616205155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrito DROP FOREIGN KEY FK_CARRITO_PEDIDO');
        $this->addSql('ALTER TABLE carrito DROP FOREIGN KEY FK_CARRITO_PEDIDO');
        $this->addSql('ALTER TABLE carrito ADD CONSTRAINT FK_77E6BED54854653A FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('DROP INDEX fk_carrito_pedido ON carrito');
        $this->addSql('CREATE INDEX IDX_77E6BED54854653A ON carrito (pedido_id)');
        $this->addSql('ALTER TABLE carrito ADD CONSTRAINT FK_CARRITO_PEDIDO FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('ALTER TABLE pedidos CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE plato CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrito DROP FOREIGN KEY FK_77E6BED54854653A');
        $this->addSql('ALTER TABLE carrito DROP FOREIGN KEY FK_77E6BED54854653A');
        $this->addSql('ALTER TABLE carrito ADD CONSTRAINT FK_CARRITO_PEDIDO FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('DROP INDEX idx_77e6bed54854653a ON carrito');
        $this->addSql('CREATE INDEX FK_CARRITO_PEDIDO ON carrito (pedido_id)');
        $this->addSql('ALTER TABLE carrito ADD CONSTRAINT FK_77E6BED54854653A FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('ALTER TABLE pedidos CHANGE status status VARCHAR(20) DEFAULT \'pending\' NOT NULL');
        $this->addSql('ALTER TABLE plato CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_2265B05DE7927C74 ON usuario');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles JSON DEFAULT \'["ROLE_USER"]\' NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
