Modernizr.addTest('android',function(){return!!navigator.userAgent.match(/Android/i)});
Modernizr.addTest('chrome',function(){return!!navigator.userAgent.match(/Chrome/i)});
Modernizr.addTest('firefox',function(){return!!navigator.userAgent.match(/Firefox/i)});
Modernizr.addTest('iemobile',function(){return!!navigator.userAgent.match(/IEMobile/i)});
Modernizr.addTest('ie',function(){return!!navigator.userAgent.match(/MSIE/i)});
Modernizr.addTest('ie10',function(){return!!navigator.userAgent.match(/MSIE 10/i)});
Modernizr.addTest('ios',function(){return!!navigator.userAgent.match(/iPhone|iPad|iPod/i)});
Modernizr.addTest('retina',function(){var ratio="2.99/2";var num="1.499";var mqs=["only screen and (-o-min-device-pixel-ratio:"+ratio+")","only screen and (min--moz-device-pixel-ratio:"+ratio+")","only screen and (-webkit-min-device-pixel-ratio:"+num+")","only screen and (min-device-pixel-ratio:"+num+")"];var isRetina=false;for(var i=mqs.length-1;i>=0;i--){isRetina=Modernizr.mq(mqs[i]);if(isRetina)return isRetina}return isRetina});
Modernizr.addTest('safari',function(){return!navigator.userAgent.match(/Chrome/i)&&!navigator.userAgent.match(/iPhone|iPad|iPod/i)&&!!navigator.userAgent.match(/Safari/i)});