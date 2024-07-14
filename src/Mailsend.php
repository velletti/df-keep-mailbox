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
                '"test@test.de","test@test.de","pass\"word","sslout.df.eu","465"';

            file_put_contents($config['csvFile'], $csvData);
            echo "Error: \n";
            echo "Did not Find file: '" . $config['csvFile'] . "'. It is created with dummydata. Please adjust email, user and password. if Password contains '\"' , escape it with '\\' ";
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
            echo 'SSL loaded'. "\n";
        } else {
            echo 'SSL not loaded' . "\n\n";
            echo "exit \n\n";
            die;
        }

        $csvData = file_get_contents($config['csvFile']);
        $lines = explode(PHP_EOL, $csvData);

        // Loop through each line in the CSV file,but skip first line with headers ..
        $headers = str_getcsv(array_shift($lines));

        foreach ($lines as $line) {
            $data = str_getcsv($line, ",", '"');
            if (count($data) > 4) {
                try {
                    // Extract the data from the CSV line
                    $fromMailAddress = $data[0];

                    $mail = new PHPMailer;
                    $mail->isSMTP();

                    // Set the SMTP settings
                    $mail->Host = ($data[3] ?? ' ');
                    $mail->Port = ($data[4] ?? ' ');
                    $mail->SMTPAuth = true;
                    $mail->Username = $data[1] ?? $data[0] ;

                    if ( $data[2]  ) {
                        $mail->Password = $data[2] ;

                        $mail->setFrom($fromMailAddress, $mail->Username);
                        $mail->addAddress($config['to'], "Keep Alive Tool");
                        $mail->Subject = $config['subject'] . " FROM " . $fromMailAddress;
                        $mail->Body = $config['body'];
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

                        if ($config['debug']) {
                            $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
                        }

                        echo " \n\n";
                        echo 'try to send from  ' . $fromMailAddress . "\n";
                        echo 'User:  ' . $mail->Username . " : " . substr($mail->Password, 0, 2) . str_repeat(".", strlen($mail->Password) - 4) . substr($mail->Password, -2, 2) . "\n";
                        if ($mail->send()) {
                            echo 'SUCCESS Email sent to ' . $fromMailAddress . "\n";
                        } else {
                            echo 'Failed to send email to ' . $fromMailAddress . ': ' . $mail->ErrorInfo . "\n";
                        }
                    }

                } catch (\Exception $e) {
                    echo 'Exception while sending to ' . $fromMailAddress . ': ' . $e->getMessage() . "\n";
                }

            }
        }
    }
}
