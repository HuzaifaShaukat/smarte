<!-- Your existing HTML and CSS remains the same -->

<!-- Add this error container before the submit button -->
<div class="error-container" id="errorMessage" style="display: none; color: var(--error); margin-bottom: 1rem;"></div>

<script>
document.getElementById("orderForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const form = e.target;
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    const errorContainer = document.getElementById('errorMessage');

    // Clear previous errors
    errorContainer.style.display = 'none';
    inputs.forEach(input => {
        input.classList.remove('invalid');
        input.nextElementSibling.nextElementSibling.style.display = 'none';
    });

    // Validate form
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('invalid');
            input.nextElementSibling.nextElementSibling.style.display = 'block';
            isValid = false;
        }
    });

    if (!isValid) {
        errorContainer.textContent = 'Please fill in all required fields';
        errorContainer.style.display = 'block';
        return;
    }

    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    // Show loading state
    btn.classList.add('loading');
    btnText.textContent = 'Processing...';

    try {
        const response = await fetch('process_order.php', {
            method: 'POST',
            body: new FormData(form)
        });

        const data = await response.json();

        if (data.success) {
            form.style.display = 'none';
            document.querySelector('.success-message').style.display = 'block';
        } else {
            errorContainer.textContent = data.error || 'Error submitting order';
            errorContainer.style.display = 'block';
        }
    } catch (error) {
        errorContainer.textContent = 'Network error. Please try again.';
        errorContainer.style.display = 'block';
    } finally {
        btn.classList.remove('loading');
        btnText.textContent = 'Place Order';
    }
});

// Rest of your existing JavaScript for new order button...
</script>