/**
 * microDOS Affiliate Portal — Creative Copy Buttons
 *
 * Injects one-click copy buttons into the AffiliateWP Portal's creative modal.
 *
 * The Portal is a React app that renders creatives client-side. Since React
 * controls the DOM, this script uses MutationObserver to detect when the
 * creative modal opens and injects buttons into it.
 *
 * Buttons added:
 * - Copy Image URL: copies the banner image address
 * - Copy My Link: copies the affiliate referral URL
 * - Copy for Email: copies ready-to-paste HTML for Gmail
 *
 * @version 2.0.0
 */
(function() {
    'use strict';

    // Prevent double-loading
    if (window.microdosPortalCopyButtons) return;
    window.microdosPortalCopyButtons = true;

    // Track if we've already processed a modal
    var processedModals = new WeakSet();

    /**
     * Copy text to clipboard
     */
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
            textarea.style.cssText = 'position:fixed;top:0;left:0;opacity:0;pointer-events:none;z-index:-1;';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                resolve(document.execCommand('copy'));
            } catch (e) {
                resolve(false);
            } finally {
                document.body.removeChild(textarea);
            }
        });
    }

    /**
     * Show feedback on a button
     */
    function showButtonFeedback(button) {
        var original = button.innerHTML;
        button.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Copied!';
        button.style.background = 'linear-gradient(135deg, #44f80c 0%, #3adf0b 100%)';
        button.style.color = '#0a0514';
        button.style.borderColor = '#44f80c';

        setTimeout(function() {
            button.innerHTML = original;
            button.style.background = '';
            button.style.color = '';
            button.style.borderColor = '';
        }, 2000);
    }

    /**
     * Parse the creative HTML code block to extract URLs
     */
    function parseCreativeCode(codeText) {
        var result = {
            imageUrl: '',
            linkUrl: '',
            fullHtml: codeText.trim()
        };

        if (!codeText) return result;

        // Extract href from <a href="...">
        var hrefMatch = codeText.match(/href=["']([^"']+)["']/i);
        if (hrefMatch) {
            result.linkUrl = hrefMatch[1];
        }

        // Extract src from <img src="...">
        var srcMatch = codeText.match(/src=["']([^"']+)["']/i);
        if (srcMatch) {
            result.imageUrl = srcMatch[1];
        }

        return result;
    }

    /**
     * Build email-friendly HTML
     */
    function buildEmailHtml(imageUrl, linkUrl, altText) {
        if (imageUrl && linkUrl) {
            return '<a href="' + linkUrl + '">\n  <img src="' + imageUrl + '" alt="' + (altText || 'microDOS(2)') + '" style="max-width:100%;height:auto;" />\n</a>';
        }
        return linkUrl || '';
    }

    /**
     * Create a copy button element
     */
    function createButton(label, iconSvg, onClick) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'microdos-portal-copy-btn';
        btn.innerHTML = iconSvg + ' ' + label;
        btn.style.cssText = 'display:inline-flex;align-items:center;gap:6px;padding:8px 14px;' +
            'background:linear-gradient(135deg,#1a1030 0%,#0d0818 100%);' +
            'border:1px solid rgba(68,248,12,0.35);border-radius:6px;' +
            'color:#e2e8f0;font-family:inherit;font-size:13px;font-weight:500;' +
            'cursor:pointer;transition:all 0.2s ease;margin:4px;line-height:1;' +
            'white-space:nowrap;';

        btn.addEventListener('mouseenter', function() {
            btn.style.background = 'linear-gradient(135deg,#44f80c 0%,#3adf0b 100%)';
            btn.style.borderColor = '#44f80c';
            btn.style.color = '#0a0514';
            btn.style.transform = 'translateY(-1px)';
            btn.style.boxShadow = '0 4px 12px rgba(68,248,12,0.25)';
        });

        btn.addEventListener('mouseleave', function() {
            btn.style.background = 'linear-gradient(135deg,#1a1030 0%,#0d0818 100%)';
            btn.style.borderColor = 'rgba(68,248,12,0.35)';
            btn.style.color = '#e2e8f0';
            btn.style.transform = '';
            btn.style.boxShadow = '';
        });

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            onClick(btn);
        });

        return btn;
    }

    /**
     * Inject copy buttons into the creative modal
     */
    function injectButtons(modal) {
        // Prevent double-processing
        if (processedModals.has(modal)) return;
        processedModals.add(modal);

        // Find the code block (pre > code or similar)
        var codeBlock = modal.querySelector('pre code') ||
                        modal.querySelector('pre') ||
                        modal.querySelector('.creative_card_code_block');
        if (!codeBlock) return;

        var codeText = codeBlock.textContent || '';
        var parsed = parseCreativeCode(codeText);

        if (!parsed.linkUrl && !parsed.imageUrl) return;

        // Find the "Copy to clipboard" button to position our buttons after it
        var existingCopyBtn = modal.querySelector('button[class*="copy" i]');

        // Create button container
        var container = document.createElement('div');
        container.className = 'microdos-portal-copy-buttons';
        container.style.cssText = 'display:flex;flex-wrap:wrap;gap:4px;' +
            'margin-top:16px;padding-top:12px;' +
            'border-top:1px solid rgba(68,248,12,0.2);';

        // Get alt text from the code
        var altMatch = codeText.match(/alt=["']([^"']*)["']/i);
        var altText = altMatch ? altMatch[1] : 'microDOS(2)';

        var emailHtml = buildEmailHtml(parsed.imageUrl, parsed.linkUrl, altText);

        // --- Button 1: Copy Image URL (only if there's an image) ---
        if (parsed.imageUrl) {
            var imgIcon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
            container.appendChild(createButton('Copy Image URL', imgIcon, function(btn) {
                copyToClipboard(parsed.imageUrl).then(function(ok) {
                    if (ok) showButtonFeedback(btn);
                });
            }));
        }

        // --- Button 2: Copy My Link ---
        if (parsed.linkUrl) {
            var linkIcon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>';
            container.appendChild(createButton('Copy My Link', linkIcon, function(btn) {
                copyToClipboard(parsed.linkUrl).then(function(ok) {
                    if (ok) showButtonFeedback(btn);
                });
            }));
        }

        // --- Button 3: Copy for Email ---
        if (emailHtml) {
            var emailIcon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>';
            container.appendChild(createButton('Copy for Email', emailIcon, function(btn) {
                copyToClipboard(emailHtml).then(function(ok) {
                    if (ok) showButtonFeedback(btn);
                });
            }));
        }

        // Insert after the existing "Copy to clipboard" button, or after the code block
        if (existingCopyBtn && existingCopyBtn.parentNode) {
            existingCopyBtn.parentNode.insertBefore(container, existingCopyBtn.nextSibling);
        } else {
            codeBlock.parentNode.insertBefore(container, codeBlock.nextSibling);
        }
    }

    /**
     * Check if an element is the creative modal
     */
    function isCreativeModal(el) {
        // Check for Portal creative modal indicators
        if (!el || el.nodeType !== 1) return false;

        // Check by class names (the Portal uses specific class patterns)
        var className = el.className || '';
        if (typeof className === 'string') {
            if (className.indexOf('creative') !== -1 && className.indexOf('modal') !== -1) return true;
            if (className.indexOf('creative_card') !== -1) return true;
        }

        // Check if it contains creative-specific content
        if (el.querySelector && (
            el.querySelector('.creative_card_code_block') ||
            el.querySelector('pre code') && el.querySelector('button[class*="copy" i]') ||
            el.querySelector('[class*="creative"][class*="modal"]')
        )) {
            return true;
        }

        // Check by child elements with creative classes
        var creativeElements = el.querySelectorAll ? el.querySelectorAll('[class*="creative_card"]') : [];
        if (creativeElements.length > 0) return true;

        return false;
    }

    /**
     * Check for modals in the DOM
     */
    function scanForModals() {
        // The Portal renders modals as top-level elements or inside portal containers
        var candidates = document.querySelectorAll([
            '[class*="modal"]:not([class*="modal-backdrop"])',
            '[class*="dialog"][class*="creative"]',
            '[class*="creative_card"]',
            '[role="dialog"]'
        ].join(','));

        candidates.forEach(function(el) {
            if (isCreativeModal(el)) {
                injectButtons(el);
            }
        });

        // Also check for recently opened modals that might not have "modal" in class
        var allElements = document.querySelectorAll('div[class*="creative"]');
        allElements.forEach(function(el) {
            if (el.querySelector('pre') && el.querySelector('button') && !processedModals.has(el)) {
                injectButtons(el);
            }
        });
    }

    /**
     * Initialize MutationObserver to watch for modal appearance
     */
    function initObserver() {
        var observer = new MutationObserver(function(mutations) {
            var shouldScan = false;

            mutations.forEach(function(mutation) {
                // Check added nodes
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (isCreativeModal(node)) {
                            injectButtons(node);
                            shouldScan = true;
                        } else if (node.querySelector && (
                            node.querySelector('pre code') ||
                            node.querySelector('[class*="creative"]')
                        )) {
                            shouldScan = true;
                        }
                    }
                });

                // Check attribute changes on existing nodes (class changes)
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (isCreativeModal(mutation.target)) {
                        injectButtons(mutation.target);
                    }
                }
            });

            if (shouldScan) {
                scanForModals();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'style']
        });
    }

    /**
     * Initialize
     */
    function init() {
        // Scan immediately in case modal is already open
        scanForModals();

        // Start watching for future modal opens
        initObserver();

        // Also scan periodically for the first 10 seconds (React lazy-loading)
        var scanCount = 0;
        var intervalId = setInterval(function() {
            scanForModals();
            scanCount++;
            if (scanCount >= 20) clearInterval(intervalId);
        }, 500);
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also try after a delay (Portal loads via JS)
    setTimeout(init, 1000);
    setTimeout(init, 2000);
    setTimeout(init, 3500);

})();
