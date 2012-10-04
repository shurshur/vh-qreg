CREATE TABLE `regs` (
  `nick` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `secret` blob,
  `pwd` varchar(60) DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nick`)
);
