CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_run_update_saldos`(in p_cc_id int,in p_fecha_loop date, in p_fecha_hasta date)
    COMMENT 'call en la madrugada del día siguiente, indicar la fecha de procesamiento'
BEGIN

-- declare fecha_loop date;
-- declare fecha_hasta date;
-- declare cc_id int;

-- set cc_id = 1
-- set fecha_loop = '2015-12-03';
-- set fecha_hasta = '2016-01-07';

while p_fecha_loop <= p_fecha_hasta do

	call proc_update_saldos(p_fecha_loop,p_cc_id);
	set p_fecha_loop = adddate(p_fecha_loop,+1);

end while;
end