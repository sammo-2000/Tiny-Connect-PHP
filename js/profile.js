document.addEventListener('DOMContentLoaded', async () => {
    const userID = User();
    const profile = document.getElementById('profile')
    const followBtn = document.querySelector('button')
    // Profile info
    const image = document.getElementById('image')
    const name = document.getElementById('name')
    const join_date = document.getElementById('join_date')
    // Profile details
    const posts = document.getElementById('posts')
    const blogs = document.getElementById('blogs')
    const following = document.getElementById('following')
    const Followers = document.getElementById('Followers')

    // START OF TAB SWITCH ............................................................
    const tabs = document.querySelectorAll(".tab");
    const sections = document.querySelectorAll("section");

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            switchTab(tab);
        });
    });

    function switchTab(tab) {
        tabs.forEach((tab) => tab.classList.remove("active"));

        sections.forEach((section) => (section.style.display = "none"));

        const sectionID = tab.getAttribute("data-section");

        const sectionToShow = document.querySelector(`.${sectionID}`);

        tab.classList.add("active");
        sectionToShow.style.display = "flex";
    }

    const activeTab = document.querySelector(".tab.active");
    switchTab(activeTab);
    // END OF TAB SWITCH ..............................................................

    // START LOADING FOLLOWER .........................................................
    function loadFollower(followers) {
        const followerSection = document.querySelector('section.follower')
        followerSection.innerHTML = ''
        const userCards = document.createElement("div");
        userCards.classList.add('user-cards')
        followers.forEach(element => {
            const newDiv = document.createElement("div");
            newDiv.classList.add('user-card')
            newDiv.classList.add('box')
            newDiv.innerHTML = `
                <img src="${setImage(element.image)}">
                <a href="/profile/${element.userID}">${element.name}</a>
            `
            userCards.appendChild(newDiv);
        });
        followerSection.appendChild(userCards);
    }
    // END LOADING FOLLOWER ...........................................................

    // START LOADING FOLLOWING ........................................................
    function loadFollowing(following) {
        const followingSection = document.querySelector('section.following')
        followingSection.innerHTML = ''
        const userCards = document.createElement("div");
        userCards.classList.add('user-cards')
        following.forEach(element => {
            const newDiv = document.createElement("div");
            newDiv.classList.add('user-card')
            newDiv.classList.add('box')
            newDiv.innerHTML = `
                    <img src="${setImage(element.image)}">
                    <a href="/profile/${element.userID}">${element.name}</a>
                `
            userCards.appendChild(newDiv);
        });
        followingSection.appendChild(userCards);
    }
    // END LOADING FOLLOWING ..........................................................

    // START OF FOLLOWING THE USER ....................................................
    if (followBtn) {
        followBtn.addEventListener('click', async () => {
            try {
                const response = await fetch(`/api/follow/${userID}`)
                const data = await response.json()
                console.log(data)
                getData()
            }
            catch (error) {
                console.error('Error sending API request:', error);
            }
    
        })
    }
    // END OF FOLLOWING THE USER ......................................................

    async function getData() {
        try {
            const response = await fetch(`/api/user/${userID}`);

            const data = await response.json()

            console.log(data)

            if (data.user) {
                // Set profile details
                image.src = setImage(data.user.image)
                name.innerText = data.user.name
                join_date.innerText = setTime(data.user.joinedAt)
                posts.innerText = data.postCount
                blogs.innerText = data.blogCount
                following.innerText = data.following
                Followers.innerText = data.followers

                // If has follower load them
                if (data.follow_details['them-me'].length != 0) {
                    loadFollower(data.follow_details['them-me'])
                } else {
                    const followerSection = document.querySelector('section.follower')
                    followerSection.innerHTML = '<div class="no-found">No follower found</div>'
                }
                // If has following load them
                if (data.follow_details['me-them'].length != 0) {
                    loadFollowing(data.follow_details['me-them'])
                }
                // Change the follow btn text
                if (data.isFollowing.length != 0) {
                    const isFollowingDiv = document.querySelector('#follow')
                    if (data.isFollowing) {
                        isFollowingDiv.innerText = 'Unfollow'
                    } else {
                        isFollowingDiv.innerText = 'Follow'
                    }
                }
            } else {
                profile.innerHTML = `<div class="no-found">No user found</div>`
            }

        } catch (error) {
            console.error('Error sending API request:', error);
        }
    }

    getData();
});
