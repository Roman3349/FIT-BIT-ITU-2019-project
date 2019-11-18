<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118175422 extends AbstractMigration {

    public function getDescription() : string {
        return 'Initializes the DB';
    }

    public function up(Schema $schema) : void {
    	$this->addSql('CREATE TABLE `bikes` (id INT AUTO_INCREMENT NOT NULL, manufacturer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, frame_material VARCHAR(15) NOT NULL, frame_size VARCHAR(15) NOT NULL, wheel_size VARCHAR(15) NOT NULL, fork_travel INT DEFAULT NULL, shock_travel INT DEFAULT NULL, speeds VARCHAR(15) NOT NULL, price INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F6FAF01CA23B42D (manufacturer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
    	$this->addSql('CREATE TABLE `manufacturers` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_94565B125E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
	    $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, role VARCHAR(15) NOT NULL, state INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
		$this->addSql('ALTER TABLE `bikes` ADD CONSTRAINT FK_F6FAF01CA23B42D FOREIGN KEY (manufacturer_id) REFERENCES `manufacturers` (id);');
    }

    public function down(Schema $schema) : void {
    	$this->addSql('DROP TABLE `bikes`');
	    $this->addSql('DROP TABLE `manufacturers`');
	    $this->addSql('DROP TABLE `users`');

    }
}
