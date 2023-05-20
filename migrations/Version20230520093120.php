<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520093120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // création des tables
        $this->addSql('CREATE TABLE "users" ("id" SERIAL PRIMARY KEY,"email" VARCHAR(100) UNIQUE,"password" VARCHAR(250),"lastname" VARCHAR(60),"firstname" VARCHAR(60),"address" VARCHAR(200),"zipcode" VARCHAR(6),"city" VARCHAR(70),"roles" JSON,"created_at" TIMESTAMP DEFAULT NOW());');
        $this->addSql('CREATE TABLE "categories" ("id" SERIAL PRIMARY KEY,"name" VARCHAR(50),"slug" VARCHAR(100),"category_order" INT,"parent_id" INT,FOREIGN KEY ("parent_id") REFERENCES "categories" ("id"));');
        $this->addSql('CREATE TABLE "products" ("id" SERIAL PRIMARY KEY,"name" VARCHAR(100),"slug" VARCHAR(50),"description" TEXT,"price" INT,"stock" INT,"categories_id" INT,"created_at" TIMESTAMP DEFAULT NOW());');
        $this->addSql('ALTER TABLE "products" ADD FOREIGN KEY ("category_id") REFERENCES "categories" ("id");');
        $this->addSql('CREATE TABLE "images" ("id" SERIAL PRIMARY KEY,"name" VARCHAR(200),"products_id" int,FOREIGN KEY ("products_id") REFERENCES "products" ("id"));');
        $this->addSql('CREATE TABLE promotions (id SERIAL PRIMARY KEY,products_id INTEGER NOT NULL REFERENCES products(id),date_deb VARCHAR(30) NOT NULL,date_fin VARCHAR(30) NOT NULL,pourcentage INTEGER NOT NULL,created_at TIMESTAMP NOT NULL,slug VARCHAR(255) NOT NULL);');
        $this->addSql('INSERT INTO public.users (email,password,lastname,firstname,address,zipcode,city,roles,created_at) VALUES (\'kallemand@ham05.com\',\'$2y$13$3BhZXvRvL5IdvJsxTosbFeSAlimniGdntAe7dq4MmUkR7iPqFT5tS\',\'Allemand\',\'Kevin\',\'Une adresse\',\'05000\',\'GAP\',\'["ROLE_ADMIN"]\',\'2023-04-21\');');
        $this->addSql('INSERT INTO public.users (email,password,lastname,firstname,address,zipcode,city,roles,created_at) VALUES (\'studi@studi.fr\',\'$2y$10$8V.i22UQPH/kjZdmpJsWV.2xyDDKfR5YldWklV4zkekNTU26mfwJO\',\'STUDI\',\'Studi\',\'Une adresse\',\'00000\',\'PARIS\',\'["ROLE_ADMIN"]\',\'2023-04-21\');');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
