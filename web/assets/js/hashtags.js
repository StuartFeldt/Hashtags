/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

(function() {
    var ht = new Object();
    window.ht = ht = {
        
        pollint:0,
        showInt:0,
        
        //initial options
        options: {
            site: 0,
            hashtag: "",
            pullInt: 10000,
            showInt: 6000,
            env: "/app_dev.php/",
            timeline: false
        },
        
        //init function
        init: function(site, hashtag, pullInt, showInt) {
            ht.options.site = site;
            ht.options.hashtag = hashtag;
            ht.options.pullInt = pullInt;
            ht.options.showInt = showInt;
            ht.options.ij = Instajam.init({
                clientId: 'aea6ae8f16e741dbb264f86de4829688',
                redirectUri: 'http://hashdev.stuartfeldt.com'
              });
        },
        
        //start
        start: function() {
            console.log("----Ht Starting----");
            $('#ht-control-play').addClass("active");
            $('#ht-control-pause').removeClass("active");
            ht.pullTweets();
            pollInt = setInterval("ht.getTweets()", ht.options.pullInt);
            instaInt = setInterval("ht.getInsta()", 60000);
            showInt = setInterval("ht.showTweets()", ht.options.showInt);
        },
        
        //pause
        pause: function() {
            console.log("----HT Paused----");
            $('#ht-control-pause').addClass("active");
            $('#ht-control-play').removeClass("active");
            clearInterval(pollInt);
            clearInterval(showInt);
            clearInterval(instaInt);
        },
                
        reset: function() {
            clearInterval(pollInt);
            clearInterval(showInt);
            clearInterval(instaInt);
            $('#tweets').empty();
            ht.tweets.numTweets = 0;
            ht.tweets.currentTweet = 0;
            ht.tweets.splicedTweets = 0;
            ht.tweets.tweets = [];
        },
                
        timeline: function() {
            ht.reset();
            ht.options.timeline = true;
            ht.start();
            $('#ht-control-timeline').addClass("active");
            $('#ht-control-random').removeClass("active");
        },
                
        shuffle: function() {
            ht.reset();
            ht.options.timeline = false;
            ht.start();
            $('#ht-control-random').addClass("active");
            $('#ht-control-timeline').removeClass("active");
        },
        
        getTweets: function() {
            console.log("getting tweets from Twitter...");
            $.getJSON(ht.options.env + 'poll/' + ht.options.site, function(data){
		numTweets = data.reply.statuses.length;
		statuses = data.reply.statuses;
                
                $.each(statuses, function(i,tweet){
                    var tweet_body =  tweet.text;
                    var tweet_author = tweet.user.screen_name;
                    var tweet_author_pic = tweet.user.profile_image_url;
                    var tweet_id = tweet.id;
                    var tweet_pic = "";
                    var tweet_time = parseTwitterDate(tweet.created_at);
                    Tweedia.extractImages(tweet, function(url){
                        if(url != -1) {
                            tweet_pic = url;
                        }
                        ht.saveTweet(tweet_body, tweet_author, tweet_author_pic, tweet_pic, tweet_id, tweet_time, 'twitter');
                    });
                });
            });
        },
        
        getInsta: function() {
            $.getJSON("https://api.instagram.com/v1/tags/"+ht.options.hashtag+"/media/recent?client_id=bc7bb286c6834142af582ef9a6029279&callback=?", function(data){
                console.log("Getting Instagram Posts...");
                numTweets = data.data.length;
		statuses = data.data;
                
                $.each(statuses, function(i,tweet){
                    var tweet_body =  tweet.caption.text;
                    var tweet_author = tweet.user.username;
                    var tweet_author_pic = tweet.user.profile_picture;
                    var tweet_id = tweet.id;
                    var tweet_pic = tweet.images.low_resolution.url;
                    var tweet_time = tweet.created_time;
                    ht.saveTweet(tweet_body, tweet_author, tweet_author_pic, tweet_pic, tweet_id, tweet_time, 'instagram');
                    });
                });
        },
        
        saveTweet: function(text, screen_name, profile_image_url, tweet_pic, tweet_id, tweet_time, tweet_type) {
            $.post(ht.options.env + "save", {
                siteId: ht.options.site,
                body: text,
                screen_name: screen_name,
                profile_image: profile_image_url,
                tweet_pic: tweet_pic,
                tweet_id: tweet_id,
                tweet_time: tweet_time,
                tweet_type: tweet_type
            }, function(data){
                    if(data.exists === 0) {
                        //console.log("--Got new tweet: " + text);
                        var splice_spot = ht.tweets.splicedTweets + ht.tweets.currentTweet;
                        ht.tweets.tweets.splice(splice_spot, 0, {tweetBody: text, tweetAuthor: screen_name, tweetAuthorPic: profile_image_url, tweetPic: tweet_pic, tweetType: tweet_type, tweetTime: "Just now", spliced: true });
                        //console.log(" -- -- spliced in to stack at " + splice_spot); 
                        ht.tweets.splicedTweets++; 
                        ht.tweets.numTweets++; 
                    }
                });
        },
        
        pullTweets: function() {
            console.log("getting tweets from HQ and resetting tweets...");
            var url = ht.options.timeline ? 'getTweetsTimeline/' : 'getTweets/';
            $.getJSON(ht.options.env + url + ht.options.site, function(data){
                ht.tweets.numTweets = data.length;
                ht.tweets.currentTweet = 0;
                ht.tweets.splicedTweets = 0;
                ht.tweets.tweets = data;
                ht.showTweets();
                console.log(" - There are "+ht.tweets.numTweets+" tweets currently in hq");
            });
        },
                
        showTweets: function() {
            if(ht.tweets.currentTweet >= ht.tweets.numTweets)
            {
             ht.pullTweets();
            }
            else
            {
                var tweet_ts;
                if(ht.tweets.tweets[ht.tweets.currentTweet].spliced) {
                    tweet_ts = ht.tweets.tweets[ht.tweets.currentTweet].tweetTime;
                } else {
                    tweet_ts = moment(ht.tweets.tweets[ht.tweets.currentTweet].tweetTime, "MM/DD/YYYY HH:mm:ss a").fromNow();
                }  
                var tweetType = ht.tweets.tweets[ht.tweets.currentTweet].tweetType;
                
                if(ht.tweets.tweets[ht.tweets.currentTweet].tweetPic != "") {
                    $("<div class='tweet well "+tweetType+"' id='tweet"+ht.tweets.currentTweet+"'><div class='user_col'><div class='tweet_author_pic'><img src='"+ht.tweets.tweets[ht.tweets.currentTweet].tweetAuthorPic+"' /></div><div class='tweet_author'>"+"<a href='//twitter.com/'"+ht.tweets.tweets[ht.tweets.currentTweet].tweetAuthor+"'>@"+ht.tweets.tweets[ht.tweets.currentTweet].tweetAuthor+"</a></div></div><div class='body_col'><div class='tweet_body'>"+ht.tweets.tweets[ht.tweets.currentTweet].tweetBody.parseURL().parseUsername().parseHashtagaslink()+"</div><div class='tweet_time'>"+tweet_ts+"</div></div><div style='clear:both;'></div><div class='tweet_pic'><img src='"+ht.tweets.tweets[ht.tweets.currentTweet].tweetPic+"' /></div><div class='tweet_pic2'></div></div>").prependTo('#tweets').hide().slideDown();
                    
                } else {
                    $("<div class='tweet well "+tweetType+"' id='tweet"+ht.tweets.currentTweet+"'><div class='user_col'><div class='tweet_author_pic'><img src='"+ht.tweets.tweets[ht.tweets.currentTweet].tweetAuthorPic+"' /></div><div class='tweet_author'>"+"<a href='//twitter.com/'"+ht.tweets.tweets[ht.tweets.currentTweet].tweetAuthor+"'>@"+ht.tweets.tweets[ht.tweets.currentTweet].tweetAuthor+"</a></div></div><div class='body_col'><div class='tweet_body'>"+ht.tweets.tweets[ht.tweets.currentTweet].tweetBody.parseURL().parseUsername().parseHashtagaslink()+"</div><div class='tweet_time'>"+tweet_ts+"</div></div><div style='clear:both;'></div></div>").prependTo('#tweets').hide().slideDown();
                }
                ht.tweets.currentTweet++;
             }

            $('#tweets').fadeIn(2000);
            $('#tweets .tweet:nth-child(4)').fadeOut();
            $('#tweets .tweet:nth-child(5)').fadeOut();
            $('#tweets .tweet:nth-child(6)').fadeOut();
            $('#tweets .tweet:nth-child(7)').fadeOut();
            $('#tweets .tweet:nth-child(8)').fadeOut();
            $('#tweets .tweet:nth-child(9)').fadeOut();

        },
        
        tweets: {
            numTweets: 0,
            currentTweet: 0,
            splicedTweets: 0,
            tweets: []
        }
    };
}).call(this);