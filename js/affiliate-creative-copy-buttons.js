/**
 * Affiliate Creative Easy Copy Buttons
 * Injects Copy Image URL, Copy My Link, and Copy for Email buttons
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

    function feedback(btn, msg) {
        var orig = btn.textContent;
        btn.textContent = msg;
        btn.style.opacity = '0.8';
        setTimeout(function() {
            btn.textContent = orig;
            btn.style.opacity = '1';
        }, 2000);
    }

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

    function extractUrls(html) {
        var href = html.match(/href=["']([^"']+)["']/);
        var src = html.match(/src=["']([^"']+)["']/);
        return href && src ? { link: href[1], image: src[1] } : null;
    }

    function findCodeBlock() {
        // Strategy 1: <pre> or <code>
        var els = document.querySelectorAll('pre, code');
        for (var i = 0; i < els.length; i++) {
            var t = els[i].textContent || '';
            if (t.indexOf('<a') > -1 && t.indexOf('href=') > -1 && t.indexOf('<img') > -1) {
                return els[i];
            }
        }
        // Strategy 2: Any div/span containing the HTML pattern
        els = document.querySelectorAll('div, span, section, textarea');
        for (var j = 0; j < els.length; j++) {
            var t2 = els[j].textContent || '';
            if (t2.indexOf('<a') > -1 && t2.indexOf('href=') > -1 && t2.indexOf('<img') > -1 && t2.indexOf('</a>') > -1) {
                return els[j];
            }
        }
        return null;
    }

    function findButtonContainer() {
        var btns = document.querySelectorAll('button, [role="button"], a[class*="copy"], a[class*="button"]');
        for (var i = 0; i < btns.length; i++) {
            var txt = (btns[i].textContent || '').toLowerCase();
            if (txt.indexOf('copy') > -1 || txt.indexOf('clipboard') > -1 || txt.indexOf('download') > -1) {
                return btns[i].parentElement;
            }
        }
        return null;
    }

    function inject() {
        // Prevent duplicates
        if (document.querySelector('.mcd-email-buttons')) return;

        var codeBlock = findCodeBlock();
        if (!codeBlock) return;

        var text = codeBlock.textContent || codeBlock.innerText || codeBlock.value || '';
        var urls = extractUrls(text);
        if (!urls) return;

        var insertTarget = findButtonContainer() || codeBlock.parentElement;
        if (!insertTarget) return;

        var container = document.createElement('div');
        container.className = 'mcd-email-buttons';
        container.style.cssText = 'margin-top:16px;padding:16px;background:#0a0514;border:2px solid #1f2b47;border-radius:12px;';

        var label = document.createElement('div');
        label.textContent = '📧 For Email (Gmail, Outlook)';
        label.style.cssText = 'color:#a0b3d6;font-size:13px;font-weight:600;margin-bottom:12px;';
        container.appendChild(label);

        var btnImg = makeBtn(LABELS.image, '#44f80c', '#44f80c');
        btnImg.onclick = function() { copy(urls.image).then(function() { feedback(btnImg, LABELS.copied); }); };
        container.appendChild(btnImg);

        var btnLink = makeBtn(LABELS.link, '#44f80c', '#44f80c');
        btnLink.onclick = function() { copy(urls.link).then(function() { feedback(btnLink, LABELS.copied); }); };
        container.appendChild(btnLink);

        var btnEmail = makeBtn(LABELS.email, '#38bdf8', '#38bdf8');
        btnEmail.onclick = function() {
            var g = 'IMAGE URL (Gmail > Insert Photo > By URL):\n' + urls.image +
                   '\n\nYOUR LINK (Gmail > click image > Link icon):\n' + urls.link;
            copy(g).then(function() { feedback(btnEmail, LABELS.guide); });
        };
        container.appendChild(btnEmail);

        insertTarget.parentElement.insertBefore(container, insertTarget.nextSibling);
        console.log('[microDOS] Creative copy buttons added');
    }

    // Watch for DOM changes
    var observer = new MutationObserver(function() {
        clearTimeout(window._mcdTimer);
        window._mcdTimer = setTimeout(inject, 200);
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Also try on click (modal open)
    document.addEventListener('click', function() {
        clearTimeout(window._mcdClickTimer);
        window._mcdClickTimer = setTimeout(inject, 300);
    });

    // Immediate attempts
    setTimeout(inject, 300);
    setTimeout(inject, 800);
    setTimeout(inject, 1500);
    setTimeout(inject, 3000);
})();
