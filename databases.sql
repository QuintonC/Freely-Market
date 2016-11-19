
create table Transactions(
tid int NOT NULL AUTO_INCREMENT,
lid int NOT NULL,
cid int NOT NULL,
aid int NOT NULL,
PRIMARY KEY(tid),
FOREIGN KEY(lid) REFERENCES Listing(lid),
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
username varchar(15) NOT NULL,
password varchar (50) NOT NULL,
first_name varchar(30) NOT NULL,
last_name varchar(30) NOT NULL, 
email varchar(30) NOT NULL,
phone varchar(10) NOT NULL,
typ varchar(10) NOT NULL,
picture LONGBLOB NOT NULL,
PRIMARY KEY(aid))
engine=innodb;


create table Listing(
lid int NOT NULL AUTO_INCREMENT,
item varchar(30) NOT NULL,
price varchar(8) NOT NULL,
typ varchar(10) NOT NULL,
picture LONGBLOB NOT NULL,
aid int NOT NULL,
PRIMARY KEY(lid),
FOREIGN KEY(aid) REFERENCES User_Accounts(aid))
engine=innodb;