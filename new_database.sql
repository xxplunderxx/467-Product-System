# CSCI 467 Project 1A
# Julia Finegan, 
# Product System SQL

DROP TABLE IF EXISTS Order_Prod;
DROP TABLE IF EXISTS Order_Info;
DROP TABLE IF EXISTS Inventory;

CREATE TABLE Inventory(
	Num	INT	NOT NULL	 AUTO_INCREMENT,
	quantity	INT	NOT NULL,
	PRIMARY KEY (Num)
);

CREATE TABLE Order_Info(
	Order_ID	INT	NOT NULL	AUTO_INCREMENT, 
	cust_name	CHAR(30)	NOT NULL,
	cust_addr	CHAR(55)	NOT NULL,
	cust_email	CHAR(40)	NOT NULL,
	total_price	DECIMAL(8,2)	NOT NULL,
	status		CHAR(12)	NOT NULL,
	date		DATE	DEFAULT CURRENT_TIMESTAMP,
	total_weight	DECIMAL(8,2)	NOT NULL,
	PRIMARY KEY (Order_ID)
);

CREATE TABLE Order_Prod(
	Order_ID	INT	NOT NULL	AUTO_INCREMENT,
	prod_ID		INT	NOT NULL,
	amount		INT	NOT NULL,
	price	DECIMAL(8,2)	NOT NULL,
	PRIMARY KEY (Order_ID, prod_ID),
	FOREIGN KEY (Order_ID) REFERENCES Order_Info(Order_ID),
	FOREIGN KEY (prod_ID) REFERENCES Inventory (Num)
);

CREATE TABLE User(
	User_name CHAR(16) NOT NULL,
	password  CHAR(64) NOT NULL,
	status    CHAR(16) DEFAULT "default",
	PRIMARY KEY (User_name)
);
#upgrade user status
#fill in ? with username 
# UPDATE User SET status = 'worker' WHERE User_name = '?';
