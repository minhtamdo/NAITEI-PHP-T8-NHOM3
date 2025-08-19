function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const value = parseInt(input.value);
    if (value > 1) input.value = value - 1;
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const value = parseInt(input.value);
    if (value < 15) input.value = value + 1;
}

function addToCart() {
    const btn = document.querySelector('.add-cart');
    const original = btn.textContent;
    
    btn.textContent = 'Đang thêm...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.textContent = 'Đã thêm';
        btn.style.background = '#2d5a2d';
        
        setTimeout(() => {
            btn.textContent = original;
            btn.style.background = '#000';
            btn.disabled = false;
        }, 1500);
    }, 600);
}

function submitReview(event) {
    event.preventDefault();
    
    const name = document.getElementById('name').value;
    const content = document.getElementById('content').value;
    
    const reviews = document.getElementById('reviews');
    const review = document.createElement('div');
    review.className = 'review';
    review.innerHTML = `
        <div class="review-header">
            <span class="review-name">${name}</span>
            <span class="review-date">Vừa xong</span>
        </div>
        <div class="review-content">${content}</div>
    `;
    
    reviews.insertBefore(review, reviews.firstChild);
    
    document.getElementById('success').style.display = 'block';
    document.getElementById('name').value = '';
    document.getElementById('content').value = '';
    
    setTimeout(() => {
        document.getElementById('success').style.display = 'none';
    }, 3000);
}

function goBack() {
    window.history.back();
}