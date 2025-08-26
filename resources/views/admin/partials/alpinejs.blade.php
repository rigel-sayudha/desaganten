<!-- Alpine.js for dropdowns and interactivity in admin dashboard -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        isOpen: window.innerWidth >= 1024,
        isMobile: window.innerWidth < 1024,
        
        toggle() {
            this.isOpen = !this.isOpen;
        },
        
        open() {
            this.isOpen = true;
        },
        
        close() {
            this.isOpen = false;
        },
        
        init() {
            // Update mobile state on resize
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
                if (window.innerWidth >= 1024) {
                    this.isOpen = true;
                } else {
                    this.isOpen = false;
                }
            });
        }
    });
    
    // Initialize on load
    Alpine.store('sidebar').init();
});

// Mobile menu overlay handling
document.addEventListener('DOMContentLoaded', function() {
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('#adminSidebar');
        const hamburger = document.querySelector('#hamburgerButton');
        const isMobile = window.innerWidth < 1024;
        
        if (isMobile && sidebar && hamburger) {
            const sidebarRect = sidebar.getBoundingClientRect();
            const isClickInSidebar = event.clientX >= sidebarRect.left && 
                                   event.clientX <= sidebarRect.right && 
                                   event.clientY >= sidebarRect.top && 
                                   event.clientY <= sidebarRect.bottom;
            
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (!isClickInSidebar && !isClickOnHamburger && Alpine.store('sidebar').isOpen) {
                Alpine.store('sidebar').close();
            }
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
