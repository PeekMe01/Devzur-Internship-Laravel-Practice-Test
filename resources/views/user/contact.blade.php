@extends('layouts.user')

@section('title', 'Contact Us')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('user/styles/contact_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('user/styles/contact_responsive.css') }}">
<div class="container contact_container">
    <div class="row">
        <div class="col">

            <!-- Breadcrumbs -->

            <div class="breadcrumbs d-flex flex-row align-items-center">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Contact</a></li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Contact Us -->

    <div class="row">

        <div class="col-lg-6 contact_col">
            <div class="contact_contents">
                <h1>Contact Us</h1>
                <p>There are many ways to contact us. You may drop us a line, give us a call or send an email, choose what suits you the most.</p>
                <div>
                    <p>(+961) 81 358 691</p>
                    <p>ralphdaher6@gmail.com</p>
                </div>
                <div>
                    <p>Open hours: 8.00-18.00 Mon-Sun</p>
                </div>
            </div>

            <!-- Follow Us -->

            <div class="follow_us_contents">
                <h1>Follow Us</h1>
                <ul class="social d-flex flex-row">
                    <li><a href="https://www.facebook.com/ralph.daher.5201/" style="background-color: #3a61c9"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li><a href="https://www.instagram.com/daher.ralph/" style="background-color: #fb4343"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                </ul>
            </div>

        </div>

        <div class="col-lg-6 get_in_touch_col">
            <div class="get_in_touch_contents">
                <h1>Get In Touch With Us!</h1>
                <p>Fill out the form below to recieve a free and confidential.</p>
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div>
                        <input id="input_name" class="form_input input_name input_ph" type="text" name="name" placeholder="Name" required>
                        <input id="input_email" class="form_input input_email input_ph" type="email" name="email" placeholder="Email" required>
                        <textarea id="input_message" class="input_ph input_message" name="message" placeholder="Message" rows="3" required></textarea>
                    </div>
                    <div>
                        <button id="review_submit" type="submit" class="red_button message_submit_btn trans_300">Send Message</button>
                    </div>
                </form>             
            </div>
        </div>

    </div>
</div>
<script src="{{ asset('user/js/single_custom.js') }}"></script>
<script src="{{ asset('user/js/contact_custom.js') }}"></script>
@endsection