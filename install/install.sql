CREATE TABLE IF NOT EXISTS b_users  (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `LOGIN` CHAR(20) NOT NULL,
    `PASSWORD` VARCHAR(400) NOT NULL,
    `ROLE` CHAR(2) DEFAULT 'R',
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS b_review  (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `USER_ID` INT NOT NULL,
    `PREFERENCES` TEXT,
    `NEGATIVE` TEXT,
    `COMMENT` TEXT,
    `NOT_EQUALE` CHAR(1) DEFAULT 'N',
    `NOT_EQUALE_TEXT` TEXT,
    `TOTAL_MARK` FLOAT NOT NULL,
    `TOTAL_VOTE` INT,
    `FILES` CHAR,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS b_review_props  (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `REVIEW_ID` INT NOT NULL,
    `VOTE` INT NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS b_files (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `FILE_PATH` CHAR NOT NULL,
    `BASE_NAME` CHAR NOT NULL,
    `TYPE` CHAR,
    `SIZE` INT(100),
    PRIMARY KEY(ID)
);