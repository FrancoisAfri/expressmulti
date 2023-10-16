<!-- partial:partials/_footer.html -->
<footer class="footer d-flex flex-column justify-content-between">

{{--        <p style="text-align:left; class=" text-muted text-center text-md-left"> Version 1.0.5.3--}}
<div class="display: inline-block">
    <p style="text-align:center; class=" text-muted text-center text-md-left">&copy; Copyright
    &copy; {{ (date("Y") - 2016 > 0) ? '2016 - ' . date("Y") : date("Y") }} <a href="https://mkhayamk.co.za/"
                                                                               target="_blank">Mkhaya Mk</a>. All rights
    reserved</p>

    <h4 style="text-align:right; class="text-muted text-center text-md-left"> Powered by
    <span class="logo-lg">
            <img src=" {{ asset('images/logo_default.png') }}" alt="" height="30">
        </span>
</div>


</footer>
<!-- partial -->



