<footer class="footer">
    <div class="container-fluid">
        <!-- <nav class="pull-left">
            <ul>
                <li>
                    <a href="#">
                        Home
                    </a>
                </li>
                <li>
                    <a href="#">
                        Company
                    </a>
                </li>
                <li>
                    <a href="#">
                        Portfolio
                    </a>
                </li>
                <li>
                    <a href="#">
                       Blog
                    </a>
                </li>
            </ul>
        </nav> -->
        <!-- <p class="copyright pull-right">
            &copy;
            <script>document.write(new Date().getFullYear())</script>
            <a href="http://www.creative-tim.com">Creative Tim</a>, made with love for a better web
        </p> -->
    </div>
</footer>
</div>
</div>

</body>

<!--   Core JS Files   -->
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/material.min.js') }}" type="text/javascript"></script>

<!--  Notifications Plugin    -->
<script src="{{ asset('js/bootstrap-notify.js') }}"></script>

<!-- Material Dashboard javascript methods -->
<script src="{{ asset('js/material-dashboard.js') }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('js/sweetalert.min.js') }}"></script>

{{--DateTimePicker--}}
<script type="text/javascript" src="{{ asset('js/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
})
</script>
@stack('js')

</html>
