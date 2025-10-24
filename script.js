function showDetails(studentId) {
    fetch('get_student_details.php?student_id=' + studentId)
        .then(response => response.json())
        .then(data => {
            const detailsDiv = document.getElementById('studentDetails');
            detailsDiv.innerHTML = `
                <p><strong>Full Name:</strong> ${data.first_name} ${data.last_name}</p>
                <p><strong>College:</strong> ${data.college}</p>
                <p><strong>Department:</strong> ${data.department}</p>
                <p><strong>Campus:</strong> ${data.campus}</p>
                <p><strong>Gender:</strong> ${data.gender}</p>
                <p><strong>Phone:</strong> ${data.phone}</p>
                <p><strong>Region:</strong> ${data.region}</p>
                <p><strong>Zone:</strong> ${data.zone}</p>
                <p><strong>Woreda:</strong> ${data.woreda}</p>
                <p><strong>Hometown:</strong> ${data.hometown}</p>
                <p><strong>Place of Birth:</strong> ${data.pob}</p>
                <p><strong>Date of Birth:</strong> ${data.dob}</p>
            `;
            document.getElementById('detailModal').style.display = 'flex';
        })
        .catch(error => console.error('Error:', error));
}

function closeModal() {
    document.getElementById('detailModal').style.display = 'none';
}