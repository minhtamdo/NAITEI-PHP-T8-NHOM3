function handleScroll() {
    const elements = document.querySelectorAll('.fade-in');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 100;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('visible');
        }
    });
}

// Smooth scroll to books section
document.querySelector('.btn-primary').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('books').scrollIntoView({
        behavior: 'smooth'
    });
});

// Initialize
window.addEventListener('scroll', handleScroll);
window.addEventListener('load', handleScroll);
document.addEventListener('DOMContentLoaded', handleScroll);