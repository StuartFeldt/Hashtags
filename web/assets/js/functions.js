String.prototype.parseUsername = function() {
			return this.replace(/[@]+[A-Za-z0-9-_]+/g, function(u) {
				var username = u.replace("@","")
				return u.link("http://twitter.com/"+username);
			});
		};
		
		String.prototype.parseURL = function() {
			return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/g, function(url) {
				return url.link(url);
			});
		};
		String.prototype.parseHashtagaslink = function() {
			return this.replace(/[#]+[A-Za-z0-9-_]+/g, function(t) {
				var tag = t.replace("#","%23")
				t.link("#");
				var test = "<a href='#' class='searchhash'>"+t+"</a>";
				return test;
			});
		};
		String.prototype.striphash = function() {
			return this.replace(/[#]/g, function(t) {
				var tag = t.replace("#","")
				return tag;
			});
		};

function getJSON(URL,success){
    var ud = 'json'+(Math.random()*100).toString().replace(/\./g,'');
    window[ud]= function(o){
        success&&success(o);
    };
    document.getElementsByTagName('body')[0].appendChild((function(){
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.src = URL.replace('callback=?','callback='+ud);
        return s;
    })());
}

function parseTwitterDate($stamp)
{		
// convert to local string and remove seconds and year //		
	var date = new Date(Date.parse($stamp)).toLocaleString();
// return the formatted string //
	//return date.substr(0, 11)+ hour + date.substr(13);
        return date.toString();
}

function prettyDate(time){
    var date = new Date((time || "").replace(/-/g,"/").replace(/[TZ]/g," ")),
        diff = (((new Date()).getTime() - date.getTime()) / 1000),
        day_diff = Math.floor(diff / 86400);
            
    if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
        return;
            
    return day_diff == 0 && (
            diff < 60 && "just now" ||
            diff < 120 && "1 minute ago" ||
            diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
            diff < 7200 && "1 hour ago" ||
            diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
        day_diff == 1 && "Yesterday" ||
        day_diff < 7 && day_diff + " days ago" ||
        day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";
}