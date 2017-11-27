CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_calc_abonos`(in p_fecha date,in p_cc_id int)
    COMMENT 'call en la madrugada del día siguiente, indicar la fecha de procesamiento'
begin
	DECLARE v_interes_hoy decimal(20,6);
    DECLARE v_interes_si decimal(20,6);
    -- declare v_uf_hoy decimal(20,6);
    declare v_monto_total_uf decimal(20,6);
	declare v_interes_pago decimal(20,6);
    declare v_capital_pago decimal(20,6);
    
    declare @tr_fecha date;
    declare @tr_moneda text;
    declare @tr_monto decimal(20,6);
    declare @tr_monto_uf decimal(20,6);
    declare @tr_descripcion text;
    
    declare cur_transacciones cursor for select tr_fecha,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = p_cc_id and tr_fecha = p_fecha;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET exit_loop = TRUE;
    
    set v_monto_total_interes_uf = 0;
    
    SELECT ifnull(ccs_interes_si_uf,0) into v_interes_si from cc_saldos where cc_id = p_cc_id and ccs_fecha = p_fecha;
    open cur_transacciones;
    
    transacciones_loop: LOOP
		fetch cur_transacciones into @tr_fecha, @tr_moneda, @tr_monto, @tr_monto_uf, @tr_descripcion;
        if exit_loop then
			close cur_transacciones;
            leave transacciones_loop;
		end if;
        
        set v_fecha = @tr_fecha;
        set v_moneda = @tr_moneda;
        set v_monto = @tr_monto;
        set v_monto_uf = @tr_monto_uf;
        set v_descripcion = @tr_descripcion;
        
	if v_interes_si >0 then
	begin	
        if v_monto_total_interes_uf <=0 then begin -- hubo una transaccion de pago de interes en el dia
			if abs(v_monto_total_interes_uf+v_monto_uf) < abs(v_interes_si) then begin
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',p_monto, v_monto_uf,p_descripcion);
			end;
            else begin 
                set v_interes_pago = -(v_interes_si + v_interes_hoy);
				set v_monto_total_interes_uf = v_monto_total_interes_uf + v_interes_pago;
                set v_capital_pago = v_monto_uf - v_interes_pago;
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,1,'clp',v_capital_pago * v_uf_hoy,v_capital_pago,p_descripcion);
                insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',v_interes_pago * v_uf_hoy,v_interes_pago,p_descripcion);
			end; end if;
		end; end if;
     end;   
	elseif v_interes_si<0 then -- v_interes_si <0
	 begin
		if v_interes_hoy >=0 then begin
			if abs(v_interes_hoy+p_monto_uf) < abs(v_interes_si) then begin
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',p_monto, v_monto_uf,p_descripcion);
			end;
            else begin-- hubo una transaccion de pago de interes en el dia
				set v_interes_pago = -(v_interes_si + v_interes_hoy);
				set v_monto_total_interes_uf = v_monto_total_interes_uf + v_interes_pago;
                set v_capital_pago = p_monto_uf - v_interes_pago;
				insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,1,'clp',v_capital_pago * v_uf_hoy,v_capital_pago,p_descripcion);
                insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,2,'clp',v_interes_pago * v_uf_hoy,v_interes_pago,p_descripcion);
			end; end if;
		end; end if;
	 end;
	else
	 begin
		insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion) values(p_fecha,p_cc_id,1,'clp',p_monto, v_monto_uf,p_descripcion);
     end; 
    end if;
        
        
        
        
 
        -- operación
	end LOOP transacciones_loop;
    
	SELECT ifnull(sum(tr_monto_uf),0) into v_interes_hoy from transacciones where tr_fecha = p_fecha and tr_cc_id = p_cc_id and tr_tipo_transaccion = 2;
    


   
end