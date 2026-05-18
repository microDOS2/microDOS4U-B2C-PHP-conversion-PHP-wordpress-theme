/**
 * Affiliate Creative Easy Copy Buttons
 * Adds Copy Image URL, Copy My Link, and Copy for Email buttons
 * to AffiliateWP's creative modal - based on actual DOM structure
 */
(function() {
    'use strict';

    var LABELS = {
        image: '📋 Copy Image URL',
        link: '📋 Copy My Link', 
        email: '📧 Copy for Email',
        copied: '✓ Copied!',
        guide: '✓ Guide copied!'
    };

    // Copy text to clipboard
    function copy(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }
        return new Promise(function(resolve) {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;left:-9999px;opacity:0;';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            resolve();
        });
    }

    // Show feedback on button
    function feedback(btn, msg) {
        var orig = btn.textContent;
        btn.textContent = msg;
        btn.style.opacity = '0.8';
        setTimeout(function() {
            btn.textContent = orig;
            btn.style.opacity = '1';
        }, 2000);
    }

    // Create a styled button
    function makeBtn(text, color, borderColor) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = text;
        btn.style.cssText = 'display:inline-flex;align-items:center;gap:6px;padding:10px 18px;margin:6px 4px;border:2px solid ' + borderColor + ';border-radius:8px;background:transparent;color:' + color + ';font-size:14px;font-weight:700;cursor:pointer;transition:all 0.2s;font-family:inherit;';
        btn.onmouseenter = function() {
            this.style.background = color;
            this.style.color = '#0a0514';
        };
        btn.onmouseleave = function() {
            this.style.background = 'transparent';
            this.style.color = color;
        };
        return btn;
    }

    // Main injection function
    function inject() {
        // Find all code blocks that contain affiliate HTML
        var allPres = document.querySelectorAll('pre, code, .creative-code, [class*="code"]');
        
        for (var i = 0; i < allPres.length; i++) {
            var block = allPres[i];
            var text = block.textContent || block.innerText || '';
            
            // Check if this block contains affiliate HTML with both href and src
            if (text.indexOf('<a') === -1 || text.indexOf('href=') === -1 || text.indexOf('<img') === -1 || text.indexOf('src=') === -1) {
                continue;
            }
            
            // Extract URLs
            var hrefMatch = text.match(/href=["']([^"']+)["']/);
            var srcMatch = text.match(/src=["']([^"']+)["']/);
            
            if (!hrefMatch || !srcMatch) continue;
            
            var linkUrl = hrefMatch[1];
            var imageUrl = srcMatch[1];
            
            // Find where to insert - look for the button container or the code block's parent
            var insertTarget = null;
            
            // Try to find the button container (where "Copy to clipboard" button is)
            var parent = block.parentElement;
            for (var p = 0; p < 5 && parent; p++) {
                var btns = parent.querySelectorAll('button, .button, [role="button"], a[class*="copy"], a[class*="button"]');
                for (var b = 0; b < btns.length; b++) {
                    var btnText = btns[b].textContent || '';
                    if (btnText.toLowerCase().indexOf('copy') >= 0 || btnText.toLowerCase().indexOf('clipboard') >= 0 || btnText.toLowerCase().indexOf('download') >= 0) {
                        insertTarget = btns[b].parentElement;
                        break;
                    }
                }
                if (insertTarget) break;
                parent = parent.parentElement;
            }
            
            // Fallback: use the code block's parent
            if (!insertTarget) {
                insertTarget = block.parentElement;
            }
            
            if (!insertTarget) continue;
            
            // Check if already injected
            if (insertTarget.querySelector('.mcd-email-buttons')) continue;
            
            // Create button container
            var container = document.createElement('div');
            container.className = 'mcd-email-buttons';
            container.style.cssText = 'margin-top:16px;padding:16px;background:rgba(10,5,20,0.95);border:2px solid #1f2b47;border-radius:12px;';
            
            // Label
            var label = document.createElement('div');
            label.textContent = '📧 For Email (Gmail, Outlook)';
            label.style.cssText = 'color:#a0b3d6;font-size:13px;font-weight:600;margin-bottom:12px;display:block;';
            container.appendChild(label);
            
            // Copy Image URL button
            var btnImg = makeBtn(LABELS.image, '#44f80c', '#44f80c');
            btnImg.onclick = function() {
                copy(imageUrl).then(function() { feedback(btnImg, LABELS.copied); });
            };
            container.appendChild(btnImg);
            
            // Copy My Link button
            var btnLink = makeBtn(LABELS.link, '#44f80c', '#44f80c');
            btnLink.onclick = function() {
                copy(linkUrl).then(function() { feedback(btnLink, LABELS.copied); });
            };
            container.appendChild(btnLink);
            
            // Copy for Email button (with full instructions)
            var btnEmail = makeBtn(LABELS.email, '#38bdf8', '#38bdf8');
            btnEmail.onclick = function() {
                var guide = 'IMAGE URL (paste in Gmail > Insert Photo > By URL):\n' + imageUrl +
                           '\n\nYOUR AFFILIATE LINK (paste in Gmail > click image > Link icon):\n' + linkUrl;
                copy(guide).then(function() { feedback(btnEmail, LABELS.guide); });
            };
            container.appendChild(btnEmail);
            
            // Insert after the button container
            insertTarget.parentElement.insertBefore(container, insertTarget.nextSibling);
            
            console.log('[microDOS] Creative copy buttons injected');
        }
    }

    // Watch for modal to appear
    var observer = new MutationObserver(function() {
        clearTimeout(window._mcdTimer);
        window._mcdTimer = setTimeout(inject, 300);
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
    
    // Also try immediately
    setTimeout(inject, 500);
    setTimeout(inject, 1500);
    
})();
