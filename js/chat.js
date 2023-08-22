document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch(`/api/chat`);
        const data = await response.json()

        if (data != false) {
            const chatWithSection = document.querySelector('#chat-with')
            chatWithSection.innerHTML = ''
            const userCards = document.createElement("div");
            userCards.classList.add('user-cards')
            data.forEach(element => {
                const newDiv = document.createElement("div");
                newDiv.classList.add('user-card')
                newDiv.classList.add('box')
                newDiv.innerHTML = `
                            <img src="${setImage(element.image)}">
                            <a href="/chat/${element.userID}">${element.name}</a>
                        `
                userCards.appendChild(newDiv);
            });
            chatWithSection.appendChild(userCards);
        }

    } catch (error) {
        console.error('Error sending API request:', error);
    }
})
