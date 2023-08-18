const formEl = document.querySelector('form')
const errorEl = document.querySelector('#error')

formEl.addEventListener('submit', async event => {
    event.preventDefault();
    const email = document.querySelector('#email').value
    const password = document.querySelector('#password').value
    const password_repeat = document.querySelector('#password_repeat').value
    const requestBody = { email, password, password_repeat };
    try {
        const response = await fetch('/api/signup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestBody),
        });

        const data = await response.json()

        errorEl.style.display = 'block'
        errorEl.classList.remove('success')
        errorEl.classList.remove('error')
        errorEl.classList.add(data.success)
        errorEl.innerText = data.message

        if (data.success == 'success') {
            window.location.href = '/login'
        }

    } catch (error) {
        console.error('Error sending API request:', error);
    }
})