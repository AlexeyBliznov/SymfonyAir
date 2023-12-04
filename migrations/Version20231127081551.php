<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127081551 extends AbstractMigration
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
        $this->addSql('CREATE TABLE "admin" (id INT NOT NULL, name VARCHAR(180) DEFAULT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76E7927C74 ON "admin" (email)');
        $this->addSql('CREATE TABLE airplane (id INT NOT NULL, model VARCHAR(50) NOT NULL, production_year TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, maintenance_schedule VARCHAR(255) NOT NULL, next_maintenance TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN airplane.production_year IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN airplane.maintenance_schedule IS \'(DC2Type:dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN airplane.next_maintenance IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE flight (id INT NOT NULL, airplane_id INT DEFAULT NULL, point_of_departure VARCHAR(100) NOT NULL, arrival_point VARCHAR(100) NOT NULL, departure_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, arrival_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C257E60E996E853C ON flight (airplane_id)');
        $this->addSql('COMMENT ON COLUMN flight.departure_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flight.arrival_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE option (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE passenger (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, expires TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BEFE8DDE7927C74 ON passenger (email)');
        $this->addSql('COMMENT ON COLUMN passenger.expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sold_ticket (id INT NOT NULL, flight_id INT DEFAULT NULL, user_id INT DEFAULT NULL, ticket_number INT NOT NULL, seat_type VARCHAR(255) NOT NULL, seat_number VARCHAR(5) NOT NULL, baggage VARCHAR(255) NOT NULL, discount VARCHAR(255) DEFAULT NULL, boarding_confirm BOOLEAN NOT NULL, check_in BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31A2934CECD2759F ON sold_ticket (ticket_number)');
        $this->addSql('CREATE INDEX IDX_31A2934C91F478C5 ON sold_ticket (flight_id)');
        $this->addSql('CREATE INDEX IDX_31A2934CA76ED395 ON sold_ticket (user_id)');
        $this->addSql('CREATE TABLE sold_ticket_option (sold_ticket_id INT NOT NULL, option_id INT NOT NULL, PRIMARY KEY(sold_ticket_id, option_id))');
        $this->addSql('CREATE INDEX IDX_F99CCFE17D75AAB8 ON sold_ticket_option (sold_ticket_id)');
        $this->addSql('CREATE INDEX IDX_F99CCFE1A7C41D6F ON sold_ticket_option (option_id)');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, flight_id INT DEFAULT NULL, ticket_number INT NOT NULL, seat_type VARCHAR(255) NOT NULL, seat_number VARCHAR(5) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
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
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E996E853C FOREIGN KEY (airplane_id) REFERENCES airplane (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sold_ticket ADD CONSTRAINT FK_31A2934C91F478C5 FOREIGN KEY (flight_id) REFERENCES flight (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sold_ticket ADD CONSTRAINT FK_31A2934CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sold_ticket_option ADD CONSTRAINT FK_F99CCFE17D75AAB8 FOREIGN KEY (sold_ticket_id) REFERENCES sold_ticket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sold_ticket_option ADD CONSTRAINT FK_F99CCFE1A7C41D6F FOREIGN KEY (option_id) REFERENCES option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
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
        $this->addSql('ALTER TABLE flight DROP CONSTRAINT FK_C257E60E996E853C');
        $this->addSql('ALTER TABLE sold_ticket DROP CONSTRAINT FK_31A2934C91F478C5');
        $this->addSql('ALTER TABLE sold_ticket DROP CONSTRAINT FK_31A2934CA76ED395');
        $this->addSql('ALTER TABLE sold_ticket_option DROP CONSTRAINT FK_F99CCFE17D75AAB8');
        $this->addSql('ALTER TABLE sold_ticket_option DROP CONSTRAINT FK_F99CCFE1A7C41D6F');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA391F478C5');
        $this->addSql('DROP TABLE "admin"');
        $this->addSql('DROP TABLE airplane');
        $this->addSql('DROP TABLE flight');
        $this->addSql('DROP TABLE option');
        $this->addSql('DROP TABLE passenger');
        $this->addSql('DROP TABLE sold_ticket');
        $this->addSql('DROP TABLE sold_ticket_option');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
