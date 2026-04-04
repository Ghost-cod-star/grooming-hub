<?php
// Footer (includes/footer.php)
?>
<footer>
  <div class="container" style="display:flex;flex-wrap:wrap;gap:24px;align-items:flex-start;justify-content:space-between;">
    <div style="max-width:380px;">
      <h3 style="color:var(--gold);margin-bottom:8px;">The Grooming Hub</h3>
      <p style="color:var(--muted);font-size:14px;line-height:1.5;">
        Premium men's grooming essentials – crafted to elevate your daily ritual. Delivered in Kenya and beyond.
      </p>
      <div class="footer-social" style="margin-top:12px;">
        <a href="#" title="Instagram">@GroomingHubOfficial</a>
        <a href="#" title="Facebook">/GroomingHubKE</a>
        <a href="#" title="X">@GroomingHub</a>
      </div>
    </div>

    <div style="min-width:220px;">
      <h4 style="color:var(--gold);margin-bottom:8px;">Quick Links</h4>
      <nav style="display:flex;flex-direction:column;gap:6px;">
        <a href="/grooming-hub/public/index.php">Home</a>
        <a href="/grooming-hub/public/products.php">Products</a>
        <a href="/grooming-hub/public/about.php">About</a>
        <a href="/grooming-hub/public/contact.php">Contact</a>
      </nav>
    </div>

    <div style="min-width:260px;">
      <h4 style="color:var(--gold);margin-bottom:8px;">Join The Brotherhood</h4>
      <p style="color:var(--muted);font-size:14px;margin-bottom:8px;">Newsletter (placeholder) – we won't send yet; for demo only.</p>
      <form method="post" action="#" style="display:flex;gap:8px;">
        <input type="email" name="newsletter_email" placeholder="you@domain.com" style="flex:1;padding:8px;border-radius:6px;border:1px solid #333;background:#0b0b0b;color:var(--text)" />
        <button class="button" type="submit">Join</button>
      </form>
    </div>
  </div>

  <div style="margin-top:18px;border-top:1px solid rgba(255,255,255,0.04);padding-top:14px;">
    <p style="color:#9b9b9b;font-size:13px;">&copy; <?php echo date('Y'); ?> The Grooming Hub. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
