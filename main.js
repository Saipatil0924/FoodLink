// In main.js
// Handles animations and smooth scrolling for the landing page.

document.addEventListener('DOMContentLoaded', function() {
    // --- Counter Animation (Corrected Logic) ---
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        // This function handles the animation for a single counter.
        const updateCounter = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / 200; // Animation speed

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCounter, 10); // Update every 10ms
            } else {
                counter.innerText = target;
            }
        };
        // Start the animation.
        updateCounter();
    });

    // --- Smooth Scrolling (No changes needed, already correct) ---
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Offset for sticky header
                    behavior: 'smooth'
                });
            }
        });
    });
});