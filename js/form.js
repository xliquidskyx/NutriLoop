$(document).ready(function () {
    let currentStep = 0;
    const steps = $(".main-form");

    //tworzymy funkcję, która umożliwa poruszanie się po formularzu
    function showStep(step) {
        steps.removeClass("active").eq(step).addClass("active");
    }

    // walidacja pól
    function validate(currentStep) {
        const currentFields = steps.eq(currentStep).find("input");
        let valid = true;
        
        //sprawdzamy poprawność pól 
        currentFields.each(function () {
            if (!this.checkValidity()) {
                valid = false;
                $(this).addClass("invalid");
            } else {
                $(this).removeClass("invalid");
            }
            });
        
        //sprawdzamy czy hasła się zgadzają
        const haslo =  $('.password').val();
        const powtorz_haslo = $('.repeated').val();

        if (!(haslo === powtorz_haslo)) {
            valid = false
            $('input[type=password]').addClass("invalid");
        }
        
        return valid;
                  
    }

    $(".next-step").click(function () {
        valid = validate(currentStep);

        if (valid && currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
        });


    $(".prev-step").click(function () {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });
});