/*  **************************BASE DE DADES ESCAPE ROOM  */

drop table if exists users CASCADE; /* taula per als diferents tipus d'usuaris */
drop table if exists escaperoom CASCADE; /* taula que conté informació sobre les escaperoom */
drop table if exists rooms CASCADE; /* taula per a les sales de les diferents escape rooms */
drop table if exists parking CASCADE; /* taula que conté informació de parkings */
drop table if exists erxparking CASCADE; /* relaciona els escape rooms amb els parkings */
drop table if exists bookings CASCADE; /* taula per a les reserves */

CREATE TABLE USERS(
	CODUSER varchar(30) CONSTRAINT USERS_CODUSUARI_PK PRIMARY KEY,
	NAME varchar(15) CONSTRAINT USERS_NOMUSUARI_NN NOT NULL,
	SURNAME varchar(30) CONSTRAINT USERS_COGNOMS_NN NOT NULL ,
	USERNAME VARCHAR(15) CONSTRAINT USERS_USUARI_NN NOT NULL,
	EMAIL varchar(40) CONSTRAINT USERS_EMAIL_NN NOT NULL,
	ADMIN varchar(1) CONSTRAINT USERS_ADMIN_NN NOT NULL,
	PASSWORD varchar(32) CONSTRAINT USERS_PASS_NN NOT NULL,
	ZIP_CODE varchar(5)  CONSTRAINT USERS_CP_NN NOT NULL,
	PHONE_NUMBER varchar(9) CONSTRAINT USERS_TEL_NN NOT NULL,
	GENDER varchar(1)
);

CREATE TABLE ESCAPEROOM(
	CODER varchar(30) CONSTRAINT ER_CODER_PK PRIMARY KEY,
	NAME varchar(25) CONSTRAINT ER_NOMER_NN NOT NULL,
	ADDRESS varchar CONSTRAINT ER_ADDRESS_NN NOT NULL,
	DESCRIP varchar CONSTRAINT ER_DESCRIP_NN NOT NULL,
	MARK varchar(10) CONSTRAINT ER_VALORACION_NN NOT NULL,
	PRICE numeric(5, 2) CONSTRAINT ER_PRICE_NN NOT NULL,
	DURATION smallint CONSTRAINT ER_DURACION_NN NOT NULL,
	ADMINISTRATOR varchar,
	CONSTRAINT ESCAPEROOM_ADMINISTRATOR_FK FOREIGN KEY(ADMINISTRATOR) REFERENCES USERS(CODUSER) ON DELETE CASCADE	 
);

CREATE TABLE ROOMS (
	CODSALA varchar(30) CONSTRAINT ROOMS_CODSALA_PK PRIMARY KEY,
	CODER varchar(30),
	NAME varchar(25) CONSTRAINT ROOMS_NAME_NN NOT NULL,
	TOPIC varchar(25) CONSTRAINT ROOMS_TOPIC_NN NOT NULL,
	CAPACITY smallint,
	CONSTRAINT ROOMS_CODER_FK FOREIGN KEY(CODER) REFERENCES ESCAPEROOM(CODER)
);

CREATE TABLE PARKING(
	CODPARKING smallint CONSTRAINT PARKING_CODPARKING_PK PRIMARY KEY,
	NAME varchar(25) CONSTRAINT PARKING_NOMPARKING_NN NOT NULL,
	ADDRESS varchar(50) CONSTRAINT PARKING_ADDRESS_NN NOT NULL,
	LATITUDE numeric(5, 2) CONSTRAINT PARKING_LATITUD_NN NOT NULL,
	LONGITUDE numeric(5, 2) CONSTRAINT PARKING_LONGITUD_NN NOT NULL,
	PRICE numeric(5, 2) CONSTRAINT PARKING_PRICE_NN NOT NULL,
	PLACES smallint CONSTRAINT PARKING_PLAZAS_NN NOT NULL,
	ZIP_CODE varchar(5)
);

CREATE TABLE BOOKINGS(
	CODUSER varchar,
	CODER varchar(30),
	STARTDATE VARCHAR(10),
	START VARCHAR(5),
	FINISH VARCHAR(5),
	PEOPLE smallint,
	CODPARKING smallint,
	PRICE numeric(5, 2),
	CONSTRAINT RESERVA_CODUSUARI_FK FOREIGN KEY(CODUSER) REFERENCES USERS(CODUSER),
	CONSTRAINT RESERVA_CODER_FK FOREIGN KEY(CODER) REFERENCES ESCAPEROOM(CODER),
	CONSTRAINT RESERVA_CODPARKING_FK FOREIGN KEY(CODPARKING) REFERENCES PARKING(CODPARKING),
	CONSTRAINT RESERVA_SOCOFEP_PK PRIMARY KEY(CODUSER, CODER, STARTDATE)
);

CREATE TABLE ERXPARKING(
	CODER varchar(30),
	CODPARKING smallint,
	CONSTRAINT ERXPARKING_CODPARKING_FK FOREIGN KEY(CODPARKING) REFERENCES PARKING(CODPARKING),
	CONSTRAINT ERXPARKING_CODER_FK FOREIGN KEY(CODER) REFERENCES ESCAPEROOM(CODER)
);

INSERT INTO ERXPARKING(CODER, CODPARKING) VALUES 
('admin1.escape1', 1),
('admin1.escape1', 3),
('admin1.escape1', 4),
('admin1.escape1', 5),
('admin1.escape1', 6),
('admin1.escape1', 7),
('admin1.escape1', 8),
('admin1.escape1', 9),
('admin1.escape1', 10),
('admin2.escape2', 3),
('admin2.escape2', 2),
('admin2.escape2', 1),
('admin2.escape2', 3),
('admin2.escape2', 4);

INSERT INTO users (CODUSER,	NAME, SURNAME, USERNAME, EMAIL, ADMIN, PASSWORD, ZIP_CODE, PHONE_NUMBER, GENDER) VALUES
    ('admin1','admin1','admin1','admin1','admin1@admin.com','t',md5('123'),'12345','123456789','M'),
    ('admin2','admin2','admin2','admin2','admin2@admin.com','t',md5('123'),'12345','123456789','M'),
    ('admin3','admin3','admin3','admin3','admin3@admin.com','t',md5('123'),'12345','123456789','M'),
    ('admin4','admin4','admin4','admin4','admin4@admin.com','t',md5('123'),'12345','123456789','M'),
    ('admin5','admin5','admin5','admin5','admin5@admin.com','t',md5('123'),'12345','123456789','M'),
    ('admin6','admin6','admin6','admin6','admin6@admin.com','t',md5('123'),'12345','123456789','M'),
    ('user1','user1','user1','user1','user1@auser.com','f',md5('123'),'12345','123456789','M'),
    ('user2','user2','user2','user2','user2@auser.com','f',md5('123'),'12345','123456789','M'),
    ('user3','user3','user3','user3','user3@auser.com','f',md5('123'),'12345','123456789','M'),
    ('user4','user4','user4','user4','user4@auser.com','f',md5('123'),'12345','123456789','M'),
    ('user5','user5','user5','user5','user5@auser.com','f',md5('123'),'12345','123456789','M'),
    ('user6','user6','user6','user6','user6@auser.com','f',md5('123'),'12345','123456789','M');


INSERT INTO escaperoom (coder, name,address,descrip,mark,price,duration,administrator) VALUES
	('admin1.escape1','escape1','dire1','descrip1',1,10.00,3,'admin1'),
	('admin1.escape2','escape2','dire2','descrip2',1,20.00,2,'admin1'),
	('admin1.escape3','escape3','dire3','descrip3',1,30.00,1,'admin1'),
	('admin1.escape4','escape4','dire4','descrip4',1,40.00,3,'admin1'),
	('admin1.escape5','escape5','dire5','descrip5',1,50.00,3,'admin1'),
	('admin1.escape6','escape6','dire6','descrip6',1,60.00,3,'admin1'),
	('admin1.escape7','escape7','dire7','descrip7',1,10.00,3,'admin1'),
	('admin1.escape8','escape8','dire8','descrip8',1,20.00,2,'admin1'),
	('admin1.escape9','escape9','dire9','descrip9',1,30.00,3,'admin1'),
	('admin1.escape0','escape0','dire0','descrip0',1,10.00,2,'admin1'),
	('admin2.escapeA','escapeA','direA','descripA',1,10.00,3,'admin2'),
	('admin2.escapeB','escapeB','direB','descripB',1,20.00,2,'admin2'),
	('admin2.escapeC','escapeC','direC','descripC',1,30.00,1,'admin2'),
	('admin2.escapeD','escapeD','direD','descripD',1,40.00,3,'admin2'),
	('admin2.escapeF','escapeF','direF','descripF',1,50.00,3,'admin2'),
	('admin2.escapeG','escapeG','direG','descripG',1,60.00,3,'admin2'),
	('admin2.escapeH','escapeH','direH','descripH',1,10.00,3,'admin2'),
	('admin2.escapeI','escapeI','direI','descripI',1,20.00,2,'admin2'),
	('admin2.escapeJ','escapeJ','direJ','descripJ',1,30.00,3,'admin2'),
	('admin2.escapeK','escapeK','direK','descripK',1,10.00,2,'admin2');


INSERT INTO parking (codparking, name, address, latitude,longitude, price, places, zip_code) VALUES
	(0,'-','-',0,0,0, 0,'-'),
	('1','parking1','direccion1',25.1,12.12,12,300,'08750'),
	('2','parking2','direccion2',25.2,12.13,12,100,'08751'),
	('3','parking3','direccion3',25.3,12.14,12,200,'08752'),
	('4','parking4','direccion4',25.4,12.15,12,400,'08753'),
	('5','parking5','direccion5',25.5,12.16,12,150,'08754'),
	('6','parking6','direccion6',25.6,12.17,12,340,'08755'),
	('7','parking7','direccion7',25.7,12.18,12,220,'08756'),
	('8','parking8','direccion8',25.8,12.19,12,210,'08757'),
	('9','parking9','direccion9',25.9,12.20,12,210,'08758'),
	('10','parking10','direccion10',26,12.21,12,120,'08759'),
	('11','parking11','direccion11',26.1,12.22,12,120,'08760'),
	('12','parking12','direccion12',26.2,12.23,12,120,'08761'),
	('13','parking13','direccion13',26.3,12.24,12,140,'08762'),
	('14','parking14','direccion14',26.4,12.25,12,330,'08763'),
	('15','parking15','direccion15',26.5,12.26,12,640,'08764');