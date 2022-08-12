<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version202208101337200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE machine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE location (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE machine (id INT NOT NULL, location_id INT NOT NULL, name VARCHAR(255) NOT NULL, ram_quantity SMALLINT NOT NULL, ram_type VARCHAR(20) NOT NULL, hard_disk_type VARCHAR(20) NOT NULL, hard_disk_quantity SMALLINT NOT NULL, hard_disk_size SMALLINT NOT NULL, hard_disk_total_capacity_gb SMALLINT NOT NULL, price NUMERIC(10, 2) NOT NULL, currency VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1505DF8464D218E ON machine (location_id)');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF8464D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE machine_id_seq CASCADE');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT FK_1505DF8464D218E');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE machine');
    }
}
