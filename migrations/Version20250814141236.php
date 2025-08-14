<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250814141236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id SERIAL NOT NULL, owner_id INT NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, year INT NOT NULL, range INT NOT NULL, battery_capacity DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_773DE69D7E3C61F9 ON car (owner_id)');
        $this->addSql('COMMENT ON COLUMN car.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN car.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE charging (id SERIAL NOT NULL, owner_id INT NOT NULL, car_id INT NOT NULL, station_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total_kwh DOUBLE PRECISION NOT NULL, total_cost DOUBLE PRECISION NOT NULL, duration TIME(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AFA98247E3C61F9 ON charging (owner_id)');
        $this->addSql('CREATE INDEX IDX_AFA9824C3C6F69F ON charging (car_id)');
        $this->addSql('CREATE INDEX IDX_AFA982421BDB235 ON charging (station_id)');
        $this->addSql('COMMENT ON COLUMN charging.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN charging.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE station (id SERIAL NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, output_power DOUBLE PRECISION NOT NULL, electricity_price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F39F8B17E3C61F9 ON station (owner_id)');
        $this->addSql('COMMENT ON COLUMN station.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN station.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE charging ADD CONSTRAINT FK_AFA98247E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE charging ADD CONSTRAINT FK_AFA9824C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE charging ADD CONSTRAINT FK_AFA982421BDB235 FOREIGN KEY (station_id) REFERENCES station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B17E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D7E3C61F9');
        $this->addSql('ALTER TABLE charging DROP CONSTRAINT FK_AFA98247E3C61F9');
        $this->addSql('ALTER TABLE charging DROP CONSTRAINT FK_AFA9824C3C6F69F');
        $this->addSql('ALTER TABLE charging DROP CONSTRAINT FK_AFA982421BDB235');
        $this->addSql('ALTER TABLE station DROP CONSTRAINT FK_9F39F8B17E3C61F9');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE charging');
        $this->addSql('DROP TABLE station');
    }
}
