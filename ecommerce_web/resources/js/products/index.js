// Animation on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.fade-in');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 100;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('visible');
        }
    });
}

// Add to cart functionality
function addToCart(event, productName) {
    event.preventDefault();
    
    console.log('Product added to cart:', productName);
}

// Filter and search functionality
function filterProducts() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const sortFilter = document.getElementById('sortFilter').value;
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    
    let visibleProducts = [];
    
    products.forEach(product => {
        const category = product.dataset.category;
        const name = product.dataset.name.toLowerCase();
        
        // Check category filter
        const categoryMatch = !categoryFilter || category === categoryFilter;
        
        // Check search filter
        const searchMatch = !searchQuery || name.includes(searchQuery);
        
        if (categoryMatch && searchMatch) {
            product.style.display = 'block';
            visibleProducts.push(product);
        } else {
            product.style.display = 'none';
        }
    });
    
    // Sort visible products
    if (sortFilter && visibleProducts.length > 0) {
        const container = document.getElementById('productList');
        
        visibleProducts.sort((a, b) => {
            switch (sortFilter) {
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'price-low':
                    return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                case 'price-high':
                    return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                default:
                    return 0;
            }
        });
        
        // Re-append sorted products
        visibleProducts.forEach(product => {
            container.appendChild(product);
        });
    }
}

// Event listeners
document.getElementById('categoryFilter').addEventListener('change', filterProducts);
document.getElementById('sortFilter').addEventListener('change', filterProducts);
document.getElementById('searchInput').addEventListener('input', filterProducts);

// Initialize
window.addEventListener('scroll', animateOnScroll);
window.addEventListener('load', animateOnScroll);
document.addEventListener('DOMContentLoaded', animateOnScroll);