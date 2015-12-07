DELIMITER //

/*
    IF YOUR TABLES HAVE PREFIX
    YOU NEED TO ADJUST THE SCRIPT
*/

SET NAMES UTF8 COLLATE utf8_general_ci//

DROP EVENT IF EXISTS evt_check_support_inactivity//

CREATE EVENT evt_check_support_inactivity
ON SCHEDULE EVERY 1 HOUR
DO BEGIN

    SELECT
        CAST(TRIM(value) AS UNSIGNED)
    INTO
        @timeLimit
    FROM
        param
    WHERE
        name = 'SET_OFFLINE_IN';

    UPDATE
        support_user
    SET
        typing = 0,
        online = 0
    WHERE TRUE
        AND online = 1
        AND TIMESTAMPDIFF(MINUTE, last_activity, NOW()) > @timeLimit;

END//

DELIMITER ;