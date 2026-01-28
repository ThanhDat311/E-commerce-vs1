{{-- Inherit from master layout --}}
@extends('layouts.master')

{{-- Page Title --}}
@section('title', 'Help & Support')

{{-- Main Content --}}
@section('content')
{{-- 1. Outer container with light gray background for contrast (Same as Contact Page) --}}
<div class="container-fluid py-5 bg-light">
    <div class="container py-5">

        <div class="text-center mb-5">
            <h1 class="text-primary text-uppercase font-weight-bold">HELP & SUPPORT CENTER</h1>
            <p class="mb-4 text-muted" style="max-width: 600px; margin: 0 auto;">
                Find answers to common questions about your orders, accounts, and our services. We're here to help you have the best experience with Electro.
            </p>
        </div>

        {{-- Topic Cards --}}
        <div class="row px-xl-5 mb-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white shadow rounded p-4 text-center h-100 transition-hover pointer-cursor">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4" style="width: 60px; height: 60px;">
                        <i class="fa fa-box-open text-primary fa-lg"></i>
                    </div>
                    <h5 class="font-weight-bold">Orders</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white shadow rounded p-4 text-center h-100 transition-hover pointer-cursor">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4" style="width: 60px; height: 60px;">
                        <i class="fa fa-undo-alt text-primary fa-lg"></i>
                    </div>
                    <h5 class="font-weight-bold">Returns</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white shadow rounded p-4 text-center h-100 transition-hover pointer-cursor">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4" style="width: 60px; height: 60px;">
                        <i class="fa fa-credit-card text-primary fa-lg"></i>
                    </div>
                    <h5 class="font-weight-bold">Payment</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white shadow rounded p-4 text-center h-100 transition-hover pointer-cursor">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4" style="width: 60px; height: 60px;">
                        <i class="fa fa-user-circle text-primary fa-lg"></i>
                    </div>
                    <h5 class="font-weight-bold">Account</h5>
                </div>
            </div>
        </div>

        <div class="row px-xl-5">
            {{-- ================= FAQ ACCORDION SECTION ================= --}}
            <div class="col-lg-8 mb-5">
                <div class="bg-white shadow rounded p-4 p-sm-5 h-100">
                    <h4 class="font-weight-bold mb-4">Frequently Asked Questions</h4>

                    {{-- ID cha 'faqAccordion' để kích hoạt tính năng đóng mở tự động --}}
                    <div class="accordion" id="faqAccordion">

                        {{-- Question 1 --}}
                        <div class="card border-0 mb-3 bg-transparent">
                            <div class="card-header p-0 border-0 bg-white" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center py-3 px-0 text-dark text-decoration-none shadow-none"
                                        type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span class="font-weight-bold">How do I track my order?</span>
                                        {{-- Icon chevron sẽ xoay bằng CSS --}}
                                        <i class="fa fa-chevron-down text-primary transition-icon"></i>
                                    </button>
                                </h5>
                            </div>
                            {{-- show: mặc định mở cái đầu tiên --}}
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                                <div class="card-body pl-0 pt-0 pb-4 text-muted border-bottom">
                                    To track your order, go to "My Orders" in your account dashboard. Click on the specific order ID to view detailed shipping status. You will also receive email updates when the status changes.
                                </div>
                            </div>
                        </div>

                        {{-- Question 2 --}}
                        <div class="card border-0 mb-3 bg-transparent">
                            <div class="card-header p-0 border-0 bg-white" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center py-3 px-0 text-dark text-decoration-none shadow-none collapsed"
                                        type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <span class="font-weight-bold">What payment methods do you accept?</span>
                                        <i class="fa fa-chevron-down text-primary transition-icon"></i>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                                <div class="card-body pl-0 pt-0 pb-4 text-muted border-bottom">
                                    We accept various payment methods including Credit/Debit cards (Visa, Mastercard), VNPAY QR, and Cash on Delivery (COD) for eligible locations.
                                </div>
                            </div>
                        </div>

                        {{-- Question 3 --}}
                        <div class="card border-0 mb-3 bg-transparent">
                            <div class="card-header p-0 border-0 bg-white" id="headingThree">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center py-3 px-0 text-dark text-decoration-none shadow-none collapsed"
                                        type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <span class="font-weight-bold">What is your return policy?</span>
                                        <i class="fa fa-chevron-down text-primary transition-icon"></i>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                                <div class="card-body pl-0 pt-0 pb-4 text-muted border-bottom">
                                    We offer a 7-day return policy for defective items or incorrect shipments. The item must be unused and in its original packaging with all tags attached.
                                </div>
                            </div>
                        </div>

                        {{-- Question 4 --}}
                        <div class="card border-0 mb-0 bg-transparent">
                            <div class="card-header p-0 border-0 bg-white" id="headingFour">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center py-3 px-0 text-dark text-decoration-none shadow-none collapsed"
                                        type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <span class="font-weight-bold">How can I change my address?</span>
                                        <i class="fa fa-chevron-down text-primary transition-icon"></i>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#faqAccordion">
                                <div class="card-body pl-0 pt-0 pb-4 text-muted border-bottom">
                                    You can manage your addresses in the "Address Book" section of your profile. For orders already placed but not yet shipped, please contact us immediately.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Contact CTA --}}
            <div class="col-lg-4 mb-5">
                <div class="bg-white shadow rounded p-4 p-sm-5 h-100 text-center d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle mb-4" style="width: 80px; height: 80px;">
                            <i class="fa fa-headset fa-2x text-white"></i>
                        </div>
                        <h4 class="font-weight-bold">Still need help?</h4>
                        <p class="text-muted">Can't find the answer you're looking for?</p>
                    </div>

                    <a href="{{ route('contact.index') }}" class="btn btn-primary btn-block py-3 rounded shadow-sm mb-4">
                        Contact Us
                    </a>

                    <div class="text-left border-top pt-4">
                        <p class="mb-2 text-muted small"><i class="fa fa-phone-alt text-primary mr-2"></i> Hotline: <span class="text-dark font-weight-bold">1900 1234</span></p>
                        <p class="mb-0 text-muted small"><i class="fa fa-envelope text-primary mr-2"></i> Email: <span class="text-dark font-weight-bold">support@electro.com</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS TÙY CHỈNH CHO ACCORDION --}}
<style>
    /* Xử lý hiệu ứng xoay mũi tên */
    /* Trạng thái mở: Xoay 180 độ */
    .btn-link .transition-icon {
        transform: rotate(180deg);
        transition: transform 0.3s ease-in-out;
    }

    /* Trạng thái đóng (collapsed): Xoay về 0 độ */
    .btn-link.collapsed .transition-icon {
        transform: rotate(0deg);
    }

    /* Hiệu ứng hover nhẹ cho câu hỏi */
    .btn-link:hover {
        color: var(--primary) !important;
    }

    .btn-link:hover .text-dark {
        color: var(--primary) !important;
    }

    /* Tiện ích khác */
    .pointer-cursor {
        cursor: pointer;
    }

    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    /* Xóa outline xấu của bootstrap khi click */
    .btn-link:focus,
    .btn-link:active {
        text-decoration: none;
        box-shadow: none;
    }
</style>
@endsection