create table Accounts(
Id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
Username varchar(15) NOT NULL,
Password varchar (50) NOT NULL,
FirstName varchar(30) NOT NULL,
LastName varchar(30) NOT NULL,
Birthday date NOT NULL,
Address varchar(30) NOT NULL,
Phone varchar(10) NOT NULL,
Picture LONGBLOB);
