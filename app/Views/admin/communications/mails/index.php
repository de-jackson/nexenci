<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="row gx-0">
    <div class="col-lg-12">
        <div class="card mb-0 h-auto border-0">
            <div class="card-body">
                <div class="row gx-0">
                    <!-- column -->
                    <div class="col-xxl-2 col-xl-3 col-lg-3 email-left-body">
                        <div class="email-left-box dz-scroll pt-3 ps-0" id="email-left">
                            <div class="p-0">
                                <a href="javascript:void(0);" onclick="compose_email()" class="btn text-white btn-block"><i class="fa-solid fa-plus me-2"></i>Compose Email</a>
                            </div>
                            <div class="mail-list rounded ">
                                <a href="javascript:void(0);" class="list-group-item mailLabel-inbox active" id="mailLabel-inbox" onclick="fetch_mails('label','inbox')">
                                    <svg class="align-middle" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.9028 8.85114L13.4596 12.4642C12.6201 13.1302 11.4389 13.1302 10.5994 12.4642L6.11865 8.85114" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.9089 21C19.9502 21.0084 22 18.5095 22 15.4384V8.57001C22 5.49883 19.9502 3 16.9089 3H7.09114C4.04979 3 2 5.49883 2 8.57001V15.4384C2 18.5095 4.04979 21.0084 7.09114 21H16.9089Z" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Inbox
                                    <span class="badge badge-info badge-sm float-end rounded" id="inboxCount"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item mailLabel-sent" id="mailLabel-sent" onclick="fetch_mails('label','sent')">
                                    <svg class="align-middle" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.8325 8.17463L10.109 13.9592L3.59944 9.88767C2.66675 9.30414 2.86077 7.88744 3.91572 7.57893L19.3712 3.05277C20.3373 2.76963 21.2326 3.67283 20.9456 4.642L16.3731 20.0868C16.0598 21.1432 14.6512 21.332 14.0732 20.3953L10.106 13.9602" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Sent
                                    <span class="badge badge-info badge-sm float-end rounded" id="sentCount"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item mailLabel-favorite" id="mailLabel-favorite" onclick="fetch_mails('label','favorite')">
                                    <svg class="align-middle" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.1043 4.17701L14.9317 7.82776C15.1108 8.18616 15.4565 8.43467 15.8573 8.49218L19.9453 9.08062C20.9554 9.22644 21.3573 10.4505 20.6263 11.1519L17.6702 13.9924C17.3797 14.2718 17.2474 14.6733 17.3162 15.0676L18.0138 19.0778C18.1856 20.0698 17.1298 20.8267 16.227 20.3574L12.5732 18.4627C12.215 18.2768 11.786 18.2768 11.4268 18.4627L7.773 20.3574C6.87023 20.8267 5.81439 20.0698 5.98724 19.0778L6.68385 15.0676C6.75257 14.6733 6.62033 14.2718 6.32982 13.9924L3.37368 11.1519C2.64272 10.4505 3.04464 9.22644 4.05466 9.08062L8.14265 8.49218C8.54354 8.43467 8.89028 8.18616 9.06937 7.82776L10.8957 4.17701C11.3477 3.27433 12.6523 3.27433 13.1043 4.17701Z" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Favorite
                                    <span class="badge badge-info badge-sm float-end rounded" id="favoriteCount"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item mailLabel-draft" id="mailLabel-draft" onclick="fetch_mails('label','draft')">
                                    <svg class="align-middle" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7379 2.76181H8.08493C6.00493 2.75381 4.29993 4.41181 4.25093 6.49081V17.2038C4.20493 19.3168 5.87993 21.0678 7.99293 21.1148C8.02393 21.1148 8.05393 21.1158 8.08493 21.1148H16.0739C18.1679 21.0298 19.8179 19.2998 19.8029 17.2038V8.03781L14.7379 2.76181Z" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.4751 2.75V5.659C14.4751 7.079 15.6231 8.23 17.0431 8.234H19.7981" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14.2882 15.3585H8.88818" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12.2432 11.606H8.88721" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Draft
                                    <span class="badge badge-info badge-sm float-end rounded" id="draftsCount"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item mailLabel-important" id="mailLabel-important" onclick="fetch_mails('label','important')">
                                    <svg class="align-middle" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.4425 10.0575L10.065 15.435C9.92569 15.5745 9.76026 15.6851 9.57816 15.7606C9.39606 15.8361
													9.20087 15.8749 9.00375 15.8749C8.80663 15.8749 8.61144 15.8361 8.42934 15.7606C8.24724 15.6851 8.08181 15.5745 7.9425 15.435L1.5 9V1.5H9L15.4425
													7.9425C15.7219 8.22354 15.8787 8.60372 15.8787 9C15.8787 9.39628 15.7219 9.77646 15.4425 10.0575Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.25 5.25H5.268" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Important
                                    <span class="badge badge-info badge-sm float-end rounded" id="importantCount"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item mailLabel-spam" id="mailLabel-spam" onclick="fetch_mails('label','spam')">
                                    <svg class="align-middle" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75012C17.108 2.75012 21.25 6.89112 21.25 12.0001C21.25 17.1081 17.108 21.2501 12 21.2501C6.891 21.2501 2.75 17.1081 2.75 12.0001C2.75 6.89112 6.891 2.75012 12 2.75012Z" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.9951 8.20422V12.6232" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.995 15.7961H12.005" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Spam
                                    <span class="badge bg-danger badge-sm float-end rounded" id="spamCount"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item mailLabel-archive" id="mailLabel-archive" onclick="fetch_mails('label','archive')">
                                    <svg class="align-middle" width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.16683 12.8333H12.8335C13.0766 12.8333 13.3098 12.7368 13.4817 12.5648C13.6536 12.3929 13.7502 12.1598 13.7502 11.9167C13.7502 11.6736 13.6536 11.4404 13.4817 11.2685C13.3098 11.0966 13.0766 11 12.8335 11H9.16683C8.92371 11 8.69056 11.0966 8.51865 11.2685C8.34674 11.4404 8.25016 11.6736 8.25016 11.9167C8.25016 12.1598 8.34674 12.3929 8.51865 12.5648C8.69056 12.7368 8.92371 12.8333 9.16683 12.8333V12.8333ZM17.4168 2.75H4.5835C3.85415 2.75 3.15468 3.03973 2.63895 3.55546C2.12323 4.07118 1.8335 4.77065 1.8335 5.5V8.25C1.8335 8.49312 1.93007 8.72627 2.10198 8.89818C2.27389 9.07009 2.50705 9.16667 2.75016 9.16667H3.66683V16.5C3.66683 17.2293 3.95656 17.9288 4.47229 18.4445C4.98801 18.9603 5.68748 19.25 6.41683 19.25H15.5835C16.3128 19.25 17.0123 18.9603 17.528 18.4445C18.0438 17.9288 18.3335 17.2293 18.3335 16.5V9.16667H19.2502C19.4933 9.16667 19.7264 9.07009 19.8983 8.89818C20.0703 8.72627 20.1668 8.49312 20.1668 8.25V5.5C20.1668 4.77065 19.8771 4.07118 19.3614 3.55546C18.8456 3.03973 18.1462 2.75 17.4168 2.75ZM16.5002 16.5C16.5002 16.7431 16.4036 16.9763 16.2317 17.1482C16.0598 17.3201 15.8266 17.4167 15.5835 17.4167H6.41683C6.17371 17.4167 5.94056 17.3201 5.76865 17.1482C5.59674 16.9763 5.50016 16.7431 5.50016 16.5V9.16667H16.5002V16.5ZM18.3335 7.33333H3.66683V5.5C3.66683 5.25688 3.76341 5.02373 3.93531 4.85182C4.10722 4.67991 4.34038 4.58333 4.5835 4.58333H17.4168C17.6599 4.58333 17.8931 4.67991 18.065 4.85182C18.2369 5.02373 18.3335 5.25688 18.3335 5.5V7.33333Z" fill="#666666" />
                                    </svg>
                                    Archive
                                    <span class="badge bg-info badge-sm float-end rounded" id="archiveCount"></span>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item mailLabel-trash" id="mailLabel-trash" onclick="fetch_mails('label','trash')">
                                    <svg class="align-middle" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.3248 9.4682C19.3248 9.4682 18.7818 16.2032 18.4668 19.0402C18.3168 20.3952 17.4798 21.1892 16.1088 21.2142C13.4998 21.2612 10.8878 21.2642 8.27979 21.2092C6.96079 21.1822 6.13779 20.3782 5.99079 19.0472C5.67379 16.1852 5.13379 9.4682 5.13379 9.4682" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20.708 6.23969H3.75" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M17.4406 6.23967C16.6556 6.23967 15.9796 5.68467 15.8256 4.91567L15.5826 3.69967C15.4326 3.13867 14.9246 2.75067 14.3456 2.75067H10.1126C9.53358 2.75067 9.02558 3.13867 8.87558 3.69967L8.63258 4.91567C8.47858 5.68467 7.80258 6.23967 7.01758 6.23967" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Trash
                                    <span class="badge bg-danger badge-sm float-end rounded" id="trashCount"></span>
                                </a>
                            </div>
                            <div class="mail-list rounded overflow-hidden mt-4">
                                <div class="intro-title d-flex justify-content-between my-0">
                                    <h5>Categories</h5>
                                </div>
                                <?php
                                if (count($tags)) :
                                    foreach ($tags as $tag) :
                                ?>
                                        <a href="javascript:void(0);" class="list-group-item change mailLabel-<?= strtolower($tag['id']); ?>" id="mailLabel-<?= strtolower($tag['id']); ?>" onclick="fetch_mails('tag', '<?= $tag['id'] ?>', '<?= $tag['tag_name'] ?>')">
                                            <i class="fa-regular fa-tag align-middle fw-semibold text-<?= $tag['color']; ?>"></i><?= ucfirst($tag['tag_name']); ?>
                                        </a>
                                <?php endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- /column -->
                    <div class="col-xxl-10 col-xl-9 col-lg-9">
                        <div class="email-right-box ms-0">
                            <!-- compose new mail & display email lists -->
                            <div id="mailsContent">
                                <div class="d-flex align-items-center px-3">
                                    <h4 class="card-title d-sm-none d-block">Email</h4>
                                    <div class="email-tools-box float-end mb-2">
                                        <i class="fa-solid fa-list-ul"></i>
                                    </div>
                                </div>
                                <!-- composer mail start -->
                                <div class="compose-wrapper mt-3 " id="compose-content" style="display: none;">
                                    <div class="compose-content">
                                        <form action="" id="compose-form">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" readonly />
                                            <input type="hidden" name="action" readonly />
                                            <div id="composeData" style="display: none;">
                                                <div class="row mb-3">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="sender_id" class="mailLabel-form-">From: <span class="text-danger">*</span></label>
                                                            <div class="col-md-12">
                                                                <input type="email" class="form-control" name="sender_id" id="sender_id" value="<?= $user['email'] ?>">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="tag" class="form-label text-dark fw-semibold">Tag:</label>
                                                            <div class="col-md-12">
                                                                <select name="tag_id" id="tag_id" class="form-control select2bs4">
                                                                    <option value="">-- select --</option>
                                                                    <?php
                                                                    if (count($tags) > 0) :
                                                                        foreach ($tags as $tag) : ?>
                                                                            <option value="<?= $tag['id']; ?>">
                                                                                <?= $tag['tag_name']; ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="recipient_account" class="mailLabel-form-">Recipient Account<sup><i class="ri-star-s-fill text-success fs-8"></i></sup></label>
                                                            <div class="col-md-12">
                                                                <select class="form-control select2bs4" name="recipient_account" id="recipient_account" onchange="get_recipients(this.value)">
                                                                    <option value="">-- select --</option>
                                                                    <?php
                                                                    if (count($accounts) > 0) :
                                                                        foreach ($accounts as $key => $value) : ?>
                                                                            <option value="<?= $key; ?>">
                                                                                <?= $value; ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="recipient_id" class="mailLabel-form-">Recipient(s): <span class="text-danger">*</span></label>
                                                            <div class="col-md-12">
                                                                <select name="recipient_id[]" id="recipient_id" class="form-control select2bs4" multiple>
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-xl-12 mb-2">
                                                        <div class="form-group">
                                                            <label for="subject" class="mailLabel-form-">Subject: <span class="text-danger">*</span></label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject:">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-xl-12">
                                                    <div class="form-group">
                                                        <label class="col-mailLabel-form-">Message: <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <textarea name="message" class="form-control" id="addSummernote"></textarea>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <textarea id="email-compose-editor" class="textarea_editor form-control bg-transparent" rows="5" placeholder="Enter text ..."></textarea> -->
                                            </div>
                                        </form>
                                        <h5 class="my-3"><i class="fa fa-paperclip me-2"></i> Attatchment</h5>
                                        <form action="#" class="dropzone">
                                            <div class="fallback">
                                                <input name="file" type="file" multiple>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="text-start mt-4 mb-3">
                                        <button class="btn btn-danger light btn-sl-sm" type="button"><span class="me-2"><i class="fa fa-times"></i></span>Discard</button>
                                        <button type="button" class="btn btn-primary btn-sl-sm me-2" id="btnSend" onclick="send_email()"><span class="me-2"><i class="fa fa-paper-plane"></i></span>Send</button>
                                    </div>
                                </div>
                                <!-- list mails -->
                                <div class="mt-3" id="mailList-content">
                                    <div role="toolbar" class="toolbar ms-1 ms-sm-0">
                                        <div class="saprat">
                                            <div class="d-flex align-items-center">
                                                <div class="btn-group ">
                                                    <div class="form-check custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </div>
                                                <!-- <ul class="nav nav-pills  " id="pills-tab" role="tablist">
                                                    <li class="nav-item btn-group ms-0" role="presentation">
                                                        <button class="btn effect mx-2 nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M15.8798 1.66667H4.21313C3.55032 1.6674 2.91485 1.93102 2.44617 2.3997C1.97748 2.86839 1.71386 3.50385 1.71313 4.16667V15.8333C1.71386 16.4962 1.97748 17.1316 2.44617 17.6003C2.91485 18.069 3.55032 18.3326 4.21313 18.3333H15.8798C16.5426 18.3326 17.1781 18.069 17.6468 17.6003C18.1154 17.1316 18.3791 16.4962 18.3798 15.8333V4.16667C18.3791 3.50385 18.1154 2.86839 17.6468 2.3997C17.1781 1.93102 16.5426 1.6674 15.8798 1.66667ZM4.21313 3.33334H15.8798C16.1007 3.33356 16.3126 3.42143 16.4688 3.57766C16.625 3.73389 16.7129 3.94573 16.7131 4.16667V10.8333H14.6591C14.3847 10.8331 14.1145 10.9008 13.8725 11.0303C13.6306 11.1597 13.4244 11.3471 13.2724 11.5755L12.1005 13.3333H7.99243L6.82056 11.5755C6.66853 11.3471 6.46237 11.1597 6.22042 11.0303C5.97848 10.9008 5.70826 10.8331 5.43384 10.8333H3.3798V4.16667C3.38002 3.94573 3.46789 3.73389 3.62412 3.57766C3.78035 3.42143 3.99219 3.33356 4.21313 3.33334ZM15.8798 16.6667H4.21313C3.99219 16.6664 3.78035 16.5786 3.62412 16.4223C3.46789 16.2661 3.38002 16.0543 3.3798 15.8333V12.5H5.43384L6.60572 14.2578C6.75774 14.4863 6.96391 14.6736 7.20585 14.8031C7.4478 14.9326 7.71802 15.0002 7.99243 15H12.1005C12.3749 15.0002 12.6451 14.9326 12.8871 14.8031C13.129 14.6736 13.3352 14.4863 13.4872 14.2578L14.6591 12.5H16.7131V15.8333C16.7129 16.0543 16.625 16.2661 16.4688 16.4223C16.3126 16.5786 16.1007 16.6664 15.8798 16.6667Z" fill="#666666" />
                                                            </svg>
                                                            Important
                                                        </button>
                                                    </li>
                                                    <li class="nav-item btn-group" role="presentation">
                                                        <button class="btn  mx-2 effect nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M4.97602 19.2308H15.0241C17.3438 19.2308 19.2308 17.3437 19.2308 15.024V4.97596C19.2308 2.65624 17.3438 0.769226 15.0241 0.769226H4.97602C2.65631 0.769226 0.769287 2.65624 0.769287 4.97596V15.024C0.769287 17.3437 2.65631 19.2308 4.97602 19.2308ZM2.30775 4.97596C2.30775 3.50473 3.50441 2.30769 4.97602 2.30769H15.0241C16.4957 2.30769 17.6924 3.50473 17.6924 4.97596V15.024C17.6924 16.4953 16.4957 17.6923 15.0241 17.6923H4.97602C3.50441 17.6923 2.30775 16.4953 2.30775 15.024V4.97596Z" fill="#666666" />
                                                                <path d="M5.87963 15.5848H14.1211C14.6199 15.5848 15.0841 15.361 15.3959 14.9711C15.7099 14.5782 15.8263 14.0711 15.7151 13.5799C15.2867 11.6913 13.9951 10.2069 12.309 9.49022C12.7777 8.95086 13.0716 8.25552 13.0716 7.48648C13.0716 5.7929 11.6939 4.41519 9.99997 4.41519C8.30601 4.41519 6.92831 5.7929 6.92831 7.48648C6.92831 8.25552 7.22222 8.95086 7.69097 9.49022C6.0048 10.2069 4.7133 11.6915 4.28483 13.5802C4.17365 14.0711 4.29009 14.5778 4.60409 14.9707C4.91584 15.361 5.38083 15.5848 5.87963 15.5848ZM8.46677 7.48648C8.46677 6.64138 9.15487 5.95365 9.99997 5.95365C10.8451 5.95365 11.5332 6.64138 11.5332 7.48648C11.5332 8.33158 10.8451 9.01931 9.99997 9.01931C9.15487 9.01931 8.46677 8.33158 8.46677 7.48648ZM9.99997 10.5578C12.032 10.5578 13.765 11.9404 14.2142 13.9198C14.224 13.9618 14.2082 13.9923 14.1939 14.0107C14.1654 14.0464 14.1323 14.0464 14.1211 14.0464H5.87963C5.86836 14.0464 5.83456 14.0464 5.80601 14.0107C5.79174 13.9923 5.77597 13.9618 5.78573 13.9201C6.23495 11.9404 7.96797 10.5578 9.99997 10.5578Z" fill="#666666" />
                                                            </svg>
                                                            Socials
                                                        </button>
                                                    </li>
                                                    <li class="nav-item btn-group" role="presentation">
                                                        <button class="btn  mx-sm-2 mx-0 effect nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                                                            <svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M19.5594 3.68912H16.0558C15.855 3.68912 15.66 3.76101 15.5071 3.89074L14.2974 4.92116L13.0951 3.89239C12.9414 3.76101 12.7464 3.68912 12.5448 3.68912H2.44958C1.49765 3.68912 0.723389 4.46338 0.723389 5.4153V8.40824C0.723389 9.08335 1.15969 9.6659 1.83479 9.89232C2.18267 10.0088 2.4157 10.3311 2.4157 10.6955C2.4157 11.0616 2.1835 11.3838 1.83644 11.4987C1.17043 11.7201 0.723389 12.3167 0.723389 12.9828V16.5847C0.723389 17.5366 1.49765 18.3109 2.44958 18.3109H12.5448C12.7472 18.3109 12.943 18.2382 13.0967 18.106L14.2974 17.0731L15.5055 18.1076C15.6592 18.239 15.8542 18.3109 16.0558 18.3109H19.5511C20.5022 18.3109 21.2765 17.5366 21.2765 16.5847V12.9828C21.2765 12.306 20.8402 11.7234 20.1634 11.4987C19.8172 11.3838 19.5842 11.0616 19.5842 10.6955C19.5842 10.3319 19.818 10.0088 20.1659 9.89232C20.841 9.6659 21.2765 9.08335 21.2765 8.40824V5.40704C21.2765 4.46008 20.5064 3.68912 19.5594 3.68912ZM19.5842 8.3033C18.5703 8.66275 17.8919 9.6188 17.8919 10.6955C17.8919 11.7739 18.567 12.7274 19.5834 13.0894L19.5511 16.6186H16.3681L14.8461 15.3155C14.5288 15.0444 14.0611 15.0461 13.7437 15.3171L12.2324 16.6178L2.4157 16.5847V13.0894C3.42959 12.7324 4.108 11.7763 4.108 10.6955C4.108 9.62128 3.43373 8.66771 2.41652 8.30247L2.44958 5.38143H12.2324L13.7454 6.67627C14.0619 6.94648 14.5263 6.94813 14.8444 6.67792L16.3648 5.3839L19.5842 5.40704V8.3033Z" fill="#666666" />
                                                                <path d="M14.3337 9.46841C13.866 9.46841 13.4876 9.84687 13.4876 10.3146V11.6854C13.4876 12.1531 13.866 12.5316 14.3337 12.5316C14.8014 12.5316 15.1799 12.1531 15.1799 11.6854V10.3146C15.1799 9.84687 14.8014 9.46841 14.3337 9.46841ZM10.7359 9.32546L10.0162 9.22051L9.69477 8.56937C9.69477 8.56937 9.69477 8.56937 9.69477 8.56855C9.47001 8.11407 9.01554 7.83147 8.50817 7.83147C8.00081 7.83147 7.54633 8.11407 7.32158 8.56937L7.00014 9.22051L6.28206 9.32463C5.78048 9.39735 5.37063 9.74192 5.21363 10.2245C5.05663 10.7071 5.18471 11.2277 5.54829 11.5813L6.06804 12.0887L5.94575 12.8043C5.85981 13.305 6.06143 13.8008 6.47212 14.0991C6.88243 14.397 7.41676 14.4352 7.86447 14.1999L8.50817 13.8611L9.15023 14.1991C9.59892 14.4354 10.1319 14.3974 10.5442 14.0991C10.9549 13.8008 11.1565 13.305 11.0706 12.8051L10.9483 12.0887L11.4689 11.5813C11.8325 11.226 11.9597 10.7062 11.8027 10.2237C11.6457 9.7411 11.2359 9.39735 10.7359 9.32546ZM9.62949 11.0112C9.31714 11.3144 9.17419 11.7524 9.24773 12.1837L9.27665 12.3539L9.12626 12.2746C8.74101 12.0704 8.28108 12.0695 7.89256 12.2729L7.73969 12.3531L7.76862 12.1829C7.84216 11.7524 7.6992 11.3144 7.38851 11.0128L7.26456 10.8922L7.43478 10.8674C7.8653 10.8054 8.23797 10.5352 8.43133 10.1435L8.50817 9.98817L8.58502 10.1443C8.77838 10.5352 9.15105 10.8054 9.57991 10.8665L9.75179 10.8922L9.62949 11.0112Z" fill="#666666" />
                                                            </svg>
        
                                                            Promotion
                                                        </button>
                                                    </li>
                                                </ul> -->
                                            </div>
                                            <div class="mail-tools">
                                                <a href="javascrip:void(0);" onclick="bulky_mailUpdate('label', 'archive')" title="Bulk Archive">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18.1668 21.8333H21.8335C22.0766 21.8333 22.3098 21.7368 22.4817 21.5648C22.6536 21.3929 22.7502 21.1598 22.7502 20.9167C22.7502 20.6736 22.6536 20.4404 22.4817 20.2685C22.3098 20.0966 22.0766 20 21.8335 20H18.1668C17.9237 20 17.6906 20.0966 17.5186 20.2685C17.3467 20.4404 17.2502 20.6736 17.2502 20.9167C17.2502 21.1598 17.3467 21.3929 17.5186 21.5648C17.6906 21.7368 17.9237 21.8333 18.1668 21.8333ZM26.4168 11.75H13.5835C12.8542 11.75 12.1547 12.0397 11.639 12.5555C11.1232 13.0712 10.8335 13.7707 10.8335 14.5V17.25C10.8335 17.4931 10.9301 17.7263 11.102 17.8982C11.2739 18.0701 11.507 18.1667 11.7502 18.1667H12.6668V25.5C12.6668 26.2293 12.9566 26.9288 13.4723 27.4445C13.988 27.9603 14.6875 28.25 15.4168 28.25H24.5835C25.3128 28.25 26.0123 27.9603 26.528 27.4445C27.0438 26.9288 27.3335 26.2293 27.3335 25.5V18.1667H28.2502C28.4933 18.1667 28.7264 18.0701 28.8983 17.8982C29.0703 17.7263 29.1668 17.4931 29.1668 17.25V14.5C29.1668 13.7707 28.8771 13.0712 28.3614 12.5555C27.8456 12.0397 27.1462 11.75 26.4168 11.75ZM25.5002 25.5C25.5002 25.7431 25.4036 25.9763 25.2317 26.1482C25.0598 26.3201 24.8266 26.4167 24.5835 26.4167H15.4168C15.1737 26.4167 14.9406 26.3201 14.7686 26.1482C14.5967 25.9763 14.5002 25.7431 14.5002 25.5V18.1667H25.5002V25.5ZM27.3335 16.3333H12.6668V14.5C12.6668 14.2569 12.7634 14.0237 12.9353 13.8518C13.1072 13.6799 13.3404 13.5833 13.5835 13.5833H26.4168C26.6599 13.5833 26.8931 13.6799 27.065 13.8518C27.2369 14.0237 27.3335 14.2569 27.3335 14.5V16.3333Z" fill="var(--primary)" />
                                                    </svg>
                                                </a>
                                                <a href="javascrip:void(0);" onclick="bulky_mailUpdate('label', 'spam')" title="Bulk Spam">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75012C17.108 2.75012 21.25 6.89112 21.25 12.0001C21.25 17.1081 17.108 21.2501 12 21.2501C6.891 21.2501 2.75 17.1081 2.75 12.0001C2.75 6.89112 6.891 2.75012 12 2.75012Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M11.9951 8.20422V12.6232" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M11.995 15.7961H12.005" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                                <a href="javascrip:void(0);" onclick="bulky_mailUpdate('status', 'inbox')" title="Read Status">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.9028 8.85114L13.4596 12.4642C12.6201 13.1302 11.4389 13.1302 10.5994 12.4642L6.11865 8.85114" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.9089 21C19.9502 21.0084 22 18.5095 22 15.4384V8.57001C22 5.49883 19.9502 3 16.9089 3H7.09114C4.04979 3 2 5.49883 2 8.57001V15.4384C2 18.5095 4.04979 21.0084 7.09114 21H16.9089Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                                <a href="javascrip:void(0);" onclick="bulky_mailUpdate('delete', 'trash')" title="Bulk Delete">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18.1667 25.5C18.4098 25.5 18.6429 25.4035 18.8148 25.2316C18.9868 25.0596 19.0833 24.8265 19.0833 24.5834V19.0834C19.0833 18.8403 18.9868 18.6071 18.8148 18.4352C18.6429 18.2633 18.4098 18.1667 18.1667 18.1667C17.9236 18.1667 17.6904 18.2633 17.5185 18.4352C17.3466 18.6071 17.25 18.8403 17.25 19.0834V24.5834C17.25 24.8265 17.3466 25.0596 17.5185 25.2316C17.6904 25.4035 17.9236 25.5 18.1667 25.5ZM27.3333 14.5H23.6667V13.5834C23.6667 12.854 23.3769 12.1546 22.8612 11.6388C22.3455 11.1231 21.646 10.8334 20.9167 10.8334H19.0833C18.354 10.8334 17.6545 11.1231 17.1388 11.6388C16.6231 12.1546 16.3333 12.854 16.3333 13.5834V14.5H12.6667C12.4236 14.5 12.1904 14.5966 12.0185 14.7685C11.8466 14.9404 11.75 15.1736 11.75 15.4167C11.75 15.6598 11.8466 15.893 12.0185 16.0649C12.1904 16.2368 12.4236 16.3334 12.6667 16.3334H13.5833V26.4167C13.5833 27.1461 13.8731 27.8455 14.3888 28.3612C14.9045 28.877 15.604 29.1667 16.3333 29.1667H23.6667C24.396 29.1667 25.0955 28.877 25.6112 28.3612C26.1269 27.8455 26.4167 27.1461 26.4167 26.4167V16.3334H27.3333C27.5764 16.3334 27.8096 16.2368 27.9815 16.0649C28.1534 15.893 28.25 15.6598 28.25 15.4167C28.25 15.1736 28.1534 14.9404 27.9815 14.7685C27.8096 14.5966 27.5764 14.5 27.3333 14.5ZM18.1667 13.5834C18.1667 13.3403 18.2632 13.1071 18.4352 12.9352C18.6071 12.7633 18.8402 12.6667 19.0833 12.6667H20.9167C21.1598 12.6667 21.3929 12.7633 21.5648 12.9352C21.7368 13.1071 21.8333 13.3403 21.8333 13.5834V14.5H18.1667V13.5834ZM24.5833 26.4167C24.5833 26.6598 24.4868 26.893 24.3148 27.0649C24.1429 27.2368 23.9098 27.3334 23.6667 27.3334H16.3333C16.0902 27.3334 15.8571 27.2368 15.6852 27.0649C15.5132 26.893 15.4167 26.6598 15.4167 26.4167V16.3334H24.5833V26.4167ZM21.8333 25.5C22.0764 25.5 22.3096 25.4035 22.4815 25.2316C22.6534 25.0596 22.75 24.8265 22.75 24.5834V19.0834C22.75 18.8403 22.6534 18.6071 22.4815 18.4352C22.3096 18.2633 22.0764 18.1667 21.8333 18.1667C21.5902 18.1667 21.3571 18.2633 21.1852 18.4352C21.0132 18.6071 20.9167 18.8403 20.9167 19.0834V24.5834C20.9167 24.8265 21.0132 25.0596 21.1852 25.2316C21.3571 25.4035 21.5902 25.5 21.8333 25.5Z" fill="var(--primary)" />
                                                    </svg>
                                                </a>
                                                <div class="email-tools-box">
                                                    <i class="fa-solid fa-list-ul"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- tab-content -->
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <p class="text-center wrong-msg msg"></p>
                                            <div class="email-list dz-scroll" id="emails">

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <div class="email-list dz-scroll" id="emails1">
                                                <div class="message">
                                                    <div>
                                                        <div class="d-flex message-single">
                                                            <div class="ps-1 align-self-center">
                                                                <div class="form-check custom-checkbox">
                                                                    <input type="checkbox" class="form-check-input" id="checkbox2">
                                                                    <label class="form-check-label" for="checkbox2"></label>
                                                                </div>
                                                            </div>
                                                            <div class="ms-2">
                                                                <label class="bookmark-btn"><input type="checkbox"><span class="checkmark"></span></label>
                                                            </div>
                                                        </div>
                                                        <a href="email-read.html" class="col-mail col-mail-2">
                                                            <div class="hader">UI Design Beginner</div>
                                                            <div class="subject">working time in this pandemic<span> Lorem ipsum dolor sit amet, consectetur adipiscing elit</span></div>
                                                            <div class="date">11:43 AM</div>
                                                        </a>
                                                        <div class="icon">
                                                            <a href="javascript:void(0);"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.16683 12.8333H12.8335C13.0766 12.8333 13.3098 12.7368 13.4817 12.5648C13.6536 12.3929 13.7502 12.1598 13.7502 11.9167C13.7502 11.6736 13.6536 11.4404 13.4817 11.2685C13.3098 11.0966 13.0766 11 12.8335 11H9.16683C8.92371 11 8.69056 11.0966 8.51865 11.2685C8.34674 11.4404 8.25016 11.6736 8.25016 11.9167C8.25016 12.1598 8.34674 12.3929 8.51865 12.5648C8.69056 12.7368 8.92371 12.8333 9.16683 12.8333V12.8333ZM17.4168 2.75H4.5835C3.85415 2.75 3.15468 3.03973 2.63895 3.55546C2.12323 4.07118 1.8335 4.77065 1.8335 5.5V8.25C1.8335 8.49312 1.93007 8.72627 2.10198 8.89818C2.27389 9.07009 2.50705 9.16667 2.75016 9.16667H3.66683V16.5C3.66683 17.2293 3.95656 17.9288 4.47229 18.4445C4.98801 18.9603 5.68748 19.25 6.41683 19.25H15.5835C16.3128 19.25 17.0123 18.9603 17.528 18.4445C18.0438 17.9288 18.3335 17.2293 18.3335 16.5V9.16667H19.2502C19.4933 9.16667 19.7264 9.07009 19.8983 8.89818C20.0703 8.72627 20.1668 8.49312 20.1668 8.25V5.5C20.1668 4.77065 19.8771 4.07118 19.3614 3.55546C18.8456 3.03973 18.1462 2.75 17.4168 2.75ZM16.5002 16.5C16.5002 16.7431 16.4036 16.9763 16.2317 17.1482C16.0598 17.3201 15.8266 17.4167 15.5835 17.4167H6.41683C6.17371 17.4167 5.94056 17.3201 5.76865 17.1482C5.59674 16.9763 5.50016 16.7431 5.50016 16.5V9.16667H16.5002V16.5ZM18.3335 7.33333H3.66683V5.5C3.66683 5.25688 3.76341 5.02373 3.93531 4.85182C4.10722 4.67991 4.34038 4.58333 4.5835 4.58333H17.4168C17.6599 4.58333 17.8931 4.67991 18.065 4.85182C18.2369 5.02373 18.3335 5.25688 18.3335 5.5V7.33333Z" fill="#666666" />
                                                                </svg>
                                                            </a>
                                                            <a href="javascript:void(0);" class="ms-2"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M11.0002 5.5C10.8798 5.49997 10.7606 5.52366 10.6493 5.56972C10.5381 5.61577 10.437 5.68329 10.3519 5.76842C10.2668 5.85354 10.1993 5.95461 10.1532 6.06584C10.1072 6.17707 10.0835 6.29628 10.0835 6.41667V10.4351L7.85539 11.5564C7.74789 11.6104 7.65209 11.6852 7.57346 11.7763C7.49483 11.8674 7.43491 11.973 7.39713 12.0873C7.35935 12.2016 7.34444 12.3221 7.35326 12.4422C7.36208 12.5622 7.39445 12.6793 7.44853 12.7868C7.5026 12.8943 7.57732 12.9901 7.66842 13.0687C7.75953 13.1473 7.86522 13.2073 7.97948 13.245C8.09373 13.2828 8.21431 13.2977 8.33433 13.2889C8.45434 13.2801 8.57145 13.2477 8.67896 13.1936L11.412 11.8186C11.5638 11.7425 11.6914 11.6255 11.7806 11.481C11.8697 11.3364 11.9169 11.1699 11.9168 11V6.41667C11.9169 6.29628 11.8932 6.17707 11.8471 6.06584C11.8011 5.95461 11.7335 5.85354 11.6484 5.76842C11.5633 5.68329 11.4622 5.61577 11.351 5.56972C11.2398 5.52366 11.1206 5.49997 11.0002 5.5V5.5ZM11.0002 1.83334C9.18717 1.83334 7.41489 2.37095 5.90744 3.3782C4.39999 4.38544 3.22507 5.81708 2.53127 7.49207C1.83747 9.16706 1.65594 11.0102 2.00964 12.7883C2.36333 14.5665 3.23637 16.1998 4.51835 17.4818C5.80034 18.7638 7.43368 19.6368 9.21184 19.9905C10.99 20.3442 12.8331 20.1627 14.5081 19.4689C16.1831 18.7751 17.6147 17.6002 18.622 16.0927C19.6292 14.5853 20.1668 12.813 20.1668 11C20.1641 8.5697 19.1974 6.23974 17.4789 4.52126C15.7604 2.80278 13.4305 1.83612 11.0002 1.83334V1.83334ZM11.0002 18.3333C9.54977 18.3333 8.13195 17.9032 6.92598 17.0974C5.72002 16.2916 4.78009 15.1463 4.22505 13.8063C3.67001 12.4664 3.52478 10.9919 3.80774 9.56934C4.0907 8.14681 4.78913 6.84014 5.81472 5.81455C6.8403 4.78897 8.14698 4.09054 9.5695 3.80758C10.992 3.52462 12.4665 3.66984 13.8065 4.22489C15.1465 4.77993 16.2918 5.71986 17.0976 6.92582C17.9034 8.13178 18.3335 9.54961 18.3335 11C18.3313 12.9442 17.5579 14.8082 16.1832 16.183C14.8084 17.5578 12.9444 18.3311 11.0002 18.3333V18.3333Z" fill="#666666" />
                                                                </svg>
                                                            </a>
                                                            <a href="javascript:void(0);" class="ms-2"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.16667 16.5C9.40978 16.5 9.64294 16.4035 9.81485 16.2316C9.98676 16.0596 10.0833 15.8265 10.0833 15.5834V10.0834C10.0833 9.84026 9.98676 9.6071 9.81485 9.43519C9.64294 9.26328 9.40978 9.16671 9.16667 9.16671C8.92355 9.16671 8.69039 9.26328 8.51849 9.43519C8.34658 9.6071 8.25 9.84026 8.25 10.0834V15.5834C8.25 15.8265 8.34658 16.0596 8.51849 16.2316C8.69039 16.4035 8.92355 16.5 9.16667 16.5ZM18.3333 5.50004H14.6667V4.58337C14.6667 3.85403 14.3769 3.15456 13.8612 2.63883C13.3455 2.12311 12.646 1.83337 11.9167 1.83337H10.0833C9.35399 1.83337 8.65451 2.12311 8.13879 2.63883C7.62306 3.15456 7.33333 3.85403 7.33333 4.58337V5.50004H3.66667C3.42355 5.50004 3.19039 5.59662 3.01849 5.76853C2.84658 5.94043 2.75 6.17359 2.75 6.41671C2.75 6.65982 2.84658 6.89298 3.01849 7.06489C3.19039 7.2368 3.42355 7.33337 3.66667 7.33337H4.58333V17.4167C4.58333 18.1461 4.87306 18.8455 5.38879 19.3613C5.90451 19.877 6.60399 20.1667 7.33333 20.1667H14.6667C15.396 20.1667 16.0955 19.877 16.6112 19.3613C17.1269 18.8455 17.4167 18.1461 17.4167 17.4167V7.33337H18.3333C18.5764 7.33337 18.8096 7.2368 18.9815 7.06489C19.1534 6.89298 19.25 6.65982 19.25 6.41671C19.25 6.17359 19.1534 5.94043 18.9815 5.76853C18.8096 5.59662 18.5764 5.50004 18.3333 5.50004ZM9.16667 4.58337C9.16667 4.34026 9.26324 4.1071 9.43515 3.93519C9.60706 3.76328 9.84022 3.66671 10.0833 3.66671H11.9167C12.1598 3.66671 12.3929 3.76328 12.5648 3.93519C12.7368 4.1071 12.8333 4.34026 12.8333 4.58337V5.50004H9.16667V4.58337ZM15.5833 17.4167C15.5833 17.6598 15.4868 17.893 15.3148 18.0649C15.1429 18.2368 14.9098 18.3334 14.6667 18.3334H7.33333C7.09022 18.3334 6.85706 18.2368 6.68515 18.0649C6.51324 17.893 6.41667 17.6598 6.41667 17.4167V7.33337H15.5833V17.4167ZM12.8333 16.5C13.0764 16.5 13.3096 16.4035 13.4815 16.2316C13.6534 16.0596 13.75 15.8265 13.75 15.5834V10.0834C13.75 9.84026 13.6534 9.6071 13.4815 9.43519C13.3096 9.26328 13.0764 9.16671 12.8333 9.16671C12.5902 9.16671 12.3571 9.26328 12.1852 9.43519C12.0132 9.6071 11.9167 9.84026 11.9167 10.0834V15.5834C11.9167 15.8265 12.0132 16.0596 12.1852 16.2316C12.3571 16.4035 12.5902 16.5 12.8333 16.5Z" fill="#666666" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                            <div class="email-list dz-scroll" id="emails2">
                                                <div class="message">
                                                    <div>
                                                        <div class="d-flex message-single">
                                                            <div class="ps-1 align-self-center">
                                                                <div class="form-check custom-checkbox">
                                                                    <input type="checkbox" class="form-check-input" id="checkbox24">
                                                                    <label class="form-check-label" for="checkbox2"></label>
                                                                </div>
                                                            </div>
                                                            <div class="ms-2">
                                                                <label class="bookmark-btn"><input type="checkbox"><span class="checkmark"></span></label>
                                                            </div>
                                                        </div>
                                                        <a href="email-read.html" class="col-mail col-mail-2">
                                                            <div class="hader">UI Design Beginner</div>
                                                            <div class="subject">working time in this pandemic<span> Lorem ipsum dolor sit amet, consectetur adipiscing elit</span></div>
                                                            <div class="date">11:43 AM</div>
                                                        </a>
                                                        <div class="icon">
                                                            <a href="javascript:void(0);"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.16683 12.8333H12.8335C13.0766 12.8333 13.3098 12.7368 13.4817 12.5648C13.6536 12.3929 13.7502 12.1598 13.7502 11.9167C13.7502 11.6736 13.6536 11.4404 13.4817 11.2685C13.3098 11.0966 13.0766 11 12.8335 11H9.16683C8.92371 11 8.69056 11.0966 8.51865 11.2685C8.34674 11.4404 8.25016 11.6736 8.25016 11.9167C8.25016 12.1598 8.34674 12.3929 8.51865 12.5648C8.69056 12.7368 8.92371 12.8333 9.16683 12.8333V12.8333ZM17.4168 2.75H4.5835C3.85415 2.75 3.15468 3.03973 2.63895 3.55546C2.12323 4.07118 1.8335 4.77065 1.8335 5.5V8.25C1.8335 8.49312 1.93007 8.72627 2.10198 8.89818C2.27389 9.07009 2.50705 9.16667 2.75016 9.16667H3.66683V16.5C3.66683 17.2293 3.95656 17.9288 4.47229 18.4445C4.98801 18.9603 5.68748 19.25 6.41683 19.25H15.5835C16.3128 19.25 17.0123 18.9603 17.528 18.4445C18.0438 17.9288 18.3335 17.2293 18.3335 16.5V9.16667H19.2502C19.4933 9.16667 19.7264 9.07009 19.8983 8.89818C20.0703 8.72627 20.1668 8.49312 20.1668 8.25V5.5C20.1668 4.77065 19.8771 4.07118 19.3614 3.55546C18.8456 3.03973 18.1462 2.75 17.4168 2.75ZM16.5002 16.5C16.5002 16.7431 16.4036 16.9763 16.2317 17.1482C16.0598 17.3201 15.8266 17.4167 15.5835 17.4167H6.41683C6.17371 17.4167 5.94056 17.3201 5.76865 17.1482C5.59674 16.9763 5.50016 16.7431 5.50016 16.5V9.16667H16.5002V16.5ZM18.3335 7.33333H3.66683V5.5C3.66683 5.25688 3.76341 5.02373 3.93531 4.85182C4.10722 4.67991 4.34038 4.58333 4.5835 4.58333H17.4168C17.6599 4.58333 17.8931 4.67991 18.065 4.85182C18.2369 5.02373 18.3335 5.25688 18.3335 5.5V7.33333Z" fill="#666666" />
                                                                </svg>
                                                            </a>
                                                            <a href="javascript:void(0);" class="ms-2"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M11.0002 5.5C10.8798 5.49997 10.7606 5.52366 10.6493 5.56972C10.5381 5.61577 10.437 5.68329 10.3519 5.76842C10.2668 5.85354 10.1993 5.95461 10.1532 6.06584C10.1072 6.17707 10.0835 6.29628 10.0835 6.41667V10.4351L7.85539 11.5564C7.74789 11.6104 7.65209 11.6852 7.57346 11.7763C7.49483 11.8674 7.43491 11.973 7.39713 12.0873C7.35935 12.2016 7.34444 12.3221 7.35326 12.4422C7.36208 12.5622 7.39445 12.6793 7.44853 12.7868C7.5026 12.8943 7.57732 12.9901 7.66842 13.0687C7.75953 13.1473 7.86522 13.2073 7.97948 13.245C8.09373 13.2828 8.21431 13.2977 8.33433 13.2889C8.45434 13.2801 8.57145 13.2477 8.67896 13.1936L11.412 11.8186C11.5638 11.7425 11.6914 11.6255 11.7806 11.481C11.8697 11.3364 11.9169 11.1699 11.9168 11V6.41667C11.9169 6.29628 11.8932 6.17707 11.8471 6.06584C11.8011 5.95461 11.7335 5.85354 11.6484 5.76842C11.5633 5.68329 11.4622 5.61577 11.351 5.56972C11.2398 5.52366 11.1206 5.49997 11.0002 5.5V5.5ZM11.0002 1.83334C9.18717 1.83334 7.41489 2.37095 5.90744 3.3782C4.39999 4.38544 3.22507 5.81708 2.53127 7.49207C1.83747 9.16706 1.65594 11.0102 2.00964 12.7883C2.36333 14.5665 3.23637 16.1998 4.51835 17.4818C5.80034 18.7638 7.43368 19.6368 9.21184 19.9905C10.99 20.3442 12.8331 20.1627 14.5081 19.4689C16.1831 18.7751 17.6147 17.6002 18.622 16.0927C19.6292 14.5853 20.1668 12.813 20.1668 11C20.1641 8.5697 19.1974 6.23974 17.4789 4.52126C15.7604 2.80278 13.4305 1.83612 11.0002 1.83334V1.83334ZM11.0002 18.3333C9.54977 18.3333 8.13195 17.9032 6.92598 17.0974C5.72002 16.2916 4.78009 15.1463 4.22505 13.8063C3.67001 12.4664 3.52478 10.9919 3.80774 9.56934C4.0907 8.14681 4.78913 6.84014 5.81472 5.81455C6.8403 4.78897 8.14698 4.09054 9.5695 3.80758C10.992 3.52462 12.4665 3.66984 13.8065 4.22489C15.1465 4.77993 16.2918 5.71986 17.0976 6.92582C17.9034 8.13178 18.3335 9.54961 18.3335 11C18.3313 12.9442 17.5579 14.8082 16.1832 16.183C14.8084 17.5578 12.9444 18.3311 11.0002 18.3333V18.3333Z" fill="#666666" />
                                                                </svg>
                                                            </a>
                                                            <a href="javascript:void(0);" class="ms-2"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.16667 16.5C9.40978 16.5 9.64294 16.4035 9.81485 16.2316C9.98676 16.0596 10.0833 15.8265 10.0833 15.5834V10.0834C10.0833 9.84026 9.98676 9.6071 9.81485 9.43519C9.64294 9.26328 9.40978 9.16671 9.16667 9.16671C8.92355 9.16671 8.69039 9.26328 8.51849 9.43519C8.34658 9.6071 8.25 9.84026 8.25 10.0834V15.5834C8.25 15.8265 8.34658 16.0596 8.51849 16.2316C8.69039 16.4035 8.92355 16.5 9.16667 16.5ZM18.3333 5.50004H14.6667V4.58337C14.6667 3.85403 14.3769 3.15456 13.8612 2.63883C13.3455 2.12311 12.646 1.83337 11.9167 1.83337H10.0833C9.35399 1.83337 8.65451 2.12311 8.13879 2.63883C7.62306 3.15456 7.33333 3.85403 7.33333 4.58337V5.50004H3.66667C3.42355 5.50004 3.19039 5.59662 3.01849 5.76853C2.84658 5.94043 2.75 6.17359 2.75 6.41671C2.75 6.65982 2.84658 6.89298 3.01849 7.06489C3.19039 7.2368 3.42355 7.33337 3.66667 7.33337H4.58333V17.4167C4.58333 18.1461 4.87306 18.8455 5.38879 19.3613C5.90451 19.877 6.60399 20.1667 7.33333 20.1667H14.6667C15.396 20.1667 16.0955 19.877 16.6112 19.3613C17.1269 18.8455 17.4167 18.1461 17.4167 17.4167V7.33337H18.3333C18.5764 7.33337 18.8096 7.2368 18.9815 7.06489C19.1534 6.89298 19.25 6.65982 19.25 6.41671C19.25 6.17359 19.1534 5.94043 18.9815 5.76853C18.8096 5.59662 18.5764 5.50004 18.3333 5.50004ZM9.16667 4.58337C9.16667 4.34026 9.26324 4.1071 9.43515 3.93519C9.60706 3.76328 9.84022 3.66671 10.0833 3.66671H11.9167C12.1598 3.66671 12.3929 3.76328 12.5648 3.93519C12.7368 4.1071 12.8333 4.34026 12.8333 4.58337V5.50004H9.16667V4.58337ZM15.5833 17.4167C15.5833 17.6598 15.4868 17.893 15.3148 18.0649C15.1429 18.2368 14.9098 18.3334 14.6667 18.3334H7.33333C7.09022 18.3334 6.85706 18.2368 6.68515 18.0649C6.51324 17.893 6.41667 17.6598 6.41667 17.4167V7.33337H15.5833V17.4167ZM12.8333 16.5C13.0764 16.5 13.3096 16.4035 13.4815 16.2316C13.6534 16.0596 13.75 15.8265 13.75 15.5834V10.0834C13.75 9.84026 13.6534 9.6071 13.4815 9.43519C13.3096 9.26328 13.0764 9.16671 12.8333 9.16671C12.5902 9.16671 12.3571 9.26328 12.1852 9.43519C12.0132 9.6071 11.9167 9.84026 11.9167 10.0834V15.5834C11.9167 15.8265 12.0132 16.0596 12.1852 16.2316C12.3571 16.4035 12.5902 16.5 12.8333 16.5Z" fill="#666666" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /tab-content -->
                                    <!-- panel -->
                                    <div class="row gx-0 ">
                                        <!-- column -->
                                        <div class="col-12 ps-3">
                                            <div class="pagination pagination-xs d-flex justify-content-between align-items-center">
                                                <p class="mb-0">Showing <span>1-5 </span>from <span>100</span>data</p>
                                                <nav>
                                                    <ul class="pagination pagination-gutter pagination-primary no-bg">
                                                        <li class="page-item page-indicator">
                                                            <a class="page-link" href="javascript:void(0)">
                                                                <i class="fa-solid fa-angle-left"></i></a>
                                                        </li>
                                                        <li class="page-item "><a class="page-link" href="javascript:void(0)">1</a>
                                                        </li>
                                                        <li class="page-item active"><a class="page-link" href="javascript:void(0)">2</a></li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
                                                        <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0)"><i class="fa-solid fa-angle-right"></i></a></li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                        <!-- /column -->
                                    </div>
                                    <!-- /panel -->
                                </div>
                            </div>

                            <!-- display a single email item -->
                            <div id="viewMailContent" style="display: none;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection(); ?>
<?= $this->section("scripts"); ?>
<script>

</script>
<script src="/assets/scripts/communications/emails.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<?= $this->endSection(); ?>