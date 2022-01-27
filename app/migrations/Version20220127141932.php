<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127141932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE version ADD full_has_xml BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE version ADD full_load_try_num INT NOT NULL');
        $this->addSql('ALTER TABLE version ADD full_broken_url BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE version ADD delta_has_xml BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE version ADD delta_load_try_num INT NOT NULL');
        $this->addSql('ALTER TABLE version ADD delta_broken_url BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE version DROP has_full_xml');
        $this->addSql('ALTER TABLE version DROP has_delta_xml');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE version ADD has_full_xml BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE version ADD has_delta_xml BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE version DROP full_has_xml');
        $this->addSql('ALTER TABLE version DROP full_load_try_num');
        $this->addSql('ALTER TABLE version DROP full_broken_url');
        $this->addSql('ALTER TABLE version DROP delta_has_xml');
        $this->addSql('ALTER TABLE version DROP delta_load_try_num');
        $this->addSql('ALTER TABLE version DROP delta_broken_url');
    }
}
