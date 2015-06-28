-- Created by Vertabelo (http://vertabelo.com)
-- Last modification date: 2015-06-08 14:11:25.047




-- tables
-- Table t_adherent
CREATE TABLE t_adherent (
    adh_id int    NOT NULL  AUTO_INCREMENT,
    adh_civilite varchar(5)    NOT NULL ,
    adh_nom varchar(255)    NOT NULL ,
    adh_prenom varchar(255)    NOT NULL ,
    adh_groupe int    NOT NULL ,
    adh_adresse varchar(800)    NOT NULL ,
    adh_codepostal int    NOT NULL ,
    adh_ville varchar(255)    NOT NULL ,
    adh_departement varchar(255)    NOT NULL ,
    adh_email varchar(255)    NOT NULL ,
    adh_phone varchar(30)    NOT NULL ,
    adh_mobile varchar(30)    NOT NULL ,
    adh_datenaiss date    NOT NULL ,
    adh_datecreation datetime    NOT NULL ,
    int_user_auteur int    NOT NULL ,
    adh_datelastmod datetime    NOT NULL ,
    int_user_lastmodauteur int    NOT NULL ,
    CONSTRAINT t_adherent_pk PRIMARY KEY (adh_id)
);

-- Table t_adherent_cotisation
CREATE TABLE t_adherent_cotisation (
    cot_id int    NOT NULL  AUTO_INCREMENT,
    cot_date datetime    NOT NULL ,
    cot_adh int    NOT NULL ,
    cot_montant int    NOT NULL ,
    int_user_auteur int    NOT NULL ,
    CONSTRAINT t_adherent_cotisation_pk PRIMARY KEY (cot_id)
);

-- Table t_adherent_groupe
CREATE TABLE t_adherent_groupe (
    adh_type_id int    NOT NULL  AUTO_INCREMENT,
    adh_type_libelle varchar(255)    NOT NULL ,
    adh_type_cotisation varchar(255)    NOT NULL ,
    CONSTRAINT t_adherent_groupe_pk PRIMARY KEY (adh_type_id)
);

-- Table t_config
CREATE TABLE t_config (
    id int    NOT NULL  AUTO_INCREMENT,
    duree_cotisation_jours int    NOT NULL ,
    CONSTRAINT t_config_pk PRIMARY KEY (id)
);

-- Table t_user
CREATE TABLE t_user (
    usr_id int    NOT NULL  AUTO_INCREMENT,
    usr_name varchar(50)    NOT NULL ,
    usr_password varchar(88)    NOT NULL ,
    usr_salt varchar(23)    NOT NULL ,
    usr_role varchar(50)    NOT NULL ,
    usr_prenom varchar(50)    NOT NULL ,
    usr_nom varchar(50)    NOT NULL ,
    usr_isadh bool    NOT NULL ,
    usr_idadh int    NOT NULL ,
    CONSTRAINT t_user_pk PRIMARY KEY (usr_id)
);





-- foreign keys
-- Reference:  t_adh_cotisation_t_adherent (table: t_adherent_cotisation)


ALTER TABLE t_adherent_cotisation ADD CONSTRAINT t_adh_cotisation_t_adherent FOREIGN KEY t_adh_cotisation_t_adherent (cot_adh)
    REFERENCES t_adherent (adh_id);
-- Reference:  t_adh_cotisation_t_user (table: t_adherent_cotisation)


ALTER TABLE t_adherent_cotisation ADD CONSTRAINT t_adh_cotisation_t_user FOREIGN KEY t_adh_cotisation_t_user (int_user_auteur)
    REFERENCES t_user (usr_id);
-- Reference:  t_adherent_type_t_adherent (table: t_adherent)


ALTER TABLE t_adherent ADD CONSTRAINT t_adherent_type_t_adherent FOREIGN KEY t_adherent_type_t_adherent (adh_groupe)
    REFERENCES t_adherent_groupe (adh_type_id);
-- Reference:  t_user_t_adherent_1 (table: t_adherent)


ALTER TABLE t_adherent ADD CONSTRAINT t_user_t_adherent_1 FOREIGN KEY t_user_t_adherent_1 (int_user_auteur)
    REFERENCES t_user (usr_id);
-- Reference:  t_user_t_adherent_2 (table: t_adherent)


ALTER TABLE t_adherent ADD CONSTRAINT t_user_t_adherent_2 FOREIGN KEY t_user_t_adherent_2 (int_user_lastmodauteur)
    REFERENCES t_user (usr_id);



-- End of file.

