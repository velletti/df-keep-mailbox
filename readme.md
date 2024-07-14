installation  
###############

    git clone 
    composer install --no-dev


configuration
###############

start tool once with 

    php run.php

This will create an example file named: 'receiverlist.csv' with following content:

    "email","username","password","smtp-server","port"
    "test@test.de","test@test.de","pass\"word","sslout.df.eu","465"



adjust the second line. the Char: "  in Passwords has to be masked with the \ sign 
add as much lines as you want; But maybe it is a good idea, to test it first with one line.




