function getFields(formId)
{
    fields = {}
    document.getElementById(formId).querySelectorAll('input, select, textarea').forEach(input => {
        fields[input.id] = input.value
    });
    return fields
}

async function createUser(fields)
{
    try {
      const response = await fetch('../app/controllers/AsyncController.php', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' 
        },
        body: JSON.stringify({
          action: 'sign-up', 
          fields: fields
        })
      });
      if (response.ok) {
        result = await response.text();
        if (result == 'true'){
            generateToken();
            const baseUrl = window.location.pathname.split('/').slice(0, -1).join('/');
            window.location.href = `${baseUrl}/index.php?page=home`;
        } else {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
      } else {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
    } catch (error) {
        document.querySelector('.not-found').innerHTML = "Cet email a déjà été utilisé.";
    }
}

function generateAuthToken(length = 64) {
    const array = new Uint8Array(length);
    window.crypto.getRandomValues(array);
    return Array.from(array, byte => byte.toString(16).padStart(2, '0')).join('');
}

async function generateToken()
{
    const date = new Date();
    date.setTime(date.getTime() + (7 * 24 * 60 * 60 * 1000));
    tokenInfo = {
        "value" : generateAuthToken(),
        "email" : document.getElementById("email").value,
        "expiration" : date.toUTCString()
    }
    try {
      const response = await fetch('../app/controllers/AsyncController.php', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' 
        },
        body: JSON.stringify({
          action: 'generate-token', 
          token: tokenInfo
        })
      }); 
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      } else {  
        const expires = "expires=" + date.toUTCString();
        document.cookie = `authToken=${tokenInfo["value"]}; ${expires}; path=/; Secure; SameSite=Strict`;
      }
      
    } catch (error) {
        document.querySelector('.not-found').innerHTML = "Cette combinaison mail/mot de passe n'existe pas.";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createUser(getFields('signupForm'))
    });
});
