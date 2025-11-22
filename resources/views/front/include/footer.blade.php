<footer class="footer section-padding pb-4">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <a href="{{ url('/') }}" class="footer-logo mb-3">
                    <img src="{{ asset($rectangular_logo) }}" alt="{{ $ins_name }}">
                </a>
                <p class="footer-about-text">
                   {{ $description }}
                </p>
                
                <div class="social-icons mt-4">
                    @if(isset($socialLinks) && $socialLinks->count() > 0)
                        @foreach($socialLinks as $link)
                            <a href="{{ $link->link }}" target="_blank" title="{{ $link->title }}">
                                {{-- 
                                    Ideally, your SocialLink model should have an 'icon' field or logic to map titles to icons.
                                    For now, we use a generic icon or try to map based on title.
                                --}}
                                @php
                                    $iconUrl = 'https://api.iconify.design/mdi:web.svg?color=white';
                                    $titleLower = strtolower($link->title);
                                    if(str_contains($titleLower, 'facebook')) $iconUrl = 'https://api.iconify.design/mdi:facebook.svg?color=white';
                                    elseif(str_contains($titleLower, 'twitter')) $iconUrl = 'https://api.iconify.design/mdi:twitter.svg?color=white';
                                    elseif(str_contains($titleLower, 'instagram')) $iconUrl = 'https://api.iconify.design/mdi:instagram.svg?color=white';
                                    elseif(str_contains($titleLower, 'linkedin')) $iconUrl = 'https://api.iconify.design/mdi:linkedin.svg?color=white';
                                    elseif(str_contains($titleLower, 'youtube')) $iconUrl = 'https://api.iconify.design/mdi:youtube.svg?color=white';
                                @endphp
                                <img src="{{ $iconUrl }}" class="footer-social-icon" alt="{{ $link->title }}">
                            </a>
                        @endforeach
                    @else
                         <span class="text-white-50">Follow us on social media</span>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">Contact Info</h5>
                <ul class="list-unstyled footer-contact-list">
                    <li>
                        <img src="https://api.iconify.design/mdi:map-marker-outline.svg?color=white" class="footer-contact-icon" alt=""> 
                        {{ $ins_add }}
                    </li>
                    <li>
                        <img src="https://api.iconify.design/mdi:phone-outline.svg?color=white" class="footer-contact-icon" alt=""> 
                        {{ $ins_phone }}
                    </li>
                    <li>
                        <img src="https://api.iconify.design/mdi:email-outline.svg?color=white" class="footer-contact-icon" alt=""> 
                        {{ $ins_email }}
                    </li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Newsletter</h5>
                <p>Subscribe to our newsletter to get the latest updates and offers.</p>
                <form class="footer-newsletter-form">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your Email">
                        <button class="btn btn-primary" type="button">Go</button>
                    </div>
                </form>
            </div>
        </div>
        
        <hr class="footer-divider">
        <div class="text-center">
            <p class="footer-copyright mb-0">&copy; {{ date('Y') }} {{ $ins_name }}. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ... Your checkout form or content here ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>