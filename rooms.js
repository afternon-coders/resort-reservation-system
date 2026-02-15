// Hardcoded Rooms
const rooms = [
    {
        name: "A Frame",
        price: 6000,
        image: "static/images/a_frame.jpg",
        description: "Spacious room with breathtaking ocean views and private balcony."
    },
    {

        name: "Cottage",
        price: 2000,
        image: "static/images/cottage.jpg",
        description: "Cozy seaside cottage with stunning ocean views and relaxing atmosphere."
    },
    {
        name: "Bird House",
        price: 1500,
        image: "static/images/birdhouse.jpg",
        description: "Charming birdhouse cottage surrounded by trees and peaceful ocean views."
    },
    {
        name: "Tree House",
        price: 1500,
        image: "static/images/treehouse.jpg",
        description: "Rustic tree house retreat nestled among lush trees and fresh air."
    }
];

// Load Rooms
function loadRooms() {
    const container = document.getElementById("roomsList");
    container.innerHTML = "";

    rooms.forEach(room => {
        container.innerHTML += `
            <div class="room-card">
                <img src="${room.image}" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">${room.name}</h3>
                    <div class="room-price">₱${room.price} / night</div>
                    <p class="room-description">${room.description}</p>
                    <div class="button-group">
                        <a href="#" class="book-btn">Book Now</a>
                        <a href="#" class="details-btn">View Details</a>
                    </div>

                </div>
            </div>
        `;
    });
}

// Simple filter (just checks if date selected)
function filterRooms() {
    const checkIn = document.getElementById("checkIn").value;
    const checkOut = document.getElementById("checkOut").value;

    if (!checkIn || !checkOut) {
        alert("Please select check-in and check-out dates.");
        return;
    }

    alert("Rooms available for selected dates!");
}

// Load on page start
window.onload = loadRooms;
