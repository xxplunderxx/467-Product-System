#upgrade user status
#fill in ? with username 
# UPDATE User SET status = 'worker' WHERE User_name = '?';

#Order info inserition
INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Jacob","123 cool street Dekalb, Illnois. 61069","real_email@gmail.com",100.00,"Pending",10.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Cole","123 cool street Rochelle, Illnois. 61068","cole@gmail.com",50.00,"Pending",5.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Nancy","123 cool street Sycamore, Illnois. 61069","real_email@gmail.com",10.00,"completed",1.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Billy","123 cool street Dekalb, Illnois. 61069","real_email@gmail.com",100.00,"Pending",10.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Mole","123 cool street Rochelle, Illnois. 61068","cole@gmail.com",50.00,"Pending",5.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Joel","123 cool street Dekalb, Illnois. 61069","real_email@gmail.com",100.00,"Pending",10.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Nathan","123 cool street Rochelle, Illnois. 61068","cole@gmail.com",50.00,"Pending",5.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Skylar","123 cool street Dekalb, Illnois. 61069","real_email@gmail.com",100.00,"Pending",10.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("James","123 cool street Rochelle, Illnois. 61068","cole@gmail.com",50.00,"Pending",5.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Ruth","123 cool street Dekalb, Illnois. 61069","real_email@gmail.com",100.00,"Pending",10.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Xavier","123 cool street Rochelle, Illnois. 61068","cole@gmail.com",50.00,"Pending",5.00);

#Order product inserition
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(1,1,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(1,2,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(2,3,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(3,4,1,10);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(4,1,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(5,2,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(6,3,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(7,4,1,10);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(8,1,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(9,2,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(9,4,1,10);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(10,3,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(10,4,1,10);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(11,4,1,10);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(11,5,1,10);

#Weights inserition
INSERT INTO Weights VALUES('1','0','2','.99');
INSERT INTO Weights(low, high, cost) VALUES('2','5','1.99');
INSERT INTO Weights(low, high, cost) VALUES('5','10','5.99');
INSERT INTO Weights(low, high, cost) VALUES('10','20','9.99');
INSERT INTO Weights(low, high, cost) VALUES('20','50','19.99');
INSERT INTO Weights(low, high, cost) VALUES('50','100','25.99');
INSERT INTO Weights(low, cost) VALUES('100','49.99');