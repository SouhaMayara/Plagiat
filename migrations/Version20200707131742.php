<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200707131742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9D2DBCA94');
        $this->addSql('DROP INDEX IDX_FEC530A9D2DBCA94 ON content');
        $this->addSql('ALTER TABLE content CHANGE id_page_id page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9C4663E4 FOREIGN KEY (page_id) REFERENCES site_page (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9C4663E4 ON content (page_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9C4663E4');
        $this->addSql('DROP INDEX IDX_FEC530A9C4663E4 ON content');
        $this->addSql('ALTER TABLE content CHANGE page_id id_page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9D2DBCA94 FOREIGN KEY (id_page_id) REFERENCES site_page (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9D2DBCA94 ON content (id_page_id)');
    }
}
