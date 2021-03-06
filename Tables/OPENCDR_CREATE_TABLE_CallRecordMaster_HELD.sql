DROP TABLE callrecordmaster_held;
CREATE TABLE callrecordmaster_held(
        CallID varchar(100)     PRIMARY KEY,
        CustomerID varchar(15),
        CallType smallint,
        CallDateTime timestamp NOT NULL,
        Duration integer NOT NULL,
        Direction char(1),
        SourceIP varchar(15),
        OriginatingNumber varchar(50) NOT NULL,
        DestinationNumber varchar(50) NOT NULL,
        LRN varchar(50),
        CNAMdipped boolean,
	RateCenter varchar(50),
	CarrierID varchar(100),
	WholesaleRate numeric(19, 7),
	WholesalePrice numeric(19, 7),	
        RoutingPrefix varchar(10),	
	ErrorMessage varchar(100) NOT NULL,
        RowID serial4 UNIQUE NOT NULL
);