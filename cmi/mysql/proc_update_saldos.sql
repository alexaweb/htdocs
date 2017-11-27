CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_update_saldos`(in p_fecha date, in p_cc_id int)
    COMMENT 'call en la madrugada del día siguiente, indicar la fecha de procesamiento'
BEGIN
	DECLARE v_cantidad_cuentas INT;
    DECLARE i INT;
    
    DECLARE v_capital_abonos_uf decimal(20,6);
    declare v_interes_devengado_uf decimal(12,2);
    declare v_interes_abonos_uf decimal(12,2);
    
	declare v_spread decimal(12,6);
    declare v_tabuf360 decimal(12,6);
    declare v_tasa_interes decimal(12,6);
    
    declare v_capital_si_uf decimal(12,2);
	declare v_interes_si_uf decimal(12,2);
	declare v_capital_sf_uf decimal(12,2);
	declare v_interes_sf_uf decimal(12,2);
    
    declare v_fecha_hoy date;
    declare v_fecha_dia_anterior date;
    declare v_fecha_manana date;
    
    set v_fecha_hoy = curdate();
	set v_fecha_dia_anterior = adddate(p_fecha,-1);
    set v_fecha_manana = adddate(p_fecha,+1);
    
    SELECT sum(tr_monto_uf) into v_capital_abonos_uf from transacciones 
    where tr_fecha = p_fecha and tr_cc_id = p_cc_id and tr_tipo_transaccion = 1;
    if v_capital_abonos_uf is null then 
		set v_capital_abonos_uf = 0;
	end if;
    
    SELECT sum(tr_monto_uf) into v_interes_abonos_uf from transacciones 
    where tr_fecha = p_fecha and tr_cc_id = p_cc_id and tr_tipo_transaccion = 2;
    if v_interes_abonos_uf is null then 
		set v_interes_abonos_uf = 0;
	end if;
    
    select cc_spread into v_spread from cc where cc_id = p_cc_id; 
    select valor into v_tabuf360 from indicadores where codigo = 'tabuf360' and fecha = p_fecha;
    set v_tasa_interes = (v_spread + v_tabuf360) / 360;
    
    -- select p_fecha,p_cc_id;
    select ccs_capital_si_uf,ccs_interes_si_uf into v_capital_si_uf,v_interes_si_uf from cc_saldos where cc_id = p_cc_id and ccs_fecha = p_fecha;
    -- select ccs_interes_si_uf into v_interes_si_uf from cc_saldos where cc_id = p_cc_id and ccs_fecha = p_fecha;
    
    set v_capital_sf_uf = v_capital_si_uf+v_capital_abonos_uf;
    set v_interes_devengado_uf = v_capital_sf_uf * v_tasa_interes; -- REVISAR QUE 
    set v_interes_sf_uf = v_interes_si_uf+v_interes_devengado_uf+v_interes_abonos_uf;
    
    delete from cc_saldos where cc_id = p_cc_id and ccs_fecha = p_fecha;
    
    
    
    insert into cc_saldos(cc_id,ccs_fecha,ccs_tasa_interes_diaria,ccs_capital_si_uf,ccs_capital_abonos_uf,ccs_capital_sf_uf,ccs_interes_si_uf,ccs_interes_devengado_uf,ccs_interes_abonos_uf,ccs_interes_sf_uf)
    values(p_cc_id,p_fecha,v_tasa_interes,v_capital_si_uf,v_capital_abonos_uf,v_capital_sf_uf,v_interes_si_uf,v_interes_devengado_uf,v_interes_abonos_uf,v_interes_sf_uf);
    
    insert into cc_saldos(cc_id,ccs_fecha,ccs_capital_si_uf, ccs_interes_si_uf) values(p_cc_id,v_fecha_manana,v_capital_sf_uf,v_interes_sf_uf);
    
    


END