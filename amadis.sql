-- phpMyAdmin SQL Dump
-- version 2.6.0-rc3
-- http://www.phpmyadmin.net
-- 
-- Tempo de Generação: Set 12, 2005 at 04:54 PM
-- Versão do Servidor: 4.1.10
-- Versão do PHP: 5.0.1
-- 
-- Banco de Dados: `amadis`
-- 

USE mysql;
CREATE DATABASE amadis;
GRANT ALL PRIVILEGES on amadis.* TO amadis@localhost;
SET PASSWORD FOR amadis@localhost=password("MY_PASSWORD");

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Areas`
-- 

CREATE TABLE `Areas` (
  `codArea` tinyint(4) NOT NULL auto_increment,
  `nomArea` varchar(50) NOT NULL default '',
  `codPai` tinyint(4) NOT NULL default '0',
  `intGeracao` char(1) NOT NULL default '1',
  PRIMARY KEY  (`codArea`),
  KEY `codArea` (`codArea`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Arquivo`
-- 

CREATE TABLE `Arquivo` (
  `codeArquivo` bigint(20) NOT NULL auto_increment,
  `dados` longblob NOT NULL,
  `tipoMime` varchar(100) NOT NULL default '',
  `tamanho` int(11) NOT NULL default '0',
  `nome` varchar(150) NOT NULL default '',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeArquivo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Avisos`
-- 

CREATE TABLE `Avisos` (
  `codeAviso` bigint(20) NOT NULL auto_increment,
  `titulo` varchar(100) NOT NULL default '',
  `descricao` text NOT NULL,
  `tempoInicio` bigint(20) NOT NULL default '0',
  `tempoFim` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeAviso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `BadExtensionsLibraries`
-- 

CREATE TABLE `BadExtensionsLibraries` (
  `libraryCode` int(11) NOT NULL default '0',
  `badExtension` char(4) NOT NULL default '',
  PRIMARY KEY  (`libraryCode`,`badExtension`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Association of lib<->badExtensions';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Cidades`
-- 

CREATE TABLE `Cidades` (
  `codCidade` int(11) NOT NULL auto_increment,
  `nomCidade` varchar(100) NOT NULL default '',
  `codEstado` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codCidade`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Comentarios`
-- 

CREATE TABLE `Comentarios` (
  `codComentario` bigint(6) NOT NULL auto_increment,
  `desNome` varchar(50) NOT NULL default '',
  `codeUser` int(11) default NULL,
  `desComentario` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codComentario`),
  KEY `codComentario` (`codComentario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Communities`
-- 

CREATE TABLE `Communities` (
  `code` int(11) NOT NULL auto_increment,
  `description` tinytext NOT NULL,
  `name` varchar(30) NOT NULL default '',
  `status` enum('AUTHORIZED','NOT_AUTHORIZED') NOT NULL default 'NOT_AUTHORIZED',
  `image` bigint(20) NOT NULL default '3',
  `flagAuth` enum('REQUEST','ALLOW') NOT NULL default 'ALLOW',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityMemberJoin`
-- 

CREATE TABLE `CommunityMemberJoin` (
  `codeCommunity` int(11) NOT NULL default '0',
  `codeUser` int(11) NOT NULL default '0',
  `type` enum('REQUEST','INVITATION') NOT NULL default 'REQUEST',
  `status` enum('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL default 'NOT_ANSWERED',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeCommunity`,`codeUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityMembers`
-- 

CREATE TABLE `CommunityMembers` (
  `codeCommunity` int(11) NOT NULL default '0',
  `codeUser` int(11) NOT NULL default '0',
  `flagAdmin` enum('MEMBER','ADMIN') NOT NULL default 'MEMBER',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeCommunity`,`codeUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityNews`
-- 

CREATE TABLE `CommunityNews` (
  `code` int(11) NOT NULL auto_increment,
  `codeCommunity` int(11) NOT NULL default '0',
  `title` tinytext NOT NULL,
  `text` text NOT NULL,
  `codeUser` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityProjectJoins`
-- 

CREATE TABLE `CommunityProjectJoins` (
  `codeCommunity` int(11) NOT NULL default '0',
  `codeProject` int(11) NOT NULL default '0',
  `type` enum('REQUEST','INVITATION') NOT NULL default 'REQUEST',
  `status` enum('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL default 'NOT_ANSWERED',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeCommunity`,`codeProject`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CommunityProjects`
-- 

CREATE TABLE `CommunityProjects` (
  `codeCommunity` int(11) NOT NULL default '0',
  `codeProject` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeCommunity`,`codeProject`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `CursoParticipantes`
-- 

CREATE TABLE `CursoParticipantes` (
  `codeCurso` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `flagCoordenador` char(1) NOT NULL default '0',
  `flagAutorizado` char(1) NOT NULL default '0',
  `tempo` int(20) NOT NULL default '0',
  `matriculado` char(1) NOT NULL default '0',
  `matricula` int(20) NOT NULL auto_increment,
  PRIMARY KEY  (`matricula`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `DiarioComentario`
-- 

CREATE TABLE `DiarioComentario` (
  `codComment` bigint(20) NOT NULL auto_increment,
  `body` text NOT NULL,
  `codePost` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codComment`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `DiarioPosts`
-- 

CREATE TABLE `DiarioPosts` (
  `codePost` bigint(20) NOT NULL auto_increment,
  `codeUser` bigint(20) NOT NULL default '0',
  `titulo` varchar(100) NOT NULL default '',
  `texto` text NOT NULL,
  `tempo` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codePost`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `DiarioProfile`
-- 

CREATE TABLE `DiarioProfile` (
  `codeUser` int(11) NOT NULL default '0',
  `tituloDiario` text NOT NULL,
  `textoProfile` text NOT NULL,
  `image` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `EnvSession`
-- 

CREATE TABLE `EnvSession` (
  `sessID` varchar(32) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `timeStart` bigint(20) NOT NULL default '0',
  `timeEnd` bigint(20) NOT NULL default '0',
  `IP` int(11) NOT NULL default '0',
  `flagEnded` enum('TRUE','FALSE') NOT NULL default 'FALSE',
  `visibility` enum('VISIBLE','HIDDEN','BUSY') NOT NULL default 'VISIBLE',
  PRIMARY KEY  (`sessID`,`codeUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Estados`
-- 

CREATE TABLE `Estados` (
  `codEstado` int(11) NOT NULL auto_increment,
  `nomEstado` varchar(20) NOT NULL default '',
  `desPais` varchar(20) NOT NULL default '',
  `desSigla` char(3) NOT NULL default '',
  PRIMARY KEY  (`codEstado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `FilesLibraries`
-- 

CREATE TABLE `FilesLibraries` (
  `libraryCode` int(11) NOT NULL default '0',
  `filesCode` int(11) NOT NULL default '0',
  PRIMARY KEY  (`filesCode`,`libraryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Association of lib<->files';

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `FinderMessages`
-- 

CREATE TABLE `FinderMessages` (
  `code` int(11) NOT NULL auto_increment,
  `codeRoom` int(11) NOT NULL default '0',
  `codeSender` int(11) NOT NULL default '0',
  `codeRecipient` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `status` enum('READ','NOT_READ') NOT NULL default 'NOT_READ',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ForumMessages`
-- 

CREATE TABLE `ForumMessages` (
  `code` bigint(20) NOT NULL auto_increment,
  `codeForum` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `body` text NOT NULL,
  `parent` bigint(20) NOT NULL default '0',
  `timePost` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ForumVisits`
-- 

CREATE TABLE `ForumVisits` (
  `codeUser` bigint(20) NOT NULL default '0',
  `codeForum` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Forums`
-- 

CREATE TABLE `Forums` (
  `code` bigint(20) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `creationTime` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Tabela com os nome dos forums';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Friends`
-- 

CREATE TABLE `Friends` (
  `codeUser` bigint(20) NOT NULL default '0',
  `codeFriend` bigint(20) NOT NULL default '0',
  `comentary` tinytext NOT NULL,
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeUser`,`codeFriend`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Lista de amigos de um usuario';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `GroupMember`
-- 

CREATE TABLE `GroupMember` (
  `codeGroup` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `status` enum('ACTIVE','RETIRED') NOT NULL default 'ACTIVE',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeGroup`,`codeUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `GroupMemberJoin`
-- 

CREATE TABLE `GroupMemberJoin` (
  `codeGroupMemberJoin` bigint(20) NOT NULL auto_increment,
  `codeUser` bigint(20) NOT NULL default '0',
  `codeGroup` bigint(20) NOT NULL default '0',
  `type` enum('INVITATION','REQUEST') NOT NULL default 'INVITATION',
  `status` enum('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL default 'NOT_ANSWERED',
  `textRequest` tinytext NOT NULL,
  `textResponse` tinytext NOT NULL,
  `ackResponse` enum('ACK','NOT_ACK') NOT NULL default 'NOT_ACK',
  `timeResponse` bigint(20) NOT NULL default '0',
  `codeUserResponse` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeGroupMemberJoin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Controla os convites e os pedidos p/ participar de projeto';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Groups`
-- 

CREATE TABLE `Groups` (
  `codeGroup` bigint(20) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL default '',
  `managed` enum('MANAGED','NOT_MANAGED') NOT NULL default 'NOT_MANAGED',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeGroup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Library`
-- 

CREATE TABLE `Library` (
  `code` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='generic library';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `LogUploadFiles`
-- 

CREATE TABLE `LogUploadFiles` (
  `uploadType` enum('PROJECT','USER') NOT NULL default 'USER',
  `codeAnchor` int(11) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeAnchor`,`uploadType`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Noticias`
-- 

CREATE TABLE `Noticias` (
  `code` int(4) NOT NULL auto_increment,
  `codProjeto` int(4) NOT NULL default '0',
  `desTitulo` varchar(100) NOT NULL default '',
  `desNoticia` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectForums`
-- 

CREATE TABLE `ProjectForums` (
  `codeProject` bigint(20) NOT NULL default '0',
  `codeForum` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeProject`,`codeForum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectNews`
-- 

CREATE TABLE `ProjectNews` (
  `codeNews` bigint(20) NOT NULL auto_increment,
  `codeProject` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `text` text NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeNews`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Projects`
-- 

CREATE TABLE `Projects` (
  `codeProject` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL default '0',
  `image` bigint(20) default '2',
  `hits` bigint(20) NOT NULL default '0',
  `codeGroup` bigint(20) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeProject`),
  KEY `codProjeto` (`codeProject`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjectsLibraries`
-- 

CREATE TABLE `ProjectsLibraries` (
  `projectCode` int(11) NOT NULL default '0',
  `libraryCode` int(11) NOT NULL default '0',
  PRIMARY KEY  (`projectCode`,`libraryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table take care of libraries associating files to proje';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjetoAreas`
-- 

CREATE TABLE `ProjetoAreas` (
  `codProjeto` int(11) NOT NULL default '0',
  `codArea` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`codProjeto`,`codArea`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ProjetoComentario`
-- 

CREATE TABLE `ProjetoComentario` (
  `codProjeto` int(11) NOT NULL default '0',
  `codComentario` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codProjeto`,`codComentario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `StatusProjeto`
-- 

CREATE TABLE `StatusProjeto` (
  `code` tinyint(4) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `User`
-- 

CREATE TABLE `User` (
  `codeUser` int(11) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL default '',
  `time` bigint(20) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `active` char(1) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `endereco` varchar(150) NOT NULL default '',
  `codCidade` int(11) NOT NULL default '0',
  `cep` varchar(9) NOT NULL default '',
  `telefone` varchar(15) NOT NULL default '',
  `url` varchar(150) NOT NULL default '',
  `datNascimento` bigint(20) NOT NULL default '0',
  `historico` text,
  `foto` bigint(20) default '1',
  PRIMARY KEY  (`codeUser`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `UserMessages`
-- 

CREATE TABLE `UserMessages` (
  `code` int(11) NOT NULL auto_increment,
  `message` tinytext NOT NULL,
  `codeUser` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `UsersLibraries`
-- 

CREATE TABLE `UsersLibraries` (
  `libraryCode` int(11) NOT NULL default '0',
  `userCode` int(11) NOT NULL default '0',
  PRIMARY KEY  (`libraryCode`,`userCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Association of lib<->user';


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `chat_mensagens`
-- 

CREATE TABLE `chat_mensagens` (
  `codMensagem` bigint(20) NOT NULL auto_increment,
  `codSalaChat` mediumint(9) NOT NULL default '0',
  `codRemetente` int(11) NOT NULL default '0',
  `codDestinatario` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  `desMensagem` text NOT NULL,
  `desTag` text NOT NULL,
  PRIMARY KEY  (`codMensagem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `chat_sala`
-- 

CREATE TABLE `chat_sala` (
  `codSala` bigint(20) NOT NULL auto_increment,
  `nomSala` varchar(30) NOT NULL default '',
  `desSala` varchar(60) NOT NULL default '',
  `flaPermanente` char(1) NOT NULL default '0',
  `datInicio` bigint(20) NOT NULL default '0',
  `datFim` bigint(20) NOT NULL default '0',
  `tempo` bigint(20) NOT NULL default '0',
  `codeUser` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codSala`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `chat_sala_conectados`
-- 

CREATE TABLE `chat_sala_conectados` (
  `codConexao` bigint(20) NOT NULL auto_increment,
  `codSala` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `datEntrou` bigint(20) NOT NULL default '0',
  `datSaiu` bigint(20) NOT NULL default '0',
  `flaOnline` char(1) NOT NULL default '0',
  PRIMARY KEY  (`codConexao`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `comunidadeChats`
-- 

CREATE TABLE `comunidadeChats` (
  `codSala` int(11) NOT NULL default '0',
  `codComunidade` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `projetoChats`
-- 

CREATE TABLE `projetoChats` (
  `codSala` int(11) NOT NULL default '0',
  `codProjeto` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
        


