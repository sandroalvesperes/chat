DELIMITER //

/*
    IF YOUR TABLES HAVE PREFIX
    YOU NEED TO ADJUST THE SCRIPT
*/

SET NAMES UTF8 COLLATE utf8_general_ci//

DROP TRIGGER IF EXISTS trg_chat_au//

CREATE TRIGGER trg_chat_au AFTER UPDATE
ON chat
FOR EACH ROW
BEGIN

	IF (OLD.id_support_user IS NULL) AND (NEW.id_support_user IS NOT NULL) AND (NEW.closed IS NULL) THEN

		SELECT
			SUBSTRING_INDEX(TRIM(name), ' ', 1)
		INTO
            @name
		FROM
			client_user
		WHERE
			id_client_user = OLD.id_client_user;

		INSERT INTO chat_message (id_chat, sent_by, sent_by_id, message)
		VALUES (OLD.id_chat, 'Support', NEW.id_support_user, CONCAT('Hi ', @name, ', how can I help you?'));

	END IF;

END//

DELIMITER ;