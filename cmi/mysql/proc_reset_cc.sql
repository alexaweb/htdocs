CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_reset_cc`(in p_cc_id int,in fecha_termino date)
    COMMENT 'call en la madrugada del día siguiente, indicar la fecha de procesamiento'
BEGIN
	declare fecha_loop date;
    declare v_num_transacciones int;
    
    -- set fecha_termino = curdate();
    
    -- se obtiene fecha de inicio para una cuenta particular
    select cc_fecha_inicio into fecha_loop from cc where cc_id = p_cc_id;
    -- se borran los salos posteriores al saldo de apertura
    delete from cc_saldos where cc_id = p_cc_id and ccs_fecha > fecha_loop;
    -- se borran todos los abonos de capital e interés para recalcular
    delete from transacciones where tr_cc_id = p_cc_id and (tr_tipo_transaccion = 1 or tr_tipo_transaccion = 2);
    
    
  	while(fecha_loop < fecha_termino) do -- se usa '<' porque corre al día siguiente en la madrugada
		set v_num_transacciones = 0;
	    select ifnull(count(*),0) into v_num_transacciones from transacciones where tr_cc_id = p_cc_id and tr_tipo_transaccion = 0 and tr_fecha = fecha_loop;
        if (v_num_transacciones>0) then 
			select 'antes calc_abonos';
			call proc_calc_abonos(fecha_loop,p_cc_id);
        end if;
		call proc_update_saldos(fecha_loop, p_cc_id);
    	set fecha_loop = adddate(fecha_loop,+1);
    end while;
end