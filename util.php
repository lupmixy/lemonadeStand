<?
session_start();
$config = parse_ini_file("config.ini.php");

$ADMIN_NAME = $config['ADMIN_NAME'] ;
$ADMIN_PASSWORD = $config['ADMIN_PASSWORD'] ;
//$ROUND_COUNT = $config['ROUND_COUNT'] ;
$ROUND_COUNT = $_SESSION["numRounds"]+1;
$ROUND_TIME = $config['ROUND_TIME'] ;

defined('APP_ENV') || define('APP_ENV', getenv('APP_ENV') ? getenv('APP_ENV') : 'dev');

// these can be used throughout the application:
define('APP_ENV_DEV', 'dev');
define('APP_ENV_INT', 'int');
define('APP_ENV_QA', 'qa');
define('APP_ENV_PROD', 'prod');

define('APP_IS_PROD', APP_ENV === APP_ENV_PROD);
define('APP_IS_NON_PROD', !APP_IS_PROD);

// project_config_get_db() is in PHP's auto_prepend file
//$dbConfig = project_config_get_db('lemonade-stand', APP_ENV);

//define('MYSQLUSER', $dbConfig['user']);
//define('MYSQLPASS', $dbConfig['password']);
//define('MYSQLSERVER', $dbConfig['host']);
//define('MYSQLDATABASENAME', $dbConfig['name']);
//let's define MYSQL stuff here, since auto_prepend is unavailable
define('MYSQLUSER', 'adminmixy');
define('MYSQLPASS', 'manicBuzz2k15!');
define('MYSQLSERVER', 'mysql.mcolombo.com');
define('MYSQLDATABASENAME', 'mcklemonsnew');

/**
 * Sends Cache-Control, Expires and Pragma headers accordingly.
 * 
 * @param mixed $offsetInDays offset in days, so specify 2/24 for 2 hours,
 *                            if offset is false, "no cache" headers are sent
 */
function sendCacheHeaders($offsetInDays) {
    if (false === $offsetInDays) {
        header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        return;
    }
    $offset = intval(60 * 60 * 24 * $offsetInDays);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT');
    header('Cache-Control: max-age=' . $offset. ', public');
    // clear pragma header:
    header('Pragma: ');
}


function stringToIntArray( $s )
{
	$r = explode( ",", $s );
	foreach( $r as $i => $v )
	{
		$r[$i] = (int)$v;
	}
	return $r;
} 

function intArrayToString( $boolArray )
{
	$r = implode( ",", $boolArray );
	return $r;
}

function stringToBoolArray( $s )
{
	$r = explode( ",", $s );
	foreach( $r as $i => $v )
	{
		$r[$i] = ($v=="true"?true:false);
	}
	return $r;
} 

function boolArrayToString( $boolArray )
{
	$r = "";
	foreach( $boolArray as $i => $v )
	{
		if( strlen( $r )>0 )
			$r.=",";
		$r.= $v?"true":"false";
	}
	return $r;
}

function rnd0to1()
{
	return mt_rand(0,1000000)/1000000;
}

function auth()
{
	$username = getHtmlParameter( "name" );
	$password = getHtmlParameter( "password" );
	$gameID = getHtmlParameter( "gameID" );
	try
	{
		$team = authWithParameters( $username, $password, $gameID );
		return $team;
	}
	catch( Exception $e )
	{
		error( 400, "Unknown username/password" );	
	}
}

function authAdmin()
{
	global $ADMIN_NAME;
	global $ADMIN_PASSWORD;
	$username = getHtmlParameter( "name" );
	$password = getHtmlParameter( "password" );
	if( $username==$ADMIN_NAME && $password==$ADMIN_PASSWORD ) {
		return true;
	} else {
		error( 400, "Unknown username/password" );
	}
}

function authWithParameters( $username, $password, $gameID )
{
	global $ADMIN_NAME;
	global $ADMIN_PASSWORD;
	$isAdmin =  ($username==$ADMIN_NAME && $password==$ADMIN_PASSWORD);
	if( $isAdmin ) {
		return null;
	} else {
		$team = getTeam( $username, $gameID );
		if( $team==null || $team->password!=$password ) {
			$_SESSION["returnMSG"] = "Unknown username/password";
			throw new Exception( "Unknown username/password" );
		} else {
			return $team;
		}
	}
}

function init()
{
	sendCacheHeaders(false);
	initDatabase();
}

function initDatabase()
{
	global $link;

	if (!$link) {
		$link = mysqli_connect( MYSQLSERVER, MYSQLUSER, MYSQLPASS, MYSQLDATABASENAME ); // must have location of mysqlserver database
		if (!$link) {
	    	die('Could not connect: ' . mysqli_error($link));
		}
	}
	date_default_timezone_set( 'America/Los_Angeles' );
}

function done(){
	global $link;
	mysqli_close($link);
}

function error( $status, $text )
{
	printResult( $status, $text, null );
	die( );
}

function printResult( $status, $text, $object )
{
    header('Content-Type: application/json');

	if( $object==null ) {
		echo "{ \"status\":$status, \"text\":\"$text\" }" ;
	} else {
		echo "{\"status\":$status, \"text\":\"$text\", \"content\":".json_encode($object)."}" ;
	}
}




function getHtmlParameter( $paramName )
{
	if( isset($_GET[$paramName] ) ) {
		return htmlentities( $_GET[ $paramName ] );
	} else {
		return null;
	}
}

/**
 * @param $query
 * @return bool|mysqli_result
 */
function db_query($query)
{
    /**
     * @var $link mysqli
     */
    global $link;
    $result = $link->query($query);
    if ($result === false) {
        error(500, db_error());
    }

    return $result;
}

/**
 * @param mysqli_result $result
 * @param $offset
 * @param $field
 * @return mixed
 */
function db_result(mysqli_result $result, $offset, $field)
{
    $result->data_seek($offset);
    $row = $result->fetch_array();
    return $row[$field];
}

function db_error()
{
    global $link;
    return mysqli_error($link);
}
