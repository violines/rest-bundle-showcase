<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200218212033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE candy_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE candy_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rating_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE candy (id INT DEFAULT nextval(\'candy_id_seq\'::regclass) NOT NULL, gtin VARCHAR(30) NOT NULL, weight INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE candy_translation (id INT DEFAULT nextval(\'candy_translation_id_seq\'::regclass) NOT NULL, candy_id INT, language VARCHAR(10) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E8E5FB17C7FC73F3 ON candy_translation (candy_id)');
        $this->addSql('CREATE TABLE rating (id INT DEFAULT nextval(\'rating_id_seq\'::regclass) NOT NULL, candy_id INT NOT NULL, taste INT NOT NULL, ingredients INT NOT NULL, healthiness INT NOT NULL, packaging INT NOT NULL, availability INT NOT NULL, comment VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8892622C7FC73F3 ON rating (candy_id)');
        $this->addSql('ALTER TABLE candy_translation ADD CONSTRAINT FK_E8E5FB17C7FC73F3 FOREIGN KEY (candy_id) REFERENCES candy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622C7FC73F3 FOREIGN KEY (candy_id) REFERENCES candy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE candy_translation DROP CONSTRAINT FK_E8E5FB17C7FC73F3');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D8892622C7FC73F3');
        $this->addSql('DROP SEQUENCE candy_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE candy_translation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rating_id_seq CASCADE');
        $this->addSql('DROP TABLE candy');
        $this->addSql('DROP TABLE candy_translation');
        $this->addSql('DROP TABLE rating');
    }
}
