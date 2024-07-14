<?php 

include "vendor/autoload.php" ;
include "src/Mailsend.php" ;

$mail = new \JVelletti\Mailsend\MailSender ;


$subject = '[TEST] - Does mailbox work';
$body = 'Can be deleted safely';
$defaultToMail = false ;
if (isset($argv[1])) {
    if (filter_var($argv[1], FILTER_VALIDATE_EMAIL)) {
        $defaultToMail = $argv[1];
    }
}
if ( ! file_exists('config.json')) {
    $config['csvFile'] = __DIR__ . "/receiverlist.csv" ;
    $config['to'] = $defaultToMail ? $defaultToMail : "your@test.xx" ;
    $config['subject'] = "[DF-Mail] keep inbox alive Test " ;
    $config['body'] = "Mail can be  deleted safely" ;
    $config['debug'] = false ;
    file_put_contents( 'config.json' , json_encode( $config , JSON_PRETTY_PRINT)) ;

    echo "\n created config.json. " ;
    if (  $config['to'] == "your@test.xx" ) {
        echo "\n \n Adjust at least the \"to\" address to your needs, before we can start!" ;
        echo "\n \n ";
        echo json_encode( $config , JSON_PRETTY_PRINT)   ;
        echo "\n \n ";
        die;
    }

} else {
    $config = json_decode ( file_get_contents( 'config.json' ) , true ) ;
    if ( $defaultToMail ) {
        $config['to'] = $defaultToMail ;
    }
}


if ( isset( $config['csvFile'] ) && isset( $config['to'] )  && isset( $config['subject'] )  && isset( $config['body'] )  ) {
    $mail->sendEmailsFromCSV( $config ) ;
} else {
    Echo "ERROR: json from config.ini File could not be converted to array \n" ;
    Echo "Fix the json structure or delete the file to get a fresh file created" ;
}





