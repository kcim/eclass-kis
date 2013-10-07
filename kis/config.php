<?
// available apps of user types //
$kis_config['apps'][] = 'iportfolio';
$kis_config['apps'][] = 'eattendance';
$kis_config['apps'][] = 'message';
$kis_config['apps'][] = 'calendar';
$kis_config['apps'][] = 'enotice';
$kis_config['apps'][] = 'sms';
$kis_config['apps'][] = 'admission';
$kis_config['apps'][] = 'album';
$kis_config['apps'][] = 'econtent';
$kis_config['apps'][] = 'einventory';
$kis_config['apps'][] = 'website';
$kis_config['apps'][] = 'elibrary';
$kis_config['apps'][] = 'shop';
$kis_config['apps'][] = 'epayment';
$kis_config['apps'][] = 'pos';
$kis_config['apps'][] = 'schoolsettings';
$kis_config['apps'][] = 'accountmanage';

// econtents //
$kis_config['econtent']['chinese']['strokecards'][] = array('title'=>'numbers', 'image'=>'card_02', 'href'=>'/kis/resources/econtent/chinese/strokecards/content_flash_card_chn.htm');
$kis_config['econtent']['chinese']['strokecards'][] = array('title'=>'body', 	'image'=>'card_02');
$kis_config['econtent']['chinese']['strokecards'][] = array('title'=>'family', 	'image'=>'card_02');
$kis_config['econtent']['chinese']['strokecards'][] = array('title'=>'weather', 'image'=>'card_02');
$kis_config['econtent']['chinese']['strokecards'][] = array('title'=>'sports', 	'image'=>'card_02');

$kis_config['econtent']['english']['flashcards'][]  = array('title'=>'body',  	'image'=>'card_01', 'href'=>'/kis/resources/econtent/english/target_vocab/content_portal_eng.php');
$kis_config['econtent']['english']['flashcards'][]  = array('title'=>'family',  	'image'=>'card_01');
$kis_config['econtent']['english']['flashcards'][]  = array('title'=>'weather',  'image'=>'card_01');
$kis_config['econtent']['english']['flashcards'][]  = array('title'=>'numbers',  'image'=>'card_01');
$kis_config['econtent']['english']['flashcards'][]  = array('title'=>'sports',  	'image'=>'card_01');
$kis_config['econtent']['english']['storybooks'][]  = array('title'=>'book_01', 	'image'=>'book_01');
$kis_config['econtent']['english']['storybooks'][]  = array('title'=>'book_02', 	'image'=>'book_02');

$kis_config['econtent']['math']['addition'][] 	    = array('title'=>'add_01', 'image'=>'math', 'href'=>'/kis/resources/econtent/math/num_calculate.html');
$kis_config['econtent']['math']['subtraction'][] 	    = array('title'=>'sub_01', 'image'=>'math');
$kis_config['econtent']['math']['addition'][] 	    = array('title'=>'add_02', 'image'=>'math');
$kis_config['econtent']['math']['subtraction'][] 	    = array('title'=>'sub_02', 'image'=>'math');
					   
$kis_config['econtent']['putonghua'] 		    = array();

$kis_config['econtent']['others'] 		    = array();

$kis_config['background'][kis::$user_types['parent']] 	= 'parent';
$kis_config['background'][kis::$user_types['student']] 	= 'student';
$kis_config['background'][kis::$user_types['teacher']] 	= 'teacher';

$kis_config['iportfolio']['guardian']['quota'] 	= 2;

?>