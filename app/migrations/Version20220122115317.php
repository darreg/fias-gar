<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122115317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            create function reshuffle(str text) returns text[]
                immutable
                language plpgsql
            as
            $$
            DECLARE
                result text[];
                words text[];
                words_num int;
            BEGIN
                words = string_to_array(str, ' ');
                words_num = array_length(words, 1);
                IF words_num < 2 THEN
                    return result;
                end if;
            
                WITH RECURSIVE t(i) AS (
                    SELECT * FROM unnest(words)
                ), cte AS (
                    SELECT i AS combo, i, 1 AS ct FROM t
                    UNION ALL
                    SELECT cte.combo || ' ' || t.i, t.i, ct + 1 FROM cte, t WHERE ct <= 3 AND position(t.i in cte.combo) = 0
                )
                SELECT ARRAY(SELECT combo FROM cte WHERE LENGTH(combo) - LENGTH(REPLACE(combo, ' ', '')) = words_num - 1 ORDER BY ct, combo) INTO result;
            
                RETURN result;
            END;
            $$;
        SQL);

        $this->addSql(<<<SQL
            create function parent_level(parentid numeric, level1 numeric, level2 numeric, level3 numeric, level4 numeric, level5 numeric, level6 numeric, level7 numeric, level8 numeric) returns integer
                immutable
                language plpgsql
            as
            $$
            BEGIN
            
                RETURN CASE
                           WHEN level1=parentid THEN 1
                           WHEN level2=parentid THEN 2
                           WHEN level3=parentid THEN 3
                           WHEN level4=parentid THEN 4
                           WHEN level5=parentid THEN 5
                           WHEN level6=parentid THEN 6
                           WHEN level7=parentid THEN 7
                           WHEN level8=parentid THEN 8
                    END;
            END;
            $$;
        SQL);

        $this->addSql(<<<SQL
            create function prepare(str text) returns text
                immutable
                language plpgsql
            as
            $$
            BEGIN
                RETURN trim(lower(
                        regexp_replace(
                                regexp_replace(
                                        regexp_replace(regexp_replace(str, 'ё', 'е', 'gi'), '[^-0-9а-я/()\| ]', ' ', 'gi'),
                                        '\s+', ' ', 'gi'
                                    ),
                                '((\s|)\|(\s|))', '|', 'gi')
                    ));
            END;
            $$;
        SQL);

        $this->addSql(<<<SQL
            create function prepare_house(str text) returns text
                immutable
                language plpgsql
            as
            $$
            BEGIN
                RETURN lower(regexp_replace(str, '[^-0-9а-яё/]', '', 'gi'));
            END;
            $$;
        SQL);

        $this->addSql(<<<SQL
            create function prepare_array(aolevel integer, str text, VARIADIC typenames text[]) returns text[]
                immutable
                language plpgsql
            as
            $$
            DECLARE
                municipal_fixes text[] DEFAULT ARRAY[
                    'муниципальный район',
                    'муниципальный округ',
                    'рабочий поселок',
                    'ЗАТО поселок',
                    'поселок'
                    ];
                municipal_fix text;
                str_ text;
                typename text;
                strs text[];
                strs_ text[];
                ret text;
            BEGIN
                str = regexp_replace(str, '\s+', ' ', 'gi');
                str = regexp_replace(str, '(\d)\s*-\s*(я|й|ый|ая|ой|ей|го|ое)', '\\1-\\2', 'gi');
            
                IF aolevel = 3 THEN
                    FOREACH municipal_fix IN ARRAY municipal_fixes
                        LOOP
                            str_ = str;
                            str = trim(regexp_replace(str, municipal_fix, '', 'gi'));
                            IF str_ != str THEN
                                typenames = array_append(typenames, municipal_fix);
                            END IF;
                        END LOOP;
                END IF;
            
                IF array_length(string_to_array(str, ' '), 1) > 1 THEN
                    strs = reshuffle(prepare(str));
                END IF;
            
                IF str ~* '\d-' THEN
                    str_ = regexp_replace(str, '(\d)-я|-й|-ый|-ая|-ой|-ей|-го|-ое', '\\1', 'gi');
                    strs_ = strs_ || trim(str_);
                    IF array_length(string_to_array(str_, ' '), 1) > 1 THEN
                        strs = strs || prepare(str_);
                        strs = strs || reshuffle(prepare(str_));
                    END IF;
            
                    str_ = regexp_replace(str, '(\d)-', '\\1', 'gi');
                    strs_ = strs_ || trim(str_);
                    IF array_length(string_to_array(str_, ' '), 1) > 1 THEN
                        strs = strs || prepare(str_);
                        strs = strs || reshuffle(prepare(str_));
                    END IF;
                END IF;
            
                strs = array_append(strs, str);
            
                FOREACH str_ IN ARRAY strs
                    LOOP
                        FOREACH typename IN ARRAY typenames
                            LOOP
                                IF typename <> '' THEN
                                    strs = array_append(strs, concat(str_, ' ', typename));
                                    strs = array_append(strs, concat(typename, ' ', str_));
                                END IF;
                            END LOOP;
                    END LOOP;
            
                IF array_length(strs_, 1) > 1 THEN
                    FOREACH str_ IN ARRAY strs_
                        LOOP
                            FOREACH typename IN ARRAY typenames
                                LOOP
                                    IF typename <> '' THEN
                                        strs = array_append(strs, concat(str_, ' ', typename));
                                        strs = array_append(strs, concat(typename, ' ', str_));
                                    END IF;
                                END LOOP;
                        END LOOP;
                END IF;
            
                ret = prepare(array_to_string(strs, '|'));
            
                RETURN ARRAY(SELECT DISTINCT e FROM unnest(string_to_array(ret, '|')) AS a(e));
            END;
            $$;
        SQL);

        $this->addSql(<<<SQL
            create function address_string(_objectid numeric) returns text
                immutable
                language plpgsql
            as
            $$
            DECLARE
                addrobjects text[];
            BEGIN
            
                WITH RECURSIVE child_to_parents AS (
                    SELECT v.* FROM v_addrobj_adm as v
                    WHERE v.objectid = _objectid
                    UNION ALL
                    SELECT v.* FROM v_addrobj_adm as v, child_to_parents
                    WHERE v.objectid = child_to_parents.parentobjid
                )
                SELECT ARRAY(SELECT ctp.formalname || ' ' || ctp.socrname FROM child_to_parents AS ctp ORDER BY ctp.aolevel) INTO addrobjects;
            
                RETURN array_to_string(addrobjects, ', ');
            END;
            $$;
        SQL);

        $this->addSql(<<<SQL
            create function address_string_mun(_objectid numeric) returns text
                immutable
                language plpgsql
            as
            $$
            DECLARE
                addrobjects text[];
            BEGIN
            
                WITH RECURSIVE child_to_parents AS (
                    SELECT v.* FROM v_addrobj_mun as v
                    WHERE v.objectid = _objectid
                    UNION ALL
                    SELECT v.* FROM v_addrobj_mun as v, child_to_parents
                    WHERE v.objectid = child_to_parents.parentobjid
                )
                SELECT ARRAY(SELECT trim(ctp.formalname || ' ' || (CASE WHEN ctp.shortname = 'вн.тер.г.' THEN '' ELSE ctp.socrname END)) FROM child_to_parents AS ctp ORDER BY ctp.aolevel) INTO addrobjects;
            
                RETURN array_to_string(addrobjects, ', ');
            END;
            $$;
        SQL);

        $this->addSql(<<<SQL
            create materialized view v_addrobj_mun as
            SELECT ao.objectid,
                   ao.objectguid                                 AS aoguid,
                   ao.name                                       AS formalname,
                   ao.typename                                   AS shortname,
                   lower(atp.name::text)                         AS socrname,
                   atp.id                                        AS typeid,
                   ao.level::integer                             AS aolevel,
                   ao.updatedate,
                   hr.parentobjid,
                   pao.objectguid                                AS parentguid,
                   pao.name                                      AS parentformalname,
                   pao.typename                                  AS parentshortname,
                   lower(patp.name::text)                        AS parentsocrname,
                   pao.level::integer                            AS parentaolevel,
                   greatest(ao.changed_at, hr.changed_at, pao.changed_at) AS changed_at
            FROM fias_gar_addrobj ao
                     JOIN (SELECT mhr.objectid,
                                  max(mhr.parentobjid) AS parentobjid,
                                  max(mhr.changed_at) as changed_at
                           FROM fias_gar_munhierarchy mhr
                           WHERE mhr.isactive = 1
                           GROUP BY mhr.objectid) hr ON hr.objectid = ao.objectid
                     LEFT JOIN fias_gar_addrobj pao ON pao.isactive = 1 AND pao.isactual = 1 AND pao.objectid = hr.parentobjid
                     LEFT JOIN fias_gar_addrobjtypes atp
                               ON atp.isactive = true AND atp.level = ao.level::integer AND atp.shortname::text = ao.typename::text
                     LEFT JOIN fias_gar_addrobjtypes patp
                               ON patp.isactive = true AND patp.level = pao.level::integer AND patp.shortname::text = pao.typename::text
            WHERE ao.isactive = 1
              AND ao.isactual = 1
            WITH NO DATA
        SQL);
        $this->addSql('create index v_addrobj_mun__aolevel_formalname_objectid__ind on v_addrobj_mun (aolevel, formalname, objectid)');
        $this->addSql('create index v_addrobj_mun__objectid__ind on v_addrobj_mun (objectid)');
        $this->addSql('create index v_addrobj_mun__aoguid__ind on v_addrobj_mun (aoguid)');


        $this->addSql(<<<SQL
            create materialized view v_addrobj_adm as
            SELECT ao.objectid,
                   ao.objectguid                                 AS aoguid,
                   ao.name                                       AS formalname,
                   ao.typename                                   AS shortname,
                   lower(atp.name::text)                         AS socrname,
                   atp.id                                        AS typeid,
                   ao.level::integer                             AS aolevel,
                   ao.updatedate,
                   hr.parentobjid,
                   pao.objectguid                                AS parentguid,
                   pao.name                                      AS parentformalname,
                   pao.typename                                  AS parentshortname,
                   lower(patp.name::text)                        AS parentsocrname,
                   pao.level::integer                            AS parentaolevel,
                   greatest(ao.changed_at, hr.changed_at, pao.changed_at) AS changed_at
            FROM fias_gar_addrobj ao
                     JOIN (SELECT ahr.objectid,
                                  max(ahr.parentobjid) AS parentobjid,
                                  max(ahr.changed_at) as changed_at
                           FROM fias_gar_admhierarchy ahr
                           WHERE ahr.isactive = 1
                           GROUP BY ahr.objectid) hr ON hr.objectid = ao.objectid
                     LEFT JOIN fias_gar_addrobj pao ON pao.isactive = 1 AND pao.isactual = 1 AND pao.objectid = hr.parentobjid
                     LEFT JOIN fias_gar_addrobjtypes atp
                               ON atp.isactive = true AND atp.level = ao.level::integer AND atp.shortname::text = ao.typename::text
                     LEFT JOIN fias_gar_addrobjtypes patp
                               ON patp.isactive = true AND patp.level = pao.level::integer AND patp.shortname::text = pao.typename::text
            WHERE ao.isactive = 1
              AND ao.isactual = 1
            WITH NO DATA
        SQL);
        $this->addSql('create index v_addrobj_adm__aolevel_formalname_objectid__ind on v_addrobj_adm (aolevel, formalname, objectid)');
        $this->addSql('create index v_addrobj_adm__objectid__ind on v_addrobj_adm (objectid)');
        $this->addSql('create index v_addrobj_adm__aoguid__ind on v_addrobj_adm (aoguid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop index v_addrobj_adm__aolevel_formalname_objectid__ind');
        $this->addSql('drop index v_addrobj_adm__objectid__ind');
        $this->addSql('drop index v_addrobj_adm__aoguid__ind');
        $this->addSql('drop materialized view v_addrobj_adm');

        $this->addSql('drop index v_addrobj_mun__aolevel_formalname_objectid__ind');
        $this->addSql('drop index v_addrobj_mun__objectid__ind');
        $this->addSql('drop index v_addrobj_mun__aoguid__ind');
        $this->addSql('drop materialized view v_addrobj_mun');

        $this->addSql('drop function address_string');
        $this->addSql('drop function address_string_mun');
        $this->addSql('drop function prepare_array');
        $this->addSql('drop function prepare_house');
        $this->addSql('drop function prepare');
        $this->addSql('drop function parent_level');
        $this->addSql('drop function reshuffle');
    }
}
