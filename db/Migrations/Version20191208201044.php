<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208201044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `galleries` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F70E6EB75E237E06 (name), UNIQUE INDEX UNIQ_F70E6EB7F47645AE (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bikes ADD gallery_id INT DEFAULT NULL, DROP picture');
        $this->addSql('ALTER TABLE bikes ADD CONSTRAINT FK_F6FAF01CA23B42D FOREIGN KEY (manufacturer_id) REFERENCES `manufacturers` (id)');
        $this->addSql('ALTER TABLE bikes ADD CONSTRAINT FK_F6FAF01C2150E69A FOREIGN KEY (usage_id) REFERENCES `bikeUsages` (id)');
        $this->addSql('ALTER TABLE bikes ADD CONSTRAINT FK_F6FAF01C4E7AF8F FOREIGN KEY (gallery_id) REFERENCES `galleries` (id)');
        $this->addSql('CREATE INDEX IDX_F6FAF01C4E7AF8F ON bikes (gallery_id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239DE12AB56 FOREIGN KEY (created_by) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE reservation_bike ADD CONSTRAINT FK_5A85FAE8B83297E7 FOREIGN KEY (reservation_id) REFERENCES `reservations` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_bike ADD CONSTRAINT FK_5A85FAE8D5A4816F FOREIGN KEY (bike_id) REFERENCES `bikes` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `bikes` DROP FOREIGN KEY FK_F6FAF01C4E7AF8F');
        $this->addSql('DROP TABLE `galleries`');
        $this->addSql('ALTER TABLE `bikes` DROP FOREIGN KEY FK_F6FAF01CA23B42D');
        $this->addSql('ALTER TABLE `bikes` DROP FOREIGN KEY FK_F6FAF01C2150E69A');
        $this->addSql('DROP INDEX IDX_F6FAF01C4E7AF8F ON `bikes`');
        $this->addSql('ALTER TABLE `bikes` ADD picture VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, DROP gallery_id');
        $this->addSql('ALTER TABLE reservation_bike DROP FOREIGN KEY FK_5A85FAE8B83297E7');
        $this->addSql('ALTER TABLE reservation_bike DROP FOREIGN KEY FK_5A85FAE8D5A4816F');
        $this->addSql('ALTER TABLE `reservations` DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE `reservations` DROP FOREIGN KEY FK_4DA239DE12AB56');
    }
}
