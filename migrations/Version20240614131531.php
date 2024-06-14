<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614131531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EA9A146A76ED395 ON calendar (user_id)');
        $this->addSql('ALTER TABLE ingredients ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE recipes ADD calendar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B5A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('CREATE INDEX IDX_A369E2B5A40A2C8 ON recipes (calendar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146A76ED395');
        $this->addSql('DROP INDEX IDX_6EA9A146A76ED395 ON calendar');
        $this->addSql('ALTER TABLE calendar DROP user_id');
        $this->addSql('ALTER TABLE ingredients DROP quantity');
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B5A40A2C8');
        $this->addSql('DROP INDEX IDX_A369E2B5A40A2C8 ON recipes');
        $this->addSql('ALTER TABLE recipes DROP calendar_id');
    }
}
