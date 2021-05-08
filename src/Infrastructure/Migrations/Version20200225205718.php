<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200914131419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql('CREATE TABLE product (id INT DEFAULT nextval(\'product_id_seq\'::regclass) NOT NULL, gtin VARCHAR(30) NOT NULL, weight INT DEFAULT NULL, titles JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uq_gtin_idx ON product (gtin)');

        $this->addSql('CREATE TABLE category (id INT DEFAULT nextval(\'category_id_seq\'::regclass) NOT NULL, key VARCHAR(255) NOT NULL, sorting INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uq_key_idx ON category (key)');

        $this->addSql('CREATE TABLE "user" (id INT DEFAULT nextval(\'user_id_seq\'::regclass) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(180) NOT NULL, key VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498A90ABA9 ON "user" (key)');

        $this->addSql('CREATE TABLE review (id INT DEFAULT nextval(\'review_id_seq\'::regclass) NOT NULL, product_id INT DEFAULT nextval(\'product_id_seq\'::regclass) NOT NULL, user_id INT DEFAULT nextval(\'user_id_seq\'::regclass) NOT NULL, taste INT NOT NULL, ingredients INT NOT NULL, healthiness INT NOT NULL, packaging INT NOT NULL, availability INT NOT NULL, comment VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6C7FC73F3 ON review (product_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6C7FC73F3 FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');

        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6C7FC73F3');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6A76ED395');

        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');

        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE "user"');
    }
}
