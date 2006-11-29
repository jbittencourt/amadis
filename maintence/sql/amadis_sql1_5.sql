-- ----------------------------------------------------------------------
-- MySQL GRT Application
-- SQL Script
-- ----------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `amadis`
  CHARACTER SET utf8;
-- -------------------------------------
-- Tables

DROP TABLE IF EXISTS `amadis`.`ACLGroup`;
CREATE TABLE `amadis`.`ACLGroup` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `codeGroup` BIGINT(20) NOT NULL DEFAULT '0',
  `privilege` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKACOonACLGroups` (`codeACO`)
    REFERENCES `amadis`.`ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKGroupsonACLGroup` (`codeGroup`)
    REFERENCES `amadis`.`Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Files`;
CREATE TABLE `amadis`.`Files` (
  `codeFile` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `data` LONGBLOB NOT NULL,
  `mimeType` VARCHAR(100) NOT NULL,
  `size` INT(11) NOT NULL DEFAULT '0',
  `name` VARCHAR(150) NOT NULL,
  `metadata` VARCHAR(255) NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeFile`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`States`;
CREATE TABLE `amadis`.`States` (
  `codeState` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `country` VARCHAR(20) NOT NULL,
  `code` CHAR(3) NOT NULL,
  PRIMARY KEY (`codeState`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;


DROP TABLE IF EXISTS `amadis`.`Cities`;
CREATE TABLE `amadis`.`Cities` (
  `codeCity` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `state` INT(11) NOT NULL DEFAULT '0',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeCity`),
  FOREIGN KEY `FKstatesonCities` (`state`)
    REFERENCES `amadis`.`States` (`codeState`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`User`;
CREATE TABLE `amadis`.`User` (
  `codeUser` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  `name` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `active` CHAR(1) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `address` VARCHAR(150) NOT NULL,
  `codeCity` INT(11) NOT NULL DEFAULT '0',
  `cep` VARCHAR(9) NOT NULL,
  `url` VARCHAR(150) NOT NULL,
  `birthDate` BIGINT(20) NOT NULL DEFAULT '0',
  `aboutMe` TEXT NULL,
  `picture` BIGINT(20) NULL DEFAULT '0',
  PRIMARY KEY (`codeUser`),
  UNIQUE INDEX `username` (`username`),
  FOREIGN KEY `FKfilesonUser` (`picture`)
    REFERENCES `amadis`.`Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKcityonUser` (`codeCity`)
    REFERENCES `amadis`.`Cities` (`codeCity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ACLUser`;
CREATE TABLE `amadis`.`ACLUser` (
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `privilege` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`privilege`, `codeACO`, `codeUser`),
  FOREIGN KEY `FKACOonACLUser` (`codeACO`)
    REFERENCES `amadis`.`ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKUseronACLUser` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ACLWorld`;
CREATE TABLE `amadis`.`ACLWorld` (
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `privilege` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`codeACO`, `privilege`),
  FOREIGN KEY `FKACOonACLWorld` (`codeACO`)
    REFERENCES `amadis`.`ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ACO`;
CREATE TABLE `amadis`.`ACO` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(150) NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Agregator`;
CREATE TABLE `amadis`.`Agregator` (
  `codeSource` INT(11) NOT NULL,
  `keywords` TEXT NOT NULL,
  `time` BIGINT(20) NOT NULL,
  PRIMARY KEY (`codeSource`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Areas`;
CREATE TABLE `amadis`.`Areas` (
  `codeArea` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`codeArea`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`BlogComments`;
CREATE TABLE `amadis`.`BlogComments` (
  `codeComment` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `body` TEXT NOT NULL,
  `codePost` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeComment`),
  FOREIGN KEY `FKblogpostsonBlogComments` (`codePost`)
    REFERENCES `amadis`.`BlogPosts` (`codePost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuseronBlogComments` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`BlogPosts`;
CREATE TABLE `amadis`.`BlogPosts` (
  `codePost` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL,
  `body` TEXT NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codePost`),
  FOREIGN KEY `FKuseronBlogPosts` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`BlogProfiles`;
CREATE TABLE `amadis`.`BlogProfiles` (
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `titleBlog` TEXT NOT NULL,
  `text` TEXT NOT NULL,
  `image` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeUser`),
  FOREIGN KEY `FKuseronBlogProfiles` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfilesonBlogProfiles` (`image`)
    REFERENCES `amadis`.`Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ChatConnectedUsers`;
CREATE TABLE `amadis`.`ChatConnectedUsers` (
  `codeConnect` INT(11) NOT NULL AUTO_INCREMENT,
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `entranceDate` BIGINT(20) NOT NULL DEFAULT '0',
  `exitDate` BIGINT(20) NOT NULL DEFAULT '0',
  `flag` ENUM('ONLINE','OFFLINE') NOT NULL DEFAULT 'ONLINE',
  PRIMARY KEY (`codeConnect`),
  FOREIGN KEY `FKuseronChatConnectedUsers` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKchatroomonChatConnectedUsers` (`codeRoom`)
    REFERENCES `amadis`.`ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ChatMessages`;
CREATE TABLE `amadis`.`ChatMessages` (
  `codeMessage` BIGINT(11) NOT NULL AUTO_INCREMENT,
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeSender` BIGINT(20) NOT NULL DEFAULT '0',
  `codeRecipient` BIGINT(20) NOT NULL DEFAULT '0',
  `message` TEXT NOT NULL,
  `userStyle` VARCHAR(30) NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeMessage`),
  FOREIGN KEY `FKchatroomonChatMessages` (`codeRoom`)
    REFERENCES `amadis`.`ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKSenderonChatMessages` (`codeSender`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKRecipientonChatMessages` (`codeRecipient`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ChatRoom`;
CREATE TABLE `amadis`.`ChatRoom` (
  `codeRoom` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TINYTEXT NOT NULL,
  `infinity` CHAR(1) NOT NULL,
  `beginDate` BIGINT(20) NOT NULL DEFAULT '0',
  `endDate` BIGINT(20) NOT NULL DEFAULT '0',
  `chatType` ENUM('PROJECT','COMMUNITY','COURSE','FREE') NOT NULL DEFAULT 'PROJECT',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeRoom`),
  FOREIGN KEY `FKuseronChatRoom` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ChatsCommunities`;
CREATE TABLE `amadis`.`ChatsCommunities` (
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  FOREIGN KEY `FKchatroomonChatsCommunities` (`codeRoom`)
    REFERENCES `amadis`.`ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKcommunitiesonChatsCommunities` (`codeCommunity`)
    REFERENCES `amadis`.`Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ChatsProject`;
CREATE TABLE `amadis`.`ChatsProject` (
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  FOREIGN KEY `FKchatroomonChatsProject` (`codeRoom`)
    REFERENCES `amadis`.`ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonChatsProject` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;


DROP TABLE IF EXISTS `amadis`.`Comments`;
CREATE TABLE `amadis`.`Comments` (
  `codeComment` BIGINT(6) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `codeUser` BIGINT(20) NULL,
  `text` TEXT NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeComment`),
  FOREIGN KEY `FKuseronComments` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Communities`;
CREATE TABLE `amadis`.`Communities` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `description` TINYTEXT NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `codeGroup` BIGINT(20) NOT NULL DEFAULT '0',
  `codeACO` BIGINT(20) NOT NULL,
  `image` BIGINT(20) NULL DEFAULT '0',
  `flagAuth` ENUM('REQUEST','ALLOW') NOT NULL DEFAULT 'ALLOW',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKacoonCommunities` (`codeACO`)
    REFERENCES `amadis`.`ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfilesonCommunities` (`image`)
    REFERENCES `amadis`.`Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKgroupsonCommunities` (`codeGroup`)
    REFERENCES `amadis`.`Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`CommunityForums`;
CREATE TABLE `amadis`.`CommunityForums` (
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `codeForum` BIGINT(20) NOT NULL DEFAULT '0',
  FOREIGN KEY `FKcommunitiesonCommunityForums` (`codeCommunity`)
    REFERENCES `amadis`.`Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKforumsonCommunityForums` (`codeForum`)
    REFERENCES `amadis`.`Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`CommunityNews`;
CREATE TABLE `amadis`.`CommunityNews` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `title` TINYTEXT NOT NULL,
  `text` TEXT NOT NULL,
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKcommunitiesonCommunityNews` (`codeCommunity`)
    REFERENCES `amadis`.`Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuseronCommunityNews` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`CommunityProjectJoins`;
CREATE TABLE `amadis`.`CommunityProjectJoins` (
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `type` ENUM('REQUEST','INVITATION') NOT NULL DEFAULT 'REQUEST',
  `status` ENUM('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL DEFAULT 'NOT_ANSWERED',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeCommunity`, `codeProject`),
  FOREIGN KEY `FKcommunitiesonCommunityProjectJoins` (`codeCommunity`)
    REFERENCES `amadis`.`Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonCommunityProjectJoins` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`CommunityProjects`;
CREATE TABLE `amadis`.`CommunityProjects` (
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeCommunity`, `codeProject`),
  FOREIGN KEY `FKcommunitiesonCommunityProjects` (`codeCommunity`)
    REFERENCES `amadis`.`Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonCommunityProjects` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`EnvSession`;
CREATE TABLE `amadis`.`EnvSession` (
  `sessID` VARCHAR(32) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `timeStart` BIGINT(20) NOT NULL DEFAULT '0',
  `timeEnd` BIGINT(20) NOT NULL DEFAULT '0',
  `IP` INT(11) NOT NULL DEFAULT '0',
  `flagEnded` ENUM('TRUE','FALSE') NOT NULL DEFAULT 'FALSE',
  `visibility` ENUM('VISIBLE','HIDDEN','BUSY') NOT NULL DEFAULT 'VISIBLE',
  PRIMARY KEY (`sessID`, `codeUser`),
  FOREIGN KEY `FKuseronEnvSession` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;



DROP TABLE IF EXISTS `amadis`.`FilesLibraries`;
CREATE TABLE `amadis`.`FilesLibraries` (
  `codeLibrary` INT(11) NOT NULL DEFAULT '0',
  `codeFile` BIGINT(20) NOT NULL DEFAULT '0',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  `referred` CHAR(1) NOT NULL DEFAULT 'n',
  `active` CHAR(1) NOT NULL DEFAULT 'y',
  `shared` CHAR(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`codeFile`, `codeLibrary`),
  FOREIGN KEY `FKlibraryonFilesLibraries` (`codeLibrary`)
    REFERENCES `amadis`.`Library` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfilesonFilesLibraries` (`codeFile`)
    REFERENCES `amadis`.`Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`FinderChatRoom`;
CREATE TABLE `amadis`.`FinderChatRoom` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `dateStart` INT(11) NOT NULL DEFAULT '0',
  `dateEnd` INT(11) NOT NULL DEFAULT '0',
  `codeStarter` BIGINT(20) NOT NULL DEFAULT '0',
  `codeRequest` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
 FOREIGN KEY `FKStarteronFinderChatRoom` (`codeStarter`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
 FOREIGN KEY `FKRequestonFinderChatRoom` (`codeRequest`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`FinderMessages`;
CREATE TABLE `amadis`.`FinderMessages` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeSender` BIGINT(20) NOT NULL DEFAULT '0',
  `codeRecipient` BIGINT(20) NOT NULL DEFAULT '0',
  `message` TEXT NOT NULL,
  `status` ENUM('READ','NOT_READ') NOT NULL DEFAULT 'NOT_READ',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKfinderchatroomonFinderMessages` (`codeRoom`)
    REFERENCES `amadis`.`FinderChatRoom` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKSenderonFinderMessages` (`codeSender`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKRecipientonFinderMessages` (`codeRecipient`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ForumMessages`;
CREATE TABLE `amadis`.`ForumMessages` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeForum` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL,
  `body` TEXT NOT NULL,
  `parent` BIGINT(20) NOT NULL DEFAULT '0',
  `timePost` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKforumsonForumMessages` (`codeForum`)
    REFERENCES `amadis`.`Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuseronForumMessages` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Forums`;
CREATE TABLE `amadis`.`Forums` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(80) NOT NULL,
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `creationTime` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ForumVisits`;
CREATE TABLE `amadis`.`ForumVisits` (
  `codeUser` BIGINT(20) NOT NULL,
  `codeForum` BIGINT(20) NOT NULL,
  `time` BIGINT(20) NOT NULL,
 PRIMARY KEY(`codeUser`, `codeForum`, `time`),
 FOREIGN KEY `FKUseronForumVisits` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
 FOREIGN KEY `FKForumonForumVisits` (`codeForum`)
    REFERENCES `amadis`.`Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION 
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Friends`;
CREATE TABLE `amadis`.`Friends` (
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `codeFriend` BIGINT(20) NOT NULL DEFAULT '0',
  `comentary` TINYTEXT NOT NULL,
  `status` ENUM('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL DEFAULT 'NOT_ANSWERED',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeUser`, `codeFriend`),
  FOREIGN KEY `FKuseronFriends` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfriendonFriends` (`codeFriend`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`GroupMember`;
CREATE TABLE `amadis`.`GroupMember` (
  `codeGroup` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `status` ENUM('ACTIVE','RETIRED') NOT NULL DEFAULT 'ACTIVE',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeGroup`, `codeUser`),
  FOREIGN KEY `FKuseronGroupMember` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKgroupsonGroupMember` (`codeGroup`)
    REFERENCES `amadis`.`Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`GroupMemberJoin`;
CREATE TABLE `amadis`.`GroupMemberJoin` (
  `codeGroupMemberJoin` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `codeGroup` BIGINT(20) NOT NULL DEFAULT '0',
  `type` ENUM('INVITATION','REQUEST') NOT NULL DEFAULT 'INVITATION',
  `status` ENUM('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL DEFAULT 'NOT_ANSWERED',
  `textRequest` TINYTEXT NOT NULL,
  `textResponse` TINYTEXT NOT NULL,
  `ackResponse` ENUM('ACK','NOT_ACK') NOT NULL DEFAULT 'NOT_ACK',
  `timeResponse` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUserResponse` BIGINT(20) NOT NULL DEFAULT '0',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeGroupMemberJoin`),
  FOREIGN KEY `FKuseronGroupMemberJoin` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKgroupsonGroupMemberJoin` (`codeGroup`)
    REFERENCES `amadis`.`Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuserResponseonGroupMemberJoin` (`codeUserResponse`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Groups`;
CREATE TABLE `amadis`.`Groups` (
  `codeGroup` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(100) NOT NULL,
  `managed` ENUM('MANAGED','NOT_MANAGED') NOT NULL DEFAULT 'NOT_MANAGED',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeGroup`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Library`;
CREATE TABLE `amadis`.`Library` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`LogUploadFiles`;
CREATE TABLE `amadis`.`LogUploadFiles` (
  `uploadType` ENUM('PROJECT','USER') NOT NULL DEFAULT 'USER',
  `codeAnchor` INT(11) NOT NULL DEFAULT '0',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeAnchor`, `uploadType`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`News`;
CREATE TABLE `amadis`.`News` (
  `codeNews` INT(4) NOT NULL AUTO_INCREMENT,
  `codeProject` INT(4) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL,
  `text` TEXT NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeNews`),
  FOREIGN KEY `FKprojectonNews` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ProjectAreas`;
CREATE TABLE `amadis`.`ProjectAreas` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeArea` TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeArea`),
  FOREIGN KEY `FKprojectsonProjectAreas` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKareasonProjectAreas` (`codeArea`)
    REFERENCES `amadis`.`Areas` (`codeArea`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ProjectComments`;
CREATE TABLE `amadis`.`ProjectComments` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeComment` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeComment`),
  FOREIGN KEY `FKprojectsonProjectComments` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKcommentsonProjectComments` (`codeComment`)
    REFERENCES `amadis`.`Comments` (`codeComment`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ProjectForums`;
CREATE TABLE `amadis`.`ProjectForums` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeForum` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeForum`),
  FOREIGN KEY `FKprojectsonProjectForums` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKForumonProjectForums` (`codeForum`)
    REFERENCES `amadis`.`Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ProjectNews`;
CREATE TABLE `amadis`.`ProjectNews` (
  `codeNews` INT(11) NOT NULL AUTO_INCREMENT,
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL,
  `text` TEXT NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeNews`),
  FOREIGN KEY `FKuseronProjectNews` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonProjectNews` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKnewsonProjectNews` (`codeNews`)
    REFERENCES `amadis`.`News` (`codeNews`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Projects`;
CREATE TABLE `amadis`.`Projects` (
  `codeProject` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `status` TINYINT(4) NOT NULL DEFAULT '1',
  `image` BIGINT(20) NULL DEFAULT '0',
  `hits` BIGINT(20) NOT NULL DEFAULT '0',
  `codeGroup` BIGINT(20) NOT NULL DEFAULT '0',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`ProjectsLibraries`;
CREATE TABLE `amadis`.`ProjectsLibraries` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeLibrary` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeLibrary`),
  FOREIGN KEY `FKprojectsonProjectsLibraries` (`codeProject`)
    REFERENCES `amadis`.`Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKlibraryonProjectsLibraries` (`codeLibrary`)
    REFERENCES `amadis`.`Library` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`StatusProjeto`;
DROP TABLE IF EXISTS `amadis`.`ProjectStatus`;
CREATE TABLE `amadis`.`ProjectStatus` (
  `code` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`UserMessages`;
CREATE TABLE `amadis`.`UserMessages` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `message` TINYTEXT NOT NULL,
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `codeTo` BIGINT(20) NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKUserFromonUserMessages` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKUserToonUserMessages` (`codeTo`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`UsersLibraries`;
CREATE TABLE `amadis`.`UsersLibraries` (
  `codeLibrary` INT(11) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeLibrary`, `codeUser`),
  FOREIGN KEY `FKuseronUsersLibraries` (`codeUser`)
    REFERENCES `amadis`.`User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKlibraryonUsersLibraries` (`codeLibrary`)
    REFERENCES `amadis`.`Library` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `amadis`.`Warnings`;
CREATE TABLE `amadis`.`Warnings` (
  `codeWarning` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `timeStart` BIGINT(20) NOT NULL DEFAULT '0',
  `timeEnd` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeWarning`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;


SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------
-- EOF

