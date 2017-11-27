drop procedure if exists pinsert_transaction;

DELIMITER //
 
CREATE PROCEDURE `pinsert_transaction` (in p_fecha date(), in p_cc_id int,in p_moneda, in p_tr_monto_uf decimal(12,2),p_descripcion text())
LANGUAGE SQL
SQL SECURITY DEFINER
COMMENT 'A procedure'
BEGIN
    SELECT 'Hello World !';


-- se debe colocar un loop de fecha
-- set  @p_fecha_ayer = '2015-12-03';
set @p_fecha = '2015-12-04';
set @p_fecha_hoy = curdate();
set @p_fecha_dia_anterior = adddate(@p_fecha,-1);
-- set DECLARE p:fecha_ayer date() DEFAULT adddate(curdate(),-1);

-- se debe colocar un loop de cuentas
set @p_cc_id = 1;


select sum(tr_monto_uf) from transacciones where tr_fecha=@p_fecha_dia_anterior and tr_cc_id=@p_cc_id;
select cc_spread from cc where cc_id = @p_cc_id;

END//

call proc_insert_transaction('2015-12-04',1,'clp',1000.2,'descripcion');