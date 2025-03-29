@includeIf('FormBuilder::form-builder.pages.form.layouts.index')

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Axios JS -->
<script src="{{ asset('assets/js/axios.min.js') }}"></script>

<script>
    $("#division").on("change", function () {
        let selectedDivision = $(this).val();
        if (selectedDivision) {
            axios.get('/api/v1/districts/' + selectedDivision)
                .then(response => {
                    let districts = response.data.data;
                    $("#district").empty();
                    $("#district").append($('<option>', {
                        text: 'Select District'
                    }));
                    $("#upzila").empty();
                    $("#upzila").append($('<option>', {
                        text: 'Select Upzila'
                    }));
                    districts.forEach(district => {
                        $("#district").append($('<option>', {
                            value: district.id,
                            text: district.area_nm
                        }));
                    });
                })
                .catch(error => {
                    console.error(error);
                })
        } else {
            $("#district").empty();
            $("#district").append($('<option>', {
                text: 'Select District'
            }));
            $("#upzila").empty();
            $("#upzila").append($('<option>', {
                text: 'Select Upzila'
            }));
        }
    });
    $("#district").on("change", function () {
        let selectedUpzila = $(this).val();
        if (selectedUpzila) {
            axios.get('/api/v1/upzilas/' + selectedUpzila)
                .then(response => {
                    let upzilas = response.data.data;
                    $("#upzila").empty();
                    $("#upzila").append($('<option>', {
                        text: 'Select Upzila'
                    }));
                    upzilas.forEach(district => {
                        $("#upzila").append($('<option>', {
                            value: district.id,
                            text: district.area_nm
                        }));
                    });
                })
                .catch(error => {
                    console.error(error);
                })
        } else {
            $("#upzila").empty();
            $("#upzila").append($('<option>', {
                text: 'Select Upzila'
            }));
        }
    });

    $("#khotian_type").on("change", function () {
        let selectedType = $(this).val();
        if (selectedType) {
            axios.get('/v1/khotian/' + selectedType)
                .then(response => {
                    let data = response.data.data;
                    console.log(data)
                    $("#khotian_no").val(data.khotian_no);
                    $("#dag_no").val(data.dag_no);
                    $("#dolil_no").val(data.dolil_no);
                    $("#dolil_date").val(data.dolil_date);
                })
                .catch(error => {
                    console.error(error);
                })
        } else {
            $("#khotian_no").val('');
            $("#dag_no").val('');
            $("#dolil_no").val('');
            $("#dolil_date").val('');
        }
    });

    $(document).ready(() => {
        const khotians = [
            {
                id: 1,
                name: "Namjari"
            },
            {
                id: 2,
                name: "Rs"
            }
        ];

        khotians.forEach(khotian => {
            $("#khotian_type").append($('<option>', {
                value: khotian.id,
                text: khotian.name
            }));
        });
    });
</script>
