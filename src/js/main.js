// Main JavaScript file
document.addEventListener('DOMContentLoaded', function () {

    // Off-canvas menu toggle
    const offCanvasToggleButtons = document.querySelectorAll('.toggle--off-canvas--menu');
    const offCanvas = document.querySelector('.off-canvas--menu');

    if (offCanvasToggleButtons.length > 0 && offCanvas) {
        offCanvasToggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                offCanvas.classList.toggle('off-canvas--visible');
            });
        });
    }

    // Native share functionality
    const shareButtons = document.querySelectorAll('.label--share');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', async () => {
            // Check if Web Share API is supported
            if (navigator.share) {
                try {
                    const shareData = {
                        title: document.title,
                        text: document.querySelector('meta[name="description"]')?.content || 'Check out this article',
                        url: window.location.href
                    };
                    
                    await navigator.share(shareData);
                } catch (error) {
                    console.log('Share cancelled or failed:', error);
                }
            } else {
                // Fallback for browsers that don't support Web Share API
                // Copy URL to clipboard
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    alert('Link copied to clipboard!');
                } catch (error) {
                    console.log('Failed to copy to clipboard:', error);
                    // Fallback: prompt user to copy manually
                    prompt('Copy this link:', window.location.href);
                }
            }
        });
    });
});