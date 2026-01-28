{{-- Inherit from master layout --}}
@extends('layouts.master')

{{-- Page Title --}}
@section('title', 'Contact Us')

{{-- Main Content --}}
@section('content')
{{-- Use bg-light for the outer container to create contrast --}}
<div class="container-fluid py-5 bg-light">
    <div class="container py-5">
        {{-- Header Section --}}
        <div class="text-center mb-5">
            <h1 class="text-primary text-uppercase font-weight-bold">Contact Us</h1>
            <p class="mb-4 text-muted" style="max-width: 600px; margin: 0 auto;">
                Do you have questions about our products, orders, or need technical support? Please fill out the form below, and we will respond as soon as possible.
            </p>
        </div>

        <div class="row px-xl-5">
            {{-- ================= LEFT COLUMN: CONTACT FORM ================= --}}
            <div class="col-lg-7 mb-5">
                {{-- Card style: bg-white, shadow, rounded --}}
                <div class="contact-form bg-white shadow rounded p-4 p-sm-5 h-100">
                    <h4 class="font-weight-bold mb-4">Send us a message</h4>
                    <div id="success">
                        @if (session('success'))
                        <div class="alert alert-success mb-3 rounded">
                            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('contact.store') }}" method="POST" novalidate="novalidate">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="control-group mb-3">
                                    <label for="name" class="small text-muted mb-1">Full Name *</label>
                                    <input type="text" class="form-control rounded" name="name" id="name"
                                        required="required" value="{{ old('name') }}" placeholder="Your Name" />
                                    @error('name') <p class="help-block text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="control-group mb-3">
                                    <label for="email" class="small text-muted mb-1">Email Address *</label>
                                    <input type="email" class="form-control rounded" name="email" id="email"
                                        required="required" value="{{ old('email') }}" placeholder="Your Email" />
                                    @error('email') <p class="help-block text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="control-group mb-3">
                            <label for="subject" class="small text-muted mb-1">Subject *</label>
                            <input type="text" class="form-control rounded" name="subject" id="subject"
                                required="required" value="{{ old('subject') }}" placeholder="Subject" />
                            @error('subject') <p class="help-block text-danger small mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="control-group mb-4">
                            <label for="message" class="small text-muted mb-1">Message *</label>
                            <textarea class="form-control rounded" rows="6" name="message" id="message"
                                required="required" placeholder="How can we help you?">{{ old('message') }}</textarea>
                            @error('message') <p class="help-block text-danger small mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <button class="btn btn-primary py-2 px-4 rounded shadow-sm" type="submit" id="sendMessageButton">
                                Send Message <i class="fa fa-paper-plane ml-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================= RIGHT COLUMN: INFO & HOURS ================= --}}
            <div class="col-lg-5 mb-5">
                {{-- Combined Info Card --}}
                <div class="bg-white shadow rounded p-4 p-sm-5 h-100 d-flex flex-column justify-content-center">

                    {{-- Contact Info --}}
                    <div class="mb-5">
                        <h5 class="text-primary text-uppercase font-weight-bold mb-4">
                            <i class="fa fa-info-circle mr-2"></i>Contact Information
                        </h5>
                        <p class="mb-4 text-muted">We are always ready to listen to your feedback and answer your questions.</p>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-map-marker-alt text-primary mr-3 fa-lg"></i>
                            <span>12th Floor, Electro Bldg, District 1, HCMC</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-envelope text-primary mr-3 fa-lg"></i>
                            <span>support@electro.com</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-phone-alt text-primary mr-3 fa-lg"></i>
                            <span>+84 1900 1234</span>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <hr class="my-4">

                    {{-- Working Hours --}}
                    <div>
                        <h5 class="text-primary text-uppercase font-weight-bold mb-4">
                            <i class="fa fa-clock mr-2"></i>Working Hours
                        </h5>
                        <ul class="list-unstyled mb-0 text-muted">
                            <li class="d-flex justify-content-between mb-2 border-bottom pb-2">
                                <span>Mon - Fri:</span>
                                <span class="font-weight-bold text-dark">8:00 AM - 6:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2 border-bottom pb-2">
                                <span>Saturday:</span>
                                <span class="font-weight-bold text-dark">8:00 AM - 12:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between text-danger font-weight-bold">
                                <span>Sunday:</span>
                                <span>Closed</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection