/* microDOS Creative Modal Copy Buttons */
(function(){
  'use strict';
  if(window._mdc2)return;window._mdc2=1;

  function copy(t){return navigator.clipboard&&window.isSecureContext?navigator.clipboard.writeText(t).catch(function(){return f(t)}):f(t)}
  function f(t){return new Promise(function(r){var x=document.createElement("textarea");x.value=t;x.style.cssText="position:fixed;left:-9999px;top:-9999px;opacity:0;";document.body.appendChild(x);x.select();try{r(document.execCommand("copy"))}catch(e){r(false)}finally{document.body.removeChild(x)}})}
  function fb(btn){var o=btn.innerHTML;btn.innerHTML="<b style=color:#44f80c>✓ Copied!</b>";setTimeout(function(){btn.innerHTML=o},1500);}
  function mkBtn(label,url){var b=document.createElement("button");b.type="button";b.textContent=label;b.style.cssText="display:inline-block;margin:4px;padding:8px 14px;background:#1a1030;border:1px solid #44f80c;border-radius:5px;color:#e2e8f0;font-size:13px;cursor:pointer;transition:all .2s";b.onmouseenter=function(){b.style.background="#44f80c";b.style.color="#0a0514";};b.onmouseleave=function(){b.style.background="#1a1030";b.style.color="#e2e8f0";};b.onclick=function(e){e.preventDefault();e.stopPropagation();copy(url).then(function(ok){if(ok)fb(b);});};return b;}
  function parse(el){var t=(el.textContent||"").trim();if(!t)return null;var h=t.match(/href=["']([^"']+)["']/i),s=t.match(/src=["']([^"']+)["']/i),a=t.match(/alt=["']([^"]*)["']/i);return{html:t,link:h?h[1]:"",img:s?s[1]:"",alt:a?a[1]:"microDOS(2)"};}

  function run(){
    var modals=document.querySelectorAll('[class*="modal"], [role="dialog"], [class*="dialog"]');
    modals.forEach(function(m){
      if(m._mdDone)return;
      var copyBtn=m.querySelector("button")||m.querySelector('[class*="copy"]');
      if(!copyBtn)return;
      var txt=copyBtn.textContent||"";if(txt.toLowerCase().indexOf("copy")===-1)return;
      var pre=m.querySelector("pre")||m.querySelector("code")||copyBtn.previousElementSibling;
      if(!pre)return;
      var d=parse(pre);if(!d||!d.link)return;
      m._mdDone=1;

      var w=document.createElement("div");w.style.cssText="margin-top:12px;padding-top:10px;border-top:1px solid rgba(68,248,12,.2);display:flex;flex-wrap:wrap;gap:6px;";
      if(d.img){w.appendChild(mkBtn("📋 Copy Image URL",d.img));w.appendChild(mkBtn("📧 Copy for Email",'<a href="'+d.link+'">\n  <img src="'+d.img+'" alt="'+(d.alt||"")+'" style=max-width:100%;height:auto; />\n</a>'));}
      w.appendChild(mkBtn("🔗 Copy My Link",d.link));
      copyBtn.parentElement.insertBefore(w,copyBtn.nextSibling);
    });
  }

  run();
  var o=new MutationObserver(function(ms){var r=false;ms.forEach(function(m){m.addedNodes.forEach(function(n){if(n.nodeType===1)r=true;});});if(r)setTimeout(run,200);});
  o.observe(document.body,{childList:true,subtree:true});
  setTimeout(run,500);setTimeout(run,1500);setTimeout(run,3000);
})();
