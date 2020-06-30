<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610110105 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('ALTER TABLE review ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_794381C6979B1AD6 ON review (company_id)');
        $this->addSql('ALTER TABLE stock ADD company_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656604584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_4B365660979B1AD6 ON stock (company_id)');
        $this->addSql('CREATE INDEX IDX_4B3656604584665A ON stock (product_id)');
        $this->addSql('ALTER TABLE ordered ADD company_id INT NOT NULL, ADD drive_id INT NOT NULL');
        $this->addSql('ALTER TABLE ordered ADD CONSTRAINT FK_C3121F99979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE ordered ADD CONSTRAINT FK_C3121F9986E5E0C4 FOREIGN KEY (drive_id) REFERENCES drive (id)');
        $this->addSql('CREATE INDEX IDX_C3121F99979B1AD6 ON ordered (company_id)');
        $this->addSql('CREATE INDEX IDX_C3121F9986E5E0C4 ON ordered (drive_id)');
        $this->addSql('ALTER TABLE drive ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE drive ADD CONSTRAINT FK_681DF58FF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_681DF58FF5B7AF75 ON drive (address_id)');
        $this->addSql('ALTER TABLE detail ADD stock_id INT NOT NULL, ADD ordered_id INT NOT NULL');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F93DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE detail ADD CONSTRAINT FK_2E067F93AA60395A FOREIGN KEY (ordered_id) REFERENCES ordered (id)');
        $this->addSql('CREATE INDEX IDX_2E067F93DCD6110 ON detail (stock_id)');
        $this->addSql('CREATE INDEX IDX_2E067F93AA60395A ON detail (ordered_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE detail DROP FOREIGN KEY FK_2E067F93DCD6110');
        $this->addSql('ALTER TABLE detail DROP FOREIGN KEY FK_2E067F93AA60395A');
        $this->addSql('DROP INDEX IDX_2E067F93DCD6110 ON detail');
        $this->addSql('DROP INDEX IDX_2E067F93AA60395A ON detail');
        $this->addSql('ALTER TABLE detail DROP stock_id, DROP ordered_id');
        $this->addSql('ALTER TABLE drive DROP FOREIGN KEY FK_681DF58FF5B7AF75');
        $this->addSql('DROP INDEX UNIQ_681DF58FF5B7AF75 ON drive');
        $this->addSql('ALTER TABLE drive DROP address_id');
        $this->addSql('ALTER TABLE ordered DROP FOREIGN KEY FK_C3121F99979B1AD6');
        $this->addSql('ALTER TABLE ordered DROP FOREIGN KEY FK_C3121F9986E5E0C4');
        $this->addSql('DROP INDEX IDX_C3121F99979B1AD6 ON ordered');
        $this->addSql('DROP INDEX IDX_C3121F9986E5E0C4 ON ordered');
        $this->addSql('ALTER TABLE ordered DROP company_id, DROP drive_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2 ON product');
        $this->addSql('ALTER TABLE product DROP category_id');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6979B1AD6');
        $this->addSql('DROP INDEX IDX_794381C6979B1AD6 ON review');
        $this->addSql('ALTER TABLE review DROP company_id');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660979B1AD6');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656604584665A');
        $this->addSql('DROP INDEX IDX_4B365660979B1AD6 ON stock');
        $this->addSql('DROP INDEX IDX_4B3656604584665A ON stock');
        $this->addSql('ALTER TABLE stock DROP company_id, DROP product_id');
    }
}
