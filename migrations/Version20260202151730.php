<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202151730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE search_forecast_history (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, hourly TINYINT NOT NULL, weather_code TINYINT NOT NULL, wind_speed10m TINYINT NOT NULL, wind_speed_unit VARCHAR(255) NOT NULL, user_id INT NOT NULL, INDEX IDX_6AA6D26AA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE search_forecast_history ADD CONSTRAINT FK_6AA6D26AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_forecast_history DROP FOREIGN KEY FK_6AA6D26AA76ED395');
        $this->addSql('DROP TABLE search_forecast_history');
    }
}
