<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200707133008 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_plagiat DROP FOREIGN KEY FK_BF1197EC212A233A');
        $this->addSql('DROP INDEX IDX_BF1197EC212A233A ON content_plagiat');
        $this->addSql('ALTER TABLE content_plagiat CHANGE id_content_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content_plagiat ADD CONSTRAINT FK_BF1197EC84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('CREATE INDEX IDX_BF1197EC84A0A3ED ON content_plagiat (content_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_plagiat DROP FOREIGN KEY FK_BF1197EC84A0A3ED');
        $this->addSql('DROP INDEX IDX_BF1197EC84A0A3ED ON content_plagiat');
        $this->addSql('ALTER TABLE content_plagiat CHANGE content_id id_content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content_plagiat ADD CONSTRAINT FK_BF1197EC212A233A FOREIGN KEY (id_content_id) REFERENCES content (id)');
        $this->addSql('CREATE INDEX IDX_BF1197EC212A233A ON content_plagiat (id_content_id)');
    }
}
