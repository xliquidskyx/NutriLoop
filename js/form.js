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
                  
        currentFields.each(function () {
            if (!this.checkValidity()) {
                valid = false;
                $(this).addClass("invalid");
            } else {
                $(this).removeClass("invalid");
            }
            });
        
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

    $("#multiStepForm").on("submit", function(e) {
        e.preventDefault();

        valid = validate(currentStep);
        if (valid) {
            //pobieranie danych z formularza
            const formData = $(this).serialize();

            //wysylanie danych z formularza do bazy przez użycie AJAX
            $.ajax({
                url: "utworz_konto.php",
                type: "POST",
                data: formData,
                success: function () {
                    alert("Utworzono konto");
                },
                error: function () {
                    alert("Nie utworzono konta");
                },
            })
        }
    });
});