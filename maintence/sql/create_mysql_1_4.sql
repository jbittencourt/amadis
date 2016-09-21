-- ----------------------------------------------------------------------
-- MySQL GRT Application
-- SQL Script
-- ----------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES 'utf8';

-- -------------------------------------
-- Tables

DROP TABLE IF EXISTS `ACLGroup`;
CREATE TABLE `ACLGroup` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `codeGroup` BIGINT(20) NOT NULL DEFAULT '0',
  `privilege` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKACOonACLGroups` (`codeACO`)
    REFERENCES `ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKGroupsonACLGroup` (`codeGroup`)
    REFERENCES `Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;


DROP TABLE IF EXISTS `Files`;
CREATE TABLE `Files` (
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

DROP TABLE IF EXISTS `States`;
CREATE TABLE `States` (
  `codeState` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `country` VARCHAR(20) NOT NULL,
  `code` CHAR(3) NOT NULL,
  PRIMARY KEY (`codeState`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;


DROP TABLE IF EXISTS `Cities`;
CREATE TABLE `Cities` (
  `codeCity` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `codeState` INT(11) NOT NULL DEFAULT '0',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeCity`),
  FOREIGN KEY `FKstatesonCities` (`codeState`)
    REFERENCES `States` (`codeState`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
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
    REFERENCES `Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKcityonUser` (`codeCity`)
    REFERENCES `Cities` (`codeCity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Album`;
CREATE TABLE `Album` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeUser` BIGINT(20) NOT NULL,
  `codePhoto` BIGINT(20) NOT NULL,
  `comments` VARCHAR(100) NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKFilesonAlbum` (`codePhoto`)
    REFERENCES `Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKUseronAlbum` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ACLUser`;
CREATE TABLE `ACLUser` (
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `privilege` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`privilege`, `codeACO`, `codeUser`),
  FOREIGN KEY `FKACOonACLUser` (`codeACO`)
    REFERENCES `ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKUseronACLUser` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ACLWorld`;
CREATE TABLE `ACLWorld` (
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `privilege` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`codeACO`, `privilege`),
  FOREIGN KEY `FKACOonACLWorld` (`codeACO`)
    REFERENCES `ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ACO`;
CREATE TABLE `ACO` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(150) NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Agregator`;
CREATE TABLE `Agregator` (
  `codeSource` INT(11) NOT NULL,
  `keywords` TEXT NOT NULL,
  `time` BIGINT(20) NOT NULL,
  PRIMARY KEY (`codeSource`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Areas`;
CREATE TABLE `Areas` (
  `codeArea` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`codeArea`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `BlogComments`;
CREATE TABLE `BlogComments` (
  `codeComment` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `body` TEXT NOT NULL,
  `codePost` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `answered` enum('TRUE','FALSE') NOT NULL default 'FALSE',
  `parentComment` int(11) NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeComment`),
  FOREIGN KEY `FKblogpostsonBlogComments` (`codePost`)
    REFERENCES `BlogPosts` (`codePost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuseronBlogComments` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `BlogPosts`;
CREATE TABLE `BlogPosts` (
  `codePost` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL,
  `body` TEXT NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codePost`),
  FOREIGN KEY `FKuseronBlogPosts` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `BlogProfiles`;
CREATE TABLE `BlogProfiles` (
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `titleBlog` TEXT NOT NULL,
  `text` TEXT NOT NULL,
  `image` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeUser`),
  FOREIGN KEY `FKuseronBlogProfiles` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfilesonBlogProfiles` (`image`)
    REFERENCES `Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ChatConnectedUsers`;
CREATE TABLE `ChatConnectedUsers` (
  `codeConnect` INT(11) NOT NULL AUTO_INCREMENT,
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `entranceDate` BIGINT(20) NOT NULL DEFAULT '0',
  `exitDate` BIGINT(20) NOT NULL DEFAULT '0',
  `flag` ENUM('ONLINE','OFFLINE') NOT NULL DEFAULT 'ONLINE',
  PRIMARY KEY (`codeConnect`),
  FOREIGN KEY `FKuseronChatConnectedUsers` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKchatroomonChatConnectedUsers` (`codeRoom`)
    REFERENCES `ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ChatMessages`;
CREATE TABLE `ChatMessages` (
  `codeMessage` BIGINT(11) NOT NULL AUTO_INCREMENT,
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeSender` BIGINT(20) NOT NULL DEFAULT '0',
  `codeRecipient` BIGINT(20) NOT NULL DEFAULT '0',
  `message` TEXT NOT NULL,
  `userStyle` VARCHAR(30) NOT NULL,
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeMessage`),
  FOREIGN KEY `FKchatroomonChatMessages` (`codeRoom`)
    REFERENCES `ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKSenderonChatMessages` (`codeSender`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKRecipientonChatMessages` (`codeRecipient`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ChatRoom`;
CREATE TABLE `ChatRoom` (
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
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ChatsCommunities`;
CREATE TABLE `ChatsCommunities` (
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  FOREIGN KEY `FKchatroomonChatsCommunities` (`codeRoom`)
    REFERENCES `ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKcommunitiesonChatsCommunities` (`codeCommunity`)
    REFERENCES `Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ChatsProject`;
CREATE TABLE `ChatsProject` (
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  FOREIGN KEY `FKchatroomonChatsProject` (`codeRoom`)
    REFERENCES `ChatRoom` (`codeRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonChatsProject` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;



DROP TABLE IF EXISTS `Comments`;
CREATE TABLE `Comments` (
  `codeComment` BIGINT(6) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `codeUser` BIGINT(20) NULL,
  `text` TEXT NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeComment`),
  FOREIGN KEY `FKuseronComments` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Communities`;
CREATE TABLE `Communities` (
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
    REFERENCES `ACO` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfilesonCommunities` (`image`)
    REFERENCES `Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKgroupsonCommunities` (`codeGroup`)
    REFERENCES `Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `CommunityForums`;
CREATE TABLE `CommunityForums` (
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `codeForum` BIGINT(20) NOT NULL DEFAULT '0',
  FOREIGN KEY `FKcommunitiesonCommunityForums` (`codeCommunity`)
    REFERENCES `Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKforumsonCommunityForums` (`codeForum`)
    REFERENCES `Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `CommunityNews`;
CREATE TABLE `CommunityNews` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `title` TINYTEXT NOT NULL,
  `text` TEXT NOT NULL,
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKcommunitiesonCommunityNews` (`codeCommunity`)
    REFERENCES `Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuseronCommunityNews` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `CommunityProjectJoins`;
CREATE TABLE `CommunityProjectJoins` (
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `type` ENUM('REQUEST','INVITATION') NOT NULL DEFAULT 'REQUEST',
  `status` ENUM('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL DEFAULT 'NOT_ANSWERED',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeCommunity`, `codeProject`),
  FOREIGN KEY `FKcommunitiesonCommunityProjectJoins` (`codeCommunity`)
    REFERENCES `Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonCommunityProjectJoins` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `CommunityProjects`;
CREATE TABLE `CommunityProjects` (
  `codeCommunity` INT(11) NOT NULL DEFAULT '0',
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeCommunity`, `codeProject`),
  FOREIGN KEY `FKcommunitiesonCommunityProjects` (`codeCommunity`)
    REFERENCES `Communities` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonCommunityProjects` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `EnvSession`;
CREATE TABLE `EnvSession` (
  `sessID` VARCHAR(32) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `timeStart` BIGINT(20) NOT NULL DEFAULT '0',
  `timeEnd` BIGINT(20) NOT NULL DEFAULT '0',
  `IP` INT(11) NOT NULL DEFAULT '0',
  `flagEnded` ENUM('TRUE','FALSE') NOT NULL DEFAULT 'FALSE',
  `visibility` ENUM('VISIBLE','HIDDEN','BUSY') NOT NULL DEFAULT 'VISIBLE',
  PRIMARY KEY (`sessID`, `codeUser`),
  FOREIGN KEY `FKuseronEnvSession` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;



DROP TABLE IF EXISTS `FilesLibraries`;
CREATE TABLE `FilesLibraries` (
  `codeLibrary` INT(11) NOT NULL DEFAULT '0',
  `codeFile` BIGINT(20) NOT NULL DEFAULT '0',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  `referred` CHAR(1) NOT NULL DEFAULT 'n',
  `active` CHAR(1) NOT NULL DEFAULT 'y',
  `shared` CHAR(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`codeFile`, `codeLibrary`),
  FOREIGN KEY `FKlibraryonFilesLibraries` (`codeLibrary`)
    REFERENCES `Library` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfilesonFilesLibraries` (`codeFile`)
    REFERENCES `Files` (`codeFile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `FinderChatRoom`;
CREATE TABLE `FinderChatRoom` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `dateStart` INT(11) NOT NULL DEFAULT '0',
  `dateEnd` INT(11) NOT NULL DEFAULT '0',
  `codeStarter` BIGINT(20) NOT NULL DEFAULT '0',
  `codeRequest` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
 FOREIGN KEY `FKStarteronFinderChatRoom` (`codeStarter`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
 FOREIGN KEY `FKRequestonFinderChatRoom` (`codeRequest`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `FinderMessages`;
CREATE TABLE `FinderMessages` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `codeRoom` INT(11) NOT NULL DEFAULT '0',
  `codeSender` BIGINT(20) NOT NULL DEFAULT '0',
  `codeRecipient` BIGINT(20) NOT NULL DEFAULT '0',
  `message` TEXT NOT NULL,
  `status` ENUM('READ','NOT_READ') NOT NULL DEFAULT 'NOT_READ',
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKfinderchatroomonFinderMessages` (`codeRoom`)
    REFERENCES `FinderChatRoom` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKSenderonFinderMessages` (`codeSender`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKRecipientonFinderMessages` (`codeRecipient`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ForumMessages`;
CREATE TABLE `ForumMessages` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codeForum` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL,
  `body` TEXT NOT NULL,
  `parent` BIGINT(20) NOT NULL DEFAULT '0',
  `timePost` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKforumsonForumMessages` (`codeForum`)
    REFERENCES `Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuseronForumMessages` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Forums`;
CREATE TABLE `Forums` (
  `code` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(80) NOT NULL,
  `codeACO` BIGINT(20) NOT NULL DEFAULT '0',
  `creationTime` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ForumVisits`;
CREATE TABLE `ForumVisits` (
  `codeUser` BIGINT(20) NOT NULL,
  `codeForum` BIGINT(20) NOT NULL,
  `time` BIGINT(20) NOT NULL,
 PRIMARY KEY(`codeUser`, `codeForum`, `time`),
 FOREIGN KEY `FKUseronForumVisits` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
 FOREIGN KEY `FKForumonForumVisits` (`codeForum`)
    REFERENCES `Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Friends`;
CREATE TABLE `Friends` (
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `codeFriend` BIGINT(20) NOT NULL DEFAULT '0',
  `comentary` TINYTEXT NOT NULL,
  `status` ENUM('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL DEFAULT 'NOT_ANSWERED',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeUser`, `codeFriend`),
  FOREIGN KEY `FKuseronFriends` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKfriendonFriends` (`codeFriend`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `GroupMember`;
CREATE TABLE `GroupMember` (
  `codeGroup` BIGINT(20) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `status` ENUM('ACTIVE','RETIRED') NOT NULL DEFAULT 'ACTIVE',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeGroup`, `codeUser`, `status`),
  FOREIGN KEY `FKuseronGroupMember` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKgroupsonGroupMember` (`codeGroup`)
    REFERENCES `Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `GroupMemberJoin`;
CREATE TABLE `GroupMemberJoin` (
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
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKgroupsonGroupMemberJoin` (`codeGroup`)
    REFERENCES `Groups` (`codeGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKuserResponseonGroupMemberJoin` (`codeUserResponse`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Groups`;
CREATE TABLE `Groups` (
  `codeGroup` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(100) NOT NULL,
  `managed` ENUM('MANAGED','NOT_MANAGED') NOT NULL DEFAULT 'NOT_MANAGED',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeGroup`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Library`;
CREATE TABLE `Library` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `LogUploadFiles`;
CREATE TABLE `LogUploadFiles` (
  `uploadType` ENUM('PROJECT','USER') NOT NULL DEFAULT 'USER',
  `codeAnchor` INT(11) NOT NULL DEFAULT '0',
  `time` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeAnchor`, `uploadType`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ProjectAreas`;
CREATE TABLE `ProjectAreas` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeArea` TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeArea`),
  FOREIGN KEY `FKprojectsonProjectAreas` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKareasonProjectAreas` (`codeArea`)
    REFERENCES `Areas` (`codeArea`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ProjectComments`;
CREATE TABLE `ProjectComments` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeComment` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeComment`),
  FOREIGN KEY `FKprojectsonProjectComments` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKcommentsonProjectComments` (`codeComment`)
    REFERENCES `Comments` (`codeComment`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ProjectForums`;
CREATE TABLE `ProjectForums` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeForum` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeForum`),
  FOREIGN KEY `FKprojectsonProjectForums` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKForumonProjectForums` (`codeForum`)
    REFERENCES `Forums` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `ProjectNews`;
CREATE TABLE `ProjectNews` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL,
  `text` TEXT NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKuseronProjectNews` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKprojectsonProjectNews` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Projects`;
CREATE TABLE `Projects` (
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

DROP TABLE IF EXISTS `ProjectsLibraries`;
CREATE TABLE `ProjectsLibraries` (
  `codeProject` INT(11) NOT NULL DEFAULT '0',
  `codeLibrary` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeProject`, `codeLibrary`),
  FOREIGN KEY `FKprojectsonProjectsLibraries` (`codeProject`)
    REFERENCES `Projects` (`codeProject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKlibraryonProjectsLibraries` (`codeLibrary`)
    REFERENCES `Library` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;


DROP TABLE IF EXISTS `StatusProjeto`;
DROP TABLE IF EXISTS `ProjectStatus`;
CREATE TABLE `ProjectStatus` (
  `code` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`code`)
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `UserMessages`;
CREATE TABLE `UserMessages` (
  `code` INT(11) NOT NULL AUTO_INCREMENT,
  `message` TEXT NOT NULL,
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  `codeTo` BIGINT(20) NOT NULL,
  `time` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  FOREIGN KEY `FKUserFromonUserMessages` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKUserToonUserMessages` (`codeTo`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `UsersLibraries`;
CREATE TABLE `UsersLibraries` (
  `codeLibrary` INT(11) NOT NULL DEFAULT '0',
  `codeUser` BIGINT(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeLibrary`, `codeUser`),
  FOREIGN KEY `FKuseronUsersLibraries` (`codeUser`)
    REFERENCES `User` (`codeUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY `FKlibraryonUsersLibraries` (`codeLibrary`)
    REFERENCES `Library` (`code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
ROW_FORMAT = Compact
CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `Warnings`;
CREATE TABLE `Warnings` (
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


CREATE TABLE `WikiPage` (
  `codePage` bigint(20) NOT NULL auto_increment,
  `namespace` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `lastest` int(11) NOT NULL,
  `new` tinyint(1) NOT NULL,
  PRIMARY KEY  (`codePage`),
  UNIQUE KEY `name` (`namespace`,`title`)
)
ENGINE=InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci;

--
-- Table structure for table `WikiRevision`
--

CREATE TABLE `WikiRevision` (
  `codeRevision` bigint(20) NOT NULL,
  `page` bigint(20) NOT NULL,
  `text` bigint(20) NOT NULL,
  `user` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  PRIMARY KEY  (`codeRevision`,`page`),
  KEY `page` (`page`),
  KEY `text` (`text`),
  KEY `user` (`user`)
)
ENGINE=InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci;

--
-- Table structure for table `WikiText`
--

CREATE TABLE `WikiText` (
  `codeText` bigint(20) NOT NULL auto_increment,
  `text` mediumblob NOT NULL,
  PRIMARY KEY  (`codeText`)
)
ENGINE=InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci;



SET FOREIGN_KEY_CHECKS = 1;

-- ALTER TABLE `WikiRevision`
--  ADD CONSTRAINT `WikiRevision_ibfk_5` FOREIGN KEY (`page`) REFERENCES `WikiPage` (`codePage`),
--  ADD CONSTRAINT `WikiRevision_ibfk_6` FOREIGN KEY (`text`) REFERENCES `WikiText` (`codeText`),
--  ADD CONSTRAINT `WikiRevision_ibfk_7` FOREIGN KEY (`user`) REFERENCES `User` (`codeUser`);

--
-- Table structure for table `WikiText`
--
CREATE TABLE `WikiFile` (
  `revision` bigint(20) NOT NULL,
  `file` bigint(20) NOT NULL,
  PRIMARY KEY  (`revision`, `file`)
)
ENGINE=InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci;

--
-- Table structure for table `ModulesConfiguration`
--
CREATE TABLE `ModulesConfiguration` (
  `module` tinytext NOT NULL,
  `property` tinytext NOT NULL,
  `value` tinytext NOT NULL
)
ENGINE=InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ------------------------------------
-- default inserts... -----------------
-- ------------------------------------

SET NAMES 'utf8';
INSERT INTO `States` ( `codeState` , `name` , `country` , `code` )
VALUES ( '1', 'Rio Grande do Sul', 'Brasil', 'RS');

INSERT INTO `Cities` ( `codeCity` , `name` , `state` , `time` )
VALUES ('1', 'Porto Alegre', '1', '0');

INSERT INTO `Areas` (`codeArea`, `name`) VALUES (1, 'Ciências'),
(2, 'Matemática'),(3, 'História'),(4, 'Física'),(5, 'Biologia'),
(6, 'Literatura'),(7, 'Educação Física'),(8, 'Geografia'),
(9, 'Química'),(10, 'Língua Estrangeira'),(12, 'Música'),
(13, 'Filosofia'),(14, 'Educação Infantil'),(15, 'Artes');

INSERT INTO `ModulesConfiguration` (`module`, `property`, `value`)
VALUES ('admin', 'codeGroup', '1'),
('webfolio', 'richTextAboutMe', 'TRUE'),
('webfolio', 'emailRequired', 'TRUE');



INSERT INTO  `Files` (
`codeFile` ,
`mimeType` ,
`size` ,
`name` ,
`metadata` ,
`time`,
`data`
)
VALUES (
'1',  '',  '0',  'AdminPicture.png',  '',  '0',''
);





--
-- Setting up the administration user in the database
--
INSERT INTO `User` (`codeUser`, `username`, `time`, `name`, `password`, `active`, `email`, `address`, `codeCity`, `cep`, `url`, `birthDate`, `aboutMe`, `picture`)
VALUES
(1, 'admin', '' , 'Administrator User', '154ca39e6f9e5f97afadc06b6ce7de67', '1', 'juliano@hardfunstudios.com', '', 1, '', '', '', '', 1);


INSERT INTO `ACO` (`code`, `description`, `time`)
VALUES
(1, 'ADMINISTRATION ', 1187103195);

INSERT INTO `ACLUser` (`codeACO`, `codeUser`, `privilege`)
VALUES
(1, 1, 'admin_all');

INSERT INTO `Groups` (`codeGroup`, `description`, `managed`, `time`)
VALUES
(1, 'Administration', 'MANAGED', 1187103195);

INSERT INTO `GroupMember` (`codeGroup`, `codeUser`, `status`, `time`)
VALUES
(1, 1, 'ACTIVE', 1187103195);

-- ----------------------------------------------------------------------
-- EOF
