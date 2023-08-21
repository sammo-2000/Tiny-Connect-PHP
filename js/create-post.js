document.addEventListener('DOMContentLoaded', async () => {
    // Get all the elements
    const userID = ID();
    const labelImg = document.querySelector('label img');
    const imageEl = document.querySelector('input[name="image"]')
    const captionEl = document.querySelector('input[name="caption"]')
    const btn = document.querySelector('button');
    const errorEl = document.querySelector('#error');
    const loadingEl = document.querySelector('.loading-icon')
    let imageBase64 = null

    // Preview the upload images
    imageEl.addEventListener('change', event => {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend = () => {
            labelImg.src = reader.result;
            imageBase64 = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            labelImg.src = '/images/upload.jpeg';
        }
    })

    // Upload the post
    btn.addEventListener('click', async event => {
        event.preventDefault();
        loadingEl.style.display = 'block'
        btn.style.display = 'none'
        try {
            const DataToSend = {
                image: imageBase64,
                caption: captionEl.value,
                userID: userID
            };

            const response = await fetch('/api/post', {
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