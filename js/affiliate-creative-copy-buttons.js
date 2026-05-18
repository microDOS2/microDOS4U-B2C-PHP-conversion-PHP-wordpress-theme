/**
 * Affiliate Creative Easy Copy Buttons
 * Adds "Copy Image URL", "Copy My Link", and "Copy for Email" buttons
 * to AffiliateWP's creative modal for easy email/Gmail usage.
 */
(function() {
    'use strict';

    // Wait for modal to open
    function initCopyButtons() {
        // Check if creative modal is visible (AffiliateWP uses #creative-modal or .creative-modal)
        var modal = document.querySelector('#creative-modal') || 
                    document.querySelector('.creative-modal') ||
                    document.querySelector('[class*="creative"][class*="modal"]');
        
        if (!modal || !modal.offsetParent) return;
        
        // Prevent duplicate buttons
        if (modal.querySelector('.mcd-copy-image-url')) return;

        // Find the HTML code block
        var codeBlock = modal.querySelector('pre code, .creative-code, [class*="code"]');
        if (!codeBlock) return;

        var htmlCode = codeBlock.textContent || codeBlock.innerText;
        if (!htmlCode) return;

        // Extract URLs from HTML
        var hrefMatch = htmlCode.match(/href=["']([^"']+)["']/i);
        var srcMatch = htmlCode.match(/src=["']([^"']+)["']/i);

        if (!hrefMatch || !srcMatch) return;

        var linkUrl = hrefMatch[1];  // e.g. https://site.com/ref/10/
        var imageUrl = srcMatch[1];  // e.g. https://site.com/wp-content/uploads/banner.jpg

        // Create button container
        var btnContainer = document.createElement('div');
        btnContainer.className = 'mcd-copy-buttons';
        btnContainer.style.cssText = 'margin-top:12px;padding:12px;background:#0a0514;border:1px solid #1f2b47;border-radius:8px;';

        // Label
        var label = document.createElement('div');
        label.textContent = 'For Email (Gmail, Outlook, etc.)';
        label.style.cssText = 'color:#a0b3d6;font-size:13px;margin-bottom:10px;font-weight:600;';
        btnContainer.appendChild(label);

        // Button styles
        var btnStyle = 'display:inline-block;padding:8px 16px;margin:4px;border:1px solid #44f80c;border-radius:6px;background:transparent;color:#44f80c;font-size:13px;cursor:pointer;transition:all 0.2s;font-weight:600;';
        var btnHoverStyle = 'background:#44f80c;color:#0a0514;';

        // --- Button 1: Copy Image URL ---
        var btn1 = document.createElement('button');
        btn1.className = 'mcd-copy-image-url';
        btn1.textContent = '📋 Copy Image URL';
        btn1.style.cssText = btnStyle;
        btn1.onmouseenter = function() { this.style.cssText = btnStyle + btnHoverStyle; };
        btn1.onmouseleave = function() { this.style.cssText = btnStyle; };
        btn1.onclick = function() {
            copyToClipboard(imageUrl);
            showFeedback(this, 'Copied!');
        };
        btnContainer.appendChild(btn1);

        // --- Button 2: Copy My Link ---
        var btn2 = document.createElement('button');
        btn2.className = 'mcd-copy-my-link';
        btn2.textContent = '📋 Copy My Link';
        btn2.style.cssText = btnStyle;
        btn2.onmouseenter = function() { this.style.cssText = btnStyle + btnHoverStyle; };
        btn2.onmouseleave = function() { this.style.cssText = btnStyle; };
        btn2.onclick = function() {
            copyToClipboard(linkUrl);
            showFeedback(this, 'Copied!');
        };
        btnContainer.appendChild(btn2);

        // --- Button 3: Copy for Email ---
        var btn3 = document.createElement('button');
        btn3.className = 'mcd-copy-for-email';
        btn3.textContent = '📧 Copy for Email';
        btn3.style.cssText = 'display:block;padding:8px 16px;margin:8px 4px 4px;border:1px solid #38bdf8;border-radius:6px;background:transparent;color:#38bdf8;font-size:13px;cursor:pointer;transition:all 0.2s;font-weight:600;';
        var btn3Hover = 'background:#38bdf8;color:#0a0514;';
        btn3.onmouseenter = function() { this.style.cssText = btn3.style.cssText + btn3Hover; };
        btn3.onmouseleave = function() { this.style.cssText = btn3.style.cssText; };
        btn3.onclick = function() {
            var emailText = 'Image URL:\n' + imageUrl + '\n\nYour Affiliate Link:\n' + linkUrl + '\n\nGmail Steps:\n1. Insert Photo > By URL > paste Image URL\n2. Click image > Link icon > paste Affiliate Link';
            copyToClipboard(emailText);
            showFeedback(this, 'Email guide copied!');
        };
        btnContainer.appendChild(btn3);

        // --- Insert into modal ---
        var insertAfter = codeBlock.closest('.creative-code-wrapper, .creative-code, [class*="code"]');
        if (insertAfter && insertAfter.parentNode) {
            insertAfter.parentNode.insertBefore(btnContainer, insertAfter.nextSibling);
        } else if (codeBlock.parentNode) {
            codeBlock.parentNode.insertBefore(btnContainer, codeBlock.nextSibling);
        }
    }

    // Utility: Copy to clipboard
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text);
        } else {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
    }

    // Utility: Show feedback on button
    function showFeedback(btn, msg) {
        var original = btn.textContent;
        btn.textContent = msg;
        btn.style.borderColor = '#44f80c';
        setTimeout(function() {
            btn.textContent = original;
            btn.style.borderColor = '';
        }, 1500);
    }

    // Watch for modal to appear
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                setTimeout(initCopyButtons, 200);
            }
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Also try on click events
    document.addEventListener('click', function() {
        setTimeout(initCopyButtons, 300);
    });

})();
