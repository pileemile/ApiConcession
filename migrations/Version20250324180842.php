<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324180842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE option (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reparation (id SERIAL NOT NULL, vehicle_id INT DEFAULT NULL, description TEXT NOT NULL, repair_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, cost NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8FDF219D545317D1 ON reparation (vehicle_id)');
        $this->addSql('CREATE TABLE sale (id SERIAL NOT NULL, vehicle_id INT NOT NULL, sale_date DATE NOT NULL, sale_price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E54BC005545317D1 ON sale (vehicle_id)');
        $this->addSql('CREATE TABLE seller (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, date_embauche DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE supplier (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, contact VARCHAR(150) NOT NULL, adress VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, phone VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE supply (id SERIAL NOT NULL, supplier_id INT DEFAULT NULL, vehicle_id INT DEFAULT NULL, quantity INT NOT NULL, supply_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, purchase_price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D219948C2ADD6D8C ON supply (supplier_id)');
        $this->addSql('CREATE INDEX IDX_D219948C545317D1 ON supply (vehicle_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE vehicle (id SERIAL NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, year INT NOT NULL, price NUMERIC(10, 2) NOT NULL, mileage INT NOT NULL, type VARCHAR(20) NOT NULL, fuel_type VARCHAR(20) NOT NULL, transmission_type VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE vehicle_option (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE vehicle_option_option (vehicle_option_id INT NOT NULL, option_id INT NOT NULL, PRIMARY KEY(vehicle_option_id, option_id))');
        $this->addSql('CREATE INDEX IDX_4E075FAD507B583D ON vehicle_option_option (vehicle_option_id)');
        $this->addSql('CREATE INDEX IDX_4E075FADA7C41D6F ON vehicle_option_option (option_id)');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT FK_8FDF219D545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supply ADD CONSTRAINT FK_D219948C2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supply ADD CONSTRAINT FK_D219948C545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle_option_option ADD CONSTRAINT FK_4E075FAD507B583D FOREIGN KEY (vehicle_option_id) REFERENCES vehicle_option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle_option_option ADD CONSTRAINT FK_4E075FADA7C41D6F FOREIGN KEY (option_id) REFERENCES option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reparation DROP CONSTRAINT FK_8FDF219D545317D1');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC005545317D1');
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948C2ADD6D8C');
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948C545317D1');
        $this->addSql('ALTER TABLE vehicle_option_option DROP CONSTRAINT FK_4E075FAD507B583D');
        $this->addSql('ALTER TABLE vehicle_option_option DROP CONSTRAINT FK_4E075FADA7C41D6F');
        $this->addSql('DROP TABLE option');
        $this->addSql('DROP TABLE reparation');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE seller');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE supply');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_option');
        $this->addSql('DROP TABLE vehicle_option_option');
    }
}
