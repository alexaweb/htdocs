drop procedure if exists proc_insert_transaccion2;

DELIMITER //
CREATE PROCEDURE `proc_insert_transaccion2` ()
LANGUAGE SQL
BEGIN
SELECT * FROM CC_SALDOS;
    DECLARE v_capital_abonos_uf decimal(12,2);
    declare v_interes_devengado_uf decimal(12,2);
    declare v_interes_abonos_uf decimal(12,2);
	SELECT 1;

    declare bbb int;
END