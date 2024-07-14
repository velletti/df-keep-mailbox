Installation  
============
clone this repository not inside of default folder "/webseiten" of domain factory.  
f.e. use: df-keep-mailbox

    cd ~
    git clone git@github.com:velletti/df-keep-mailbox.git df-keep-mailbox --branch main
    composer install --no-dev


Configuration:
==============

start tool once with the email address that should receive all mails:

    php run.php <your@Reciever.xx>

This will create a: 'config.json' with following content:

    {
        "csvFile": "\/var\/www\/html\/tools\/receiverlist.csv",
        "to": "your@test.xx",
        "subject": "[DF-Mail] keep inbox alive Test ",
        "body": "Mail can be  deleted safely",
        "debug": false
}

In case of errors, Adjust the line "TO" and put their YOUR inbox, where you want to receive the keep-alive emails.  
(it should be an inbox that allows filtering, to keep this kind of eMails out of your daily work )

if TO is a valid Email Address, this script will create an example file named: 'receiverlist.csv' with following content:

    "email","username","password","smtp-server","port"
    "test@test.de","test@test.de","pass\"word","sslout.df.eu","465"


adjust the second line. the Char: '"' in passwords has to be masked with a slash ( '\' ) sign. 
Add as much lines as you want; But maybe it is a good idea, to test it first with one line.


Hints:
======

set up a cron job that calls this php script once a week with 

    /usr/bin/php81 -c /etc/opt/remi/php81/php.ini /kunden/client_contract/df-keep-mailbox/run.php

Or adjust config.json to read receiver.csv from a file from outside of your web root
or add a .htaccess and a .htpasswd file to protect this folder 
    




