document.addEventListener("DOMContentLoaded", function () {
    const inputEmail = document.getElementById('inputEmail');
    const inputAppogee = document.getElementById('inputAppogee');
    const selectType = document.getElementById('selectType');
    const yearField = document.getElementById('yearField');
    const yearFieldScolarite = document.getElementById('yearFieldScolarite');
    const conventionStageFields = document.getElementById('conventionStageFields');
    const inputIdStudent = document.getElementById('inputIdStudent');
    const emailStatus = document.getElementById('emailStatus');
    const appogeeStatus = document.getElementById('appogeeStatus');
    const inputYearReleve = document.getElementById('inputYearReleve');
    let verificationDone = false;
    const submitBtn = document.querySelector('button[type="submit"]');
    document.getElementById('button1').addEventListener('click', function () {
        document.getElementById('myModal').style.display = 'block';
    });

    submitBtn.addEventListener('click', function (event) {
        event.preventDefault();

        if (!verificationDone) {
            checkEmail();
            checkAppogee();
        } else {
            const selectedType = selectType.value;
            if (selectedType === "Attestation de Reussite") {
                sendFormData();
            } else if (selectedType === "Attestation de Scolarité") {
                sendFormDataForScolarite();
            } else if (selectedType === "Convention de Stage") {
                sendFormDataForConventionStage();
            } else if (selectedType === "Relevé de Notes") {
                sendFormDataForReleveNotes(); // Appeler la fonction pour le relevé de notes
            }
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
                showFieldsForAttestation();
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
                showFieldsForAttestation();
            })
            .catch(error => console.error('Erreur:', error));
    }

    function showFieldsForAttestation() {
        const selectedType = selectType.value;
        const yearField = document.getElementById('yearField');
        const yearFieldScolarite = document.getElementById('yearFieldScolarite');
        const conventionStageFields = document.getElementById('conventionStageFields');
        const releveNotesField = document.getElementById('releveNotesField'); // Champ pour le relevé de notes

        if (selectedType === "Attestation de Reussite") {
            yearField.style.display = 'block';
            yearFieldScolarite.style.display = 'none';
            conventionStageFields.style.display = 'none';
            releveNotesField.style.display = 'none'; // Masquer le champ pour le relevé de notes
        } else if (selectedType === "Attestation de Scolarité") {
            yearField.style.display = 'none';
            yearFieldScolarite.style.display = 'block';
            conventionStageFields.style.display = 'none';
            releveNotesField.style.display = 'none';
        } else if (selectedType === "Convention de Stage") {
            yearField.style.display = 'none';
            yearFieldScolarite.style.display = 'none';
            conventionStageFields.style.display = 'block';
            releveNotesField.style.display = 'none';
        } else if (selectedType === "Relevé de Notes") {
            yearField.style.display = 'none';
            yearFieldScolarite.style.display = 'none';
            conventionStageFields.style.display = 'none';
            releveNotesField.style.display = 'block'; // Afficher le champ pour le relevé de notes
        } else {
            yearField.style.display = 'none';
            yearFieldScolarite.style.display = 'none';
            conventionStageFields.style.display = 'none';
            releveNotesField.style.display = 'none';
        }
    }



    function sendFormData() {
        const Email = inputEmail.value;
        const appogee = inputAppogee.value;
        const selectedType = selectType.value;
        const annee = document.getElementById('inputYear').value;
        const idStudent = inputIdStudent.value; // Récupérer l'ID de l'étudiant

        fetch('attestation_reussite.php', {
            method: 'POST',
            body: JSON.stringify({ Email, appogee, selectedType, annee, idstudent: idStudent }), // Envoyer l'ID de l'étudiant
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Envoi réussi:', data.message);
                    resetForm(); // Réinitialiser le formulaire en cas de succès
                } else {
                    console.error('Erreur lors de l\'envoi:', data.message);
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'envoi des données:', error);
            });
    }

    function sendFormDataForScolarite() {
        const Email = inputEmail.value;
        const appogee = inputAppogee.value;
        const selectedType = selectType.value;
        const annee = document.getElementById('inputYearScolarite').value;
        const idStudent = inputIdStudent.value;

        fetch('attestation_scolarite.php', {
            method: 'POST',
            body: JSON.stringify({ Email, appogee, selectedType, annee, idstudent: idStudent }),
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

    function sendFormDataForConventionStage() {
        const Email = inputEmail.value;
        const appogee = inputAppogee.value;
        const selectedType = selectType.value;
        const annee = document.getElementById('inputYear').value;
        const type_stage = document.getElementById('inputTypeStage').value;
        const datedebut = document.getElementById('inputDateDebut').value;
        const datefin = document.getElementById('inputDateFin').value;
        const nom_entreprise = document.getElementById('inputNomEntreprise').value;
        const adresse_entreprise = document.getElementById('inputAdresseEntreprise').value;
        const nom_encadrant = document.getElementById('inputNomEncadrant').value;
        const email_encadrant = document.getElementById('inputEmailEncadrant').value;
        const tel_encadrant = document.getElementById('inputTelEncadrant').value;
        const idStudent = inputIdStudent.value;

        const formData = {
            Email,
            appogee,
            selectedType,
            annee,
            type_stage,
            datedebut,
            datefin,
            nom_entreprise,
            adresse_entreprise,
            nom_encadrant,
            email_encadrant,
            tel_encadrant,
            idstudent: idStudent
        };

        fetch('convention_stage.php', {
            method: 'POST',
            body: JSON.stringify(formData),
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

    function sendFormDataForReleveNotes() {
        const Email = inputEmail.value;
        const appogee = inputAppogee.value;
        const selectedType = selectType.value;
        const annee = document.getElementById('inputYearReleve').value; // Récupérer l'année pour le relevé de notes
        const idStudent = inputIdStudent.value;

        fetch('releve_notes.php', {
            method: 'POST',
            body: JSON.stringify({ Email, appogee, selectedType, annee, idstudent: idStudent }),
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
        yearField.style.display = 'none'; // Masquer le champ d'année s'il est affiché
        yearFieldScolarite.style.display = 'none';
        conventionStageFields.style.display = 'none';
        releveNotesField.style.display = 'none';
        document.getElementById('inputYear').value = ''; // Effacer l'année le cas échéant
        document.getElementById('inputYearScolarite').value = '';
        document.getElementById('inputTypeStage').value = '';
        document.getElementById('inputDateDebut').value = '';
        document.getElementById('inputDateFin').value = '';
        document.getElementById('inputNomEntreprise').value = '';
        document.getElementById('inputAdresseEntreprise').value = '';
        document.getElementById('inputNomEncadrant').value = '';
        document.getElementById('inputEmailEncadrant').value = '';
        document.getElementById('inputTelEncadrant').value = '';
        document.getElementById('inputYearReleve').value = '';
        inputIdStudent.value = ''; // Effacer l'ID de l'étudiant
        emailStatus.textContent = ''; // Effacer les messages de statut
        appogeeStatus.textContent = '';

        alert('Demande envoyée avec succès!'); // Afficher un message à l'utilisateur
        verificationDone = false; // Permettre de refaire les vérifications pour une nouvelle demande
    }
    const modal = document.getElementById('myModal');
    const closeButton = document.querySelector('.close');

    document.getElementById('button1').addEventListener('click', function () {
        modal.style.display = 'block';
    });

    closeButton.addEventListener('click', function () {
        modal.style.display = 'none';
    });
});
