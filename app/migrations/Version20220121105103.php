<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121105103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ext_add_house_types (id UUID NOT NULL, name VARCHAR(255) NOT NULL, type_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ext_add_house_types__type_id_ind ON ext_add_house_types (type_id)');
        $this->addSql('COMMENT ON COLUMN ext_add_house_types.id IS \'(DC2Type:ext_add_house_type_id)\'');
        $this->addSql('CREATE TABLE ext_addrobj_types (id UUID NOT NULL, name VARCHAR(255) NOT NULL, type_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ext_addrobj_types__type_id_ind ON ext_addrobj_types (type_id)');
        $this->addSql('COMMENT ON COLUMN ext_addrobj_types.id IS \'(DC2Type:ext_addrobj_types_type_id)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ext_add_house_types');
        $this->addSql('DROP TABLE ext_addrobj_types');
    }
}
