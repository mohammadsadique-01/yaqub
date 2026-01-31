function FormValidation(button, input) {
    let inputClasses = input;

    let missingFields = new Set(); // To store unique labels
    let errorMsg = false; // Error message variable

    // Iterate through each input class
    inputClasses.forEach(className => {
        const inputs = document.getElementsByClassName(className);

        // Iterate through the inputs of a particular class
        for (let i = 0; i < inputs.length; i++) {
            const input = inputs[i];

            // Check if the input is a select element
            if (input.tagName.toLowerCase() === 'select') {
                const labelElement = input.closest('.col-md-12, .col-md-6').querySelector('label');
                const label = labelElement ? labelElement.textContent.trim() : '';
                // Check if the select option is not selected
                if (input.value.trim() === '') {
                    input.classList.add('is-invalid'); // Add 'invalid' class

                    // If the label is not already in missingFields, add it
                    if (!missingFields.has(label)) {
                        missingFields.add(label);
                        errorMsg = true;
                    }
                } else {
                    input.classList.remove('is-invalid'); // Remove 'invalid' class
                }
            } else { // For other input types
                const labelElement = input.closest('.form-group').querySelector('label');
                const label = labelElement.textContent.trim();

                // Check if the input is empty
                if (input.value.trim() === '') {
                    input.classList.add('is-invalid'); // Add 'invalid' class

                    // If the label is not already in missingFields, add it
                    if (!missingFields.has(label)) {
                        missingFields.add(label);
                        errorMsg = true;
                    }
                } else {
                    input.classList.remove('is-invalid'); // Remove 'invalid' class
                }
            }
        }
    });

    // Show the error message for empty fields
    if (errorMsg) {
        let errorMsgText = `Please fill in the following fields: ${Array.from(missingFields).join(', ')}.`;
        errorToastr(errorMsgText); // Display the error message
    }
    return errorMsg;
}











// function FormValidation(button, input){
//     let inputClasses = input;
    

//     let missingFields = new Set(); // To store unique labels
//     let errorMsg = false; // Error message variable
    
//     // Iterate through each input class
//     inputClasses.forEach(className => {
//         const inputs = document.getElementsByClassName(className);
    
//         // Iterate through the inputs of a particular class
//         for (let i = 0; i < inputs.length; i++) {
//             const input = inputs[i];
//             const labelElement = input.closest('.form-group').querySelector('label');
//             const label = labelElement.textContent.trim();
    
//             // Check if the input is empty
//             if (input.value.trim() === '') {
//                 input.classList.add('is-invalid'); // Add 'invalid' class
    
//                 // If the label is not already in missingFields, add it
//                 if (!missingFields.has(label)) {
//                     missingFields.add(label);
//                     errorMsg = true;
//                 }
//             } else {
//                 input.classList.remove('is-invalid'); // Remove 'invalid' class
//             }
//         }
//     });
    
//     // Show the error message for empty fields
//     if (errorMsg) {
//         let errorMsgText = `Please fill in the following fields: ${Array.from(missingFields).join(', ')}.`;
//         errorToastr(errorMsgText); // Display the error message
//     }
//     return errorMsg;
// }