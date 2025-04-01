document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('contactForm');
    const fileUpload = document.getElementById('fileUpload');
    const fileName = document.getElementById('fileName');
    const messageField = document.getElementById('message');
    const charCount = document.querySelector('.char-count');

    // Mise à jour du compteur de caractères
    messageField.addEventListener('input', function() {
        const currentLength = this.value.length;
        charCount.textContent = `${currentLength}/750 caractères max`;
        
        if (currentLength > 750) {
            charCount.style.color = 'red';
        } else {
            charCount.style.color = 'rgba(255, 255, 255, 0.8)';
        }
    });

    // Gestion du nom en majuscules
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('blur', function() {
        this.value = this.value.toUpperCase();
    });

    // Gestion du fichier uploadé
    fileUpload.addEventListener('change', function() {
        const file = this.files[0];
        const fileError = document.getElementById('fileError');
        fileError.textContent = "";
        fileName.textContent = "";

        if (file) {
            const allowedExtensions = ['pdf', 'doc', 'docx', 'odt', 'rtf', 'jpg', 'png'];
            const fileExt = file.name.split('.').pop().toLowerCase();
            
            if (!allowedExtensions.includes(fileExt)) {
                fileError.textContent = `Format non autorisé. Formats acceptés : ${allowedExtensions.join(', ')}`;
                this.value = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                fileError.textContent = "La taille du fichier dépasse 2 Mo";
                this.value = "";
                return;
            }

            fileName.textContent = file.name;
        }
    });

    // Validation et soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Réinitialisation des messages d'erreur
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => error.textContent = "");

        let isValid = true;
        const nameValue = nameInput.value.trim();
        const emailValue = document.getElementById('email').value.trim();
        const messageValue = document.getElementById('message').value.trim();
        const userType = document.querySelector('input[name="userType"]:checked');

        // Validation du type d'utilisateur
        if (!userType) {
            isValid = false;
            // Pas besoin d'afficher un message car le required HTML gère cela
        }

        // Validation du nom
        if (!nameValue) {
            document.getElementById('nameError').textContent = "Le nom est requis";
            isValid = false;
        }

        // Validation de l'email
        if (!emailValue) {
            document.getElementById('emailError').textContent = "L'email est requis";
            isValid = false;
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                document.getElementById('emailError').textContent = "Email invalide";
                isValid = false;
            }
        }

        // Validation du message
        if (!messageValue) {
            document.getElementById('messageError').textContent = "Le message est requis";
            isValid = false;
        } else if (messageValue.length > 750) {
            document.getElementById('messageError').textContent = "Le message ne doit pas dépasser 750 caractères";
            isValid = false;
        }

        if (isValid) {
            // Simulation d'envoi du formulaire
            alert('Message envoyé avec succès !');
            form.reset();
            fileName.textContent = "";
            charCount.textContent = "0/750 caractères max";
        }
    });
});