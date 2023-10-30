CREATE TABLE azienda (
    nome varchar(255) NOT NULL PRIMARY KEY
);

CREATE TABLE utente (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome varchar(255) NOT NULL,
    cognome varchar(255) NOT NULL,
    azienda varchar(255) NOT NULL FOREIGN KEY REFERENCES azienda(nome)
);

CREATE TABLE server (
    ip varchar(255) NOT NULL PRIMARY KEY
);

CREATE TABLE db (
    nome varchar(255) NOT NULL PRIMARY KEY
);

CREATE TABLE utenza_as (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_richiedente varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(nome),
    cognome_richiedente varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(cognome),
    nome_utenza varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(nome),
    cognome_utenza varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(cognome),
    azienda varchar(255) NOT NULL FOREIGN KEY REFERENCES azienda(nome),
    scadenza date,
    nome_server varchar(255) NOT NULL FOREIGN KEY REFERENCES server(nome),
    privilegi varchar(255)
);

CREATE TABLE utenza_db (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_richiedente varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(nome),
    cognome_richiedente varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(cognome),
    nome_utenza varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(nome),
    cognome_utenza varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(cognome),
    azienda varchar(255) NOT NULL FOREIGN KEY REFERENCES azienda(nome),
    scadenza date,
    nome_db varchar(255) NOT NULL FOREIGN KEY REFERENCES db(nome),
    db_schema varchar(255) NOT NULL,
    privilegi varchar(255)
);

CREATE TABLE safe_cyb (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_safe varchar(255) NOT NULL,
    nome_utenza varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(nome),
    cognome_utenza varchar(255) NOT NULL FOREIGN KEY REFERENCES utente(cognome),
    nome_server varchar(255) NOT NULL FOREIGN KEY REFERENCES server(nome),
    utenza_as varchar(255) NOT NULL,
    ticket_as varchar(255) NOT NULL,
    nome_db varchar(255) NOT NULL FOREIGN KEY REFERENCES db(nome),
    utenza_db varchar(255) NOT NULL,
    ticket_db varchar(255) NOT NULL
);
