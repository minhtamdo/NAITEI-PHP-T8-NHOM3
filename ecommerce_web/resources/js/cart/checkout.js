function handleSubmit() {
    const form = document.getElementById('checkoutForm');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    // Reset previous states
    inputs.forEach(input => {
        input.style.borderColor = '#d1d5db';
        input.style.boxShadow = 'none';
    });
    
    // Validate
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = '#6b7280';
            input.style.boxShadow = '0 0 0 1px #6b7280';
            if (isValid === false) {
                input.focus();
            }
        }
    });
    
    if (isValid) {
        const btn = document.querySelector('.submit-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Đang xử lý đơn hàng...';
        btn.style.background = '#6b7280';
        btn.disabled = true;
        
        setTimeout(() => {
            // Success animation
            btn.innerHTML = '✓ Đặt hàng thành công';
            btn.style.background = '#1a1a1a';
            
            setTimeout(() => {
                alert('Cảm ơn bạn đã đặt hàng! Chúng tôi sẽ liên hệ xác nhận trong vòng 30 phút và giao hàng trong 24-48 giờ.');
                // Reset
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1000);
        }, 2000);
    }
}

// Enhanced form interactions
document.querySelectorAll('input, select, textarea').forEach(element => {
    element.addEventListener('focus', function() {
        this.style.borderColor = '#1a1a1a';
        this.style.boxShadow = '0 0 0 1px #1a1a1a';
    });
    
    element.addEventListener('blur', function() {
        if (this.value.trim()) {
            this.style.borderColor = '#6b7280';
            this.style.boxShadow = 'none';
        } else {
            this.style.borderColor = '#d1d5db';
            this.style.boxShadow = 'none';
        }
    });
});

// Smooth page entrance
window.addEventListener('load', function() {
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.5s ease';
    
    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 100);
});

window.handleSubmit = handleSubmit; 