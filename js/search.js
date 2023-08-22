document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch(`/api/search`);
        const data = await response.json()

        if (data.success) {
            insertData(data.users)
        }

    } catch (error) {
        console.error('Error sending API request:', error);
    }

    // Search by input field
    const btn = document.querySelector('button')
    const input = document.querySelector('input')
    btn.addEventListener('click', async (event) => {
        event.preventDefault();
        try {
            const response = await fetch(`/api/search/${input.value}`);
            const data = await response.json()

            if (data.success) {
                insertData(data.users)
            }

        } catch (error) {
            console.error('Error sending API request:', error);
        }
    })

    function insertData(data) {
        const showSection = document.querySelector('.search')
        showSection.innerHTML = ''
        const userCards = document.createElement("div");
        userCards.classList.add('user-cards')
        data.forEach(element => {
            const newDiv = document.createElement("div");
            newDiv.classList.add('user-card')
            newDiv.classList.add('box')
            newDiv.innerHTML = `
                            <img src="${setImage(element.image)}">
                            <a href="/profile/${element.userID}">${element.name}</a>
                        `
            userCards.appendChild(newDiv);
        });
        showSection.appendChild(userCards);
    }
})
