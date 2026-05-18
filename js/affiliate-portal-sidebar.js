/* microDOS Portal Sidebar Link */
(function(){
  if(window._mdSidebar)return;
  window._mdSidebar=1;

  function addLink(){
    var nav=document.querySelector('nav[class*="sidebar"]')||document.querySelector('[class*="sidebar-nav"]')||document.querySelector('nav');
    if(!nav)return;
    var links=nav.querySelectorAll('a');
    if(links.length<3)return;

    var a=document.createElement('a');
    a.href='/creatives-easy/';
    a.textContent='Quick Copy Creatives';
    a.className=links[0].className||'';
    a.style.cssText='display:flex;align-items:center;gap:8px;padding:10px 16px;color:#44f80c;text-decoration:none;font-weight:600;border-left:3px solid #44f80c;background:rgba(68,248,12,0.05);';
    a.onmouseenter=function(){a.style.background='rgba(68,248,12,0.15)';};
    a.onmouseleave=function(){a.style.background='rgba(68,248,12,0.05)';};

    var li=document.createElement('li');
    li.style.cssText='margin:4px 0;list-style:none;';
    li.appendChild(a);

    var ul=nav.querySelector('ul')||nav;
    ul.appendChild(li);
    return true;
  }

  // Try multiple times
  var tries=0;
  var iv=setInterval(function(){
    if(addLink()||tries++>30)clearInterval(iv);
  },500);

  // Also after full load
  window.addEventListener('load',function(){
    setTimeout(addLink,1000);
    setTimeout(addLink,2500);
  });
})();
