function ID() {
  const userIDElement = document.querySelector('#userID');
  return userID = userIDElement.innerText.trim().replace(/%20/g, '');
}

function setTime(myTime) {
  const dateTimeString = myTime;
  const dateObj = new Date(dateTimeString.replace(/-/g, "/")); // Replace hyphens with slashes
  const now = new Date();
  const diff = Math.floor((now - dateObj) / 1000); // Corrected variable name

  if (diff < 60) {
    return "Just now";
  } else if (diff < 3600) {
    const minutes = Math.floor(diff / 60);
    return `${minutes} ${minutes === 1 ? "minute" : "minutes"} ago`;
  } else if (diff < 86400) {
    const hours = Math.floor(diff / 3600);
    return `${hours} ${hours === 1 ? "hour" : "hours"} ago`;
  } else {
    const days = Math.floor(diff / 86400);
    return `${days} ${days === 1 ? "day" : "days"} ago`;
  }
}

function setImage(image) {
  if (image === null) {
    return '/images/user.jpeg'
  }
  return image
}