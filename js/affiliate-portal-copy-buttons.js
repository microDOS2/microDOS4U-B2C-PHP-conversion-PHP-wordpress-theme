/* microDOS Portal Creative Copy Buttons - Minimal Approach */
(function(){
  'use strict';
  if(window._mdcCopyDone)return;
  window._mdcCopyDone=1;

  function copy(t){return navigator.clipboard&&window.isSecureContext?navigator.clipboard.writeText(t).catch(function(){return fall(t)}):fall(t)}
  function fall(t){return new Promise(function(r){var x=document.createElement("textarea");x.value=t;x.style.cssText="position:fixed;left:-9999px;top:-9999px;";document.body.appendChild(x);x.select();try{r(document.execCommand("copy"))}catch(e){r(false)}finally{document.body.removeChild(x)}})}

  function feedback(btn){
    var orig=btn.innerHTML;
    btn.innerHTML="✓ Copied!";
    btn.style.background="#44f80c";
    btn.style.color="#0a0514";
    setTimeout(function(){btn.innerHTML=orig;btn.style.background="";btn.style.color="";},1500);
  }

  function makeBtn(label,url){
    var b=document.createElement("button");
    b.type="button";
    b.textContent=label;
    b.style.cssText="display:inline-block;margin:4px 4px 0 0;padding:6px 12px;background:#1a1030;border:1px solid rgba(68,248,12,.4);border-radius:4px;color:#e2e8f0;font-size:12px;cursor:pointer;";
    b.onmouseenter=function(){b.style.background="#44f80c";b.style.color="#0a0514";b.style.borderColor="#44f80c";b.style.boxShadow="0 2px 8px rgba(68,248,12,.3)";b.style.transform="translateY(-1px)";b.style.transition="all .2s";}
    b.onmouseleave=function(){b.style.background="#1a1030";b.style.color="#e2e8f0";b.style.borderColor="rgba(68,248,12,.4)";b.style.boxShadow="";b.style.transform="";}
    b.onclick=function(e){e.preventDefault();e.stopPropagation();copy(url).then(function(ok){if(ok)feedback(b);});};
    return b;
  }

  function parseCode(el){
    var txt=(el.textContent||"").trim();
    if(!txt)return null;
    var h=txt.match(/href=["']([^"']+)["']/i),s=txt.match(/src=["']([^"']+)["']/i),a=txt.match(/alt=["']([^"]*)["']/i);
    return{html:txt,link:h?h[1]:"",img:s?s[1]:"",alt:a?a[1]:"microDOS(2)"};
  }

  function inject(){
    var btns=document.querySelectorAll("button");
    btns.forEach(function(btn){
      if(btn._mdDone)return;
      var txt=(btn.textContent||"").trim().toLowerCase();
      if(txt.indexOf("copy to clipboard")===-1&&txt.indexOf("copy to clipboard")===-1)return;

      var codeEl=btn.parentElement.querySelector("pre")||btn.parentElement.querySelector("code")||btn.previousElementSibling;
      if(!codeEl)return;
      var d=parseCode(codeEl);
      if(!d||!d.link)return;
      btn._mdDone=1;

      var wrap=document.createElement("div");
      wrap.style.cssText="margin-top:10px;padding-top:8px;border-top:1px solid rgba(68,248,12,.2);display:flex;flex-wrap:wrap;gap:6px;";

      if(d.img) wrap.appendChild(makeBtn("📋 Copy Image URL",d.img));
      wrap.appendChild(makeBtn("🔗 Copy My Link",d.link));
      if(d.img){
        var emailHtml='<a href="'+d.link+'">\n  <img src="'+d.img+'" alt="'+d.alt+'" style="max-width:100%;height:auto;" />\n</a>';
        wrap.appendChild(makeBtn("📧 Copy for Email",emailHtml));
      }

      btn.parentElement.insertBefore(wrap,btn.nextSibling);
    });
  }

  // Run immediately + on DOM changes
  inject();
  var obs=new MutationObserver(function(ms){var run=false;ms.forEach(function(m){m.addedNodes.forEach(function(n){if(n.nodeType===1&&(n.tagName==="DIV"||n.tagName==="BUTTON"))run=true;});});if(run)setTimeout(inject,100);});
  obs.observe(document.body,{childList:true,subtree:true});
  setTimeout(inject,500);
  setTimeout(inject,1500);
  setTimeout(inject,3000);
})();
