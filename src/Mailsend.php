<?php

namespace JVelletti\Mailsend;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailSender
{
    public function sendEmailsFromCSV($config): void
    {
        if (!file_exists($config['csvFile'])) {
            $csvData = '"email","username","password","smtp-server","port" ' . "\n" .
                '"test@test.de","test@test.de","","sslout.df.eu","465"';

            file_put_contents($config['csvFile'], $csvData);
            echo "\n\nSETUP NOT FINISHED WARNING: \n";
            echo "Did not Find file: '" . $config['csvFile'] . "'. It is created with dummydata. Please adjust email, user and password. If password contains '\"' , escape it with '\\' ";
            echo "exit \n\n";
            die;
        }

        if ( trim($config['to']) ==  "your@test.xx" ) {
            echo "\n\nERROR: \n";
            echo "Please correct config.json: '" . $config['to'] . "' is still set to 'your@test.xx' ";
            echo "exit \n\n";
            die;
        }
        if (extension_loaded('openssl')) {
            echo  "\n" . 'SSL loaded'. "\n";
        } else {
            echo 'SSL not loaded' . "\n\n";
            echo "exit \n\n";
            die;
        }

        $csvData = file_get_contents($config['csvFile']);
        $lines = explode(PHP_EOL, $csvData);

        // Loop through each line in the CSV file,but skip first line with headers ..
        $headers = str_getcsv(array_shift($lines));
        echo  "\n Got " . count($lines) .  " Lines (including empty line or lines starting with a # \n"  ;
        foreach ($lines as $line) {
            $line = trim($line) ;
            if ( strlen($line ) > 10 && !str_starts_with( $line ,"#") ) {
                $data = str_getcsv($line, ",", '"');
                if (count($data) > 4) {
                    try {
                        // Extract the data from the CSV line $fromMailAddress = $data[0];
                        if ( $data[0] == "test@test.de" ) {
                            echo "\n ERROR: user/email: " . $data[0]. " is still default value !! \n" ;
                        }

                        $mail = new PHPMailer;
                        $mail->isSMTP();

                        // Set the SMTP settings
                        $mail->Host = ($data[3] ?? ' ');
                        $mail->Port = ($data[4] ?? ' ');
                        $mail->SMTPAuth = true;
                        $mail->Username = $data[1] ?? $data[0] ;

                        if ( strlen ( $data[2] ) > 4 ) {
                            $mail->Password = $data[2] ;

                            $mail->setFrom($data[0], $mail->Username);
                            $mail->addAddress($config['to'], "Keep Alive Tool");
                            $mail->Subject = $config['subject'] . " FROM " . $data[0];
                            $mail->Body = $config['body'];
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

                            if ($config['debug']) {
                                $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
                            }

                            echo " \n\n";
                            echo 'try to send from  ' . $data[0] . "\n";
                            echo 'User:  ' . $mail->Username . " : " . substr($mail->Password, 0, 2) . str_repeat(".", strlen($mail->Password) - 4) . substr($mail->Password, -2, 2) . "\n";
                            if ($mail->send()) {
                                echo 'SUCCESS Email sent from ' . $data[0] . "\n";
                            } else {
                                echo 'Failed to send email from ' . $data[0] . ': ' . $mail->ErrorInfo . "\n\n";
                            }
                        } else {
                            echo "\n ERROR: No or too short password set for user/email: " . $data[0]. "\n\n" ;
                        }

                    } catch (\Exception $e) {
                        echo 'Exception while sending to ' . $data[0] . ': ' . $e->getMessage() . "\n";
                    }

                } else {
                    echo "\n line Skipped  " . ( $data[0] ?? ' empty data line ' ). "\n\n" ;
                }
            }

        }
    }
}
