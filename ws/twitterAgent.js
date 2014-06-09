
var util = require('util'),
    twitter = require('twitter'),
    http = require("http");
    var request = require('request');
    
var twit = new twitter({
    consumer_key: 'WQOK7uxRI60gsCTsHnAMw',
    consumer_secret: 'wPlLms2qIeqUBCcdFo2vAOWCcPMUm3hzmgI43EzMs',
    access_token_key: '18130555-8AIIFpWONSmQ3TkSYhA7fNAhpidFa7kEmKvpcY',
    access_token_secret: 'lf6eTj0ITnLErxi0ZJhsxEjAhhyyCWh2H0pam0'
});

function parseTwitterDate($stamp)
{		
// convert to local string and remove seconds and year //		
	var date = new Date(Date.parse($stamp)).toLocaleString();
// return the formatted string //
	//return date.substr(0, 11)+ hour + date.substr(13);
        return date.toString();
}

var tracks = "";
var tpsCalcTime = 10000;
var tweetCounter = 0;

var int = setInterval(function(){
    console.log(tweetCounter / 60 + " TPS");
    tweetCounter = 0;
}, 60000);


request.post(
'http://hashtagmyevent.com/getActive',
{  },
function (error, response, body) {
    if (!error && response.statusCode == 200) {
        tracks = body;
    }         

twit.stream('filter', {track:'ozarks'}, function(stream) {
    console.log(tracks);
    stream.on('data', function(data) {
        var tweet_body =  data.text;
        var tweet_author = data.user.screen_name;
        var tweet_author_pic = data.user.profile_image_url;
        var tweet_id = data.id;
        var tweet_pic = "";
        var tweet_time = parseTwitterDate(data.created_at);
        var tweet = {
            siteId: '88',
            body: tweet_body,
            screen_name: tweet_author,
            profile_image: tweet_author_pic,
            tweet_pic: tweet_pic,
            tweet_id: tweet_id,
            tweet_time: tweet_time,
            tweet_type: 'twitter'
          };
					
					extractImg(tweet, function(url){
                        if(url != -1) {
                            tweet_pic = url;
                        }
                        request.post(
													'http://hashtagmyevent.com/save',
													{ form: tweet },
													function (error, response, body) {
															if (!error && response.statusCode == 200) {
																	//console.log(body)
																	tweetCounter++;
															}
													}
											);
                    });
					
        
    });
});
});


function extractImg(tweet, callback) {
        
        /* Search media entities */
        if(tweet.entities.media !== undefined && tweet.entities.media.length > 0) {
                for(var m in tweet.entities.media) {
									if(tweet.entities.media[m].media_url) {
                    /* Check for jpg, png, gif */
										ht.m = tweet.entities.media[m];
                    if(tweet.entities.media[m].media_url.indexOf("jpg") !== -1 || tweet.entities.media[m].media_url.indexOf("png") !== -1 || tweet.entities.media[m].media_url.indexOf("gif") !== -1) {  
                            callback(tweet.entities.media[m].media_url);
                        }
                    }
								}

            } else if(tweet.entities.urls !== undefined && tweet.entities.urls.length > 0) {

                for(var u in tweet.entities.urls) {
										if(tweet.entities.urls[u].expanded_url !== undefined) {
                    /* Check for jpg, png, gif */
                    if(tweet.entities.urls[u].expanded_url.indexOf("jpg") !== -1 || tweet.entities.urls[u].expanded_url.indexOf("png") !== -1 || tweet.entities.urls[u].expanded_url.indexOf("gif") !== -1) {
                            callback(tweet.entities.urls[u].expanded_url);
                        }
                    /* Check if instagram */
                    if(tweet.entities.urls[u].expanded_url.indexOf("instagram") !== -1) {
                            this.getInstagram(tweet.entities.urls[u].expanded_url, function(ourl){
                                callback(ourl);
                            });
                        }	
										}
                    }
             } else {
                 callback(-1);
             }
    }