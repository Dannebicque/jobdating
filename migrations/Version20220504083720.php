<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504083720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise DROP INDEX IDX_D19FA606C4A52F0, ADD UNIQUE INDEX UNIQ_D19FA606C4A52F0 (representant_id)');
        $this->addSql('ALTER TABLE offre CHANGE offre_pdf offre_pdf VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise DROP INDEX UNIQ_D19FA606C4A52F0, ADD INDEX IDX_D19FA606C4A52F0 (representant_id)');
        $this->addSql('ALTER TABLE offre CHANGE offre_pdf offre_pdf VARCHAR(50) DEFAULT NULL');
    }
}
