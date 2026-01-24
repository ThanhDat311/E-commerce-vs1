/**
 * Flash Sale Countdown Timer
 * 
 * Manages countdown timers for flash sales across the website
 * Updates display and progress bars in real-time
 */

class FlashSaleCountdown {
    constructor(elementSelector, endTime) {
        this.element = document.querySelector(elementSelector);
        this.endTime = new Date(endTime).getTime();
        this.animationFrameId = null;

        if (this.element) {
            this.start();
        }
    }

    start() {
        const update = () => {
            const now = new Date().getTime();
            const distance = this.endTime - now;

            if (distance < 0) {
                this.displayExpired();
                return;
            }

            this.displayTime(distance);
            this.animationFrameId = requestAnimationFrame(update);
        };

        update();
    }

    displayTime(distance) {
        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const timeDisplay = `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;

        // Update text display
        const timeElement = this.element.querySelector('[data-time]');
        if (timeElement) {
            timeElement.textContent = timeDisplay;
        }

        // Update progress bar
        const progressBar = this.element.querySelector('[data-progress-bar]');
        if (progressBar) {
            const totalDuration = this.endTime - new Date(this.element.dataset.startTime).getTime();
            const remaining = distance;
            const progress = (remaining / totalDuration) * 100;
            progressBar.style.width = Math.max(0, progress) + '%';
        }

        // Add urgency styling when less than 1 hour remaining
        if (distance < (60 * 60 * 1000)) {
            this.element.classList.add('urgency-low');
        }

        // Add critical styling when less than 10 minutes remaining
        if (distance < (10 * 60 * 1000)) {
            this.element.classList.add('urgency-critical');
        }
    }

    displayExpired() {
        const timeElement = this.element.querySelector('[data-time]');
        if (timeElement) {
            timeElement.textContent = 'Sale Ended';
            timeElement.parentElement?.classList.add('sale-ended');
        }

        const progressBar = this.element.querySelector('[data-progress-bar]');
        if (progressBar) {
            progressBar.style.width = '0%';
        }

        if (this.animationFrameId) {
            cancelAnimationFrame(this.animationFrameId);
        }
    }

    stop() {
        if (this.animationFrameId) {
            cancelAnimationFrame(this.animationFrameId);
        }
    }
}

/**
 * Initialize all flash sale countdown timers on the page
 */
function initializeFlashSaleCountdowns() {
    document.querySelectorAll('[data-flash-sale-countdown]').forEach(element => {
        const endTime = element.dataset.flashSaleCountdown;
        if (endTime) {
            new FlashSaleCountdown(`[data-flash-sale-countdown="${endTime}"]`, endTime);
        }
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeFlashSaleCountdowns);

// Re-initialize when dynamic content is added
const observer = new MutationObserver(() => {
    initializeFlashSaleCountdowns();
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { FlashSaleCountdown, initializeFlashSaleCountdowns };
}
