CREATE TABLE Employee (
	eid INT(11) AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(60) NOT NULL,
	email VARCHAR(60) NOT NULL UNIQUE,
	phone VARCHAR(20),
	password VARCHAR(255) NOT NULL, -- future-proofing for larger password hashes
	hourlyWage FLOAT NOT NULL DEFAULT 0,
	qualifiesOvertime BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE Client (
	cid INT(11) AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100),
	alias VARCHAR(10),
	address TEXT,
	contactPhone VARCHAR(20),
	contactEmail VARCHAR(60),
	paysOvertime BOOLEAN
);

CREATE TABLE Disability (
	did INT(11) AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20),
	qualifiesForTaxBreak BOOLEAN
);

CREATE TABLE hasDisability (
	eid INT(11),
	did INT(11),
	PRIMARY KEY (eid, did),
	FOREIGN KEY (eid) REFERENCES Employee(eid) ON UPDATE CASCADE,
	FOREIGN KEY (did) REFERENCES Disability(did) ON UPDATE CASCADE
);

CREATE TABLE Position (
	pid INT(11) AUTO_INCREMENT PRIMARY KEY,
	cid INT(11),
	name VARCHAR(20),
	alias VARCHAR(10),
	unitHourlyPrice FLOAT,
	FOREIGN KEY (cid) REFERENCES Client(cid) ON UPDATE CASCADE
);

CREATE TABLE hasPosition (
	eid INT(11),
	pid INT(11),
	since DATE,
	until DATE,
	PRIMARY KEY (eid, pid),
	FOREIGN KEY (eid) REFERENCES Employee(eid) ON UPDATE CASCADE,
	FOREIGN KEY (pid) REFERENCES `Position`(pid) ON UPDATE CASCADE
);

CREATE TABLE ClockIn (
	ciid INT(11) AUTO_INCREMENT PRIMARY KEY,
	eid INT(11),
	pid INT(11),
	entered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (eid) REFERENCES Employee(eid) ON UPDATE CASCADE
	FOREIGN KEY (pid) REFERENCES `Position`(pid) ON UPDATE CASCADE
);

CREATE TABLE ClockOut (
	coid INT(11) AUTO_INCREMENT PRIMARY KEY,
	ciid INT(11),
	`comment` TEXT,
	`left` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	approved BOOLEAN,
	FOREIGN KEY (ciid) REFERENCES ClockIn(ciid) ON UPDATE CASCADE
);


# Insert Bucket Client
INSERT INTO Client
(`cid`, `name`, `alias`, `address`, `contactPhone`, `contactEmail`, `paysOvertime`)
VALUES
(0, 'Sky Shield Security', 'SKY', 'Sonny Hernandez Inc.
Calle D #20
Ext. Villa Verde
Cayey, PR 00736', '9396302780', 'victorsonny@yahoo.com', 1);

# Insert Bucket Position
INSERT INTO Position (`pid`, `cid`, `name`, `alias`, `unitHourlyPrice`) VALUES (0, 0, 'Independent Work', 'IW', 10);
