// js/ngo.js
document.addEventListener('DOMContentLoaded', function() {
    // Handle donation claiming
    const claimButtons = document.querySelectorAll('.claim-btn');
    claimButtons.forEach(button => {
        button.addEventListener('click', function() {
            const donationId = this.getAttribute('data-id');
            const donationTitle = this.getAttribute('data-title');
            
            // In a real app, this would open a modal for scheduling
            const pickupTime = prompt(`Enter pickup time for ${donationTitle} (e.g., Today 5 PM):`);
            
            if (pickupTime) {
                const claimData = {
                    donationId: donationId,
                    ngoId: 1, // This would come from session in a real app
                    pickupTime: pickupTime
                };
                
                // In a real app, this would be an API call
                console.log('Claim data:', claimData);
                alert(`Successfully claimed ${donationTitle} for pickup at ${pickupTime}`);
                
                // Update UI
                this.textContent = 'Claimed';
                this.disabled = true;
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline');
            }
        });
    });
    
    // Handle pickup status updates
    const pickupStatusButtons = document.querySelectorAll('.pickup-status-btn');
    pickupStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const pickupId = this.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            
            // In a real app, this would be an API call
            console.log(`Updating pickup ${pickupId} with action: ${action}`);
            
            if (action === 'complete') {
                alert('Pickup marked as completed');
            } else if (action === 'cancel') {
                alert('Pickup cancelled');
            }
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('donation-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const foodCards = document.querySelectorAll('.food-card');
            
            foodCards.forEach(card => {
                const title = card.querySelector('.food-title').textContent.toLowerCase();
                const description = card.querySelector('.food-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});