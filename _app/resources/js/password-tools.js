const togglePasswordButton = document.getElementById('generate-password');
if(togglePasswordButton) {
    document.getElementById('toggle-password').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            const passwordFieldType = passwordInput.getAttribute('type');
            if (passwordFieldType === 'password') {
                passwordInput.setAttribute('type', 'text');
                this.textContent = 'Hide Password';
            } else {
                passwordInput.setAttribute('type', 'password');
                this.textContent = 'Show Password';
            }
        }
    });
}

const generatePasswordButton = document.getElementById('generate-password');
if(generatePasswordButton){
    generatePasswordButton.addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        if(passwordInput) {
            const newPassword = generatePassword(12); // Genera una password di 12 caratteri
            passwordInput.value = newPassword;
        }
    });
}

function generatePassword(length) {
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
    let password = "";
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    return password;
}

