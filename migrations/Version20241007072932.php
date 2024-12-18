<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007072932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_line (id INT AUTO_INCREMENT NOT NULL, related_order_id INT NOT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, tax_free_unit_price INT NOT NULL, vat_rate DOUBLE PRECISION NOT NULL, INDEX IDX_D3D1D6932B1C2395 (related_order_id), INDEX IDX_D3D1D6934584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6932B1C2395 FOREIGN KEY (related_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6934584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD order_status VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D6932B1C2395');
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D6934584665A');
        $this->addSql('DROP TABLE invoice_line');
        $this->addSql('ALTER TABLE `order` DROP order_status');
    }
}
