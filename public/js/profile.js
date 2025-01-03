function openModal() {
    document.getElementById('updateUsernameModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('updateUsernameModal').style.display = 'none';
}

// Close modal when clicking outside the modal content
window.onclick = function(event) {
    let modal = document.getElementById('updateUsernameModal');
    if (event.target == modal) {
        closeModal();
    }
}


/*function openModal1() {
    document.getElementById('updatePasswordModal').style.display = 'block';
}

function closeModal1() {
    document.getElementById('updatePasswordModal').style.display = 'none';
}

// Close modal when clicking outside the modal content
window.onclick = function(event) {
    let modal = document.getElementById('updatePasswordModal');
    if (event.target == modal) {
        closeModal1();
    }
}
    */

// Open the modal
function openModal1() {
    document.getElementById('updatePasswordModal').style.display = 'block';
}

// Close the modal
function closeModal1() {
    document.getElementById('updatePasswordModal').style.display = 'none';
}

// Close modal if clicked outside the modal content
window.addEventListener('click', function(event) {
    let modal = document.getElementById('updatePasswordModal');
    if (event.target === modal) {
        closeModal1();
    }
});

// Close modal after form submission (optional)
//document.querySelector('form').addEventListener('submit', function() {
 //   closeModal1();
//});

function openModal2() {
    document.getElementById('updateAddressModal').style.display = 'block';
}

function closeModal2() {
    document.getElementById('updateAddressModal').style.display = 'none';
}

window.addEventListener('click', function(event) {
    let modal = document.getElementById('updateAddressModal');
    if (event.target === modal) {
        closeModal2();
    }
});

