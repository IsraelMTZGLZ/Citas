Create database Citas;

use Citas;

create table tb_Person(idPerson int primary key auto_increment,
  name_Person varchar(100)not null,
  surname_Person varchar(100) not null,
  gender_Person enum('M','F') not null,
  tel_Person varchar(150) not null,
  cd_Person timestamp default current_timestamp,
  lu_Person timestamp default current_timestamp
);

insert into tb_Person(name_Person,surname_Person,gender_Person,tel_Person) values('Israel','Martinez','M','44231231232');
  insert into tb_Person(name_Person,surname_Person,gender_Person,tel_Person) values('Beto','Alpanza','M','441292649');
    insert into tb_Person(name_Person,surname_Person,gender_Person,tel_Person) values('Rafa','Tero','M','442292649');
      insert into tb_Person(name_Person,surname_Person,gender_Person,tel_Person) values('Lik','Andro','M','442492649');
        insert into tb_Person(name_Person,surname_Person,gender_Person,tel_Person) values('Chu','Tetas','M','442492449');


create table tb_Users(idUser int primary key auto_increment,
  email_User varchar(150) not null unique,
  password_User varchar(150) default '$2a$10$BPFaoqQ7pdl06vx7D39rHuXLlow1dhQsVt502y7hEgAho9NmI0woi',
  status_User enum('Active','Inactive','Pending','Blocked') default 'Pending',
  address_User text not null,
  type_User enum('Admin','Doctor','Collection','Patient','Reception'),
  fk_Person int,
  cd_User timestamp default current_timestamp,
  lu_User timestamp default current_timestamp on update current_timestamp
);
alter table tb_Users add FOREIGN KEY (fk_Person) references tb_Person(idPerson)on update cascade on delete cascade;
insert into  tb_Users(email_User,password_User,address_User,type_User,status_User,fk_Person) values('israel@gmail.com','$2a$10$hGCOm1Od4zdNSF7NbNDzruwEJTVaIWNVRnRxLqo8wc8dza.p.nRTq','ToliYork','Admin','Active',3);
  insert into  tb_Users(email_User,password_User,address_User,type_User,status_User,fk_Person) values('beto@gmail.com','$2a$10$hGCOm1Od4zdNSF7NbNDzruwEJTVaIWNVRnRxLqo8wc8dza.p.nRTq','ToliYork','Doctor','Active',4);
    insert into  tb_Users(email_User,password_User,address_User,type_User,status_User,fk_Person) values('rafa@gmail.com','$2a$10$hGCOm1Od4zdNSF7NbNDzruwEJTVaIWNVRnRxLqo8wc8dza.p.nRTq','ToliYork','Collection','Active',5);
      insert into  tb_Users(email_User,password_User,address_User,type_User,status_User,fk_Person) values('lika@gmail.com','$2a$10$hGCOm1Od4zdNSF7NbNDzruwEJTVaIWNVRnRxLqo8wc8dza.p.nRTq','BlackMonth','Patient','Active',6);
        insert into  tb_Users(email_User,password_User,address_User,type_User,status_User,fk_Person) values('chucho@gmail.com','$2a$10$hGCOm1Od4zdNSF7NbNDzruwEJTVaIWNVRnRxLqo8wc8dza.p.nRTq','BlackMonth','Reception','Active',7);



create table tb_Speciality(idSpeciality int primary key auto_increment,
  name_Speciality varchar(100)not null,
  description_Speciality text,
  status_Speciality enum('Active','Inactive','Pending') default 'Pending',
  cd_Speciality timestamp default current_timestamp,
  lu_Speciality timestamp default current_timestamp on update current_timestamp
);
insert into tb_Speciality(name_Speciality,description_Speciality,status_Speciality) values('Traumatología','To fix something','Active');


create table tb_Doctor(idDoctor int primary key auto_increment,
  status_Doctor enum('Available','Appointment','Resting')DEFAULT 'Available',
  professionalId_Doctor varchar(100) not null,
  fk_Specialities int,
  fk_Person int,
  cd_Doctor timestamp default current_timestamp,
  lu_Doctor timestamp default current_timestamp on update current_timestamp
);

alter table tb_Doctor add FOREIGN KEY (fk_Person) references tb_Person(idPerson)on update cascade on delete cascade;
alter table tb_Doctor add FOREIGN KEY (fk_Specialities) references tb_Speciality(idSpeciality)on update cascade on delete cascade;
insert into tb_Doctor(professionalId_Doctor,fk_Specialities,fk_Person) values('12312212',1,4);


create table tb_Collection(idCollection int primary key auto_increment,
  enrollment_Collection varchar(100) not null unique,
  status_Collection enum('Open','Closed') default 'Open',
  cd_Collection timestamp default current_timestamp,
  lu_Collection timestamp default current_timestamp on update current_timestamp,
  fk_Person int
);
alter table tb_Collection add FOREIGN KEY (fk_Person) references tb_Person(idPerson)on update cascade on delete cascade;
insert into tb_Collection(enrollment_Collection,status_Collection,fk_Person) values('1212B','Open',5);



create table tb_Patient(idPatient INT PRIMARY KEY AUTO_INCREMENT,
  enrollment_Patient varchar(100)not null,
  status_Patient enum('Appointment','NoDate','Payment','Available','NoAvailable') default 'NoDate',
  cd_Patient timestamp default current_timestamp,
  lu_Patient timestamp default current_timestamp on update current_timestamp,
  fk_Person int
);

alter table tb_Patient add FOREIGN KEY (fk_Person) references tb_Person(idPerson)on update cascade on delete cascade;
insert into tb_Patient(enrollment_Patient,status_Patient,fk_Person) values('1314H','NoDate',6);


create table tb_Reception(idReception int primary key auto_increment,
  enrollment_Reception varchar(100)not null,
  cd_Reception timestamp default current_timestamp,
  lu_Reception timestamp default current_timestamp on update current_timestamp,
  fk_Person int
);
alter table tb_Reception add FOREIGN KEY (fk_Person) references tb_Person(idPerson)on update cascade on delete cascade;
insert into tb_Reception(enrollment_Reception,fk_Person) values('1314P',7);


create or replace view vw_Admin AS SELECT idPerson as id,
name_Person as name,
surname_Person as lastname,
status_User as status,
password_User as password,
type_User as type,
tel_Person as tel,
gender_Person as gender,
email_User as email,
address_User as address
from (tb_Users join tb_Person) where ((`tb_Users`.`fk_Person` = `tb_Person`.`idPerson`) and (`tb_Users`.`type_User`= 'Admin'));

create or replace view vw_Doctor AS SELECT idPerson as id,
name_Person as name,
surname_Person as lastname,
status_User as status,
password_User as password,
type_User as type,
tel_Person as tel,
gender_Person as gender,
email_User as email,
address_User as address,
status_Doctor as statusDoc,
professionalId_Doctor as professionalId,
idDoctor as idDoctor
from (tb_Users join tb_Person join tb_Doctor) where ((`tb_Users`.`fk_Person` = `tb_Person`.`idPerson`) and (`tb_Users`.`type_User`= 'Doctor') and (`tb_Doctor`.`fk_Person` = `tb_Person`.`idPerson`));

create or replace view vw_Collection AS SELECT idPerson as id,
name_Person as name,
surname_Person as lastname,
status_User as status,
password_User as password,
type_User as type,
tel_Person as tel,
gender_Person as gender,
email_User as email,
address_User as address,
status_Collection as status_Collection,
enrollment_Collection as enrollment_Collection
from (tb_Users join tb_Person join tb_Collection) where ((`tb_Users`.`fk_Person` = `tb_Person`.`idPerson`) and (`tb_Users`.`type_User`= 'Collection') and (`tb_Collection`.`fk_Person` = `tb_Person`.`idPerson`));

create or replace view vw_Patient AS SELECT idPerson as id,
name_Person as name,
surname_Person as lastname,
status_User as status,
password_User as password,
type_User as type,
tel_Person as tel,
gender_Person as gender,
email_User as email,
address_User as address,
enrollment_Patient as enrollment_Patient,
status_Patient as status_Patient
from (tb_Users join tb_Person join tb_Patient) where ((`tb_Users`.`fk_Person` = `tb_Person`.`idPerson`) and (`tb_Users`.`type_User`= 'Patient') and (`tb_Patient`.`fk_Person` = `tb_Person`.`idPerson`));

create or replace view vw_Reception AS SELECT idPerson as id,
name_Person as name,
surname_Person as lastname,
status_User as status,
password_User as password,
type_User as type,
tel_Person as tel,
gender_Person as gender,
email_User as email,
address_User as address,
enrollment_Reception
from (tb_Users join tb_Person join tb_Reception) where ((`tb_Users`.`fk_Person` = `tb_Person`.`idPerson`) and (`tb_Users`.`type_User`= 'Reception') and (`tb_Reception`.`fk_Person` = `tb_Person`.`idPerson`));



create table tb_Report(idReport int primary key auto_increment,
name_Report varchar(100) not null,
description_Report text,
status_Report enum('Active','Inactive') default 'Active',
fk_Person int,
cd_Report timestamp default current_timestamp,
lu_Report timestamp default current_timestamp on update current_timestamp
);

alter table tb_Report add FOREIGN KEY (fk_Person) references tb_Person(idPerson)on update cascade on delete cascade;

create table tb_Day(idDay int primary key auto_increment,
name_Day varchar(50) not null,
status_Day enum('Active','INactive') default 'Active',
cd_Day timestamp default current_timestamp,
lu_Day timestamp default current_timestamp on update current_timestamp
);

create table tb_CitaPatient(idCitaPatient int primary key auto_increment,
hour_CitaPatient date not null,
status_CitaPatient enum('Active','Inactive','InConsultation','Here','NotHere','Pending') default 'Active',
description_CitaPatient text,
fk_Doctor int,
fk_Day int,
fk_Pacient int,
cd_CitaPatient timestamp default current_timestamp,
lu_CitaPatient timestamp default current_timestamp on update current_timestamp
);

alter table tb_CitaPatient add FOREIGN KEY (fk_Doctor) references tb_Doctor(idDoctor)on update cascade on delete cascade;
alter table tb_CitaPatient add FOREIGN KEY (fk_Day) references tb_Day(idDay)on update cascade on delete cascade;
alter table tb_CitaPatient add FOREIGN KEY (fk_Pacient) references tb_Patient(idPatient)on update cascade on delete cascade;

create table tb_CaseFile(idCaseFile int primary key auto_increment,
weight_CaseFile decimal not null,
height_CaseFile decimal not null,
bloodPreassure_CaseFile varchar(100) not null,
bloodType_CaseFile varchar(20) not null,
status_CaseFile enum('Active','Inactive') default 'Active',
fk_Patient int,
cd_CaseFile timestamp default current_timestamp,
lu_CaseFile timestamp default current_timestamp on update current_timestamp
);
alter table tb_CaseFile add FOREIGN KEY (fk_Patient) references tb_Patient(idPatient)on update cascade on delete cascade;

create table tb_Recipe(idRecipe int primary key auto_increment,
description_Recipe text not null,
nota_Recipe text not null,
status_Recipe enum('Active','Inactive') default 'Active',
fk_Patient int,
cd_Recipe timestamp default current_timestamp,
lu_Recipe timestamp default current_timestamp on update current_timestamp
);
alter table tb_Recipe add FOREIGN KEY (fk_Patient) references tb_Patient(idPatient)on update cascade on delete cascade;

create table tb_Services(idService int primary key auto_increment,
name_Service varchar(100) not null,
description_Service text not null,
status_Service enum('Active','Inactive') default 'Active',
cd_Service timestamp default current_timestamp,
lu_Service timestamp default current_timestamp on update current_timestamp
);

create table tb_PayPatient(idPayPatient int primary key auto_increment,
subtotal decimal not null,
Iva decimal not null,
Total decimal not null,
status_PayPatient enum('Pending','Paid','Cnceled')default 'Pending',
fk_Recipe int,
fk_Service int,
fk_Patient int,
cd_PayPatient timestamp default current_timestamp,
lu_PayPatinet timestamp default current_timestamp on update current_timestamp
);
alter table tb_PayPatient add FOREIGN KEY (fk_Patient) references tb_Patient(idPatient)on update cascade on delete cascade;
alter table tb_PayPatient add FOREIGN KEY (fk_Service) references tb_Services(idService)on update cascade on delete cascade;
alter table tb_PayPatient add FOREIGN KEY (fk_Recipe) references tb_Recipe(idRecipe)on update cascade on delete cascade;



create table tb_History(idHistory int primary key auto_increment,
fk_CitaPatient int,
fk_Patient int,
cd_History timestamp default current_timestamp,
lu_History timestamp default current_timestamp on update current_timestamp
);

alter table tb_History add FOREIGN KEY (fk_Patient) references tb_Patient(idPatient)on update cascade on delete cascade;
alter table tb_History add FOREIGN KEY (fk_CitaPatient) references tb_CitaPatient(idCitaPatient)on update cascade on delete cascade;


create table tb_DayOf(idDayOf int primary key auto_increment,
date_DayOf timestamp,
description_DayOf text,
cd_DayOf timestamp default current_timestamp,
lu_DayOf timestamp default current_timestamp on update current_timestamp
);


create table user(id INTEGER NOT NULL PRIMARY KEY,
  first_name VARCHAR(30) NOT NULL,
  last_name VARCHAR(30) NOT NULL,
  income FLOAT NOT NULL);
   select count(*) from user;


insert into user(id,first_name,last_name,income) value (1,"Isra","Mtz",2701);
  insert into user(id,first_name,last_name,income) value (2,"Beto","Mtz",2701);
  select table_name,table_rows from information_schema.tables where table_schema='x';
create table user_payments(idP INTEGER NOT NULL PRIMARY KEY,
user_id INTEGER NOT NULL,
amount FLOAT);
Alter table user_payments add FOREIGN key(user_id) references user(id);

insert into user_payments(idP,user_id,amount) values (1,1,2);
  insert into user_payments(idP,user_id,amount) values (2,2,3);
    insert into user_payments(idP,user_id,amount) values (3,1,4);
      insert into user_payments(idP,user_id,amount) values (4,2,0);
        insert into user_payments(idP,user_id,amount) values (5,1,0);
            insert into user_payments(idP,user_id,amount) values (6,2,1);

select * from user_payments where amount>=1 and
Compradores (id,nombre,apellido,email) Compras (id,id_comprador,articulo,cantidad)
user (id,first_name,last_name) user_payments (id,user_id,amount)
SELECT
    Compradores.nombre,
    Compras.*
FROM
    Compradores
    INNER JOIN Compras ON(Compradores.id = Compras.id_comprador)
ORDER BY
    Compradores.nombre ASC

SELECT
    user.first_name,
    user_payments.*
FROM
    user
    INNER JOIN user_payments ON(user.id = user_payments.user_id)
ORDER BY
    user.first_name ASC

    SELECT id, first_name FROM user
    UNION
    SELECT user_id, amount FROM user_payments

SELECT nombreCliente, idPedido, fechaPedido
FROM CLIENTE INNER JOIN PEDIDO
ON cliente.idCliente = pedido.idCliente

select * from user inner join user_payments on user.id=user_payments.idP;

create or replace view vw_A AS SELECT id as id,
first_name as first_name,
last_name as last_name,
income as income,
amount as amount
from (user join user_payments) where ((`user_payments`.`user_id` = `user`.`id`));




WITH datos AS
(
select distinct is as 'id', modelo as 'Modelo', COUNT(amount) as 'Cantidad'
from user_payments
where modelo like '%Blade%'
group by Marca,Modelo
union
select distinct marca as 'Marca', modelo as 'Modelo', COUNT(modelo) as 'Cantidad'
from inventariogc
where modelo like '%Blade%'
group by Marca,Modelo
)
select marca, modelo, sum(Cantidad)
from datos
group by Marca,Modelo

-- TABLE user
-- id INTEGER NOT NULL PRIMARY KEY
-- first_name VARCHAR(30) NOT NULL
-- last_name VARCHAR(30) NOT NULL
-- income FLOAT NOT NULL
--
-- TABLE user_payments
-- id INTEGER NOT NULL PRIMARY KEY
-- user_id INTEGER NOT NULL
-- amount FLOAT
