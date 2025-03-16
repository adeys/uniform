<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316124909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form_notification_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, form_id INTEGER NOT NULL, enabled BOOLEAN NOT NULL, type VARCHAR(32) NOT NULL, target VARCHAR(255) DEFAULT NULL, options CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_9EF488325FF69B7D FOREIGN KEY (form_id) REFERENCES forms (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9EF488325FF69B7D ON form_notification_settings (form_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__forms AS SELECT id, name, description, uid, created_at, updated_at, enabled, redirect_url FROM forms');
        $this->addSql('DROP TABLE forms');
        $this->addSql('CREATE TABLE forms (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, uid BLOB NOT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled BOOLEAN NOT NULL, redirect_url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO forms (id, name, description, uid, created_at, updated_at, enabled, redirect_url) SELECT id, name, description, uid, created_at, updated_at, enabled, redirect_url FROM __temp__forms');
        $this->addSql('DROP TABLE __temp__forms');
        $this->addSql('CREATE INDEX IDX_FD3F1BF7539B0606 ON forms (uid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE form_notification_settings');
        $this->addSql('CREATE TEMPORARY TABLE __temp__forms AS SELECT id, name, description, uid, enabled, redirect_url, created_at, updated_at FROM forms');
        $this->addSql('DROP TABLE forms');
        $this->addSql('CREATE TABLE forms (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, uid BLOB NOT NULL --(DC2Type:uuid)
        , enabled BOOLEAN DEFAULT TRUE NOT NULL, redirect_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO forms (id, name, description, uid, enabled, redirect_url, created_at, updated_at) SELECT id, name, description, uid, enabled, redirect_url, created_at, updated_at FROM __temp__forms');
        $this->addSql('DROP TABLE __temp__forms');
        $this->addSql('CREATE INDEX IDX_FD3F1BF7539B0606 ON forms (uid)');
    }
}
