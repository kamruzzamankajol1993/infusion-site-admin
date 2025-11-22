@extends('front.master.master')

@section('title')
OPTIFUSION INC - Your One-Stop Solution
@endsection


@section('css')
@endsection


@section('body')
  <!-- Hero Carousel Section -->
  @include('front.include.mainSlider')

    <!-- Featured Categories Section -->
   <section id="at-a-glance" class="section-padding bg-dark-blue text-white text-center">
    <div class="container">
        <p class="subtitle text-primary mb-2">Digital Care, Inc.</p>
        <h2 class="section-title text-white mb-5">At A Glance</h2>
        <div class="row justify-content-center g-4">
            
            <div class="col-lg-2 col-md-4 col-4">
                <div class="glance-card">
                    <div class="glance-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-handshake"><path d="M11 11.001V3.001c0-1.104-.896-2-2-2-.58 0-1.116.25-1.48.67L1 6.001v6c0 1.104.896 2 2 2h3.001v8.001h8v-8c0-1.104-.896-2-2-2h-3v-3h3z"></path><path d="M22.001 8.001L18 4.001"></path><path d="M22.001 12.001L18 8.001"></path><path d="M22.001 16.001L18 12.001"></path></svg>
                    </div>
                    <div class="glance-number" data-target="120">0</div>
                    <p class="glance-text">Projects</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-4">
                <div class="glance-card">
                    <div class="glance-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><path d="M12.89 1.45l8 4a2 2 0 0 1 1.11 1.74v9.87a2 2 0 0 1-1.11 1.74l-8 4a2 2 0 0 1-1.78 0l-8-4a2 2 0 0 1-1.11-1.74V7.19a2 2 0 0 1 1.11-1.74l8-4a2 2 0 0 1 1.78 0z"></path><polyline points="2.32 6.16 12 11.84 21.68 6.16"></polyline><line x1="12" y1="22.76" x2="12" y2="11.84"></line><line x1="7" y1="3.5" x2="17" y2="8.5"></line></svg>
                    </div>
                    <div class="glance-number" data-target="40">0</div>
                    <p class="glance-text">Products</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-4">
                <div class="glance-card">
                    <div class="glance-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="glance-number" data-target="30">0</div>
                    <p class="glance-text">Experts</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-4">
                <div class="glance-card">
                    <div class="glance-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </div>
                    <div class="glance-number" data-target="20">0</div>
                    <p class="glance-text">Countries</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-4">
                <div class="glance-card">
                    <div class="glance-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                    </div>
                    <div class="glance-number" data-target="9000">0</div>
                    <p class="glance-text">Happy Clients</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-4">
                <div class="glance-card">
                    <div class="glance-icon">
                        <svg xmlns="http://www.w3.org/2S3 0 0 0 10.364 8.273l-2.091-2.091a1 1 0 0 0-1.414 1.414l2.828 2.828a1 1 0 0 0 1.414 0l6.364-6.364a1 1 0 0 0-1.414-1.414z"/></svg>
                    </div>
                    <div class="glance-number" data-target="9">0</div>
                    <p class="glance-text">Yrs Experienced</p>
                </div>
            </div>
        </div>
    </div>
</section>
    
    <!-- New Arrivals Section -->
    <section id="our-solutions" class="section-padding bg-dark-blue text-white">
    <div class="container text-center">
        <p class="subtitle text-primary mb-2">What We Do...</p>
        <h2 class="section-title text-white mb-5">OUR SOLUTIONS</h2>
        <p class="section-description lead text-white-50 mb-5 max-w-700 mx-auto">
            We Provide A Time-Worthy Business Solution To Every Type Of Business. Find Out
            Your One And Level Up Your Success Stairs.
        </p>

        <div class="row g-4 solutions-grid">
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:cellphone-link.svg?color=%23FFFFFF" alt="Digital Marketing" class="solution-icon">
                    <h5 class="solution-title">Digital Marketing</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:pencil-ruler.svg?color=%23FFFFFF" alt="Design Solution" class="solution-icon">
                    <h5 class="solution-title">Design Solution</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:file-document-multiple-outline.svg?color=%23FFFFFF" alt="Content Solution" class="solution-icon">
                    <h5 class="solution-title">Content Solution</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:code-tags.svg?color=%23FFFFFF" alt="Web Solution" class="solution-icon">
                    <h5 class="solution-title">Web Solution</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:account-group-outline.svg?color=%23FFFFFF" alt="Marketing Consultancy" class="solution-icon">
                    <h5 class="solution-title">Marketing Consultancy</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:database-search-outline.svg?color=%23FFFFFF" alt="SEO" class="solution-icon">
                    <h5 class="solution-title">SEO</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:video-vintage.svg?color=%23FFFFFF" alt="Video Production" class="solution-icon">
                    <h5 class="solution-title">Video Production</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 solutions-item">
                <a href="#" class="solution-card">
                    <img src="https://api.iconify.design/mdi:animation.svg?color=%23FFFFFF" alt="Animation" class="solution-icon">
                    <h5 class="solution-title">Animation</h5>
                </a>
            </div>
        </div>
    </div>
</section>

  <section id="global-clients" class="section-padding bg-dark-blue text-white">
        <div class="container text-center">
            <p class="subtitle text-primary mb-2">Digital Care, Inc.</p>
            <h2 class="section-title text-white mb-5">Our Global Clients</h2>

            <div class="map-container mb-5">
<img src="{{ asset('/') }}public/front/world.svg" alt="World Map" class="world-map-svg">                
                <span class="map-flag" style="top: 35%; left: 20%;">
                    <img src="https://api.iconify.design/circle-flags:us.svg?color=%230098de" alt="USA Flag">
                </span>
                <span class="map-flag" style="top: 32%; left: 47%;">
                    <img src="https://api.iconify.design/circle-flags:es.svg?color=%230098de" alt="Spain Flag">
                </span>
                <span class="map-flag" style="top: 60%; left: 30%;">
                    <img src="https://api.iconify.design/circle-flags:br.svg?color=%230098de" alt="Brazil Flag">
                </span>
                <span class="map-flag" style="top: 40%; left: 75%;">
                    <img src="https://api.iconify.design/circle-flags:in.svg?color=%230098de" alt="India Flag">
                </span>
                <span class="map-flag" style="top: 70%; left: 88%;">
                    <img src="https://api.iconify.design/circle-flags:au.svg?color=%230098de" alt="Australia Flag">
                </span>
            </div>

            <div class="swiper client-logo-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="client-logo-card">
                            <img src="https://placehold.co/150x60/cccccc/ffffff?text=Client+1" alt="Client Logo 1">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="client-logo-card">
                            <img src="https://placehold.co/150x60/cccccc/ffffff?text=Client+2" alt="Client Logo 2">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="client-logo-card">
                            <img src="https://placehold.co/150x60/cccccc/ffffff?text=Client+3" alt="Client Logo 3">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="client-logo-card">
                            <img src="https://placehold.co/150x60/cccccc/ffffff?text=Client+4" alt="Client Logo 4">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="client-logo-card">
                            <img src="https://placehold.co/150x60/cccccc/ffffff?text=Client+5" alt="Client Logo 5">
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="client-logo-card">
                            <img src="https://placehold.co/150x60/cccccc/ffffff?text=Client+6" alt="Client Logo 6">
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next client-swiper-nav"></div>
                <div class="swiper-button-prev client-swiper-nav"></div>
            </div>

        </div>
    </section>
    
   <section id="why-us" class="section-padding bg-dark-blue text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <p class="subtitle text-primary mb-2">Why Us..?</p>
                <h2 class="section-title text-white mb-3">Digital Care Benefits</h2>
                <p class="section-description lead text-white-50 mb-5">
                    We Provide A Time-Worthy Solution To Every Type Of Brand Or Business. Find Out
                    Your One And Level Up Your Success Stairs.
                </p>
            </div>
        </div>

        <div class="row benefits-grid">
            <div class="col-lg-4 col-md-6 benefits-item">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <img src="https://api.iconify.design/mdi:truck-fast-outline.svg?color=white" alt="Timely Delivery">
                    </div>
                    <h5 class="benefit-title">Timely Delivery</h5>
                    <p class="benefit-description">
                        You Get Every Service According To Our Policy And Within The
                        Timeframe & Fast Service From Us.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 benefits-item">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <img src="https://api.iconify.design/mdi:face-agent.svg?color=white" alt="Professional Support">
                    </div>
                    <h5 class="benefit-title">Professional Support</h5>
                    <p class="benefit-description">
                        For Any Issues Related To Our Services, We Provide 16/7
                        Professional Support.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 benefits-item">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <img src="https://api.iconify.design/mdi:account-group-outline.svg?color=white" alt="Expert Team">
                    </div>
                    <h5 class="benefit-title">Expert Team</h5>
                    <p class="benefit-description">
                        Our Services Are Always Provided By Expert Team Members
                        According To Your Needs.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 benefits-item">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <img src="https://api.iconify.design/mdi:shield-check-outline.svg?color=white" alt="Trusted & Secure">
                    </div>
                    <h5 class="benefit-title">Trusted & Secure</h5>
                    <p class="benefit-description">
                        We Always Provide Trusted And Satisfying Services, Which
                        Maintains Our Company’s Reputation.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 benefits-item">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <img src="https://api.iconify.design/mdi:star-circle-outline.svg?color=white" alt="Client Satisfaction">
                    </div>
                    <h5 class="benefit-title">Client Setisfication</h5>
                    <p class="benefit-description">
                        Our Main Goal Is To Satisfy Our Clients Therefore, You Will Also
                        Receive Satisfying Service From Us.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 benefits-item">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <img src="https://api.iconify.design/mdi:certificate-outline.svg?color=white" alt="Valid & Certified">
                    </div>
                    <h5 class="benefit-title">Valid & Certified</h5>
                    <p class="benefit-description">
                        Because Our Agency Is Valid, Government Authorized And
                        Certified. It’s Easier For Us To Provide Services.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('script')
@endsection