<?php

declare(strict_types=1);

namespace App\Hash\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109141405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE avato_requests (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, request_number INTEGER NOT NULL, input_string VARCHAR(255) NOT NULL, key_found VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, attempts BIGINT NOT NULL, moment_request DATETIME NOT NULL)'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE avato_requests');
    }
}
