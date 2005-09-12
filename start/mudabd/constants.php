<?

$tabsArray = array(
		'Finder_Chat',
		'Finder_Mensagens',
		'Forum',
		'Mensagem',
		'areas',
		'arquivos',
		'biblioteca_doc',
		'chat_mensagens',
		'chat_sala',
		'chat_sala_conectados',
		'cidade',
		'comentarios',
		'diario',
		'diarioComent',
		'email_mensagens',
		'estado',
		'noticias',
		'projeto',
		'projetoAreas',
		'projetoMatricula',
		'projetoStatus',
		'user',
		'admin',
		'anuncios',
		'categoria',
		'ciclo',
		'classetabela',
		'compromisso',
		'config',
		'contatos',
		'email_users_destino',
		'escola',
		'forumImagem',
		'mensagensLidas',
		'novidades',
		'oficina',
		'oficinaCoordenador',
		'oficinaMatricula',
		'professorTurma',
		'tipo_cursos',
		'turma',
		'userClasse',
		'userTurma',
		'usuario_categoria');


$cr_user= "CREATE TABLE `User` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_user = array ('codUser','nomUser','tempo','nomPessoa','desSenha','flaAtivo','strEMailAlt',
'desEndereco','codCidade','desCEP','desTelefone','desUrl','datNascimento','desHistorico');


$cr_Finder_Chat = "CREATE TABLE `FinderChatRoom` (
  `code` int(11) NOT NULL auto_increment,
  `dateStart` int(11) NOT NULL default '0',
  `dateEnd` int(11) NOT NULL default '0',
  `codeStarter` int(11) NOT NULL default '0',
  `codeRequest` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_Finder_Chat = array('codFinderChat','datInicio','datFim','codIniciador','codRequisitado');


$cr_Finder_Mensagens = "CREATE TABLE `FinderMessages` (
  `code` int(11) NOT NULL auto_increment,
  `codeRoom` int(11) NOT NULL default '0',
  `codeSender` int(11) NOT NULL default '0',
  `codeRecipient` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `status` enum('READ','NOT_READ') NOT NULL default 'NOT_READ',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_Finder_Mensagens = array('codMensagem','codFinderChat','codRemetente','codDestinatario',
'desMensagem','flaLida','tempo');


$cr_Forum = "CREATE TABLE `Forums` (
  `code` bigint(20) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `creationTime` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Tabela com os nome dos forums';";

$flds_Forum = array('codForum','nomForum','tempo');


$cr_Mensagem = "CREATE TABLE `ForumMessages` (
  `code` bigint(20) NOT NULL auto_increment,
  `codeForum` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `body` text NOT NULL,
  `parent` bigint(20) NOT NULL default '0',
  `timePost` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_Mensagem = array('codMensagem','codForum','codAutor','strTitulo','desCorpo','codMensagemPai',
'tempo');


$cr_admin = "";

$flds_admin = array();


$cr_anuncios = "";

$flds_anuncios = array();


$cr_areas = "CREATE TABLE `Areas` (
  `codArea` tinyint(4) NOT NULL auto_increment,
  `nomArea` varchar(50) NOT NULL default '',
  `codPai` tinyint(4) NOT NULL default '0',
  `intGeracao` char(1) NOT NULL default '1',
  PRIMARY KEY  (`codArea`),
  KEY `codArea` (`codArea`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_areas = array('codArea','nomArea','codPai','intGeracao');


$cr_arquivos = "CREATE TABLE `Arquivo` (
  `codeArquivo` bigint(20) NOT NULL auto_increment,
  `dados` longblob NOT NULL,
  `tipoMime` varchar(100) NOT NULL default '',
  `tamanho` int(11) NOT NULL default '0',
  `nome` varchar(150) NOT NULL default '',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codeArquivo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_arquivos = array('codArquivo','desDados','desTipoMime','desTamanho','desNome','tempo');


$cr_biblioteca_doc = "CREATE TABLE `biblioteca_doc` (
  `codDoc` bigint(20) NOT NULL auto_increment,
  `desTitulo` varchar(100) NOT NULL default '',
  `codUser` mediumint(9) NOT NULL default '0',
  `codeArquivo` bigint(20) NOT NULL default '0',
  `tempo` bigint(11) NOT NULL default '0',
  PRIMARY KEY  (`codDoc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_biblioteca_doc = array('codDoc','desTitulo','codUser','codArquivo','tempo');


$cr_categoria = "";

$flds_categoria = array();


$cr_chat_mensagens = "CREATE TABLE `chat_mensagens` (
  `codMensagem` bigint(20) NOT NULL auto_increment,
  `codSalaChat` mediumint(9) NOT NULL default '0',
  `codRemetente` int(11) NOT NULL default '0',
  `codDestinatario` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  `desMensagem` text NOT NULL,
  `desTag` text NOT NULL,
  PRIMARY KEY  (`codMensagem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;";

$flds_chat_mensagens = array('codMensagem','codSalaChat','codRemetente','codDestinatario','tempo',
'desMensagem','desTag');


$cr_chat_sala= "CREATE TABLE `chat_sala` (
  `codSala` bigint(20) NOT NULL auto_increment,
  `nomSala` varchar(30) NOT NULL default '',
  `desSala` varchar(60) NOT NULL default '',
  `tipoPai` char(1) NOT NULL default '',
  `codPai` int(11) NOT NULL default '0',
  `codPlataforma` tinyint(4) NOT NULL default '0',
  `flaPermanente` char(1) NOT NULL default '0',
  `datInicio` bigint(20) NOT NULL default '0',
  `datFim` bigint(20) NOT NULL default '0',
  `tempo` bigint(20) NOT NULL default '0',
  `codeUser` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codSala`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_chat_sala = array('codSala','nomSala','desSala','tipoPai','codPai','codPlataforma',
'flaPermanente','datInicio','datFim','tempo');


$cr_chat_sala_conectados = "CREATE TABLE `chat_sala_conectados` (
  `codConexao` bigint(20) NOT NULL auto_increment,
  `codSala` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `datEntrou` bigint(20) NOT NULL default '0',
  `datSaiu` bigint(20) NOT NULL default '0',
  `flaOnline` char(1) NOT NULL default '0',
  PRIMARY KEY  (`codConexao`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_chat_sala_conectados = array('codConexao','codSala','codUser','datEntrou','datSaiu',
'flaOnline');


$cr_ciclo = "";

$flds_ciclo = array();


$cr_cidade = "CREATE TABLE `Cidades` (
  `codCidade` int(11) NOT NULL auto_increment,
  `nomCidade` varchar(100) NOT NULL default '',
  `codEstado` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codCidade`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_cidade = array('codCidade','nomCidade','codEstado','tempo');


$cr_classetabela = "";

$flds_classetabela = array();


$cr_comentarios = "CREATE TABLE `Comentarios` (
  `codComentario` bigint(6) NOT NULL auto_increment,
  `desNome` varchar(50) NOT NULL default '',
  `codeUser` int(11) default NULL,
  `desComentario` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codComentario`),
  KEY `codComentario` (`codComentario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_comentarios = array('codComentario','desNome','codUser','desComentario','tempo');


$cr_compromisso = "";

$flds_compromisso = array();


$cr_config = "";

$flds_config = array();


$cr_contatos = "";

$flds_contatos = array();


$cr_diario = "CREATE TABLE `DiarioPosts` (
  `codePost` bigint(20) NOT NULL auto_increment,
  `codeUser` bigint(20) NOT NULL default '0',
  `titulo` varchar(100) NOT NULL default '',
  `texto` text NOT NULL,
  `tempo` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codePost`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_diario = array('codTexto','desTexto','tempo');


$cr_diarioComent = "CREATE TABLE `DiarioComentario` (
  `codComment` bigint(20) NOT NULL auto_increment,
  `body` text NOT NULL,
  `codePost` bigint(20) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codComment`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_diarioComent = array('codComent','desTexto','codTexto','codUser','tempo');


$cr_email_mensagens = "";

$flds_email_mensagens = array();


$cr_email_users_destino = "";

$flds_email_users_destino = array();


$cr_escola = "";

$flds_escola = array();


$cr_estado = "CREATE TABLE `Estados` (
  `codEstado` int(11) NOT NULL auto_increment,
  `nomEstado` varchar(20) NOT NULL default '',
  `desPais` varchar(20) NOT NULL default '',
  `desSigla` char(3) NOT NULL default '',
  PRIMARY KEY  (`codEstado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_estado = array('codEstado','nomEstado','desPais','desSigla');


$cr_forumImagem = "";

$flds_forumImagem = array();


$cr_mensagensLidas = "";

$flds_mensagensLidas = array();


$cr_noticias = "CREATE TABLE `Noticias` (
  `code` int(4) NOT NULL auto_increment,
  `codProjeto` int(4) NOT NULL default '0',
  `desTitulo` varchar(100) NOT NULL default '',
  `desNoticia` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_noticias = array('codNoticia','desNoticia','tempo');


$cr_novidades = "";

$flds_novidades = array();


$cr_oficina = "";

$flds_oficina = array();


$cr_oficinaCoordenador = "";

$flds_oficinaCoordenador = array();


$cr_oficinaMatricula = "";

$flds_oficinaMatricula = array();


$cr_professorTurma = "";

$flds_professorTurma = array();


$cr_projeto = "CREATE TABLE `Projetos` (
  `code` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL default '0',
  `image` bigint(20) default '2',
  `hits` bigint(20) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code`),
  KEY `codProjeto` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_projeto = array('codProjeto','desTitulo','desProjeto','flaEstado','hits','tempo');


$cr_projetoStatus = "CREATE TABLE `StatusProjeto` (
  `code` tinyint(4) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_projetoStatus = array('codStatus','desStatus');


$cr_projetoAreas = "CREATE TABLE `ProjetoAreas` (
  `codProjeto` int(11) NOT NULL default '0',
  `codArea` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`codProjeto`,`codArea`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$flds_projetoAreas = array('codProjeto','codArea');


$cr_projetoMatricula = "CREATE TABLE `ProjectMemberJoin` (
  `codeUser` bigint(20) NOT NULL default '0',
  `codeProject` bigint(20) NOT NULL default '0',
  `type` enum('INVITATION','REQUEST') NOT NULL default 'INVITATION',
  `status` enum('NOT_ANSWERED','REJECTED','ACCEPTED') NOT NULL default 'NOT_ANSWERED',
  `time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codeUser`,`codeProject`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Controla os convites e os pedidos p/ participar de projeto';";

$flds_projetoMatricula = array('codUser','codProjeto','tempo');


$cr_tipo_cursos = "";

$flds_tipo_cursos = array();


$cr_turma = "";

$flds_turma = array();


$cr_userClasse = "";

$flds_userClasse = array();


$cr_userTurma = "";

$flds_userTurma = array();


$cr_usuario_categoria = "";

$flds_usuario_categoria = array();


$cr_Ambiente_Sessoes = "CREATE TABLE `EnvSession` (
  `sessID` varchar(32) NOT NULL default '0',
  `codeUser` bigint(20) NOT NULL default '0',
  `timeStart` bigint(20) NOT NULL default '0',
  `timeEnd` bigint(20) NOT NULL default '0',
  `IP` int(11) NOT NULL default '0',
  `flagEnded` enum('TRUE','FALSE') NOT NULL default 'FALSE',
  `visibility` enum('VISIBLE','HIDDEN','BUSY') NOT NULL default 'VISIBLE',
  PRIMARY KEY  (`sessID`,`codeUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

?>
