START TRANSACTION;

/* DROP old tables */
DROP TABLE IF EXISTS `biblioteca_doc`, `chat_mensagens`, `chat_sala`, `chat_sala_conectados`, `CommunityMemberJoin`, `CommunityMembers`;
DROP TABLE IF EXISTS `CursoParticipantes`,`comunidadeChats`, `projetoChats`;


/* ALTER TABLEs */
ALTER TABLE `Communities` ADD `codeACO` BIGINT NOT NULL AFTER `codeGroup` ;


DROP TABLE IF EXISTS `ProjectBlogs`;

CREATE TABLE IF NOT EXISTS `ProjectBlogs` (
 `codeBlog` int(11) NOT NULL auto_increment,
 `codeProject` int(11) NOT NULL default '0',
 `address` text NOT NULL,
 `title` varchar(200) NOT NULL default '',
 `type` enum('INTERNAL_SOURCE','EXTERNAL_SOURCE') default 'INTERNAL_SOURCE',
 PRIMARY KEY  (`codeBlog`)
) ENGINE=INNODB CHARSET=utf8;


CREATE TABLE `Agregator` (
 `codeSource` int(11) NOT NULL,
 `keywords` text NOT NULL,
 `time` bigint(20) NOT NULL,
 PRIMARY KEY  (`codeSource`)
) ENGINE=innodb CHARSET=utf8;

/*Rename table Arquivos para Files*/
RENAME TABLE `Arquivo` TO `Files`;
/*
CREATE TABLE `Files` (
`codeArquivo` bigint( 20 ) NOT NULL AUTO_INCREMENT ,
`dados` longblob NOT NULL ,
`tipoMime` varchar( 100 ) NOT NULL default '',
`tamanho` int( 11 ) NOT NULL default '0',
`nome` varchar( 150 ) NOT NULL default '',
`metaDados` varchar( 255 ) NOT NULL default '',
`tempo` int( 11 ) NOT NULL default '0',
PRIMARY KEY ( `codeArquivo` )
) ENGINE = InnoDB CHARSET=utf8;


INSERT INTO `Files`
SELECT *
FROM  ;
*/

/*change Files table structure*/
ALTER TABLE `Files` CHANGE `codeArquivo` `codeFile` BIGINT( 20 ) NOT NULL ;
ALTER TABLE `Files` CHANGE `dados` `data` LONGBLOB NOT NULL ;
ALTER TABLE `Files` CHANGE `tipoMime` `mimeType` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Files` CHANGE `tamanho` `size` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `Files` CHANGE `nome` `name` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Files` CHANGE `metaDados` `metadata` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `Files` CHANGE `tempo` `time` INT( 11 ) NOT NULL DEFAULT '0';

/*Translate all the database*/
ALTER TABLE `Avisos` RENAME TO `Warnings`,
 CHANGE COLUMN `codeAviso` `codeWarning` BIGINT(20) NOT NULL DEFAULT NULL AUTO_INCREMENT,
 CHANGE COLUMN `titulo` `title` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `descricao` `description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `tempoInicio` `timeStart` BIGINT(20) NOT NULL DEFAULT 0,
 CHANGE COLUMN `tempoFim` `timeEnd` BIGINT(20) NOT NULL DEFAULT 0;

ALTER TABLE `Areas` CHANGE COLUMN `codArea` `codeArea` TINYINT(4) NOT NULL DEFAULT NULL AUTO_INCREMENT,
 CHANGE COLUMN `nomArea` `name` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 DROP COLUMN `codPai`,
 DROP COLUMN `intGeracao`;
 
 
 ALTER TABLE `Cidades` RENAME TO `Cities`,
 CHANGE COLUMN `nomCidade` `name` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `codEstado` `state` INTEGER NOT NULL DEFAULT 0,
 CHANGE COLUMN `tempo` `time` INTEGER NOT NULL DEFAULT 0;
 
 ALTER TABLE `Comentarios` RENAME TO `Comments`,
 CHANGE COLUMN `codComentario` `codeComment` BIGINT(6) NOT NULL DEFAULT NULL AUTO_INCREMENT,
 CHANGE COLUMN `desNome` `name` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `desComentario` `text` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `tempo` `time` INTEGER NOT NULL DEFAULT 0;
 
 ALTER TABLE `DiarioComentario` RENAME TO `BlogComments`,
 CHANGE COLUMN `codComment` `codeComment` BIGINT(20) NOT NULL DEFAULT NULL AUTO_INCREMENT;
 
 ALTER TABLE `DiarioPosts` RENAME TO `BlogPosts`,
 CHANGE COLUMN `titulo` `title` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `texto` `body` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `tempo` `time` BIGINT(20) NOT NULL DEFAULT 0;
 
 ALTER TABLE `DiarioProfile` RENAME TO `BlogProfiles`,
 CHANGE COLUMN `tituloDiario` `titleBlog` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `textoProfile` `text` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';
 
 ALTER TABLE `Estados` RENAME TO `States`,
 CHANGE COLUMN `codEstado` `codeState` INTEGER NOT NULL DEFAULT NULL AUTO_INCREMENT,
 CHANGE COLUMN `nomEstado` `name` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `desPais` `country` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `desSigla` `code` CHAR(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';
 
 ALTER TABLE `Noticias` RENAME TO `News`,
 CHANGE COLUMN `code` `codeNews` INT(4) NOT NULL DEFAULT NULL AUTO_INCREMENT,
 CHANGE COLUMN `codProjeto` `codeProject` INT(4) NOT NULL DEFAULT 0,
 CHANGE COLUMN `desTitulo` `title` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `desNoticia` `text` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
 CHANGE COLUMN `tempo` `time` INTEGER NOT NULL DEFAULT 0;
 
 ALTER TABLE `ProjetoAreas` RENAME TO `ProjectAreas`,
 CHANGE COLUMN `codProjeto` `codeProject` INTEGER NOT NULL DEFAULT 0,
 CHANGE COLUMN `codArea` `codeArea` INTEGER NOT NULL DEFAULT 0;
 
ALTER TABLE `ProjetoComentario` RENAME TO `ProjectComments`,
 CHANGE COLUMN `codProjeto` `codeProject` INTEGER NOT NULL DEFAULT 0,
 CHANGE COLUMN `codComentario` `codeComment` BIGINT(20) NOT NULL DEFAULT 0;
 
 ALTER TABLE `User` CHANGE COLUMN `endereco` `address` VARCHAR(150)  NOT NULL DEFAULT '',
 CHANGE COLUMN `codCidade` `codeCity` INTEGER NOT NULL DEFAULT 0,
 DROP COLUMN `telefone`,
 CHANGE COLUMN `datNascimento` `birthDate` BIGINT(20) NOT NULL DEFAULT 0,
 CHANGE COLUMN `foto` `picture` BIGINT(20) DEFAULT 0;
 
ALTER TABLE `UsersLibraries` CHANGE `userCode` `codeUser` BIGINT( 20 ) NOT NULL ;
ALTER TABLE `FilesLibraries` CHANGE `filesCode` `codeFile` INT( 11 ) NOT NULL ,
CHANGE COLUMN `libraryCode` `codeLibrary` INT( 11 ) NOT NULL ;

/*TODO Change all tables to InnoDB*/
ALTER TABLE `User`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ACLGroup` ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ACLUser`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ACLWorld`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ACO`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Areas`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Warnings`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `BadExtensionsLibraries`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ChatConnectedUsers`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ChatMessages`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ChatRoom`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ChatsCommunities`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ChatsProject`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Cities`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Comments`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Communities`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `CommunityForums`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `CommunityNews`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `CommunityProjectJoins`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `CommunityProjects`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `BlogComments`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `BlogPosts`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `BlogProfiles`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `EnvSession`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `States`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Files`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `FilesLibraries`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `FinderChatRoom`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `FinderMessages`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ForumMessages`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ForumVisits`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Forums`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Friends`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `GroupMember`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `GroupMemberJoin`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Groups`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Library`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `LogUploadFiles`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `News`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ProjectBlogs`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ProjectForums`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ProjectNews`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `Projects`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ProjectsLibraries`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ProjectAreas`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `ProjectComments`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `StatusProjeto`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `UserMessages`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `UsersLibraries`  ENGINE = innodb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

COMMIT;
