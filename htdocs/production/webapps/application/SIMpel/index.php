<!DOCTYPE HTML>
<html manifest="cache.appcache">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>SIMRS GOS 2</title>

    
    <script type="text/javascript">
        var Ext = Ext || {}; // Ext namespace won't be defined yet...

        // This function is called by the Microloader after it has performed basic
        // device detection. The results are provided in the "tags" object. You can
        // use these tags here or even add custom tags. These can be used by platform
        // filters in your manifest or by platformConfig expressions in your app.
        //
        Ext.beforeLoad = function (tags) {
            var s = location.search,  // the query string (ex "?foo=1&bar")
                profile;

            // For testing look for "?classic" or "?modern" in the URL to override
            // device detection default.
            //
            if (s.match(/\bclassic\b/)) {
                profile = 'classic';
            }
            else if (s.match(/\bmodern\b/)) {
                profile = 'modern';
            }
            else {
                profile = tags.desktop ? 'classic' : 'modern';
                //profile = tags.phone ? 'modern' : 'classic';
            }
			
			profile = "classic";
			window.profile = profile;
            Ext.manifest = profile; // this name must match a build profile name			
						
            // This function is called once the manifest is available but before
            // any data is pulled from it.
            //
            //return function (manifest) {
                // peek at / modify the manifest object
            //};
			var _shortcutIcon = document.createElement('link');
			_shortcutIcon.setAttribute("href", window.profile + "/resources/images/app.png");
			_shortcutIcon.setAttribute("rel", "shortcut icon");
			_shortcutIcon.setAttribute("type", "image/png");
			document.head.appendChild(_shortcutIcon);
        };
    </script>
    
    
    <!-- The line below must be kept intact for Sencha Cmd to build your application -->
    <script id="microloader" data-app="daec57bf-5ffe-4dd4-aed9-3de918f91e4f" type="text/javascript">var Ext=Ext||{};Ext.manifest=Ext.manifest||"classic.json";Ext=Ext||{};
Ext.Boot=Ext.Boot||function(f){function l(b){if(b.$isRequest)return b;b=b.url?b:{url:b};var e=b.url,e=e.charAt?[e]:e,a=b.charset||d.config.charset;x(this,b);delete this.url;this.urls=e;this.charset=a}function r(b){if(b.$isEntry)return b;var e=b.charset||d.config.charset,a=Ext.manifest,a=a&&a.loader,k=void 0!==b.cache?b.cache:a&&a.cache,c;d.config.disableCaching&&(void 0===k&&(k=!d.config.disableCaching),!1===k?c=+new Date:!0!==k&&(c=k),c&&(a=a&&a.cacheParam||d.config.disableCachingParam,c=a+"\x3d"+
c));x(this,b);this.charset=e;this.buster=c;this.requests=[]}var n=document,q=[],t={disableCaching:/[?&](?:cache|disableCacheBuster)\b/i.test(location.search)||!/http[s]?\:/i.test(location.href)||/(^|[ ;])ext-cache=1/.test(n.cookie)?!1:!0,disableCachingParam:"_dc",loadDelay:!1,preserveScripts:!0,charset:"UTF-8"},u=/\.css(?:\?|$)/i,w=n.createElement("a"),y="undefined"!==typeof window,v={browser:y,node:!y&&"function"===typeof require,phantom:window&&(window._phantom||window.callPhantom)||/PhantomJS/.test(window.navigator.userAgent)},
g=Ext.platformTags={},x=function(b,e,a){a&&x(b,a);if(b&&e&&"object"===typeof e)for(var d in e)b[d]=e[d];return b},c=function(){var b=!1,e=Array.prototype.shift.call(arguments),a,d,c,m;"boolean"===typeof arguments[arguments.length-1]&&(b=Array.prototype.pop.call(arguments));c=arguments.length;for(a=0;a<c;a++)if(m=arguments[a],"object"===typeof m)for(d in m)e[b?d.toLowerCase():d]=m[d];return e},a="function"==typeof Object.keys?function(b){return b?Object.keys(b):[]}:function(b){var e=[],a;for(a in b)b.hasOwnProperty(a)&&
e.push(a);return e},d={loading:0,loaded:0,apply:x,env:v,config:t,assetConfig:{},scripts:{},currentFile:null,suspendedQueue:[],currentRequest:null,syncMode:!1,useElements:!0,listeners:[],Request:l,Entry:r,allowMultipleBrowsers:!1,browserNames:{ie:"IE",firefox:"Firefox",safari:"Safari",chrome:"Chrome",opera:"Opera",dolfin:"Dolfin",edge:"Edge",webosbrowser:"webOSBrowser",chromeMobile:"ChromeMobile",chromeiOS:"ChromeiOS",silk:"Silk",other:"Other"},osNames:{ios:"iOS",android:"Android",windowsPhone:"WindowsPhone",
webos:"webOS",blackberry:"BlackBerry",rimTablet:"RIMTablet",mac:"MacOS",win:"Windows",tizen:"Tizen",linux:"Linux",bada:"Bada",chromeOS:"ChromeOS",other:"Other"},browserPrefixes:{ie:"MSIE ",edge:"Edge/",firefox:"Firefox/",chrome:"Chrome/",safari:"Version/",opera:"OPR/",dolfin:"Dolfin/",webosbrowser:"wOSBrowser/",chromeMobile:"CrMo/",chromeiOS:"CriOS/",silk:"Silk/"},browserPriority:"edge opera dolfin webosbrowser silk chromeiOS chromeMobile ie firefox safari chrome".split(" "),osPrefixes:{tizen:"(Tizen )",
ios:"i(?:Pad|Phone|Pod)(?:.*)CPU(?: iPhone)? OS ",android:"(Android |HTC_|Silk/)",windowsPhone:"Windows Phone ",blackberry:"(?:BlackBerry|BB)(?:.*)Version/",rimTablet:"RIM Tablet OS ",webos:"(?:webOS|hpwOS)/",bada:"Bada/",chromeOS:"CrOS "},fallbackOSPrefixes:{windows:"win",mac:"mac",linux:"linux"},devicePrefixes:{iPhone:"iPhone",iPod:"iPod",iPad:"iPad"},maxIEVersion:12,detectPlatformTags:function(){var b=this,e=navigator.userAgent,p=/Mobile(\/|\s)/.test(e),k=document.createElement("div"),h=function(){var a=
{},d,p,c,k,h;p=b.browserPriority.length;for(k=0;k<p;k++)d=b.browserPriority[k],h?c=0:(c=b.browserPrefixes[d],(c=(c=e.match(new RegExp("("+c+")([\\w\\._]+)")))&&1<c.length?parseInt(c[2]):0)&&(h=!0)),a[d]=c;a.ie&&(k=document.documentMode,8<=k&&(a.ie=k));c=a.ie||!1;d=Math.max(c,b.maxIEVersion);for(k=8;k<=d;++k)p="ie"+k,a[p+"m"]=c?c<=k:0,a[p]=c?c===k:0,a[p+"p"]=c?c>=k:0;return a}(),m=function(){var d={},p,c,k,h,m,f,z;k=a(b.osPrefixes);m=k.length;for(z=h=0;h<m;h++)c=k[h],p=b.osPrefixes[c],f=(p=e.match(new RegExp("("+
p+")([^\\s;]+)")))?p[1]:null,(p=!f||"HTC_"!==f&&"Silk/"!==f?p&&1<p.length?parseFloat(p[p.length-1]):0:2.3)&&z++,d[c]=p;k=a(b.fallbackOSPrefixes);m=k.length;for(h=0;h<m;h++)c=k[h],0===z?(p=b.fallbackOSPrefixes[c],p=e.toLowerCase().match(new RegExp(p)),d[c]=p?!0:0):d[c]=0;return d}(),f=function(){var d={},p,c,k,h,m;k=a(b.devicePrefixes);m=k.length;for(h=0;h<m;h++)c=k[h],p=b.devicePrefixes[c],p=e.match(new RegExp(p)),d[c]=p?!0:0;return d}(),l=d.loadPlatformsParam();c(g,h,m,f,l,!0);g.phone=!!(g.iphone||
g.ipod||!g.silk&&g.android&&(3>g.android||p)||g.blackberry&&p||g.windowsphone);g.tablet=!(g.phone||!(g.ipad||g.android||g.silk||g.rimtablet||g.ie10&&/; Touch/.test(e)));g.touch=function(b,e){b="on"+b.toLowerCase();e=b in k;!e&&k.setAttribute&&k.removeAttribute&&(k.setAttribute(b,""),e="function"===typeof k[b],"undefined"!==typeof k[b]&&(k[b]=void 0),k.removeAttribute(b));return e}("touchend")||navigator.maxTouchPoints||navigator.msMaxTouchPoints;g.desktop=!g.phone&&!g.tablet;g.cordova=g.phonegap=
!!(window.PhoneGap||window.Cordova||window.cordova);g.webview=/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)(?!.*FBAN)/i.test(e);g.androidstock=4.3>=g.android&&(g.safari||g.silk);c(g,l,!0)},loadPlatformsParam:function(){var b=window.location.search.substr(1).split("\x26"),e={},a,d={},c,m,f;for(a=0;a<b.length;a++)c=b[a].split("\x3d"),e[c[0]]=c[1];if(e.platformTags)for(c=e.platformTags.split(","),b=c.length,a=0;a<b;a++)e=c[a].split(":"),m=e[0],f=!0,1<e.length&&(f=e[1],"false"===f||"0"===f)&&(f=!1),d[m]=
f;return d},filterPlatform:function(b,e){b=q.concat(b||q);e=q.concat(e||q);var a=b.length,d=e.length,c=!a&&d,m;for(m=0;m<a&&!c;m++)c=b[m],c=!!g[c];for(m=0;m<d&&c;m++)c=e[m],c=!g[c];return c},init:function(){var b=n.getElementsByTagName("script"),e=b[0],a=b.length,c=/\/ext(\-[a-z\-]+)?\.js$/,h,m,f,l,g;d.hasReadyState="readyState"in e;d.hasAsync="async"in e;d.hasDefer="defer"in e;d.hasOnLoad="onload"in e;d.isIE8=d.hasReadyState&&!d.hasAsync&&d.hasDefer&&!d.hasOnLoad;d.isIE9=d.hasReadyState&&!d.hasAsync&&
d.hasDefer&&d.hasOnLoad;d.isIE10p=d.hasReadyState&&d.hasAsync&&d.hasDefer&&d.hasOnLoad;d.isIE10=10===(new Function("/*@cc_on return @_jscript_version @*/"))();d.isIE10m=d.isIE10||d.isIE9||d.isIE8;d.isIE11=d.isIE10p&&!d.isIE10;for(g=0;g<a;g++)if(h=(e=b[g]).src)m=e.readyState||null,!f&&c.test(h)&&(f=h),d.scripts[l=d.canonicalUrl(h)]||new r({key:l,url:h,done:null===m||"loaded"===m||"complete"===m,el:e,prop:"src"});f||(e=b[b.length-1],f=e.src);d.baseUrl=f.substring(0,f.lastIndexOf("/")+1);d.origin=window.location.origin||
window.location.protocol+"//"+window.location.hostname+(window.location.port?":"+window.location.port:"");d.detectPlatformTags();Ext.filterPlatform=d.filterPlatform},canonicalUrl:function(b){w.href=b;b=w.href;var e=t.disableCachingParam,e=e?b.indexOf(e+"\x3d"):-1,a,d;0<e&&("?"===(a=b.charAt(e-1))||"\x26"===a)&&(d=b.indexOf("\x26",e),(d=0>d?"":b.substring(d))&&"?"===a&&(++e,d=d.substring(1)),b=b.substring(0,e-1)+d);return b},getConfig:function(b){return b?d.config[b]:d.config},setConfig:function(b,
e){if("string"===typeof b)d.config[b]=e;else for(var a in b)d.setConfig(a,b[a]);return d},getHead:function(){return d.docHead||(d.docHead=n.head||n.getElementsByTagName("head")[0])},create:function(b,e,a){a=a||{};a.url=b;a.key=e;return d.scripts[e]=new r(a)},getEntry:function(b,e,a){var c,h;c=a?b:d.canonicalUrl(b);h=d.scripts[c];h||(h=d.create(b,c,e),a&&(h.canonicalPath=!0));return h},registerContent:function(b,e,a){return d.getEntry(b,{content:a,loaded:!0,css:"css"===e})},processRequest:function(b,
a){b.loadEntries(a)},load:function(b){b=new l(b);if(b.sync||d.syncMode)return d.loadSync(b);d.currentRequest?(b.getEntries(),d.suspendedQueue.push(b)):(d.currentRequest=b,d.processRequest(b,!1));return d},loadSync:function(b){b=new l(b);d.syncMode++;d.processRequest(b,!0);d.syncMode--;return d},loadBasePrefix:function(b){b=new l(b);b.prependBaseUrl=!0;return d.load(b)},loadSyncBasePrefix:function(b){b=new l(b);b.prependBaseUrl=!0;return d.loadSync(b)},requestComplete:function(b){if(d.currentRequest===
b)for(d.currentRequest=null;0<d.suspendedQueue.length;)if(b=d.suspendedQueue.shift(),!b.done){d.load(b);break}d.currentRequest||0!=d.suspendedQueue.length||d.fireListeners()},isLoading:function(){return!d.currentRequest&&0==d.suspendedQueue.length},fireListeners:function(){for(var b;d.isLoading()&&(b=d.listeners.shift());)b()},onBootReady:function(b){d.isLoading()?d.listeners.push(b):b()},getPathsFromIndexes:function(b,a){if(!("length"in b)){var d=[],c;for(c in b)isNaN(+c)||(d[+c]=b[c]);b=d}return l.prototype.getPathsFromIndexes(b,
a)},createLoadOrderMap:function(b){return l.prototype.createLoadOrderMap(b)},fetch:function(b,a,d,c){c=void 0===c?!!a:c;var h=new XMLHttpRequest,m,g,l,n=!1,r=function(){h&&4==h.readyState&&(g=1223===h.status?204:0!==h.status||"file:"!==(self.location||{}).protocol&&"ionp:"!==(self.location||{}).protocol?h.status:200,l=h.responseText,m={content:l,status:g,exception:n},a&&a.call(d,m),h.onreadystatechange=f,h=null)};c&&(h.onreadystatechange=r);try{h.open("GET",b,c),h.send(null)}catch(q){return n=q,r(),
m}c||r();return m},notifyAll:function(b){b.notifyRequests()}};l.prototype={$isRequest:!0,createLoadOrderMap:function(b){var a=b.length,d={},c,h;for(c=0;c<a;c++)h=b[c],d[h.path]=h;return d},getLoadIndexes:function(b,a,c,k,h){var m=[],f=[b];b=b.idx;var g,l,n;if(a[b])return m;for(a[b]=m[b]=!0;b=f.shift();)if(g=b.canonicalPath?d.getEntry(b.path,null,!0):d.getEntry(this.prepareUrl(b.path)),!h||!g.done)for(b=k&&b.uses&&b.uses.length?b.requires.concat(b.uses):b.requires,l=0,n=b.length;l<n;l++)g=b[l],a[g]||
(a[g]=m[g]=!0,f.push(c[g]));return m},getPathsFromIndexes:function(b,a){var d=[],c,h;c=0;for(h=b.length;c<h;c++)b[c]&&d.push(a[c].path);return d},expandUrl:function(b,a,d,c,h,f){return a&&(d=d[b])&&(c=this.getLoadIndexes(d,c,a,h,f),c.length)?this.getPathsFromIndexes(c,a):[b]},expandUrls:function(b,a){var d=this.loadOrder,c=[],h={},f=[],g,l,n,r,q,u,t;"string"===typeof b&&(b=[b]);d&&(g=this.loadOrderMap,g||(g=this.loadOrderMap=this.createLoadOrderMap(d)));n=0;for(r=b.length;n<r;n++)for(l=this.expandUrl(b[n],
d,g,f,a,!1),q=0,u=l.length;q<u;q++)t=l[q],h[t]||(h[t]=!0,c.push(t));0===c.length&&(c=b);return c},expandLoadOrder:function(){var b=this.urls,a;this.expanded?a=b:(a=this.expandUrls(b,!0),this.expanded=!0);this.urls=a;b.length!=a.length&&(this.sequential=!0);return this},getUrls:function(){this.expandLoadOrder();return this.urls},prepareUrl:function(b){return this.prependBaseUrl?d.baseUrl+b:b},getEntries:function(){var b=this.entries,a,c,k,h,f;if(!b){b=[];f=this.getUrls();this.loadOrder&&(a=this.loadOrderMap);
for(k=0;k<f.length;k++)h=this.prepareUrl(f[k]),a&&(c=a[h]),h=d.getEntry(h,{buster:this.buster,charset:this.charset},c&&c.canonicalPath),h.requests.push(this),b.push(h);this.entries=b}return b},loadEntries:function(b){var a=this,d=a.getEntries(),c=d.length,h=a.loadStart||0,f,g;void 0!==b&&(a.sync=b);a.loaded=a.loaded||0;a.loading=a.loading||c;for(g=h;g<c;g++)if(f=d[g],h=f.loaded?!0:d[g].load(a.sync),!h){a.loadStart=g;f.onDone(function(){a.loadEntries(b)});break}a.processLoadedEntries()},processLoadedEntries:function(){var b=
this.getEntries(),a=b.length,d=this.startIndex||0,c;if(!this.done){for(;d<a;d++){c=b[d];if(!c.loaded){this.startIndex=d;return}c.evaluated||c.evaluate();c.error&&(this.error=!0)}this.notify()}},notify:function(){var b=this;if(!b.done){var a=b.error,c=b[a?"failure":"success"],a="delay"in b?b.delay:a?1:d.config.chainDelay,f=b.scope||b;b.done=!0;c&&(0===a||0<a?setTimeout(function(){c.call(f,b)},a):c.call(f,b));b.fireListeners();d.requestComplete(b)}},onDone:function(b){var a=this.listeners||(this.listeners=
[]);this.done?b(this):a.push(b)},fireListeners:function(){var b=this.listeners,a;if(b)for(;a=b.shift();)a(this)}};r.prototype={$isEntry:!0,done:!1,evaluated:!1,loaded:!1,isCrossDomain:function(){void 0===this.crossDomain&&(this.crossDomain=0!==this.getLoadUrl().indexOf(d.origin));return this.crossDomain},isCss:function(){if(void 0===this.css)if(this.url){var b=d.assetConfig[this.url];this.css=b?"css"===b.type:u.test(this.url)}else this.css=!1;return this.css},getElement:function(b){var a=this.el;
a||(this.isCss()?(b=b||"link",a=n.createElement(b),"link"==b?(a.rel="stylesheet",this.prop="href"):this.prop="textContent",a.type="text/css"):(a=n.createElement(b||"script"),a.type="text/javascript",this.prop="src",this.charset&&(a.charset=this.charset),d.hasAsync&&(a.async=!1)),this.el=a);return a},getLoadUrl:function(){var b;b=this.canonicalPath?this.url:d.canonicalUrl(this.url);this.loadUrl||(this.loadUrl=this.buster?b+(-1===b.indexOf("?")?"?":"\x26")+this.buster:b);return this.loadUrl},fetch:function(b){var a=
this.getLoadUrl();d.fetch(a,b.complete,this,!!b.async)},onContentLoaded:function(b){var a=b.status,d=b.content;b=b.exception;this.getLoadUrl();this.loaded=!0;!b&&0!==a||v.phantom?200<=a&&300>a||304===a||v.phantom||0===a&&0<d.length?this.content=d:this.evaluated=this.error=!0:this.evaluated=this.error=!0},createLoadElement:function(b){var a=this,c=a.getElement();a.preserve=!0;c.onerror=function(){a.error=!0;b&&(b(),b=null)};d.isIE10m?c.onreadystatechange=function(){"loaded"!==this.readyState&&"complete"!==
this.readyState||!b||(b(),b=this.onreadystatechange=this.onerror=null)}:c.onload=function(){b();b=this.onload=this.onerror=null};c[a.prop]=a.getLoadUrl()},onLoadElementReady:function(){d.getHead().appendChild(this.getElement());this.evaluated=!0},inject:function(b,a){a=d.getHead();var c=this.url,f=this.key,h,g;this.isCss()?(this.preserve=!0,g=f.substring(0,f.lastIndexOf("/")+1),h=n.createElement("base"),h.href=g,a.firstChild?a.insertBefore(h,a.firstChild):a.appendChild(h),h.href=h.href,c&&(b+="\n/*# sourceURL\x3d"+
f+" */"),c=this.getElement("style"),f="styleSheet"in c,a.appendChild(h),f?(a.appendChild(c),c.styleSheet.cssText=b):(c.textContent=b,a.appendChild(c)),a.removeChild(h)):(c&&(b+="\n//# sourceURL\x3d"+f),Ext.globalEval(b));return this},loadCrossDomain:function(){var b=this;b.createLoadElement(function(){b.el.onerror=b.el.onload=f;b.el=null;b.loaded=b.evaluated=b.done=!0;b.notifyRequests()});b.evaluateLoadElement();return!1},loadElement:function(){var b=this;b.createLoadElement(function(){b.el.onerror=
b.el.onload=f;b.el=null;b.loaded=b.evaluated=b.done=!0;b.notifyRequests()});b.evaluateLoadElement();return!0},loadSync:function(){var b=this;b.fetch({async:!1,complete:function(a){b.onContentLoaded(a)}});b.evaluate();b.notifyRequests()},load:function(b){var a=this;if(!a.loaded){if(a.loading)return!1;a.loading=!0;if(b)a.loadSync();else{if(d.isIE10||a.isCrossDomain())return a.loadCrossDomain();if(!a.isCss()&&d.hasReadyState)a.createLoadElement(function(){a.loaded=!0;a.notifyRequests()});else if(!d.useElements||
a.isCss()&&v.phantom)a.fetch({async:!b,complete:function(b){a.onContentLoaded(b);a.notifyRequests()}});else return a.loadElement()}}return!0},evaluateContent:function(){this.inject(this.content);this.content=null},evaluateLoadElement:function(){d.getHead().appendChild(this.getElement())},evaluate:function(){this.evaluated||this.evaluating||(this.evaluating=!0,void 0!==this.content?this.evaluateContent():this.error||this.evaluateLoadElement(),this.evaluated=this.done=!0,this.cleanup())},cleanup:function(){var a=
this.el,c;if(a){if(!this.preserve)for(c in this.el=null,a.parentNode.removeChild(a),a)try{c!==this.prop&&(a[c]=null),delete a[c]}catch(d){}a.onload=a.onerror=a.onreadystatechange=f}},notifyRequests:function(){var a=this.requests,c=a.length,d,f;for(d=0;d<c;d++)f=a[d],f.processLoadedEntries();this.done&&this.fireListeners()},onDone:function(a){var c=this.listeners||(this.listeners=[]);this.done?a(this):c.push(a)},fireListeners:function(){var a=this.listeners,c;if(a&&0<a.length)for(;c=a.shift();)c(this)}};
Ext.disableCacheBuster=function(a,c){var d=new Date;d.setTime(d.getTime()+864E5*(a?3650:-1));d=d.toGMTString();n.cookie="ext-cache\x3d1; expires\x3d"+d+"; path\x3d"+(c||"/")};d.init();return d}(function(){});Ext.globalEval=Ext.globalEval||(this.execScript?function(f){execScript(f)}:function(f){eval.call(window,f)});
Function.prototype.bind||function(){var f=Array.prototype.slice,l=function(l){var n=f.call(arguments,1),q=this;if(n.length)return function(){var t=arguments;return q.apply(l,t.length?n.concat(f.call(t)):n)};n=null;return function(){return q.apply(l,arguments)}};Function.prototype.bind=l;l.$extjs=!0}();Ext.setResourcePath=function(f,l){var r=Ext.manifest||(Ext.manifest={}),n=r.resources||(r.resources={});r&&("string"!==typeof f?Ext.apply(n,f):n[f]=l,r.resources=n)};
Ext.getResourcePath=function(f,l,r){"string"!==typeof f&&(l=f.pool,r=f.packageName,f=f.path);var n=Ext.manifest,n=n&&n.resources;l=n[l];var q=[];null==l&&(l=n.path,null==l&&(l="resources"));l&&q.push(l);r&&q.push(r);q.push(f);return q.join("/")};Ext=Ext||window.Ext||{};
Ext.Microloader=Ext.Microloader||function(){var f=Ext.Boot,l=function(a){console.log("[WARN] "+a)},r="_ext:"+location.pathname,n=function(a,d){return r+a+"-"+(d?d+"-":"")+c.appId},q,t;try{t=window.localStorage}catch(a){}var u=window.applicationCache,w={clearAllPrivate:function(a){if(t){t.removeItem(a.key);var d,b=[],e=a.profile+"-"+c.appId,f=t.length;for(a=0;a<f;a++)d=t.key(a),0===d.indexOf(r)&&-1!==d.indexOf(e)&&b.push(d);for(a in b)t.removeItem(b[a])}},retrieveAsset:function(a){try{return t.getItem(a)}catch(c){return null}},
setAsset:function(a,c){try{null===c||""==c?t.removeItem(a):t.setItem(a,c)}catch(b){}}},y=function(a){this.assetConfig="string"===typeof a.assetConfig?{path:a.assetConfig}:a.assetConfig;this.type=a.type;this.key=n(this.assetConfig.path,a.manifest.profile);a.loadFromCache&&this.loadFromCache()};y.prototype={shouldCache:function(){return t&&this.assetConfig.update&&this.assetConfig.hash&&!this.assetConfig.remote},is:function(a){return!!a&&this.assetConfig&&a.assetConfig&&this.assetConfig.hash===a.assetConfig.hash},
cache:function(a){this.shouldCache()&&w.setAsset(this.key,a||this.content)},uncache:function(){w.setAsset(this.key,null)},updateContent:function(a){this.content=a},getSize:function(){return this.content?this.content.length:0},loadFromCache:function(){this.shouldCache()&&(this.content=w.retrieveAsset(this.key))}};var v=function(a){this.content="string"===typeof a.content?JSON.parse(a.content):a.content;this.assetMap={};this.url=a.url;this.fromCache=!!a.cached;this.assetCache=!1!==a.assetCache;this.key=
n(this.url);this.profile=this.content.profile;this.hash=this.content.hash;this.loadOrder=this.content.loadOrder;this.deltas=this.content.cache?this.content.cache.deltas:null;this.cacheEnabled=this.content.cache?this.content.cache.enable:!1;this.loadOrderMap=this.loadOrder?f.createLoadOrderMap(this.loadOrder):null;a=this.content.tags;var c=Ext.platformTags;if(a){if(a instanceof Array)for(var b=0;b<a.length;b++)c[a[b]]=!0;else f.apply(c,a);f.apply(c,f.loadPlatformsParam())}this.js=this.processAssets(this.content.js,
"js");this.css=this.processAssets(this.content.css,"css")};v.prototype={processAsset:function(a,c){c=new y({manifest:this,assetConfig:a,type:c,loadFromCache:this.assetCache});return this.assetMap[a.path]=c},processAssets:function(a,c){var b=[],e=a.length,f,g;for(f=0;f<e;f++)g=a[f],b.push(this.processAsset(g,c));return b},useAppCache:function(){return!0},getAssets:function(){return this.css.concat(this.js)},getAsset:function(a){return this.assetMap[a]},shouldCache:function(){return this.hash&&this.cacheEnabled},
cache:function(a){this.shouldCache()&&w.setAsset(this.key,JSON.stringify(a||this.content))},is:function(a){return this.hash===a.hash},uncache:function(){w.setAsset(this.key,null)},exportContent:function(){return f.apply({loadOrderMap:this.loadOrderMap},this.content)}};var g=[],x=!1,c={init:function(){Ext.microloaded=!0;var a=document.getElementById("microloader");c.appId=a?a.getAttribute("data-app"):"";Ext.beforeLoad&&(q=Ext.beforeLoad(Ext.platformTags));var d=Ext._beforereadyhandler;Ext._beforereadyhandler=
function(){Ext.Boot!==f&&(Ext.apply(Ext.Boot,f),Ext.Boot=f);d&&d()}},applyCacheBuster:function(a){var c=(new Date).getTime(),b=-1===a.indexOf("?")?"?":"\x26";return a+b+"_dc\x3d"+c},run:function(){c.init();var a=Ext.manifest;if("string"===typeof a){var a=a.indexOf(".json")===a.length-5?a:a+".json",d=n(a);(d=w.retrieveAsset(d))?(a=new v({url:a,content:d,cached:!0}),q&&q(a),c.load(a)):0===location.href.indexOf("file:/")?(v.url=c.applyCacheBuster(a+"p"),f.load(v.url)):(v.url=a,f.fetch(c.applyCacheBuster(a),
function(a){c.setManifest(a.content)}))}else a=new v({content:a}),c.load(a)},setManifest:function(a){a=new v({url:v.url,content:a});a.cache();q&&q(a);c.load(a)},load:function(a){c.urls=[];c.manifest=a;Ext.manifest=c.manifest.exportContent();var d=a.getAssets(),b=[],e,g,k,h;k=d.length;for(g=0;g<k;g++)if(e=d[g],h=c.filterAsset(e))a.shouldCache()&&e.shouldCache()&&(e.content?(h=f.registerContent(e.assetConfig.path,e.type,e.content),h.evaluated&&l("Asset: "+e.assetConfig.path+" was evaluated prior to local storage being consulted.")):
b.push(e)),c.urls.push(e.assetConfig.path),f.assetConfig[e.assetConfig.path]=f.apply({type:e.type},e.assetConfig);if(0<b.length)for(c.remainingCachedAssets=b.length;0<b.length;)e=b.pop(),f.fetch(e.assetConfig.path,function(a){return function(b){c.onCachedAssetLoaded(a,b)}}(e));else c.onCachedAssetsReady()},onCachedAssetLoaded:function(a,d){var b;d=c.parseResult(d);c.remainingCachedAssets--;d.error?(l("There was an error pre-loading the asset '"+a.assetConfig.path+"'. This asset will be uncached for future loading"),
a.uncache()):(b=c.checksum(d.content,a.assetConfig.hash),b||(l("Cached Asset '"+a.assetConfig.path+"' has failed checksum. This asset will be uncached for future loading"),a.uncache()),f.registerContent(a.assetConfig.path,a.type,d.content),a.updateContent(d.content),a.cache());if(0===c.remainingCachedAssets)c.onCachedAssetsReady()},onCachedAssetsReady:function(){f.load({url:c.urls,loadOrder:c.manifest.loadOrder,loadOrderMap:c.manifest.loadOrderMap,sequential:!0,success:c.onAllAssetsReady,failure:c.onAllAssetsReady})},
onAllAssetsReady:function(){x=!0;c.notify();!1!==navigator.onLine?c.checkAllUpdates():window.addEventListener&&window.addEventListener("online",c.checkAllUpdates,!1)},onMicroloaderReady:function(a){x?a():g.push(a)},notify:function(){for(var a;a=g.shift();)a()},patch:function(a,c){var b=[],e,f,g;if(0===c.length)return a;f=0;for(g=c.length;f<g;f++)e=c[f],"number"===typeof e?b.push(a.substring(e,e+c[++f])):b.push(e);return b.join("")},checkAllUpdates:function(){window.removeEventListener&&window.removeEventListener("online",
c.checkAllUpdates,!1);u&&c.checkForAppCacheUpdate();c.manifest.fromCache&&c.checkForUpdates()},checkForAppCacheUpdate:function(){u.status===u.UPDATEREADY||u.status===u.OBSOLETE?c.appCacheState="updated":u.status!==u.IDLE&&u.status!==u.UNCACHED?(c.appCacheState="checking",u.addEventListener("error",c.onAppCacheError),u.addEventListener("noupdate",c.onAppCacheNotUpdated),u.addEventListener("cached",c.onAppCacheNotUpdated),u.addEventListener("updateready",c.onAppCacheReady),u.addEventListener("obsolete",
c.onAppCacheObsolete)):c.appCacheState="current"},checkForUpdates:function(){f.fetch(c.applyCacheBuster(c.manifest.url),c.onUpdatedManifestLoaded)},onAppCacheError:function(a){l(a.message);c.appCacheState="error";c.notifyUpdateReady()},onAppCacheReady:function(){u.swapCache();c.appCacheUpdated()},onAppCacheObsolete:function(){c.appCacheUpdated()},appCacheUpdated:function(){c.appCacheState="updated";c.notifyUpdateReady()},onAppCacheNotUpdated:function(){c.appCacheState="current";c.notifyUpdateReady()},
filterAsset:function(a){a=a&&a.assetConfig||{};return a.platform||a.exclude?f.filterPlatform(a.platform,a.exclude):!0},onUpdatedManifestLoaded:function(a){a=c.parseResult(a);if(a.error)l("Error loading manifest file to check for updates"),c.onAllUpdatedAssetsReady();else{var d,b,e,g,k,h=[],m=new v({url:c.manifest.url,content:a.content,assetCache:!1});c.remainingUpdatingAssets=0;c.updatedAssets=[];c.removedAssets=[];c.updatedManifest=null;c.updatedAssetsReady=!1;if(m.shouldCache())if(c.manifest.is(m))c.onAllUpdatedAssetsReady();
else{c.updatedManifest=m;d=c.manifest.getAssets();b=m.getAssets();for(g in b)a=b[g],e=c.manifest.getAsset(a.assetConfig.path),(k=c.filterAsset(a))&&(!e||a.shouldCache()&&!e.is(a))&&h.push({_new:a,_current:e});for(g in d)e=d[g],a=m.getAsset(e.assetConfig.path),(k=!c.filterAsset(a))&&a&&(!e.shouldCache()||a.shouldCache())||c.removedAssets.push(e);if(0<h.length)for(c.remainingUpdatingAssets=h.length;0<h.length;)(e=h.pop(),a=e._new,e=e._current,"full"!==a.assetConfig.update&&e)?"delta"===a.assetConfig.update&&
(g=m.deltas,g=g+"/"+a.assetConfig.path+"/"+e.assetConfig.hash+".json",f.fetch(g,function(a,b){return function(d){c.onDeltaAssetUpdateLoaded(a,b,d)}}(a,e))):f.fetch(a.assetConfig.path,function(a){return function(b){c.onFullAssetUpdateLoaded(a,b)}}(a));else c.onAllUpdatedAssetsReady()}else c.updatedManifest=m,w.clearAllPrivate(m),c.onAllUpdatedAssetsReady()}},onFullAssetUpdateLoaded:function(a,d){var b;d=c.parseResult(d);c.remainingUpdatingAssets--;d.error?a.uncache():(b=c.checksum(d.content,a.assetConfig.hash))?
(a.updateContent(d.content),c.updatedAssets.push(a)):a.uncache();if(0===c.remainingUpdatingAssets)c.onAllUpdatedAssetsReady()},onDeltaAssetUpdateLoaded:function(a,d,b){var e,f,g;b=c.parseResult(b);c.remainingUpdatingAssets--;if(b.error)l("Error loading delta patch for "+a.assetConfig.path+" with hash "+d.assetConfig.hash+" . This asset will be uncached for future loading"),a.uncache();else try{e=JSON.parse(b.content),g=c.patch(d.content,e),(f=c.checksum(g,a.assetConfig.hash))?(a.updateContent(g),
c.updatedAssets.push(a)):a.uncache()}catch(h){l("Error parsing delta patch for "+a.assetConfig.path+" with hash "+d.assetConfig.hash+" . This asset will be uncached for future loading"),a.uncache()}if(0===c.remainingUpdatingAssets)c.onAllUpdatedAssetsReady()},onAllUpdatedAssetsReady:function(){var a;c.updatedAssetsReady=!0;if(c.updatedManifest){for(;0<c.removedAssets.length;)a=c.removedAssets.pop(),a.uncache();for(c.updatedManifest&&c.updatedManifest.cache();0<c.updatedAssets.length;)a=c.updatedAssets.pop(),
a.cache()}c.notifyUpdateReady()},notifyUpdateReady:function(){"checking"!==c.appCacheState&&c.updatedAssetsReady&&("updated"===c.appCacheState||c.updatedManifest)&&(c.appUpdate={updated:!0,app:"updated"===c.appCacheState,manifest:c.updatedManifest&&c.updatedManifest.exportContent()},c.fireAppUpdate())},fireAppUpdate:function(){Ext.GlobalEvents&&Ext.defer(function(){Ext.GlobalEvents.fireEvent("appupdate",c.appUpdate)},1E3)},checksum:function(a,c){if(!a||!c)return!1;var b=!0,e=c.length,f=a.substring(0,
1);"/"==f?a.substring(2,e+2)!==c&&(b=!1):"f"==f?a.substring(10,e+10)!==c&&(b=!1):"."==f&&a.substring(1,e+1)!==c&&(b=!1);return b},parseResult:function(a){var c={};!a.exception&&0!==a.status||f.env.phantom?200<=a.status&&300>a.status||304===a.status||f.env.phantom||0===a.status&&0<a.content.length?c.content=a.content:c.error=!0:c.error=!0;return c}};return c}();Ext.manifest=Ext.manifest||"bootstrap";Ext.Microloader.run();</script>
	<style>
		html {
			background: url(classic/resources/images/background.jpg) no-repeat center fixed;
			background-size: cover;
		}
		body {			
			background: transparent;
		}
        #splashScreen {
			/*background-color:#118074;*/
			background: transparent;
			z-index:30000;
			position:absolute;
			top:0px;bottom:0px;
			left:0px;right:0px;
			text-align:center;
			padding-top:13%;
        }
		
		#splashScreenLoading {
            margin-top             : 5px;
            color                  : #fff;
            -webkit-font-smoothing : antialiased;
        }

        #splashScreenProgress {
            display     : flex;
            background  : #e9ecef;
            height      : 1rem;
            /*border      : 1px solid #90c258;*/
            border-radius: .25rem;
            width       : 400px;
            margin      : 0 auto; 
            box-shadow  : 0 1px 10px 0 rgba(0, 0, 0, 0.19);
        }

        #splashScreenProgressInner {
            display     : flex;
            border-radius: .25rem;
            height      : 1rem;
            background  : #28a745;            
            width       : 200px;
        }
    </style>
	
</head>
<body>
	<div id='splashScreen'>
		<img id="logo" alt="No Image">
		<!--<div><img id="logo1" alt="No Image"></div>-->
		<div id="splashScreenLoading">Memuat File Aplikasi...</div>
		<div id="splashScreenProgress">
			<div id="splashScreenProgressInner"></div>
		</div>
	</div>
	<script type="text/javascript">		
		var splashScreen = document.getElementById('splashScreen'),
			logo = document.getElementById('logo');
			//logo1 = document.getElementById('logo1');
		logo.src = window.profile + "/resources/images/simrsgos2_potrait.png";
		//logo1.src = window.profile + "/resources/images/text-SIMpel.png";
	</script>
</body>
</html>
