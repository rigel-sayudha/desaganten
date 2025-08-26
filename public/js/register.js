// Register Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('form[method="POST"][action*="register"]');
    
    if (registerForm) {
        // Add submit event listener
        registerForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                // Disable button to prevent double submission
                submitBtn.disabled = true;
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
        
        // Handle browser back/forward button
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Reset form state if page is loaded from cache
                const submitBtn = registerForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            }
        });
    }
    
    // Auto-hide error messages after 10 seconds
    const errorMessages = document.querySelectorAll('.bg-red-50');
    if (errorMessages.length > 0) {
        setTimeout(() => {
            errorMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 10000);
    }
});
