installation  
###############

    git clone 
    composer install --no-dev


configuration
###############

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
add as much lines as you want; But maybe it is a good idea, to test it first with one line.




