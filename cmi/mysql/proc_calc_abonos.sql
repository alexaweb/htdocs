CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_calc_abonos`(in p_fecha date,in p_cc_id int)
    COMMENT 'call en la madrugada del día siguiente, indicar la fecha de procesamiento'
begin
	DECLARE v_interes_hoy decimal(20,6);
    DECLARE v_interes_si decimal(20,6);
    -- declare v_uf_hoy decimal(20,6);
    declare v_monto_total_uf decimal(20,6);
	declare v_interes_pago decimal(20,6);
    declare v_capital_pago decimal(20,6);
    declare c_fecha date;
    declare c_moneda int;
    declare c_monto decimal(20,6);
    declare c_monto_uf decimal(20,6);
    declare c_descripcion text;
    
   --  declare cur_transacciones cursor for select tr_fecha,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = p_cc_id and tr_fecha = p_fecha;
    -- DECLARE CONTINUE HANDLER FOR NOT FOUND SET exit_loop = TRUE;
    
    set v_monto_total_uf = 0;
    delete from transacciones where  cc_id = p_cc_id and tr_fecha = p_fecha and (tr_tipo_transaccion = 1 or tr_tipo_transaccion = 2);
    
    SELECT ifnull(ccs_interes_si_uf,0) into v_interes_si from cc_saldos where cc_id = p_cc_id and ccs_fecha = p_fecha;
    select ifnull(sum(tr_monto_uf),0) into v_monto_total_uf from transacciones where  tr_cc_id = p_cc_id and tr_fecha = p_fecha;
    select v_interes_si;
    -- open cur_transacciones;
    /*
    transacciones_loop: LOOP
		fetch cur_transacciones into c_fecha, c_moneda, c_monto, c_monto_uf, c_descripcion;
        if exit_loop then
			close cur_transacciones;
            leave transacciones_loop;
		end if;
        
      */  
	if v_interes_si >0 then
	begin	
        if v_monto_total_uf <=0 then begin -- hubo una transaccion de pago de interes en el dia
			if abs(v_monto_total_uf) < abs(v_interes_si) then begin
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',123, v_monto_total_uf,c_descripcion);
			end;
            else begin 
                set v_interes_pago = -(v_interes_si);
				-- set v_monto_total_interes_uf = v_monto_total_interes_uf + v_interes_pago;
                set v_capital_pago = v_monto_total_uf - v_interes_pago;
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,1,'clp',124,v_capital_pago,p_descripcion);
                insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',125,v_interes_pago,p_descripcion);
			end; end if;
		end; end if;
     end;   
	elseif v_interes_si<0 then -- v_interes_si <0
	 begin
		if v_monto_total_uf >=0 then begin
			if abs(v_monto_total_uf) < abs(v_interes_si) then begin
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',223, v_monto_total_uf,p_descripcion);
			end;
            else begin-- hubo una transaccion de pago de interes en el dia
				set v_interes_pago = -(v_interes_si);
                -- set v_monto_total_interes_uf = v_monto_total_interes_uf + v_interes_pago;
                set v_capital_pago = v_monto_total_uf - v_interes_pago;
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,1,'clp',224,v_capital_pago,p_descripcion);
                insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',225,v_interes_pago,p_descripcion);
			end; end if;
		end; end if;
	 end;
	else
	 begin
		insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,1,'clp',331, v_monto_total_uf,p_descripcion);
     end; 
    end if;
        
        


   
end