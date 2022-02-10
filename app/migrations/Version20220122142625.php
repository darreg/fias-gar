<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122142625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            create materialized view v_search_houses as
            SELECT DISTINCT h.objectid,
                            CASE
                                WHEN hr.parentobjid IS NULL THEN 0::numeric
                                ELSE hr.parentobjid
                                END AS parentid,
                            parent_level(hr.parentobjid, ap.level1, ap.level2, ap.level3, ap.level4, ap.level5, ap.level6, ap.level7, ap.level8) AS parentlevel,
                            CASE
                                WHEN h.addnum1 IS NOT NULL AND h.addnum2 IS NOT NULL THEN prepare_house(concat(h.housenum, ahtp1.shortname, h.addnum1, ahtp2.shortname, h.addnum2))::character varying::text
                                WHEN h.addnum1 IS NOT NULL AND h.addnum2 IS NULL
                                    THEN prepare_house(concat(h.housenum, ahtp1.shortname, h.addnum1))::character varying::text
                                WHEN h.addnum1 IS NULL AND h.addnum2 IS NOT NULL
                                    THEN prepare_house(concat(h.housenum, ahtp2.shortname, h.addnum2))::character varying::text
                                ELSE prepare_house(h.housenum::text)
                                END AS name,
                            lower(htp.name::text) AS typename,
                            lower(replace(htp.shortname::text, '.'::text, ''::text)) AS stypename,
                            ap.level1,
                            ap.level2,
                            ap.level3,
                            ap.level4,
                            ap.level5,
                            ap.level6,
                            ap.level7,
                            ap.level8,
                            h.objectguid,
                            greatest(h.changed_at, hr.changed_at, ap.changed_at) AS changed_at
            FROM fias_gar_houses h
                     JOIN fias_gar_admhierarchy hr ON hr.isactive = 1 AND hr.objectid = h.objectid
                     LEFT JOIN fias_gar_addhousetypes ahtp1 ON ahtp1.id = h.addtype1::numeric
                     LEFT JOIN fias_gar_addhousetypes ahtp2 ON ahtp2.id = h.addtype2::numeric
                     LEFT JOIN fias_gar_housetypes htp ON htp.id = h.housetype::numeric
                     LEFT JOIN v_addrobj_plain_adm ap ON ap.objectid = hr.parentobjid
            WHERE h.isactive = 1
              AND h.isactual = 1
            UNION
            SELECT DISTINCT h.objectid,
                            CASE
                                WHEN hr.parentobjid IS NULL THEN 0::numeric
                                ELSE hr.parentobjid
                                END AS parentid,
                            parent_level(hr.parentobjid, ap.level1, ap.level2, ap.level3, ap.level4, ap.level5, ap.level6, ap.level7, ap.level8) AS parentlevel,
                            CASE
                                WHEN h.addnum1 IS NOT NULL AND h.addnum2 IS NOT NULL THEN prepare_house(concat(h.housenum, ahtp1.shortname, h.addnum1, ahtp2.shortname, h.addnum2))::character varying::text
                                WHEN h.addnum1 IS NOT NULL AND h.addnum2 IS NULL
                                    THEN prepare_house(concat(h.housenum, ahtp1.shortname, h.addnum1))::character varying::text
                                WHEN h.addnum1 IS NULL AND h.addnum2 IS NOT NULL
                                    THEN prepare_house(concat(h.housenum, ahtp2.shortname, h.addnum2))::character varying::text
                                ELSE prepare_house(h.housenum::text)
                                END AS name,
                            lower(htp.name::text) AS typename,
                            lower(replace(htp.shortname::text, '.'::text, ''::text)) AS stypename,
                            ap.level1,
                            ap.level2,
                            ap.level3,
                            ap.level4,
                            ap.level5,
                            ap.level6,
                            ap.level7,
                            ap.level8,
                            h.objectguid,
                            greatest(h.changed_at, hr.changed_at, ap.changed_at) AS changed_at
            FROM fias_gar_houses h
                     JOIN fias_gar_munhierarchy hr ON hr.isactive = 1 AND hr.objectid = h.objectid
                     LEFT JOIN fias_gar_addhousetypes ahtp1 ON ahtp1.id = h.addtype1::numeric
                     LEFT JOIN fias_gar_addhousetypes ahtp2 ON ahtp2.id = h.addtype2::numeric
                     LEFT JOIN fias_gar_housetypes htp ON htp.id = h.housetype::numeric
                     LEFT JOIN v_addrobj_plain_mun ap ON ap.objectid = hr.parentobjid
            WHERE h.isactive = 1
              AND h.isactual = 1
            WITH NO DATA
        SQL);
        $this->addSql('create index v_search_houses__objectid_ind on v_search_houses (objectid)');
        $this->addSql('create index v_search_houses__changed_at_ind on v_search_houses (changed_at)');

        $this->addSql(<<<SQL
            create materialized view v_search_addrobjects as
            SELECT va.aolevel,
                   va.objectid,
                   CASE
                       WHEN va.parentobjid IS NULL THEN 0::numeric
                       ELSE va.parentobjid
                       END AS parentid,
                   parent_level(va.parentobjid, vp.level1, vp.level2, vp.level3, vp.level4, vp.level5, vp.level6, vp.level7, vp.level8) AS parentlevel,
                   vp.level1,
                   vp.level2,
                   vp.level3,
                   vp.level4,
                   vp.level5,
                   vp.level6,
                   vp.level7,
                   vp.level8,
                   unnest(prepare_array(va.aolevel, va.formalname::text, VARIADIC ARRAY [va.socrname, va.shortname::text, atp.name::text])) AS name,
                   va.socrname AS typename,
                   va.aoguid AS guid,
                   greatest(va.changed_at, vp.changed_at, atp.updated_at) AS changed_at
            FROM v_addrobj_adm va
                     LEFT JOIN v_addrobj_plain_adm vp ON vp.objectid = va.objectid
                     LEFT JOIN ext_addrobj_types atp ON atp.type_id::numeric = va.typeid
            UNION
            SELECT va.aolevel,
                   va.objectid,
                   CASE
                       WHEN va.parentobjid IS NULL THEN 0::numeric
                       ELSE va.parentobjid
                       END AS parentid,
                   parent_level(va.parentobjid, vp.level1, vp.level2, vp.level3, vp.level4, vp.level5, vp.level6, vp.level7, vp.level8) AS parentlevel,
                   vp.level1,
                   vp.level2,
                   vp.level3,
                   vp.level4,
                   vp.level5,
                   vp.level6,
                   vp.level7,
                   vp.level8,
                   unnest(prepare_array(va.aolevel, va.formalname::text, VARIADIC ARRAY [va.socrname, va.shortname::text, atp.name::text])) AS name,
                   va.socrname AS typename,
                   va.aoguid AS guid,
                   greatest(va.changed_at, vp.changed_at, atp.updated_at) AS changed_at
            FROM v_addrobj_mun va
                     LEFT JOIN v_addrobj_plain_mun vp ON vp.objectid = va.objectid
                     LEFT JOIN ext_addrobj_types atp ON atp.type_id::numeric = va.typeid
            UNION
            SELECT va.aolevel,
                   va.objectid,
                   CASE
                       WHEN va.parentobjid IS NULL THEN 0::numeric
                       ELSE va.parentobjid
                       END AS parentid,
                   parent_level(va.parentobjid, vp.level1, vp.level2, vp.level3, vp.level4, vp.level5, vp.level6, vp.level7, vp.level8) AS parentlevel,
                   vp.level1,
                   vp.level2,
                   vp.level3,
                   vp.level4,
                   vp.level5,
                   vp.level6,
                   vp.level7,
                   vp.level8,
                   prepare(s.name::text) AS name,
                   va.socrname AS typename,
                   va.aoguid AS guid,
                   greatest(s.updated_at, va.changed_at, vp.changed_at) AS changed_at
            FROM ext_addrobj_synonym s
                     JOIN v_addrobj_adm va ON va.objectid = s.objectid
                     LEFT JOIN v_addrobj_plain_adm vp ON vp.objectid = va.objectid
            UNION
            SELECT va.aolevel,
                   va.objectid,
                   CASE
                       WHEN va.parentobjid IS NULL THEN 0::numeric
                       ELSE va.parentobjid
                       END AS parentid,
                   parent_level(va.parentobjid, vp.level1, vp.level2, vp.level3, vp.level4, vp.level5, vp.level6, vp.level7, vp.level8) AS parentlevel,
                   vp.level1,
                   vp.level2,
                   vp.level3,
                   vp.level4,
                   vp.level5,
                   vp.level6,
                   vp.level7,
                   vp.level8,
                   prepare(s.name::text) AS name,
                   va.socrname AS typename,
                   va.aoguid AS guid,
                   greatest(s.updated_at, va.changed_at, vp.changed_at) AS changed_at
            FROM ext_addrobj_synonym s
                     JOIN v_addrobj_mun va ON va.objectid = s.objectid
                     LEFT JOIN v_addrobj_plain_mun vp ON vp.objectid = va.objectid
            WITH NO DATA
        SQL);
        $this->addSql('create index v_search_addrobjects__aolevel_ind on v_search_addrobjects (aolevel)');
        $this->addSql('create index v_search_addrobjects__objectid_ind on v_search_addrobjects (objectid)');
        $this->addSql('create index v_search_addrobjects__changed_at_ind on v_search_addrobjects (changed_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop index v_search_addrobjects__aolevel_ind');
        $this->addSql('drop index v_search_addrobjects__objectid_ind');
        $this->addSql('drop index v_search_addrobjects__changed_at_ind');
        $this->addSql('drop materialized view v_search_addrobjects');

        $this->addSql('drop index v_search_houses__objectid_ind');
        $this->addSql('drop index v_search_houses__changed_at_ind');
        $this->addSql('drop materialized view v_search_houses');
    }
}
