use mFlow;
select * from woht;
SELECT id from woht ORDER BY id desc LIMIT 1;
SELECT id from umf ORDER BY id desc LIMIT 1;
INSERT INTO umf (id, priLev, passwd, name, lsl) VALUES ('001', '0', 'passwd1', 'name', CURRENT_TIMESTAMP()) ON DUPLICATE KEY UPDATE priLev = '0', passwd ='passwd2', name = 'name', lsl = CURRENT_TIMESTAMP(); 
SELECT * FROM mFlow.umf;
select id, qty, td as data from poht inner join podt on poht.id = podt.poid;