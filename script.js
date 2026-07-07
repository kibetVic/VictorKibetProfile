
        const menuBtn = document.getElementById('menuBtn');
        const nav = document.getElementById('nav');

        menuBtn.addEventListener('click', () => {
            nav.classList.toggle('active');
        });

        // close menu on link click (optional)
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', () => {
                nav.classList.remove('active');
            });
        });

        // ---------- TYPING ANIMATION ----------
        const textArray = [
            'Software Developer',
            'ASP.NET Core Developer',
            'M-Pesa Integration Expert',
            'USSD Integration',
            'Database Designer',
            'Web Designer'
        ];

        let count = 0;
        let index = 0;
        let currentText = '';
        let letter = '';

        (function type() {
            if (count === textArray.length) {
                count = 0;
            }
            currentText = textArray[count];
            letter = currentText.slice(0, ++index);
            document.getElementById('typing').textContent = letter;

            if (letter.length === currentText.length) {
                count++;
                index = 0;
                setTimeout(type, 1500);
            } else {
                setTimeout(type, 100);
            }
        })();

         // ---------- CONTACT FORM (AJAX with fallback) ----------
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const successMsg = document.getElementById('successMessage');
            const errorMsg = document.getElementById('errorMessage');

            // Hide messages initially
            successMsg.classList.remove('show');
            errorMsg.classList.remove('show');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Hide any previous messages
                successMsg.classList.remove('show');
                errorMsg.classList.remove('show');

                // Get form data
                const formData = new FormData(this);
                const originalText = submitBtn.textContent;

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';

                // Send data via fetch
                fetch('send_email.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    // Check if response is OK
                    if (!response.ok) {
                        throw new Error(`Server responded with status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        successMsg.textContent = '✅ ' + (data.message || 'Thank you for reaching out! I will get back to you soon.');
                        successMsg.classList.add('show');
                        form.reset(); // Clear the form
                    } else {
                        // Show error message
                        let errorText = '❌ ';
                        if (data.errors && data.errors.length > 0) {
                            errorText += data.errors.join(' | ');
                        } else if (data.error) {
                            errorText += data.error;
                        } else {
                            errorText += 'Failed to send message. Please try again later.';
                        }
                        errorMsg.textContent = errorText;
                        errorMsg.classList.add('show');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    // Show detailed error
                    let errorText = '❌ Network error. Please check your internet connection and try again.';
                    if (error.message) {
                        errorText += '\n\nDetails: ' + error.message;
                    }
                    errorMsg.textContent = errorText;
                    errorMsg.classList.add('show');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;

                    // Auto-hide messages after 10 seconds
                    setTimeout(() => {
                        successMsg.classList.remove('show');
                        errorMsg.classList.remove('show');
                    }, 10000);
                });
            });
        });