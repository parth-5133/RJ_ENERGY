@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
@endphp
@extends('layouts.layout')
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span>Documents</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Documents Details</h5>
                    <div class="card-body">
                        <form id="commonform" name="commonform" method="POST" enctype="multipart/form-data">
                            <div class="row gy-4">
                                <input type="hidden" id="id" name="id" />
                                <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="file" id="id_proofs" name="id_proofs"
                                            placeholder="ID Proofs" />
                                        <label for="idProofs">ID Proofs <span style="color:red">*</span></label>
                                        <span class="text-danger" id="id_proofs-error"></span>
                                        <a href="#" id="idProofs-old-name" name="id_proofs" target="_blank"
                                            class="form-text"></a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="file" id="work_permits_visa"
                                            name="work_permits_visa" placeholder="Work Permits/Visa" />
                                        <label for="work_permits_visa">Work Permits/Visa <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="work_permits_visa-error"></span>

                                        <a href="#" id="work_permits_visa-old-name" name="work_permits_visa"
                                            target="_blank" class="form-text"></a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="file" id="resume_cv" name="resume_cv"
                                            placeholder="Resume/CV" />
                                        <label for="Res_resumeCv">Resume/CV</label>
                                        <span class="text-danger" id="resume_cv-error"></span>
                                        <a href="#" id="resumeCv-old-name" target="_blank" class="form-text"></a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="file" id="Off_offerLetter" name="offer_letter"
                                            placeholder="Offer Letter" />
                                        <label for="Off_offerLetter">Offer Letter</label>
                                        <span class="text-danger" id="Off_offerLetter-error"></span>
                                        <a href="#" id="offerLetter-old-name" target="_blank" class="form-text"></a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="file" id="contracts" name="contracts"
                                            placeholder="Contracts" />
                                        <label for="contracts">Contracts</label>
                                        <span class="text-danger" id="contracts-error"></span>
                                        <a href="#" id="contracts-old-name" target="_blank" class="form-text"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center p-3">
                                <button class="btn rounded-pill btn-outline-secondary me-2" type="button"
                                    onclick="window.history.back();">
                                    <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
                                </button>
                                <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light"
                                    id="submitButton">
                                    <span class="tf-icons mdi mdi-checkbox-marked-circle-outline me-1"></span>Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <script type="text/javascript">
        $(document).ready(function() {
            loadDocumentsData();
        });

        function loadDocumentsData() {
            let url =
                `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}& Params='Document'`;
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200) {
                    // Check if employeeDocument and the first element are defined
                    const employeeDocument = response.data.employeeDocument;
                    const idValue = employeeDocument && employeeDocument.length > 0 ? employeeDocument[
                            0]
                        .id : null;

                    $('#id').val(idValue);
                    setOldFileNames(response.data);
                }
            });
        }

        function setOldFileNames(data) {
            if (data && data.employeeDocument) {
                data.employeeDocument.forEach(document => {
                    const fileName = document.file_display_name;
                    const filePath = document.relative_path; // Path relative to storage/app/public
                    const fileLink = "{{ url('/storage/') }}/" + filePath; // URL to access the file

                    switch (document.document_type_name) {
                        case 'resume_cv':
                            $("#resumeCv-old-name").text(fileName).attr('href', fileLink);
                            break;
                        case 'offer_letter':
                            $("#offerLetter-old-name").text(fileName).attr('href', fileLink);
                            break;
                        case 'contracts':
                            $("#contracts-old-name").text(fileName).attr('href', fileLink);
                            break;
                        case 'id_proofs':
                            $("#idProofs-old-name").text(fileName).attr('href', fileLink);
                            break;
                        case 'work_permits_visa':
                            $("#work_permits_visa-old-name").text(fileName).attr('href', fileLink);
                            break;
                        default:
                            console.warn(`Unknown document type: ${document.document_type_name}`);
                    }
                });
            }
        }

        $("#commonform").validate({
            rules: {
                id_proofs: {
                    required: true,
                    extension: "pdf|doc|docx|jpg|png|jpeg|gif|webp"
                },
                work_permits_visa: {
                    required: true,
                    extension: "pdf|doc|docx|jpg|png|jpeg|gif|webp"
                }
            },
            messages: {
                id_proofs: {
                    required: "ID Proofs are required.",
                    extension: "ID Proofs must be a file of type: pdf, doc, docx, jpg, png, jpeg, gif, or webp."
                },
                work_permits_visa: {
                    required: "Work Permits/Visa are required.",
                    extension: "Work Permits/Visa must be a file of type: pdf, doc, docx, jpg, png, jpeg, gif, or webp."
                }
            },
            errorPlacement: function(error, element) {
                var errorId = element.attr("name") + "-error";
                $("#" + errorId).text(error.text());
                $("#" + errorId).show();
                element.addClass("is-invalid");
            },
            success: function(label, element) {
                var errorId = $(element).attr("name") + "-error";
                $("#" + errorId).text("");
                $("#" + errorId).hide();
                $(element).removeClass("is-invalid");
            },
            submitHandler: function(form) {
                event.preventDefault();
                // Proceed with AJAX form submission
                var formData = new FormData(form);
                const Url = `{{ config('apiConstants.PROFILE_URLS.DOCUMENTS') }}/{{ request()->get('id') }}`;

                fnCallAjaxHttpPostEventWithoutJSON(Url, formData, true, true, function(response) {
                    if (response.status === 200) {
                        location.reload();
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-warning", 'The record could not be processed.');
                    }
                });
            }
        });
    </script>
@endsection
