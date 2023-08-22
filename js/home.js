document.addEventListener('DOMContentLoaded', async () => {
    // START OF RESIZE ................................................................
    let reSizeAble = false
    window.addEventListener('resize', () => {
        if (reSizeAble) {
            reSize();
        }
    })
    function reSize() {
        const postCardSingle = document.querySelectorAll('.post-card');
        const width = postCardSingle[0].clientWidth;

        postCardSingle.forEach(element => {
            const image = element.querySelector('img');
            image.style.width = width + 'px';
            image.style.height = width + 'px';
            element.style.width = width + 'px';
            element.style.height = width + 'px';
            element.style.maxWidth = width + 'px';
            element.style.maxHeight = width + 'px';
        });
    }
    // END OF RESIZE ..................................................................

    // START OF RESIZE ................................................................
    let reSizeAbleFollowing = false
    window.addEventListener('resize', () => {
        if (reSizeAbleFollowing) {
            reSizeFollowing();
        }
    })
    function reSizeFollowing() {
        const postCardSingleFollowing = document.querySelectorAll('.post-card-following');
        const width = postCardSingleFollowing[0].clientWidth;

        postCardSingleFollowing.forEach(element => {
            const image = element.querySelector('img');
            image.style.width = width + 'px';
            image.style.height = width + 'px';
            element.style.width = width + 'px';
            element.style.height = width + 'px';
            element.style.maxWidth = width + 'px';
            element.style.maxHeight = width + 'px';
        });
    }
    // END OF RESIZE ..................................................................

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

    // START OF LOADING PUBLIC POSTS ..................................................

    // Load more post BTN
    const postBtn = document.querySelector('.post button');
    postBtn.addEventListener('click', () => {
        getPost()
    })

    // Get post function
    const postSection = document.querySelector('.post-section');
    let postLimit = 8;
    let loadedPostIDs = [];

    async function getPost() {
        try {
            const response = await fetch(`/api/home/post/${postLimit}`)
            const data = await response.json()

            // Hide BTN if no more posts
            if (data.morePost) {
                postBtn.style.display = 'block'
            } else {
                postBtn.style.display = 'none'
            }

            // Clear the section if not done yet
            if (loadedPostIDs.length === 0 && data.postInfo.length !== 0) {
                postSection.innerHTML = ''
                const postCards = document.createElement("div");
                postCards.classList.add('post-cards')
                postSection.appendChild(postCards);
            }

            // Select post cards
            const postCards = document.querySelector('.post-cards')

            // Loop the data
            data.postInfo.forEach(element => {

                // Only run if post is not added any
                if (!loadedPostIDs.includes(element.post.postID)) {

                    // Add the ID into loaded array
                    loadedPostIDs.push(element.post.postID);

                    // Create post card and show it to user
                    const newDiv = document.createElement("a");
                    newDiv.classList.add('post-card')
                    newDiv.classList.add('box')
                    newDiv.href = `/post/${element.post.postID}`
                    newDiv.innerHTML = `
                        <img src="${element.post.image}">
                    `
                    postCards.appendChild(newDiv);

                }

                let reSizeAble = true
                reSize()

            });
            // End loop

            // Change to the new limit
            postLimit = data.limit

        } catch (error) {
            console.error('Error sending API request:', error);
        }
    }

    getPost()
    // END OF LOADING PUBLIC POSTS ....................................................

    // START OF LOADING PUBLIC BLOGS ..................................................

    // Load more post BTN
    const blogBtn = document.querySelector('.blog button');
    blogBtn.addEventListener('click', () => {
        getBlog()
    })

    // Get post function
    const blogSection = document.querySelector('.blog-section');
    let blogLimit = 8;
    let loadedBlogIDs = [];

    async function getBlog() {
        try {
            const response = await fetch(`/api/home/blog/${blogLimit}`)
            const data = await response.json()

            // Hide BTN if no more posts
            if (data.moreBlog) {
                blogBtn.style.display = 'block'
            } else {
                blogBtn.style.display = 'none'
            }

            // Clear the section if not done yet
            if (loadedBlogIDs.length === 0 && data.blogInfo.length !== 0) {
                blogSection.innerHTML = ''
                const blogCards = document.createElement("div");
                blogCards.classList.add('blog-cards')
                blogSection.appendChild(blogCards);
            }

            // Select post cards
            const blogCards = document.querySelector('.blog-cards')

            // Loop the data
            data.blogInfo.forEach(element => {

                // Only run if post is not added any
                if (!loadedBlogIDs.includes(element.blog.blogID)) {

                    // Add the ID into loaded array
                    loadedBlogIDs.push(element.blog.blogID);

                    // Create post card and show it to user
                    const newDiv = document.createElement("a");
                    newDiv.classList.add('blog-card');
                    newDiv.classList.add('box');
                    newDiv.href = `/blog/${element.blog.blogID}`;

                    const truncatedTitle = element.blog.title.length > 100 ? `${element.blog.title.substring(0, 100)}...` : element.blog.title;
                    const truncatedBody = element.blog.body.length > 200 ? `${element.blog.body.substring(0, 200)}...` : element.blog.body;

                    newDiv.innerHTML = `
                        <p class="title">${truncatedTitle}</p>
                        <p class="body">${truncatedBody}</p>
                    `;

                    blogCards.appendChild(newDiv);

                }

            });
            // End loop

            // Change to the new limit
            blogLimit = data.limit

        } catch (error) {
            console.error('Error sending API request:', error);
        }
    }

    getBlog()
    // END OF LOADING PUBLIC BLOGS ....................................................

    // START OF LOADING PRIVATE POSTS .................................................

    // Load more post BTN
    const postBtnFollowing = document.querySelector('.post-following button');
    postBtnFollowing.addEventListener('click', () => {
        getPostFollowing()
    })

    // Get post function
    const postSectionFollowing = document.querySelector('.post-section-following');
    let postLimitFollowing = 8;
    let loadedPostIDsFollowing = [];

    async function getPostFollowing() {
        try {
            const response = await fetch(`/api/home/postFollowing/${postLimitFollowing}`)
            const data = await response.json()

            // Hide BTN if no more posts
            if (data.morePost) {
                postBtnFollowing.style.display = 'block'
            } else {
                postBtnFollowing.style.display = 'none'
            }

            // Clear the section if not done yet
            if (loadedPostIDsFollowing.length === 0 && data.postInfo.length !== 0) {
                postSectionFollowing.innerHTML = ''
                const postCardsFollowing = document.createElement("div");
                postCardsFollowing.classList.add('post-cards')
                postCardsFollowing.classList.add('post-cards-following')
                postSectionFollowing.appendChild(postCardsFollowing);
            }

            // Select post cards
            const postCardsFollowing = document.querySelector('.post-cards-following')

            // Loop the data
            data.postInfo.forEach(element => {

                // Only run if post is not added any
                if (!loadedPostIDsFollowing.includes(element.post.postID)) {

                    // Add the ID into loaded array
                    loadedPostIDsFollowing.push(element.post.postID);

                    // Create post card and show it to user
                    const newDiv = document.createElement("a");
                    newDiv.classList.add('post-card')
                    newDiv.classList.add('box')
                    newDiv.href = `/post/${element.post.postID}`
                    newDiv.innerHTML = `
                        <img src="${element.post.image}">
                    `
                    postCardsFollowing.appendChild(newDiv);

                }

                let reSizeAble = true
                reSize()

            });
            // End loop

            // Change to the new limit
            postLimitFollowing = data.limit

        } catch (error) {
            console.error('Error sending API request:', error);
        }
    }

    getPostFollowing()
    // END OF LOADING PRIVATE POSTS ...................................................

    // START OF LOADING PRIVATE BLOGS .................................................

    // Load more post BTN
    const blogBtnFollowing = document.querySelector('.blog-following button');
    blogBtnFollowing.addEventListener('click', () => {
        getBlogFollowing()
    })

    // Get post function
    const blogSectionFollowing = document.querySelector('.blog-section-following');
    let blogLimitFollowing = 8;
    let loadedBlogIDsFollowing = [];

    async function getBlogFollowing() {
        try {
            const response = await fetch(`/api/home/blogFollowing/${blogLimitFollowing}`)
            const data = await response.json()

            // Hide BTN if no more posts
            if (data.moreBlog) {
                blogBtnFollowing.style.display = 'block'
            } else {
                blogBtnFollowing.style.display = 'none'
            }

            // Clear the section if not done yet
            if (loadedBlogIDsFollowing.length === 0 && data.blogInfo.length !== 0) {
                blogSectionFollowing.innerHTML = ''
                const blogCardsFollowing = document.createElement("div");
                blogCardsFollowing.classList.add('blog-cards-following')
                blogCardsFollowing.classList.add('blog-cards')
                blogSectionFollowing.appendChild(blogCardsFollowing);
            }

            // Select post cards
            const blogCardsFollowing = document.querySelector('.blog-cards-following')

            // Loop the data
            data.blogInfo.forEach(element => {

                // Only run if post is not added any
                if (!loadedBlogIDsFollowing.includes(element.blog.blogID)) {

                    // Add the ID into loaded array
                    loadedBlogIDsFollowing.push(element.blog.blogID);

                    // Create post card and show it to user
                    const newDiv = document.createElement("a");
                    newDiv.classList.add('blog-card');
                    newDiv.classList.add('box');
                    newDiv.href = `/blog/${element.blog.blogID}`;

                    const truncatedTitle = element.blog.title.length > 100 ? `${element.blog.title.substring(0, 100)}...` : element.blog.title;
                    const truncatedBody = element.blog.body.length > 200 ? `${element.blog.body.substring(0, 200)}...` : element.blog.body;

                    newDiv.innerHTML = `
                        <p class="title">${truncatedTitle}</p>
                        <p class="body">${truncatedBody}</p>
                    `;

                    blogCardsFollowing.appendChild(newDiv);

                }

            });
            // End loop

            // Change to the new limit
            blogLimitFollowing = data.limit

        } catch (error) {
            console.error('Error sending API request:', error);
        }
    }

    getBlogFollowing()
    // END OF LOADING PRIVATE BLOGS ...................................................
});
