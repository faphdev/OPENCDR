DROP TABLE effectiveinterstateratemaster;
CREATE TABLE effectiveinterstateratemaster(
		CustomerID varchar(15) NOT NULL,
		NPANXXX varchar(7) NOT NULL,
		RetailRate numeric(9,7) NOT NULL,
		PRIMARY KEY(CustomerID, NPANXXX)
);