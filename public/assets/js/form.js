function handleFormSubmission(formSelector) {
    const form = $(formSelector);
    const submitBtn = form.find('[type="submit"]');

    form.on("submit", function (e) {
        e.preventDefault();

        if (submitBtn.prop("disabled")) return;

        // Clear previous errors
        form.find(".is-invalid").removeClass("is-invalid");
        form.find(".invalid-feedback").remove();

        const originalText = submitBtn.html();
        submitBtn
            .prop("disabled", true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Please wait...'
            );

        const formData = new FormData(this);

        // If there's an image upload box on this form, append only images currently in preview
        if (typeof window.imgArray !== 'undefined' && window.imgArray.length > 0) {
            formData.delete('attachments[]');
            window.imgArray.forEach((file) => {
                formData.append('attachments[]', file);
            });
        }

        $.ajax({
            url: form.attr("action"),
            method: form.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: response.message || "Form submitted successfully.",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                });

                form[0].reset();
                // Clear previews and reset imgArray for this upload box
                $('.upload__img-wrap').empty();
                window.imgArray = [];
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text:
                            xhr.responseJSON.message ||
                            "Please fix the form errors below.",
                        timer: 3000,
                    });

                    $.each(errors, function (field, messages) {
                        let input = form.find(`[name="${field}"]`);

                        if (input.length === 0 && field.includes(".")) {
                            const flatName = field
                                .replace(/\./g, "\\.")
                                .replace(/\[\]/g, "");
                            input = form.find(`[name="${flatName}"]`);
                        }

                        if (input.length) {
                            input.addClass("is-invalid");
                            if (!input.next(".invalid-feedback").length) {
                                input.after(
                                    `<div class="invalid-feedback">${messages[0]}</div>`
                                );
                            }

                            // Remove error dynamically when typing or changing
                            input.on("input change", function () {
                                $(this).removeClass("is-invalid");
                                $(this).next(".invalid-feedback").remove();
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: "Something went wrong. Please try again.",
                    });
                    console.error(xhr);
                }
            },
            complete: function () {
                submitBtn.prop("disabled", false).html(originalText);
            },
        });
    });
}


$(document).ready(function () {
    $("form.ajax-form").each(function () {
        handleFormSubmission(this);
    });
});

$(document).ready(function () {
    $(document).on("click", ".btn-delete", function () {
        const button = $(this);
        const url = button.data("url");
        const refresh = button.data("refresh");
        const removeObject = button.data("remove");

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text:
                                response.message ||
                                "Record deleted successfully.",
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            if (refresh !== false) {
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    location.reload();
                                }
                            }

                            if (removeObject) {
                                $(removeObject).remove();
                            }
                        });
                    },
                    error: function (xhr) {
                        Swal.fire(
                            "Error",
                            "Something went wrong. Please try again.",
                            "error"
                        );
                        console.error(xhr);
                    },
                });
            }
        });
    });
});

function getAside() {
    url = $(event.target).data("url");

    if (url && url.length == 0) {
        return false;
    }

    $.ajax({
        url: url,
        method: "GET",
        success: function (res) {
            $("#aside-content").html(res.data.view);
            $("#open-aside-button").click();
        },
    });
}
