
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