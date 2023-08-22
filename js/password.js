document.addEventListener('DOMContentLoaded', async () => {
    const emailEl = document.getElementById('email')
    const OPTEl = document.getElementById('OTP')
    const passwordEl = document.getElementById('password')
    const errorEl = document.getElementById('error')
    const btnEl = document.getElementById('reset')
    const loadingEl = document.querySelector('.loading-icon')
    const linkEl = document.querySelector('form a')

    // Hide the input email on first load
    OPTEl.style.display = 'none'
    passwordEl.style.display = 'none'

    btnEl.addEventListener('click', async event => {
        event.preventDefault()

        // Hide other elements
        btnEl.style.display = 'none'
        linkEl.style.display = 'none'
        loadingEl.style.display = 'block'

        // Send data
        try {
            const DataToSend = {
                email: emailEl.value,
                OTP: OPTEl.value,
                password: passwordEl.value,
            };

            const response = await fetch('/api/user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(DataToSend)
            });
            const data = await response.json()

            console.log(data)

            btnEl.style.display = 'block'
            linkEl.style.display = 'flex'
            loadingEl.style.display = 'none'

            if (data.success) {
                errorEl.classList.add('success')
                errorEl.classList.remove('error')
                errorEl.style.display = 'flex'
                errorEl.innerText = data.message
                OPTEl.style.display = 'block'
                passwordEl.style.display = 'block'
                if (data.message == 'Pasword reset successful, you can now login') {
                    window.location.href = '/login'
                }

            } else {
                errorEl.classList.remove('success')
                errorEl.classList.add('error')
                errorEl.style.display = 'flex'
                errorEl.innerText = data.message
            }
        } catch (error) {
            console.error('Error sending API request:', error);
        }

    })
})