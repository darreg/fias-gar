<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122134308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            create materialized view v_search_types as
            SELECT types.level,
                   types.short,
                   types.name,
                   max(types.changed_at) as changed_at
            FROM (SELECT fga.level,
                         lower(btrim(regexp_replace(regexp_replace(fga.shortname::text, '[^-а-я0-9]'::text, ' '::text, 'gi'::text),
                                                    '\s+'::text, ' '::text, 'gi'::text))) AS short,
                         lower(btrim(fga.name::text)) AS name,
                         fga.changed_at
                  FROM fias_gar_addrobjtypes fga
                  WHERE lower(btrim(fga.name::text)) <> 'чувашия'::text
                  UNION
                  SELECT fga.level,
                         lower(btrim(regexp_replace(eah.name::text, '[^а-я0-9-]'::text, ' '::text, 'gi'::text))) AS short,
                         lower(btrim(fga.name::text)) AS name,
                         greatest(eah.updated_at, fga.changed_at) AS changed_at
                  FROM ext_addrobj_types eah
                           LEFT JOIN fias_gar_addrobjtypes fga ON fga.id = eah.type_id::numeric
                  UNION
                  SELECT 10                                                                                           AS level,
                         lower(btrim(regexp_replace(fgh.shortname::text, '[^а-я0-9-]'::text, ' '::text, 'gi'::text))) AS short,
                         lower(btrim(fgh.name::text)) AS name,
                         fgh.changed_at
                  FROM fias_gar_housetypes fgh
                  UNION
                  SELECT 10                                                                                      AS level,
                         lower(btrim(regexp_replace(eah.name::text, '[^а-я0-9-]'::text, ' '::text, 'gi'::text))) AS short,
                         lower(btrim(fgat.name::text)) AS name,
                         greatest(eah.updated_at, fgat.changed_at) AS changed_at
                  FROM ext_add_house_types eah
                           LEFT JOIN fias_gar_addhousetypes fgat ON fgat.id = eah.type_id::numeric) types
            GROUP BY types.level, types.short, types.name
            ORDER BY (length(types.short)) DESC
            WITH NO DATA
        SQL);


        $this->addSql(<<<SQL
            create materialized view v_addrobj_names as
            SELECT va.objectid,
                   va.formalname AS name,
                   va.socrname   AS typename,
                   va.shortname  AS stypename,
                   va.aoguid     AS guid,
                   va.changed_at
            FROM v_addrobj_adm va
            UNION
            SELECT va.objectid,
                   va.formalname AS name,
                   va.socrname   AS typename,
                   va.shortname  AS stypename,
                   va.aoguid     AS guid,
                   va.changed_at
            FROM v_addrobj_mun va
            WITH NO DATA
        SQL);
        $this->addSql('create index v_addrobj_names__objectid__ind on v_addrobj_names (objectid)');
        $this->addSql('create index v_addrobj_names__changed_at_ind on v_addrobj_names (changed_at)');

        $this->addSql(<<<SQL
            create materialized view v_addrobj_plain_adm as
            select a.objectid,
                   a.aolevel as aolevel,
                   case
                       when a.aolevel=1 then a.objectid
                       when ap.aolevel=1 then ap.objectid
                       when app.aolevel=1 then app.objectid
                       when appp.aolevel=1 then appp.objectid
                       when apppp.aolevel=1 then apppp.objectid
                       when appppp.aolevel=1 then appppp.objectid
                       when apppppp.aolevel=1 then apppppp.objectid
                       when appppppp.aolevel=1 then appppppp.objectid
                       end as level1,
                   case
                       when a.aolevel=2 then a.objectid
                       when ap.aolevel=2 then ap.objectid
                       when app.aolevel=2 then app.objectid
                       when appp.aolevel=2 then appp.objectid
                       when apppp.aolevel=2 then apppp.objectid
                       when appppp.aolevel=2 then appppp.objectid
                       when apppppp.aolevel=2 then apppppp.objectid
                       when appppppp.aolevel=2 then appppppp.objectid
                       end as level2,
                   case
                       when a.aolevel=3 then a.objectid
                       when ap.aolevel=3 then ap.objectid
                       when app.aolevel=3 then app.objectid
                       when appp.aolevel=3 then appp.objectid
                       when apppp.aolevel=3 then apppp.objectid
                       when appppp.aolevel=3 then appppp.objectid
                       when apppppp.aolevel=3 then apppppp.objectid
                       when appppppp.aolevel=3 then appppppp.objectid
                       end as level3,
                   case
                       when a.aolevel=4 then a.objectid
                       when ap.aolevel=4 then ap.objectid
                       when app.aolevel=4 then app.objectid
                       when appp.aolevel=4 then appp.objectid
                       when apppp.aolevel=4 then apppp.objectid
                       when appppp.aolevel=4 then appppp.objectid
                       when apppppp.aolevel=4 then apppppp.objectid
                       when appppppp.aolevel=4 then appppppp.objectid
                       end as level4,
                   case
                       when a.aolevel=5 then a.objectid
                       when ap.aolevel=5 then ap.objectid
                       when app.aolevel=5 then app.objectid
                       when appp.aolevel=5 then appp.objectid
                       when apppp.aolevel=5 then apppp.objectid
                       when appppp.aolevel=5 then appppp.objectid
                       when apppppp.aolevel=5 then apppppp.objectid
                       when appppppp.aolevel=5 then appppppp.objectid
                       end as level5,
                   case
                       when a.aolevel=6 then a.objectid
                       when ap.aolevel=6 then ap.objectid
                       when app.aolevel=6 then app.objectid
                       when appp.aolevel=6 then appp.objectid
                       when apppp.aolevel=6 then apppp.objectid
                       when appppp.aolevel=6 then appppp.objectid
                       when apppppp.aolevel=6 then apppppp.objectid
                       when appppppp.aolevel=6 then appppppp.objectid
                       end as level6,
                   case
                       when a.aolevel=7 then a.objectid
                       when ap.aolevel=7 then ap.objectid
                       when app.aolevel=7 then app.objectid
                       when appp.aolevel=7 then appp.objectid
                       when apppp.aolevel=7 then apppp.objectid
                       when appppp.aolevel=7 then appppp.objectid
                       when apppppp.aolevel=7 then apppppp.objectid
                       when appppppp.aolevel=7 then appppppp.objectid
                       end as level7,
                   case
                       when a.aolevel=8 then a.objectid
                       when ap.aolevel=8 then ap.objectid
                       when app.aolevel=8 then app.objectid
                       when appp.aolevel=8 then appp.objectid
                       when apppp.aolevel=8 then apppp.objectid
                       when appppp.aolevel=8 then appppp.objectid
                       when apppppp.aolevel=8 then apppppp.objectid
                       when appppppp.aolevel=8 then appppppp.objectid
                       end as level8,
                       greatest(a.changed_at, ap.changed_at, app.changed_at, appp.changed_at, apppp.changed_at, appppp.changed_at, apppppp.changed_at, appppppp.changed_at) AS changed_at                   
            from v_addrobj_adm a
                     left join v_addrobj_adm ap on ap.objectid = a.parentobjid
                     left join v_addrobj_adm app on app.objectid = ap.parentobjid
                     left join v_addrobj_adm appp on appp.objectid = app.parentobjid
                     left join v_addrobj_adm apppp on apppp.objectid = appp.parentobjid
                     left join v_addrobj_adm appppp on appppp.objectid = apppp.parentobjid
                     left join v_addrobj_adm apppppp on apppppp.objectid = appppp.parentobjid
                     left join v_addrobj_adm appppppp on appppppp.objectid = apppppp.parentobjid
            WITH NO DATA            
        SQL);
        $this->addSql('create index v_addrobj_plain_adm__objectid__ind on v_addrobj_plain_adm (objectid)');
        $this->addSql('create index v_addrobj_plain_adm__changed_at_ind on v_addrobj_plain_adm (changed_at)');

        $this->addSql(<<<SQL
            create materialized view v_addrobj_plain_mun as
            select a.objectid,
                   a.aolevel as aolevel,
                   case
                       when a.aolevel=1 then a.objectid
                       when ap.aolevel=1 then ap.objectid
                       when app.aolevel=1 then app.objectid
                       when appp.aolevel=1 then appp.objectid
                       when apppp.aolevel=1 then apppp.objectid
                       when appppp.aolevel=1 then appppp.objectid
                       when apppppp.aolevel=1 then apppppp.objectid
                       when appppppp.aolevel=1 then appppppp.objectid
                       end as level1,
                   case
                       when a.aolevel=2 then a.objectid
                       when ap.aolevel=2 then ap.objectid
                       when app.aolevel=2 then app.objectid
                       when appp.aolevel=2 then appp.objectid
                       when apppp.aolevel=2 then apppp.objectid
                       when appppp.aolevel=2 then appppp.objectid
                       when apppppp.aolevel=2 then apppppp.objectid
                       when appppppp.aolevel=2 then appppppp.objectid
                       end as level2,
                   case
                       when a.aolevel=3 then a.objectid
                       when ap.aolevel=3 then ap.objectid
                       when app.aolevel=3 then app.objectid
                       when appp.aolevel=3 then appp.objectid
                       when apppp.aolevel=3 then apppp.objectid
                       when appppp.aolevel=3 then appppp.objectid
                       when apppppp.aolevel=3 then apppppp.objectid
                       when appppppp.aolevel=3 then appppppp.objectid
                       end as level3,
                   case
                       when a.aolevel=4 then a.objectid
                       when ap.aolevel=4 then ap.objectid
                       when app.aolevel=4 then app.objectid
                       when appp.aolevel=4 then appp.objectid
                       when apppp.aolevel=4 then apppp.objectid
                       when appppp.aolevel=4 then appppp.objectid
                       when apppppp.aolevel=4 then apppppp.objectid
                       when appppppp.aolevel=4 then appppppp.objectid
                       end as level4,
                   case
                       when a.aolevel=5 then a.objectid
                       when ap.aolevel=5 then ap.objectid
                       when app.aolevel=5 then app.objectid
                       when appp.aolevel=5 then appp.objectid
                       when apppp.aolevel=5 then apppp.objectid
                       when appppp.aolevel=5 then appppp.objectid
                       when apppppp.aolevel=5 then apppppp.objectid
                       when appppppp.aolevel=5 then appppppp.objectid
                       end as level5,
                   case
                       when a.aolevel=6 then a.objectid
                       when ap.aolevel=6 then ap.objectid
                       when app.aolevel=6 then app.objectid
                       when appp.aolevel=6 then appp.objectid
                       when apppp.aolevel=6 then apppp.objectid
                       when appppp.aolevel=6 then appppp.objectid
                       when apppppp.aolevel=6 then apppppp.objectid
                       when appppppp.aolevel=6 then appppppp.objectid
                       end as level6,
                   case
                       when a.aolevel=7 then a.objectid
                       when ap.aolevel=7 then ap.objectid
                       when app.aolevel=7 then app.objectid
                       when appp.aolevel=7 then appp.objectid
                       when apppp.aolevel=7 then apppp.objectid
                       when appppp.aolevel=7 then appppp.objectid
                       when apppppp.aolevel=7 then apppppp.objectid
                       when appppppp.aolevel=7 then appppppp.objectid
                       end as level7,
                   case
                       when a.aolevel=8 then a.objectid
                       when ap.aolevel=8 then ap.objectid
                       when app.aolevel=8 then app.objectid
                       when appp.aolevel=8 then appp.objectid
                       when apppp.aolevel=8 then apppp.objectid
                       when appppp.aolevel=8 then appppp.objectid
                       when apppppp.aolevel=8 then apppppp.objectid
                       when appppppp.aolevel=8 then appppppp.objectid
                       end as level8,
                       greatest(a.changed_at, ap.changed_at, app.changed_at, appp.changed_at, apppp.changed_at, appppp.changed_at, apppppp.changed_at, appppppp.changed_at) AS changed_at
            from v_addrobj_mun a
                     left join v_addrobj_mun ap on ap.objectid = a.parentobjid
                     left join v_addrobj_mun app on app.objectid = ap.parentobjid
                     left join v_addrobj_mun appp on appp.objectid = app.parentobjid
                     left join v_addrobj_mun apppp on apppp.objectid = appp.parentobjid
                     left join v_addrobj_mun appppp on appppp.objectid = apppp.parentobjid
                     left join v_addrobj_mun apppppp on apppppp.objectid = appppp.parentobjid
                     left join v_addrobj_mun appppppp on appppppp.objectid = apppppp.parentobjid
            WITH NO DATA            
        SQL);
        $this->addSql('create index v_addrobj_plain_mun__objectid__ind on v_addrobj_plain_mun (objectid)');
        $this->addSql('create index v_addrobj_plain_mun__changed_at_ind on v_addrobj_plain_mun (changed_at)');

        $this->addSql(<<<SQL
            create materialized view v_addrobj_address_strings as
            SELECT 1 AS type,
                   va.objectid,
                   va.aolevel,
                   address_string(va.objectid) AS address_string,
                   va.changed_at
            FROM v_addrobj_adm va
            UNION
            SELECT 2 AS type,
                   va.objectid,
                   va.aolevel,
                   address_string_mun(va.objectid) AS address_string,
                   va.changed_at
            FROM v_addrobj_mun va
            WITH NO DATA
        SQL);
        $this->addSql('create index v_addrobj_address_strings__objectid__ind on v_addrobj_address_strings (objectid)');
        $this->addSql('create index v_addrobj_address_strings__changed_at_ind on v_addrobj_address_strings (changed_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop index v_addrobj_names__objectid__ind');
        $this->addSql('drop index v_addrobj_names__changed_at_ind');
        $this->addSql('drop materialized view v_addrobj_names');

        $this->addSql('drop index v_addrobj_address_strings__objectid__ind');
        $this->addSql('drop index v_addrobj_address_strings__changed_at_ind');
        $this->addSql('drop materialized view v_addrobj_address_strings');

        $this->addSql('drop materialized view v_search_types');

        $this->addSql('drop index v_addrobj_plain_mun__objectid__ind');
        $this->addSql('drop index v_addrobj_plain_mun__changed_at_ind');
        $this->addSql('drop materialized view v_addrobj_plain_mun');

        $this->addSql('drop index v_addrobj_plain_adm__objectid__ind');
        $this->addSql('drop index v_addrobj_plain_adm__changed_at_ind');
        $this->addSql('drop materialized view v_addrobj_plain_adm');
    }
}
