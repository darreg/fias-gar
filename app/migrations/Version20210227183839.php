<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227183839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE INDEX v_house_adm__objectid__ind ON v_house_adm (objectid)');
        $this->addSql('CREATE INDEX v_house_adm__parentobjid__ind ON v_house_adm (parentobjid)');
        $this->addSql('CREATE INDEX v_house_adm__objectguid__ind ON v_house_adm (objectguid)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX v_house_adm__objectid__ind');
        $this->addSql('DROP INDEX v_house_adm__parentobjid__ind');
        $this->addSql('DROP INDEX v_house_adm__objectguid__ind');
    }
}
