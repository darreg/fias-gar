<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128171559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE imports ADD parse_task_num INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE imports ADD save_task_num INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE imports DROP task_num');
        $this->addSql('ALTER TABLE imports ALTER parse_error_num SET DEFAULT 0');
        $this->addSql('ALTER TABLE imports ALTER parse_success_num SET DEFAULT 0');
        $this->addSql('ALTER TABLE imports ALTER save_error_num SET DEFAULT 0');
        $this->addSql('ALTER TABLE imports ALTER save_success_num SET DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE imports ADD task_num INT NOT NULL');
        $this->addSql('ALTER TABLE imports DROP parse_task_num');
        $this->addSql('ALTER TABLE imports DROP save_task_num');
        $this->addSql('ALTER TABLE imports ALTER parse_error_num DROP DEFAULT');
        $this->addSql('ALTER TABLE imports ALTER parse_success_num DROP DEFAULT');
        $this->addSql('ALTER TABLE imports ALTER save_error_num DROP DEFAULT');
        $this->addSql('ALTER TABLE imports ALTER save_success_num DROP DEFAULT');
    }
}
