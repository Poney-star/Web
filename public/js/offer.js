// Simple JavaScript for accordion functionality
document.addEventListener('DOMContentLoaded', function() {
const accordionBtns = document.querySelectorAll('.accordion-btn');

accordionBtns.forEach(btn => {
    btn.addEventListener('click', function() {
    this.classList.toggle('active');
    const content = this.nextElementSibling;
    
    if (content.style.maxHeight) {
        content.style.maxHeight = null;
        this.querySelector('.accordion-icon').textContent = '▼';
    } else {
        content.style.maxHeight = content.scrollHeight + 'px';
        this.querySelector('.accordion-icon').textContent = '▲';
    }
    });
});

// Favorite button functionality
const favBtns = document.querySelectorAll('.favorite-btn');

favBtns.forEach(btn => {
    btn.addEventListener('click', function() {
    this.classList.toggle('active');
    });
});
});