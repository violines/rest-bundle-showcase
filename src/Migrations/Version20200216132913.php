<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216132913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE candy_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE candy_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE candy (id INT DEFAULT nextval(\'candy_id_seq\'::regclass) NOT NULL, weight INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE candy_translation (id INT DEFAULT nextval(\'candy_translation_id_seq\'::regclass) NOT NULL, candy_id INT, language VARCHAR(10) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E8E5FB17C7FC73F3 ON candy_translation (candy_id)');
        $this->addSql('ALTER TABLE candy_translation ADD CONSTRAINT FK_E8E5FB17C7FC73F3 FOREIGN KEY (candy_id) REFERENCES candy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE candy_translation DROP CONSTRAINT FK_E8E5FB17C7FC73F3');
        $this->addSql('DROP SEQUENCE candy_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE candy_translation_id_seq CASCADE');
        $this->addSql('DROP TABLE candy');
        $this->addSql('DROP TABLE candy_translation');
    }
}
