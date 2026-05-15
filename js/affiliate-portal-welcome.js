/**
 * microDOS(2) Affiliate Portal Welcome Panel
 * ============================================
 * Injects a Getting Started panel into the Affiliate Portal dashboard.
 * Works with AffiliateWP Affiliate Portal v1.3.x (sidebar-based UI).
 *
 * Features:
 * - Injects welcome card above the main content area
 * - Shows referral link with copy button
 * - "Take a Tour" and "View Dashboard Guide" buttons
 * - Auto-detects Portal vs old tabbed interface
 * - Only runs for logged-in affiliates
 *
 * @version 1.0.0
 * @package microDOS4U
 */
(function() {
    'use strict';

    // ============================================
    // CONFIG
    // ============================================
    var CONFIG = {
        // Portal DOM selectors (Affiliate Portal v1.3.x)
        PORTAL_CONTENT: '.affwp-portal-content, .affwp-portal-main, .portal-content, #affwp-portal-content',
        PORTAL_SIDEBAR: '.affwp-portal-sidebar, .portal-sidebar, #affwp-portal-sidebar',
        PORTAL_HEADER: '.affwp-portal-header, .portal-header',

        // Fallback: old tabbed interface
        TAB_CONTENT: '.affwp-wrap, .affwp-tab-content',

        // URLs (set via wp_localize_script)
        GUIDE_URL: window.microDOSPortalData ? window.microDOSPortalData.guideUrl : '/affiliate-dashboard-guide/',
        MG_URL: window.microDOSPortalData ? window.microDOSPortalData.mgUrl : '/marketing-guide/',
        REFERRAL_URL: window.microDOSPortalData ? window.microDOSPortalData.referralUrl : '',

        // localStorage key for "don't show again"
        HIDE_KEY: 'microdos_welcome_hidden',

        // Colors
        COLORS: {
            bg: '#150f24',
            border: '#2d2255',
            accent: '#44f80c',
            pink: '#ff66c4',
            text: '#d1d5db',
            textDim: '#94a3b8',
            cardBg: '#1a1040'
        }
    };

    // ============================================
    // DETECTION
    // ============================================
    function isAffiliatePortal() {
        // Check for Portal-specific elements
        return !!document.querySelector('.affwp-portal, .affiliate-portal, .affwp-portal-sidebar, [class*="portal-sidebar"], [class*="portal-content"]');
    }

    function isOldTabbedInterface() {
        // Check for old tabbed Affiliate Area
        return !!document.querySelector('.affwp-tabs, .affwp-tab-wrapper, .affwp-wrap');
    }

    function shouldShowPanel() {
        // Check if user has dismissed it
        try {
            if (localStorage.getItem(CONFIG.HIDE_KEY) === '1') return false;
        } catch(e) {}
        return true;
    }

    // ============================================
    // CSS INJECTION
    // ============================================
    function injectStyles() {
        var styleId = 'microdos-portal-welcome-styles';
        if (document.getElementById(styleId)) return;

        var css = `
            #microdos-welcome-panel {
                background: linear-gradient(135deg, #150f24, #0a0514);
                border: 1px solid #2d2255;
                border-radius: 12px;
                padding: 24px 28px;
                margin: 0 0 24px 0;
                color: #d1d5db;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-size: 14px;
                line-height: 1.5;
                position: relative;
            }
            #microdos-welcome-panel h3 {
                color: #44f80c;
                font-size: 18px;
                font-weight: 700;
                margin: 0 0 16px 0;
            }
            #microdos-welcome-panel .mcd-ref-box {
                background: rgba(68,248,12,0.06);
                border: 1px solid #44f80c;
                border-radius: 8px;
                padding: 14px 18px;
                margin-bottom: 20px;
            }
            #microdos-welcome-panel .mcd-ref-box strong {
                color: #44f80c;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            #microdos-welcome-panel .mcd-ref-box p {
                color: #94a3b8;
                font-size: 13px;
                margin: 6px 0 10px;
            }
            #microdos-welcome-panel .mcd-ref-box code {
                display: block;
                background: rgba(68,248,12,0.08);
                color: #44f80c;
                padding: 10px 14px;
                border-radius: 6px;
                font-size: 13px;
                word-break: break-all;
                margin: 0 0 10px;
                font-family: monospace;
            }
            #microdos-welcome-panel .mcd-ref-box button {
                padding: 8px 18px;
                background: #44f80c;
                color: #0a0514;
                border: none;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                transition: opacity 0.2s;
            }
            #microdos-welcome-panel .mcd-ref-box button:hover { opacity: 0.85; }
            #microdos-welcome-panel .mcd-section-title {
                color: #ff66c4;
                font-size: 15px;
                font-weight: 700;
                margin: 20px 0 10px;
            }
            #microdos-welcome-panel ol, #microdos-welcome-panel ul {
                color: #94a3b8;
                font-size: 13px;
                line-height: 1.7;
                padding-left: 20px;
                margin: 0 0 16px;
            }
            #microdos-welcome-panel li { margin-bottom: 4px; }
            #microdos-welcome-panel li strong { color: #e2e8f0; }
            #microdos-welcome-panel .mcd-note {
                background: rgba(16,185,129,0.06);
                border-left: 3px solid #10b981;
                border-radius: 0 6px 6px 0;
                padding: 12px 16px;
                margin: 14px 0;
                color: #94a3b8;
                font-size: 13px;
            }
            #microdos-welcome-panel .mcd-note strong { color: #10b981; }
            #microdos-welcome-panel .mcd-buttons {
                display: flex;
                gap: 12px;
                margin-top: 16px;
                flex-wrap: wrap;
            }
            #microdos-welcome-panel .mcd-btn {
                padding: 12px 28px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: opacity 0.2s;
                border: none;
            }
            #microdos-welcome-panel .mcd-btn:hover { opacity: 0.85; }
            #microdos-welcome-panel .mcd-btn-green {
                background: #44f80c;
                color: #0a0514;
            }
            #microdos-welcome-panel .mcd-btn-pink {
                background: #ff66c4;
                color: #fff;
            }
            #microdos-welcome-panel .mcd-dismiss {
                position: absolute;
                top: 12px;
                right: 12px;
                background: none;
                border: 1px solid #475569;
                color: #94a3b8;
                border-radius: 50%;
                width: 28px;
                height: 28px;
                cursor: pointer;
                font-size: 16px;
                line-height: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
                transition: all 0.2s;
            }
            #microdos-welcome-panel .mcd-dismiss:hover {
                background: rgba(239,68,68,0.1);
                color: #ef4444;
                border-color: #ef4444;
            }
            @media (max-width: 600px) {
                #microdos-welcome-panel { padding: 16px; }
                #microdos-welcome-panel .mcd-buttons { flex-direction: column; }
            }
        `;

        var style = document.createElement('style');
        style.id = styleId;
        style.textContent = css;
        document.head.appendChild(style);
    }

    // ============================================
    // PANEL HTML BUILDER
    // ============================================
    function buildPanelHTML() {
        var guideUrl = CONFIG.GUIDE_URL;
        var mgUrl = CONFIG.MG_URL;
        var refUrl = CONFIG.REFERRAL_URL;

        return `
            <button class="mcd-dismiss" title="Hide this panel" aria-label="Hide">&times;</button>
            <h3>Getting Started as a microDOS(2) Affiliate</h3>

            <div class="mcd-ref-box">
                <strong>Your Referral Link</strong>
                <p>Share this link everywhere. When someone clicks and buys, you earn 20%.</p>
                <code id="mcd-ref-url">${escapeHtml(refUrl)}</code>
                <button onclick="copyRefLink(this)">Copy Link</button>
            </div>

            <div class="mcd-section-title">How It Works</div>
            <ol>
                <li><strong>Share your link</strong> — Post on social media, email, anywhere</li>
                <li><strong>Someone clicks</strong> — Tracked to your account</li>
                <li><strong>They buy within 45 days</strong> — Cookie tracks them</li>
                <li><strong>You earn 20%</strong> — Every sale. No cap.</li>
                <li><strong>Get paid monthly</strong> — $50 minimum, 1st of the month</li>
            </ol>

            <div class="mcd-note">
                <strong>Your numbers start at zero.</strong> That is normal. They grow as you share consistently.
            </div>

            <div class="mcd-section-title">Quick Start</div>
            <ol>
                <li>Copy your link above and add to social bios</li>
                <li>Grab a banner from the Creatives tab</li>
                <li>Post with a personal recommendation today</li>
                <li>Check Visits tomorrow to see clicks</li>
            </ol>

            <div class="mcd-buttons">
                <button class="mcd-btn mcd-btn-green" onclick="launchTour()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    Take a Tour
                </button>
                <a href="${escapeHtml(guideUrl)}" class="mcd-btn mcd-btn-pink">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    View Dashboard Guide
                </a>
                ${mgUrl ? '<a href="' + escapeHtml(mgUrl) + '" class="mcd-btn mcd-btn-pink" style="background:#9a02d0;">Marketing Guide</a>' : ''}
            </div>
        `;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ============================================
    // GLOBAL FUNCTIONS (called from HTML onclick)
    // ============================================
    window.copyRefLink = function(btn) {
        var url = document.getElementById('mcd-ref-url').textContent;
        navigator.clipboard.writeText(url).then(function() {
            btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = 'Copy Link'; }, 2000);
        }, function() {
            // Fallback for older browsers
            var ta = document.createElement('textarea');
            ta.value = url;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = 'Copy Link'; }, 2000);
        });
    };

    window.launchTour = function() {
        if (window.microDOSAffiliateTour && window.microDOSAffiliateTour.launch) {
            window.microDOSAffiliateTour.launch(true);
        } else {
            alert('Tour is loading... Please try again in a moment.');
        }
    };

    // ============================================
    // PANEL INJECTION
    // ============================================
    function injectPanel() {
        // Already injected?
        if (document.getElementById('microdos-welcome-panel')) return;

        // Inject CSS
        injectStyles();

        // Build panel
        var panel = document.createElement('div');
        panel.id = 'microdos-welcome-panel';
        panel.innerHTML = buildPanelHTML();

        // Find insertion point
        var insertionPoint = null;
        var container = null;

        if (isAffiliatePortal()) {
            // Portal: try main content area
            container = document.querySelector('.affwp-portal-content') ||
                        document.querySelector('.affwp-portal-main') ||
                        document.querySelector('[class*="portal-content"]') ||
                        document.querySelector('.affwp-portal') ||
                        document.querySelector('.affiliate-portal') ||
                        document.querySelector('main') ||
                        document.querySelector('.content-area');

            if (container) {
                // Insert as first child of content area
                if (container.firstChild) {
                    container.insertBefore(panel, container.firstChild);
                } else {
                    container.appendChild(panel);
                }
                console.log('[microDOS Welcome] Injected into Affiliate Portal');
            } else {
                // Fallback: inject at top of body content
                var bodyFirst = document.body.querySelector('div');
                if (bodyFirst && bodyFirst.parentNode) {
                    bodyFirst.parentNode.insertBefore(panel, bodyFirst);
                } else {
                    document.body.insertBefore(panel, document.body.firstChild);
                }
            }
        } else if (isOldTabbedInterface()) {
            // Old tabbed: use the affwp_affiliate_dashboard_top hook approach
            // This is handled by PHP. JS won't inject here.
            console.log('[microDOS Welcome] Old tabbed interface detected - panel handled by PHP');
            return;
        } else {
            // Unknown interface - try generic content area
            container = document.querySelector('.content-area') ||
                        document.querySelector('main') ||
                        document.querySelector('.site-main') ||
                        document.querySelector('article');
            if (container) {
                container.insertBefore(panel, container.firstChild);
            }
        }

        // Dismiss handler
        var dismissBtn = panel.querySelector('.mcd-dismiss');
        if (dismissBtn) {
            dismissBtn.addEventListener('click', function() {
                panel.style.opacity = '0';
                panel.style.transform = 'translateY(-10px)';
                panel.style.transition = 'all 0.3s ease';
                setTimeout(function() {
                    panel.remove();
                }, 300);
                try {
                    localStorage.setItem(CONFIG.HIDE_KEY, '1');
                } catch(e) {}
            });
        }
    }

    // ============================================
    // SIDEBAR MENU INJECTION (JS Fallback)
    // ============================================
    function injectSidebarMenuLinks() {
        // Only inject if they don't already exist
        if (document.querySelector('.mcd-portal-menu-link')) return;

        var sidebar = document.querySelector('.affwp-portal-sidebar') ||
                      document.querySelector('.portal-sidebar') ||
                      document.querySelector('[class*="portal-sidebar"]') ||
                      document.querySelector('.affwp-portal nav') ||
                      document.querySelector('nav[role="navigation"]');

        if (!sidebar) return;

        var guideUrl = CONFIG.GUIDE_URL;
        var mgUrl = CONFIG.MG_URL;

        // Create menu items
        var items = [
            { url: guideUrl, label: 'Dashboard Guide', icon: '📖' },
            { url: mgUrl, label: 'Marketing Guide', icon: '🚀' }
        ];

        items.forEach(function(item) {
            if (!item.url) return;

            var link = document.createElement('a');
            link.href = item.url;
            link.className = 'mcd-portal-menu-link';
            link.innerHTML = item.icon + ' ' + escapeHtml(item.label);

            // Try to match Portal's existing link styles
            var existingLinks = sidebar.querySelectorAll('a');
            if (existingLinks.length > 0) {
                var firstLink = existingLinks[0];
                link.style.cssText = window.getComputedStyle(firstLink).cssText;
            }

            sidebar.appendChild(link);
        });

        console.log('[microDOS Welcome] Injected sidebar menu links');
    }

    // ============================================
    // INIT
    // ============================================
    function init() {
        // Only run on affiliate pages
        var path = window.location.pathname;
        var isAffiliatePage = path.indexOf('affiliate') !== -1 ||
                              path.indexOf('portal') !== -1 ||
                              document.querySelector('.affwp-portal, .affiliate-portal, .affwp-wrap');

        if (!isAffiliatePage) return;

        // Inject welcome panel
        if (shouldShowPanel()) {
            injectPanel();
        }

        // Inject sidebar links (always)
        injectSidebarMenuLinks();
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also try after a short delay (Portal may load content via JS)
    setTimeout(init, 500);
    setTimeout(init, 1500);

})();
