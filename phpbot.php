<?php

require_once('twitteroauth-master/twitteroauth/twitteroauth.php'); //Require Twitter stuffs
require_once('twitterbotclass.php'); //Bot class. Makes stuff a bit clener/easier to do

set_time_limit(0); //Set unlimited code execution time.
ignore_user_abort();

function run($tweets){
    
    global $bot;
    
    foreach($tweets->statuses as $tweet){ //Foreach through each tweet
        
        if(!$bot->inblacklist($tweet->user->id_str)){ //Be sure they are not in the blacklist
            if(rand(1,2) == 2){ //Either just retweet or rt and favorite
                $bot->retweet($tweet->id_str, $tweet->user->id_str); //Retweet. param one is the tweet id and param two is the user id
            }
            else{
                $bot->favorite($tweet->id_str, $tweet->user->id_str);
                $bot->retweet($tweet->id_str, $tweet->user->id_str);
            }
        }
        sleep(60); //1 minute between each retweet
    }
    
}

$bot = new twitterbot;
$bot->connect('', '', '', ''); //Connect using the oauth keys found on apps.twitter.com (Read and write needed)

$bot->addblacklist(array("1390693447", "558800766", "2198265000")); //Black list twitter ids (http://gettwitterid.com/)

$bot->checkignore(array("ignore", "stop"), "You have been added to the ignore list."); //Ignore list. WILL NOT WORK WITHOUT MongoDB. I will make a version to work with a .json file one day.

$bot->action("run", $bot->search("#php OR #php5 OR #phpgd OR @phpstorm OR #phpdev")); //Tell the bot what it should do. First param is the function to run. Second is the tweets it should work with. (So search in this case)

?>