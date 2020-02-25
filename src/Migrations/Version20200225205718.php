<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225205718 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE candy_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE candy (id INT DEFAULT nextval(\'candy_id_seq\'::regclass) NOT NULL, gtin VARCHAR(30) NOT NULL, weight INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uq_gtin_idx ON candy (gtin)');
        $this->addSql('CREATE TABLE review (id INT DEFAULT nextval(\'review_id_seq\'::regclass) NOT NULL, candy_id INT NOT NULL, taste INT NOT NULL, ingredients INT NOT NULL, healthiness INT NOT NULL, packaging INT NOT NULL, availability INT NOT NULL, comment VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6C7FC73F3 ON review (candy_id)');
        $this->addSql('CREATE TABLE candy_translation (id INT DEFAULT nextval(\'candy_translation_id_seq\'::regclass) NOT NULL, candy_id INT, language VARCHAR(10) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E8E5FB17C7FC73F3 ON candy_translation (candy_id)');
        $this->addSql('CREATE UNIQUE INDEX uq_candy_id_idx ON candy_translation (candy_id, language)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6C7FC73F3 FOREIGN KEY (candy_id) REFERENCES candy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE candy_translation ADD CONSTRAINT FK_E8E5FB17C7FC73F3 FOREIGN KEY (candy_id) REFERENCES candy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6C7FC73F3');
        $this->addSql('ALTER TABLE candy_translation DROP CONSTRAINT FK_E8E5FB17C7FC73F3');
        $this->addSql('DROP SEQUENCE candy_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE candy_translation_id_seq CASCADE');
        $this->addSql('DROP TABLE candy');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE candy_translation');
    }
}
