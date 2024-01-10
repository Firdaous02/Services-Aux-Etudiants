document.addEventListener("DOMContentLoaded", function () {
// Récupérer le bouton et la fenêtre modale
/*const reclamationButton = document.getElementById('reclamationButton');
const reclamationModal = document.getElementById('reclamationModal');*/
const inputEmail = document.getElementById('inputEmail');
const inputAppogee = document.getElementById('inputAppogee');
const selectType = document.getElementById('selectType');
const emailStatus = document.getElementById('emailStatus');
const appogeeStatus = document.getElementById('appogeeStatus');
const inputReclamation = document.getElementById('inputReclamation');


    let verificationDone = false;
    const submitBtn = document.querySelector('button[type="submit"]');
    document.getElementById('reclamationButton').addEventListener('click', function () {
        document.getElementById('reclamationModal').style.display = 'block';
    });
    submitBtn.addEventListener('click', function (event) {
        event.preventDefault();

        if (!verificationDone) {
            checkEmail();
            checkAppogee();
        } else {
            sendFormDataForReclamation(); // Appeler la fonction pour le relevé de notes
        }
    });
    function checkEmail() {
        const email = inputEmail.value;
        fetch('verification_email.php', {
            method: 'POST',
            body: JSON.stringify({ email: email }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    emailStatus.textContent = 'Email correct';
                    emailStatus.style.color = 'green';
                } else {
                    emailStatus.textContent = 'Email incorrect';
                    emailStatus.style.color = 'red';
                }

                verificationDone = true;

                showFieldsForReclamation();
            })
            .catch(error => console.error('Erreur:', error));
    }

    function checkAppogee() {
        const appogee = inputAppogee.value;
        fetch('verification_appogee.php', {
            method: 'POST',
            body: JSON.stringify({ appogee: appogee }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    appogeeStatus.textContent = 'Apogée correct';
                    appogeeStatus.style.color = 'green';
                } else {
                    appogeeStatus.textContent = 'Apogée incorrect';
                    appogeeStatus.style.color = 'red';
                }

                verificationDone = true;

                showFieldsForReclamation();
            })
            .catch(error => console.error('Erreur:', error));
    }
    function showFieldsForReclamation() {
        const selectedType = selectType.value;

        const reclamationField = document.getElementById('reclamationField'); // Champ pour le relevé de notes
        reclamationField.style.display = 'block'; // Afficher le champ pour le relevé de notes
        if (selectedType === "Attestation de Reussite") {
            reclamationField.style.display = 'block'; // Afficher le champ pour le relevé de notes

        } else if (selectedType === "Attestation de Scolarité") {
            reclamationField.style.display = 'block'; // Afficher le champ pour le relevé de notes

        } else if (selectedType === "Convention de Stage") {
            reclamationField.style.display = 'block'; // Afficher le champ pour le relevé de notes

        } else if (selectedType === "Relevé de Notes") {
            reclamationField.style.display = 'block'; // Afficher le champ pour le relevé de notes

        } else {
            reclamationField.style.display = 'none'; 

        }
       
    }

    function sendFormDataForReclamation() {
        const Email = inputEmail.value;
        const appogee = inputAppogee.value;
        const selectedType = selectType.value;
        const rec = document.getElementById('inputReclamation').value; // Récupérer l'année pour le relevé de notes
        const idStudent = inputIdStudent.value;

        fetch('reclamation.php', {
            method: 'POST',
            body: JSON.stringify({ Email, appogee, selectedType, rec, idstudent: idStudent }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Envoi réussi:', data.message);
                    resetForm();
                } else {
                    console.error('Erreur lors de l\'envoi:', data.message);
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'envoi des données:', error);
            });
    }


    function resetForm() {
        inputEmail.value = ''; // Effacer les valeurs des champs
        inputAppogee.value = '';
        selectType.value = '';
        reclamationField.style.display = 'none';

        document.getElementById('inputReclamation').value = '';
        inputIdStudent.value = ''; // Effacer l'ID de l'étudiant
        emailStatus.textContent = ''; // Effacer les messages de statut
        appogeeStatus.textContent = '';

        alert('Réclamation envoyée avec succès!'); // Afficher un message à l'utilisateur
        verificationDone = false; // Permettre de refaire les vérifications pour une nouvelle demande
    }

    const modal = document.getElementById('reclamationModal');
    const closeButton = document.querySelector('.close1');

    document.getElementById('reclamationButton').addEventListener('click', function () {
        modal.style.display = 'block';
    });

    closeButton.addEventListener('click', function () {
        modal.style.display = 'none';
    });

});
