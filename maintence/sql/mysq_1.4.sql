-- Data de Criação: 08-Nov-2006 às 13:56
-- Versão do servidor: 5.0.24
-- versão do PHP: 5.1.4


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ACLGroup`
-- 

CREATE TABLE `ACLGroup` (
  `code` bigint(20) NOT NULL auto_increment,
  `codeACO` bigint(20) NOT NULL default '0',
  `codeGroup` bigint(20) NOT NULL default '0',
  `privilege` varchar(100) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ACLUser`
-- 

CREATE TABLE `ACLUser` (
  `codeACO` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `privilege` varchar(100) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`privilege`,`codeACO`,`codeUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ACLWorld`
-- 

CREATE TABLE `ACLWorld` (
  `codeACO` bigint(20) NOT NULL default '0',
  `privilege` varchar(100) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`codeACO`,`privilege`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ACO`
-- 

CREATE TABLE `ACO` (
  `code` bigint(20) NOT NULL auto_increment,
  `description` varchar(150) character set latin1 NOT NULL default '',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Access Custom List Object';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Agregator`
-- 

CREATE TABLE `Agregator` (
  `codeSource` int(11) NOT NULL,
  `keywords` text NOT NULL,
  `time` bigint(20) NOT NULL,
  PRIMARY KEY  (`codeSource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Areas`
-- 

CREATE TABLE `Areas` (
  `codeArea` tinyint(4) NOT NULL auto_increment,
  `name` varchar(50) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`codeArea`),
  KEY `codArea` (`codeArea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `BadExtensionsLibraries`
-- 

CREATE TABLE `BadExtensionsLibraries` (
  `libraryCode` int(11) NOT NULL default '0',
  `badExtension` char(4) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`libraryCode`,`badExtension`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Association of lib<->badExtensions';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `BlogComments`
-- 

CREATE TABLE `BlogComments` (
  `codeComment` bigint(20) NOT NULL auto_increment,
  `body` text character set latin1 NOT NULL,
  `codePost` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeComment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `BlogPosts`
-- 

CREATE TABLE `BlogPosts` (
  `codePost` bigint(20) NOT NULL auto_increment,
  `codeUser` bigint(20) NOT NULL default '0',
  `title` varchar(100) character set latin1 NOT NULL default '',
  `body` text character set latin1 NOT NULL,
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codePost`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `BlogProfiles`
-- 

CREATE TABLE `BlogProfiles` (
  `codeUser` int(11) NOT NULL default '0',
  `titleBlog` text character set latin1 NOT NULL,
  `text` text character set latin1 NOT NULL,
  `image` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ChatConnectedUsers`
-- 

CREATE TABLE `ChatConnectedUsers` (
  `codeConnect` int(11) NOT NULL auto_increment,
  `codeRoom` int(11) NOT NULL default '0',
  `codeUser` int(11) NOT NULL default '0',
  `entranceDate` bigint(20) NOT NULL default '0',
  `exitDate` bigint(20) NOT NULL default '0',
  `flag` enum('ONLINE','OFFLINE') character set latin1 NOT NULL default 'ONLINE',
  PRIMARY KEY  (`codeConnect`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Usuarios conectados no chat';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ChatMessages`
-- 

CREATE TABLE `ChatMessages` (
  `codeMessage` bigint(11) NOT NULL auto_increment,
  `codeRoom` int(11) NOT NULL default '0',
  `codeSender` int(11) NOT NULL default '0',
  `codeRecipient` int(11) NOT NULL default '0',
  `message` text character set latin1 NOT NULL,
  `userStyle` varchar(30) character set latin1 NOT NULL default '',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeMessage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Mesnagens do chat';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ChatRoom`
-- 

CREATE TABLE `ChatRoom` (
  `codeRoom` int(11) NOT NULL auto_increment,
  `name` varchar(100) character set latin1 NOT NULL default '',
  `description` tinytext character set latin1 NOT NULL,
  `infinity` char(1) character set latin1 NOT NULL default '',
  `beginDate` bigint(20) NOT NULL default '0',
  `endDate` bigint(20) NOT NULL default '0',
  `chatType` enum('PROJECT','COMMUNITY','COURSE','FREE') character set latin1 NOT NULL default 'PROJECT',
  `codeUser` int(11) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeRoom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ChatsCommunities`
-- 

CREATE TABLE `ChatsCommunities` (
  `codeRoom` int(11) NOT NULL default '0',
  `codeCommunity` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ChatsProject`
-- 

CREATE TABLE `ChatsProject` (
  `codeRoom` int(11) NOT NULL default '0',
  `codeProject` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Cities`
-- 

CREATE TABLE `Cities` (
  `codCidade` int(11) NOT NULL auto_increment,
  `name` varchar(100) character set latin1 NOT NULL default '',
  `state` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codCidade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Comments`
-- 

CREATE TABLE `Comments` (
  `codeComment` bigint(6) NOT NULL auto_increment,
  `name` varchar(50) character set latin1 NOT NULL default '',
  `codeUser` int(11) default NULL,
  `text` text character set latin1 NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeComment`),
  KEY `codComentario` (`codeComment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Communities`
-- 

CREATE TABLE `Communities` (
  `code` int(11) NOT NULL auto_increment,
  `description` tinytext character set latin1 NOT NULL,
  `name` varchar(30) character set latin1 NOT NULL default '',
  `codeGroup` bigint(20) NOT NULL default '0',
  `codeACO` bigint(20) NOT NULL,
  `status` enum('AUTHORIZED','NOT_AUTHORIZED') character set latin1 NOT NULL default 'NOT_AUTHORIZED',
  `image` bigint(20) default '0',
  `flagAuth` enum('REQUEST','ALLOW') character set latin1 NOT NULL default 'ALLOW',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityForums`
-- 

CREATE TABLE `CommunityForums` (
  `codeCommunity` bigint(20) NOT NULL default '0',
  `codeForum` bigint(20) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityNews`
-- 

CREATE TABLE `CommunityNews` (
  `code` int(11) NOT NULL auto_increment,
  `codeCommunity` int(11) NOT NULL default '0',
  `title` tinytext character set latin1 NOT NULL,
  `text` text character set latin1 NOT NULL,
  `codeUser` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityProjectJoins`
-- 

CREATE TABLE `CommunityProjectJoins` (
  `codeCommunity` int(11) NOT NULL default '0',
  `codeProject` int(11) NOT NULL default '0',
  `type` enum('REQUEST','INVITATION') character set latin1 NOT NULL default 'REQUEST',
  `status` enum('NOT_ANSWERED','REJECTED','ACCEPTED') character set latin1 NOT NULL default 'NOT_ANSWERED',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeCommunity`,`codeProject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityProjects`
-- 

CREATE TABLE `CommunityProjects` (
  `codeCommunity` int(11) NOT NULL default '0',
  `codeProject` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeCommunity`,`codeProject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `EnvSession`
-- 

CREATE TABLE `EnvSession` (
  `sessID` varchar(32) character set latin1 NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `timeStart` bigint(20) NOT NULL default '0',
  `timeEnd` bigint(20) NOT NULL default '0',
  `IP` int(11) NOT NULL default '0',
  `flagEnded` enum('TRUE','FALSE') character set latin1 NOT NULL default 'FALSE',
  `visibility` enum('VISIBLE','HIDDEN','BUSY') character set latin1 NOT NULL default 'VISIBLE',
  PRIMARY KEY  (`sessID`,`codeUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Files`
-- 

CREATE TABLE `Files` (
  `codeFile` bigint(20) NOT NULL,
  `data` longblob NOT NULL,
  `mimeType` varchar(100) NOT NULL,
  `size` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL,
  `metadata` varchar(255) NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeFile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `FilesLibraries`
-- 

CREATE TABLE `FilesLibraries` (
  `libraryCode` int(11) NOT NULL default '0',
  `filesCode` int(11) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  `referred` char(1) character set latin1 NOT NULL default 'n',
  `active` char(1) character set latin1 NOT NULL default 'y',
  `shared` char(1) character set latin1 NOT NULL default 'n',
  `versioned` char(1) character set latin1 NOT NULL default 'N',
  `version` varchar(10) character set latin1 NOT NULL default '1.0',
  PRIMARY KEY  (`filesCode`,`libraryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Association of lib<->files';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `FinderChatRoom`
-- 

CREATE TABLE `FinderChatRoom` (
  `code` int(11) NOT NULL auto_increment,
  `dateStart` int(11) NOT NULL default '0',
  `dateEnd` int(11) NOT NULL default '0',
  `codeStarter` int(11) NOT NULL default '0',
  `codeRequest` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `FinderMessages`
-- 

CREATE TABLE `FinderMessages` (
  `code` int(11) NOT NULL auto_increment,
  `codeRoom` int(11) NOT NULL default '0',
  `codeSender` int(11) NOT NULL default '0',
  `codeRecipient` int(11) NOT NULL default '0',
  `message` text character set latin1 NOT NULL,
  `status` enum('READ','NOT_READ') character set latin1 NOT NULL default 'NOT_READ',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ForumMessages`
-- 

CREATE TABLE `ForumMessages` (
  `code` bigint(20) NOT NULL auto_increment,
  `codeForum` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `title` varchar(100) character set latin1 NOT NULL default '',
  `body` text character set latin1 NOT NULL,
  `parent` bigint(20) NOT NULL default '0',
  `timePost` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ForumVisits`
-- 

CREATE TABLE `ForumVisits` (
  `codeUser` bigint(20) NOT NULL default '0',
  `codeForum` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Forums`
-- 

CREATE TABLE `Forums` (
  `code` bigint(20) NOT NULL auto_increment,
  `name` varchar(80) character set latin1 NOT NULL default '',
  `codeACO` bigint(20) NOT NULL default '0',
  `creationTime` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabela com os nome dos forums';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Friends`
-- 

CREATE TABLE `Friends` (
  `codeUser` bigint(20) NOT NULL default '0',
  `codeFriend` bigint(20) NOT NULL default '0',
  `comentary` tinytext character set latin1 NOT NULL,
  `status` enum('NOT_ANSWERED','REJECTED','ACCEPTED') character set latin1 NOT NULL default 'NOT_ANSWERED',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeUser`,`codeFriend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista de amigos de um usuario';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `GroupMember`
-- 

CREATE TABLE `GroupMember` (
  `codeGroup` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `status` enum('ACTIVE','RETIRED') character set latin1 NOT NULL default 'ACTIVE',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeGroup`,`codeUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `GroupMemberJoin`
-- 

CREATE TABLE `GroupMemberJoin` (
  `codeGroupMemberJoin` bigint(20) NOT NULL auto_increment,
  `codeUser` bigint(20) NOT NULL default '0',
  `codeGroup` bigint(20) NOT NULL default '0',
  `type` enum('INVITATION','REQUEST') character set latin1 NOT NULL default 'INVITATION',
  `status` enum('NOT_ANSWERED','REJECTED','ACCEPTED') character set latin1 NOT NULL default 'NOT_ANSWERED',
  `textRequest` tinytext character set latin1 NOT NULL,
  `textResponse` tinytext character set latin1 NOT NULL,
  `ackResponse` enum('ACK','NOT_ACK') character set latin1 NOT NULL default 'NOT_ACK',
  `timeResponse` bigint(20) NOT NULL default '0',
  `codeUserResponse` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeGroupMemberJoin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Controla os convites e os pedidos p/ participar de projeto';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Groups`
-- 

CREATE TABLE `Groups` (
  `codeGroup` bigint(20) NOT NULL auto_increment,
  `description` varchar(100) character set latin1 NOT NULL default '',
  `managed` enum('MANAGED','NOT_MANAGED') character set latin1 NOT NULL default 'NOT_MANAGED',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Library`
-- 

CREATE TABLE `Library` (
  `code` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='generic library';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `LogUploadFiles`
-- 

CREATE TABLE `LogUploadFiles` (
  `uploadType` enum('PROJECT','USER') character set latin1 NOT NULL default 'USER',
  `codeAnchor` int(11) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeAnchor`,`uploadType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `News`
-- 

CREATE TABLE `News` (
  `codeNews` int(4) NOT NULL auto_increment,
  `codeProject` int(4) NOT NULL default '0',
  `title` varchar(100) character set latin1 NOT NULL default '',
  `text` text character set latin1 NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeNews`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectAreas`
-- 

CREATE TABLE `ProjectAreas` (
  `codeProject` int(11) NOT NULL default '0',
  `codeArea` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeProject`,`codeArea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectBlogs`
-- 

CREATE TABLE `ProjectBlogs` (
  `codeBlog` int(11) NOT NULL auto_increment,
  `codeProject` int(11) NOT NULL default '0',
  `address` text NOT NULL,
  `title` varchar(200) NOT NULL default '',
  `type` enum('INTERNAL_SOURCE','EXTERNAL_SOURCE') default 'INTERNAL_SOURCE',
  PRIMARY KEY  (`codeBlog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectComments`
-- 

CREATE TABLE `ProjectComments` (
  `codeProject` int(11) NOT NULL default '0',
  `codeComment` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeProject`,`codeComment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectForums`
-- 

CREATE TABLE `ProjectForums` (
  `codeProject` bigint(20) NOT NULL default '0',
  `codeForum` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeProject`,`codeForum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectNews`
-- 

CREATE TABLE `ProjectNews` (
  `codeNews` bigint(20) NOT NULL auto_increment,
  `codeProject` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `title` varchar(100) character set latin1 NOT NULL default '',
  `text` text character set latin1 NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeNews`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Projects`
-- 

CREATE TABLE `Projects` (
  `codeProject` int(11) NOT NULL auto_increment,
  `title` varchar(100) character set latin1 NOT NULL default '',
  `description` text character set latin1 NOT NULL,
  `status` tinyint(4) NOT NULL default '1',
  `image` bigint(20) default '0',
  `hits` bigint(20) NOT NULL default '0',
  `codeGroup` bigint(20) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeProject`),
  KEY `codProjeto` (`codeProject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectsLibraries`
-- 

CREATE TABLE `ProjectsLibraries` (
  `projectCode` int(11) NOT NULL default '0',
  `libraryCode` int(11) NOT NULL default '0',
  PRIMARY KEY  (`projectCode`,`libraryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table take care of libraries associating files to proje';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `States`
-- 

CREATE TABLE `States` (
  `codeState` int(11) NOT NULL auto_increment,
  `name` varchar(20) character set latin1 NOT NULL default '',
  `country` varchar(20) character set latin1 NOT NULL default '',
  `code` char(3) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`codeState`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `StatusProjeto`
-- 

CREATE TABLE `StatusProjeto` (
  `code` tinyint(4) NOT NULL auto_increment,
  `name` varchar(40) character set latin1 NOT NULL default '',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `User`
-- 

CREATE TABLE `User` (
  `codeUser` int(11) NOT NULL auto_increment,
  `username` varchar(20) character set latin1 NOT NULL default '',
  `time` bigint(20) NOT NULL default '0',
  `name` varchar(100) character set latin1 NOT NULL default '',
  `password` varchar(100) character set latin1 NOT NULL default '',
  `active` char(1) character set latin1 NOT NULL default '',
  `email` varchar(100) character set latin1 NOT NULL default '',
  `address` varchar(150) character set latin1 NOT NULL default '',
  `codeCity` int(11) NOT NULL default '0',
  `cep` varchar(9) character set latin1 NOT NULL default '',
  `url` varchar(150) character set latin1 NOT NULL default '',
  `birthDate` bigint(20) NOT NULL default '0',
  `aboutMe` text character set latin1,
  `picture` bigint(20) default '0',
  PRIMARY KEY  (`codeUser`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `UserMessages`
-- 

CREATE TABLE `UserMessages` (
  `code` int(11) NOT NULL auto_increment,
  `message` tinytext character set latin1 NOT NULL,
  `codeUser` int(11) NOT NULL default '0',
  `codeTo` int(11) NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `UsersLibraries`
-- 

CREATE TABLE `UsersLibraries` (
  `libraryCode` int(11) NOT NULL default '0',
  `userCode` int(11) NOT NULL default '0',
  PRIMARY KEY  (`libraryCode`,`userCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Association of lib<->user';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Warnings`
-- 

CREATE TABLE `Warnings` (
  `codeWarning` bigint(20) NOT NULL auto_increment,
  `title` varchar(100) character set latin1 NOT NULL default '',
  `description` text character set latin1 NOT NULL,
  `timeStart` bigint(20) NOT NULL default '0',
  `timeEnd` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeWarning`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
