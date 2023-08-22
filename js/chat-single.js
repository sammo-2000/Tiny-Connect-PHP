document.addEventListener('DOMContentLoaded', async () => {
    const userID = ID();
    let firstLoad = true;
    let lastChatID = 0;
    let lastDate = null;
    const chatSection = document.querySelector('.chat')
    const btn = document.querySelector('button')
    const chat = document.querySelector('input')
    const errorEl = document.querySelector('#error')

    btn.addEventListener('click', async () => {
        const chatInput = chat.value
        try {
            const DataToSend = {
                userID: userID,
                chat: chatInput
            };

            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(DataToSend)
            });
            const data = await response.json()
            if (data.error) {
                errorEl.style.display = 'block'
                errorEl.classList.add('error')
                errorEl.innerText = data.message
            } else {
                errorEl.style.display = 'none'
                chat.value = ''
                getChat();
            }
        } catch (error) {
            console.error('Error sending API request:', error);
        }
    })

    try {
        const response = await fetch(`/api/user/${userID}`);
        const data = await response.json()

        document.querySelector('#chat a').innerText = data.user.name
        document.querySelector('#chat a').href = '/profile/' + data.user.userID
        document.querySelector('#chat img').src = setImage(data.user.image)

        getChat();

    } catch (error) {
        console.error('Error sending API request:', error);
    }

    // Get chat function
    async function getChat() {
        const response = await fetch(`/api/chat/${userID}`)
        const data = await response.json()
        if (data != false) {
            if (firstLoad) {
                firstLoad = false;
                chatSection.innerHTML = ''
            }

            data.forEach(element => {
                if (element.chatID > lastChatID) {
                    const newDiv = document.createElement("div");
                    // Change date if it is different
                    chatDate(element.time)
                    // Add me class if chat sent by me
                    if (element.senderID != userID) {
                        newDiv.classList.add('me')
                    }
                    // Create the chat
                    newDiv.innerHTML = `
                    <span>${getTime(element.time)}</span>
                    <p>${HTML(element.chat)}</p>
                `
                    // Add the chat to show the user
                    chatSection.appendChild(newDiv)
                    lastChatID = element.chatID
                    chatSection.scrollTop = chatSection.scrollHeight;
                }

            });
        }
    }

    setInterval(getChat, 1000)

    // Show time function
    function getTime(time) {
        const dateTime = new Date(time);

        const hours = dateTime.getHours();
        const minutes = dateTime.getMinutes();

        const formattedTime = `${hours.toString().padStart(2, "0")}:${minutes
            .toString()
            .padStart(2, "0")}`;

        return formattedTime;
    }

    // Show date function
    function chatDate(time) {
        const messageDate = new Date(time).toLocaleDateString();
        if (messageDate != lastDate) {
            const dateHeader = document.createElement("span");
            dateHeader.classList.add("date-header");

            const currentDate = new Date();
            if (messageDate === currentDate.toLocaleDateString()) {
                dateHeader.textContent = "Today";
            } else {
                dateHeader.textContent = messageDate;
            }

            chatSection.appendChild(dateHeader);
            lastDate = messageDate;
        }
    }
})
