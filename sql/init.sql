-- Create types
CREATE TYPE ticket_status AS ENUM ('reserved', 'purchased', 'cancelled', 'expired');
CREATE TYPE role_type AS ENUM ('user', 'client');

-- Create tables
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,

    name VARCHAR(100) NOT NULL,
    password_hash TEXT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role role_type NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS events (
    id SERIAL PRIMARY KEY,

    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url TEXT,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP,
    location VARCHAR(255),

    ticket_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00 CHECK (ticket_price >= 0),
    ticket_quantity INT NOT NULL DEFAULT 0 CHECK (ticket_quantity >= 0),

    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tickets (
    id SERIAL PRIMARY KEY,

    status ticket_status NOT NULL DEFAULT 'reserved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    client_id INT NOT NULL,
    event_id INT NOT NULL,

    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Trigger to check ticket availability before inserting a new ticket
CREATE OR REPLACE FUNCTION check_ticket_availability()
RETURNS TRIGGER AS $$
DECLARE
    total_quantity INT;
    current_tickets INT;
BEGIN
    SELECT ticket_quantity INTO total_quantity FROM events WHERE id = NEW.event_id FOR UPDATE;

    SELECT COUNT(*) INTO current_tickets FROM tickets
    WHERE event_id = NEW.event_id AND status IN ('reserved', 'purchased');

    IF current_tickets >= total_quantity THEN
        RAISE EXCEPTION 'Não há mais ingressos disponíveis para este evento.';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_insert_ticket
BEFORE INSERT ON tickets
FOR EACH ROW
EXECUTE FUNCTION check_ticket_availability();
