parcelRequire=function(e,r,n){var t="function"==typeof parcelRequire&&parcelRequire,i="function"==typeof require&&require;function u(n,o){if(!r[n]){if(!e[n]){var f="function"==typeof parcelRequire&&parcelRequire;if(!o&&f)return f(n,!0);if(t)return t(n,!0);if(i&&"string"==typeof n)return i(n);var c=new Error("Cannot find module '"+n+"'");throw c.code="MODULE_NOT_FOUND",c}a.resolve=function(r){return e[n][1][r]||r};var l=r[n]=new u.Module(n);e[n][0].call(l.exports,a,l,l.exports)}return r[n].exports;function a(e){return u(a.resolve(e))}}u.isParcelRequire=!0,u.Module=function(e){this.id=e,this.bundle=u,this.exports={}},u.modules=e,u.cache=r,u.parent=t;for(var o=0;o<n.length;o++)u(n[o]);return u}({57:[function(require,module,exports) {
var global = (1,eval)("this");
var e=(0,eval)("this");!function(o){var n="object"==typeof exports&&exports&&!exports.nodeType&&exports,t="object"==typeof module&&module&&!module.nodeType&&module,r="object"==typeof e&&e;r.global!==r&&r.window!==r&&r.self!==r||(o=r);var i,u,f=2147483647,c=36,l=1,s=26,d=38,p=700,a=72,h=128,v="-",w=/^xn--/,g=/[^\x20-\x7E]/,x=/[\x2E\u3002\uFF0E\uFF61]/g,y={overflow:"Overflow: input needs wider integers to process","not-basic":"Illegal input >= 0x80 (not a basic code point)","invalid-input":"Invalid input"},C=c-l,b=Math.floor,m=String.fromCharCode;function j(e){throw new RangeError(y[e])}function A(e,o){for(var n=e.length,t=[];n--;)t[n]=o(e[n]);return t}function I(e,o){var n=e.split("@"),t="";return n.length>1&&(t=n[0]+"@",e=n[1]),t+A((e=e.replace(x,".")).split("."),o).join(".")}function E(e){for(var o,n,t=[],r=0,i=e.length;r<i;)(o=e.charCodeAt(r++))>=55296&&o<=56319&&r<i?56320==(64512&(n=e.charCodeAt(r++)))?t.push(((1023&o)<<10)+(1023&n)+65536):(t.push(o),r--):t.push(o);return t}function F(e){return A(e,function(e){var o="";return e>65535&&(o+=m((e-=65536)>>>10&1023|55296),e=56320|1023&e),o+=m(e)}).join("")}function O(e,o){return e+22+75*(e<26)-((0!=o)<<5)}function S(e,o,n){var t=0;for(e=n?b(e/p):e>>1,e+=b(e/o);e>C*s>>1;t+=c)e=b(e/C);return b(t+(C+1)*e/(e+d))}function T(e){var o,n,t,r,i,u,d,p,w,g,x,y=[],C=e.length,m=0,A=h,I=a;for((n=e.lastIndexOf(v))<0&&(n=0),t=0;t<n;++t)e.charCodeAt(t)>=128&&j("not-basic"),y.push(e.charCodeAt(t));for(r=n>0?n+1:0;r<C;){for(i=m,u=1,d=c;r>=C&&j("invalid-input"),((p=(x=e.charCodeAt(r++))-48<10?x-22:x-65<26?x-65:x-97<26?x-97:c)>=c||p>b((f-m)/u))&&j("overflow"),m+=p*u,!(p<(w=d<=I?l:d>=I+s?s:d-I));d+=c)u>b(f/(g=c-w))&&j("overflow"),u*=g;I=S(m-i,o=y.length+1,0==i),b(m/o)>f-A&&j("overflow"),A+=b(m/o),m%=o,y.splice(m++,0,A)}return F(y)}function L(e){var o,n,t,r,i,u,d,p,w,g,x,y,C,A,I,F=[];for(y=(e=E(e)).length,o=h,n=0,i=a,u=0;u<y;++u)(x=e[u])<128&&F.push(m(x));for(t=r=F.length,r&&F.push(v);t<y;){for(d=f,u=0;u<y;++u)(x=e[u])>=o&&x<d&&(d=x);for(d-o>b((f-n)/(C=t+1))&&j("overflow"),n+=(d-o)*C,o=d,u=0;u<y;++u)if((x=e[u])<o&&++n>f&&j("overflow"),x==o){for(p=n,w=c;!(p<(g=w<=i?l:w>=i+s?s:w-i));w+=c)I=p-g,A=c-g,F.push(m(O(g+I%A,0))),p=b(I/A);F.push(m(O(p,0))),i=S(n,C,t==r),n=0,++t}++n,++o}return F.join("")}if(i={version:"1.4.1",ucs2:{decode:E,encode:F},decode:T,encode:L,toASCII:function(e){return I(e,function(e){return g.test(e)?"xn--"+L(e):e})},toUnicode:function(e){return I(e,function(e){return w.test(e)?T(e.slice(4).toLowerCase()):e})}},"function"==typeof define&&"object"==typeof define.amd&&define.amd)define("punycode",function(){return i});else if(n&&t)if(module.exports==n)t.exports=i;else for(u in i)i.hasOwnProperty(u)&&(n[u]=i[u]);else o.punycode=i}(this);
},{}],59:[function(require,module,exports) {
"use strict";module.exports={isString:function(n){return"string"==typeof n},isObject:function(n){return"object"==typeof n&&null!==n},isNull:function(n){return null===n},isNullOrUndefined:function(n){return null==n}};
},{}],60:[function(require,module,exports) {
"use strict";function r(r,e){return Object.prototype.hasOwnProperty.call(r,e)}module.exports=function(t,n,o,a){n=n||"&",o=o||"=";var s={};if("string"!=typeof t||0===t.length)return s;var p=/\+/g;t=t.split(n);var u=1e3;a&&"number"==typeof a.maxKeys&&(u=a.maxKeys);var c=t.length;u>0&&c>u&&(c=u);for(var i=0;i<c;++i){var y,l,f,v,b=t[i].replace(p,"%20"),d=b.indexOf(o);d>=0?(y=b.substr(0,d),l=b.substr(d+1)):(y=b,l=""),f=decodeURIComponent(y),v=decodeURIComponent(l),r(s,f)?e(s[f])?s[f].push(v):s[f]=[s[f],v]:s[f]=v}return s};var e=Array.isArray||function(r){return"[object Array]"===Object.prototype.toString.call(r)};
},{}],61:[function(require,module,exports) {
"use strict";var n=function(n){switch(typeof n){case"string":return n;case"boolean":return n?"true":"false";case"number":return isFinite(n)?n:"";default:return""}};module.exports=function(o,u,c,a){return u=u||"&",c=c||"=",null===o&&(o=void 0),"object"==typeof o?r(t(o),function(t){var a=encodeURIComponent(n(t))+c;return e(o[t])?r(o[t],function(e){return a+encodeURIComponent(n(e))}).join(u):a+encodeURIComponent(n(o[t]))}).join(u):a?encodeURIComponent(n(a))+c+encodeURIComponent(n(o)):""};var e=Array.isArray||function(n){return"[object Array]"===Object.prototype.toString.call(n)};function r(n,e){if(n.map)return n.map(e);for(var r=[],t=0;t<n.length;t++)r.push(e(n[t],t));return r}var t=Object.keys||function(n){var e=[];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&e.push(r);return e};
},{}],58:[function(require,module,exports) {
"use strict";exports.decode=exports.parse=require("./decode"),exports.encode=exports.stringify=require("./encode");
},{"./decode":60,"./encode":61}],56:[function(require,module,exports) {
"use strict";var t=require("punycode"),s=require("./util");function h(){this.protocol=null,this.slashes=null,this.auth=null,this.host=null,this.port=null,this.hostname=null,this.hash=null,this.search=null,this.query=null,this.pathname=null,this.path=null,this.href=null}exports.parse=b,exports.resolve=O,exports.resolveObject=d,exports.format=q,exports.Url=h;var e=/^([a-z0-9.+-]+:)/i,a=/:[0-9]*$/,r=/^(\/\/?(?!\/)[^\?\s]*)(\?[^\s]*)?$/,o=["<",">",'"',"`"," ","\r","\n","\t"],n=["{","}","|","\\","^","`"].concat(o),i=["'"].concat(n),l=["%","/","?",";","#"].concat(i),p=["/","?","#"],c=255,u=/^[+a-z0-9A-Z_-]{0,63}$/,f=/^([+a-z0-9A-Z_-]{0,63})(.*)$/,m={javascript:!0,"javascript:":!0},v={javascript:!0,"javascript:":!0},g={http:!0,https:!0,ftp:!0,gopher:!0,file:!0,"http:":!0,"https:":!0,"ftp:":!0,"gopher:":!0,"file:":!0},y=require("querystring");function b(t,e,a){if(t&&s.isObject(t)&&t instanceof h)return t;var r=new h;return r.parse(t,e,a),r}function q(t){return s.isString(t)&&(t=b(t)),t instanceof h?t.format():h.prototype.format.call(t)}function O(t,s){return b(t,!1,!0).resolve(s)}function d(t,s){return t?b(t,!1,!0).resolveObject(s):s}h.prototype.parse=function(h,a,o){if(!s.isString(h))throw new TypeError("Parameter 'url' must be a string, not "+typeof h);var n=h.indexOf("?"),b=-1!==n&&n<h.indexOf("#")?"?":"#",q=h.split(b);q[0]=q[0].replace(/\\/g,"/");var O=h=q.join(b);if(O=O.trim(),!o&&1===h.split("#").length){var d=r.exec(O);if(d)return this.path=O,this.href=O,this.pathname=d[1],d[2]?(this.search=d[2],this.query=a?y.parse(this.search.substr(1)):this.search.substr(1)):a&&(this.search="",this.query={}),this}var j=e.exec(O);if(j){var x=(j=j[0]).toLowerCase();this.protocol=x,O=O.substr(j.length)}if(o||j||O.match(/^\/\/[^@\/]+@[^@\/]+/)){var A="//"===O.substr(0,2);!A||j&&v[j]||(O=O.substr(2),this.slashes=!0)}if(!v[j]&&(A||j&&!g[j])){for(var C,I,w=-1,U=0;U<p.length;U++){-1!==(k=O.indexOf(p[U]))&&(-1===w||k<w)&&(w=k)}-1!==(I=-1===w?O.lastIndexOf("@"):O.lastIndexOf("@",w))&&(C=O.slice(0,I),O=O.slice(I+1),this.auth=decodeURIComponent(C)),w=-1;for(U=0;U<l.length;U++){var k;-1!==(k=O.indexOf(l[U]))&&(-1===w||k<w)&&(w=k)}-1===w&&(w=O.length),this.host=O.slice(0,w),O=O.slice(w),this.parseHost(),this.hostname=this.hostname||"";var N="["===this.hostname[0]&&"]"===this.hostname[this.hostname.length-1];if(!N)for(var R=this.hostname.split(/\./),S=(U=0,R.length);U<S;U++){var $=R[U];if($&&!$.match(u)){for(var z="",H=0,L=$.length;H<L;H++)$.charCodeAt(H)>127?z+="x":z+=$[H];if(!z.match(u)){var Z=R.slice(0,U),_=R.slice(U+1),E=$.match(f);E&&(Z.push(E[1]),_.unshift(E[2])),_.length&&(O="/"+_.join(".")+O),this.hostname=Z.join(".");break}}}this.hostname.length>c?this.hostname="":this.hostname=this.hostname.toLowerCase(),N||(this.hostname=t.toASCII(this.hostname));var P=this.port?":"+this.port:"",T=this.hostname||"";this.host=T+P,this.href+=this.host,N&&(this.hostname=this.hostname.substr(1,this.hostname.length-2),"/"!==O[0]&&(O="/"+O))}if(!m[x])for(U=0,S=i.length;U<S;U++){var B=i[U];if(-1!==O.indexOf(B)){var D=encodeURIComponent(B);D===B&&(D=escape(B)),O=O.split(B).join(D)}}var F=O.indexOf("#");-1!==F&&(this.hash=O.substr(F),O=O.slice(0,F));var G=O.indexOf("?");if(-1!==G?(this.search=O.substr(G),this.query=O.substr(G+1),a&&(this.query=y.parse(this.query)),O=O.slice(0,G)):a&&(this.search="",this.query={}),O&&(this.pathname=O),g[x]&&this.hostname&&!this.pathname&&(this.pathname="/"),this.pathname||this.search){P=this.pathname||"";var J=this.search||"";this.path=P+J}return this.href=this.format(),this},h.prototype.format=function(){var t=this.auth||"";t&&(t=(t=encodeURIComponent(t)).replace(/%3A/i,":"),t+="@");var h=this.protocol||"",e=this.pathname||"",a=this.hash||"",r=!1,o="";this.host?r=t+this.host:this.hostname&&(r=t+(-1===this.hostname.indexOf(":")?this.hostname:"["+this.hostname+"]"),this.port&&(r+=":"+this.port)),this.query&&s.isObject(this.query)&&Object.keys(this.query).length&&(o=y.stringify(this.query));var n=this.search||o&&"?"+o||"";return h&&":"!==h.substr(-1)&&(h+=":"),this.slashes||(!h||g[h])&&!1!==r?(r="//"+(r||""),e&&"/"!==e.charAt(0)&&(e="/"+e)):r||(r=""),a&&"#"!==a.charAt(0)&&(a="#"+a),n&&"?"!==n.charAt(0)&&(n="?"+n),h+r+(e=e.replace(/[?#]/g,function(t){return encodeURIComponent(t)}))+(n=n.replace("#","%23"))+a},h.prototype.resolve=function(t){return this.resolveObject(b(t,!1,!0)).format()},h.prototype.resolveObject=function(t){if(s.isString(t)){var e=new h;e.parse(t,!1,!0),t=e}for(var a=new h,r=Object.keys(this),o=0;o<r.length;o++){var n=r[o];a[n]=this[n]}if(a.hash=t.hash,""===t.href)return a.href=a.format(),a;if(t.slashes&&!t.protocol){for(var i=Object.keys(t),l=0;l<i.length;l++){var p=i[l];"protocol"!==p&&(a[p]=t[p])}return g[a.protocol]&&a.hostname&&!a.pathname&&(a.path=a.pathname="/"),a.href=a.format(),a}if(t.protocol&&t.protocol!==a.protocol){if(!g[t.protocol]){for(var c=Object.keys(t),u=0;u<c.length;u++){var f=c[u];a[f]=t[f]}return a.href=a.format(),a}if(a.protocol=t.protocol,t.host||v[t.protocol])a.pathname=t.pathname;else{for(var m=(t.pathname||"").split("/");m.length&&!(t.host=m.shift()););t.host||(t.host=""),t.hostname||(t.hostname=""),""!==m[0]&&m.unshift(""),m.length<2&&m.unshift(""),a.pathname=m.join("/")}if(a.search=t.search,a.query=t.query,a.host=t.host||"",a.auth=t.auth,a.hostname=t.hostname||t.host,a.port=t.port,a.pathname||a.search){var y=a.pathname||"",b=a.search||"";a.path=y+b}return a.slashes=a.slashes||t.slashes,a.href=a.format(),a}var q=a.pathname&&"/"===a.pathname.charAt(0),O=t.host||t.pathname&&"/"===t.pathname.charAt(0),d=O||q||a.host&&t.pathname,j=d,x=a.pathname&&a.pathname.split("/")||[],A=(m=t.pathname&&t.pathname.split("/")||[],a.protocol&&!g[a.protocol]);if(A&&(a.hostname="",a.port=null,a.host&&(""===x[0]?x[0]=a.host:x.unshift(a.host)),a.host="",t.protocol&&(t.hostname=null,t.port=null,t.host&&(""===m[0]?m[0]=t.host:m.unshift(t.host)),t.host=null),d=d&&(""===m[0]||""===x[0])),O)a.host=t.host||""===t.host?t.host:a.host,a.hostname=t.hostname||""===t.hostname?t.hostname:a.hostname,a.search=t.search,a.query=t.query,x=m;else if(m.length)x||(x=[]),x.pop(),x=x.concat(m),a.search=t.search,a.query=t.query;else if(!s.isNullOrUndefined(t.search)){if(A)a.hostname=a.host=x.shift(),(k=!!(a.host&&a.host.indexOf("@")>0)&&a.host.split("@"))&&(a.auth=k.shift(),a.host=a.hostname=k.shift());return a.search=t.search,a.query=t.query,s.isNull(a.pathname)&&s.isNull(a.search)||(a.path=(a.pathname?a.pathname:"")+(a.search?a.search:"")),a.href=a.format(),a}if(!x.length)return a.pathname=null,a.search?a.path="/"+a.search:a.path=null,a.href=a.format(),a;for(var C=x.slice(-1)[0],I=(a.host||t.host||x.length>1)&&("."===C||".."===C)||""===C,w=0,U=x.length;U>=0;U--)"."===(C=x[U])?x.splice(U,1):".."===C?(x.splice(U,1),w++):w&&(x.splice(U,1),w--);if(!d&&!j)for(;w--;w)x.unshift("..");!d||""===x[0]||x[0]&&"/"===x[0].charAt(0)||x.unshift(""),I&&"/"!==x.join("/").substr(-1)&&x.push("");var k,N=""===x[0]||x[0]&&"/"===x[0].charAt(0);A&&(a.hostname=a.host=N?"":x.length?x.shift():"",(k=!!(a.host&&a.host.indexOf("@")>0)&&a.host.split("@"))&&(a.auth=k.shift(),a.host=a.hostname=k.shift()));return(d=d||a.host&&x.length)&&!N&&x.unshift(""),x.length?a.pathname=x.join("/"):(a.pathname=null,a.path=null),s.isNull(a.pathname)&&s.isNull(a.search)||(a.path=(a.pathname?a.pathname:"")+(a.search?a.search:"")),a.auth=t.auth||a.auth,a.slashes=a.slashes||t.slashes,a.href=a.format(),a},h.prototype.parseHost=function(){var t=this.host,s=a.exec(t);s&&(":"!==(s=s[0])&&(this.port=s.substr(1)),t=t.substr(0,t.length-s.length)),t&&(this.hostname=t)};
},{"punycode":57,"./util":59,"querystring":58}],55:[function(require,module,exports) {
var r=require("url").resolve,e=Array.prototype.slice;function l(l){var o=e.call(arguments,1);return r(l,o.join("/").replace(/\/+/g,"/"))}module.exports=l;
},{"url":56}],49:[function(require,module,exports) {
"use strict";Object.defineProperty(exports,"__esModule",{value:!0});var e=require("url-resolve"),r=t(e);function t(e){return e&&e.__esModule?e:{default:e}}var u="./api.php",o={getUser:function(){return axios({method:"GET",url:(0,r.default)(u)})}};window.api=o,window.BASE_URL=u,exports.default=o;
},{"url-resolve":55}]},{},[49])
//# sourceMappingURL=api.b870e32a.map