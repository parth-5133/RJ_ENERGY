<!-- Profile Header -->
<div class="row">
    <div class="col-12">
        <div class="card mb-6">
            <div class="user-profile-header-banner">
                <img src="../assets/img/backgrounds/profile-banner.png" alt="Banner-image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-4">
                <div class="profile-image-wrapper flex-shrink-0 mx-sm-0 mx-auto position-relative mx-5">
                    <img src="{{ isset($profile_img) && $profile_img ? asset('storage/profile_images/' . $profile_img) : asset('assets/img/avatars/1.png') }}"
                        alt="User Image" class="d-block w-px-120 h-px-120 rounded ms-0 ms-sm-5" id="uploadedAvatar" />

                    <div class="profile-image-overlay rounded ms-sm-5">
                        <div class="icon-wrapper">
                            <label for="profile_upload" class="rounded  ms-0 ms-sm-5" tabindex="0">
                                <i class="mdi mdi-camera-flip-outline icon-center me-1"></i>
                                <i class="mdi mdi-trash-can-outline icon-center account-image-reset" hidden> </i>
                            </label>
                        </div>
                    </div>
                    <input type="file" id="profile_upload" class="account-file-input" hidden />
                </div>

                <div class="flex-grow-1 mt-3 mt-lg-5 mx-sm-2">
                    <div
                        class="d-flex align-items-md-center align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="text-primary mb-2 mt-lg-6">{{ $name ?? '' }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Profile Header -->
<script type="text/javascript">
    $(document).ready(function() {
        let accountUserImage = document.getElementById('uploadedAvatar');
        const fileInput = document.querySelector('.account-file-input'),
            resetFileInput = document.querySelector('.account-image-reset');

        if (accountUserImage) {
            const resetImage = accountUserImage.src;

            fileInput.onchange = () => {

                if (fileInput.files[0]) {
                    let file = fileInput.files[0];
                    let img = new Image();
                    img.src = window.URL.createObjectURL(file);

                    img.onload = function() {
                        // Validate image dimensions
                        if (img.width <= 150 && img.height <= 150) {
                            accountUserImage.src = img.src;
                            let formData = new FormData();
                            formData.append('profile_img', file);
                            formData.append('user_uuid', '{{ request()->get('id') }}');

                            fnCallAjaxHttpPostEventWithoutJSON(
                                "{{ config('apiConstants.PROFILE_URLS.P_IMG') }}",
                                formData, true, true,
                                function(response) {
                                    if (response.status === 200) {
                                        ShowMsg("bg-success", response.message);
                                    } else {
                                        ShowMsg("bg-warning", 'The record could not be processed.');
                                    }
                                });
                        } else {
                            ShowMsg("bg-warning", 'Image must be exactly 150x150 pixels.');
                            fileInput.value = ''; // Reset file input
                        }
                    };
                }
            };
            resetFileInput.onclick = () => {
                fileInput.value = '';
                accountUserImage.src = resetImage;
            };
        }
    });
</script>
