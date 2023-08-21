document.addEventListener('DOMContentLoaded', async () => {
    const postID = ID()
    const postSection = document.getElementById('post')
    const errorEl = document.getElementById('error')

    const btn = document.querySelector('button.send')

    // Add new comment
    btn.addEventListener('click', async () => {
        const input = document.querySelector('input')
        try {
            const DataToSend = {
                input: input.value,
                postID: postID
            };

            const response = await fetch('/api/post-comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(DataToSend)
            });
            const data = await response.json()

            if (data.error == false) {
                input.value = ''
                getData()
            } else {
                if (data.error) {
                    errorEl.classList.remove('success')
                    errorEl.classList.add('error')
                    errorEl.style.display = 'flex'
                    errorEl.innerText = data.message
                } else {
                    errorEl.classList.add('success')
                    errorEl.classList.remove('error')
                    errorEl.style.display = 'flex'
                    errorEl.innerText = data.message
                }
            }
        } catch (error) {
            console.error('Error sending API request:', error);
        }
    })

    // Load the data
    async function getData() {
        try {
            const response = await fetch(`/api/post/${postID}`);

            const data = await response.json()

            if (data.post) {
                document.querySelector('.post-detail a').innerText = data.uploaderName
                document.querySelector('.post-detail a').href = `/profile/${data.post.uploaderID}`
                document.querySelector('h1').innerText = data.post.caption
                document.querySelector('.post-detail span').innerText = setTime(data.post.uploadTime)
                document.querySelector('#post img').src = data.post.image
                if (data.comment) {
                    const comment = data.comment;
                    const commentsDiv = document.querySelector('.comments')
                    commentsDiv.innerHTML = ''
                    const commentCards = document.createElement("div");
                    commentCards.classList.add('comment-cards')
                    comment.forEach(element => {
                        const newDiv = document.createElement("div");
                        newDiv.classList.add('comment-card')
                        newDiv.classList.add('box')
                        let trash = '';
                        if (element.comment.userID == userID) {
                            trash = `<button data-section="${element.comment.commentID}" class="btn btn-white delete"><i class="fa-solid fa-trash"></i></button>`
                        }
                        newDiv.innerHTML = `
                                <div>
                                    <div>
                                        <div>
                                            <img src="${setImage(element.user.image)}">
                                            <a href="/profile/${element.comment.userID}">${element.user.name}</a>
                                        </div>    
                                        <div>
                                            <span class="time">${setTime(element.comment.time)}</span>
                                            ${trash}
                                        </div>    
                                    </div>    
                                    <p>${element.comment.comment}</p>
                                </div>
                            `
                        commentCards.appendChild(newDiv);
                    });
                    commentsDiv.appendChild(commentCards);

                    // Delete comment function
                    const btnDelete = document.querySelectorAll('button.delete');

                    btnDelete.forEach(button => {
                        button.addEventListener('click', async () => {
                            const commentID = button.getAttribute("data-section")
                            try {
                                const response = await fetch(`/api/post-comment/${commentID}`, {
                                    method: 'DELETE'
                                });
                                const data = await response.json()
                                if (data.error) {
                                    errorEl.classList.remove('success')
                                    errorEl.classList.add('error')
                                    errorEl.style.display = 'flex'
                                    errorEl.innerText = data.message
                                } else {
                                    errorEl.classList.add('success')
                                    errorEl.classList.remove('error')
                                    errorEl.style.display = 'flex'
                                    errorEl.innerText = data.message
                                    getData()
                                }
                            } catch (error) {
                                console.error('Error sending API request:', error);
                            }
                        });
                    });
                }

            } else {
                postSection.innerHTML = `<div class="no-found">No post found</div>`
            }

        } catch (error) {
            console.error('Error sending API request:', error);
        }
    }

    getData();
})