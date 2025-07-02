<?php
/**
 * index.php
 *
 * This is the main entry point for the application. It has been updated
 * to ensure proper initialization and checking of $browser and $koks_ip,
 * and to establish a robust mysqli connection as $db.
 */

// ERROR REPORTING (for development, remove or restrict in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- START OF CRITICAL CHANGES FOR SESSION AND HEADERS ---
// Ensure session_start() runs before any output.
// mukaka.php is assumed to contain session_start() and crucially,
// it should also establish the MySQLi database connection and assign it to the $db variable.
include_once("mukaka.php");

// Changed to HTML content type and character set
header("Content-type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");

// Changed to HTML5 DOCTYPE
echo "<!DOCTYPE html>";
// --- END OF CRITICAL CHANGES FOR SESSION AND HEADERS ---
?>
<html>
<head>
    <title><?php echo htmlspecialchars($title_total ?? 'My Application'); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f0f0; color: #333; margin: 0; padding: 0; display: flex; justify-content: center; min-height: 100vh; }
        .container {
            width: 100%;
            max-width: 600px; /* Max width for better readability on large screens */
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin: 20px;
            box-sizing: border-box; /* Include padding in width */
        }
        small { font-size: 0.9em; line-height: 1.4; }
        b { font-weight: bold; }
        u { text-decoration: underline; }
        a { color: #007bff; text-decoration: none; border-radius: 4px; padding: 2px 4px; transition: background-color 0.2s ease; }
        a:hover { text-decoration: underline; background-color: #e0f0ff; }
        .line { border-top: 1px solid #eee; margin: 15px 0; }
        .center { text-align: center; }
        .left { text-align: left; }
        .button-link {
            display: inline-block;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }
        .button-link:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }
        .button-link:active {
            transform: translateY(0);
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-block {
            background-color: #e9f7ef;
            border: 1px solid #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: left;
        }
        .menu-section {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .menu-section h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #555;
            text-align: left;
        }
        .menu-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu-section li {
            margin-bottom: 8px;
        }
        .menu-section li a {
            display: block;
            padding: 5px 0;
            color: #333;
        }
        .menu-section li a:hover {
            color: #007bff;
        }
        .shoutbox-entry {
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 8px;
            margin-bottom: 8px;
            text-align: left;
        }
        .shoutbox-entry .shouter-name {
            font-weight: bold;
            color: #007bff;
        }
        .shoutbox-entry .shout-text {
            margin-left: 5px;
        }
        .shoutbox-entry .shout-time {
            font-size: 0.8em;
            color: #777;
            margin-left: 5px;
        }
        .admin-panel-link {
            color: #dc3545; /* Red for admin links */
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
<?php
// --- Database Connection Check ---
// We are now relying on mukaka.php to establish the $db connection.
// This block will verify that it was successful.
if (!isset($db) || !($db instanceof mysqli)) {
    die("<div class='error-message'>
            <p><strong>Critical Error: Database connection not established by mukaka.php.</strong></p>
            <p>Please ensure <code>mukaka.php</code> correctly initializes a MySQLi connection and assigns it to the <code>\$db</code> variable.</p>
            <p>If <code>mukaka.php</code> contains database credentials, verify they are correct (hostname, username, password, database name).</p>
            <p>Also, ensure your database server is running and accessible.</p>
            " . (isset($db) && $db->connect_error ? "<p>Technical details: " . htmlspecialchars($db->connect_error) . "</p>" : "") . "
         </div>");
}

// --- Initialize and Check $browser and $koks_ip ---
// Removed include_once("ip.php") as we're handling these explicitly here
// to ensure they are always defined and prevent "Undefined variable" warnings.

// Initialize $browser (User Agent)
$browser = '';
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $browser = htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
} else {
    $browser = 'Unknown Browser';
    error_log("Warning: HTTP_USER_AGENT not set in _SERVER.");
}

// Initialize $koks_ip (Client IP Address)
$koks_ip = '';
if (isset($_SERVER['REMOTE_ADDR'])) {
    $koks_ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'UTF-8');
} else {
    $koks_ip = 'Unknown IP';
    error_log("Warning: REMOTE_ADDR not set in _SERVER.");
}

// Include core.php for global variables like $title, $line, $home, $back, $next, $homet
// Ensure core.php doesn't re-define $browser or $koks_ip, or try to establish a conflicting DB connection.
include_once("core.php");

// Fix for "Undefined array key 'action'": Check if $_GET['action'] is set
$action = ""; // Initialize $action
if (isset($_GET['action'])) {
    $action = addslashes(htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8'));
}

if ($action !== "arena") {
    echo "<div class='card-content'><p class='center'>";
}

// The original code had a condition based on $HTTP_USER_AGENT.
// If this block was meant to do something specific for WML browsers,
// it might need re-evaluation for HTML output. For now, it remains.
// Note: $user_agent is now $browser.
if ($browser == "") {
    // This block might be for specific user agents, keep as is for now if logic depends on it.
}

// Fix for "Undefined array key 'id'": Check if $_GET['id'] is set
$id = ""; // Initialize $id
if (isset($_GET['id'])) {
    $id = addslashes(htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8'));
}

// Fix: Replace mysql_fetch_array and mysql_query with mysqli_fetch_array and mysqli_query
// Pass the $db connection object to mysqli_query
$topic_result = mysqli_query($db, "SELECT max,topic FROM spec LIMIT 1");

// Fix for "Undefined array key 'id' on line 70" (and similar warnings):
// Check if any rows were returned before trying to fetch data.
if ($topic_result && mysqli_num_rows($topic_result) > 0) { // Added check for $topic_result to be non-false
    $topic = mysqli_fetch_array($topic_result, MYSQLI_ASSOC); // Use MYSQLI_ASSOC for associative array
} else {
    // If no rows, initialize $topic to prevent warnings and insert a default row
    $topic = ['max' => '0', 'topic' => ''];
    // Use mysqli_query with $db connection object
    $insert_spec_query = "INSERT INTO spec (max,topic) VALUES ('0','')";
    if (!mysqli_query($db, $insert_spec_query)) {
        error_log("MySQLi Error: Failed to insert into spec table: " . mysqli_error($db) . " Query: " . $insert_spec_query);
    }
}

// --- IMPORTANT: This file MUST be migrated to MySQLi as well. ---
// It is crucial for user data and authentication.
include_once("check.php");

// If check.php is not yet migrated, or for testing, you might use mock data.
// In a real scenario, you MUST migrate check.php to mysqli.
/*
if (!isset($user)) { // Only mock if $user is not set by check.php
    $user = [
        'username' => 'Guest',
        'blokas' => '0',
        'ship' => 0,
        'new_pm' => 0,
        'trade' => 0,
        'expierence' => 0,
        'gold' => 0,
        'level' => 1,
        'skill_points' => 0,
        'kvietimas' => 0,
        'member' => 0,
        'kred' => 0,
        'rain' => 0,
        'sfrenzy' => 0,
        'sfrenzy2' => 0,
        'immortal' => 0,
        'fre' => 0,
        'battle' => 0,
        'class' => 'warrior', // Default class for display
        'status' => 'Player', // Default status
        'id' => 0, // Default ID
        'max_hp' => 100, 'hp' => 100,
        'magic' => 10, 'max_magic' => 10,
        'mana' => 10, 'max_mana' => 10,
        'attack' => 5, 'defense' => 5,
        'power' => 5, 'knowledge' => 5,
        'strength' => 0, 'intellect' => 0,
        'luck' => 0, 'morale' => 0, 'speed' => 0,
        'exp_next' => 1000,
        'rank' => 'Newbie', 'kills' => 0, 'wins' => 0, 'losses' => 0, 'draws' => 0,
        'gem' => 0, 'wood' => 0, 'ore' => 0, 'sulfur' => 0, 'mercury' => 0, 'crystal' => 0,
        'perv' => 0, // For gold transfer cooldown
        'identify' => 'none' // Added 'identify' key for strategy.php
    ];
    $user_skill = []; // Initialize user skills if check.php is mocked
    $user_skill_lvl = [];
}
*/


if ((isset($user['username']) && $user['username'] == "chaotic") || (isset($user['blokas']) && $user['blokas'] == "1")) {
    echo " ";
} else {
    if ($action !== "arena") {
        if (isset($user['ship']) && $user['ship'] > time()) {
            echo "<small>Your ship is under attack!</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=laiv&la=vand\">Strike back!</a></small><br/>";
        }
    }
}

// Replace the $line variable usage for horizontal rule
$line = "<hr class='line'>";

// Handle 'para' table (daily activity log)
$par_result = mysqli_query($db, "SELECT * FROM para WHERE nick='" . mysqli_real_escape_string($db, $user['username']) . "'");
if (!$par_result) {
    error_log("MySQLi Error: Failed to query para table: " . mysqli_error($db));
    $par = null; // Set to null if query fails
} else {
    $par = mysqli_fetch_array($par_result, MYSQLI_ASSOC);
}

$data = date("Y-m-d");
if (!$par) { // If no record for user, insert one
    $insert_para_query = "INSERT INTO para (nick,data) VALUES ('" . mysqli_real_escape_string($db, $user['username']) . "','" . mysqli_real_escape_string($db, $data) . "')";
    if (!mysqli_query($db, $insert_para_query)) {
        error_log("MySQLi Error: Failed to insert into para table: " . mysqli_error($db) . " Query: " . $insert_para_query);
    }
    $par_result = mysqli_query($db, "SELECT * FROM para WHERE nick='" . mysqli_real_escape_string($db, $user['username']) . "'");
    if ($par_result) {
        $par = mysqli_fetch_array($par_result, MYSQLI_ASSOC);
    }
}

if (isset($par['data']) && $par['data'] !== $data) {
    $delete_para_query = "DELETE FROM para"; // This deletes ALL records, might be intended for daily reset
    if (!mysqli_query($db, $delete_para_query)) {
        error_log("MySQLi Error: Failed to delete from para table: " . mysqli_error($db) . " Query: " . $delete_para_query);
    }
}

// Pending battle check
if (($action !== "arena") && ($action !== "map") && ($action !== "nbattle") && ($action !== "run") && ($action !== "laiv")) {
    $nn = strtolower($user['username']);
    $kauk_result = mysqli_query($db, "SELECT id, unit, vnd FROM nbattle WHERE heroe='" . mysqli_real_escape_string($db, $nn) . "' AND active='0' LIMIT 1");
    if (!$kauk_result) {
        error_log("MySQLi Error: Failed to query nbattle table: " . mysqli_error($db));
        $kauk = null;
    } else {
        $kauk = mysqli_fetch_array($kauk_result, MYSQLI_ASSOC);
    }

    if ($kauk && isset($kauk['id'])) {
        $wow = "";
        if (isset($kauk['vnd']) && $kauk['vnd'] == "0") {
            $wow = "action=nbattle&id=" . htmlspecialchars($id) . "&i=" . (isset($_GET['i']) ? htmlspecialchars($_GET['i']) : '') . "&j=" . (isset($_GET['j']) ? htmlspecialchars($_GET['j']) : '') . "&k=" . (isset($_GET['k']) ? htmlspecialchars($_GET['k']) : '') . "";
        } else {
            $wow = "action=laiv&la=kov&id=" . htmlspecialchars($id);
        }
        include_once("names/units.php"); // Assumed to define $unit_name_s1
        $name_unit = isset($unit_name_s1[$kauk['unit']]) ? $unit_name_s1[$kauk['unit']] : $kauk['unit'];
        echo "<small>You still have pending battle with <b> " . htmlspecialchars($name_unit) . " </b></small><br/>";
        echo "<small><b><a href=\"index.php?" . htmlspecialchars($wow) . "&event=" . htmlspecialchars($kauk['id']) . "\" class=\"button-link\">[&#187;] To battlefield</a></b></small><br/>";
        echo "<small><b><a href=\"index.php?action=run&id=" . htmlspecialchars($id) . "&mekeke=" . htmlspecialchars($kauk['id']) . "\" class=\"button-link\">[&#171;] Kabur</a></b></small><br/>";
        echo "</p></div>"; // Close current card and exit
        mysqli_close($db);
        exit;
    }
}

// --- START OF CHANGES FOR skils/strategy.php ---
// This include is here, so the fix for 'identify' will apply to the included file.
include_once("skils/strategy.php");
// --- END OF CHANGES FOR skils/strategy.php ---

// New messages count
$npm_result = mysqli_query($db, "SELECT COUNT(id) AS num FROM pm WHERE nick='" . mysqli_real_escape_string($db, $user['username']) . "' AND active='0'");
$newpmm = ($npm_result) ? mysqli_fetch_assoc($npm_result)['num'] : 0;

$apm_result = mysqli_query($db, "SELECT COUNT(id) AS num FROM pm WHERE nick='" . mysqli_real_escape_string($db, $user['username']) . "'");
$alpm = ($apm_result) ? mysqli_fetch_assoc($apm_result)['num'] : 0;

if (($newpmm == "1") || ($newpmm == "21")) {
    echo "<small><a href=\"pm.php?id=" . htmlspecialchars($id) . "&forum=" . (isset($forum) ? htmlspecialchars($forum) : '') . "&topic=" . (isset($topic['topic']) ? htmlspecialchars($topic['topic']) : '') . "\">You have " . htmlspecialchars($newpmm) . " messages!</a></small><br/>$line<br/>";
} elseif ((($newpmm > 1) && ($newpmm <= 9)) || (($newpmm > 21) && ($newpmm < 30))) {
    echo "<small><a href=\"pm.php?id=" . htmlspecialchars($id) . "&forum=" . (isset($forum) ? htmlspecialchars($forum) : '') . "&topic=" . (isset($topic['topic']) ? htmlspecialchars($topic['topic']) : '') . "\">You have " . htmlspecialchars($newpmm) . " messages!</a></small><br/>$line<br/>";
} elseif ((($newpmm > 9) && ($newpmm <= 20)) || ($newpmm == "30")) {
    echo "<small><a href=\"pm.php?id=" . htmlspecialchars($id) . "&forum=" . (isset($forum) ? htmlspecialchars($forum) : '') . "&topic=" . (isset($topic['topic']) ? htmlspecialchars($topic['topic']) : '') . "\">You have " . htmlspecialchars($newpmm) . " messages!</a></small><br/>$line<br/>";
}

// Trade offers
$trd = null;
if (isset($user['trade'])) {
    if ($user['trade'] == "1") {
        $trd_result = mysqli_query($db, "SELECT * FROM trade WHERE name='" . mysqli_real_escape_string($db, $user['username']) . "'");
        if ($trd_result) $trd = mysqli_fetch_array($trd_result, MYSQLI_ASSOC);
    } elseif ($user['trade'] == "2") {
        $trd_result = mysqli_query($db, "SELECT * FROM trade WHERE name2='" . mysqli_real_escape_string($db, $user['username']) . "'");
        if ($trd_result) $trd = mysqli_fetch_array($trd_result, MYSQLI_ASSOC);
    }
}

if (isset($trd['id']) && ($action !== "trade")) {
    if ((isset($trd['act']) && $trd['act'] == "0") && (strtolower($trd['name2']) == strtolower($user['username']))) {
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=nick_info&name=" . htmlspecialchars($trd['name']) . "\">" . htmlspecialchars($trd['name']) . "</a> offers trade!</small><br/>";
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=trade&da=sut&idzz=" . htmlspecialchars($trd['id']) . "\">Accept</a></small><br/>";
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=trade&da=atm&idzz=" . htmlspecialchars($trd['id']) . "\">Reject</a></small><br/>";
    } else {
        if (strtolower($user['username']) == strtolower($trd['name'])) {
            echo "<small>Wait for <a href=\"index.php?id=" . htmlspecialchars($id) . "&action=nick_info&name=" . htmlspecialchars($trd['name2']) . "\">" . htmlspecialchars($trd['name2']) . "'s responses!</a></small><br/>";
        }
        if (strtolower($user['username']) == strtolower($trd['name2'])) {
            echo "<small>Wait for <a href=\"index.php?id=" . htmlspecialchars($id) . "&action=nick_info&name=" . htmlspecialchars($trd['name']) . "\">" . htmlspecialchars($trd['name']) . "'s responses!</a></small><br/>";
        }
        if ((strtolower($user['username']) == strtolower($trd['name'])) && (isset($trd['act']) && $trd['act'] == "0")) {
            echo "<small>No answere yet</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=trade&da=ats&idzz=" . htmlspecialchars($trd['id']) . "\">Cancel trade!</a></small><br/>";}
        else {
            echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=trade&da=trade&idzz=" . htmlspecialchars($trd['id']) . "\">To traderoom!</a></small><br/>";}}}


// Data integrity checks and updates
// Ensure 'magic' table is cleaned up
$delete_magic_query = "DELETE FROM magic WHERE name=''";
if (!mysqli_query($db, $delete_magic_query)) {
    error_log("MySQLi Error: Failed to delete from magic table: " . mysqli_error($db) . " Query: " . $delete_magic_query);
}

// Update user stats if negative (basic sanitization)
if (isset($user['expierence']) && preg_match('/-/i', (string)$user['expierence'])) { // Cast to string for preg_match
    $update_exp_query = "UPDATE users SET expierence='0' WHERE username='" . mysqli_real_escape_string($db, $user['username']) . "' LIMIT 1";
    if (!mysqli_query($db, $update_exp_query)) {
        error_log("MySQLi Error: Failed to update expierence: " . mysqli_error($db) . " Query: " . $update_exp_query);
    }
}
if (isset($user['gold']) && preg_match('/-/i', (string)$user['gold'])) { // Cast to string for preg_match
    $update_gold_query = "UPDATE users SET gold='0' WHERE username='" . mysqli_real_escape_string($db, $user['username']) . "' LIMIT 1";
    if (!mysqli_query($db, $update_gold_query)) {
        error_log("MySQLi Error: Failed to update gold: " . mysqli_error($db) . " Query: " . $update_gold_query);
    }
}
if (isset($user['new_pm']) && preg_match('/-/i', (string)$user['new_pm'])) { // Cast to string for preg_match
    $update_newpm_query = "UPDATE users SET new_pm='0' WHERE username='" . mysqli_real_escape_string($db, $user['username']) . "' LIMIT 1";
    if (!mysqli_query($db, $update_newpm_query)) {
        error_log("MySQLi Error: Failed to update new_pm: " . mysqli_error($db) . " Query: " . $update_newpm_query);
    }
}

// Update user browser and IP (Lines 383 and 394 in the original report)
// These now correctly reference the $browser and $koks_ip variables initialized above.
if (preg_match("/'/i", $browser)) { // No need for isset($browser) as it's always initialized
    $update_onl_query = "UPDATE users SET onl='Unknown' WHERE session='" . mysqli_real_escape_string($db, $id) . "'";
    if (!mysqli_query($db, $update_onl_query)) {
        error_log("MySQLi Error: Failed to update onl (browser): " . mysqli_error($db) . " Query: " . $update_onl_query);
    }
} else {
    $update_onl_query = "UPDATE users SET onl='" . mysqli_real_escape_string($db, $browser) . "' WHERE session='" . mysqli_real_escape_string($db, $id) . "'";
    if (!mysqli_query($db, $update_onl_query)) {
        error_log("MySQLi Error: Failed to update onl (browser): " . mysqli_error($db) . " Query: " . $update_onl_query);
    }
}
if (preg_match("/'/i", $koks_ip)) { // No need for isset($koks_ip) as it's always initialized
    $update_ip_query = "UPDATE users SET ip='Unknown' WHERE session='" . mysqli_real_escape_string($db, $id) . "'";
    if (!mysqli_query($db, $update_ip_query)) {
        error_log("MySQLi Error: Failed to update ip: " . mysqli_error($db) . " Query: " . $update_ip_query);
    }
} else {
    $update_ip_query = "UPDATE users SET ip='" . mysqli_real_escape_string($db, $koks_ip) . "' WHERE session='" . mysqli_real_escape_string($db, $id) . "'";
    if (!mysqli_query($db, $update_ip_query)) {
        error_log("MySQLi Error: Failed to update ip: " . mysqli_error($db) . " Query: " . $update_ip_query);
    }
}

$idm = $koks_ip . "|" . $browser; // No need for isset() here
if (isset($user['username']) && $user['username'] == "Nakked") {
    // This is a file system operation, not DB. Keep as is.
    @file_put_contents("nak.txt", $idm);
}

// Main content rendering based on $action
if (($action == "map") || ($action == "object") || ($action == "nbattle") || ($action == "event") || ($action == "online") || ($action == "nick_info")) {
    $i = isset($_GET['i']) ? addslashes(htmlspecialchars($_GET['i'], ENT_QUOTES, 'UTF-8')) : "";
    $j = isset($_GET['j']) ? addslashes(htmlspecialchars($_GET['j'], ENT_QUOTES, 'UTF-8')) : "";
    $k = isset($_GET['k']) ? addslashes(htmlspecialchars($_GET['k'], ENT_QUOTES, 'UTF-8')) : "";

    $place = "";
    if ($action !== "nbattle") {
        $place = "$i|$j|$k";
        if (isset($_GET['event']) && addslashes(htmlspecialchars($_GET['event'], ENT_QUOTES, 'UTF-8')) == "arena") $place = "arena";
        include_once("online.php"); // Needs mysqli migration
    } else {
        $place = isset($_GET['event']) ? addslashes(htmlspecialchars($_GET['event'], ENT_QUOTES, 'UTF-8')) : '';
        include_once("online.php"); // Needs mysqli migration
    }

    // Map existence checks
    if ((($k !== "") && (!file_exists("map/$i/$j/$k.php"))) || (($j !== "") && (!file_exists("map/$i/$j"))) || ((!file_exists("map/$i")))) {
        echo "<small><b>Not such territory</b></small><br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
        mysqli_close($db);
        exit;
    }

    $level_limit = 0; // Initialize level_limit
    $need = ""; // Initialize need for artifacts

    if ($i !== "") {
        $header = ""; // Original code sets $header to empty string, might be used in included files
        include("map/$i.php"); // This file might define $need and $level_limit
        if (!empty($need)) {
            $kei_result = mysqli_query($db, "SELECT * FROM artifacts where user='" . mysqli_real_escape_string($db, $user['username']) . "' and name='" . mysqli_real_escape_string($db, $need) . "'");
            if (!$kei_result) {
                error_log("MySQLi Error: Failed to query artifacts table: " . mysqli_error($db));
            }
            $kei = ($kei_result) ? mysqli_fetch_array($kei_result, MYSQLI_ASSOC) : null;

            if (!isset($kei['name'])) {
                include_once("names/artifacts.php"); // Assumed to define $artifact_name
                $artifact_display_name = isset($artifact_name[$need]) ? $artifact_name[$need] : $need;
                echo "<small>You need <b>" . htmlspecialchars($artifact_display_name) . "</b> if you want to access this area.</small><br/>$line</p><p class=\"left\"><small><b>&#171;</b><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$homet</a></small>";
                mysqli_close($db);
                exit;
            }
        }

        if (isset($user['level']) && $level_limit > $user['level']) {
            echo "<small>You must be at <b>" . htmlspecialchars($level_limit) . " level</b> if you want to access this area.</small><br/>$line</p><p class=\"left\"><small><b>&#171;</b><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$homet</a></small>";
            mysqli_close($db);
            exit;
        }
    }
    if ($j !== "") {
        $header = "";
        include_once("map/$i/$j.php"); // This file might define $need and $level_limit
        include_once("names/lands.php"); // Assumed to define $land_name
        $land = isset($land_name[$i]) ? $land_name[$i] : '';
        if (!empty($need)) {
            $kei_result = mysqli_query($db, "SELECT * FROM artifacts where user='" . mysqli_real_escape_string($db, $user['username']) . "' and name='" . mysqli_real_escape_string($db, $need) . "'");
            if (!$kei_result) {
                error_log("MySQLi Error: Failed to query artifacts table: " . mysqli_error($db));
            }
            $kei = ($kei_result) ? mysqli_fetch_array($kei_result, MYSQLI_ASSOC) : null;

            if (!isset($kei['name'])) {
                include_once("names/artifacts.php");
                $artifact_display_name = isset($artifact_name[$need]) ? $artifact_name[$need] : $need;
                echo "<small>You need <b>" . htmlspecialchars($artifact_display_name) . "</b> if you want to access this area.</small><br/>$line</p><p class=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=" . htmlspecialchars($i) . "\">" . htmlspecialchars($land) . "</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$homet</a></small>";
                mysqli_close($db);
                exit;
            }
        }

        if (isset($user['level']) && $level_limit > $user['level']) {
            echo "<small>You must be at <b>" . htmlspecialchars($level_limit) . " level</b> if you want to access this room.</small><br/>$line</p><p class=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=" . htmlspecialchars($i) . "\">" . htmlspecialchars($land) . "</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$homet</a></small>";
            mysqli_close($db);
            exit;
        }
    }
    if ($k !== "") {
        $header = "";
        include_once("names/territories.php"); // Assumed to define $territory_name
        $territory = isset($territory_name[$j]) ? $territory_name[$j] : '';
        include("map/$i/$j/$k.php"); // This file might define $need and $level_limit
        if (!empty($need)) {
            $kei_result = mysqli_query($db, "SELECT * FROM artifacts where user='" . mysqli_real_escape_string($db, $user['username']) . "' and name='" . mysqli_real_escape_string($db, $need) . "'");
            if (!$kei_result) {
                error_log("MySQLi Error: Failed to query artifacts table: " . mysqli_error($db));
            }
            $kei = ($kei_result) ? mysqli_fetch_array($kei_result, MYSQLI_ASSOC) : null;

            if (!isset($kei['name'])) {
                include_once("names/artifacts.php");
                $artifact_display_name = isset($artifact_name[$need]) ? $artifact_name[$need] : $need;
                echo "<small>you need <b>" . htmlspecialchars($artifact_display_name) . "</b> if you want to access this room.</small><br/>$line</p><p class=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=" . htmlspecialchars($i) . "&j=" . htmlspecialchars($j) . "\">" . htmlspecialchars($territory) . "</a></small><br/><small><b>&#171;</b><a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=" . htmlspecialchars($i) . "\">" . htmlspecialchars($land ?? 'Unknown Land') . "</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$homet</a></small>";
                mysqli_close($db);
                exit;
            }
        }

        if (isset($user['level']) && $level_limit > $user['level']) {
            echo "<small>You must be at <b>" . htmlspecialchars($level_limit) . " level</b> if you want to access this area.</small><br/>$line</p><p class=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=" . htmlspecialchars($i) . "&j=" . htmlspecialchars($j) . "\">" . htmlspecialchars($territory) . "</a></small><br/><small><b>&#171;</b><a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=" . htmlspecialchars($i) . "\">" . htmlspecialchars($land ?? 'Unknown Land') . "</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$homet</a></small>";
            mysqli_close($db);
            exit;
        }
    }
} elseif ($action == "object") {
    $place = isset($_GET['i']) ? htmlspecialchars($_GET['i']) : '';
    include_once("online.php"); // Needs mysqli migration
} elseif ($action == "arena") {
    include_once("include/arena.php"); // Needs mysqli migration
} elseif ($action == "abattle") {
    $p = isset($_GET['p']) ? addslashes(htmlspecialchars($_GET['p'], ENT_QUOTES, 'UTF-8')) : "";
    if ($p == "") {
        include_once("include/arena_battle.php"); // Needs mysqli migration
    } elseif ($p == "spells") {
        include_once("include/arena_spells.php"); // Needs mysqli migration
    } elseif ($p == "info") {
        include_once("include/arena_info.php"); // Needs mysqli migration
    }
} else {
    $place = "pagr"; // Default place for online.php if no specific action
    include_once("online.php"); // Needs mysqli migration
}

// Include specific action files
if ($action == "map") {
    include_once("include/map.php"); // Needs mysqli migration
}

if ($action == "kred") {
    echo "<small>No information yet</small><br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
}
if ($action == "object") {
    include_once("include/object.php"); // Needs mysqli migration
}
if ($action == "event") {
    include_once("include/event.php"); // Needs mysqli migration
}
if ($action == "nbattle") {
    $p = isset($_GET['p']) ? addslashes(htmlspecialchars($_GET['p'], ENT_QUOTES, 'UTF-8')) : "";
    if ($p == "") {
        include_once("include/neutral_battle.php"); // Needs mysqli migration
    } elseif ($p == "spells") {
        include_once("include/nbattle_spells.php"); // Needs mysqli migration
    } elseif ($p == "info") {
        include_once("include/nbattle_info.php"); // Needs mysqli migration
    }
}

include_once("include/newskill.php"); // Needs mysqli migration
include_once("include/aukcionas.php"); // Needs mysqli migration
include_once("include/ally.php"); // Needs mysqli migration

if ($action == "rekla") {
    include_once("rekla.php"); // Needs mysqli migration
}
if ($action == "frenzy") {
    include_once("include/frenzy.php"); // Needs mysqli migration
}
if ($action == "sfrenzy") {
    include_once("include/super_frenzy.php"); // Needs mysqli migration
}

if ($action == "catapulta") {
    $war_result = mysqli_query($db, "SELECT hp FROM war WHERE user='" . mysqli_real_escape_string($db, $user['username']) . "' AND machine='catapulta'");
    if (!$war_result) {
        error_log("MySQLi Error: Failed to query war table for catapult: " . mysqli_error($db));
        $war = null;
    } else {
        $war = mysqli_fetch_array($war_result, MYSQLI_ASSOC);
    }

    if (isset($war['hp'])) {
        echo "<small>You already have catapult!</small>";
    } elseif (($user['gold'] < 15000) || ($user['wood'] < 5)) {
        echo "<small>Not enough resources</small>";
    } else {
        $update_user_res_query = "UPDATE users SET wood=wood-5,gold=gold-15000 WHERE username='" . mysqli_real_escape_string($db, $user['username']) . "'";
        if (!mysqli_query($db, $update_user_res_query)) {
            error_log("MySQLi Error: Failed to update user resources for catapult: " . mysqli_error($db) . " Query: " . $update_user_res_query);
        } else {
            $insert_war_catapult_query = "INSERT INTO war (user,machine,hp) VALUES ('" . mysqli_real_escape_string($db, $user['username']) . "','catapulta','1000')";
            if (!mysqli_query($db, $insert_war_catapult_query)) {
                error_log("MySQLi Error: Failed to insert catapult into war table: " . mysqli_error($db) . " Query: " . $insert_war_catapult_query);
            } else {
                echo "<small>Catapult was successfully bought</small>";
            }
        }
    }
    echo "<br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
}

if ($action == "laiv") {
    include_once("include/laiv.php"); // Needs mysqli migration
}


if ($action == "akad") {
    include_once("include/akad.php"); // Needs mysqli migration
}
if ($action == "viktz") {
    if ((isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator") && (isset($user['status']) && $user['status'] !== "Captain")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    include_once("vikt.php"); // Needs mysqli migration
    // Assuming $kls and $ats are defined in vikt.php
    if (isset($kls) && is_array($kls) && isset($ats) && is_array($ats)) {
        for ($i_vikt = $nuo; $i_vikt < count($kls); $i_vikt++) {
            $insert_quiz_query = "INSERT INTO viktorinos_klausimai (klausimas,atsakymas) VALUES ('" . mysqli_real_escape_string($db, $kls[$i_vikt]) . "','" . mysqli_real_escape_string($db, $ats[$i_vikt]) . "')";
            if (!mysqli_query($db, $insert_quiz_query)) {
                error_log("MySQLi Error: Failed to insert quiz question: " . mysqli_error($db) . " Query: " . $insert_quiz_query);
            }
        }
    }
    
    // Read from kls.txt
    $nph = @file("kls.txt"); // @ suppresses warnings if file doesn't exist
    if ($nph !== false) {
        $nph = array_reverse($nph);
        $kiek_nph = count($nph);
        for ($oh = 0; $oh < $kiek_nph; $oh++) {
            $oph = explode("|", $nph[$oh]);
            if (isset($oph[0]) && isset($oph[1])) {
                $insert_quiz_nph_query = "INSERT INTO viktorinos_klausimai (klausimas,atsakymas) VALUES ('" . mysqli_real_escape_string($db, $oph[0]) . "','" . mysqli_real_escape_string($db, $oph[1]) . "')";
                if (!mysqli_query($db, $insert_quiz_nph_query)) {
                    error_log("MySQLi Error: Failed to insert quiz question from kls.txt: " . mysqli_error($db) . " Query: " . $insert_quiz_nph_query);
                }
            }
        }
    }
    echo "Date Added";
}
if ($action == "vikt") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    include_once("include/viktorina.class.php"); // Needs mysqli migration
    $cViktorina = new cViktorina(1);
    if (isset($cViktorina->start) && $cViktorina->start) {
        $cViktorina->stop();
        echo "Quiz stopped.<br/><small><a href=\"quiz-" . htmlspecialchars($id) . "\">Quiz</a></small><br/>$line<br/><small><a href=\"index.php?action=xcpanelx&id=" . htmlspecialchars($id) . "\">cPanel</a></small><br/>";
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
    } else {
        $cViktorina->start();
        echo "Quiz started.<br/><small><a href=\"quiz-" . htmlspecialchars($id) . "\">Quiz</a></small><br/>$line<br/><small><a href=\"index.php?action=xcpanelx&id=" . htmlspecialchars($id) . "\">cPanel</a></small><br/>";
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
    }
}
if ($action == "linija") {
    include_once("include/linija.php"); // Needs mysqli migration
}
if ($action == "linija2") {
    include_once("include/linija2.php"); // Needs mysqli migration
}
if ($action == "scholar") {
    include_once("include/scholar.php"); // Needs mysqli migration
}
if ($action == "shop1") {
    include_once("include/shop.php"); // Needs mysqli migration
}
if ($action == "shop2") {
    include_once("include/shop.php"); // Needs mysqli migration
}

if ($action == "castle") {
    include_once("include/castle.php"); // Needs mysqli migration
}
if ($action == "find") {
    include_once("include/find.php"); // Needs mysqli migration
}
if ($action == "game") {
    include_once("game.php"); // Needs mysqli migration
}
if ($action == "krdinf") {
    include_once("include/krdinf.php"); // Needs mysqli migration
}
if ($action == "delpm") {
    $pid = isset($_GET['pid']) ? htmlspecialchars($_GET['pid']) : '';
    $delete_pm_query = "DELETE FROM pm WHERE id='" . mysqli_real_escape_string($db, $pid) . "' LIMIT 1";
    if (!mysqli_query($db, $delete_pm_query)) {
        error_log("MySQLi Error: Failed to delete PM: " . mysqli_error($db) . " Query: " . $delete_pm_query);
    }
    echo "<small>OK</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
}


if ($action == "run") {
    include_once("include/run.php"); // Needs mysqli migration
}
if ($action == "barak") {
    include_once("include/barak.php"); // Needs mysqli migration
}
if ($action == "member") {
    include_once("member.php"); // Needs mysqli migration
}
if ($action == "infor") {
    include_once("include/info.php"); // Needs mysqli migration
}
if ($action == "rpmd") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    include_once("rpmd.php"); // Needs mysqli migration
}
if ($action == "aukats") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    include_once("aukats.php"); // Needs mysqli migration
}
if ($action == "barak5") {
    include_once("include/barak.php"); // Needs mysqli migration
}
if ($action == "barak2") {
    include_once("include/barak.php"); // Needs mysqli migration
}
if ($action == "barak3") {
    include_once("include/barak.php"); // Needs mysqli migration
}
if ($action == "barak4") {
    include_once("include/barak.php"); // Needs mysqli migration
}
if ($action == "krd") {
    include_once("include/krd.php"); // Needs mysqli migration
}
if ($action == "reglog") {
    include_once("reglog.php"); // Needs mysqli migration
}
if ($action == "findip") {
    include_once("include/findip.php"); // Needs mysqli migration
}

if ($action == "next") {
    include_once("include/next.php"); // Needs mysqli migration
}

// Default action (homepage)
if ($action == "") {
    $place = "pagr";
    include_once("online.php"); // Needs mysqli migration
    $usr = strtolower($user['username']);
    $nauj_result = mysqli_query($db, "SELECT date FROM news ORDER BY date DESC LIMIT 1");
    if (!$nauj_result) {
        error_log("MySQLi Error: Failed to query news table: " . mysqli_error($db));
        $nauj = null;
    } else {
        $nauj = mysqli_fetch_array($nauj_result, MYSQLI_ASSOC);
    }

    $update_laivynas_query = "UPDATE laivynas SET ejimas='999999999' WHERE user='Arshc'";
    if (!mysqli_query($db, $update_laivynas_query)) {
        error_log("MySQLi Error: Failed to update laivynas for Arshc: " . mysqli_error($db) . " Query: " . $update_laivynas_query);
    }

    $day_result = mysqli_query($db, "SELECT day, time FROM time LIMIT 1");
    if (!$day_result) {
        error_log("MySQLi Error: Failed to query time table: " . mysqli_error($db));
        $day = null;
    } else {
        $day = mysqli_fetch_array($day_result, MYSQLI_ASSOC);
    }
    
    $queries = 0; // Initialize queries counter if not defined elsewhere

    // Time update logic
    $time = time();
    $day_length = 86400; // Define $day_length if not coming from core.php or other include
    if (isset($day['time']) && $day['time'] < $time) {
        $dd = ($time - $day['time']) / $day_length;
        $days_passed = ceil($dd);
        $day['time'] = $day_length - ($day_length + ceil(($dd - $days_passed) * $day_length)) + $time;
        $day['day'] = $days_passed + $day['day'];
        $update_time_query = "UPDATE time SET day='" . mysqli_real_escape_string($db, $day['day']) . "', time='" . mysqli_real_escape_string($db, $day['time']) . "' LIMIT 1";
        if (!mysqli_query($db, $update_time_query)) {
            error_log("MySQLi Error: Failed to update time table: " . mysqli_error($db) . " Query: " . $update_time_query);
        }
        $queries++;
    }

    // Daily resource/mana gain
    if (isset($user['day']) && isset($day['day']) && $user['day'] < $day['day']) {
        $days_diff = $day['day'] - $user['day'];
        $mp2 = 2; // Base mana gain
        
        // Artifact checks for mana gain
        $mp7_result = mysqli_query($db, "SELECT * FROM artifacts where user='" . mysqli_real_escape_string($db, $user['username']) . "' and det='1' and name='wizards_well'");
        if ($mp7_result && mysqli_num_rows($mp7_result) > 0) { $mp2 += 50; }
        $mp5_result = mysqli_query($db, "SELECT * FROM artifacts where user='" . mysqli_real_escape_string($db, $user['username']) . "' and det='1' and name='talisman_of_mana'");
        if ($mp5_result && mysqli_num_rows($mp5_result) > 0) { $mp2 += 2; }
        $mp4_result = mysqli_query($db, "SELECT * FROM artifacts where user='" . mysqli_real_escape_string($db, $user['username']) . "' and det='1' and name='charm_of_mana'");
        if ($mp4_result && mysqli_num_rows($mp4_result) > 0) { $mp2 += 1; }
        $mp6_result = mysqli_query($db, "SELECT * FROM artifacts where user='" . mysqli_real_escape_string($db, $user['username']) . "' and det='1' and name='mystic_orb_of_mana'");
        if ($mp6_result && mysqli_num_rows($mp6_result) > 0) { $mp2 += 3; }
        
        include_once("skils/mistic.php"); // This file might modify $mp2 or related vars

        $mp2 = $mp2 * $days_diff;
        if (isset($user['maxmana']) && isset($user['mana']) && ($user['maxmana'] - $user['mana'] < $mp2)) {
            $mp2 = $user['maxmana'] - $user['mana'];
        }
        $update_mana_query = "UPDATE users SET mana=mana+" . mysqli_real_escape_string($db, $mp2) . " WHERE username='" . mysqli_real_escape_string($db, $user['username']) . "'";
        if (!mysqli_query($db, $update_mana_query)) {
            error_log("MySQLi Error: Failed to update mana: " . mysqli_error($db) . " Query: " . $update_mana_query);
        }

        if ($days_diff > 6) $days_diff = 6; // Limit days for some calculations

        // Resource includes (assuming they define $crt, $stn, $wd, etc.)
        include_once("core/gold.php"); // Needs mysqli migration
        include_once("res/mercury.php"); // Needs mysqli migration
        include_once("res/sulfur.php"); // Needs mysqli migration
        include_once("res/gem.php"); // Needs mysqli migration
        include_once("res/stone.php"); // Needs mysqli migration
        include_once("res/wood.php"); // Needs mysqli migration
        include_once("res/crystal.php"); // Needs mysqli migration
        include_once("res/cor.php"); // Needs mysqli migration

        // Initialize resource variables before use if includes don't guarantee their definition
        $crt = $crt ?? 0;
        $stn = $stn ?? 0;
        $wd = $wd ?? 0;
        $mer = $mer ?? 0;
        $gms = $gms ?? 0;
        $sul = $sul ?? 0;
        $gold_day = $gold_day ?? 0;


        // Update resources
        if ($crt > 0) {
            $crt = $crt * $days_diff;
            $update_crystal_query = "UPDATE users SET crystal=crystal+" . mysqli_real_escape_string($db, $crt) . " WHERE session='" . mysqli_real_escape_string($db, $id) . "' LIMIT 1";
            if (!mysqli_query($db, $update_crystal_query)) {
                error_log("MySQLi Error: Failed to update crystal: " . mysqli_error($db) . " Query: " . $update_crystal_query);
            }
        }
        if ($stn > 0) {
            $stn = $stn * $days_diff;
            $update_stone_query = "UPDATE users SET stone=stone+" . mysqli_real_escape_string($db, $stn) . " WHERE session='" . mysqli_real_escape_string($db, $id) . "' LIMIT 1";
            if (!mysqli_query($db, $update_stone_query)) {
                error_log("MySQLi Error: Failed to update stone: " . mysqli_error($db) . " Query: " . $update_stone_query);
            }
        }
        if ($wd > 0) {
            $wd = $wd * $days_diff;
            $update_wood_query = "UPDATE users SET wood=wood+" . mysqli_real_escape_string($db, $wd) . " WHERE session='" . mysqli_real_escape_string($db, $id) . "' LIMIT 1";
            if (!mysqli_query($db, $update_wood_query)) {
                error_log("MySQLi Error: Failed to update wood: " . mysqli_error($db) . " Query: " . $update_wood_query);
            }
        }
        if ($mer > 0) {
            $mer = $mer * $days_diff;
            $update_mercury_query = "UPDATE users SET mercury=mercury+" . mysqli_real_escape_string($db, $mer) . " WHERE session='" . mysqli_real_escape_string($db, $id) . "' LIMIT 1";
            if (!mysqli_query($db, $update_mercury_query)) {
                error_log("MySQLi Error: Failed to update mercury: " . mysqli_error($db) . " Query: " . $update_mercury_query);
            }
        }
        if ($gms > 0) {
            $gms = $gms * $days_diff;
            $update_gem_query = "UPDATE users SET gem=gem+" . mysqli_real_escape_string($db, $gms) . " WHERE session='" . mysqli_real_escape_string($db, $id) . "' LIMIT 1";
            if (!mysqli_query($db, $update_gem_query)) {
                error_log("MySQLi Error: Failed to update gem: " . mysqli_error($db) . " Query: " . $update_gem_query);
            }
        }
        if ($sul > 0) {
            $sul = $sul * $days_diff;
            $update_sulfur_query = "UPDATE users SET sulfur=sulfur+" . mysqli_real_escape_string($db, $sul) . " WHERE session='" . mysqli_real_escape_string($db, $id) . "' LIMIT 1";
            if (!mysqli_query($db, $update_sulfur_query)) {
                error_log("MySQLi Error: Failed to update sulfur: " . mysqli_error($db) . " Query: " . $update_sulfur_query);
            }
        }

        $lai_result = mysqli_query($db, "SELECT * FROM laivynas WHERE user='" . mysqli_real_escape_string($db, $user['username']) . "'");
        if (!$lai_result) {
            error_log("MySQLi Error: Failed to query laivynas table: " . mysqli_error($db));
            $lai = null;
        } else {
            $lai = mysqli_fetch_array($lai_result, MYSQLI_ASSOC);
        }

        if (isset($lai['user'])) {
            include_once("ships/" . $lai['name'] . ".php"); // Needs mysqli migration, defines $speed
            include_once("skils/navigace.php"); // Needs mysqli migration, defines $nav
            $speed = $speed ?? 0; // Initialize if not set by include
            $nav = $nav ?? 0; // Initialize if not set by include
            if ($nav > 0) {
                $speed = round($speed * $nav);
            }
            $update_laivynas_ejimas_query = "UPDATE laivynas SET ejimas='" . mysqli_real_escape_string($db, $speed) . "' WHERE user='" . mysqli_real_escape_string($db, $user['username']) . "'";
            if (!mysqli_query($db, $update_laivynas_ejimas_query)) {
                error_log("MySQLi Error: Failed to update laivynas ejimas: " . mysqli_error($db) . " Query: " . $update_laivynas_ejimas_query);
            }
        }
        $gold = $days_diff * $gold_day;
        $update_user_day_gold_query = "UPDATE users SET day='" . mysqli_real_escape_string($db, $day['day']) . "', gold=gold+" . mysqli_real_escape_string($db, $gold) . " WHERE session='" . mysqli_real_escape_string($db, $id) . "' LIMIT 1";
        if (!mysqli_query($db, $update_user_day_gold_query)) {
            error_log("MySQLi Error: Failed to update user day and gold: " . mysqli_error($db) . " Query: " . $update_user_day_gold_query);
        }
        $queries++;
    }
    include_once("names/classes.php"); // Assumed to define $class_name
    $class_display = isset($class_name[$user['class']]) ? $class_name[$user['class']] : $user['class'];
    $datex = date("m-d H:i");
    $date = date("l, j F h:iA");
    $mana = $user['knowledge'] * 10;
    include_once("skils/intelekt.php"); // Needs mysqli migration, might modify $mana

    if (isset($user['maxmana']) && $user['maxmana'] < $mana) {
        $update_maxmana_query = "UPDATE users SET maxmana='" . mysqli_real_escape_string($db, $mana) . "' WHERE session='" . mysqli_real_escape_string($db, $id) . "'";
        if (!mysqli_query($db, $update_maxmana_query)) {
            error_log("MySQLi Error: Failed to update maxmana: " . mysqli_error($db) . " Query: " . $update_maxmana_query);
        }
    }

    if (isset($user['level']) && $user['level'] < 3) {
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=infor\">How to play?</a></small><br/>";
    }
    if (isset($user['skill_points']) && $user['skill_points'] > "0") {
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=newskill\">You have " . htmlspecialchars($user['skill_points']) . " unused skillpoints!</a></small><br/>";
    }
    if (isset($user['kvietimas']) && $user['kvietimas'] !== "0") {
        $aly_result = mysqli_query($db, "SELECT * FROM ally WHERE id='" . mysqli_real_escape_string($db, $user['kvietimas']) . "'");
        if (!$aly_result) {
            error_log("MySQLi Error: Failed to query ally table: " . mysqli_error($db));
            $aly = null;
        } else {
            $aly = mysqli_fetch_array($aly_result, MYSQLI_ASSOC);
        }
        
        if ($aly) {
            $alyx = htmlspecialchars($aly['pavadinimas']);
            echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=ally&idz=" . htmlspecialchars($aly['id']) . "\">" . $alyx . "</a> alliance invites you to be one of them member!</small><br/>";
            echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=stot&idz=" . htmlspecialchars($aly['id']) . "\">Agree</a></small> | ";
            echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=mest&idz=" . htmlspecialchars($aly['id']) . "\">Disagree</a></small><br/>$line<br/>";
        }
    }
    if (isset($user['member']) && $user['member'] == "0") {
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=member\">Be a member and be rewarded!!!</a></small><br/>";
    }

    echo "<b><a href='index.php?action=ancs&id=" . htmlspecialchars($id) . "'>[&#187;] News Update [&#171;]</a></b>";
    echo "<br/>$line<br/>";
    echo "<img src=\"img/logobanner.png\" alt=\"" . htmlspecialchars($title ?? 'Heroes of Might and Magic') . "\"/><br/>$line<br/><small>[" . htmlspecialchars($date) . "]</small><br/>$line<br/>";
    
    $eyeko_result = mysqli_query($db, "SELECT COUNT(*) FROM anc");
    $eyeko = ($eyeko_result) ? mysqli_fetch_row($eyeko_result)[0] : 0;

    $hehes_result = mysqli_query($db, "SELECT anc, addedby, id, anctime FROM anc ORDER BY anctime DESC LIMIT 1");
    if ($hehes_result && mysqli_num_rows($hehes_result) > 0) {
        $hehe = mysqli_fetch_array($hehes_result, MYSQLI_ASSOC);
        $x4 = htmlspecialchars(strlen($hehe['anc']) < 25 ? $hehe['anc'] : substr($hehe['anc'], 0, 21));
    }

    $lshouts_result = mysqli_query($db, "SELECT shout, shouter, id, shtime FROM shouts ORDER BY shtime DESC LIMIT 1");
    if ($lshouts_result && mysqli_num_rows($lshouts_result) > 0) {
        $lshout = mysqli_fetch_array($lshouts_result, MYSQLI_ASSOC);
        $shad = $lshout['shtime'];
        $remain = time() - $shad;
        
        // gettimemsg function (assuming it's defined in core.php or similar)
        if (!function_exists('gettimemsg')) {
            function gettimemsg($time_diff) {
                if ($time_diff < 60) return $time_diff . " seconds ago";
                $minutes = round($time_diff / 60);
                if ($minutes < 60) return $minutes . " minutes ago";
                $hours = round($minutes / 60);
                if ($hours < 24) return $hours . " hours ago";
                $days = round($hours / 24);
                return $days . " days ago";
            }
        }
        $past = gettimemsg($remain);
        $shadd = date("g:ia", $shad);
        $shdt = date("M d", $shad);
        $shday = date("l", $shad);
        $tg = explode(" ", $past);
        $pst = "";
        if ($past == "1 day ago") {
            $pst = "Kemarin pada " . $shadd;
        } elseif (($past == "2 days ago") || ($past == "3 days ago") || ($past == "4 days ago") || ($past == "5 days ago") || ($past == "6 days ago")) {
            $pst = "$shday, " . $shadd;
        } elseif (($past == "7 days ago") && ($tg[0] > 6)) {
            $pst = "$shdt at " . $shadd;
        } else {
            $pst = $past;
        }

        echo "<div class='shoutbox-entry'>";
        echo "<span class='shouter-name'><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=nick_info&name=" . htmlspecialchars($lshout['shouter']) . "\">" . htmlspecialchars($lshout['shouter']) . "</a>:</span> ";
        echo "<span class='shout-text'>" . htmlspecialchars($lshout['shout']) . "</span> - <span class='shout-time'>" . htmlspecialchars($pst) . "</span>";
        if ((isset($user['status']) && $user['status'] == "Administrator") || (isset($user['status']) && $user['status'] == "Moderator") || (isset($user['status']) && $user['status'] == "King")) {
            echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "&action=delsh&shid=" . htmlspecialchars($lshout['id']) . "\">X</a>";
        }
        echo "</div>";
    } else {
        echo "No shout yet<br/>";
    }
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "&action=shout\">Shout</a> | ";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "&action=shouts\">More</a><br/>---</br>";
    echo "</p><p class='center'>";
    
    include_once("core/level.php"); // Assumed to define level() function
    $level_info = level($user['level'] ?? 1); // Provide a default if $user['level'] is not set
    if (isset($user['level']) && isset($user['expierence']) && isset($level_info[$user['level']]) && $level_info[$user['level']] <= $user['expierence']) {
        $lev = $user['level'] + 1;
        echo "<small><u><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=level\"><b>You reached " . htmlspecialchars($lev) . " level!</b></a></u></small><br/>***<br/>";
    }
    if (isset($user['battle']) && $user['battle'] > $time) {
        $left = $user['battle'] - $time;
        $sx = ($left < 2) ? "Second" : "Seconds";
        echo "<small><b><u>You need to rest for " . htmlspecialchars($left) . " " . htmlspecialchars($sx) . ".</u></b><br/></small>***<br/>";
    }
    echo "</p><p class='center'>";
    if (isset($user['rain']) && $user['rain'] > time()) {
        echo "<small><b>Experience Rain!!!</b></small><br/>";
        $left2 = $user['rain'] - $time;
        $h2 = floor($left2 / 3600);
        $m2 = floor(($left2 - ($h2 * 3600)) / 60);
        $s2 = $left2 - $h2 * 3600 - $m2 * 60;
        $xh = ($h2 < 2) ? "Hour" : "Hours";
        $xm = ($m2 < 2) ? "Minute" : "Minutes";
        $xs = ($s2 < 2) ? "Second" : "Seconds";
        echo "<small><b>Times left : ";
        if ($h2 > 0) {
            echo htmlspecialchars($h2) . " " . htmlspecialchars($xh) . ", ";
        }
        if ($m2 > 0) {
            echo htmlspecialchars($m2) . " " . htmlspecialchars($xm) . ", ";
        } elseif (($h2 > 0) && ($m2 == "0")) {
            echo htmlspecialchars($m2) . " " . htmlspecialchars($xm) . ", ";
        }
        if ($s2 > 0) {
            echo htmlspecialchars($s2) . " " . htmlspecialchars($xs);
        } else {
            echo htmlspecialchars($s2) . " " . htmlspecialchars($xs);
        }
        echo "</b></small><br/>***<br/>";
    }

    if ((isset($user['sfrenzy']) && $user['sfrenzy'] > time()) || (isset($user['sfrenzy2']) && $user['sfrenzy2'] > 0)) {
        echo "<small><b>Super Frenzy!!!</b></small><br/>";
        if (isset($user['sfrenzy']) && $user['sfrenzy'] > time()) {
            $left2 = $user['sfrenzy'] - $time;
        } else {
            $left2 = $user['sfrenzy2'];
        }
        $h2 = floor($left2 / 3600);
        $m2 = floor(($left2 - ($h2 * 3600)) / 60);
        $s2 = $left2 - $h2 * 3600 - $m2 * 60;
        echo "<small><b>Times left : ";
        $xh = ($h2 < 2) ? "Hour" : "Hours";
        $xm = ($m2 < 2) ? "Minute" : "Minutes";
        $xs = ($s2 < 2) ? "Second" : "Seconds";
        if ($h2 > 0) {
            echo htmlspecialchars($h2) . " " . htmlspecialchars($xh) . ", ";
        }
        if ($m2 > 0) {
            echo htmlspecialchars($m2) . " " . htmlspecialchars($xm) . ", ";
        } elseif (($h2 > 0) && ($m2 == "0")) {
            echo htmlspecialchars($m2) . " " . htmlspecialchars($xm) . ", ";
        }
        if ($s2 > 0) {
            echo htmlspecialchars($s2) . " " . htmlspecialchars($xs);
        } else {
            echo htmlspecialchars($s2) . " " . htmlspecialchars($xs);
        }
        if (($h2 == "0") && ($m2 == "0") && ($s2 == "0")) {
            echo "Expired :(";
        }
        echo "</b></small><br/>";
        $sf = (isset($user['sfrenzy']) && $user['sfrenzy'] > time()) ? "Stop" : "Start";
        echo "<small><a href=\"index.php?action=sfrenzy&id=" . htmlspecialchars($id) . "\">" . htmlspecialchars($sf) . "</a></small><br/>***<br/>";
    }

    if ((isset($user['immortal']) && $user['immortal'] > time()) || (isset($user['fre']) && $user['fre'] > 0)) {
        echo "<small><b>Frenzy!!!</b></small><br/>";
        if (isset($user['immortal']) && $user['immortal'] > time()) {
            $left2 = $user['immortal'] - $time;
        } else {
            $left2 = $user['fre'];
        }
        $h2 = floor($left2 / 3600);
        $m2 = floor(($left2 - ($h2 * 3600)) / 60);
        $s2 = $left2 - $h2 * 3600 - $m2 * 60;
        $xh = ($h2 < 2) ? "Hour" : "Hours";
        $xm = ($m2 < 2) ? "Minute" : "Minutes";
        $xs = ($s2 < 2) ? "Second" : "Seconds";
        echo "<small><b>Times left : ";
        if ($h2 > 0) {
            echo " " . htmlspecialchars($h2) . " " . htmlspecialchars($xh) . ", ";
        }
        if ($m2 > 0) {
            echo htmlspecialchars($m2) . " " . htmlspecialchars($xm) . ", ";
        } elseif (($h2 > 0) && ($m2 == "0")) {
            echo htmlspecialchars($m2) . " " . htmlspecialchars($xm) . ", ";
        }
        if ($s2 > 0) {
            echo htmlspecialchars($s2) . " " . htmlspecialchars($xs);
        } else {
            echo htmlspecialchars($s2) . " " . htmlspecialchars($xs);
        }
        if (($xh == "0") && ($xm == "0") && ($s2 == "0")) {
            echo "Expired :(";
        }
        echo "</b></small><br/>";
        $fr = (isset($user['immortal']) && $user['immortal'] > time()) ? "Stop" : "Start";
        echo "<small><a href=\"index.php?action=frenzy&id=" . htmlspecialchars($id) . "\">" . htmlspecialchars($fr) . "</a></small><br/>***<br/>";
    }
    echo "</p><p class='left'>";
    echo "<small><b>" . htmlspecialchars($user['username'] ?? 'Guest') . "'s Menu</b></small><br/>"; // Provide default for username
    echo "<small><b>[*]</b> <a href=\"index.php?action=mymenu&id=" . htmlspecialchars($id) . "\">My Castle</a></small><br/>";
    echo "<small><b>[*]</b> <a href=\"index.php?action=capitol&id=" . htmlspecialchars($id) . "\">Capitol</a></small>";
    if (isset($user['ally']) && $user['ally'] !== "0") {
        echo "<br/><small><b>[*]</b> <a href=\"index.php?action=ally&id=" . htmlspecialchars($id) . "&idz=" . htmlspecialchars($user['ally']) . "\">Alliance</a></small>";
    }
    echo "<br/><small><b>[*]</b> <a href=\"pm.php?id=" . htmlspecialchars($id) . "\">Inbox [" . htmlspecialchars($newpmm) . "/" . htmlspecialchars($alpm) . "]</a></small><br/>";
    $kr4 = number_format($user['kred'] ?? 0); // Provide default for kred
    echo "<small><b>[*]</b> <a href=\"index.php?action=krd&id=" . htmlspecialchars($id) . "\">Kroin [" . htmlspecialchars($kr4) . "]</a></small><br/>";
    
    $online = 0; // Initialize online count
    $on_result = mysqli_query($db, "SELECT place FROM users WHERE time > " . mysqli_real_escape_string($db, $time));
    if (!$on_result) {
        error_log("MySQLi Error: Failed to query users for online count: " . mysqli_error($db));
    } else {
        $onl = ['kaln' => 0, 'arena' => 0, 'forum' => 0, 'zod' => 0, 'gb' => 0, 'gb2100' => 0]; // Initialize counts
        while ($onn = mysqli_fetch_array($on_result, MYSQLI_ASSOC)) {
            $online++;
            if (isset($onn['place']) && isset($onl[$onn['place']])) {
                $onl[$onn['place']]++;
            }
        }
    }
    echo "</p><p class=\"left\">";
    echo "<small><b><u>Heroes World</u></b></small><br/>";
    echo "<small><b>[*]</b> <a href=\"index.php?action=laiv&id=" . htmlspecialchars($id) . "\">Port</a></small><br/>";
    
    $lands = 0;
    $land_array = []; // Renamed to avoid conflict with $land variable in map includes
    if ($handle = @opendir("map/")) { // @ suppresses warnings if directory doesn't exist
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != "index.php" && $file != "act.php") {
                $file_parts = explode(".", $file);
                if (isset($file_parts[1]) && $file_parts[1] == "") { // Check if it's a directory (no extension)
                    $land_array[$lands] = $file_parts[0];
                    $lands++;
                }
            }
        }
        closedir($handle);
    }
    include_once("names/lands.php"); // Assumed to define $land_name
    for ($t = 0; $t < count($land_array); $t++) {
        $landn = isset($land_name[$land_array[$t]]) ? $land_name[$land_array[$t]] : $land_array[$t];
        if ($t > 0) {
            echo "<br/>";
        }
        if ($land_array[$t] !== "act") {
            echo "<small><b>[*]</b> <a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=" . htmlspecialchars($land_array[$t]) . "\">" . htmlspecialchars($landn) . "</a></small>";
        }
    }
    $left = (isset($day['time']) ? $day['time'] : 0) - $time;
    
    // Online.txt handling (file system)
    $file = @fopen("online.txt", "r");
    $count = ['0', date("m-d H:i")]; // Default value
    if ($file) {
        @flock($file, LOCK_SH); // Shared lock
        $file_content = fgets($file, 255);
        @flock($file, LOCK_UN); // Release lock
        @fclose($file);
        if ($file_content !== false) {
            $count = explode("|", $file_content);
        }
    }
    
    if ($online >= $count[0]) {
        $file = @fopen("online.txt", "w");
        if ($file) {
            @flock($file, LOCK_EX); // Exclusive lock
            $date_online = date("m-d H:i");
            $count_write = "$online|$date_online";
            fputs($file, $count_write);
            @flock($file, LOCK_UN); // Release lock
            @fclose($file);
        }
    }
    $h = floor($left / 3600);
    $m = floor(($left - ($h * 3600)) / 60);
    $s = $left - $h * 3600 - $m * 60;
    echo"
<br/>";
    
    if (isset($topic['max']) && $topic['max'] < $online) {
        $dta = date("Y-m-d H:i:s");
        $max_online_update = "$online $dta";
        $update_spec_max_query = "UPDATE spec SET max='" . mysqli_real_escape_string($db, $max_online_update) . "'";
        if (!mysqli_query($db, $update_spec_max_query)) {
            error_log("MySQLi Error: Failed to update spec max: " . mysqli_error($db) . " Query: " . $update_spec_max_query);
        }
    }
    $to = explode(" ", $topic['max'] ?? '0 0000-00-00 00:00:00'); // Provide default if $topic['max'] is not set
    echo "</p><p class='left'>";
    echo "<small><b><u>Community &amp; Others</u></b></small><br/>";
    echo "<small><b>[*]</b> <a href=\"index.php?action=tavern&id=" . htmlspecialchars($id) . "\">Tavern [" . (isset($onl['gb']) ? htmlspecialchars($onl['gb']) : 0) . "]</a></small><br/>";
    echo "<small><b>[*]</b> <a href='index.php?action=game&id=" . htmlspecialchars($id) . "'>Trivia Quiz [" . (isset($onl['zod']) ? htmlspecialchars($onl['zod']) : 0) . "]</a></small><br/>";
    echo "<small><b>[*]</b> <a href=\"forum.php?id=" . htmlspecialchars($id) . "\">Forum [" . (isset($onl['forum']) ? htmlspecialchars($onl['forum']) : 0) . "]</a></small><br/>";
    echo "<small><b>[*]</b> <a href=\"index.php?action=arena&id=" . htmlspecialchars($id) . "\">Gladiator [" . (isset($onl['arena']) ? htmlspecialchars($onl['arena']) : 0) . "]</a></small><br/>";
    echo "<small><b>[*]</b> <a href='index.php?action=tophero&id=" . htmlspecialchars($id) . "'>Hall of Fame</a></small><br/>";
    echo "<small><b>[*]</b> <a href='index.php?action=infor&id=" . htmlspecialchars($id) . "'>Information</a></small>";
    echo "</p><p class='left'>";
    
    if ((isset($user['status']) && $user['status'] == "Moderator") || (isset($user['username']) && $user['username'] == "Arshc")) {
        echo "<small><b>Staff Panel</b><br/><b>[*]</b> <a href=\"index.php?action=xcpanelx&id=" . htmlspecialchars($id) . "\">Admin Panel</a></small><br/>";
    }
    if ((isset($user['status']) && $user['status'] == "Administrator") || (isset($user['username']) && $user['username'] == "Arshc")) {
        echo "<b>[*]</b> <a href=\"index.php?action=usernick&id=" . htmlspecialchars($id) . "\">User Panel</a>";
    }
    if ((isset($user['status']) && $user['status'] == "Administrator") || (isset($user['username']) && $user['username'] == "Arshc")) {
        echo "<br/><b>[*]</b> <a href=\"index.php?action=tool&id=" . htmlspecialchars($id) . "\">Artifact Tools</a><br/><b>[*]</b> <a href=\"index.php?action=cl1&id=" . htmlspecialchars($id) . "\">Nick Tools</a>";
    }

    echo "</p><p class='center'>";
    $dax = (isset($day['day']) && $day['day'] < 2) ? "Day" : "Days";
    $hx = ($h < 2) ? "Jam" : "Hours";
    $mx = ($m < 2) ? "Minute" : "Minutes";
    $sx = ($s < 2) ? "Second" : "Seconds";
    echo "$line<br/>";
    echo "<small>Games " . htmlspecialchars($dax) . " : </small> " . (isset($day['day']) ? htmlspecialchars($day['day']) : 0) . " " . htmlspecialchars($dax) . "<br/>";
    echo "<small>Next Day : </small><br/>";
    echo "<small>";
    if ($h > 0) {
        echo "<b>" . htmlspecialchars($h) . " </b> " . htmlspecialchars($hx) . ", ";
    }
    if ($m > 0) {
        echo "<b>" . htmlspecialchars($m) . " </b> " . htmlspecialchars($mx) . ", ";
    } elseif (($h > 0) && ($m == "0")) {
        echo "<b>" . htmlspecialchars($m) . " </b> " . htmlspecialchars($mx) . ", ";
    }
    if ($s > 0) {
        echo "<b>" . htmlspecialchars($s) . " </b> " . htmlspecialchars($sx);
    } else {
        echo "<b>" . htmlspecialchars($s) . " </b> " . htmlspecialchars($sx);
    }
    if (($h == "0") && ($m == "0") && ($s == "0")) {
        echo "<b><br/>Resources and mana earned!</b>";
    }
    echo "</small><br/>$line<br/>";
    echo "<b><a href=\"index.php?action=online&id=" . htmlspecialchars($id) . "\"> Now Online [" . htmlspecialchars($online) . "]</a></b><br/>";
    echo "<b><a href=\"index.php?action=maxon&id=" . htmlspecialchars($id) . "\">Max Online [" . htmlspecialchars($to[0]) . "]</a></b><br/>";
    echo "<b><a href=\"index.php?action=logout&id=" . htmlspecialchars($id) . "\">&#171; Logout</a></b>";
} elseif ($action == "allyreit") {
    include_once("include/reit.php");
} elseif ($action == "mymenu") {
    include_once("include/my_menu.php");
} elseif ($action == "pltop") {
    include_once("include/pltop.php");
} elseif ($action == "huinfo") {
    include_once("include/my_unit_info.php");
} elseif ($action == "btop") {
    include_once("include/btop.php");
} elseif ($action == "qtop") {
    include_once("include/qtop.php");
} elseif ($action == "sinfo") {
    include_once("include/skill_info.php");
} elseif ($action == "library") {
    include_once("include/library.php");
} elseif ($action == "ainfo") {
    include("names/artifacts.php");
    include("names/artap.php");
    include_once("include/artifacts_info.php");
} elseif ($action == "useart") {
    include_once("include/useart.php");
} elseif ($action == "online") {
    include_once("include/online.php");
} elseif ($action == "level") {
    include_once("include/level.php");
} elseif ($action == "profile") {
    include_once("include/profile.php");
} elseif ($action == "profile2") {
    include_once("include/profile.php");
} elseif ($action == "profile3") {
    include_once("include/profile.php");
} elseif ($action == "profile4") {
    include_once("include/profile.php");
} elseif ($action == "pvp") {
    include_once("include/pvp.php");
} elseif ($action == "profile5") {
    include_once("include/profile.php");
} elseif ($action == "ns") {
    include_once("include/ns.php");
} elseif ($action == "capitol") {
    include_once("include/capitol.php");
} elseif ($action == "nick_info") {
    include_once("include/view_nick_info.php");
} elseif (in_array($action, ["qturn", "qturn1", "qturn2", "qturn3"])) { // Multiple actions map to qturnyras
    include_once("include/qturnyras.php");
} elseif ($action == "newbie") {
    include_once("include/newbie.php");
} elseif ($action == "trade") {
    include_once("include/trade.php");
} elseif ($action == "alibrary") {
    include_once("include/alibrary.php");
} elseif ($action == "qturn1") {
    include_once("include/qturnyras.php");
} elseif ($action == "newbie1") {
    include_once("include/newbie.php");
} elseif ($action == "qturn2") {
    include_once("include/qturnyras.php");
} elseif ($action == "newbie2") {
    include_once("include/newbie.php");
} elseif ($action == "potion_shop") {
    include_once("include/potion_shop.php");
} elseif ($action == "slibrary") {
    include_once("include/slibrary.php");
} elseif ($action == "wizardry") {
    include_once("include/school_of_wizardry.php");
} elseif ($action == "qturn3") {
    include_once("include/qturnyras.php");
} elseif ($action == "newbie3") {
    include_once("include/newbie.php");
} elseif ($action == "tophero") {
    include_once("include/tophero.php");
} elseif ($action == "artinfo") {
    include_once("include/a_info.php");
} elseif ($action == "top") {
    include_once("include/top.php");
} elseif ($action == "toplan") {
    include_once("include/toplan.php");
} elseif ($action == "quiz") {
    include_once("include/viktorina.php");
} elseif ($action == "cas") {
    include_once("include/casino.php");
} elseif ($action == "reg") {
    include_once("include/ns.php");
} elseif ($action == "gld2") {
    include_once("include/casino.php");
} elseif ($action == "cr2") {
    include_once("include/casino.php");
} elseif ($action == "wear") {
    include_once("include/wear.php");
} elseif ($action == "shout") {
    include_once("shout.php");
} elseif ($action == "shoutproc") {
    include_once("shoutproc.php");
} elseif ($action == "shouts") {
    include_once("shouts.php");
} elseif ($action == "delsh") {
    include_once("deleshout.php");
} elseif ($action == "anc") {
    include_once("xanc.php");
} elseif ($action == "ancp") {
    include_once("xancp.php");
} elseif ($action == "ancs") {
    include_once("xancs.php");
} elseif ($action == "delanc") {
    include_once("xancdel.php");
} elseif ($action == "logout") {
    $name = strtolower($user['username'] ?? '');
    $update_user_time_query = "UPDATE users SET time='" . mysqli_real_escape_string($db, $time) . "' WHERE username='" . mysqli_real_escape_string($db, $name) . "' LIMIT 1";
    if (!mysqli_query($db, $update_user_time_query)) {
        error_log("MySQLi Error: Failed to update user time on logout: " . mysqli_error($db) . " Query: " . $update_user_time_query);
    }
    echo "<small>You are offline!Please come back later!</small><br/>$line<br/><small><a href=\"index.php?lang=" . (isset($lang) ? htmlspecialchars($lang) : '') . "\">$home</a></small>";
} elseif ($action == "usernick") {
    if ((isset($user['id']) && $user['id'] !== "1") && (isset($user['id']) && $user['id'] !== "2") && (isset($user['status']) && $user['status'] !== "Administrator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $wer = isset($_GET["wer"]) ? htmlspecialchars($_GET["wer"]) : '';
    $sts_result = mysqli_query($db, "SELECT * FROM users WHERE id='" . mysqli_real_escape_string($db, $wer) . "' LIMIT 1");
    if (!$sts_result) {
        error_log("MySQLi Error: Failed to query users for usernick: " . mysqli_error($db));
        $sts = null;
    } else {
        $sts = mysqli_fetch_array($sts_result, MYSQLI_ASSOC);
    }
    
    echo "cPanel-Username<br/>";
    echo "<form action=\"index.php?action=xuserinfoxx&id=" . htmlspecialchars($id) . "&wer=" . htmlspecialchars($wer) . "\" method=\"post\">";
    echo "ID: <br/><input type=\"text\" name=\"idxx\" value=\"" . htmlspecialchars($wer) . "\"/><br/>";
    echo "To What Username?:<br/><input name=\"username\" type=\"text\" maxlength=\"20\" value=\"" . (isset($sts['username']) ? htmlspecialchars($sts['username']) : '') . "\"/><br/>";
    echo "Status:<br/><input name=\"userstats\" type=\"text\" maxlength=\"20\" value=\"" . (isset($sts['status']) ? htmlspecialchars($sts['status']) : '') . "\"/><br/>";
    echo "<input type=\"submit\" value=\"Update!\" class=\"button-link\"/><br/>";
    echo "</form>";
    echo "$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "xuserinfoxx") {
    if ((isset($user['id']) && $user['id'] !== "1") && (isset($user['id']) && $user['id'] !== "2") && (isset($user['status']) && $user['status'] !== "Administrator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $wer = isset($_GET["wer"]) ? htmlspecialchars($_GET["wer"]) : '';
    $idxxx = isset($_POST["idxx"]) ? htmlspecialchars($_POST["idxx"]) : '';
    $username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : '';
    $userstats = isset($_POST["userstats"]) ? htmlspecialchars($_POST["userstats"]) : '';
    
    $update_user_info_query = "UPDATE users SET username='" . mysqli_real_escape_string($db, $username) . "', status='" . mysqli_real_escape_string($db, $userstats) . "' WHERE id='" . mysqli_real_escape_string($db, $idxxx) . "' LIMIT 1";
    $res = mysqli_query($db, $update_user_info_query);
    if (!$res) {
        error_log("MySQLi Error: Failed to update user info: " . mysqli_error($db) . " Query: " . $update_user_info_query);
    }

    if ($res) {
        echo "<small>User CodeName Updated!</small><br/>id: " . htmlspecialchars($idxxx) . "<br/>New Nick: " . htmlspecialchars($username) . "<br/>Status: " . htmlspecialchars($userstats);
    } else {
        echo "Error!<br/>";
    }
    echo "<br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=usernick&wer=" . htmlspecialchars($wer) . "\">$back</a><br/></small><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "tool") {
    if ((isset($user['id']) && $user['id'] !== "1") && (isset($user['id']) && $user['id'] !== "2")) {
        echo "Error!</p></div>";
        mysqli_close($db);
        exit;
    }
    $wer = isset($_GET["wer"]) ? htmlspecialchars($_GET["wer"]) : '';
    $sts_result = mysqli_query($db, "SELECT * FROM users WHERE id='" . mysqli_real_escape_string($db, $wer) . "' LIMIT 1");
    if (!$sts_result) {
        error_log("MySQLi Error: Failed to query users for tool: " . mysqli_error($db));
        $sts = null;
    } else {
        $sts = mysqli_fetch_array($sts_result, MYSQLI_ASSOC);
    }

    echo "Artifact Tools-<br/>";
    echo "<form action=\"index.php?action=xgiveart&id=" . htmlspecialchars($id) . "&wer=" . htmlspecialchars($wer) . "\" method=\"post\">";
    if ($wer == "") {
        echo "User: <br/><input type=\"text\" name=\"tuser\"/><br/>";
    } else {
        echo "Give " . htmlspecialchars($wer) . " Artifact?<br/>";
        echo "<input type=\"hidden\" name=\"tuser\" value=\"" . htmlspecialchars($wer) . "\"/>";
    }
    echo "Artifact Name: <br/><input type=\"text\" name=\"artn\"/><br/>";
    echo "Art-Type:<br/><input name=\"artt\" type=\"text\" maxlength=\"20\"/><br/>";
    echo "Quantity?:<br/><input type=\"text\" name=\"ilan\" maxlength=\"2\"/><br/>";
    echo "<input type=\"submit\" value=\"Give Art\" class=\"button-link\"/>";
    echo "</form>";
    echo "<br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "xgiveart") {
    if ((isset($user['id']) && $user['id'] !== "1") && (isset($user['id']) && $user['id'] !== "2")) {
        echo "Error!</p></div>";
        mysqli_close($db);
        exit;
    }
    $wer = isset($_GET["wer"]) ? htmlspecialchars($_GET["wer"]) : '';
    $tuser = isset($_POST["tuser"]) ? htmlspecialchars($_POST["tuser"]) : '';
    $artn = isset($_POST["artn"]) ? htmlspecialchars($_POST["artn"]) : '';
    $artt = isset($_POST["artt"]) ? htmlspecialchars($_POST["artt"]) : '';
    $quan = isset($_POST["ilan"]) ? htmlspecialchars($_POST["ilan"]) : '';

    if ($artn !== "") {
        include_once("names/artifacts.php"); // Assumed to define $artifact_name
        $art_display_name = isset($artifact_name[$artn]) ? $artifact_name[$artn] : $artn;
        echo htmlspecialchars($tuser) . ", received " . htmlspecialchars($quan) . " " . htmlspecialchars($art_display_name) . " - a " . htmlspecialchars($artt) . ".<br/>";
        echo "<img src=\"img/artifact/" . htmlspecialchars($artn) . ".gif\" alt=\"hehe\"/><br/>";
        include_once("artifact/use/" . $artn . ".php"); // Needs mysqli migration
        
        $art_result = mysqli_query($db, "SELECT * FROM artifacts WHERE user='" . mysqli_real_escape_string($db, $tuser) . "' AND name='" . mysqli_real_escape_string($db, $artn) . "'");
        if (!$art_result) {
            error_log("MySQLi Error: Failed to query artifacts for xgiveart: " . mysqli_error($db));
            $art = null;
        } else {
            $art = mysqli_fetch_array($art_result, MYSQLI_ASSOC);
        }

        if (!isset($art['name'])) {
            $insert_artifact_query = "INSERT INTO artifacts(user,name,kiek,type) VALUES ('" . mysqli_real_escape_string($db, $tuser) . "','" . mysqli_real_escape_string($db, $artn) . "','" . mysqli_real_escape_string($db, $quan) . "','" . mysqli_real_escape_string($db, $artt) . "')";
            if (!mysqli_query($db, $insert_artifact_query)) {
                error_log("MySQLi Error: Failed to insert artifact: " . mysqli_error($db) . " Query: " . $insert_artifact_query);
            }
        } else {
            $update_artifact_query = "UPDATE artifacts SET kiek=kiek+" . mysqli_real_escape_string($db, $quan) . " WHERE name='" . mysqli_real_escape_string($db, $artn) . "' AND user='" . mysqli_real_escape_string($db, $tuser) . "'";
            if (!mysqli_query($db, $update_artifact_query)) {
                error_log("MySQLi Error: Failed to update artifact quantity: " . mysqli_error($db) . " Query: " . $update_artifact_query);
            }
        }
    }
    echo "<small></small><br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=tool&wer=" . htmlspecialchars($wer) . "\">$back</a></small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "avand") {
    if (isset($user['status']) && $user['status'] !== "Administrator") {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    include_once("jura.php"); // Needs mysqli migration, defines $jura
    $delete_jura_query = "DELETE FROM jura WHERE type!='game'";
    if (!mysqli_query($db, $delete_jura_query)) {
        error_log("MySQLi Error: Failed to delete from jura table: " . mysqli_error($db) . " Query: " . $delete_jura_query);
    }
    
    if (isset($jura) && is_array($jura)) {
        foreach ($jura as $p_jura) {
            $obi = explode("-", $p_jura);
            if (count($obi) >= 9) { // Ensure enough elements
                $ex = explode("|", $obi[4]);
                $ex2 = explode("|", $obi[5]);
                $tim = time() + (int)($ex[0] ?? 0) * (int)($ex[1] ?? 0); // Cast to int, provide default
                $tim2 = (int)($ex2[0] ?? 0) * (int)($ex2[1] ?? 0); // Cast to int, provide default
                $insert_jura_query = "INSERT INTO jura (name,type,kiek,loc,time,time2,subtype,res,kres) VALUES (
                    '" . mysqli_real_escape_string($db, $obi[0]) . "',
                    '" . mysqli_real_escape_string($db, $obi[1]) . "',
                    '" . mysqli_real_escape_string($db, $obi[2]) . "',
                    '" . mysqli_real_escape_string($db, $obi[3]) . "',
                    '" . mysqli_real_escape_string($db, $tim) . "',
                    '" . mysqli_real_escape_string($db, $tim2) . "',
                    '" . mysqli_real_escape_string($db, $obi[6]) . "',
                    '" . mysqli_real_escape_string($db, $obi[7]) . "',
                    '" . mysqli_real_escape_string($db, $obi[8]) . "'
                )";
                if (!mysqli_query($db, $insert_jura_query)) {
                    error_log("MySQLi Error: Failed to insert into jura table: " . mysqli_error($db) . " Query: " . $insert_jura_query);
                }
            }
        }
    }
    echo "<small>Refreshed</small>";
}

if ($action == "gold") {
    $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    echo "<small>You are transferring <b>" . htmlspecialchars($name) . "</b></small><br/><small>Amount : </small><br/>";
    echo "<form action=\"index.php?id=" . htmlspecialchars($id) . "&action=gold2&name=" . htmlspecialchars($name) . "\" method=\"post\">";
    echo "<input type=\"number\" name=\"gold\" pattern=\"[0-9]*\"/><br/>"; // Use type="number" with pattern for numeric input
    echo "<input type=\"submit\" value=\"Transfer\" class=\"button-link\"/>";
    echo "</form>";
    echo "<br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
}

if ($action == "gold2") {
    $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $gold = isset($_POST['gold']) ? (int)$_POST['gold'] : 0; // Cast to int for numeric comparison
    
    $usr_result = mysqli_query($db, "SELECT * FROM users WHERE username='" . mysqli_real_escape_string($db, $name) . "'");
    if (!$usr_result) {
        error_log("MySQLi Error: Failed to query users for gold2: " . mysqli_error($db));
        $usr = null;
    } else {
        $usr = mysqli_fetch_array($usr_result, MYSQLI_ASSOC);
    }

    if (!isset($user['ally']) || !isset($usr['ally']) || ($user['ally'] < 1) || ($user['ally'] !== $usr['ally'])) {
        echo "<small>Error!</small>";
    } elseif ($gold > ($user['gold'] ?? 0)) { // Add null coalesce for $user['gold']
        echo "<small>Not enough gold!</small>";
    } elseif (isset($user['perv']) && $user['perv'] > time()) {
        echo "<small>You cannot transfer now.</small>";
    } elseif (isset($usr['level']) && (($usr['level'] * 100000) < $gold)) {
        $pgo = $usr['level'] * 100000;
        echo "<small>You cannot transfer more than " . htmlspecialchars($pgo) . " gold.</small>";
    } else {
        $ti = time() + 3600 * 2;
        $update_sender_gold_query = "UPDATE users SET gold=gold-" . mysqli_real_escape_string($db, $gold) . ",perv='" . mysqli_real_escape_string($db, $ti) . "' WHERE username='" . mysqli_real_escape_string($db, $user['username']) . "'";
        if (!mysqli_query($db, $update_sender_gold_query)) {
            error_log("MySQLi Error: Failed to update sender gold: " . mysqli_error($db) . " Query: " . $update_sender_gold_query);
        } else {
            $update_receiver_gold_query = "UPDATE users SET gold=gold+" . mysqli_real_escape_string($db, $gold) . " WHERE username='" . mysqli_real_escape_string($db, $name) . "'";
            if (!mysqli_query($db, $update_receiver_gold_query)) {
                error_log("MySQLi Error: Failed to update receiver gold: " . mysqli_error($db) . " Query: " . $update_receiver_gold_query);
            } else {
                echo "<small>Transferred " . htmlspecialchars($gold) . " gold to " . htmlspecialchars($name) . "</small>";
            }
        }
    }
    echo "<br/>$line<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "truncates") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['id']) && $user['id'] !== "2")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    echo "<b>Truncator Tool(Truncate..)</b><br/>";
    
    $nbkh_result = mysqli_query($db, "SELECT COUNT(*) FROM nbattle");
    $nbkh = ($nbkh_result) ? mysqli_fetch_row($nbkh_result)[0] : 0;
    $pmkh_result = mysqli_query($db, "SELECT COUNT(*) FROM pm");
    $pmkh = ($pmkh_result) ? mysqli_fetch_row($pmkh_result)[0] : 0;
    $obkh_result = mysqli_query($db, "SELECT COUNT(*) FROM objects");
    $obkh = ($obkh_result) ? mysqli_fetch_row($obkh_result)[0] : 0;
    $aukh_result = mysqli_query($db, "SELECT COUNT(*) FROM aukatas");
    $aukh = ($aukh_result) ? mysqli_fetch_row($aukh_result)[0] : 0;
    $mpkh_result = mysqli_query($db, "SELECT COUNT(*) FROM map");
    $mpkh = ($mpkh_result) ? mysqli_fetch_row($mpkh_result)[0] : 0;

    echo "<small><a href=\"index.php?action=deletnbattle&id=" . htmlspecialchars($id) . "\">Battles</a> " . htmlspecialchars($nbkh) . "</small><br/>";
    echo "<small><a href=\"index.php?action=deletpms&id=" . htmlspecialchars($id) . "\">Private Msgs</a> " . htmlspecialchars($pmkh) . "</small><br/>";
    echo "<small><a href=\"index.php?action=deletobjects&id=" . htmlspecialchars($id) . "\">Object Logs</a> " . htmlspecialchars($obkh) . "</small><br/>";
    echo "<small><a href=\"index.php?action=deletlogs&id=" . htmlspecialchars($id) . "\">Market Logs</a> " . htmlspecialchars($aukh) . "</small><br/>";
    echo "<small><a href=\"index.php?action=deletchat&id=" . htmlspecialchars($id) . "\">Chat Msgs</a></small><br/>";
    echo "<small><a href=\"index.php?action=deletqchat&id=" . htmlspecialchars($id) . "\">Quiz Msgs</a></small><br/>";
    echo "<small><a href=\"index.php?action=deletmap&id=" . htmlspecialchars($id) . "\">Map Logs</a> " . htmlspecialchars($mpkh) . "</small><br/>";
    echo "<small><a href=\"index.php?action=deletshouts&id=" . htmlspecialchars($id) . "\">Shouts</a><br/>";
    echo "<small><a href=\"index.php?action=deletancs&id=" . htmlspecialchars($id) . "\">Announcements</a></small><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
}
if ($action == "xcpanelx") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $dti = date("Y-m-d");
    $sn_result = mysqli_query($db, "SELECT COUNT(id) AS num FROM sms WHERE data='" . mysqli_real_escape_string($db, $dti) . "'");
    $snd = ($sn_result) ? mysqli_fetch_assoc($sn_result)['num'] : 0;
    $al_result = mysqli_query($db, "SELECT COUNT(id) AS num FROM sms");
    $all = ($al_result) ? mysqli_fetch_assoc($al_result)['num'] : 0;
    $lt = 0;
    $lt2 = 0;
    
    $ltu_result = mysqli_query($db, "SELECT kaina FROM sms WHERE data='" . mysqli_real_escape_string($db, $dti) . "'");
    if ($ltu_result) {
        while ($row = mysqli_fetch_array($ltu_result, MYSQLI_ASSOC)) {
            $li = $row['kaina'];
            $lt = $lt + $li;
        }
    }
    $ltu2_result = mysqli_query($db, "SELECT kaina FROM sms");
    if ($ltu2_result) {
        while ($row2 = mysqli_fetch_array($ltu2_result, MYSQLI_ASSOC)) {
            $li2 = $row2['kaina'];
            $lt2 = $lt2 + $li2;
        }
    }

    if (isset($user['status']) && $user['status'] == "Moderator") {
        include_once("include/viktorina.class.php"); // Needs mysqli migration
        $cViktorina = new cViktorina(1);
        if (isset($cViktorina->start) && $cViktorina->start) {
            echo "<small><a href=\"index.php?action=vikt&id=" . htmlspecialchars($id) . "\">Stop quiz</a></small><br/>";
        } else {
            echo "<small><a href=\"index.php?action=vikt&id=" . htmlspecialchars($id) . "\">Activate quiz</a></small><br/>";
        }
        echo "<small><a href=\"index.php?action=qmpanel&id=" . htmlspecialchars($id) . "\">Quiz Panel</a></small><br/>";
        echo "<small><a href=\"index.php?action=anc&id=" . htmlspecialchars($id) . "\">Announce</a><br/>";
    } else {
        echo "<small><a href=\"index.php?action=truncates&id=" . htmlspecialchars($id) . "\">Truncator(!)</a></small><br/>";
        echo "<small><a href=\"index.php?action=aukats&id=" . htmlspecialchars($id) . "\">Members Actions</a></small><br/>";
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=reglog\">New Registered Users</a></small><br/>";
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=rpmd\">Messaging Panel</a></small><br/>";
        echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "&action=findip\">Find IP</a></small><br/>";
        include_once("include/viktorina.class.php");
        $cViktorina = new cViktorina(1);
        if (isset($cViktorina->start) && $cViktorina->start) {
            echo "<small><a href=\"index.php?action=vikt&id=" . htmlspecialchars($id) . "\">Stop quiz</a></small><br/>";
        } else {
            echo "<small><a href=\"index.php?action=vikt&id=" . htmlspecialchars($id) . "\">Activate quiz</a></small><br/>";
        }
        echo "<small><a href=\"index.php?action=qmpanel&id=" . htmlspecialchars($id) . "\">Quiz Panel</a></small><br/>";
        //echo"<small><a href=\"ac-viktz-$id\">Daftar kuis</a></small><br/>";
        echo "<small><a href=\"index.php?action=avand&id=" . htmlspecialchars($id) . "\">Reload Port</a></small><br/>";
        echo "<small><a href=\"index.php?action=anc&id=" . htmlspecialchars($id) . "\">Announce</a><br/>";
        echo "<small><a href=\"index.php?action=asms&id=" . htmlspecialchars($id) . "\">SMS</a></small><br/>";
    }
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "dnew") {
    if (isset($user['status']) && $user['status'] !== "Administrator") {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    echo "<small>Title<br/><input type=\"text\" name=\"title\"/><br/>New<br/><input type=\"text\" name=\"new\"/><br/>";
    echo "<form action=\"index.php?action=dnew2&id=" . htmlspecialchars($id) . "\" method=\"post\">";
    echo "<input type=\"text\" name=\"title\" placeholder=\"Title\" required/><br/>";
    echo "<textarea name=\"new\" placeholder=\"News content\" required></textarea><br/>";
    echo "<input type=\"submit\" value=\"Add\" class=\"button-link\"/>";
    echo "</form>";
    echo "<br/>$line<br/><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "dnew2") {
    $dat = date("Y-m-d H:i:s");
    $title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
    $new = isset($_POST['new']) ? htmlspecialchars($_POST['new']) : '';
    $insert_news_query = "INSERT INTO news (title,zin,date) VALUES ('" . mysqli_real_escape_string($db, $title) . "','" . mysqli_real_escape_string($db, $new) . "','" . mysqli_real_escape_string($db, $dat) . "')";
    if (!mysqli_query($db, $insert_news_query)) {
                error_log("MySQLi Error: Failed to insert news: " . mysqli_error($db) . " Query: " . $insert_news_query);
    }
    echo "<small>News was added Successfully<br/>" . htmlspecialchars($title) . ": " . htmlspecialchars($new) . "<br/>$line<br/><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "news") {
    $nws_result = mysqli_query($db, "SELECT COUNT(id) AS num FROM news");
    $ntot = ($nws_result) ? mysqli_fetch_assoc($nws_result)['num'] : 0;
    echo "<small><b>Arshc Heroes III - Updates</b><br/>";
    $psl = isset($_POST['psl']) ? (int)$_POST['psl'] : 1;
    if (!$psl) {
        $psl = 1;
    }
    $nuo = $psl * 10 - 10;
    $iki = $psl * 10;
    if ($ntot < 1) {
        echo "No news yet<br/>";
    } else {
        $news_result = mysqli_query($db, "SELECT title,id FROM news ORDER BY id DESC LIMIT " . mysqli_real_escape_string($db, $nuo) . "," . mysqli_real_escape_string($db, $iki));
        if ($news_result) {
            while ($rowz = mysqli_fetch_array($news_result, MYSQLI_ASSOC)) {
                $tit = $rowz['title'];
                $idz_news = $rowz['id'];
                echo "<a href=\"index.php?action=snew&id=" . htmlspecialchars($id) . "&idz=" . htmlspecialchars($idz_news) . "\">" . htmlspecialchars($tit) . "</a>";
                if ((isset($user['id']) && $user['id'] == "1") || (isset($user['username']) && $user['username'] == "Arshc") || (isset($user['username']) && $user['username'] == "Arshc1")) {
                    echo "<a href=\"index.php?action=delanc&id=" . htmlspecialchars($id) . "&dnew=" . htmlspecialchars($idz_news) . "\">(x)</a>"; // Changed to delanc as per original
                }
                echo "<br/>";
            }
        }
        echo "";
        if ($ntot > 10) {
            $tol = $psl + 1;
            echo "<form action=\"index.php?action=news&id=" . htmlspecialchars($id) . "\" method=\"post\" style=\"display:inline;\">";
            echo "<input type=\"hidden\" name=\"psl\" value=\"" . htmlspecialchars($tol) . "\"/>";
            echo "<input type=\"submit\" value=\"$next\" class=\"button-link\"/>";
            echo "</form>";
        }
        if ($psl > 1) {
            $atg = $psl - 1;
            echo "<form action=\"index.php?action=news&id=" . htmlspecialchars($id) . "\" method=\"post\" style=\"display:inline;\">";
            echo "<input type=\"hidden\" name=\"psl\" value=\"" . htmlspecialchars($atg) . "\"/>";
            echo "<input type=\"submit\" value=\"$back\" class=\"button-link\"/>";
            echo "</form>";
        }
    }
    echo "$line<br/><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "snew") {
    $idz_snew = isset($_GET['idz']) ? htmlspecialchars($_GET['idz']) : '';
    $qua_result = mysqli_query($db, "SELECT date,zin,title FROM news WHERE id='" . mysqli_real_escape_string($db, $idz_snew) . "'");
    if ($qua_result) {
        $rows = mysqli_fetch_array($qua_result, MYSQLI_ASSOC);
        $tit4 = isset($rows['title']) ? $rows['title'] : '';
        $dti = isset($rows['date']) ? $rows['date'] : '';
        $zin = isset($rows['zin']) ? $rows['zin'] : '';
    } else {
        $tit4 = ''; $dti = ''; $zin = '';
        error_log("MySQLi Error: Failed to query news for snew: " . mysqli_error($db));
    }
    echo "<small><b>" . htmlspecialchars($tit4) . "</b><br/>" . htmlspecialchars($zin) . "<br/><b>" . htmlspecialchars($dti) . "</b><br/>$line<br/><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "qmpanel") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    echo "More specific, okay! :)<br/>";
    echo "<form action=\"index.php?action=qmpanelpr&id=" . htmlspecialchars($id) . "\" method=\"post\">";
    echo "Question :<br/><input name=\"question\" maxlength=\"400\"/><br/>";
    echo "Answer :<br/><input name=\"answer\" maxlength=\"100\"/><br/>";
    echo "<input type=\"submit\" value=\"Submit\" class=\"button-link\"/><br/>";
    echo "</form>";
    echo "<a href=\"index.php?action=quizlist&id=" . htmlspecialchars($id) . "\">Quiz List</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "qmpanelpr") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $question = isset($_POST['question']) ? htmlspecialchars($_POST['question']) : '';
    $answer = isset($_POST['answer']) ? htmlspecialchars($_POST['answer']) : '';
    $insert_quiz_qmpanel_query = "INSERT INTO viktorinos_klausimai SET klausimas='" . mysqli_real_escape_string($db, $question) . "', atsakymas='" . mysqli_real_escape_string($db, $answer) . "'";
    $res = mysqli_query($db, $insert_quiz_qmpanel_query);
    if (!$res) {
        error_log("MySQLi Error: Failed to insert quiz question from qmpanelpr: " . mysqli_error($db) . " Query: " . $insert_quiz_qmpanel_query);
    }

    if ($res) {
        echo "Question successfully added!<br/>Question: " . htmlspecialchars($question) . "<br/>Answer : " . htmlspecialchars($answer) . "<br/>";
    } else {
        echo "Error!<br/>";
    }
    echo "<a href=\"index.php?action=quizlist&id=" . htmlspecialchars($id) . "\">Quiz List</a><br/>";
    echo "<a href=\"index.php?action=qmpanel&id=" . htmlspecialchars($id) . "\">Quiz Panel</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "delquiztion") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $idx_quiz = isset($_GET["idx"]) ? htmlspecialchars($_GET["idx"]) : '';
    $delete_quiz_query = "DELETE FROM viktorinos_klausimai WHERE id='" . mysqli_real_escape_string($db, $idx_quiz) . "'";
    $res = mysqli_query($db, $delete_quiz_query);
    if (!$res) {
        error_log("MySQLi Error: Failed to delete quiz question: " . mysqli_error($db) . " Query: " . $delete_quiz_query);
    }

    if ($res) {
        echo "Quiz question successfully deleted!<br/>";
    } else {
        echo "Error!<br/>";
    }
    echo "<a href=\"index.php?action=quizlist&id=" . htmlspecialchars($id) . "\">Quiz List</a><br/>";
    echo "<a href=\"index.php?action=qmpanel&id=" . htmlspecialchars($id) . "\">Quiz Panel</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "quizlist") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
    if ($page <= 0) {
        $page = 1;
    }
    $noi_result = mysqli_query($db, "SELECT COUNT(*) FROM viktorinos_klausimai");
    $num_items = ($noi_result) ? mysqli_fetch_row($noi_result)[0] : 0;
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1) {
        $page = $num_pages;
    }
    $limit_start = ($page - 1) * $items_per_page;

    $sql_quizlist = "SELECT id, klausimas, atsakymas FROM viktorinos_klausimai ORDER BY id LIMIT " . mysqli_real_escape_string($db, $limit_start) . ", " . mysqli_real_escape_string($db, $items_per_page);
    $items_result = mysqli_query($db, $sql_quizlist);
    if (!$items_result) {
        error_log("MySQLi Error: Failed to query quizlist: " . mysqli_error($db) . " Query: " . $sql_quizlist);
    }

    if ($items_result && mysqli_num_rows($items_result) > 0) {
        while ($item = mysqli_fetch_array($items_result, MYSQLI_ASSOC)) {
            echo "Question : " . htmlspecialchars($item['klausimas']) . "<br/>Answer : " . htmlspecialchars($item['atsakymas']) . " <a href=\"index.php?action=editqq&id=" . htmlspecialchars($id) . "&idx=" . htmlspecialchars($item['id']) . "\">(e)</a><a href=\"index.php?action=delquiztion&id=" . htmlspecialchars($id) . "&idx=" . htmlspecialchars($item['id']) . "\">[x]</a><br/>--<br/>";
        }
    } else {
        echo "No questions yet.";
    }
    if ($page > 1) {
        $ppage = $page - 1;
        echo "<a href=\"index.php?action=quizlist&id=" . htmlspecialchars($id) . "&page=" . htmlspecialchars($ppage) . "\">$back</a> ";
    }
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<a href=\"index.php?action=quizlist&id=" . htmlspecialchars($id) . "&page=" . htmlspecialchars($npage) . "\">$next</a>";
    }
    echo "<br/>" . htmlspecialchars($page) . "/" . htmlspecialchars($num_pages) . "<br/>";
    if ($num_pages > 2) {
        echo "<form action=\"index.php\" method=\"get\" style=\"display:inline;\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"" . htmlspecialchars($id) . "\"/>";
        echo "<input type=\"hidden\" name=\"action\" value=\"quizlist\"/>";
        echo "page<input type=\"number\" name=\"page\" size=\"3\"/>";
        echo "<input type=\"submit\" value=\"jump\" class=\"button-link\"/>";
        echo "</form><br/>";
    }
    echo "<a href=\"index.php?action=qmpanel&id=" . htmlspecialchars($id) . "\">Quiz Panel</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "editqq") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $idx_edit = isset($_GET["idx"]) ? htmlspecialchars($_GET["idx"]) : '';
    $xquestion_result = mysqli_query($db, "SELECT klausimas FROM viktorinos_klausimai WHERE id='" . mysqli_real_escape_string($db, $idx_edit) . "'");
    $xquestion = ($xquestion_result) ? mysqli_fetch_row($xquestion_result)[0] : '';
    $xanswer_result = mysqli_query($db, "SELECT atsakymas FROM viktorinos_klausimai WHERE id='" . mysqli_real_escape_string($db, $idx_edit) . "'");
    $xanswer = ($xanswer_result) ? mysqli_fetch_row($xanswer_result)[0] : '';
    
    echo "Question : <input name=\"nquestion\" maxlength=\"400\" value=\"" . htmlspecialchars($xquestion) . "\"/><br/>";
    echo "Answer : <input name=\"nanswer\" maxlength=\"200\" value=\"" . htmlspecialchars($xanswer) . "\"/><br/>";
    echo "<form action=\"index.php?action=editques&id=" . htmlspecialchars($id) . "&idx=" . htmlspecialchars($idx_edit) . "\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"nquestion\" value=\"" . htmlspecialchars($xquestion) . "\"/>"; // Hidden field to pass original value
    echo "<input type=\"hidden\" name=\"nanswer\" value=\"" . htmlspecialchars($xanswer) . "\"/>"; // Hidden field to pass original value
    echo "<input type=\"submit\" value=\"Submit\" class=\"button-link\"/><br/>";
    echo "</form>";
    echo "<a href=\"index.php?action=quizlist&id=" . htmlspecialchars($id) . "\">Quiz List</a><br/>";
    echo "<a href=\"index.php?action=qmpanel&id=" . htmlspecialchars($id) . "\">Quiz Panel</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "editques") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    $xidx = isset($_GET["idx"]) ? htmlspecialchars($_GET["idx"]) : '';
    $fcq = isset($_POST["nquestion"]) ? htmlspecialchars($_POST["nquestion"]) : '';
    $fca = isset($_POST["nanswer"]) ? htmlspecialchars($_POST["nanswer"]) : '';
    $update_quiz_ques_query = "UPDATE viktorinos_klausimai SET klausimas='" . mysqli_real_escape_string($db, $fcq) . "', atsakymas='" . mysqli_real_escape_string($db, $fca) . "' WHERE id='" . mysqli_real_escape_string($db, $xidx) . "'";
    $res = mysqli_query($db, $update_quiz_ques_query);
    if (!$res) {
        error_log("MySQLi Error: Failed to update quiz question: " . mysqli_error($db) . " Query: " . $update_quiz_ques_query);
    }

    if ($res) {
        echo "Question successfully changed!<br/>Question : " . htmlspecialchars($fcq) . "<br/>Answer : " . htmlspecialchars($fca) . "<br/>";
    } else {
        echo "Error!<br/>";
    }
    echo "<a href=\"index.php?action=quizlist&id=" . htmlspecialchars($id) . "\">Quiz List</a><br/>";
    echo "<a href=\"index.php?action=qmpanel&id=" . htmlspecialchars($id) . "\">Quiz Panel</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "blokas") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    echo "<small>Blocked</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
    $name_block = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $update_block_query = "UPDATE users SET blokas='1' WHERE username='" . mysqli_real_escape_string($db, $name_block) . "'";
    if (!mysqli_query($db, $update_block_query)) {
        error_log("MySQLi Error: Failed to block user: " . mysqli_error($db) . " Query: " . $update_block_query);
    }
} elseif ($action == "blokas2") {
    if ((isset($user['status']) && $user['status'] !== "King") && (isset($user['status']) && $user['status'] !== "Administrator") && (isset($user['status']) && $user['status'] !== "Moderator")) {
        echo "Error! Only for admin!</p></div>";
        mysqli_close($db);
        exit;
    }
    echo "<small>Unblocked</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
    $name_unblock = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $update_unblock_query = "UPDATE users SET blokas='0' WHERE username='" . mysqli_real_escape_string($db, $name_unblock) . "'";
    if (!mysqli_query($db, $update_unblock_query)) {
        error_log("MySQLi Error: Failed to unblock user: " . mysqli_error($db) . " Query: " . $update_unblock_query);
    }
} elseif ($action == "nsdel") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>Error!</small></p></div>";
        mysqli_close($db);
        exit;
    }
    echo "<small>Done</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
    $name_nsdel = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $update_nsdel_query = "UPDATE users SET ns='0' WHERE username='" . mysqli_real_escape_string($db, $name_nsdel) . "'";
    if (!mysqli_query($db, $update_nsdel_query)) {
        error_log("MySQLi Error: Failed to update ns: " . mysqli_error($db) . " Query: " . $update_nsdel_query);
    }
} elseif ($action == "cl2") {
    $dnew = isset($_GET['dnew']) ? htmlspecialchars($_GET['dnew']) : '';
    $delete_news_query = "DELETE FROM news WHERE id='" . mysqli_real_escape_string($db, $dnew) . "'";
    if (!mysqli_query($db, $delete_news_query)) {
        error_log("MySQLi Error: Failed to delete news: " . mysqli_error($db) . " Query: " . $delete_news_query);
    }
    echo "News " . htmlspecialchars($dnew) . " Removed";
} elseif ($action == "cl1") {
    if ((isset($user['id']) && $user['id'] !== "1") && (isset($user['id']) && $user['id'] !== "2") && (isset($user['username']) && $user['username'] !== "Arshc")) {
        echo "Only for Admins</p></div>";
        mysqli_close($db);
        exit;
    }
    $wer_cl1 = isset($_GET["wer"]) ? htmlspecialchars($_GET["wer"]) : '';
    $h_result = mysqli_query($db, "SELECT * FROM users WHERE username='" . mysqli_real_escape_string($db, $wer_cl1) . "' LIMIT 1");
    if (!$h_result) {
        error_log("MySQLi Error: Failed to query users for cl1: " . mysqli_error($db));
        $h = null;
    } else {
        $h = mysqli_fetch_array($h_result, MYSQLI_ASSOC);
    }

    echo "Update User Status(use - to deduct)<br/>";
    echo "<form action=\"index.php?action=cl1pr&id=" . htmlspecialchars($id) . "&wer=" . htmlspecialchars($wer_cl1) . "\" method=\"post\">";
    if ($wer_cl1 == "") {
        echo "Username:<br/><input name=\"un\" maxlength=\"100\"/><br/>";
        echo "Rank:<br/><input name=\"prm\" maxlength=\"100\" value=\"Peasant\"/><br/>"; // This 'prm' field is not used in cl1pr, check if it's meant for something else
        echo "Level:<br/><input name=\"lvl\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Exp:<br/><input name=\"exp\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Credit:<br/><input name=\"kr\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Gold:<br/><input name=\"gl\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Gem:<br/><input name=\"gem\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Mercury:<br/><input name=\"merc\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Sulfur:<br/><input name=\"sfr\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Crystal:<br/><input name=\"cry\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Wood:<br/><input name=\"wd\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Ores:<br/><input name=\"stn\" maxlength=\"100\" value=\"0\"/><br/>";
    } else {
        echo "Update " . htmlspecialchars($wer_cl1) . " Status(use - to deduct)<br/>";
        echo "Username:<br/><input name=\"un\" maxlength=\"100\" value=\"" . htmlspecialchars($wer_cl1) . "\"/><br/>";
        echo "Level<small>(" . (isset($h['level']) ? htmlspecialchars($h['level']) : 0) . ")</small>:<br/><input name=\"lvl\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Exp<small>(" . (isset($h['expierence']) ? htmlspecialchars($h['expierence']) : 0) . ")</small>:<br/><input name=\"exp\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Credit<small>(" . (isset($h['kred']) ? htmlspecialchars($h['kred']) : 0) . ")</small>:<br/><input name=\"kr\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Gold<small>(" . (isset($h['gold']) ? htmlspecialchars($h['gold']) : 0) . ")</small>:<br/><input name=\"gl\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Gem<small>(" . (isset($h['gem']) ? htmlspecialchars($h['gem']) : 0) . ")</small>:<br/><input name=\"merc\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Sulfur<small>(" . (isset($h['sulfur']) ? htmlspecialchars($h['sulfur']) : 0) . ")</small>:<br/><input name=\"sfr\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Crystal<small>(" . (isset($h['crystal']) ? htmlspecialchars($h['crystal']) : 0) . ")</small>:<br/><input name=\"cry\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Wood<small>(" . (isset($h['wood']) ? htmlspecialchars($h['wood']) : 0) . ")</small>:<br/><input name=\"wd\" maxlength=\"100\" value=\"0\"/><br/>";
        echo "Stone<small>(" . (isset($h['stone']) ? htmlspecialchars($h['stone']) : 0) . ")</small>:<br/><input name=\"stn\" maxlength=\"100\" value=\"0\"/><br/>";
    }
    echo "<input type=\"submit\" value=\"Submit\" class=\"button-link\"/>";
    echo "</form>";
    echo "----<br/>";
    echo "<form action=\"index.php?action=cl1pr&id=" . htmlspecialchars($id) . "&cl=p\" method=\"post\">";
    echo "ResetPass:<input type=\"password\" maxlength='100' name='rsxpass' title='ResetPass?:' value=''/><br/>";
    echo "Name:<input type=\"text\" maxlength=\"100\" name=\"ursx\" value=\"" . htmlspecialchars($wer_cl1) . "\"/><br/>";
    echo "<input type=\"submit\" value=\"resetpass\" class=\"button-link\"/>";
    echo "</form>";
    echo "<a href=\"index.php?action=xcpanelx&id=" . htmlspecialchars($id) . "\">cPanel</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "cl1pr") {
    if ((isset($user['id']) && $user['id'] !== "1") && (isset($user['id']) && $user['id'] !== "2") && (isset($user['username']) && $user['username'] !== "Arshc")) {
        echo "Only for Admins</p></div>";
        mysqli_close($db);
        exit;
    }
    $cl = isset($_GET['cl']) ? htmlspecialchars($_GET['cl']) : '';
    $np = isset($_POST['rsxpass']) ? htmlspecialchars($_POST['rsxpass']) : '';
    $nu = isset($_POST['ursx']) ? htmlspecialchars($_POST['ursx']) : '';
    $wer = isset($_GET["wer"]) ? htmlspecialchars($_GET["wer"]) : '';
    $xunx = isset($_POST["un"]) ? htmlspecialchars($_POST["un"]) : '';
    $lvl = isset($_POST["lvl"]) ? (int)$_POST["lvl"] : 0;
    $exp = isset($_POST["exp"]) ? (int)$_POST["exp"] : 0;
    $kr = isset($_POST["kr"]) ? (int)$_POST["kr"] : 0;
    $gl = isset($_POST["gl"]) ? (int)$_POST["gl"] : 0;
    $gem = isset($_POST["gem"]) ? (int)$_POST["gem"] : 0;
    $merc = isset($_POST["merc"]) ? (int)$_POST["merc"] : 0;
    $sfr = isset($_POST["sfr"]) ? (int)$_POST["sfr"] : 0;
    $cry = isset($_POST["cry"]) ? (int)$_POST["cry"] : 0;
    $wd = isset($_POST["wd"]) ? (int)$_POST["wd"] : 0;
    $stn = isset($_POST["stn"]) ? (int)$_POST["stn"] : 0;

    if ($cl == 'p') {
        $hashed_reset_pass = md5(md5($np)); // WARNING: Still using MD5 - consider stronger hashing
        $reset_pass_query = "UPDATE users SET password='" . mysqli_real_escape_string($db, $hashed_reset_pass) . "' WHERE username='" . mysqli_real_escape_string($db, $nu) . "'";
        $resx = mysqli_query($db, $reset_pass_query);
        if (!$resx) {
            error_log("MySQLi Error: Failed to reset password: " . mysqli_error($db) . " Query: " . $reset_pass_query);
        }
        if ($resx) {
            echo htmlspecialchars($nu) . "'s Pass was reset to " . htmlspecialchars($np) . "<br/>";
        } else {
            echo "Error to Reset Pass.<br/>";
        }
    } else {
        $update_user_stats_query = "UPDATE users SET level=level+" . mysqli_real_escape_string($db, $lvl) . ", expierence=expierence+" . mysqli_real_escape_string($db, $exp) . ", kred=kred+" . mysqli_real_escape_string($db, $kr) . ", gold=gold+" . mysqli_real_escape_string($db, $gl) . ", gem=gem+" . mysqli_real_escape_string($db, $gem) . ", mercury=mercury+" . mysqli_real_escape_string($db, $merc) . ", sulfur=sulfur+" . mysqli_real_escape_string($db, $sfr) . ", crystal=crystal+" . mysqli_real_escape_string($db, $cry) . ", wood=wood+" . mysqli_real_escape_string($db, $wd) . ", stone=stone+" . mysqli_real_escape_string($db, $stn) . " WHERE username='" . mysqli_real_escape_string($db, $xunx) . "' LIMIT 1";
        $res = mysqli_query($db, $update_user_stats_query);
        if (!$res) {
            error_log("MySQLi Error: Failed to update user stats: " . mysqli_error($db) . " Query: " . $update_user_stats_query);
        }
        if ($res) {
            echo "Success!<br/>User: " . htmlspecialchars($xunx) . "<br/>Level  " . htmlspecialchars($lvl) . ", Experience  " . htmlspecialchars($exp) . ", Credits  " . htmlspecialchars($kr) . ", Gold  " . htmlspecialchars($gl) . ", Gem  " . htmlspecialchars($gem) . ", Mercury  " . htmlspecialchars($merc) . ", Sulfur  " . htmlspecialchars($sfr) . ", Crystal  " . htmlspecialchars($cry) . ", Wood  " . htmlspecialchars($wd) . ", Ores  " . htmlspecialchars($stn) . ". done<br/>";
        } else {
            echo "Error!<br/>";
        }
    }
    echo "<a href=\"index.php?action=cl1&id=" . htmlspecialchars($id) . "&wer=" . htmlspecialchars($wer) . "\">$back</a><br/>";
    echo "<a href=\"index.php?action=xcpanelx&id=" . htmlspecialchars($id) . "\">cPanel</a><br/>";
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a>";
} elseif ($action == "deletintuseritotaliai") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    echo "<small>Totally Deleted</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
    $name_del_total = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $mame = strtolower($name_del_total);
    
    $delete_queries = [
        "DELETE FROM users WHERE username='" . mysqli_real_escape_string($db, $name_del_total) . "'",
        "DELETE FROM army WHERE username='" . mysqli_real_escape_string($db, $name_del_total) . "'",
        "DELETE FROM artifacts WHERE user='" . mysqli_real_escape_string($db, $name_del_total) . "'",
        "DELETE FROM aukcionas WHERE user='" . mysqli_real_escape_string($db, $name_del_total) . "'",
        "DELETE FROM war WHERE user='" . mysqli_real_escape_string($db, $name_del_total) . "'",
        "DELETE FROM nbattle WHERE heroe='" . mysqli_real_escape_string($db, $name_del_total) . "'",
        "DELETE FROM barak WHERE user='" . mysqli_real_escape_string($db, $name_del_total) . "'"
    ];
    foreach ($delete_queries as $query) {
        if (!mysqli_query($db, $query)) {
            error_log("MySQLi Error: Failed to delete user data: " . mysqli_error($db) . " Query: " . $query);
        }
    }
} elseif ($action == "deletintuseri") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>Deleted</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
        mysqli_close($db); // Close DB connection before exiting
        exit;
    }
    echo "<small>Deleted</small><br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
    $name_del = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $update_deleted_query = "UPDATE users SET deleted='1' WHERE username='" . mysqli_real_escape_string($db, $name_del) . "'";
    if (!mysqli_query($db, $update_deleted_query)) {
        error_log("MySQLi Error: Failed to mark user as deleted: " . mysqli_error($db) . " Query: " . $update_deleted_query);
    }
} elseif ($action == "deletnbattle") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM nbattle");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from nbattle: " . mysqli_error($db));
        echo "<small>Error deleting nbattle</small>";
    } else {
        echo "<small>Neutral Battles Cleared</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletpms") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM pm");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from pm: " . mysqli_error($db));
        echo "<small>Error deleting pm</small>";
    } else {
        echo "<small>Private Msgs Cleared</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletobjects") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM objects");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from objects: " . mysqli_error($db));
        echo "<small>Error deleting objects</small>";
    } else {
        echo "<small>Object Logs Cleared</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletlogs") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM aukatas");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from aukatas: " . mysqli_error($db));
        echo "<small>Error deleting aukats/bm</small>";
    } else {
        echo "<small>Market Logs Cleared</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletchat") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM chat");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from chat: " . mysqli_error($db));
        echo "<small>Error deleting chat</small>";
    } else {
        echo "<small>Chat Msgs Cleared</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletqchat") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM achat");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from achat: " . mysqli_error($db));
        echo "<small>Error deleting achat</small>";
    } else {
        echo "<small>Quiz Msgs Cleared</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletmap") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM map");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from map: " . mysqli_error($db));
        echo "<small>Error deleting map</small>";
    } else {
        echo "<small>Map Truncated</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletshouts") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM shouts");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from shouts: " . mysqli_error($db));
        echo "<small>Shouts Truncated</small>";
    } else {
        echo "<small>Shouts Truncated</small>";
    }
    echo "<br/><small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "deletancs") {
    if (isset($user['id']) && $user['id'] !== "1") {
        echo "<small>You can not be here</small></p></div>";
        mysqli_close($db);
        exit;
    }
    $res = mysqli_query($db, "DELETE FROM anc");
    if (!$res) {
        error_log("MySQLi Error: Failed to delete from anc: " . mysqli_error($db));
        echo "<small>Error deleting anc</small>";
    } else {
        echo "<small>Ancs Truncated</small>";
    }
    echo "<small><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
} elseif ($action == "thegame") {
    echo "<img src=\"img/banner.png\" alt=\"" . htmlspecialchars($title ?? 'Heroes of Might and Magic') . "\"/><br/>
    <small><b><u>heroes.us.tc</u></b><br/>$line<br/>
    If you have any concerns regarding the Game you can contact Admin via:<br/>
    <small>Email:</small><b> kylou21@gmail.com</b><br/>Twitter: @kh1r4<br/>
    <small>Phone Number:</small><b> 639063434723</b>(PH GLOBE)<br/>
    or go to Community site where he goes: <small><b>http://pinoypark.2ks.info/</b></small><br/>
    You can help us grow by clicking our ads you see below<br/>[here:]<br/><small><b>
    <a href=\"http://ad.Wap4Dollars.in/adServelet?rm=NGYyMGM5YjMxNDc2Yw==\">Recharge</a><br/><a href=\"http://ad.Wap4Dollars.in/adServelet?rm=NGYyMGM5YjMxNDc2Yw==\">More Cool Sites Here!!</a></b></small><br/>
    Thank you for the Continiually supporting us ;).
    <br/>$line<br/>
    <a href=\"index.php?id=" . htmlspecialchars($id) . "\">[&#187;] Back to Game</a></small>";
} elseif ($action == "support") {
    echo "<img src=\"img/banner.png\" alt=\"" . htmlspecialchars($title ?? 'Heroes of Might and Magic') . "\"/><br/>
    <small><b><u>Please Support us by Clicking our ads Below(each click is a big help)</u></b><br/>$line<br/>
    <small><b>
    <a href=\"http://ad.Wap4Dollars.in/adServelet?rm=NGYyMGM5YjMxNDc2Yw==\">Click Here</a></b></small><br/>
    Thank you for Continueally supporting us ;) .
    <br/>$line<br/>
    <a href=\"index.php?id=" . htmlspecialchars($id) . "\">[&#187;] Back to Game</a></small>";
} elseif ($action == "maxon") {
    $max = explode(" ", $topic['max'] ?? '0 0000-00-00 00:00:00'); // Provide default for $topic['max']
    echo "<small>Max Online:<b>" . htmlspecialchars($max[0]) . "</b><br/>Date:<b>" . htmlspecialchars($max[1]) . "<br/>" . htmlspecialchars($max[2]) . "</b><br/>$line<br/><a href=\"index.php?id=" . htmlspecialchars($id) . "\">$home</a></small>";
}

// Convert <do> blocks to HTML links for main navigation
if ($action == "map") {
    echo "<div class='options-menu'>";
    // $i, $j, $k variables are not defined in index.php's current scope,
    // they likely come from $_GET or other included files.
    // Added isset() checks for robustness.
    $i = isset($_GET['i']) ? htmlspecialchars($_GET['i']) : '';
    $j = isset($_GET['j']) ? htmlspecialchars($_GET['j']) : '';
    $k = isset($_GET['k']) ? htmlspecialchars($_GET['k']) : '';

    if (isset($user['new_pm']) && $user['new_pm'] > 0) {
        echo "<a href=\"pm.php?id=" . htmlspecialchars($id) . "&i=$i&j=$j&k=$k\">" . htmlspecialchars($user['new_pm']) . " New Mail</a>";
    } else {
        echo "<a href=\"pm.php?id=" . htmlspecialchars($id) . "&i=$i&j=$j&k=$k\">MailBox</a>";
    }
    echo "<a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=$i&j=$j&k=$k\">Refresh</a>";
    
    // These variables ($territory, $land) are defined within map-related includes,
    // so they might not be available here if those includes haven't run.
    // Added isset() checks.
    if ($k !== "" && isset($territory)) {
        echo "<a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=$i&j=$j\">" . htmlspecialchars($territory) . "</a>";
    }
    if ($j !== "" && isset($land)) {
        echo "<a href=\"index.php?action=map&id=" . htmlspecialchars($id) . "&i=$i\">" . htmlspecialchars($land) . "</a>";
    }
    echo "<a href=\"index.php?id=" . htmlspecialchars($id) . "\">$homet</a>";
    echo "</div>";
}

// Close the main card-content div and centered paragraph if opened
if ($action !== "arena") {
    echo "</p></div>";
}
?>
<p class='center'><?php echo $line; ?><br/>
<b>Heroes of Might and Magic</b>
</p>
</div> <!-- Close container -->
<?php
// Ensure $db is a valid mysqli object before attempting to close.
if (isset($db) && $db instanceof mysqli) {
    mysqli_close($db); // Close the database connection
}
?>
</body>
</html>
