<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121133437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('create or replace function tablecolumns(tblname text) returns text[] immutable language plpgsql as $$ DECLARE colnames text[]; BEGIN EXECUTE format(\'SELECT ARRAY(select a.attname as colname from pg_catalog.pg_attribute a inner join pg_catalog.pg_class c on a.attrelid = c.oid where c.relname = %L and a.attnum > 0 and a.attisdropped = false and pg_catalog.pg_table_is_visible(c.oid) order by a.attnum)\', tblname) INTO colnames; RETURN colnames; END; $$;');

        $this->addSql('create or replace function upsert(tblname text, primkey text, cols text[], VARIADIC params text[]) returns boolean language plpgsql as $$ DECLARE colnames text[]; colname text; colnames_str text := \'\'; param text; sql text := \'\'; field_place text := \'\'; BEGIN colnames = cols; IF colnames IS NULL THEN SELECT tablecolumns(tblname) INTO colnames; END IF; FOREACH colname IN ARRAY colnames LOOP IF colname = \'desc\' THEN sql = CONCAT(sql, \'"desc" = EXCLUDED.desc, \'); colnames_str = CONCAT(colnames_str, \'"desc",\'); ELSE sql = CONCAT(sql, colname, \' = EXCLUDED.\', colname, \', \'); colnames_str = CONCAT(colnames_str, colname , \',\'); END IF; END LOOP; FOREACH param IN ARRAY params LOOP field_place = CONCAT(field_place, \'%L\', \',\'); END LOOP; sql = format(\'INSERT INTO %I(%s) VALUES(%s) ON CONFLICT ("%I") DO UPDATE SET %s;\', tblname, LEFT(colnames_str, -1), LEFT(field_place, -1), primkey, LEFT(sql, -2)); sql = format(sql, VARIADIC params); EXECUTE sql; RETURN TRUE; END $$;');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop function tablecolumns');
        $this->addSql('drop function upsert');
    }
}
