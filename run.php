<?php 

include "vendor/autoload.php" ;
include "src/Mailsend.php" ;

$mail = new \JVelletti\Mailsend\MailSender ;


$subject = '[TEST] - Does mailbox work';
$body = 'Can be deleted safely';

if ( ! file_exists('config.ini')) {
    $config['csvFile'] = __DIR__ . "/receiverlist.csv" ;
    $config['to'] = "your@ttest.xx" ;
    $config['subject'] = "[DF-Mail] keep inbox alive Test " ;
    $config['body'] = "Mail can be  deleted safely" ;
    $config['debug'] = false ;
    file_put_contents( 'config.ini' , json_encode( $config )) ;
    echo "\n created config.ini. Adjust at least the \"to\" address to your needs, before we can start!" ;
    die;
} else {
    $config = json_decode ( file_get_contents( 'config.ini' ) , true ) ;
}

if ( isset( $config['csvFile'] ) && isset( $config['to'] )  && isset( $config['subject'] )  && isset( $config['body'] )  ) {
    $mail->sendEmailsFromCSV( $config ) ;
} else {
    Echo "ERROR: json from config.ini File could not be converted to array \n" ;
    Echo "Fix the json structure or delete the file to get a fresh file created" ;
}





