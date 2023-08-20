document.addEventListener('DOMContentLoaded', async () => {
    // Get all the elements
    const userID = User();
    const labelImg = document.querySelector('label img');
    const imageEl = document.querySelector('input[name="image"]');
    const nameEl = document.querySelector('input[name="name"]');
    const bioEl = document.querySelector('textarea');
    const btnUpdate = document.querySelector('button.update');
    const btnDelete = document.querySelector('button.delete')
    const errorEl = document.querySelector('#error');
    const loadingEl = document.querySelector('.loading-icon')
    let imageBase64 = null

    // Preview the profile images
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
            labelImg.src = '/images/user.jpeg';
        }
    })

    // Delete profile
    btnDelete.addEventListener('click', async event => {
        event.preventDefault()
        const confirmed = confirm('Are you sure you want to delete your account, once done this action cannot be undone');
        alert('Please leave this tap open until redirected to successfully delete your profile')
        loadingEl.style.display = 'block'
        btnUpdate.style.display = 'none'
        if (confirmed) {
            const response = await fetch('/api/user', {
                method: 'DELETE'
            })
            const data = await response.json()
            if (data.success) {
                window.location.href = '/'
            }
        }
    })

    // Update profile
    btnUpdate.addEventListener('click', async event => {
        event.preventDefault();
        loadingEl.style.display = 'block'
        btnUpdate.style.display = 'none'
        try {
            const DataToSend = {
                image: imageBase64,
                name: nameEl.value,
                bio: bioEl.value,
            };

            const response = await fetch('/api/user', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(DataToSend)
            });
            const data = await response.json()

            loadingEl.style.display = 'none'
            btnUpdate.style.display = 'block'

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

    try {
        const response = await fetch(`/api/user/${userID}`);

        const data = await response.json()

        labelImg.src = setImage(data.user.image);
        nameEl.value = data.user.name
        bioEl.value = data.user.bio
    } catch (error) {
        console.error('Error sending API request:', error);
    }
})
