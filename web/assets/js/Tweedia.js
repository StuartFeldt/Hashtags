(function() {

var Tweedia = new Object();
window.Tweedia = Tweedia = {
    
    extractImages : function(tweet, callback) {
        
        /* Search media entities */
        if(tweet.entities.media !== undefined && tweet.entities.media.length > 0) {
                for(var m in tweet.entities.media) {
                    /* Check for jpg, png, gif */
										console.log(tweet.entities.media[m]);
                    if(tweet.entities.media[m].media_url.indexOf("jpg") !== -1 || tweet.entities.media[m].media_url.indexOf("png") !== -1 || tweet.entities.media[m].media_url.indexOf("gif") !== -1) {  
                            callback(tweet.entities.media[m].media_url);
                        }
                    }

            } else if(tweet.entities.urls !== undefined && tweet.entities.urls.length > 0) {
                for(var u in tweet.entities.urls) {
										console.log(tweet.entities.urls[u]);
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
             } else {
                 callback(-1);
             }
    },
    
    extractImagesHTML : function(tweet, options, callback) {
        /* extract images */
        this.extractImages(tweet, function(url) {

            var img = document.createElement("img");
            img.setAttribute('src', url);

            if(options.width !== undefined) {
                img.setAttribute('width', options.width);
            }

            if(options.height !== undefined) {
                img.setAttribute('height', options.height);
            }

            if(options.class !== undefined) {
                img.className += " " + options.class;
            }

            callback(img);          
        });
    },

    extractVideoHTML : function(tweet, options, callback) {

            /* Search url entities */  
            if(tweet.entities.urls !== undefined && tweet.entities.urls.length > 0) {
                for(var u in tweet.entities.urls) {

                    /* Check for youtube */
                    if(tweet.entities.urls[u].expanded_url.indexOf("youtu") !== -1) {

                            var parts = tweet.entities.urls[u].expanded_url.split("/");
                            parts = parts[parts.length - 1].split("?v=");

                            var yt = document.createElement("iframe");
                            yt.setAttribute("src", "http://www.youtube.com/embed/" + parts[parts.length - 1] + "enablejsapi=1&playerapiid=ytplayer&version=3&cc_load_policy=0&iv_load_policy=3&modestbranding=1");
                            yt.setAttribute("frameborder", 0);

                            if(options.width !== undefined) {
                                yt.setAttribute('width', options.width);
                            }

                            if(options.height !== undefined) {
                                yt.setAttribute('height', options.height);
                            }

                            if(options.class !== undefined) {
                                yt.className += " " + options.class;
                            }

                            callback(yt);
                        }
                    /* check for vine */
                    if(tweet.entities.urls[u].expanded_url.indexOf("vine.co") !== -1) {

                            var parts = tweet.entities.urls[u].expanded_url.split("/");

                            var vineDiv = document.createElement("div");
                            var vine = document.createElement("iframe");
                            vine.setAttribute("src", "https://vine.co/v/" + parts[parts.length - 1] + "/card");

                            if(options.width !== undefined) {
                                vine.setAttribute('width', options.width);
                                vineDiv.setAttribute('width', options.width);
                            }

                            if(options.height !== undefined) {
                                vine.setAttribute('height', options.height);
                                vineDiv.setAttribute('height', options.height);
                            }
                            
                            if(options.class !== undefined) {
                                vineDiv.className += " " + options.class;
                            }
                            
                            vineDiv.appendChild(vine);
                            vineDiv.innerHTML += '<script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>';

                        callback(vineDiv);
                    }
                }
             }
    },

    getInstagram : function(link, callback) {   
        $.ajax({
            url:"http://api.instagram.com/oembed?url="+link,
            dataType: 'jsonp',
            success:function(data){
                callback(data.url);
            }
        });
    }
};
}).call(this);