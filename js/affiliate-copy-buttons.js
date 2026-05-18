/**
 * microDOS Affiliate Copy Buttons
 *
 * One-click copy functionality for creative copy buttons.
 * Works with the template override in affiliatewp/creative.php
 *
 * Uses native Clipboard API with execCommand fallback.
 */
(function() {
    'use strict';
    if (window.microdosCopyButtonsLoaded) return;
    window.microdosCopyButtonsLoaded = true;

    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text).then(function() {
                return true;
            }).catch(function() {
                return fallbackCopy(text);
            });
        }
        return fallbackCopy(text);
    }

    function fallbackCopy(text) {
        return new Promise(function(resolve) {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.cssText = 'position:fixed;top:0;left:0;opacity:0;pointer-events:none;';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                var success = document.execCommand('copy');
                resolve(success);
            } catch (e) {
                resolve(false);
            } finally {
                document.body.removeChild(textarea);
            }
        });
    }

    function showFeedback(feedbackEl) {
        if (!feedbackEl) return;
        feedbackEl.style.display = 'inline-flex';
        feedbackEl.style.opacity = '1';
        setTimeout(function() {
            feedbackEl.style.opacity = '0';
            setTimeout(function() {
                feedbackEl.style.display = 'none';
            }, 300);
        }, 2000);
    }

    function handleCopyClick(button) {
        var uid = button.getAttribute('data-uid');
        if (!uid) return;
        var dataEl = document.getElementById(uid + '-data');
        if (!dataEl) return;
        var textToCopy = dataEl.textContent.trim();
        if (!textToCopy) return;

        var container = button.closest('.microdos-copy-buttons');
        var feedbackEl = container ? container.querySelector('.microdos-copy-feedback') : null;

        copyToClipboard(textToCopy).then(function(success) {
            if (success && feedbackEl) {
                showFeedback(feedbackEl);
            } else if (success) {
                var originalText = button.innerHTML;
                button.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Copied!';
                setTimeout(function() {
                    button.innerHTML = originalText;
                }, 2000);
            }
        });
    }

    function init() {
        document.querySelectorAll('.microdos-copy-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                handleCopyClick(this);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Re-init for dynamically added buttons (React portals)
    var observer = new MutationObserver(function(mutations) {
        var hasNew = false;
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) {
                    if (node.matches && node.matches('.microdos-copy-btn')) hasNew = true;
                    else if (node.querySelector && node.querySelector('.microdos-copy-btn')) hasNew = true;
                }
            });
        });
        if (hasNew) {
            document.querySelectorAll('.microdos-copy-btn:not([data-microdos-init])').forEach(function(btn) {
                btn.setAttribute('data-microdos-init', 'true');
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleCopyClick(this);
                });
            });
        }
    });

    setTimeout(function() {
        var container = document.querySelector('.affwp-affiliate-dashboard') ||
                        document.querySelector('#affwp-affiliate-dashboard') ||
                        document.body;
        observer.observe(container, { childList: true, subtree: true });
    }, 1000);
})();
