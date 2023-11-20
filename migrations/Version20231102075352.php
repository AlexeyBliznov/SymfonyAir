<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102075352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "admin_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE airplane_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE flight_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE option_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE passenger_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "admin" (id INT NOT NULL, name VARCHAR(180) DEFAULT NULL, email VARCHAR(180) NOT NULL, status VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, confirm_token VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76E7927C74 ON "admin" (email)');
        $this->addSql('CREATE TABLE airplane (id INT NOT NULL, model VARCHAR(255) NOT NULL, production_year TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, maintenance_schedule VARCHAR(255) NOT NULL, next_maintenance TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN airplane.maintenance_schedule IS \'(DC2Type:dateinterval)\'');
        $this->addSql('CREATE TABLE flight (id INT NOT NULL, point_of_departure VARCHAR(255) NOT NULL, arrival_point VARCHAR(255) NOT NULL, plane_id INT NOT NULL, date VARCHAR(255) NOT NULL, departure_time VARCHAR(255) NOT NULL, arrival_time VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE option (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE passenger (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, expires TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BEFE8DDE7927C74 ON passenger (email)');
        $this->addSql('COMMENT ON COLUMN passenger.expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sold_ticket (id INT NOT NULL, ticket_number INT NOT NULL, flight VARCHAR(255) NOT NULL, passenger VARCHAR(255) NOT NULL, seat_type VARCHAR(255) NOT NULL, baggage VARCHAR(255) NOT NULL, options VARCHAR(255) DEFAULT NULL, discount VARCHAR(255) DEFAULT NULL, boarding_confirm BOOLEAN NOT NULL, check_in BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31A2934CECD2759F ON sold_ticket (ticket_number)');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, flight_id INT DEFAULT NULL, ticket_number INT NOT NULL, seat_type VARCHAR(255) NOT NULL, seat VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA3ECD2759F ON ticket (ticket_number)');
        $this->addSql('CREATE INDEX IDX_97A0ADA391F478C5 ON ticket (flight_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(180) DEFAULT NULL, email VARCHAR(180) NOT NULL, status VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, confirm_token VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA391F478C5 FOREIGN KEY (flight_id) REFERENCES flight (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "admin_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE airplane_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE flight_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE option_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE passenger_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA391F478C5');
        $this->addSql('DROP TABLE "admin"');
        $this->addSql('DROP TABLE airplane');
        $this->addSql('DROP TABLE flight');
        $this->addSql('DROP TABLE option');
        $this->addSql('DROP TABLE passenger');
        $this->addSql('DROP TABLE sold_ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
