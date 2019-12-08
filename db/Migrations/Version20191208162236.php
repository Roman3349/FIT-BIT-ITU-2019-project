<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Initial migration
 */
final class Version20191208162236 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `manufacturers` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_94565B125E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `bikes` (id INT AUTO_INCREMENT NOT NULL, manufacturer_id INT DEFAULT NULL, usage_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, frame_material VARCHAR(15) NOT NULL, frame_size VARCHAR(15) NOT NULL, wheel_size VARCHAR(15) NOT NULL, fork_travel INT DEFAULT NULL, shock_travel INT DEFAULT NULL, speeds VARCHAR(15) NOT NULL, price INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F6FAF01CA23B42D (manufacturer_id), INDEX IDX_F6FAF01C2150E69A (usage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `reservations` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_by INT DEFAULT NULL, from_date DATETIME NOT NULL, to_date DATETIME NOT NULL, state INT NOT NULL, price INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4DA239A76ED395 (user_id), INDEX IDX_4DA239DE12AB56 (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_bike (reservation_id INT NOT NULL, bike_id INT NOT NULL, INDEX IDX_5A85FAE8B83297E7 (reservation_id), INDEX IDX_5A85FAE8D5A4816F (bike_id), PRIMARY KEY(reservation_id, bike_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, role VARCHAR(15) NOT NULL, state INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `bikeUsages` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6FE2A5975E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `bikes` ADD CONSTRAINT FK_F6FAF01CA23B42D FOREIGN KEY (manufacturer_id) REFERENCES `manufacturers` (id)');
        $this->addSql('ALTER TABLE `bikes` ADD CONSTRAINT FK_F6FAF01C2150E69A FOREIGN KEY (usage_id) REFERENCES `bikeUsages` (id)');
        $this->addSql('ALTER TABLE `reservations` ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE `reservations` ADD CONSTRAINT FK_4DA239DE12AB56 FOREIGN KEY (created_by) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE reservation_bike ADD CONSTRAINT FK_5A85FAE8B83297E7 FOREIGN KEY (reservation_id) REFERENCES `reservations` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_bike ADD CONSTRAINT FK_5A85FAE8D5A4816F FOREIGN KEY (bike_id) REFERENCES `bikes` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `bikes` DROP FOREIGN KEY FK_F6FAF01CA23B42D');
        $this->addSql('ALTER TABLE reservation_bike DROP FOREIGN KEY FK_5A85FAE8D5A4816F');
        $this->addSql('ALTER TABLE reservation_bike DROP FOREIGN KEY FK_5A85FAE8B83297E7');
        $this->addSql('ALTER TABLE `reservations` DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE `reservations` DROP FOREIGN KEY FK_4DA239DE12AB56');
        $this->addSql('ALTER TABLE `bikes` DROP FOREIGN KEY FK_F6FAF01C2150E69A');
        $this->addSql('DROP TABLE `manufacturers`');
        $this->addSql('DROP TABLE `bikes`');
        $this->addSql('DROP TABLE `reservations`');
        $this->addSql('DROP TABLE reservation_bike');
        $this->addSql('DROP TABLE `users`');
        $this->addSql('DROP TABLE `bikeUsages`');
    }
}
