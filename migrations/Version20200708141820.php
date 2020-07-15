<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200708141820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_FEC530A9C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_plagiat (id INT AUTO_INCREMENT NOT NULL, content_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, plagiat VARCHAR(255) NOT NULL, INDEX IDX_BF1197EC84A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, sitemap VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_page (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, plagiat TINYINT(1) NOT NULL, states VARCHAR(255) NOT NULL, INDEX IDX_2F900BD9F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9C4663E4 FOREIGN KEY (page_id) REFERENCES site_page (id)');
        $this->addSql('ALTER TABLE content_plagiat ADD CONSTRAINT FK_BF1197EC84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE site_page ADD CONSTRAINT FK_2F900BD9F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_plagiat DROP FOREIGN KEY FK_BF1197EC84A0A3ED');
        $this->addSql('ALTER TABLE site_page DROP FOREIGN KEY FK_2F900BD9F6BD1646');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9C4663E4');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE content_plagiat');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_page');
    }
}
