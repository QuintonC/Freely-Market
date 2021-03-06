CREATE TABLE Advertisements(
adid int(11) NOT NULL AUTO_INCREMENT,
username varchar(30) NOT NULL,
title varchar(30) NOT NULL,
description varchar(200) NOT NULL,
file longblob NOT NULL,
createdate date NULL,
expirdate date NOT NULL,
PRIMARY KEY (adid))
engine=innodb;

create table Bike_Transactions(
btid int NOT NULL AUTO_INCREMENT,
buyer varchar(30) NOT NULL,
seller varchar(30) NOT NULL,
occured datetime NOT NULL,
payment varchar(3) NOT NULL,
bid int NOT NULL,
cid int NOT NULL,
aid int NOT NULL,
PRIMARY KEY(btid),
FOREIGN KEY(bid) REFERENCES Buy_Listing(bid),
FOREIGN KEY(cid) REFERENCES CardInfo(cid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;

create table Rental_Transactions(
rtid int NOT NULL AUTO_INCREMENT,
borrower varchar(30) NOT NULL,
renter varchar(30) NOT NULL,
occured datetime NOT NULL,
rid int NOT NULL,
cid int NOT NULL,
aid int NOT NULL,
payment varchar(3) NOT NULL,
PRIMARY KEY(rtid),
FOREIGN KEY(rid) REFERENCES Rental_Listing(rid),
FOREIGN KEY(cid) REFERENCES CardInfo(cid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;

create table Equipment_Transactions(
etid int NOT NULL AUTO_INCREMENT,
buyer varchar(30) NOT NULL,
seller varchar(30) NOT NULL,
occured datetime NOT NULL,
payment varchar(3) NOT NULL,
eid int NOT NULL,
cid int NOT NULL,
aid int NOT NULL,
PRIMARY KEY(etid),
FOREIGN KEY(eid) REFERENCES Equipment_Listing(eid),
FOREIGN KEY(cid) REFERENCES CardInfo(cid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;


create table CardInfo(
cid int NOT NULL AUTO_INCREMENT,
card_name varchar(15) NOT NULL,
card_number varchar(12) NOT NULL UNIQUE,
expr varchar(5) NOT NULL,
cvv varchar(3) NOT NULL UNIQUE,
aid int NOT NULL,
PRIMARY KEY(cid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;



create table User_Accounts(
aid int NOT NULL AUTO_INCREMENT,
username varchar(30) NOT NULL,
password varchar (50) NOT NULL,
first_name varchar(30) NOT NULL,
last_name varchar(30) NOT NULL, 
email varchar(30) NOT NULL,
phone varchar(10) NOT NULL,
typ varchar(10) NOT NULL,
picture LONGBLOB NOT NULL,
created datetime NOT NULL,
PRIMARY KEY(aid))
engine=innodb;


create table Buy_Listing(
bid int NOT NULL AUTO_INCREMENT,
item varchar(30) NOT NULL,
price DECIMAL(10,2) NOT NULL,
descr varchar(100) NOT NULL,
picture LONGBLOB NOT NULL,
status varchar(30),
owner varchar(50),
typ varchar(20),
aid int NOT NULL,
PRIMARY KEY(bid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;

create table Equipment_Listing(
eid int NOT NULL AUTO_INCREMENT,
item varchar(30) NOT NULL,
price DECIMAL(10,2) NOT NULL,
descr varchar(100) NOT NULL,
picture LONGBLOB NOT NULL,
status varchar(30),
owner varchar(50),
typ varchar(20),
aid int NOT NULL,
PRIMARY KEY(eid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;


create table Rental_Listing(
rid int NOT NULL AUTO_INCREMENT,
item varchar(30) NOT NULL,
price DECIMAL(10,2) NOT NULL,
duration varchar(10) NOT NULL,
descr varchar(100) NOT NULL,
picture LONGBLOB NOT NULL,
status varchar(30),
owner varchar(50),
typ varchar(20),
aid int NOT NULL,
PRIMARY KEY(rid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;


create table Messages(
msgid int NOT NULL AUTO_INCREMENT,
sender varchar(30) NOT NULL,
reciever varchar(30) NOT NULL,
message varchar(250) NOT NULL,
recieved datetime NOT NULL,
PRIMARY KEY(msgid))
engine=innodb;

create table Pending_Sale(
psid int NOT NULL AUTO_INCREMENT,
username varchar(30),
bid int NOT NULL,
PRIMARY KEY(psid),
FOREIGN KEY(bid) REFERENCES Buy_Listing(bid))
engine=innodb;

create table Pending_Rental(
prid int NOT NULL AUTO_INCREMENT,
username varchar(30),
reason varchar(100),
duration varchar(100),
destination varchar(100),
rid int NOT NULL,
PRIMARY KEY(prid),
FOREIGN KEY(rid) REFERENCES Rental_Listing(rid))
engine=innodb;

create table Pending_Equipment(
peid int NOT NULL AUTO_INCREMENT,
username varchar(30),
eid int NOT NULL,
PRIMARY KEY(peid),
FOREIGN KEY(eid) REFERENCES Equipment_Listing(eid))
engine=innodb;

create table Notifications(
nid int NOT NULL AUTO_INCREMENT,
message varchar(100) NOT NULL,
recipient varchar(30) NOT NULL,
sender varchar(30) NOT NULL,
types varchar(30) NOT NULL,
created datetime NOT NULL,
bid int,
rid int,
eid int,
btid int,
rtid int,
etid int,
PRIMARY KEY(nid))
engine=innodb;

create table Msg_Notifications(
mnid int NOT NULL AUTO_INCREMENT,
message varchar(100) NOT NULL,
recipient varchar(30) NOT NULL,
sender varchar(30) NOT NULL,
created datetime NOT NULL,
msgid int,
PRIMARY KEY(mnid))
engine=innodb;






