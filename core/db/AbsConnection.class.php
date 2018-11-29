<?php
/**
 * Abstract class - Connection
 * @author Adler Brediks Medrado
 * @email adler@neshertech.net
 * @copyright 2005 - Nesher Technologies - www.neshertech.net
 * @date 30/11/2005
 */
abstract class AbsConnection {

	abstract function connect();
	abstract function getConnection();
	abstract function setConnection($conn);
}
?>
