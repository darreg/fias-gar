<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220331205328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD main_role VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE users ADD new_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD join_confirm_token_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD join_confirm_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD password_reset_token_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD password_reset_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD email_change_token_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD email_change_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ALTER name_last DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN users.main_role IS \'(DC2Type:auth_user_main_role)\'');
        $this->addSql('COMMENT ON COLUMN users.new_email IS \'(DC2Type:auth_user_email)\'');
        $this->addSql('COMMENT ON COLUMN users.join_confirm_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.password_reset_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.email_change_token_expires IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP main_role');
        $this->addSql('ALTER TABLE users DROP new_email');
        $this->addSql('ALTER TABLE users DROP join_confirm_token_value');
        $this->addSql('ALTER TABLE users DROP join_confirm_token_expires');
        $this->addSql('ALTER TABLE users DROP password_reset_token_value');
        $this->addSql('ALTER TABLE users DROP password_reset_token_expires');
        $this->addSql('ALTER TABLE users DROP email_change_token_value');
        $this->addSql('ALTER TABLE users DROP email_change_token_expires');
        $this->addSql('ALTER TABLE users ALTER name_last SET NOT NULL');
    }
}
