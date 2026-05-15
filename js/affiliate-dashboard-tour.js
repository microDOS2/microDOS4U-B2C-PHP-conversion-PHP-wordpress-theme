/**
 * microDOS(2) Affiliate Dashboard Tour
 * =====================================
 * An interactive Shepherd.js walkthrough that guides new affiliates
 * through every section of their dashboard. Auto-launches on first visit.
 *
 * Features:
 * - Auto-detects if AffiliateWP dashboard is present
 * - Stores "tour completed" / "tour skipped" in localStorage
 * - Accessible via "Take a Tour" button and floating help icon
 * - 10 steps covering all dashboard tabs
 * - Dark themed to match microDOS(2) brand
 *
 * @version 1.0.0
 * @package microDOS4U
 */
(function() {
    'use strict';

    // ============================================
    // CONFIGURATION
    // ============================================
    var CONFIG = {
        // localStorage keys
        STORAGE_KEY_COMPLETED: 'microdos_tour_completed',
        STORAGE_KEY_SKIPPED:   'microdos_tour_skipped',
        STORAGE_KEY_DISMISSED: 'microdos_help_dismissed_at',
        STORAGE_KEY_TOUR_STEP: 'microdos_tour_step',

        // Timing
        AUTO_LAUNCH_DELAY: 1200,     // ms to wait before auto-launching
        HELP_BUTTON_HIDE_DAYS: 30,   // days to hide help button after dismiss

        // Shepherd theme colors (match microDOS(2) brand)
        THEME: {
            bg:          '#150f24',
            text:        '#d1d5db',
            textStrong:  '#ffffff',
            accent:      '#44f80c',
            accentHover: '#3de00b',
            border:      '#1f2b47',
            danger:      '#ef4444'
        }
    };

    // ============================================
    // DETECTION: Are we on the affiliate dashboard?
    // ============================================
    function isAffiliateDashboard() {
        // Check for AffiliateWP dashboard elements
        var hasAffiliateArea = document.querySelector('.affwp-wrap') !== null;
        var hasDashboardTabs = document.querySelector('.affwp-tab-content') !== null;
        var hasAffiliateUrl  = document.querySelector('.affwp-referral-url') !== null;
        var urlHasTab        = window.location.search.indexOf('tab=') !== -1;

        return hasAffiliateArea || hasDashboardTabs || hasAffiliateUrl || urlHasTab;
    }

    function isMainDashboardTab() {
        // Only auto-launch on the main dashboard tab (no ?tab= parameter)
        return !window.location.search.match(/[?&]tab=/);
    }

    // ============================================
    // LOCALSTORAGE HELPERS
    // ============================================
    function storageGet(key) {
        try {
            return localStorage.getItem(key);
        } catch (e) { return null; }
    }

    function storageSet(key, value) {
        try {
            localStorage.setItem(key, value);
        } catch (e) {}
    }

    function storageRemove(key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {}
    }

    function shouldAutoLaunch() {
        // Don't auto-launch if already completed or skipped
        if (storageGet(CONFIG.STORAGE_KEY_COMPLETED)) return false;
        if (storageGet(CONFIG.STORAGE_KEY_SKIPPED)) return false;
        return true;
    }

    function wasTourStarted() {
        return !!storageGet(CONFIG.STORAGE_KEY_TOUR_STEP);
    }

    // ============================================
    // HELP BUTTON (floating, bottom-right)
    // ============================================
    function injectFloatingHelpButton() {
        // Check if dismissed recently
        var dismissedAt = storageGet(CONFIG.STORAGE_KEY_DISMISSED);
        if (dismissedAt) {
            var daysSince = (Date.now() - parseInt(dismissedAt, 10)) / (1000 * 60 * 60 * 24);
            if (daysSince < CONFIG.HELP_BUTTON_HIDE_DAYS) return;
        }

        // Don't show if already on the guide page
        if (window.location.pathname.indexOf('affiliate-dashboard-guide') !== -1) return;

        // Check if button already exists
        if (document.getElementById('microdos-floating-help')) return;

        var wrapper = document.createElement('div');
        wrapper.id = 'microdos-floating-help';
        wrapper.innerHTML =
            '<button id="microdos-help-btn" title="Need Help? Take a tour" aria-label="Need Help? Take a tour">' +
                '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                    '<circle cx="12" cy="12" r="10"/>' +
                    '<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>' +
                    '<line x1="12" y1="17" x2="12.01" y2="17"/>' +
                '</svg>' +
            '</button>' +
            '<button id="microdos-help-close" title="Dismiss help button" aria-label="Dismiss">' +
                '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">' +
                    '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>' +
                '</svg>' +
            '</button>';

        // Styles
        var style = document.createElement('style');
        style.textContent =
            '#microdos-floating-help {' +
                'position: fixed;' +
                'bottom: 24px;' +
                'right: 24px;' +
                'z-index: 9999;' +
                'display: flex;' +
                'align-items: center;' +
                'gap: 8px;' +
                'animation: microdos-help-fade-in 0.3s ease;' +
            '}' +
            '@keyframes microdos-help-fade-in {' +
                'from { opacity: 0; transform: translateY(10px); }' +
                'to   { opacity: 1; transform: translateY(0); }' +
            '}' +
            '#microdos-help-btn {' +
                'width: 52px;' +
                'height: 52px;' +
                'border-radius: 50%;' +
                'background: linear-gradient(135deg, #44f80c, #3ad60a);' +
                'color: #0a0514;' +
                'border: none;' +
                'cursor: pointer;' +
                'display: flex;' +
                'align-items: center;' +
                'justify-content: center;' +
                'box-shadow: 0 4px 16px rgba(68,248,12,0.3);' +
                'transition: transform 0.2s, box-shadow 0.2s;' +
            '}' +
            '#microdos-help-btn:hover {' +
                'transform: scale(1.08);' +
                'box-shadow: 0 6px 24px rgba(68,248,12,0.45);' +
            '}' +
            '#microdos-help-close {' +
                'width: 24px;' +
                'height: 24px;' +
                'border-radius: 50%;' +
                'background: rgba(100,116,139,0.2);' +
                'color: #94a3b8;' +
                'border: 1px solid rgba(100,116,139,0.3);' +
                'cursor: pointer;' +
                'display: flex;' +
                'align-items: center;' +
                'justify-content: center;' +
                'opacity: 0.7;' +
                'transition: opacity 0.2s;' +
                'padding: 0;' +
            '}' +
            '#microdos-help-close:hover { opacity: 1; background: rgba(239,68,68,0.15); color: #ef4444; }';

        document.head.appendChild(style);
        document.body.appendChild(wrapper);

        // Event listeners
        document.getElementById('microdos-help-btn').addEventListener('click', function() {
            launchTour(true); // true = user-initiated (not auto)
        });

        document.getElementById('microdos-help-close').addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.style.opacity = '0';
            wrapper.style.transform = 'translateY(10px)';
            setTimeout(function() {
                wrapper.remove();
            }, 300);
            storageSet(CONFIG.STORAGE_KEY_DISMISSED, Date.now().toString());
        });
    }

    // ============================================
    // SHEPHERD THEME INJECTION
    // ============================================
    function injectShepherdTheme() {
        var existing = document.getElementById('microdos-shepherd-theme');
        if (existing) return;

        var css =
            /* Shepherd dark theme matching microDOS(2) */
            '.shepherd-element {' +
                'background: #150f24 !important;' +
                'border: 1px solid #1f2b47 !important;' +
                'border-radius: 12px !important;' +
                'box-shadow: 0 16px 48px rgba(0,0,0,0.5) !important;' +
                'color: #d1d5db !important;' +
                'max-width: 380px !important;' +
            '}' +
            '.shepherd-text {' +
                'color: #d1d5db !important;' +
                'font-size: 14px !important;' +
                'line-height: 1.6 !important;' +
                'padding: 20px 24px 0 !important;' +
            '}' +
            '.shepherd-text h3 {' +
                'color: #ffffff !important;' +
                'font-size: 16px !important;' +
                'font-weight: 700 !important;' +
                'margin: 0 0 10px !important;' +
            '}' +
            '.shepherd-text p {' +
                'margin: 0 0 12px !important;' +
            '}' +
            '.shepherd-text p:last-child {' +
                'margin-bottom: 0 !important;' +
            '}' +
            '.shepherd-footer {' +
                'padding: 16px 24px 20px !important;' +
                'display: flex;' +
                'justify-content: space-between;' +
                'align-items: center;' +
            '}' +
            '.shepherd-button {' +
                'padding: 8px 18px !important;' +
                'border-radius: 6px !important;' +
                'font-size: 13px !important;' +
                'font-weight: 600 !important;' +
                'cursor: pointer !important;' +
                'transition: opacity 0.2s !important;' +
            '}' +
            '.shepherd-button:hover {' +
                'opacity: 0.85 !important;' +
            '}' +
            '.shepherd-button.shepherd-button-primary {' +
                'background: #44f80c !important;' +
                'color: #0a0514 !important;' +
                'border: none !important;' +
            '}' +
            '.shepherd-button:not(.shepherd-button-primary) {' +
                'background: transparent !important;' +
                'color: #94a3b8 !important;' +
                'border: 1px solid #1f2b47 !important;' +
            '}' +
            '.shepherd-button:not(.shepherd-button-primary):hover {' +
                'background: rgba(68,248,12,0.05) !important;' +
                'color: #44f80c !important;' +
            '}' +
            '.shepherd-cancel-icon {' +
                'color: #64748b !important;' +
                'font-size: 20px !important;' +
                'top: 12px !important;' +
                'right: 12px !important;' +
            '}' +
            '.shepherd-cancel-icon:hover {' +
                'color: #ef4444 !important;' +
            '}' +
            '.shepherd-arrow::before {' +
                'background: #150f24 !important;' +
                'border: 1px solid #1f2b47 !important;' +
            '}' +
            '.shepherd-has-title .shepherd-content .shepherd-header {' +
                'background: transparent !important;' +
                'padding: 20px 24px 0 !important;' +
            '}' +
            '.shepherd-title {' +
                'color: #44f80c !important;' +
                'font-size: 16px !important;' +
                'font-weight: 700 !important;' +
            '}' +
            '.shepherd-progress {' +
                'color: #64748b !important;' +
                'font-size: 12px !important;' +
                'margin-right: auto;' +
                'padding-right: 12px;' +
            '}';

        var style = document.createElement('style');
        style.id = 'microdos-shepherd-theme';
        style.textContent = css;
        document.head.appendChild(style);
    }

    // ============================================
    // TOUR STEP DEFINITIONS
    // ============================================
    function getTourSteps() {
        var guideUrl = '/';
        var guidePage = document.querySelector('a[href*="affiliate-dashboard-guide"]');
        if (guidePage) guideUrl = guidePage.getAttribute('href');

        return [
            {
                id: 'step-welcome',
                title: 'Welcome to Your Dashboard!',
                text: '<p>This is your affiliate command center. Every stat, chart, and tool you need is here. Let us show you around in <strong>10 quick steps</strong>.</p>',
                attachTo: { element: '.affwp-wrap', on: 'bottom' },
                buttons: [
                    { text: 'Skip Tour', action: function() { skipTour(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Start Tour →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-referral-url',
                title: 'Your Referral Link',
                text: '<p>This is your money link. Copy it and share it anywhere — social media, email, blog, QR code. When someone clicks and buys within 45 days, you earn <strong>20% commission</strong>.</p>',
                attachTo: { element: '.affwp-referral-url, .affwp-url', on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-tabs',
                title: 'Dashboard Tabs',
                text: '<p>Click any tab to explore a different section:</p>' +
                      '<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li><strong>Affiliate URLs</strong> — Custom links & QR codes</li>' +
                      '<li><strong>Statistics</strong> — Detailed numbers</li>' +
                      '<li><strong>Graphs</strong> — Visual trends</li>' +
                      '<li><strong>Referrals</strong> — Your sales</li>' +
                      '<li><strong>Visits</strong> — Click tracking</li>' +
                      '<li><strong>Creatives</strong> — Banners & ads</li>' +
                      '<li><strong>Payouts</strong> — Payments</li>' +
                      '<li><strong>Settings</strong> — Profile & payment email</li>' +
                      '</ul>',
                attachTo: { element: '.affwp-tabs, .affwp-tab-wrapper', on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-stats',
                title: 'Your Stats',
                text: '<p>These cards show your performance at a glance:</p>' +
                      '<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li><strong>Earnings</strong> — Total money earned</li>' +
                      '<li><strong>Paid</strong> — Already sent to you</li>' +
                      '<li><strong>Unpaid</strong> — Coming next payout</li>' +
                      '<li><strong>Conversion Rate</strong> — Clicks that bought</li>' +
                      '</ul>',
                attachTo: { element: '.affwp-stats, .affwp-dashboard-stats, [class*="stats"]', on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-referrals',
                title: 'Referral Statuses',
                text: '<p>Every sale goes through statuses:</p>' +
                      '<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li><span style="color:#ffaa00">● Pending</span> — Order processing (24-48h)</li>' +
                      '<li><span style="color:#60a5fa">● Unpaid</span> — Confirmed, awaiting payout</li>' +
                      '<li><span style="color:#44f80c">● Paid</span> — Money sent to you</li>' +
                      '<li><span style="color:#ef4444">● Rejected</span> — Refunded or cancelled</li>' +
                      '</ul>',
                attachTo: { element: '.affwp-referrals, [class*="referrals"]', on: 'top' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-creatives',
                title: 'Marketing Materials',
                text: '<p>Pre-made banners and text ads with your link <strong>already built in</strong>. Click "Copy Link" to grab the code, then paste into your social post or email. No design work needed.</p>',
                attachTo: { element: 'a[href*="tab=creatives"], .affwp-creatives', on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-visits',
                title: 'Tracking Visits',
                text: '<p>See who clicked your link and where they came from. Check this 24 hours after posting to see which platforms drive the most traffic. Use this data to double down on what works.</p>',
                attachTo: { element: 'a[href*="tab=visits"], .affwp-visits', on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-graphs',
                title: 'Growth Graphs',
                text: '<p>Watch your earnings and referral count grow over time. Use the date filters (Today, This Week, This Month) to spot trends. Spikes usually happen right after you post on social media.</p>',
                attachTo: { element: 'a[href*="tab=graphs"], .affwp-graphs', on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-payouts',
                title: 'Getting Paid',
                text: '<p>Payouts happen automatically on the <strong>1st of every month</strong> via PayPal. You need at least <strong>$50</strong> to trigger a payout. Make sure your payment email in Settings is correct, and submit your <strong>W-9</strong> (US affiliates).</p>',
                attachTo: { element: 'a[href*="tab=payouts"], .affwp-payouts', on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-finish',
                title: 'You Are Ready!',
                text: '<p>That is everything. Here is your quick start:</p>' +
                      '<ol style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li>Copy your referral link</li>' +
                      '<li>Grab a banner from Creatives</li>' +
                      '<li>Post with a personal recommendation</li>' +
                      '<li>Check Visits tomorrow</li>' +
                      '</ol>' +
                      '<p style="margin-top:10px;font-size:13px;">Need a refresher? Visit the <a href="' + guideUrl + '" style="color:#44f80c;font-weight:600;">Dashboard Guide</a> anytime.</p>',
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Done!', action: function() { completeTour(); }, classes: 'shepherd-button-primary' }
                ]
            }
        ];
    }

    // ============================================
    // TOUR LIFECYCLE
    // ============================================
    var tour = null;

    function buildTour() {
        injectShepherdTheme();

        tour = new Shepherd.Tour({
            defaultStepOptions: {
                cancelIcon: { enabled: true },
                scrollTo: { behavior: 'smooth', block: 'center' },
                when: {
                    show: function() {
                        var currentStep = tour.steps.indexOf(tour.getCurrentStep()) + 1;
                        var totalSteps = tour.steps.length;
                        var progressEl = document.createElement('span');
                        progressEl.className = 'shepherd-progress';
                        progressEl.textContent = currentStep + ' / ' + totalSteps;

                        var footer = document.querySelector('.shepherd-footer');
                        if (footer) {
                            var existingProgress = footer.querySelector('.shepherd-progress');
                            if (existingProgress) existingProgress.remove();
                            footer.insertBefore(progressEl, footer.firstChild);
                        }

                        // Save current step for resume
                        storageSet(CONFIG.STORAGE_KEY_TOUR_STEP, currentStep.toString());
                    }
                }
            },
            useModalOverlay: true
        });

        var steps = getTourSteps();
        steps.forEach(function(step) {
            // Fallback: if attachTo element not found, make it a center modal
            if (step.attachTo && step.attachTo.element) {
                var el = document.querySelector(step.attachTo.element);
                if (!el) {
                    delete step.attachTo; // Center modal fallback
                }
            }
            tour.addStep(step);
        });

        // On cancel (X button or Escape)
        tour.on('cancel', function() {
            if (!storageGet(CONFIG.STORAGE_KEY_COMPLETED)) {
                storageSet(CONFIG.STORAGE_KEY_SKIPPED, 'true');
            }
            storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
        });

        return tour;
    }

    function launchTour(userInitiated) {
        if (typeof Shepherd === 'undefined') {
            console.warn('[microDOS Tour] Shepherd.js not loaded');
            return;
        }

        if (tour) {
            tour.complete(); // Close any existing tour
            tour = null;
        }

        // If user clicked "Take a Tour", clear skipped status
        if (userInitiated) {
            storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
            storageRemove(CONFIG.STORAGE_KEY_COMPLETED);
        }

        buildTour();
        tour.start();
    }

    function completeTour() {
        storageSet(CONFIG.STORAGE_KEY_COMPLETED, 'true');
        storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
        storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
        if (tour) tour.complete();
    }

    function skipTour() {
        storageSet(CONFIG.STORAGE_KEY_SKIPPED, 'true');
        storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
        if (tour) tour.complete();
    }

    // ============================================
    // PUBLIC API (exposed to window)
    // ============================================
    window.microDOSAffiliateTour = {
        launch: launchTour,
        reset: function() {
            storageRemove(CONFIG.STORAGE_KEY_COMPLETED);
            storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
            storageRemove(CONFIG.STORAGE_KEY_DISMISSED);
            storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
            console.log('[microDOS Tour] Tour state reset. Refresh to start over.');
        }
    };

    // ============================================
    // INITIALIZATION
    // ============================================
    function init() {
        // Only run on affiliate dashboard
        if (!isAffiliateDashboard()) return;

        // Inject floating help button
        injectFloatingHelpButton();

        // Auto-launch on first visit (main dashboard tab only)
        if (shouldAutoLaunch() && isMainDashboardTab()) {
            setTimeout(function() {
                // Double-check we are still on the right page
                if (isMainDashboardTab()) {
                    launchTour(false); // false = auto-launched
                }
            }, CONFIG.AUTO_LAUNCH_DELAY);
        }
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
