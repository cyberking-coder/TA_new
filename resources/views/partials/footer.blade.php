<footer class="site-footer">
    <div class="frame footer-grid">
        <nav class="footer-col" aria-label="Help">
            <h3>Help</h3>
            <a href="{{ url('/pages/contact-us') }}">Contact Us</a>
            <a href="{{ url('/pages/disclaimer') }}">Disclaimer</a>
            <a href="{{ url('/pages/privacy') }}">Privacy Policy</a>
            <a href="{{ url('/pages/terms') }}">Terms &amp; Conditions</a>
        </nav>

        <nav class="footer-col" aria-label="Orders">
            <h3>Orders</h3>
            <a href="{{ url('/pages/shipping-and-delivery') }}">Shipping &amp; Delivery</a>
            <a href="{{ url('/pages/about-us') }}">About Us</a>
            <a href="{{ url('/pages/refund-and-cancellation') }}">Refund &amp; Cancellation Policy</a>
        </nav>

    </div>

    <div class="frame footer-bottom">
        <small>Copyright &copy; {{ now()->year }} TERRITORIALARMY.IN INDIA. All rights reserved.</small>
        <small class="footer-note">Independent exam information &amp; preparation guide.</small>
    </div>
</footer>
