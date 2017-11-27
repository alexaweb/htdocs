CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_insert_transaccion`(in p_fecha date,in p_cc_id int,in p_moneda text,in p_monto decimal(20,6),in p_descripcion text, in calc_abonos boolean)
BEGIN
    declare v_uf_hoy decimal(20,6);
    declare v_monto_uf decimal(20,6);
  
    select valor into v_uf_hoy from indicadores where codigo='uf' and fecha = p_fecha;
    set v_monto_uf = (p_monto / v_uf_hoy);
    
-- 0 transaccion base
-- 1 abono de capital
-- 2 abono de interes
    insert into transacciones(tr_fecha,tr_cc_id,tr_tipo_transaccion,tr_moneda,tr_monto,tr_monto_uf,tr_descripcion)
    values(p_fecha,p_cc_id,0,p_moneda,p_monto,v_monto_uf,p_descripcion);
    
if (calc_abonos) then
	call proc_calc_abonos(p_fecha,p_cc_id,p_moneda,p_monto,v_monto_uf,p_descripcion);
end if;
end