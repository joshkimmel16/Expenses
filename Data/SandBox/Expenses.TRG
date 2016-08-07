TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER `UUID` BEFORE INSERT ON `Expenses` FOR EACH ROW BEGIN\n    IF (SELECT Count(UUID) FROM Expenses)=0 THEN\n        SET NEW.UUID = 0;\n    ELSE\n    	SET NEW.UUID = (SELECT MAX(UUID) FROM Expenses)+1;\n    END IF;\nEND'
sql_modes=0
definers='root@localhost'
client_cs_names='utf8'
connection_cl_names='utf8_general_ci'
db_cl_names='latin1_swedish_ci'
