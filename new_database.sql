# CSCI 467 Project 1A
# Julia Finegan, 
# Product System SQL

CREATE TABLE Inventory(
	Num	INT	NOT NULL	 AUTO_INCREMENT,
	quantity	INT	NOT NULL
	PRIMARY_KEY(Num)
);

CREATE TABLE Order_Info(
	Order_ID	INT,
	cust_name	CHAR(30),
	cust_addr	CHAR(55),
	cust_email	CHAR(40),
	total_price	DECIMAL(8,2),
	status		CHAR(12),
	date		DATE,
	total_weight	DECIMAL(8,2),
	PRIMARY KEY (Order_ID)
);

CREATE TABLE Order(
	Order_ID	INT,
	prod_ID		INT,
	amount		INT,
	price	DECIMAL(8,2),
	PRIMARY KEY (Order_ID, prod_ID),
	FOREIGN KEY (Order_ID) REFERENCES Order_Info(Order_ID)
);