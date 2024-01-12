<?php

declare(strict_types=1);

namespace App\Hash\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109150910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avato_requests ADD COLUMN alias VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TEMPORARY TABLE __temp__avato_requests AS SELECT id, request_number, input_string, key_found, hash, attempts, moment_request FROM avato_requests'
        );
        $this->addSql('DROP TABLE avato_requests');
        $this->addSql(
            'CREATE TABLE avato_requests (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, request_number INTEGER NOT NULL, input_string VARCHAR(255) NOT NULL, key_found VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, attempts BIGINT NOT NULL, moment_request DATETIME NOT NULL)'
        );
        $this->addSql(
            'INSERT INTO avato_requests (id, request_number, input_string, key_found, hash, attempts, moment_request) SELECT id, request_number, input_string, key_found, hash, attempts, moment_request FROM __temp__avato_requests'
        );
        $this->addSql('DROP TABLE __temp__avato_requests');
    }
}
