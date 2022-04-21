#Order info inserition
INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Jacob","123 cool street Dekalb, Illnois. 61069","real_email@gmail.com",100.00,"Pending",10.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Cole","123 cool street Rochelle, Illnois. 61068","cole@gmail.com",50.00,"Pending",5.00);

INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES("Nancy","123 cool street Sycamore, Illnois. 61069","real_email@gmail.com",10.00,"Completed",1.00);

#Order product inserition
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(1,1,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(1,2,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(2,3,1,50);
INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(3,4,1,10);
