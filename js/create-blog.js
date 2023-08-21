document.addEventListener('DOMContentLoaded', async () => {
    // Get all the elements
    const userID = ID();
    const blogTitleEl = document.querySelector('input[name="title"]')
    const blogBodyEl = document.querySelector('textarea[name="blog"]')
    const btn = document.querySelector('button');
    const errorEl = document.querySelector('#error');
    const loadingEl = document.querySelector('.loading-icon')

    // Upload the blog
    btn.addEventListener('click', async event => {
        event.preventDefault();
        loadingEl.style.display = 'block'
        btn.style.display = 'none'
        try {
            const DataToSend = {
                title: blogTitleEl.value,
                body: blogBodyEl.value,
            };

            const response = await fetch('/api/blog', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(DataToSend)
            });
            const data = await response.json()

            loadingEl.style.display = 'none'
            btn.style.display = 'block'

            if (data.success) {
                window.location.href = '/profile'
            } else {
                errorEl.classList.add('error')
                errorEl.style.display = 'flex'
                errorEl.innerText = data.message
            }
        } catch (error) {
            console.error('Error sending API request:', error);
        }
    })
})