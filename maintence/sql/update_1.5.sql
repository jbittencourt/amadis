
CREATE TABLE ModulesConfiguration (
  `module` tinytext NOT NULL,
  `property` tinytext NOT NULL,
  `value` tinytext NOT NULL
)
ENGINE=InnoDB
CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO `ModulesConfiguration` (`module`, `property`, `value`)
VALUES ('admin', 'codeGroup', '1'),
('webfolio', 'richTextAboutMe', 'TRUE'),
('webfolio', 'emailRequired', 'FALSE');

--
-- Insert a new group ADMINISTRATION
--
INSERT INTO `ACO` (`code`, `description`, `time`)
VALUES
(28, 'ADMINISTRATION ', 1187103195);

INSERT INTO `ACLUser` (`codeACO`, `codeUser`, `privilege`)
VALUES
(28, 1, 'admin_all');

INSERT INTO `Groups` (`codeGroup`, `description`, `managed`, `time`)
VALUES
(30, 'Administration', 'MANAGED', 1187103195);

INSERT INTO `GroupMember` (`codeGroup`, `codeUser`, `status`, `time`)
VALUES
(30, 1, 'ACTIVE', 1187103195);
